<?php
/**
 * @version   $Id: gantryparamprocessor.class.php 58623 2012-12-15 22:01:32Z btowles $
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2014 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 * Gantry uses the Joomla Framework (http://www.joomla.org), a GNU/GPLv2 content management system
 *
 */
defined('GANTRY_VERSION') or die();
/**
 * Base class for Gantry Parameter processor classes
 * @package    gantry
 * @subpackage core
 */
abstract class GantryParamProcessor
{

	public function preLoad(&$gantry, $param_name, &$param_element, &$data)
	{

	}

	public function postLoad(&$gantry, $param_name, &$param_element, &$data)
	{

	}
}