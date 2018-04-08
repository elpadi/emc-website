<?php
namespace MustUsePlugin;

class Shortcode {
	
	protected $names;

	public function __construct() {
		if (!isset($this->names)) {
			throw new BadMethodCallException("You must define the shortcode names.");
		}
	}

	public function register() {
		if (!isset($this->names) || !is_array($this->names)) {
			throw new BadMethodCallException("The names property must be an array of shortcode names.");
		}
		foreach ($this->names as $name) add_shortcode($name, [$this, 'shortcodeHandler']);
	}

	protected function getDefaultAttributes($name) {
		return [];
	}

	public function shortcodeHandler($atts, $content, $name) {
    $attributes = shortcode_atts($this->getDefaultAttributes($name), $atts);
		return call_user_func([$this, is_callable([$this, $name]) ? $name : 'getContent'], $attributes, $content, $name);
	}

	public function getContent($atts, $content, $name) {
		return "$name / $content / ".var_export($atts, true);
	}

}
