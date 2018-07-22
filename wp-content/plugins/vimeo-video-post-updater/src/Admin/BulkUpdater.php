<?php
namespace VimeoUpdater\Admin;

class BulkUpdater {

		public function __construct() {
				$this->screen = 'edit-vimeo-video';
				$this->actionName = 'update_vimeo_videos';
				add_filter('bulk_actions-'.$this->screen, [$this, 'menuItemFilter']);
				add_filter('handle_bulk_actions-'.$this->screen, [$this, 'action'], 10, 3 );
				add_action('admin_notices', [$this, 'notice']);
		}
 
		public function notice() {
				if (!empty($_REQUEST[$this->actionName])) {
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
				VimeoUpdater\Queue\Queue::add($post_ids);
				$redirect_to = add_query_arg($this->actionName, 'added', $redirect_to);
				return $redirect_to;
		}

		public function metaBoxes() {
				$this->postMetaBox = new PostMetaBox();
		}

}
