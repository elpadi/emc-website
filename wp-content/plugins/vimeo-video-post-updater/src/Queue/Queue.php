<?php
namespace VimeoUpdater\Queue;

use VimeoUpdater\Admin\Admin;
use VimeoUpdater\Admin\Message as FlashMessage;
use VimeoUpdater\VimeoUpdater;

class Queue {

	protected static $instance;

	public static function statusUrl() {
	}

	public static function create() {
		if (static::$instance) return static::$instance;
		static::$instance = new Queue(new Cron(), new Tries());
		return static::$instance;
	}

	public static function add(array $ids) {
		$instance = static::create();
		$instance->append($ids);
	}

	protected function __construct(Cron $cron, Tries $tries) {
		// menu
		add_action('admin_menu', [$this, 'addSubmenuEntry'], 100);

		// option
		$this->optionName = 'vimeo_update_queue';
		$this->autoloadOption = false;
		$this->ids = $this->getPostsInQueue();

		// cron
		$this->tries = $tries;
		$this->cron = $cron;
		empty($this->ids) ? $cron->disable() : $cron->enable();
	}

	public function getPostsInQueue() {
		$ids = get_option($this->optionName);
		return $ids ? (is_string($ids) ? unserialize($ids) : $ids) : [];
	}

	public function reset() {
		delete_option($this->optionName);
		$this->cron->disable();
	}

	public function getStatusUrl() {
		return admin_url($this->parentSlug.'&page='.$this->menuSlug);
	}

	public function addSubmenuEntry() {
		$this->parentSlug = 'edit.php?post_type=vimeo-video';
		$this->pageTitle = __('Queue Status | Vimeo Video Updater');
		$this->menuTitle = __('Update Queue');
		$this->capability = 'edit_posts';
		$this->menuSlug = 'vimeo-update-queue';
		add_submenu_page($this->parentSlug, $this->pageTitle, $this->menuTitle, $this->capability, $this->menuSlug, [$this, 'statusHtml']);
	}

	public function append(array $ids) {
		Admin::log(__METHOD__.'. IDs: '.implode(', ', $ids));
		$this->ids = array_unique(array_merge($this->ids, $ids));
		$this->update();
	}

	public function update() {
		update_option($this->optionName, array_values($this->ids), $this->autoloadOption);
	}

	public function statusHtml() {
		if (!current_user_can('edit_posts')) {
			wp_die(__('You do not have sufficient permissions to access this page.'));
		}
		$ids = $this->getPostsInQueue();
		include(VIMEO_VIDEO_UPDATER_DIR.'/templates/status.php');
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
		throw new \InvalidArgumentException("The post $post->post_name (ID: $post->ID) has an invalid Vimeo ID");
	}

	protected function updateError($error) {
		if (is_string($error)) FlashMessage::error($error);
		if (is_wp_error($error)) foreach ($error->getErrorMessages() as $msg) FlashMessage::error($msg);
		$this->checkTries();
	}

	protected function checkTries() {
		$count = $this->tries->get();
		FlashMessage::info("The ID $id has failed $count times.");
		if ($this->tries->hasMaxedOut()) {
			$id = $this->ids[0];
			FlashMessage::info("The ID $id will be removed from the queue.");
			$this->update();
			$this->tries->reset();
		}
		else $this->tries->increment();
	}

	public function next() {
		Admin::log(__METHOD__);

		$ids = $this->getPostsInQueue();
		if (empty($this->ids)) {
			FlashMessage::warn('The queue is empty');
			return NULL;
		}

		$id = $this->ids[0];
		$posts = VimeoUpdater::getVideoPosts([$id]);
		if (!$posts || !count($posts)) {
			return $this->updateError("Could not find Vimeo video post with ID $id");
		}
		$post = $posts[0];
		FlashMessage::info("Updating the video data for post $post->post_name (ID: $post->ID)");

		$vimeo = $this->fetchVimeoData($post);
		if (is_wp_error($vimeo)) return $this->updateError($vimeo);

		$result = $this->updatePostData($post, $vimeo);
		if (is_wp_error($result)) return $this->updateError($result);

		FlashMessage::success("Post $post->post_name (ID: $id) was updated successfully");
		array_shift($this->ids);
		$this->update();
		$this->tries->reset();
	}

}
