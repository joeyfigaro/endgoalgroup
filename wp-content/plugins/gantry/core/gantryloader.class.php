<?php
/**
 * @version   $Id: gantryloader.class.php 60342 2014-01-03 17:12:22Z jakub $
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2014 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */
defined('GANTRY_VERSION') or die();

class GantryLoader
{
	/**
	 * Loads a class from specified directories.
	 *
	 * @param string $name    The class name to look for ( dot notation ).
	 *
	 * @return void
	 */
	static function import($filePath)
	{
		static $paths;
		$base = gantry_clean_path(realpath(dirname(__FILE__) . '/..'));

		if (!isset($paths)) {
			$paths = array();
		}

		if (!isset($paths[$filePath])) {
			$parts            = explode('.', $filePath);
			$classname        = array_pop($parts);
			$path             = str_replace('.', DS, $filePath);
			$rs               = require($base . DS . $path . '.class.php');
			$paths[$filePath] = $rs;
		}
		return $paths[$filePath];
	}
}


