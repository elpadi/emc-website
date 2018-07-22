<?php
namespace VimeoUpdater\Admin;

use VimeoUpdater\Queue\Queue;

class Admin {

		public function __construct() {
				$this->bulkUpdater = new BulkUpdater();
				$this->queue = Queue::create();
				add_action('add_meta_boxes', [$this, 'metaBoxes']);
		}

		public function metaBoxes() {
				$this->postMetaBox = new PostMetaBox();
		}

}
