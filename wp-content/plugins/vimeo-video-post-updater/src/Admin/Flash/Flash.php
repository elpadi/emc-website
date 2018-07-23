<?php
namespace VimeoUpdater\Admin\Flash;

class Flash {

	protected static $name = 'flash_messages_queue';

	public static function add(string $name) {
		$queue = get_transient(static::$name);
		if ($queue) $queue[] = $name;
		else $queue = [$name];
		set_transient(static::$name, $queue, HOUR_IN_SECONDS);
	}

	public static function show() {
		$queue = get_transient(static::$name);
		if ($queue) {
			foreach ($queue as $name) Message::show($name);
			delete_transient($name);
		}
		delete_transient(static::$name);
	}

	public function __construct() {
		add_action('admin_notices', [__CLASS__, 'show']);
	}

}
