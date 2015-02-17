<?php
/**
 * @version   $Id: templatepage.class.php 59361 2013-03-13 23:10:27Z btowles $
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2014 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

gantry_import('core.rules.gantryoverridefact');

add_filter('gantry_check_page_type_function', 'GantryFactTemplatePage::getPageCheckConditionalFunction');

class GantryFactTemplatePage extends GantryOverrideFact
{
	function matchesCallPageType($query)
	{
		$ret   = false;
		$check = apply_filters('gantry_check_page_type_function', $this->type);
		switch ($this->type) {
			case 'wp-signup':
				if (basename($_SERVER['SCRIPT_NAME']) == 'wp-signup.php' && $query->is_home == true) {
					$ret = true;
				}
				break;
			case 'home':
				if ($query->is_home == true && basename($_SERVER['SCRIPT_NAME']) != 'wp-signup.php') {
					$ret = true;
				}
				break;
			default:
				if (isset($query->$check)) {
					$ret = $query->$check;
				}
		}
		return $ret;
	}

	public static function getPageCheckConditionalFunction($type)
	{
		return "is_" . $type;
	}
}