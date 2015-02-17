<?php
/**
 * @version   $Id: cache.php 59361 2013-03-13 23:10:27Z btowles $
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2014 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

defined('GANTRY_VERSION') or die();

/** @global $gantry Gantry */
global $gantry;

$action = $_POST['gantry_action'];
if (!current_user_can('edit_theme_options')) die('-1');

if ($action == 'clear') {
	gantry_import('core.utilities.gantrycache');
	$adminCache = GantryCache::getCache(GantryCache::ADMIN_GROUP_NAME);
	$adminCache->clearGroupCache();
	$frontEndCache = GantryCache::getCache(GantryCache::GROUP_NAME);
	$frontEndCache->clearGroupCache();
	$frontEndLessCache = GantryCache::getCache(Gantry::LESS_SITE_CACHE_GROUP, null, true);
	$frontEndLessCache->clearGroupCache();
	echo "Cache successfully cleared.";
} else {
	echo "Error occurred while trying to clear the cache.";
}
