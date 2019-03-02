<?php
if (is_front_page() && !isset($_COOKIE['has_played_home_video'])) setcookie('has_played_home_video', '1', time() + YEAR_IN_SECONDS);
get_header();
if (have_posts()): the_post();
if (is_front_page()) $project = get_post(apply_filters('wpml_object_id', 286, 'page', TRUE), OBJECT, 'display');
?><div class="row header">
	<div class="twelve columns">
		<h2 class="title-page"><?php if (is_front_page()) echo $project->post_title; else the_title(); ?></h2>
		<div class="twelve columns mobile-twelve sharing-buttons">
			<ul>
				<li><a href="https://twitter.com/intent/tweet?<?= sprintf('url=%s&text=%s', urlencode(get_the_permalink()), urlencode(get_the_title().', Ecologies of Migrant Care')); ?>" rel="nofollow" target="_blank"><?= file_get_contents(__DIR__.'/svg/social/twitter.svg'); ?></a></li>
				<li><a href="https://facebook.com/sharer/sharer.php?<?= sprintf('u=%s', urlencode(get_the_permalink())); ?>" rel="nofollow" target="_blank"><?= file_get_contents(__DIR__.'/svg/social/facebook.svg'); ?></a></li>
			</ul>
		</div>
	</div>
</div>
<!-- Begin Breadcrumbs -->
<div class="row breadcrumbs">
	<div class="twelve columns">
		<ul><?php foreach ((new EMC\Breadcrumbs()) as $p) printf('<li><a href="%s">%s</a></li>', get_the_permalink($p), get_the_title($p)); ?></ul>
	</div>
</div>
<!-- End Breadcrumbs -->
<!--Sample Page Start-->
<div class="row">
	<div class="twelve columns mobile-twelve page_content fullwidth-page"><?php if (is_front_page()) echo apply_filters('the_content', $project->post_content); else the_content(); ?></div>
</div> <!-- end row -->
<!--Sample Page End-->
<?php else:
?><div class="row header">
	<div class="twelve columns">
		<h2 class="title-page">Page Not Found</h2>
	</div>
</div>
<?php endif;
get_footer();
