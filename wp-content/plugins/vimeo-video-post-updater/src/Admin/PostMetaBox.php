<?php
namespace VimeoUpdater\Admin;

class PostMetaBox {

		public function __construct() {
				$this->id = 'vimeo-updater-single-post';
				$this->title = 'Synch Vimeo Data';
				$this->screen = 'vimeo-video';
				$this->context = 'side';
				$this->addMetaBox();
		}

		public function addMetaBox() {
				add_meta_box(
						$this->id,
						$this->title,
						[$this, 'html'],
						$this->screen,
						$this->context
				);
		}

		public function html($post) {
				include(VIMEO_VIDEO_UPDATER_DIR.'/templates/single-post-metabox.php');
		}

}
