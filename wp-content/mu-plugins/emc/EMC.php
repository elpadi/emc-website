<?php
namespace EMC;

class EMC {

	const VIDEO_TAG_SLUG = 'vimeo-tag';

	public static function prefix(string $s) {
		return "emc_$s";
	}

	public function init() {
		$vimeoShortcode = new VimeoGridShortcode();
		$vimeoShortcode->register();
	}

	public static function getVideoTags($videoPostId) {
		$tags = get_the_tags($videoPostId);
		return $tags ? array_map(function($t) {
			if (defined('ICL_LANGUAGE_CODE') && ICL_LANGUAGE_CODE != 'en' && ($transId = icl_object_id($t->termID, static::VIDEO_TAG_SLUG, FALSE))) {
				return get_term($transId, static::VIDEO_TAG_SLUG)->name;
			}
			return $t->name;
		}, $tags) : [];
	}

	function __construct() {
		add_action('init', [$this, 'init']);
	}

}
