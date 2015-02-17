<?php
/**
 * @version   $Id: recentposts.php 60832 2014-05-12 09:47:23Z jakub $
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2014 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 * Widget based on the WordPress core Recent Posts Widget.
 */

defined('GANTRY_VERSION') or die();

gantry_import('core.gantrywidget');

add_action('widgets_init', array("GantryWidgetRecentPosts", "init"));
add_action('comment_post', array("GantryWidgetRecentPosts", 'gantry_flush_widget_cache'));
add_action('transition_comment_status', array("GantryWidgetRecentPosts", 'gantry_flush_widget_cache'));

class GantryWidgetRecentPosts extends GantryWidget
{
	var $short_name = 'recentposts';
	var $wp_name = 'gantry_recentposts';
	var $long_name = 'Gantry Recent Posts';
	var $description = 'Gantry Recent Posts Widget';
	var $css_classname = 'widget_gantry_recentposts';
	var $width = 200;
	var $height = 400;

	static function gantry_flush_widget_cache()
	{
		wp_cache_delete('gantry_recentposts', 'widget');
	}

	static function init()
	{
		register_widget("GantryWidgetRecentPosts");
	}

	function render_title($args, $instance)
	{
		/** @global $gantry Gantry */
        global $gantry;
		if ($instance['title'] != '') :
            echo apply_filters( 'widget_title', $instance['title'], $instance );
		endif;
	}

	function render($args, $instance)
	{
		/** @global $gantry Gantry */
        global $gantry;

		ob_start();

		$menu_class = $instance['menu_class'];
		$number     = $instance['number'];
		$cat        = $instance['cat'];

		if ($menu_class != '') :
			$menu_class = ' class="' . $menu_class . '"'; else :
			$menu_class = '';
		endif;

		if (!$number = (int)$instance['number']) $number = 10; else if ($number < 1) $number = 1; else if ($number > 15) $number = 15;

		$cache = wp_cache_get('gantry_recentposts', 'widget');

		if (!is_array($cache)) $cache = array();

		if (isset($cache[$args['widget_id']])) {
			echo $cache[$args['widget_id']];
			return;
		}

		$rp = new WP_Query(array('showposts'       => $number,
		                        'nopaging'         => 0,
		                        'post_status'      => 'publish',
		                        'ignore_sticky_posts' => 1,
		                        'cat'              => $cat
		                   ));
		if ($rp->have_posts()) : ?>

			<ul<?php echo $menu_class;?>>

				<?php  while ($rp->have_posts()) : $rp->the_post(); ?>

					<li>
						<a href="<?php the_permalink(); ?>" title="<?php echo esc_attr(get_the_title() ? get_the_title() : get_the_ID()); ?>"><span><?php if (get_the_title()) the_title(); else the_ID(); ?></span></a>
					</li>

				<?php endwhile; ?>

			</ul>

			<?php
			// Reset the global $the_post as this query will have stomped on it
			wp_reset_postdata();

		endif;

		$cache[$args['widget_id']] = ob_get_flush();
		wp_cache_set('gantry_recentposts', $cache, 'widget');

	}
}