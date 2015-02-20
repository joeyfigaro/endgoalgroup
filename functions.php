<?php
/**
 * @Starter Site
 * @version:		1.0
 *
 * @author: 		Joey Figaro
 * @website:		endgoalgroup.com
**/


/* DEFINE STANDARD WP SETTINGS
-------------------------------------------------------------------------*/

/** google analytics */
define('GOOGLEANALYTICS', 'U-XXXXX-X'); // change this to site analytics code

/** favicon */
define('USE_BLOG_FAVICON', false); // if true, save favicon.ico to site root directory

/** menu navigation */
define('ENABLE_MAIN_MENU', true); // enable wp-nav functionality for main menu
define('ENABLE_FOOTER_MENU', true); // enable wp-nav functionality for footer menu
define('ENABLE_SUB_MENU', true); // enable wp-nav functionality for submenu menu

define('MAIN_MENU_WPNAME', 'Header'); // name used by WP backend
define('MAIN_MENU_NAME', 'Header Navigation'); // name displayed in wp-admin

define('FOOTER_MENU_WPNAME', 'Footer'); // name used by WP backend
define('FOOTER_MENU_NAME', 'Footer Navigation'); // name displayed in wp-admin

define('SUB_MENU_WPNAME', 'Sub'); // name used by WP backend
define('SUB_MENU_NAME', 'Submenu Navigation'); // name displayed in wp-admin

/** sidebars */
define('ENABLE_SIDEBAR', true); // enable/disable using sidebars
$sidebars = array(
  'Right Sidebar'
);

/** excerpt */
define('CUSTOM_EXCERPT_LENGTH', true); // use custom excerpt length for the_excerpt()
define('EXCERPT_LENGTH', 20); // excerpt length
define('AFTER_EXCERPT', '...'); // after excerpt text

/** comments */
define('ENABLE_THREADED_COMMENTS', true); // use threaded comments

/** images */
// set_post_thumbnail_size( 100, 100, false ); // set custom post thumbnail size
// add_image_size( '', 0, 0, true); // name, width, height, crop
// add_image_size( '', 0, 0, true);
// add_image_size( '', 0, 0, true);

/** remove admin dashboard widgets */
define('ADMIN_REMOVE_ALL_DASHBOARD', false);

define('ADMIN_REMOVE_QUICK_PRESS', true);
define('ADMIN_REMOVE_INCOMING_LINKS', true);
define('ADMIN_REMOVE_RECENT_COMMENTS', true);
define('ADMIN_REMOVE_DASHBOARD_PLUGINS', true);
define('ADMIN_REMOVE_RECENT_DRAFTS', true);
define('ADMIN_REMOVE_PRIMARY', true);
define('ADMIN_REMOVE_SECONDARY', true);
define('ADMIN_REMOVE_RIGHT_NOW', true);

