<?php
/*
 * Plugin Name: Vimeo Video Updater
 * Plugin URI: https://github.com/elpadi/emc.git
 * Description: Update video post info imported with CodeFlavors Vimeo Videos.
 * Author: Carlos Padilla
 * Version: 1.5.2.1
 * Author URI: http://cpadilla.thejackmag.com
 */
if (!function_exists('add_action')) {
	die('Where is WordPress??');
}
require_once(__DIR__.'/vendor/autoload.php');
define('VIMEO_VIDEO_UPDATER_DIR', __DIR__);
add_action('admin_init', function() { new VimeoUpdater\Admin\Admin(); });
