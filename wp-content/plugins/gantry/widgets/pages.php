<?php
/**
 * @version   $Id: pages.php 60832 2014-05-12 09:47:23Z jakub $
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2014 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 * Widget based on the WordPress core Pages Widget.
 */
defined('GANTRY_VERSION') or die();

gantry_import('core.gantrywidget');

add_action('widgets_init', array("GantryWidgetPages", "init"));

class GantryWidgetPages extends GantryWidget
{
	var $short_name = 'pages';
	var $wp_name = 'gantry_pages';
	var $long_name = 'Gantry Pages';
	var $description = 'Gantry Pages Widget';
	var $css_classname = 'widget_gantry_pages';
	var $width = 200;
	var $height = 400;

	static function init()
	{
		register_widget("GantryWidgetPages");
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
		global $gantry, $post;
		ob_start();

		$sortby     = $instance['sortby'];
		$menu_class = $instance['menu_class'];
		$depth      = $instance['depth'];

		if ($sortby == 'menu_order') $sortby = 'menu_order, post_title';

		if ($menu_class != '') :
			$menu_class = ' class="' . $menu_class . '"'; else :
			$menu_class = '';
		endif;

		$out = wp_list_pages(apply_filters('widget_pages_args', array('title_li'   => '',
		                                                             'echo'        => 0,
		                                                             'depth'       => $depth,
		                                                             'sort_column' => $sortby,
		                                                             'exclude'     => $instance['exclude'],
		                                                             'link_before' => '<span>',
		                                                             'link_after'  => '</span>'
		                                                        )));

		$out = str_replace('current_page_item', 'current_page_item active', $out);

		if (!empty($out)) {

			?>

			<ul<?php echo $menu_class; ?>>
				<?php echo $out; ?>
			</ul>

		<?php

		}

		echo ob_get_clean();

	}
}