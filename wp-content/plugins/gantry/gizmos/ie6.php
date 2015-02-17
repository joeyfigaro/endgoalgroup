<?php
/**
 * @version   $Id: ie6.php 59361 2013-03-13 23:10:27Z btowles $
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
class GantryGizmoIE6 extends GantryGizmo
{
	var $_name = 'ie6';

	function isEnabled()
	{
		return true;
	}

	function query_parsed_init()
	{
		/** @global $gantry Gantry */
		global $gantry;

		if ($gantry->browser->name == 'ie' && $gantry->browser->shortversion == '6') {
			$gantry->set('rtl-enabled', false); //disable problematic RTL for ie6
			$gantry->addBodyClass('menu-type-suckerfishmenu');
		}
	}
}