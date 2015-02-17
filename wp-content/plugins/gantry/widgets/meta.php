<?php
/**
 * @version   $Id: meta.php 60832 2014-05-12 09:47:23Z jakub $
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2014 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 * Widget based on the WordPress core Meta Widget.
 */

defined('GANTRY_VERSION') or die();

gantry_import('core.gantrywidget');

add_action('widgets_init', array("GantryWidgetMeta", "init"));

class GantryWidgetMeta extends GantryWidget
{
	var $short_name = 'meta';
	var $wp_name = 'gantry_meta';
	var $long_name = 'Gantry Meta';
	var $description = 'Gantry Meta Widget';
	var $css_classname = 'widget_gantry_meta';
	var $width = 200;
	var $height = 400;

	static function init()
	{
		register_widget("GantryWidgetMeta");
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

		?>

		<ul<?php echo $menu_class; ?>>
			<?php
			$out = wp_register('<li>', '</li>', '0');
			$out = preg_replace('@\<a([^>]*)>(.*?)\<\/a\>@', '<a$1><span>$2</span></a>', $out);
			echo $out;
			?>
			<?php if (is_user_logged_in()) : ?>
				<li>
					<a href="<?php echo wp_logout_url(); ?>" title="<?php _ge('Logout'); ?>"><span><?php _ge('Logout'); ?></span></a>
				</li>
			<?php else : ?>
				<li>
					<a href="<?php echo wp_login_url(); ?>" title="<?php _ge('Login'); ?>"><span><?php _ge('Login'); ?></span></a>
				</li>
			<?php endif; ?>
			<li>
				<a href="<?php bloginfo('rss2_url'); ?>" title="<?php echo esc_attr(_g('Syndicate this site using RSS 2.0')); ?>"><span><?php _ge('Entries <abbr title="Really Simple Syndication">RSS</abbr>'); ?></span></a>
			</li>
			<li>
				<a href="<?php bloginfo('comments_rss2_url'); ?>" title="<?php echo esc_attr(_g('The latest comments to all posts in RSS')); ?>"><span><?php _ge('Comments <abbr title="Really Simple Syndication">RSS</abbr>'); ?></span></a>
			</li>
			<li>
				<a href="http://wordpress.org/" title="<?php echo esc_attr(_g('Powered by WordPress, state-of-the-art semantic personal publishing platform.')); ?>"><span>WordPress.org</span></a>
			</li>
			<?php wp_meta(); ?>
		</ul>

		<?php

		echo ob_get_clean();

	}
}