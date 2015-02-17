<?php
/**
 * @version   $Id: logo.php 60832 2014-05-12 09:47:23Z jakub $
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2014 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

defined('GANTRY_VERSION') or die();

gantry_import('core.gantrywidget');

//add_action('widgets_init', array("GantryWidgetLogo","init"));

class GantryWidgetLogo extends GantryWidget
{
	var $short_name = 'logo';
	var $wp_name = 'gantry_logo';
	var $long_name = 'Gantry Logo';
	var $description = 'Gantry Logo Widget';
	var $css_classname = 'widget_gantry_logo';
	var $width = 200;
	var $height = 400;

	static function init()
	{
		register_widget("GantryWidgetLogo");
	}

	function render($args, $instance)
	{
		/** @global $gantry Gantry */
        global $gantry;
		extract($args);

		// default location for custom icon is {template}/images/logo/logo.png, with 'perstyle' it's
		// located in {template}/images/logo/styleX/logo.png
		if (isset($instance['autosize']) && $instance['autosize']) {

			$path    = $gantry->templatePath . '/images/' . 'logo';
			$logocss = $instance['css'];

			// get proper path based on perstyle param
			$path = (intval($instance['perstyle']) === 1) ? $path . DS . $gantry->get("cssstyle") . DS : $path . DS;
			// append logo file
			$path .= 'logo.png';

			// if the logo exists, get it's dimentions and add them inline
			if (file_exists($path)) {
				$logosize = getimagesize($path);
				if (isset($logosize[0]) && isset($logosize[1])) {
					$gantry->addInlineStyle($logocss . ' {width:' . $logosize[0] . 'px;height:' . $logosize[1] . 'px;}');
				}
			}
		}
		ob_start();

		?>
		<a href="<?php echo home_url(); ?>" id="rt-logo"></a>
		<?php
		echo ob_get_clean();
	}
}
