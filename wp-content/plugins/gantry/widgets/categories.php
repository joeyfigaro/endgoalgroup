<?php
/**
 * @version   $Id: categories.php 60832 2014-05-12 09:47:23Z jakub $
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2014 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 * Widget based on the WordPress core Categories Widget.
 */

defined('GANTRY_VERSION') or die();

gantry_import('core.gantrywidget');

add_action('widgets_init', array("GantryWidgetCategories", "init"));

class GantryWidgetCategories extends GantryWidget
{
	var $short_name = 'categories';
	var $wp_name = 'gantry_categories';
	var $long_name = 'Gantry Categories';
	var $description = 'Gantry Categories Widget';
	var $css_classname = 'widget_gantry_categories';
	var $width = 200;
	var $height = 400;

	static function init()
	{
		register_widget("GantryWidgetCategories");
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

		$menu_class = $instance['menu_class'];

		if ($menu_class != '') :
			$menu_class = ' class="' . $menu_class . '"'; else :
			$menu_class = '';
		endif;

		$cat_args = array('orderby'      => $instance['orderby'],
		                  'show_count'   => $instance['show_count'],
		                  'hierarchical' => $instance['hierarchical'],
		                  'class'        => $menu_class,
		                  'hide_empty'   => $instance['hide_empty'],
		                  'exclude'      => $instance['exclude'],
		                  'depth'        => $instance['depth']
		);

		if ($instance['dropdown']) {

			$cat_args['show_option_none'] = __('Select Category');
			wp_dropdown_categories(apply_filters('widget_categories_dropdown_args', $cat_args));

			?>

			<script type='text/javascript'>
				/* <![CDATA[ */
				var dropdown = document.getElementById("cat");
				function onCatChange() {
					if (dropdown.options[dropdown.selectedIndex].value > 0) {
						location.href = "<?php echo home_url(); ?>/?cat=" + dropdown.options[dropdown.selectedIndex].value;
					}
				}
				dropdown.onchange = onCatChange;
				/* ]]> */
			</script>

		<?php

		} else {

			?>

			<ul<?php echo $menu_class; ?>>

				<?php

				$cat_args['title_li'] = '';
				$cat_args['class'] = '';
				$cat_args['show_count'] = '';
				$cat_args['echo'] = '0';

				$out = wp_list_categories(apply_filters('widget_categories_args', $cat_args));
				$out = str_replace('current-cat', 'current-cat active', $out);
				$out = preg_replace('@\<a([^>]*)>(.*?)\<\/a\>@', '<a$1><span>$2</span></a>', $out);
				echo $out;

				?>

			</ul>

		<?php

		}

		echo ob_get_clean();

	}
}