<?php
/**
 * @version   $Id: pagesuffix.php 60211 2013-11-14 00:02:42Z jakub $
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
class GantryGizmoPageSuffix extends GantryGizmo
{

	var $_name = 'pagesuffix';

	function query_parsed_init()
	{

		/** @global $gantry Gantry */
		global $gantry;

		$classes = explode(' ', $gantry->get('pagesuffix-class'));

		//add body class suffix
		foreach($classes as $class) {
			$gantry->addBodyClass($class);
		}

	}

}