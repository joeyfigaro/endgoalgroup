<?php
/**
 * @version   $Id: title.php 59687 2013-05-16 13:25:33Z jakub $
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2014 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */


defined('GANTRY_VERSION') or die();

gantry_import('core.gantrygizmo');

/**
 * @package     gantry
 * @subpackage  features
 */
class GantryGizmoTitle extends GantryGizmo
{

	var $_name = 'title';

	function isEnabled()
	{
		return true;
	}


	function query_parsed_init()
	{
		global $gantry, $post, $s, $wp_query;

		$aioseo_options = get_option('aioseop_options');
		if( $post !== null ) $aioseo_title   = get_post_meta($post->ID, '_aioseop_title', true);
		$title = trim($gantry->get('title-format'));

		if ($aioseo_options != false && $aioseo_options['aiosp_home_title'] != '' && (is_home() || is_front_page())) {

			$title             = $aioseo_options['aiosp_home_title'];
			$title = apply_filters('wp_title', $title, '»', '');
			$gantry->pageTitle = trim($title);

		} elseif (is_singular() && !empty($aioseo_title) && $aioseo_title != '') {

			$title             = $aioseo_title;
			$title = apply_filters('wp_title', $title, '»', '');
			$gantry->pageTitle = trim($title);

		} elseif ($title != '') {

			// Single posts
			if (is_single()) {

				$categories = get_the_category();
				if (count($categories) > 0) {
					$category = $categories[0]->cat_name;
				}

				$author = get_userdata($post->post_author);

				$token = array(
					'%blog_title%',
					'%blog_description%',
					'%post_title%',
					'%category%',
					'%post_author_login%',
					'%post_author_nicename%',
					'%post_author_firstname%',
					'%post_author_lastname%'
				);

				$replace = array(
					get_bloginfo('name'),
					get_bloginfo('description'),
					get_the_title(),
					$category,
					$author->user_login,
					$author->user_nicename,
					$author->first_name,
					$author->last_name
				);

				$title = str_replace($token, $replace, $title);

				// The home page or, if using a static front page, the blog posts page.
			} elseif (is_home() || is_front_page()) {

				$token = array(
					'%blog_title%',
					'%blog_description%'
				);

				$replace = array(
					get_bloginfo('name'),
					get_bloginfo('description')
				);

				$title = str_replace($token, $replace, $title);

				// WordPress Pages
			} elseif (is_page()) {

				$author = get_userdata($post->post_author);

				$token = array(
					'%blog_title%',
					'%blog_description%',
					'%page_title%',
					'%page_author_login%',
					'%page_author_nicename%',
					'%page_author_firstname%',
					'%page_author_lastname%'
				);

				$replace = array(
					get_bloginfo('name'),
					get_bloginfo('description'),
					get_the_title(),
					$author->user_login,
					$author->user_nicename,
					$author->first_name,
					$author->last_name
				);

				$title = str_replace($token, $replace, $title);

				// Category Pages
			} elseif (is_category()) {

				$token = array(
					'%blog_title%',
					'%blog_description%',
					'%category_title%',
					'%category_description%'
				);

				$replace = array(
					get_bloginfo('name'),
					get_bloginfo('description'),
					single_cat_title('', false),
					category_description()
				);

				$title = str_replace($token, $replace, $title);

				// Archive Pages
			} elseif (is_day() || is_month() || is_year() || is_author() || is_archive()) {

				$token = array(
					'%blog_title%',
					'%blog_description%',
					'%date%'
				);

				$replace = array(
					get_bloginfo('name'),
					get_bloginfo('description'),
					wp_title('', false)
				);

				$title = str_replace($token, $replace, $title);

				// Tags Pages
			} elseif (is_tag()) {

				$token = array(
					'%blog_title%',
					'%blog_description%',
					'%tag%'
				);

				$replace = array(
					get_bloginfo('name'),
					get_bloginfo('description'),
					$wp_query->query_vars['tag']
				);

				$title = str_replace($token, $replace, $title);

				// Search results
			} elseif (is_search()) {

				$token = array(
					'%blog_title%',
					'%blog_description%',
					'%search%'
				);

				$replace = array(
					get_bloginfo('name'),
					get_bloginfo('description'),
					$s
				);

				$title = str_replace($token, $replace, $title);

				// 404 (Not Found)
			} elseif (is_404()) {

				$token = array(
					'%blog_title%',
					'%blog_description%',
					'%request_url%',
					'%404_title%'
				);

				$replace = array(
					get_bloginfo('name'),
					get_bloginfo('description'),
					$_SERVER['REQUEST_URI'],
					wp_title('', false)
				);

				$title = str_replace($token, $replace, $title);

				// Otherwise:
			} else {

				$title = wp_title('', false) . ' | ' . get_bloginfo('name');

			}

			$title = apply_filters('wp_title', $title, '»', '');
			$gantry->pageTitle = trim($title);


		} else {

			$title = '';

			// Single posts
			if (is_single()) {
				$title = single_post_title('', false) . ' | ' . get_bloginfo('name');

				// The home page or, if using a static front page, the blog posts page.
			} elseif (is_home() || is_front_page()) {
				$title = get_bloginfo('name');
				if (get_bloginfo('description')) $title .= ' | ' . get_bloginfo('description');

				// WordPress Pages
			} elseif (is_page()) {
				$title = single_post_title('', false) . ' | ' . get_bloginfo('name');

				// Search results
			} elseif (is_search()) {
				$title = _g('Search results for ') . '"' . get_search_query() . '"' . ' | ' . get_bloginfo('name');

				// 404 (Not Found)
			} elseif (is_404()) {
				$title = _g('Not Found') . ' | ' . get_bloginfo('name');

				// Otherwise:
			} else {
				$title = wp_title('', false) . ' | ' . get_bloginfo('name');
			}

			$title = apply_filters('wp_title', $title, '»', '');
			$gantry->pageTitle = apply_filters('gantry_title_gizmo', $title);

		}

	}
}