<?php
namespace VimeoUpdater\Queue;

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

		public function getPostsInQueue() {
				return get_option($this->optionName, []);
		}

		protected function __construct(Tries $tries) {
				// menu
				$this->addSubmenuEntry();

				// option
				$this->autoloadOption = false;
				$this->ids = $this->getPostsInQueue();

				// cron
				$this->tries = $tries;
				$this->cron = $cron;
				add_action($cron->name, [$this, 'next']);
				empty($this->ids) $cron->disable() : $cron->enable();
		}

		public function reset() {
				delete_option($this->optionName);
				$this->ids = [];
				$this->cron->disable();
		}

		public function getStatusUrl() {
				return admin_url($this->parentSlug.'&page='.$this->menuSlug);
		}

		public function addSubmenuEntry() {
				/*
				add_submenu_page(
						'edit.php?post_type=' . $this->cpt->get_post_type(),
						__( 'Import videos', 'cvm_video' ),
						__( 'Import videos', 'cvm_video' ),
						'edit_posts',
						'cvm_import',
						[$import_page, 'get_html']
				);
				 */
				$this->parentSlug = 'edit.php?post_type=vimeo-video';
				$this->pageTitle = __('Queue Status | Vimeo Video Updater');
				$this->menuTitle = __('Update Queue');
				$this->capability = 'edit_posts';
				$this->menuSlug = 'vimeo-update-queue';
				add_submenu_page($this->parentSlug, $this->pageTitle, $this->menuTitle, $this->capability, $this->menuSlug, [$this, 'statusHtml']);
		}

		public function append(array $ids) {
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
				$queue = $this;
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
				if (is_string($error) Flash::error($error);
				if (is_wp_error($error)) foreach ($error->getErrorMessages() as $msg) Flash::error($msg);
				$this->checkTries();
		}

		protected function checkTries() {
				if ($this->tries->hasMaxedOut()) {
						$id = $this->ids[0];
						$count = $this->tries->get();
						Flash::info("The ID $id has failed $count times. It will be removed from the queue.");
						$this->update();
						$this->tries->reset();
				}
				else $this->tries->increment();
		}

		public function next() {
				if (empty($this->ids)) {
						Flash::warn('The queue is empty');
						return NULL;
				}
				
				$id = $this->ids[0];
				$posts = VimeoUpdater::getVideoPosts([$id]);
				if (!$posts || !count($posts)) {
						return $this->updateError("Could not find Vimeo video post with ID $id");
				}
				$post = $posts[0];

				$vimeo = $this->fetchVimeoData($post);
				if (is_wp_error($vimeo)) return $this->updateError($vimeo);

				$result = $this->updatePostData($post, $vimeo);
				if (is_wp_error($result)) return $this->updateError($result);

				Flash::success("Post $post->post_name (ID: $id) was updated successfully");
				array_shift($this->ids);
				$this->update();
				$this->tries->reset();
		}

}
