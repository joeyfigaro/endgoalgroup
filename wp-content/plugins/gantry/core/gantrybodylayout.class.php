<?php
/**
 * @version   $Id: gantrybodylayout.class.php 60387 2014-01-13 12:21:14Z jakub $
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2014 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */
defined('GANTRY_VERSION') or die();
gantry_import('core.gantrylayout');
/**
 * Base class for all Gantry custom features.
 *
 * @package    gantry
 * @subpackage core
 */
class GantryBodyLayout extends GantryLayout
{
	function include_type()
	{
		/** @global $gantry Gantry */
		global $gantry;

		$is_wp3             = version_compare($gantry->platform->platform_version, "3.0", ">=");
		$main_body_template = false;

		// get the main template page loaded
		$template_page = basename($gantry->retrieveTemp('template', 'page_name'));

		// load main body layout of the exact match is there (shortcut)
		if ('index.php' != strtolower($template_page) && $main_body_template = $this->locate_type(array($template_page))) :

			// see if we need to load any subcases of the mainbody for the template page used
		elseif ($main_body_template = $this->get_taxonomy_page($template_page)) : 
		elseif ($main_body_template = $this->get_single_page($template_page)) : 
		elseif ($main_body_template = $this->get_page_page($template_page)) : 
		elseif ($main_body_template = $this->get_category_page($template_page)) : 
		elseif ($main_body_template = $this->get_tag_page($template_page)) : 
		elseif ($main_body_template = $this->get_author_page($template_page)) :

			// load based on query options (used for index.php template) 
		elseif (is_404() && $main_body_template = $this->get_404_type()) : 
		elseif (is_search() && $main_body_template = $this->get_search_type()) : 
		elseif (is_tax() && $main_body_template = $this->get_taxonomy_type()) : 
		elseif ($is_wp3 && is_front_page() && $main_body_template = $this->get_front_page_type()) : 
		elseif (is_home() && $main_body_template = $this->get_home_type()) : 
		elseif (is_attachment() && $main_body_template = $this->get_attachment_type()) :
		remove_filter('the_content', 'prepend_attachment'); 
		elseif (is_single() && $main_body_template = $this->get_single_type()) : 
		elseif (is_page() && $main_body_template = $this->get_page_type()) : 
		elseif (is_category() && $main_body_template = $this->get_category_type()) : 
		elseif (is_tag() && $main_body_template = $this->get_tag_type()) : 
		elseif (is_author() && $main_body_template = $this->get_author_type()) : 
		elseif (is_date() && $main_body_template = $this->get_date_type()) : 
		elseif (is_archive() && $main_body_template = $this->get_archive_type()) : 
		elseif (is_comments_popup() && $main_body_template = $this->get_comments_popup_type()) : 
		elseif (is_paged() && $main_body_template = $this->get_paged_type()) : 
		else :
			$main_body_template = $this->get_index_type();
		endif;

		if ($main_body_template = apply_filters('gantry_mainbody_include', $main_body_template)) include($main_body_template);
		return;
	}

	/**
	 * Retrieve path to file without the use of extension.
	 *
	 * Used to quickly retrieve the path of file without including the file
	 * extension. It will also check the parent template, if the file exists, with
	 * the use of {@link locate_type()}. Allows for more generic file location
	 * without the use of the other get_*_type() functions.
	 *
	 * Can be used with include() or require() to retrieve path.
	 * <code>
	 * if( '' != get_query_type( '404' ) )
	 *     include( get_query_type( '404' ) );
	 * </code>
	 * or the same can be accomplished with
	 * <code>
	 * if( '' != get_404_type() )
	 *     include( get_404_type() );
	 * </code>
	 *
	 * @since 1.5.0
	 *
	 * @param string $type Filename without extension.
	 *
	 * @return string Full path to file.
	 */
	function get_query_type($type)
	{
		$type = preg_replace('|[^a-z0-9-]+|', '', $type);
		return apply_filters("gantry_mainbody_{$type}_type", $this->locate_type(array("{$type}.php")));
	}

	function get_index_type()
	{
		return $this->get_query_type('index');
	}

