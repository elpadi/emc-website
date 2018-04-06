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
	// add our own main css file
	wp_enqueue_style( 'emc-style', get_stylesheet_directory_uri() . '/style.css' );

	wp_enqueue_script('foundation');
	wp_register_script('vimeo', "https://player.vimeo.com/api/player.js");
	wp_register_script('emc-forms', get_stylesheet_directory_uri() . "/js/forms.js", ['jquery']);
	wp_register_script('emc-nav', get_stylesheet_directory_uri() . "/js/nav.js", ['jquery']);
	wp_register_script('emc-scroll', get_stylesheet_directory_uri() . "/js/content-scroll.js", ['jquery']);
	/*
    <link rel="stylesheet" href="<?php echo get_template_directory_uri();?>/css/style.css">
    <link rel="stylesheet" href="<?php echo get_template_directory_uri();?>/css/custom.css">
    <link rel="stylesheet" href="<?php echo get_template_directory_uri();?>/css/mobile_navigation.css" type="text/css" />
  	<link rel="stylesheet" href="<?php echo get_template_directory_uri();?>/css/font-awesome/css/font-awesome.min.css">
	 <!-- Attach the Table CSS and Javascript -->
	<link rel="stylesheet" href="<?php echo get_template_directory_uri();?>/css/responsive-tables.css">
	<script src="<?php echo get_template_directory_uri();?>/js/responsive-tables.js" type="text/javascript" ></script>
	<script type="text/javascript" src="https://f.vimeocdn.com/js/froogaloop2.min.js"></script>
	 */

	// remove kingsize js
	wp_dequeue_script('custom');
	// add our modified version of kingsize custom
	wp_enqueue_script('emc-custom', get_stylesheet_directory_uri() . "/js/custom.js", ['vimeo','emc-forms','emc-nav','emc-scroll']);
	
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
});

function _remove_script_version($src) { 
	$parts = explode('?', $src); 	
	return $parts[0] . '?v=' . time(); 
} 
add_filter( 'script_loader_src', '_remove_script_version', 15, 1 ); 
add_filter( 'style_loader_src', '_remove_script_version', 15, 1 );

add_filter('body_class', function($classes) {
	$classes[] = 'scroll-top';
	return $classes;
});
