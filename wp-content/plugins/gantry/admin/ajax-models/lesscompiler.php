<?php
/**
 * @version   $Id: lesscompiler.php 59361 2013-03-13 23:10:27Z btowles $
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2014 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 * Gantry uses the Joomla Framework (http://www.joomla.org), a GNU/GPLv2 content management system
 *
 */
defined('GANTRY_VERSION') or die();

/** @var $gantry Gantry */
global $gantry;

$action = $_POST['gantry_action'];
gantry_import('core.gantryjson');

switch ($action) {
	case 'clear':
		echo gantryAjaxClearLessCache();
		break;
	default:
		echo "error";
}

function gantryAjaxClearLessCache()
{
	gantry_import('core.utilities.gantrycache');
	/** @var $gantry Gantry */
	global $gantry;
	$cache_handler = GantryCache::getCache(Gantry::LESS_SITE_CACHE_GROUP, null, true);
	$cache_handler->clearGroupCache();
	return _ge('Less complier cache files cleared');
}
