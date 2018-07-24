<?php
namespace VimeoUpdater\Admin\Logger;

use VimeoUpdater\Admin\Admin;

class Logger {

	public function __construct() {
		$this->name = uniqid();
		$this->isEchoing = TRUE;
	}

	protected function log($msg, $type) {
		Admin::log(__("$type: $msg"));
		if ($this->isEchoing)
			printf('<div class="notice notice-%s is-dismissible"><p>%s: %s</p></div>', $type, ucfirst(__($type)), __($msg));
	}

	public function info($msg) {
		$this->log($msg, 'info');
	}

	public function warn($msg) {
		$this->log($msg, 'warning');
	}

	public function success($msg) {
		$this->log($msg, 'success');
	}

	public function error($msg) {
		$this->log($msg, 'error');
	}

}
