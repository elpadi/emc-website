<?php
namespace VimeoUpdater\Admin;

use VimeoUpdater\Queue\Queue;

class Admin {

	public static $logger;

	public static function log($msg) {
		error_log(date('c').": $msg\n", 3, VIMEO_VIDEO_UPDATER_DIR.'/log.txt');
	}

	public function __construct() {
		static::$logger = new Logger\Logger();
		$this->bulkUpdater = new BulkUpdater();
		$this->queue = new Queue();
		add_action('add_meta_boxes', [$this, 'metaBoxes']);
	}

	public function metaBoxes() {
		$this->postMetaBox = new PostMetaBox();
	}

}
