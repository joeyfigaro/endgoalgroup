<?php
/**
 * @version   $Id: fontsizer.php 60832 2014-05-12 09:47:23Z jakub $
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2014 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

defined('GANTRY_VERSION') or die();

gantry_import('core.gantrywidget');

add_action('widgets_init', array("GantryWidgetFontSizer", "init"));

/**
 * @package     gantry
 * @subpackage  features
 */
class GantryWidgetFontSizer extends GantryWidget
{
	var $short_name = 'fontsizer';
	var $wp_name = 'gantry_fontsizer';
	var $long_name = 'Gantry Font Sizer';
	var $description = 'Gantry Font Sizer Widget';
	var $css_classname = 'widget_gantry_fontsizer';
	var $width = 200;
	var $height = 400;

	// Static function to register widget
	static function init()
	{
		register_widget("GantryWidgetFontSizer");
	}

	static function gantry_init()
	{
		/** @global $gantry Gantry */
        global $gantry;

		$fontsize         = $gantry->get('font-size');
		$current_fontsize = $gantry->get('font-size-is');
		$font_sizes       = array(
			0 => "xsmall",
			1 => "small",
			2 => "default",
			3 => "large",
			4 => "xlarge"
		);

		$current = array_search($current_fontsize, $font_sizes);
		if ($current !== false) {
			switch ($fontsize) {
				case 'smaller':
					if ($current > 0) $current--;
					break;
				case 'larger':
					if ($current < count($font_sizes) - 1) $current++;
					break;
			}
			$gantry->set('font-size-is', $font_sizes[$current]);
		}
	}

	function render($args, $instance)
	{
		/** @global $gantry Gantry */
global $gantry;

		ob_start();
		?>
		<div id="rt-accessibility">
			<div class="rt-desc"><?php _ge($instance['text']); ?></div>
			<div id="rt-buttons">
				<a href="<?php echo $gantry->addQueryStringParams($gantry->getCurrentUrl(array('reset-settings')), array('font-size' => 'smaller')); ?>" title="<?php echo _g('Decrease Font Size'); ?>" class="small"><span class="button"></span></a>
				<a href="<?php echo $gantry->addQueryStringParams($gantry->getCurrentUrl(array('reset-settings')), array('font-size' => 'larger')); ?>" title="<?php echo _g('Increase Font Size'); ?>" class="large"><span class="button"></span></a>
			</div>
		</div>
		<div class="clear"></div>
		<?php
		echo ob_get_clean();
	}
}