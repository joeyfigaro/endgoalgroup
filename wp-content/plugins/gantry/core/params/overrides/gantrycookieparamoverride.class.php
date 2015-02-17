<?php
/**
 * @version   $Id: gantrycookieparamoverride.class.php 59361 2013-03-13 23:10:27Z btowles $
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2014 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */
defined('GANTRY_VERSION') or die();

gantry_import('core.params.gantryparamoverride');

/**
 * @package    gantry
 * @subpackage core.params
 */
class GantryCookieParamOverride extends GantryParamOverride
{

	public static function store()
	{
		/** @global $gantry Gantry */
		global $gantry;
		if (array_key_exists('cookie_time', $gantry->_working_params)) {
			$cookie_time = time() + (int)$gantry->_working_params['cookie_time']['value'];
		}
		foreach ($gantry->_setincookie as $cookie_var) {
			if ($gantry->_working_params[$cookie_var]['setby'] != 'override') {
				if (!headers_sent() && $gantry->_working_params[$cookie_var]['value'] != $gantry->_working_params[$cookie_var]['sitebase'] && $gantry->_working_params[$cookie_var]['type'] != 'preset') {
					@setcookie($gantry->template_prefix . $gantry->_base_params_checksum . "-" . $cookie_var, $gantry->_working_params[$cookie_var]['value'], $cookie_time, $gantry->getCookiePath(), false);
				} else {
					if (!headers_sent() && array_key_exists($gantry->template_prefix . $gantry->_base_params_checksum . "-" . $cookie_var, $_COOKIE)) {
						@setcookie($gantry->template_prefix . $gantry->_base_params_checksum . "-" . $cookie_var, "", $cookie_time, $gantry->getCookiePath(), false);
					}
				}
			}
		}
		self::_flushOldCookies();
	}

	public static function clean()
	{
		/** @global $gantry Gantry */
		global $gantry;
		if (array_key_exists('cookie_time', $gantry->_working_params)) {
			$cookie_time = time() + (int)$gantry->_working_params['cookie_time']['value'];
		}
		foreach ($gantry->_setincookie as $cookie_var) {
			if (!headers_sent() && array_key_exists($gantry->template_prefix . $gantry->_base_params_checksum . "-" . $cookie_var, $_COOKIE)) {
				@setcookie($gantry->template_prefix . $gantry->_base_params_checksum . "-" . $cookie_var, "", $cookie_time, $gantry->getCookiePath(), false);
			}
		}
		self::_flushOldCookies();
	}

	protected static function _flushOldCookies()
	{
		/** @global $gantry Gantry */
		global $gantry;
		if (array_key_exists('cookie_time', $gantry->_working_params)) {
			$cookie_time = time() + (int)$gantry->_working_params['cookie_time']['value'];
		}
		foreach ($_COOKIE as $cookie_key => $cookie_value) {
			if (!headers_sent() && strpos($cookie_key, $gantry->template_prefix) === 0 && strpos($cookie_key, $gantry->template_prefix . $gantry->_base_params_checksum) === false) {
				@setcookie($cookie_key, "", $cookie_time, $gantry->getCookiePath(), false);
			}
		}
	}

	public static function populate()
	{
		/** @global $gantry Gantry */
		global $gantry;

		// get any cookie param overrides and set to that
		// set preset values
		foreach ($gantry->_preset_names as $param_name) {
			$cookie_param_name = $gantry->template_prefix . $gantry->_base_params_checksum . "-" . $param_name;
			if (in_array($param_name, $gantry->_setbycookie) && array_key_exists($cookie_param_name, $_COOKIE)) {
				$param                =& $gantry->_working_params[$param_name];
				$cookie_value         = htmlentities($_COOKIE[$gantry->template_prefix . $gantry->_base_params_checksum . "-" . $param['name']]);
				$cookie_preset_params = $gantry->getPresetParams($param['name'], $cookie_value);
				foreach ($cookie_preset_params as $cookie_preset_param_name => $cookie_preset_param_value) {
					if (!empty($cookie_preset_param_value)) {
						$gantry->_working_params[$cookie_preset_param_name]['value'] = $cookie_preset_param_value;
						$gantry->_working_params[$cookie_preset_param_name]['setby'] = 'cookie';
					}
				}
			}
		}
		// set individual values
		foreach ($gantry->_param_names as $param_name) {
			$cookie_param_name = $gantry->template_prefix . $gantry->_base_params_checksum . "-" . $param_name;
			if (in_array($param_name, $gantry->_setbycookie) && array_key_exists($cookie_param_name, $_COOKIE)) {
				$param        =& $gantry->_working_params[$param_name];
				$cookie_value = htmlentities($_COOKIE[$gantry->template_prefix . $gantry->_base_params_checksum . "-" . $param['name']]);
				if (!is_null($cookie_value)) {
					$gantry->_working_params[$param['name']]['value'] = $cookie_value;
					$gantry->_working_params[$param['name']]['setby'] = 'cookie';
				}
			}
		}
	}
}