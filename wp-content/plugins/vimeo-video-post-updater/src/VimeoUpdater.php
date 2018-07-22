<?php
namespace VimeoUpdater;

class VimeoUpdater {

		public static function getVideoPosts(array $ids=[], array $overrides=[]) {
				$params = [
						'post_type' => 'vimeo-video',
						'posts_per_page' => -1,
						'ignore_sticky_posts' => 1,
				];
				if (!empty($ids)) {
						$params['post__in'] = $ids;
						$params['orderby'] = 'post__in';
				}
				return get_posts(array_merge($params, $overrides));
		}

}
