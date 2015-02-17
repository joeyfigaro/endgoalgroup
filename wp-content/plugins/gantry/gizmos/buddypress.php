<?php
/**
 * @version   $Id: buddypress.php 59361 2013-03-13 23:10:27Z btowles $
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2014 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

defined( 'GANTRY_VERSION' ) or die();

gantry_import( 'core.gantrygizmo' );

/**
 * @package     gantry
 * @subpackage  features
 */
class GantryGizmoBuddyPress extends GantryGizmo {

	var $_name = 'buddypress';

	function isEnabled() {
		/** @global $gantry Gantry */
		global $gantry, $bp;

		if( !function_exists( 'is_plugin_active' ) || !function_exists( 'is_plugin_active_for_network' ) )
			require_once( ABSPATH . 'wp-admin/includes/plugin.php' );

		// check if BuddyPress is active and it's at least 1.8
		if( $bp === null || version_compare( $bp->version, '1.8', '<' ) ) {
			return false;
		}

		// check if the deprecated Gantry BuddyPress plugin is installed and active
		if( is_plugin_active( 'gantry-buddypress/loader.php' ) || is_plugin_active_for_network( 'gantry-buddypress/loader.php' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_nag' ) );
			return false;
		}

		// stop loading BuddyPress Template Pack plugin
		remove_action( 'bp_include', 'bp_tpack_loader' );

		// add new locations for the possible BuddyPress template files
		add_action( 'after_setup_theme', array( $this, 'add_file_paths' ) );

		// add BuddyPress component types to the WP_Query
		add_action( 'parse_query', array( $this, 'bp_query_add_components' ) );

		// add BuddyPress component types to the Assignements tab in the admin
		add_action( 'gantry_admin_page_types', array( $this, 'bp_add_page_types_to_admin' ) );

		return true;
	}

	function init() {
		/** @global $gantry Gantry */
		global $gantry;

		// check for active BuddyPress component and skip the Gantry Title gizmo if needed
		add_filter( 'gantry_title_gizmo', array( $this, 'check_component_title' ) );

		// add the blog name to the BuddyPress modified page title
		add_filter( 'bp_modify_page_title', array( $this, 'add_title_blog_name' ), 10, 3 );
	}

	function query_parsed_init() {
		/** @global $gantry Gantry */
		global $gantry;

		// load the template files from the proper Gantry location
		add_filter( 'gantry_mainbody_include', array( $this, 'page_location' ) );
	}

	// load the template files from the proper Gantry location
	function page_location( $tmpl ) {
		global $gantry;

		if( bp_current_component() ) {
			foreach( array( 'plugin-buddypress.php', 'buddypress.php', 'community.php', 'generic.php', 'page.php', 'single.php' ) as $template ) {
				foreach ($gantry->_contentTypePaths as $file_path) {
					if( file_exists( $file_path . '/' . $template ) ) return $file_path . '/' . $template;
				}
			}
		}

		return $tmpl;
	}

	// add new locations for the possible BuddyPress template files
	function add_file_paths() {
		global $gantry;

		$gantry->addContentTypePath( $gantry->templatePath . '/buddypress' );
		$gantry->addContentTypePath( $gantry->templatePath . '/community' );
	}

	// add BuddyPress component types to the WP_Query
	function bp_query_add_components() {
		global $wp_query, $bp;

		if ( bp_current_component() ) {
			$component = bp_current_component();
			$component = 'is_bp_' . $component;
			$wp_query->$component = true;
		}
	}

	// add BuddyPress component types to the Assignements tab in the admin
	function bp_add_page_types_to_admin( $page_types ) {
		$bp_page_types = array(
			'bp_blogs'          => _g( 'BuddyPress Blogs Component' ),
			'bp_activity'       => _g( 'BuddyPress Activity Component' ),
			'bp_profile'        => _g( 'BuddyPress Profile Component' ),
			'bp_messages'       => _g( 'BuddyPress Messages Component' ),
			'bp_friends'        => _g( 'BuddyPress Friends Component' ),
			'bp_members'        => _g( 'BuddyPress Members Component' ),
			'bp_groups'         => _g( 'BuddyPress Groups Component' ),
			'bp_forums'         => _g( 'BuddyPress Forums Component' ),
            'bp_notifications'  => _g( 'BuddyPress Notifications Component' ),
			'bp_settings'       => _g( 'BuddyPress Settings Component' )
		);

		$page_types = $page_types + $bp_page_types;
		return $page_types;
	}

	// check for active BuddyPress component and skip the Gantry Title gizmo if needed
	function check_component_title( $title ) {
		global $gantry;

		if( bp_current_component() ) {
			return;
		} else {
			return $title;
		}
	}

	// add the blog name to the BuddyPress modified page title
	function add_title_blog_name( $title, $pagename, $sep ) {
		global $gantry;

		$title .= get_bloginfo( 'name' );
		return $title;
	}

	// admin nag displayed when the deprecated Gantry BuddyPress plugin is present and active
	function admin_nag() {
		$msg = _g( 'The Gantry BuddyPress plugin is no longer required starting with Gantry Framework 4.0.8. You will need to deactivate and/or remove it for this new built-in support to work. This change affects BuddyPress 1.8+.' );
		echo '<div class="error"><p>' . $msg . '</p></div>';
	}
}