	/**
	 * Retrieve path of 404 template in current or parent template.
	 *
	 * @since 1.5.0
	 *
	 * @return string
	 */
	function get_404_type()
	{
		return $this->get_query_type('404');
	}

	/**
	 * Retrieve path of archive template in current or parent template.
	 *
	 * @since 1.5.0
	 *
	 * @return string
	 */
	function get_archive_type()
	{
		/** @global $gantry Gantry */
		global $gantry;
		$template = 'archive';
		if (version_compare($gantry->platform->platform_version, "3.0", ">=")) {
			$post_type = get_query_var('post_type');

			$templates = array();

			if ($post_type) $templates[] = "archive-{$post_type}.php";
			$templates[] = 'archive.php';

			$template = $this->locate_type($templates);
		}

		return apply_filters('gantry_mainbody_archive_type', $template);
	}

	/**
	 * Retrieve path of author template in current or parent template.
	 *
	 * @since 1.5.0
	 *
	 * @return string
	 */
	function get_author_type()
	{
		/** @global $gantry Gantry */
		global $gantry;
		$template = "author";
		if (version_compare($gantry->platform->platform_version, "3.0", ">=")) {
			$author_id = absint(get_query_var('author'));
			$author    = get_user_by('id', $author_id);
			$author    = $author->user_nicename;

			$templates = array();

			if ($author) $templates[] = "author-{$author}.php";
			if ($author_id) $templates[] = "author-{$author_id}.php";
			$templates[] = 'author.php';

			$template = $this->locate_type($templates);
		}
		return apply_filters('gantry_mainbody_author_type', $template);
	}

	function get_author_page($page)
	{
		/** @global $gantry Gantry */
		global $gantry;
		$template  = "author";
		$templates = array();
		if (version_compare($gantry->platform->platform_version, "3.0", ">=")) {
			if (preg_match('/^author/i', $page)) {

				if (preg_match('/^author-(\w+).php/i', $page, $matches)) {
					$templates[] = "author-{$matches[1]}.php";
				}
				$templates[] = 'author.php';
			}
			$template = $this->locate_type($templates);
		}
		return apply_filters('gantry_mainbody_author_type', $template);
	}

	/**
	 * Retrieve path of category template in current or parent template.
	 *
	 * Works by first retrieving the current slug for example 'category-default.php' and then
	 * trying category ID, for example 'category-1.php' and will finally fallback to category.php
	 * template, if those files don't exist.
	 *
	 * @since 1.5.0
	 * @uses  apply_filters() Calls 'category_type' on file path of category template.
	 *
	 * @return string
	 */
	function get_category_type()
	{
		$cat_ID   = absint(get_query_var('cat'));
		$category = get_category($cat_ID);

		$templates = array();

		if (!is_wp_error($category)) $templates[] = "category-{$category->slug}.php";

		$templates[] = "category-$cat_ID.php";
		$templates[] = "category.php";

		$template = $this->locate_type($templates);
		return apply_filters('category_type', $template);
	}

	function get_category_page($page)
	{
		$template  = "category";
		$templates = array();
		if (preg_match('/^category/i', $page)) {

			if (preg_match('/^category-(\w+).php/i', $page, $matches)) {
				$templates[] = "category-{$matches[1]}.php";
			}
			$templates[] = 'category.php';
		}
		$template = $this->locate_type($templates);
		return apply_filters('gantry_mainbody_category_type', $template);
	}

	/**
	 * Retrieve path of tag template in current or parent template.
	 *
	 * Works by first retrieving the current tag name, for example 'tag-wordpress.php' and then
	 * trying tag ID, for example 'tag-1.php' and will finally fallback to tag.php
	 * template, if those files don't exist.
	 *
	 * @since 2.3.0
	 * @uses  apply_filters() Calls 'tag_type' on file path of tag template.
	 *
	 * @return string
	 */
	function get_tag_type()
	{
		$tag_id   = absint(get_query_var('tag_id'));
		$tag_name = get_query_var('tag');

		$templates = array();

		if ($tag_name) $templates[] = "tag-$tag_name.php";
		if ($tag_id) $templates[] = "tag-$tag_id.php";
		$templates[] = "tag.php";

		$template = $this->locate_type($templates);
		return apply_filters('tag_type', $template);
	}

