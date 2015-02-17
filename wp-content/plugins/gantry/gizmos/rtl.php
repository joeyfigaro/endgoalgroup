<?php
/**
 * @version   $Id: rtl.php 59361 2013-03-13 23:10:27Z btowles $
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
class GantryGizmoRTL extends GantryGizmo
{

	var $_name = 'rtl';

	function query_parsed_init()
	{
		/** @global $gantry Gantry */
		global $gantry;

		if (get_bloginfo('text_direction') == 'rtl' && $gantry->get('rtl-enabled')) {
			$gantry->addBodyClass("rtl");
			$gantry->addStyle("rtl.css");
		}
	}
}