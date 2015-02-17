<?php
/**
 * @version   $Id: gantrysessionparamoverride.class.php 59361 2013-03-13 23:10:27Z btowles $
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
class GantrySessionParamOverride extends GantryParamOverride
{
	public static function store()
	{
		if (defined('GANTRY_SESSIONS_ENABLED')) {
			/** @global $gantry Gantry */
			global $gantry;
			foreach ($gantry->_setinsession as $session_var) {
				if ($gantry->_working_params[$session_var]['setby'] != 'override') {
					if ($gantry->_working_params[$session_var]['value'] != $gantry->_working_params[$session_var]['sitebase'] && $gantry->_working_params[$session_var]['type'] != 'preset') {
						$_SESSION[$gantry->template_prefix . $gantry->_base_params_checksum . "-" . $session_var] = $gantry->_working_params[$session_var]['value'];
					} else {
						unset($_SESSION[$gantry->template_prefix . $gantry->_base_params_checksum . "-" . $session_var]);
					}
				}
			}
		}
	}

	public static function clean()
	{
		if (defined('GANTRY_SESSIONS_ENABLED')) {
			/** @global $gantry Gantry */
			global $gantry;
			foreach ($gantry->_setinsession as $session_var) {
				unset($_SESSION[$gantry->template_prefix . $gantry->_base_params_checksum . "-" . $session_var]);
			}
		}
	}

	public static function populate()
	{
		if (defined('GANTRY_SESSIONS_ENABLED')) {
			/** @global $gantry Gantry */
			global $gantry;

			// get any session param overrides and set to that
			// set preset values
			foreach ($gantry->_preset_names as $param_name) {
				$session_param_name = $gantry->template_prefix . $gantry->_base_params_checksum . "-" . $param_name;
				if (in_array($param_name, $gantry->_setbysession) && array_key_exists($session_param_name, $_SESSION)) {
					$param                 =& $gantry->_working_params[$param_name];
					$session_value         = $_SESSION[$session_param_name];
					$session_preset_params = $gantry->presets[$param_name][$session_value];
					foreach ($session_preset_params as $session_preset_param_name => $session_preset_param_value) {
						if (array_key_exists($session_preset_param_name, $gantry->_working_params) && !is_null($session_preset_param_value)) {
							$gantry->_working_params[$session_preset_param_name]['value'] = $session_preset_param_value;
							$gantry->_working_params[$session_preset_param_name]['setby'] = 'session';
						}
					}
				}
			}
			// set individual values
			foreach ($gantry->_param_names as $param_name) {
				$session_param_name = $gantry->template_prefix . $gantry->_base_params_checksum . "-" . $param_name;
				if (in_array($param_name, $gantry->_setbysession) && array_key_exists($session_param_name, $_SESSION)) {
					$param         =& $gantry->_working_params[$param_name];
					$session_value = $_SESSION[$session_param_name];
					if (!is_null($session_value)) {
						$gantry->_working_params[$param['name']]['value'] = $session_value;
						$gantry->_working_params[$param['name']]['setby'] = 'session';
					}
				}
			}
		}
	}
}