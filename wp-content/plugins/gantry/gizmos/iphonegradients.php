<?php
/**
 * @version   $Id: iphonegradients.php 59361 2013-03-13 23:10:27Z btowles $
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
class GantryGizmoiPhoneGradients extends GantryGizmo
{
	var $_name = 'iphonegradients';

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

		$cookie = $gantry->retrieveTemp('platform', $cookiename);
		if ($gantry->browser->platform == 'iphone' && $cookie == '1') return true; else return false;
	}

	function query_parsed_init()
	{
		/** @global $gantry Gantry */
		global $gantry;

		$filtered = array_filter($gantry->_param_names, array($this, '_filtering'));
		$css      = "";
		foreach ($filtered as $filter) {
			$prefix   = str_replace('-from', '', $filter);
			$position = str_replace('iphone-', '', $filter);
			$position = str_replace('-gradient-from', '', $position);

			$type     = $gantry->get($prefix . '-gradient', 'linear');
			$dirStart = str_replace("-", " ", $gantry->get($prefix . '-direction_start'));
			$dirEnd   = str_replace("-", " ", $gantry->get($prefix . '-direction_end'));
			$from     = $gantry->get($prefix . '-from');
			$to       = $gantry->get($prefix . '-to');
			$opacity  = array(
				'from' => (float)$gantry->get($prefix . '-fromopacity'),
				'to'   => (float)$gantry->get($prefix . '-toopacity')
			);
			$css .= "#rt-" . $position . " .rt-container, #rt-" . $position . " .rt-container {background: -webkit-gradient(" . $type . ", " . $dirStart . ", " . $dirEnd . ", from(rgba(" . $this->_hex2rgb($from) . ", " . $opacity['from'] . ")), to(rgba(" . $this->_hex2rgb($to) . ", " . $opacity['to'] . "))) !important;}\n";
		}

		$gantry->addInlineStyle($css);

	}

	function _filtering($key)
	{
		return (stripos($key, '-gradient-from') !== false && stripos($key, 'iphone-') !== false && !stripos($key, 'opacity') === true);

	}

	function _hex2rgb($color)
	{
		$color = str_replace('#', '', $color);
		if (strlen($color) == 3) $color = str_repeat($color, 2);
		if (strlen($color) != 6) {
			return "0, 0, 0";
		}

		$rgb = array();
		for ($x = 0; $x < 3; $x++) {
			$rgb[$x] = hexdec(substr($color, (2 * $x), 2));
		}
		return implode(", ", $rgb);
	}
}