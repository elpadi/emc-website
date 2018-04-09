<?php global $video_query; if ($video_query->have_posts()): ?>
<div class="video-listing">
	<?php while ($video_query->have_posts()): $video_query->the_post(); $video_post = get_field('vimeo_video_post'); ?>
	<article>
		<header>
			<a href="<?php the_permalink(); ?>"><?php echo get_the_post_thumbnail($video_post, 'large'); ?></a>
			<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
		</header>
		<main><?php the_excerpt(); ?></main>
	</article>
	<?php endwhile; ?>
</div>
<?php else: echo '<p>No videos found.</p>'; endif; ?>