	function get_tag_page($page)
	{
		$template  = "tag";
		$templates = array();
		if (preg_match('/^tag/i', $page)) {

			if (preg_match('/^tag-(\w+).php/i', $page, $matches)) {
				$templates[] = "tag-{$matches[1]}.php";
			}
			$templates[] = 'tag.php';
		}
		$template = $this->locate_type($templates);
		return apply_filters('gantry_mainbody_tag_type', $template);
	}

	/**
	 * Retrieve path of taxonomy template in current or parent template.
	 *
	 * Retrieves the taxonomy and term, if term is available. The template is
	 * prepended with 'taxonomy-' and followed by both the taxonomy string and
	 * the taxonomy string followed by a dash and then followed by the term.
	 *
	 * The taxonomy and term template is checked and used first, if it exists.
	 * Second, just the taxonomy template is checked, and then finally, taxonomy.php
	 * template is used. If none of the files exist, then it will fall back on to
	 * index.php.
	 *
	 * @since unknown (2.6.0 most likely)
	 * @uses  apply_filters() Calls 'taxonomy_type' filter on found path.
	 *
	 * @return string
	 */
	function get_taxonomy_type()
	{
		$taxonomy = get_query_var('taxonomy');
		$term     = get_query_var('term');

		$templates = array();
		if ($taxonomy && $term) $templates[] = "taxonomy-$taxonomy-$term.php";
		if ($taxonomy) $templates[] = "taxonomy-$taxonomy.php";

		$templates[] = "taxonomy.php";

		$template = $this->locate_type($templates);
		return apply_filters('taxonomy_type', $template);
	}

	function get_taxonomy_page($page)
	{
		$template  = "taxonomy";
		$templates = array();
		if (preg_match('/^taxonomy/i', $page)) {
			if (preg_match('/^taxonomy-(\w+).php/i', $page, $matches)) {
				$templates[] = "taxonomy-{$matches[1]}.php";
			}
			if (preg_match('/^taxonomy-(\w+)-(\w+).php/i', $page, $matches)) {
				$templates[] = "taxonomy-{$matches[1]}-{$matches[2]}.php";
			}
			$templates[] = 'taxonomy.php';
		}
		$template = $this->locate_type($templates);
		return apply_filters('gantry_mainbody_taxonomy_type', $template);
	}

	/**
	 * Retrieve path of date template in current or parent template.
	 *
	 * @since 1.5.0
	 *
	 * @return string
	 */
	function get_date_type()
	{
		return $this->get_query_type('date');
	}

	/**
	 * Retrieve path of home template in current or parent template.
	 *
	 * Attempts to locate 'home.php' first before falling back to 'index.php'.
	 *
	 * @since 1.5.0
	 * @uses  apply_filters() Calls 'home_type' on file path of home template.
	 *
	 * @return string
	 */
	function get_home_type()
	{
		$template = $this->locate_type(array('home.php', 'index.php'));
		return apply_filters('home_type', $template);
	}

	/**
	 * Retrieve path of page template in current or parent template.
	 *
	 * Will first look for the specifically assigned page template
	 * The will search for 'page-{slug}.php' followed by 'page-id.php'
	 * and finally 'page.php'
	 *
	 * @since 1.5.0
	 *
	 * @return string
	 */
	function get_page_type()
	{
		global $wp_query;

		$id       = (int)$wp_query->post->ID;
		$id       = (int)$wp_query->get_queried_object_id();
		$template = get_post_meta($id, '_wp_page_type', true);
		$pagename = get_query_var('pagename');

		if (!$pagename && $id > 0) {
			// If a static page is set as the front page, $pagename will not be set. Retrieve it from the queried object
			$post     = $wp_query->get_queried_object();
			$pagename = $post->post_name;
		}

		if ('default' == $template) $template = '';

		$templates = array();
		if (!empty($template) && !validate_file($template)) $templates[] = $template;
		if ($pagename) $templates[] = "page-$pagename.php";
		if ($id) $templates[] = "page-$id.php";
		$templates[] = "page.php";

		return apply_filters('page_type', $this->locate_type($templates));
	}

