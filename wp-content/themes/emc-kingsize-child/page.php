<?php
get_header();
if (have_posts()): the_post();
?><div class="row header">
	<div class="twelve columns">
		<h2 class="title-page"><?php the_title(); ?></h2>
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
	<div class="twelve columns mobile-twelve page_content fullwidth-page"><?php the_content(); ?></div>
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
