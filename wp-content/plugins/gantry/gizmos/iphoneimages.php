<?php
/**
 * @version   $Id: iphoneimages.php 59361 2013-03-13 23:10:27Z btowles $
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2014 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */


defined('GANTRY_VERSION') or die();

gantry_import('core.gantrygizmo');
/**
 * @package     gantry
 * @subpackage  features
 */
class GantryGizmoiPhoneImages extends GantryGizmo
{
	var $_name = 'iphoneimages';

	function isEnabled()
	{
		/** @global $gantry Gantry */
		global $gantry;

		if (!$gantry->browser) return false;

		$prefix     = $gantry->get('template_prefix');
		$cookiename = $prefix . $gantry->browser->platform . '-switcher';
		$cookie     = (isset($_COOKIE[$cookiename])) ? $_COOKIE[$cookiename] : false;

		if (!strlen($cookie) || $cookie === false) {
			setcookie($cookiename, "1", time() + 60 * 60 * 24 * 365);
			$cookie = "1";
			$gantry->addTemp('platform', $cookiename, $cookie);
		}

		if ($gantry->browser->platform == 'iphone' && $cookie == '1' && $this->get('enabled')) return true; else return false;
	}

	function query_parsed_init()
	{
		/** @global $gantry Gantry */
		global $gantry;

		$gantry->addInlineScript($this->_js());

	}

	function _js()
	{
		/** @global $gantry Gantry */
		global $gantry;

		$percentage = $this->get('percentage');
		$minWidth   = $this->get('minWidth');

		return "
			window.addEvent('load', function() {
				var winsize = window.getSize();
				var imgs = $$('img').each(function(img) {
					var size = {}, backup = {};

					size = {
						width: img.get('width') || img.getStyle('width').toInt() || img.offsetWidth,
						height: img.get('height') || img.getStyle('height').toInt() || img.offsetHeight
					};
					backup = size;
					size = {
						width: size.width - (size.width * " . $percentage . " / 100),
						height: size.height - (size.height * " . $percentage . " / 100)
					};

					if (size.width > winsize.x) {
						var width = backup.width - (backup.width - winsize.x);
						var height = width * backup.height / backup.width;
						size = {
							width: width - 30,
							height: height - 30
						}
					}
					if (backup.width > " . $minWidth . " && backup.width != 0) {
						img.set('width', size.width).set('height', size.height).setStyles(size);
					}
				});
			});
		";
	}
}