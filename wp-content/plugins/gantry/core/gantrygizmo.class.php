<?php
/**
 * @version   $Id: gantrygizmo.class.php 59361 2013-03-13 23:10:27Z btowles $
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2014 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */
defined('GANTRY_VERSION') or die();

/**
 * Base class for all Gantry custom features.
 *
 * @package    gantry
 * @subpackage core
 */
class GantryGizmo
{
	var $_name = '';

	var $_prefix = '';

	function isEnabled()
	{
		if ((int)$this->get('enabled') == 1) return true;
		return false;
	}

	function isOrderable()
	{
		return true;
	}

	function setPrefix($prefix)
	{
		$this->_prefix = $prefix;
	}

	function get($param, $prefixed = true)
	{
		/** @global $gantry Gantry */
		global $gantry;

		$gantry_param = '';
		$gantry_param .= ($prefixed && !empty($this->_prefix)) ? $this->_prefix . '-' : '';
		$gantry_param .= $this->_name . '-' . $param;
		$value = $gantry->get($gantry_param);
		return $value;
	}

	function init()
	{

	}

	function query_parsed_init()
	{

	}

	function admin_init()
	{

	}

	function finalize()
	{

	}
}