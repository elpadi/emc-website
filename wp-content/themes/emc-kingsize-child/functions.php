<?php

class KingSize_Theme {
	public static $styles = [
		'style',
		'custom',
		'mobile_navigation',
		'font-awesome/css/font-awesome.min',
		'responsive-tables',
	];
}

add_action( 'wp_enqueue_scripts', function() {
	// add kingsize css files
	foreach (KingSize_Theme::$styles as $css) {
		wp_enqueue_style('kingsize-'.basename($css), get_template_directory_uri() . "/css/$css.css", [], '1.0.0' );
	}
	// add kingsize main css file
	wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );

	wp_enqueue_script('foundation');
	wp_enqueue_script('vimeo', "https://player.vimeo.com/api/player.js");

	if (is_front_page()) {
		wp_enqueue_script('wp-api');
	}

	// remove kingsize js
	wp_dequeue_script('custom');

	$theme_dir = get_stylesheet_directory();
	$theme_uri = get_stylesheet_directory_uri();

	foreach (glob("$theme_dir/css/*.css") as $f) {
		$h = 'emc-'.basename($f, '.css');
		wp_enqueue_style($h, "$theme_uri/css/".basename($f), [], filemtime($f));
	}
	wp_enqueue_style('emc-style', "$theme_uri/style.css", [], filemtime("$theme_dir/style.css"));

	foreach (glob("$theme_dir/js/*.js") as $f) {
		$h = 'emc-'.basename($f, '.js');
		wp_enqueue_script($h, "$theme_uri/js/".basename($f), [], filemtime($f));
	}

	if(is_singular()) wp_enqueue_script('comment-reply');
});

add_action('wp_head', function() {
	global $get_options,$data,$tpl_body_id;
	
	if( preg_match('/type="colorbox"(.*)/', $posts[0]->post_content, $matches) ) 	
	{
		$tpl_body_id = "colorbox";
	}
	elseif( preg_match('/type="fancybox"(.*)/', $posts[0]->post_content, $matches) ) 	
	{
		$tpl_body_id = "fancybox";
	}
	elseif( preg_match('/type="prettyphoto"(.*)/', $posts[0]->post_content, $matches) )
	{
		$tpl_body_id = "prettyphoto";
	}	
	elseif( preg_match('/type="slideviewer"(.*)/', $posts[0]->post_content, $matches) )
	{
		$tpl_body_id = "slideviewer";
	}
	elseif( preg_match('/type="galleria"(.*)/', $posts[0]->post_content, $matches) )
	{
		$tpl_body_id = "galleria";
	}
	include (get_template_directory() . '/lib/gallery_template_style_js.php');
	if ( false && $data['wm_no_rightclick_enabled'] == "1" ) {
		echo '
		<!-- Disable Right-click -->
		<script type="text/javascript" language="javascript">
			jQuery(function($) {
				$(this).bind("contextmenu", function(e) {
					e.preventDefault();
				});
			}); 
		</script>
		<!-- END of Disable Right-click -->
		';
	}
	if( $data['wm_background_type'] != 'Video Background' && is_home()) {			
		include (get_template_directory() . '/lib/background_slider.php'); 
	} 
	if( false && $data['wm_custom_css'] ) printf('<style>%s</style>', $data['wm_custom_css']);
	if( $data['wm_date_enabled'] == '1' ) echo '<style>.blog_post { margin-bottom: 60px; }</style>';
});

add_action('wp_footer', function() {
	global $post;
	if ($post->post_name !== 'ftest') echo '<div class="grid">&nbsp;</div>';
	printf(
		'<button id="scroll-button" class="plain">
			<img class="icon" src="%s/svg/scroll-arrow.svg" alt="">
		</button>', get_stylesheet_directory_uri()
	);
	switch (basename(get_page_template(), '.php')) {
	case 'map':
		echo '<div class="backgroundmap"><iframe src="https://snazzymaps.com/embed/62070" width="100%" height="100%" style="border:none;"></iframe></div>';
		break;
	}
});

add_filter('body_class', function($classes) {
	global $post;
	$classes[] = 'scroll-top';
	if (is_front_page() || $post->post_name === 'map') $classes[] = 'no-content';
	return $classes;
});
