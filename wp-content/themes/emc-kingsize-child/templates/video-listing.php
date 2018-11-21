<?php global $video_query; if ($video_query->have_posts()):
?><section class="video-listing">
	<header>
		<h3 class="accordion accordion-toggler">Filter By Tags</h3>
		<form class="accordion--content" method="GET" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
		</form>
	</header>
	<main>
		<?php while ($video_query->have_posts()): $video_query->the_post(); $video_post = get_field('vimeo_video_post'); if (!$video_post) continue; ?>
		<article data-tags="<?php echo esc_attr(json_encode(EMC\EMC::getVideoTags($video_post->ID))); ?>">
			<header>
				<a href="<?php the_permalink(); ?>"><?php echo get_the_post_thumbnail($video_post, 'large'); ?></a>
				<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
			</header>
			<main><?= apply_filters('the_content', $video_post->post_content); ?></main>
		</article>
		<?php endwhile; ?>
	</main>
</section><?php else: echo '<p>No videos found.</p>'; endif;
