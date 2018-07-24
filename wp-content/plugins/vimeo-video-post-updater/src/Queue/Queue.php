<?php
namespace VimeoUpdater\Queue;

use VimeoUpdater\Admin\Admin;
use VimeoUpdater\Admin\BulkUpdater;
use VimeoUpdater\VimeoUpdater;

class Queue {

	protected static $parentSlug = 'edit.php?post_type=vimeo-video';
	protected static $pageTitle = 'Update Queue Processor | Vimeo Video Updater';
	protected static $menuTitle = 'Process Update Queue';
	protected static $capability = 'edit_posts';
	protected static $menuSlug = 'vimeo-update-queue';

	public function __construct() {
		add_action('admin_menu', [$this, 'addSubmenuEntry'], 100);
	}

	public static function getStatusUrl() {
		return admin_url(sprintf('%s&page=%s', static::$parentSlug, static::$menuSlug));
	}

	public function addSubmenuEntry() {
		add_submenu_page(static::$parentSlug, __(static::$pageTitle), __(static::$menuTitle), static::$capability, static::$menuSlug, [$this, 'statusHtml']);
	}

	public function statusHtml() {
		if (!current_user_can('edit_posts')) {
			wp_die(__('You do not have sufficient permissions to access this page.'));
		}
		
		$this->ids = get_transient(BulkUpdater::getActionName());
		if (!$this->ids) return $this->error('The queue seems to be empty. IDs: '.var_export($this->ids, TRUE));

		$ids = $this->ids;
		include(VIMEO_VIDEO_UPDATER_DIR.'/templates/status.php');
		if (empty($this->ids)) return $this->error('The queue is empty');
		
		Admin::$logger->info("Processing Vimeo update queue: ".implode(', ', $this->ids));
		$this->next();
	}

	/**
	 * See includes/libs/custom-post-type.class.php:755 from Vimeo Video Post.
	 */
	protected function updatePostData($post, $video) {
		$post->post_title = $video['title'];
		$post->post_content = $video['description'];
		$post->post_excerpt = $video['description'];
		if (isset($video['tags']) && is_array($video['tags'])) {
			wp_set_post_terms($post->ID, $tags, 'post_tag', TRUE);
		}
		return wp_update_post($post, TRUE);
	}

	protected function fetchVimeoData($post) {
		$vimeoId = get_post_meta($post->ID, '__cvm_video_id', TRUE);
		if ($vimeoId) return cvm_query_video($vimeoId);
		return new \WP_Error('invalid_video_id', "The post $post->post_title (ID: $post->ID) has an invalid Vimeo ID");
	}

	protected function error($error) {
		if (is_string($error)) Admin::$logger->error($error);
		if (is_wp_error($error)) foreach ($error->getErrorMessages() as $msg) Admin::$logger->error($msg);
		return FALSE;
	}

	protected function checkTries() {
		$count = $this->tries->get();
		Admin::$logger->info("The ID $id has failed $count times.");
		if ($this->tries->hasMaxedOut()) {
			Admin::$logger->info("The ID $this->id will be removed from the queue.");
			$this->next();
		}
		else {
			$this->tries->increment();
			$this->updateCurrentPost();
		}
	}

	protected function updateCurrentPost() {
		$posts = VimeoUpdater::getVideoPosts([$this->id]);
		if (!$posts || !count($posts)) {
			return $this->error("Could not find Vimeo video post with ID $this->id");
		}
		$post = $posts[0];
		Admin::$logger->info("Updating the video data for post $post->post_title (ID: $post->ID)");

		$vimeo = $this->fetchVimeoData($post);
		if (is_wp_error($vimeo)) return $this->error($vimeo);
		Admin::$logger->info("Fetched Vimeo data: ".var_export($vimeo, TRUE));
		sleep(1);

		$result = $this->updatePostData($post, $vimeo);
		if (is_wp_error($result)) return $this->error($result);

		Admin::$logger->success("Post $post->post_title (ID: $post->ID) was updated successfully");
		return TRUE;
	}

	public function next() {
		if (empty($this->ids)) {
			Admin::$logger->info('Job finished.');
			delete_transient(BulkUpdater::getActionName());
			return NULL;
		}

		$this->id = array_shift($this->ids);
		set_transient(BulkUpdater::getActionName(), $this->ids, 0);
		$this->tries = new Tries();

		if ($this->updateCurrentPost()) $this->next();
		else $this->checkTries();
	}

}