	function get_page_page($page)
	{
		$template  = "page";
		$templates = array();
		if (preg_match('/^page/i', $page)) {

			if (preg_match('/^page-(\w+).php/i', $page, $matches)) {
				$templates[] = "page-{$matches[1]}.php";
			}
			$templates[] = 'page.php';
		}
		$template = $this->locate_type($templates);
		return apply_filters('gantry_mainbody_page_type', $template);
	}

	/**
	 * Retrieve path of paged template in current or parent template.
	 *
	 * @since 1.5.0
	 *
	 * @return string
	 */
	function get_paged_type()
	{
		return $this->get_query_type('paged');
	}

	/**
	 * Retrieve path of search template in current or parent template.
	 *
	 * @since 1.5.0
	 *
	 * @return string
	 */
	function get_search_type()
	{
		return $this->get_query_type('search');
	}

	/**
	 * Retrieve path of single template in current or parent template.
	 *
	 * @since 1.5.0
	 *
	 * @return string
	 */
	function get_single_type()
	{
		global $wp_query, $gantry;
		if (version_compare($gantry->platform->platform_version, "3.0", ">=")) {
			$object    = $wp_query->get_queried_object();
			$templates = array('single-' . $object->post_type . '.php', 'single.php');
			return apply_filters('gantry_mainbody_single_template', $this->locate_type($templates));
		}
		return $this->get_query_type('single');
	}

	function get_single_page($page)
	{
		$template  = "single";
		$templates = array();
		if (preg_match('/^single/i', $page)) {

			if (preg_match('/^single-(\w+).php/i', $page, $matches)) {
				$templates[] = "single-{$matches[1]}.php";
			}
			$templates[] = 'single.php';
		}
		$template = $this->locate_type($templates);
		return apply_filters('gantry_mainbody_single_type', $template);
	}

	function get_front_page_type()
	{
		return apply_filters('front_page_type', $this->locate_type(array('front-page.php')));
	}

	/**
	 * Retrieve path of attachment template in current or parent template.
	 *
	 * The attachment path first checks if the first part of the mime type exists.
	 * The second check is for the second part of the mime type. The last check is
	 * for both types separated by an underscore. If neither are found then the file
	 * 'attachment.php' is checked and returned.
	 *
	 * Some examples for the 'text/plain' mime type are 'text.php', 'plain.php', and
	 * finally 'text_plain.php'.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	function get_attachment_type()
	{
		global $posts;
		$type = explode('/', $posts[0]->post_mime_type);
		if ($template = $this->get_query_type($type[0])) return $template; elseif ($template = $this->get_query_type($type[1])) return $template; elseif ($template = $this->get_query_type("$type[0]_$type[1]")) return $template; else
			return $this->get_query_type('attachment');
	}

	/**
	 * Retrieve path of comment popup template in current or parent template.
	 *
	 * Checks for comment popup template in current template, if it exists or in the
	 * parent template. If it doesn't exist, then it retrieves the comment-popup.php
	 * file from the default theme. The default theme must then exist for it to
	 * work.
	 *
	 * @since 1.5.0
	 * @uses  apply_filters() Calls 'comments_popup_type' filter on path.
	 *
	 * @return string
	 */
	function get_comments_popup_type()
	{
		$template = $this->locate_type(array("comments-popup.php"));
		if ('' == $template) $template = get_theme_root() . '/default/comments-popup.php';

		return apply_filters('comments_popup_type', $template);
	}

	/**
	 * get the current page context
	 * @return string
	 */
	function getContext() {
		if( isset( $this->context ) )
			return $this->context;

		if( is_home() ) {
			$this->context = 'blog';
		} elseif( is_single() ) {
			$this->context = 'single';
		} elseif( is_page() ) {
			$this->context = 'page';
		} elseif( is_category() ) {
			$this->context = 'category';
		} elseif( is_tag() ) {
			$this->context = 'tag';
		} elseif( is_archive() ) {
			$this->context = 'archive';
		} elseif( is_search() ) {
			$this->context = 'search';
		} elseif( is_404() ) {
			$this->context = '404';
		}

		return $this->context;
	}

