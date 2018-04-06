<?php
namespace EMC;

class EMC {

	public static function prefix(string $s) {
		return "emc_$s";
	}

	public function init() {
		$vimeoShortcode = new VimeoGridShortcode();
		$vimeoShortcode->register();
	}

	function __construct() {
		add_action('init', [$this, 'init']);
	}

}