/** custom admin widget */
define('DASHBOARD_WIDGET_SLUG', 'cacpro-dashboard-widget');
define('DASHBOARD_WIDGET_NAME', 'Cross and Crown');
define('DASHBOARD_WIDGET_CONTENT', '<h4>Welcome to your Cross and Crown powered site!</h4><br />
	<h3>Here you can perform some basic functions:</h3><br />
	<ol>
	<li><a href="edit.php?post_type=page">Edit Pages</a></li>
	<li><a href="edit.php?post_type=post">Write and Edit Posts</a></li>
	<li><a href="admin.php?page=nggallery-add-gallery">Add images to your slider</a> (if we installed one on your site)</li>
	<li>and more!</li>
	</ol>
	<p>We are here to support you 100% if you have any questions or concerns.</p><br />
	<h3>We are here to support you!</h3><br />
	<a href="admin.php?page=support">Need help?</a>
');

/** jquery version */
define('JQUERY_VERSION', '1.5.2');

/** use typekit */
define('USE_TYPEKIT', false); // use typekit, true or false
define('TYPEKIT_ID', 'XXXXXXX'); //typekit 7 digit kit ID found in script code


/* WP FUNCTIONS
-------------------------------------------------------------------------*/

/** add standard theme support */
if (function_exists('add_theme_support')) {
  add_theme_support('post-thumbnails');
  add_theme_support('automatic-feed-links');
}


if (function_exists('register_nav_menu')) {
	if (ENABLE_MAIN_MENU == true) register_nav_menu(MAIN_MENU_WPNAME, MAIN_MENU_NAME);
	if (ENABLE_FOOTER_MENU == true) register_nav_menu(FOOTER_MENU_WPNAME, FOOTER_MENU_NAME);
	if (ENABLE_SUB_MENU == true) register_nav_menu(SUB_MENU_WPNAME, SUB_MENU_NAME);
}

if (function_exists('register_sidebar') && ENABLE_SIDEBAR) {
	foreach ($sidebars as $sidebar) {
		register_sidebar(array('name'=> $sidebar,
			'before_widget' => '<div id="widget-%2$s" class="widget">',
			'after_widget' => '</div>',
			'before_title' => '<h2 class="widget-title">',
			'after_title' => '</h2>'
		));
	}
}


/** add google analytics to footer */
if (GOOGLEANALYTICS != 'U-XXXXX-X') {
	function add_google_analytics() {
		echo "\n" . '<!-- Google Analytics for ' . GOOGLEANALYTICS . ' -->' . "\n";
		echo '<script src="http://www.google-analytics.com/ga.js" type="text/javascript"></script>' . "\n";
		echo '<script type="text/javascript">' . "\n";
		echo 'var pageTracker = _gat._getTracker("'.GOOGLEANALYTICS.'");' . "\n";
		echo 'pageTracker._trackPageview();' . "\n";
		echo '</script>' . "\n";
	}

  add_action('wp_footer', 'add_google_analytics');
}

/** smart jquery inclusion */
if (!is_admin()) {
	wp_deregister_script('jquery');
	wp_register_script('jquery', ("https://ajax.googleapis.com/ajax/libs/jquery/".JQUERY_VERSION."/jquery.min.js"), false, JQUERY_VERSION );
	wp_enqueue_script('jquery');
}


/** enable typekit use on site */
if (USE_TYPEKIT) {
  wp_register_script('typekit', ("http://use.typekit.com/".TYPEKIT_ID.".js"), false );
	wp_enqueue_script('typekit');

  function add_typekit() {
  	echo "\n" . '<!-- Typekit Init -->' . "\n";
  	echo '<script type="text/javascript">try{Typekit.load();}catch(e){}</script>' . "\n";
  }

  add_action('wp_head', 'add_typekit');
}

/** enable threaded comments */
if (ENABLE_THREADED_COMMENTS == true) {
  function enable_threaded_comments() {
  	if (!is_admin()) {
  		if (is_singular() AND comments_open() AND (get_option('thread_comments') == 1)) {
        wp_enqueue_script('comment-reply');
      }
  	}
  }

  add_action('get_header', 'enable_threaded_comments');
}


/** remove unnecessary items from head */
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'feed_links', 2);
remove_action('wp_head', 'index_rel_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'feed_links_extra', 3);
remove_action('wp_head', 'start_post_rel_link', 10, 0);
remove_action('wp_head', 'parent_post_rel_link', 10, 0);
remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0);


/** custom excerpt length */
if (CUSTOM_EXCERPT_LENGTH == true) {
  function custom_excerpt_length($length) {
    return EXCERPT_LENGTH;
  }

  add_filter('excerpt_length', 'custom_excerpt_length');
}

function custom_excerpt_more($more) {
	return AFTER_EXCERPT;
}

add_filter('excerpt_more', 'custom_excerpt_more');


/** add a favicon */
if (USE_BLOG_FAVICON) {
  function blog_favicon() {
  	echo '<link rel="Shortcut Icon" type="image/x-icon" href="'.get_bloginfo('wpurl').'/favicon.ico" />';
  }

  add_action('wp_head', 'blog_favicon');
}


/** add a favicon for admin */
function admin_favicon() {
	echo '<link rel="Shortcut Icon" type="image/x-icon" href="'.get_bloginfo('stylesheet_directory').'/images/favicon.png" />';
}

add_action('admin_head', 'admin_favicon');


/** category id in body and post class */
function category_id_class($classes) {
	global $post;
	foreach((get_the_category($post->ID)) as $category)
		$classes [] = 'cat-' . $category->cat_ID . '-id';
		return $classes;
}

add_filter('post_class', 'category_id_class');
add_filter('body_class', 'category_id_class');


/** get the first category id */
function get_first_category_ID() {
	$category = get_the_category();
	return $category[0]->cat_ID;
}