	/**
	 * Retrieve the name of the highest priority template file that exists.
	 *
	 * Searches in the STYLESHEETPATH before TEMPLATEPATH so that themes which
	 * inherit from a parent theme can just overload one file.
	 *
	 * @since 2.7.0
	 *
	 * @param array $template_names Array of template files to search for in priority order.
	 * @param bool  $load           If true the template file will be loaded if it is found.
	 *
	 * @return string The template filename if one is located.
	 */
	function locate_type($template_names, $load = false, $require_once = true)
	{
		/** @global $gantry Gantry */
		global $gantry;

		if (!is_array($template_names)) return ''; else
			$template_names = array_reverse($template_names);

		$located = '';
		foreach ($template_names as $template_name) {
			foreach ($gantry->getContentTypePaths() as $contentTypePath) {
				if (file_exists($contentTypePath . '/' . $template_name)) {
					$located = $contentTypePath . '/' . $template_name;
					break;
				}
			}
		}

		if ($load && '' != $located) $this->load_type($located, $require_once);

		return $located;
	}

	/**
	 * Require the template file with WordPress environment.
	 *
	 * The globals are set up for the template file to ensure that the WordPress
	 * environment is available from within the function. The query variables are
	 * also available.
	 *
	 * @since 1.5.0
	 *
	 * @param string $_template_file Path to template file.
	 * @param bool   $require_once   Whether to require_once or require. Default true.
	 */
	function load_type($_template_file, $require_once = true)
	{
		global $gantry, $posts, $post, $wp_did_header, $wp_did_template_redirect, $wp_query, $wp_rewrite, $wpdb, $wp_version, $wp, $id, $comment, $user_ID;

		if (is_array($wp_query->query_vars)) extract($wp_query->query_vars, EXTR_SKIP);

		if ($require_once) require_once($_template_file); else
			require($_template_file);
	}

	/**
	 * Load a template part into a template
	 *
	 * Makes it easy for a theme to reuse sections of code in a easy to overload way
	 * for child themes.
	 *
	 * Includes the named template part for a theme or if a name is specified then a
	 * specialised part will be included. If the theme contains no {slug}.php file
	 * then no template will be included.
	 *
	 * The template is included using require, not require_once, so you may include the
	 * same template part multiple times.
	 *
	 * For the $name parameter, if the file is called "{slug}-special.php" then specify
	 * "special".
	 *
	 * @uses locate_template()
	 * @since 3.0.0
	 * @uses do_action() Calls 'get_template_part_{$slug}' action.
	 *
	 * @param string $slug The slug name for the generic template.
	 * @param string $name The name of the specialised template.
	 */
	function gantry_get_template_part( $slug, $name = null ) {
		do_action( "get_template_part_{$slug}", $slug, $name );

		$templates = array();
		$name = (string) $name;
		if ( '' !== $name )
			$templates[] = "{$slug}-{$name}.php";

		$templates[] = "{$slug}.php";

		$this->locate_type( $templates, true, false );
	}

	/**
	 * Load a template part into a template
	 *
	 * Includes the named template part for a theme or if a name is specified then a
	 * specialised part will be included. If the theme contains no {slug}.php file
	 * then no template will be included.
	 *
	 * The template is included using require, not require_once, so you may include the
	 * same template part multiple times.
	 *
	 * For the $name parameter, if the file is called "{slug}-special.php" then specify
	 * "special".
	 *
	 * Function supports third parameter which let's you allow the check for the post
	 * formats and post types.
	 *
	 * @param string $slug The slug name for the generic template.
	 * @param string $name The name of the specialised template.
	 * @param boolean $use_context Adds the context to the template name for more flexibility
	 */
	function get_content_template( $slug, $name = null, $use_context = true ) {
		do_action( "get_content_template_{$slug}", $slug, $name );

		/** for Custom Post Types, allow different template files in different contexts */
		$context = $this->getContext();

		$templates = array();
		$name = (string) $name;

		if( $use_context && isset( $context ) && $name !== '' )
			$templates[] = "{$slug}-{$context}-{$name}.php";

		if ( '' !== $name )
			$templates[] = "{$slug}-{$name}.php";

		if( $use_context && isset( $context ) && $name !== $context )
			$templates[] = "{$slug}-{$context}.php";

		$templates[] = "{$slug}.php";

		$this->locate_type($templates, true, false);
	}

}