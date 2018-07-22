<?php
namespace VimeoUpdater\Admin;

use VimeoUpdater\VimeoUpdater;
use VimeoUpdater\Queue\Queue;

class BulkUpdater {

		public function __construct() {
				$this->screen = 'edit-vimeo-video';
				$this->actionName = 'update_vimeo_videos';
				$this->transientName = 'vimeo_updater_added_ids';
				add_filter('bulk_actions-'.$this->screen, [$this, 'menuItemFilter']);
				add_filter('handle_bulk_actions-'.$this->screen, [$this, 'action'], 10, 3 );
				add_action('admin_notices', [$this, 'notice']);
		}
 
		protected function getSelectedPostsDisplay($ids) {
				$posts = VimeoUpdater::getVideoPosts(array_diff($ids, Queue::create()->getPostsInQueue()));
				if (empty($posts)) return __('No new posts');
				return count($posts).__(' posts ').implode(' ', array_map(function($p) {
						return sprintf('<pre>%s (ID: %d)</pre>', $p->post_name, $p->ID);
				}, $posts));
		}

		public function notice() {
				if (!empty($_REQUEST[$this->actionName])) {
						$ids = get_transient($this->transientName);
						if ($ids !== false) {
								$selected = $this->getSelectedPostsDisplay($ids);
						}
						else $selected = __('The selected posts');
						$queue_url = Queue::create()->getStatusUrl();
						include(VIMEO_VIDEO_UPDATER_DIR.'/templates/bulk-updater-notice.php');
				}
		}
		
		public function menuItemFilter($items) {
				$items[$this->actionName] = __('Update Info From Vimeo', 'video_updater');
				return $items;
		}

		public function action($redirect_to, $doaction, $post_ids) {
				if ($doaction !== $this->actionName) {
						return $redirect_to;
				}
				Queue::add($post_ids);
				set_transient($this->transientName, $post_ids, MINUTE_IN_SECONDS);
				$redirect_to = add_query_arg($this->actionName, 'added', $redirect_to);
				return $redirect_to;
		}

		public function metaBoxes() {
				$this->postMetaBox = new PostMetaBox();
		}

}