/** remove admin dashboard widgets */
function remove_dashboard_widgets() {
	if (ADMIN_REMOVE_QUICK_PRESS == true || ADMIN_REMOVE_ALL_DASHBOARD == true) { remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' ); }
	if (ADMIN_REMOVE_INCOMING_LINKS == true || ADMIN_REMOVE_ALL_DASHBOARD == true) { remove_meta_box( 'dashboard_incoming_links', 'dashboard', 'normal' ); }
	if (ADMIN_REMOVE_RECENT_COMMENTS == true || ADMIN_REMOVE_ALL_DASHBOARD == true) { remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' ); }
	if (ADMIN_REMOVE_DASHBOARD_PLUGINS == true || ADMIN_REMOVE_ALL_DASHBOARD == true) { remove_meta_box( 'dashboard_plugins', 'dashboard', 'normal' ); }
	if (ADMIN_REMOVE_RECENT_DRAFTS  == true || ADMIN_REMOVE_ALL_DASHBOARD == true) { remove_meta_box( 'dashboard_recent_drafts', 'dashboard', 'side' ); }
	if (ADMIN_REMOVE_PRIMARY == true || ADMIN_REMOVE_ALL_DASHBOARD == true) { remove_meta_box( 'dashboard_primary', 'dashboard', 'side' ); }
	if (ADMIN_REMOVE_SECONDARY == true || ADMIN_REMOVE_ALL_DASHBOARD == true) { remove_meta_box( 'dashboard_secondary', 'dashboard', 'side' ); }
	if (ADMIN_REMOVE_RIGHT_NOW == true || ADMIN_REMOVE_ALL_DASHBOARD == true) { remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' ); }
}

add_action('wp_dashboard_setup', 'remove_dashboard_widgets' );

function dashboard_widget_function() {
	echo DASHBOARD_WIDGET_CONTENT;
}

function add_dashboard_widgets() {
  wp_add_dashboard_widget(DASHBOARD_WIDGET_SLUG, DASHBOARD_WIDGET_NAME, dashboard_widget_function);
}

add_action('wp_dashboard_setup', 'add_dashboard_widgets' );


/** add cacpro rss feed to admin dashboard */
add_action('wp_dashboard_setup', 'my_dashboard_widgets');
function my_dashboard_widgets() {
  global $wp_meta_boxes;
  // add a custom dashboard widget
  wp_add_dashboard_widget( 'dashboard_custom_feed', 'Latest from Cross and Crown', 'dashboard_custom_feed_output' ); //add new RSS feed output
}

function dashboard_custom_feed_output() {
     echo '
		<div class="rss-widget">
			<br />';
     		wp_widget_rss_output(array(
          	'url' => 'http://www.cacpro.com/feed',  //put your feed URL here
          	'title' => 'Latest from Cross and Crown',
          	'items' => 4, //how many posts to show
          	'show_summary' => 1,
          	'show_author' => 1,
          	'show_date' => 1
     ));
     echo "</div>
";
}


/** add "first" and "last" class to menu items */
function addClassToLastMenuItem($theMenu)
{
	$classToSearchFor = 'class="menu-item';
	$lengthOfClassToSearchFor = strlen($classToSearchFor);
	$lastOccurranceOfClass = strripos( $theMenu, $classToSearchFor );
	$beforeTheClass = substr( $theMenu,	0,($lastOccurranceOfClass + $lengthOfClassToSearchFor) );
	$afterTheClass = substr( $theMenu,($lastOccurranceOfClass + $lengthOfClassToSearchFor),	strlen($theMenu) );
	return $beforeTheClass . ' last' . $afterTheClass;
}

add_filter('wp_nav_menu_items','addClassToLastMenuItem', 20, 1);

function addClassToFirstMenuItem($theMenu)
{
	$classToSearchFor = 'class="menu-item';
	$lengthOfClassToSearchFor = strlen($classToSearchFor);
	$firstOccurranceOfClass = stripos( $theMenu, $classToSearchFor );
	$beforeTheClass = substr( $theMenu, 0,($firstOccurranceOfClass + $lengthOfClassToSearchFor) );
	$afterTheClass = substr( $theMenu,($firstOccurranceOfClass + $lengthOfClassToSearchFor), strlen($theMenu) );
	return $beforeTheClass . ' first ' . $afterTheClass;
}

add_filter('wp_nav_menu_items','addClassToFirstMenuItem', 20, 1);



/** remove "links" and "tools" from admin panel */
function remove_menus_items () {
global $menu;
	$restricted = array( __('Links'), __('Tools'));
	end ($menu);
	while (prev($menu)){
		$value = explode(' ',$menu[key($menu)][0]);
		if(in_array($value[0] != NULL?$value[0]:"" , $restricted)){unset($menu[key($menu)]);}
	}
/*
global $submenu;
	unset($submenu['options-general.php'][41]);
	unset($submenu['options-general.php'][43]);
*/
}

add_action('admin_menu', 'remove_menus_items');


/** add support connection to WHMCS to admin menu */
add_action('admin_menu', 'manage_account');

function manage_account() {
	add_menu_page( 'Cross and Crown Support Management', 'Support', 'manage_options', 'support', 'whmcs', '/wp-content/images/icon_support.png', 3 );
}

function whmcs() {
	if (!current_user_can('manage_options'))  {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}
	echo '<div class="wrap" style="height:100%">';
	echo "<h2>" . __( 'Cross and Crown Support Management', 'cacpro-manage' ) . "</h2>";
	echo '<iframe src="https://my.cacpro.com" width="100%" height="100%" style="min-height:800px;"/>';
	echo '</div>';
}


/** ADD ANY ADDITIONAL SITE SPECIFIC FUNCTIONS BELOW */


/** END SITE SPECIFIC FUNCTIOS */

?>