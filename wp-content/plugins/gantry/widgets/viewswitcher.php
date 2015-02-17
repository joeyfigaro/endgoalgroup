<?php
/**
 * @version   $Id: viewswitcher.php 60832 2014-05-12 09:47:23Z jakub $
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2014 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

defined('GANTRY_VERSION') or die();


gantry_import('core.gantrywidget');

/**
 * @package     gantry
 * @subpackage  features
 */
add_action('widgets_init', array("GantryWidgetViewSwitcher", "init"));


class GantryWidgetViewSwitcher extends GantryWidget
{
	var $short_name = 'viewswitcher';
	var $wp_name = 'gantry_viewswitcher';
	var $long_name = 'Gantry iPhone View Switcher';
	var $description = 'Gantry iPhone View Switcher';
	var $css_classname = 'widget_gantry_viewswitcher';
	var $width = 200;
	var $height = 400;

	function render_widget_open($args, $instance)
	{
	}

	function render_widget_close($args, $instance)
	{
	}

	static function init()
	{
		register_widget("GantryWidgetViewSwitcher");
	}

	static function gantry_init()
	{
		/** @global $gantry Gantry */
        global $gantry;
		$platform = $gantry->browser->platform;

		$prefix     = $gantry->get('template_prefix');
		$cookiename = $prefix . $platform . '-switcher';

		$cookie = (isset($_COOKIE[$cookiename])) ? $_COOKIE[$cookiename] : false;

		if (!strlen($cookie) || $cookie === false) {
			setcookie($cookiename, "1", time() + 60 * 60 * 24 * 365);
			$cookie = "1";
		}

		$gantry->addTemp('platform', $cookiename, $cookie);

		$gantry->addDomReadyScript(GantryWidgetViewSwitcher::_js($cookie, $cookiename));
	}

	function render($args, $instance)
	{
		/** @global $gantry Gantry */
        global $gantry;
		$platform = $gantry->browser->platform;
		if ($gantry->get($platform . '-enabled')) {

			$prefix     = $gantry->get('template_prefix');
			$cookiename = $prefix . $gantry->browser->platform . '-switcher';
			$cls        = (!$gantry->retrieveTemp('platform', $cookiename)) ? 'off' : 'on';

			ob_start();
			?>
			<div class="clear"></div>
			<a href="#" id="gantry-viewswitcher" class="<?php echo $cls; ?>"><span><?php _ge('Switcher'); ?></span></a>
			<?php
			echo ob_get_clean();
		}
	}

	static function _js($cookie, $cookiename)
	{
		/** @global $gantry Gantry */
        global $gantry;
		if ($cookie === false || $cookie == '1' || $gantry->retrieveTemp('platform', $cookiename) == "1") $cookie = 0; else $cookie = 1;

		return "
				var switcher = document.id('gantry-viewswitcher');
				if (switcher) {
					switcher.addEvent('click', function(e) {
						e.stop();
						if ('" . $cookie . "' == '0') document.id('gantry-viewswitcher').addClass('off');
						else $('gantry-viewswitcher').removeClass('off');
						Cookie.write('" . $cookiename . "', '" . $cookie . "');
						window.location.reload();
					});
				}
		";
	}
}