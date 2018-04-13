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

	protected function getAllVideosArguments() {
		return [
			'meta_key'   => '_wp_page_template',
			'meta_value' => 'templates/video.php',
		];
	}

	protected function getAlbumVideosArguments($album) {
		$videos_query = new \WP_Query([
			'post_type' => 'vimeo-video',
			'posts_per_page' => -1,
			'tax_query' => [['taxonomy' => 'vimeo-videos', 'field' => 'name', 'terms' => $album]],
		]);
		return [
			'meta_key'   => 'vimeo_video_post',
			'meta_value' => implode(',', array_map(function($p) { return $p->ID; }, $videos_query->posts)),
			'meta_compare' => 'IN',
		];
	}

	protected function vimeo_video_listing($atts, $content, $name) {
		global $video_query;
		ob_start();
		extract($atts);
		if (isset($album) && !empty($album)) {
			$args = $this->getAlbumVideosArguments($album);
		}
		else {
			$args = $this->getAllVideosArguments();
		}
		$video_query = new \WP_Query(array_merge($args, [
			'order' => 'ASC',
			'orderby' => 'title',
			'post_type'  => 'page',
			'posts_per_page' => -1,
		]));
		get_template_part('templates/video-listing');
		wp_reset_postdata();
		return ob_get_clean();
	}

}
