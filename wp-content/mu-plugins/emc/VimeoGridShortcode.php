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

	protected function vimeo_video_listing($atts, $content, $name) {
		global $video_query;
		$args = array(
			'meta_key'   => '_wp_page_template',
			'meta_value' => 'templates/video.php',
			'order' => 'ASC',
			'orderby' => 'title',
			'post_type'  => 'page',
			'posts_per_page' => -1,
		);
		$video_query = new \WP_Query( $args );
		ob_start();
		get_template_part('templates/video-listing'/*, string $name = null */);
		wp_reset_postdata();
		return ob_get_clean();
	}

}
