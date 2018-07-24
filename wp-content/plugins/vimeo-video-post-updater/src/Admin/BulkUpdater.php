<?php
namespace VimeoUpdater\Admin;

use VimeoUpdater\VimeoUpdater;
use VimeoUpdater\Queue\Queue;

class BulkUpdater {

	protected static $argName = 'video_ids';
	protected static $actionName = 'update_vimeo_videos';
	protected static $screen = 'edit-vimeo-video';

	public static function getActionName() {
		return static::$actionName;
	}

	public function __construct() {
		add_filter('bulk_actions-'.static::$screen, [$this, 'menuItemFilter']);
		add_filter('handle_bulk_actions-'.static::$screen, [$this, 'action'], 10, 3 );
		add_action('admin_notices', [$this, 'notices']);
	}

	public function menuItemFilter($items) {
		$items[static::$actionName] = __('Update Info From Vimeo');
		return $items;
	}

	public function action($redirect_to, $doaction, $post_ids) {
		if ($doaction !== static::$actionName) {
			return $redirect_to;
		}
		set_transient(static::$actionName, $post_ids, 0);
		$redirect_to = add_query_arg(static::$argName, implode('-', $post_ids), $redirect_to);
		return $redirect_to;
	}

	public function notices() {
		if (isset($_REQUEST[static::$argName])) {
			$ids = explode('-', $_REQUEST[static::$argName]);
			foreach ($ids as $id) 
				Admin::$logger->success("The ID $id was added to the update queue.");
			Admin::$logger->info(sprintf('<a href="%s">%s</a>', Queue::getStatusUrl(), __('Process the Vimeo update queue')));
		}
	}

}
