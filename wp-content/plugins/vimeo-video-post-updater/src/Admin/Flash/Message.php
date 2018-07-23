<?php
namespace VimeoUpdater\Admin\Flash;

class Message {

	public static function info(string $msg) {
		return new Message($msg, 'info');
	}

	public static function warn(string $msg) {
		return new Message($msg, 'warning');
	}

	public static function success(string $msg) {
		return new Message($msg, 'success');
	}

	public static function error(string $msg) {
		return new Message($msg, 'error');
	}

	public static function show($name) {
		$data = get_transient(static::$name);
		if (!$data) return NULL;
		list($msg, $type) = $data;
		printf('<div class="notice notice-%s is-dismissible"><p>%s</p></div>', $type, __($msg));
	}

	protected function __construct(string $msg, string $type) {
		$this->name = uniqid();
		Flash::add($this->name);
		set_transient($this->name, [$msg, $type], HOUR_IN_SECONDS);
	}

}
