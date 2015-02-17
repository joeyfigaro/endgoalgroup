<?php
/**
 * @version   $Id: gantry.php 60694 2014-03-26 16:16:53Z btowles $
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2014 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

/*
Plugin Name: Gantry Template Framework
Plugin URI: http://www.gantry-framework.org/
Description: This is a Framework to support easily modifiable themes that are very extensible.
Version: 4.1.2
Author: RocketTheme
Author URI: http://www.rockettheme.com/wordpress
License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

/**
 * @global Gantry $gantry
 */

// Only run if a gantry template is active
global $gantry_path;
if (!is_multisite()) {
	$gantry_path = dirname($plugin);
} else {
	$gantry_path = rtrim(ABSPATH,'/\\') . "/wp-content/plugins/gantry";
}
require_once( dirname(__FILE__) . '/autoload.php');


$gantry_templatepath = get_template_directory() . '/templateDetails.xml';
if (file_exists($gantry_templatepath)) {
	global $ajaxurl;
	$ajaxurl = admin_url('admin-ajax.php');
	include(dirname(__FILE__) . '/functions.php');
	include(dirname(__FILE__) . '/bugfixes.php');
	add_action('after_setup_theme', 'gantry_construct', -10000);
	add_action('after_setup_theme', 'gantry_mootools_init', -50);
	add_action('init', 'gantry_setup_override_widget_instances', 2);



	if (!is_admin()) { // Main Site
		add_action('wp', 'gantry_setup_override_widget_instances', 2);
		add_action('after_setup_theme', 'gantry_init_action', -9999);
		add_action('plugins_loaded', 'gantry_change_widiget_init_action', 2);
		add_action('wp', 'gantry_post_parse_load_action', -10000);
		add_filter('template_include', 'gantry_template_include_filter', 1, 1);
		add_action('init', 'gantry_load_template_lang_action', 10);
		add_filter('comments_template', 'gantry_force_blank_comment', 1, 1);
		add_filter('sidebars_widgets', 'gantry_load_sidebar_intercept', -10000);
		add_filter('template_include', 'gantry_get_template_page_filter', 1000);
		add_action('widgets_init', 'gantry_force_base_widget_settings', 99);
		add_filter('query_vars', 'gantry_addUrlVars');
		add_filter( 'the_content', 'gantry_pretty_print' );
		remove_action('wp_head', 'wp_enqueue_scripts', 1);
		add_action('gantry_enqueue_scripts','wp_enqueue_scripts', 1);

	} else { // Admin
		include(dirname(__FILE__) . '/admin_functions.php');
		add_filter('admin_body_class', 'gantry_add_old_ui_class');
		add_action('after_setup_theme', 'gantry_admin_init', -9999);
		add_action('admin_init', 'gantry_admin_start_buffer', -10000);
		add_action('admin_init', 'gantry_admin_register_theme_settings');
		add_action('admin_head', 'gantry_admin_head', -10000);
		add_action('admin_footer', 'gantry_admin_end', 10000);
		add_action('admin_menu', 'gantry_admin_menu', 9);
		add_action('in_widget_form', 'gantry_add_widget_styles_action', 1, 3);
		add_filter('widget_update_callback', "gantry_widget_style_udpate_filter", 1, 3);
		add_action('admin_post_gantry_theme_delete_override', 'gantry_delete_override');
		add_action('admin_post_gantry_theme_save_as_copy','gantry_override_save_as_copy');
		add_action('admin_post_gantry_theme_update_override', 'gantry_post_update_override');
		add_action('wp_ajax_gantry_admin_save_theme_override', 'gantry_ajax_update_override');
		add_action('wp_ajax_gantry_admin_save_theme_default', 'gantry_update_options');
		//add_action('admin_head', 'gantry_add_meta_buttons');
		add_action('after_setup_theme', 'gantry_widgets_admin_page_init', -10001); //commented out until  widgets admin page override
		add_action('plugins_loaded', 'gantry_widgets_admin_change_widget_init_action', 2);
		add_action('sidebar_admin_setup', 'gantry_widgets_admin_force_accessibility_off', 20);
		add_action('sidebar_admin_setup', 'gantry_admin_clear_cache', 19);
		add_action('widgets.php', 'gantry_widget_admin_clear_widget_instance_overrides', 10);
		add_filter('get_user_option_widgets_access','gantry_filter_get_user_option_widgets_access',-1000);
		add_action('after_switch_theme', 'gantry_admin_clear_cache',10,1);
		add_action('after_switch_theme', 'gantry_theme_switched',9,2);
	}

	add_action('wp_ajax_gantry_admin', 'gantry_admin_ajax');
	add_action('wp_ajax_gantry', 'gantry_ajax');
	add_action('wp_ajax_nopriv_gantry', 'gantry_ajax');
}
