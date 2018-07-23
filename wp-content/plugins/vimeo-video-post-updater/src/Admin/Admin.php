<?php
namespace VimeoUpdater\Admin;

use VimeoUpdater\Queue\Queue;

class Admin {

	public static function log($msg) {
		error_log(date('c').": $msg\n", 3, VIMEO_VIDEO_UPDATER_DIR.'/log.txt');
	}

	public function __construct() {
		$this->bulkUpdater = new BulkUpdater();
		$this->queue = Queue::create();
		$this->flash = new Flash\Flash();
		add_action('add_meta_boxes', [$this, 'metaBoxes']);
	}

	public function metaBoxes() {
		$this->postMetaBox = new PostMetaBox();
	}

}
