<?php
namespace EMC;

use MustUsePlugin\Shortcode;

class VimeoGridShortcode extends Shortcode {

	protected $names = ['vimeo_video_listing'];

	protected function getDefaultAttributes($name) {
		return [
			'album' => '',
		];
	}

}
