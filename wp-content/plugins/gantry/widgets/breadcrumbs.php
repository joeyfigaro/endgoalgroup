<?php
/**
 * @version   4.1.2 May 18, 2014
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2014 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */
defined( 'GANTRY_VERSION' ) or die();

gantry_import( 'core.gantrywidget' );

add_action( 'widgets_init', array( 'GantryWidgetBreadcrumbs', 'init' ) );

class GantryWidgetBreadcrumbs extends GantryWidget {
	var $short_name = 'breadcrumbs';
	var $wp_name = 'gantry_breakcrumbs';
	var $long_name = 'Gantry Breadcrumbs';
	var $description = 'Gantry Breadcrumbs Widget';
	var $css_classname = 'widget_gantry_breadcrumbs';
	var $width = 200;
	var $height = 400;

	static function init() {
		register_widget( 'GantryWidgetBreadcrumbs' );
	}

	function render( $args, $instance ) {
		global $gantry, $post, $author, $wp_query;

		$defaults = apply_filters( 'gantry_breadcrumbs_defaults', array(
				'before_breadcrumbs' => '<div class="breadcrumbs">',
				'after_breadcrumbs' => '</div>',
				'before' => '',
				'after' => '',
				'home' => _r( 'Home' ),
				'delimiter' => '<img alt="" class="breadcrumbs-delimiter" src="' . $gantry->templateUrl . '/images/arrow.png" />'
			) );

		extract( $defaults );

		ob_start();

		do_action( 'gantry_breakcrumbs_before_widget' );
		
		if( !is_front_page() ) {

			echo $before_breadcrumbs;

				if( $instance['prefix'] != '' ) {

					do_action( 'gantry_breakcrumbs_before_prefix' );

					echo '<span class="showHere breadcrumbs-prefix">' . $instance['prefix'] . '</span>';

					do_action( 'gantry_breakcrumbs_after_prefix' );

				}

				if( !empty( $home ) ) {

					do_action( 'gantry_breakcrumbs_before_home' );

					echo $before . '<a href="' . apply_filters( 'gantry_breadcrumbs_home_url', home_url() ) . '" class="pathway breadcrumbs-home" title="' . $home . '">' . $home . '</a>' . $after . $delimiter;

					do_action( 'gantry_breakcrumbs_after_home' );
					
				}

				do_action( 'gantry_breakcrumbs_before_items' );

                if ( is_home() ) {

                    $queried_object = $wp_query->get_queried_object();
                    $title = $queried_object->post_title;

                    echo $before . '<span>' . $title . '</span>' . $after;

                } elseif ( is_category() ) {

					$queried_object = $wp_query->get_queried_object();
					$category = get_category( $queried_object->term_id );

					if ( $category->parent != 0 ) {
						$parent_category = get_category( $category->parent );
						$cat_parents = get_category_parents( $parent_category, true, $delimiter );
						$cat_parents = str_replace( '<a', '<a class="pathway"', $cat_parents );
						echo $cat_parents;
					}

					echo $before . '<span>' . single_cat_title( '', false ) . '</span>' . $after;
				
				} elseif ( is_day() ) {

					echo $before . '<a href="' . get_year_link( get_the_time( 'Y' ) ) . '" class="pathway">' . get_the_time( 'Y' ) . '</a>' . $after . $delimiter;
					echo $before . '<a href="' . get_month_link( get_the_time( 'Y' ), get_the_time( 'm' ) ) . '" class="pathway">' . get_the_time( 'F' ) . '</a>' . $after . $delimiter;
					echo $before . '<span>' . get_the_time( 'd' ) . '</span>' . $after;

				} elseif ( is_month() ) {

					echo $before . '<a href="' . get_year_link( get_the_time( 'Y' ) ) . '" class="pathway">' . get_the_time( 'Y' ) . '</a>' . $after . $delimiter;
					echo $before . '<span>' . get_the_time( 'F' ) . '</span>' . $after;

				} elseif ( is_year() ) {

					echo $before . '<span>' . get_the_time( 'Y' ) . '</span>' . $after;

				} elseif ( is_single() && !is_attachment() ) {

					if ( get_post_type() != 'post' ) {

						$post_type = get_post_type_object( get_post_type() );
						$slug = $post_type->rewrite;
							echo $before . '<a href="' . get_post_type_archive_link( get_post_type() ) . '" class="pathway">' . $post_type->labels->singular_name . '</a>' . $after . $delimiter;
						echo $before . '<span>' . get_the_title() . '</span>' . $after;

					} else {

						$the_category = get_the_category();
						$cat = current( $the_category );
						$cat_parents = get_category_parents( $cat, true, $delimiter );
						$cat_parents = str_replace( '<a', '<a class="pathway"', $cat_parents );
						echo $cat_parents;
						echo $before . '<span>' . get_the_title() . '</span>' . $after;

					}

				} elseif ( is_404() ) {

					echo $before . '<span>' . _r( 'Error 404' ) . '</span>' . $after;

				} elseif ( !is_single() && !is_page() && get_post_type() != 'post' && !is_search() ) {

					$post_type = get_post_type_object( get_post_type() );

					if ( $post_type )
						echo $before . '<span>' . $post_type->labels->singular_name . '</span>' . $after;

				} elseif ( is_attachment() ) {

					$parent = get_post( $post->post_parent );
					$cat = get_the_category( $parent->ID );
					if( !empty( $cat ) ) {
						$cat = $cat[0];
						$cat_parents = get_category_parents( $cat, true, '' . $delimiter );
						$cat_parents = str_replace( '<a', '<a class="pathway"', $cat_parents );
						echo $cat_parents;
					}
					echo $before . '<a href="' . get_permalink( $parent ) . '" class="pathway">' . $parent->post_title . '</a>' . $after . $delimiter;
					echo $before . '<span>' . get_the_title() . '</span>' . $after;

				} elseif ( is_page() && !$post->post_parent ) {

					echo $before . '<span>' . get_the_title() . '</span>' . $after;

				} elseif ( is_page() && $post->post_parent ) {

					$parent_id  = $post->post_parent;
					$breadcrumbs = array();

					while ( $parent_id ) {
						$page = get_page( $parent_id );
						$breadcrumbs[] = '<a href="' . get_permalink($page->ID) . '" class="pathway">' . get_the_title( $page->ID ) . '</a>';
						$parent_id  = $page->post_parent;
					}

					$breadcrumbs = array_reverse( $breadcrumbs );

					foreach ( $breadcrumbs as $crumb )
						echo $crumb . '' . $delimiter;

					echo $before . '<span>' . get_the_title() . '</span>' . $after;

				} elseif ( is_search() ) {

					echo $before . '<span>' . _r( 'Search results for &ldquo;' ) . get_search_query() . '&rdquo;' . '</span>' . $after;

				} elseif ( is_tag() ) {

						echo $before . '<span>' . _r( 'Posts tagged &ldquo;' ) . single_tag_title('', false) . '&rdquo;' . '</span>' . $after;

				} elseif ( is_author() ) {

					$userdata = get_userdata( $author );
					echo $before . '<span>' . _r( 'Author:' ) . ' ' . $userdata->display_name . '</span>' . $after;

				}

				do_action( 'gantry_breakcrumbs_after_items' );

			echo $after_breadcrumbs;
		}

		do_action( 'gantry_breakcrumbs_after_widget' );

		echo apply_filters( 'gantry_breadcrumbs', ob_get_clean() );
		
	}
}