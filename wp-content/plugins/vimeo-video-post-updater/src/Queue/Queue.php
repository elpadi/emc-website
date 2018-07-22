<?php
namespace VimeoUpdater\Queue;

class Queue {

		protected static $instance;

		public static function statusUrl() {
		}

		public static function create() {
				if (static::$instance) return static::$instance;
				static::$instance = new Queue();
				return static::$instance;
		}

		public static function add(array $ids) {
				$instance = static::create();
				$instance->append($ids);
		}

		protected function __construct() {
				$this->addSubmenuEntry();
				$this->autoloadOption = false;
				$this->ids = get_option($this->optionName, []);
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
				update_option($this->optionName, $this->ids, $this->autoloadOption);
		}

		public function statusHtml() {
				if (!current_user_can('edit_posts')) {
						wp_die(__('You do not have sufficient permissions to access this page.'));
				}
				$queue = $this;
				include(VIMEO_VIDEO_UPDATER_DIR.'/templates/status.php');
		}

}
