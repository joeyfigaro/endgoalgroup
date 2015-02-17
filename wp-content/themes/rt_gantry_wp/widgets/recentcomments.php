<?php
/**
 * @version   4.1.2 May 18, 2014
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2014 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 * Widget based on the WordPress core Recent Comments Widget.
 */

defined( 'GANTRY_VERSION' ) or die();

gantry_import( 'core.gantrywidget' );

add_action( 'widgets_init', array( 'GantryWidgetRecentComments', 'init' ) );
add_action( 'comment_post', array( 'GantryWidgetRecentComments', 'gantry_flush_widget_cache' ) );
add_action( 'transition_comment_status', array( 'GantryWidgetRecentComments', 'gantry_flush_widget_cache' ) );

class GantryWidgetRecentComments extends GantryWidget
{
	var $short_name = 'recentcomments';
	var $wp_name = 'gantry_recentcomments';
	var $long_name = 'Gantry Recent Comments';
	var $description = 'Gantry Recent Comments Widget';
	var $css_classname = 'widget_gantry_recentcomments';
	var $width = 200;
	var $height = 400;

	static function gantry_flush_widget_cache()
	{
		wp_cache_delete( 'gantry_recentcomments', 'widget' );
	}

	static function init()
	{
		register_widget( 'GantryWidgetRecentComments' );
	}

	function render_title( $args, $instance ) {
		/** @global $gantry Gantry */
		global $gantry;
		if( $instance['title'] != '' ) :
            echo apply_filters( 'widget_title', $instance['title'], $instance );
		endif;
	}

	function render( $args, $instance ) {
		global $gantry, $comments, $comment;

		ob_start();

		$menu_class = $instance['menu_class'];
		$link_class = $instance['link_class'];

		if($menu_class != '') :
			$menu_class = ' class="' . $menu_class . '"'; else :
			$menu_class = '';
		endif;

		if($link_class != '') :
			$link_class = ' class="' . $link_class . '"'; else :
			$link_class = '';
		endif;

		$cache = wp_cache_get('gantry_recentcomments', 'widget');

		if( !is_array( $cache ) ) $cache = array();

		if( isset( $cache[$args['widget_id']] ) ) {
			echo $cache[$args['widget_id']];
			return;
		}

		$output = '';

		if( !$number = ( int ) $instance['number'] ) $number = 5; else if( $number < 1 ) $number = 1;

		if( !$word_limit = ( int ) $instance['word_limit'] ) $word_limit = 8; else if( $word_limit < 1 ) $word_limit = 1;

		$comments = get_comments( array( 'number' => $number, 'status' => 'approve' ) );

		$output .= '<ul' . $menu_class . '>';

		if( $comments ) {
			foreach ( ( array ) $comments as $comment ) {

				$words        = explode( ' ', strip_tags( $comment->comment_content ) );
				$comment_text = implode( ' ', array_slice( $words, 0, $word_limit ) );
				$avatar       = get_avatar( $comment->comment_author_email, $size = 32 );
				$avatar       = str_replace( "class='", "class='rt-image ", $avatar );

				$output .= '<li class="comment-item">';
				$output .= $avatar;
				$output .= '<blockquote>';
				$output .= '<a href="' . get_comment_link( $comment->comment_ID ) . '"' . $link_class . '>' . $comment_text . '...</a><br />';
				$output .= sprintf( _r( 'By %1$s' ), $comment->comment_author );
				$output .= '</blockquote>';
				$output .= '</li>';
			}
		}

		$output .= '</ul>';

		echo $output;

		$cache[$args['widget_id']] = $output;

		wp_cache_set( 'gantry_recentcomments', $cache, 'widget' );

		echo ob_get_clean();

	}
}