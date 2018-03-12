<?php

add_action( 'wp_enqueue_scripts', function() {
	// add parent theme style
	wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );

	wp_enqueue_script('emc-forms', get_stylesheet_directory_uri() . "/js/forms.js", ['jquery']);
	wp_enqueue_script('emc-nav', get_stylesheet_directory_uri() . "/js/nav.js", ['jquery']);

	// remove kingsize js
	wp_dequeue_script('custom');
	// add our modified version of kingsize custom
	wp_enqueue_script('emc-custom', get_stylesheet_directory_uri() . "/js/custom.js", ['emc-forms','emc-nav']);
});

