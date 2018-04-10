<?php global $video_query; if ($video_query->have_posts()):
?><section class="video-listing">
	<header>
		<form method="GET" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
		</form>
	</header>
	<main>
		<?php while ($video_query->have_posts()): $video_query->the_post(); $video_post = get_field('vimeo_video_post'); if (!$video_post) continue; ?>
		<article data-tags="<?php $vimeo_tags = get_the_tags($video_post->ID); echo $vimeo_tags ? esc_attr(json_encode(array_map(function($t) { return $t->name; }, $vimeo_tags))) : '[]'; ?>">
			<header>
				<a href="<?php the_permalink(); ?>"><?php echo get_the_post_thumbnail($video_post, 'large'); ?></a>
				<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
			</header>
			<main><?php the_excerpt(); ?></main>
		</article>
		<?php endwhile; ?>
	</main>
</section><?php else: echo '<p>No videos found.</p>'; endif;
