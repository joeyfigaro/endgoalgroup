<?php
/**
 * @version   $Id: archives.php 60832 2014-05-12 09:47:23Z jakub $
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2014 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 * Widget based on the WordPress core Archives Widget.
 */

defined('GANTRY_VERSION') or die();

gantry_import('core.gantrywidget');

add_action('widgets_init', array("GantryWidgetArchives", "init"));

class GantryWidgetArchives extends GantryWidget
{
	var $short_name = 'archives';
	var $wp_name = 'gantry_archives';
	var $long_name = 'Gantry Archives';
	var $description = 'Gantry Archives Widget';
	var $css_classname = 'widget_gantry_archives';
	var $width = 200;
	var $height = 400;

	static function init()
	{
		register_widget("GantryWidgetArchives");
	}

	function render_title($args, $instance) {
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

		$menu_class = $instance['menu_class'];
		$limit      = $instance['limit'];

		if ($menu_class != '') :
			$menu_class = ' class="' . $menu_class . '"'; else :
			$menu_class = '';
		endif;

		if ($limit < 1) : $limit = ''; endif;

		if ($instance['dropdown']) {
			?>
			<select name="archive-dropdown" onchange='document.location.href=this.options[this.selectedIndex].value;'>
				<option value=""><?php echo esc_attr(__('Select Date')); ?></option> <?php wp_get_archives(apply_filters('widget_archives_dropdown_args', array('type'           => $instance['type'],
				                                                                                                                                               'format'          => 'option',
				                                                                                                                                               'show_post_count' => $instance['show_count'],
				                                                                                                                                               'limit'           => $limit
				                                                                                                                                          ))); ?>
			</select>

		<?php } else { ?>

			<ul<?php echo $menu_class; ?>>

				<?php

				$output = wp_get_archives(apply_filters('widget_archives_args', array('type'           => $instance['type'],
				                                                                     'show_post_count' => 0,
				                                                                     'limit'           => $limit,
				                                                                     'echo'            => '0'
				                                                                )));
				$output = preg_replace('@\<a([^>]*)>(.*?)\<\/a\>@', '<a$1><span>$2</span></a>', $output);
				echo $output;

				?>

			</ul>

		<?php
		}

		echo ob_get_clean();

	}
}