<?php
/**
 * @version   $Id: presets-saver.php 59361 2013-03-13 23:10:27Z btowles $
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2014 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

defined('GANTRY_VERSION') or die();

/** @global $gantry Gantry */
global $gantry;


$file = $gantry->custom_presets_file;
$action = $_POST['gantry_action'];

if (!current_user_can('edit_theme_options')) die('-1');

if ($action == 'add') {
	$jsonstring = stripslashes($_POST['presets-data']);

	$data = json_decode($jsonstring, false);

	if (!file_exists($file)) {
		$handle = @fopen($file, 'w');
		@fwrite($handle, "");
	}

	gantry_import('core.gantryini');
	$newEntry = GantryINI::write($file, $data);
	gantry_admin_clear_cache();

	if ($newEntry) echo "success";
} else if ($action == 'delete') {
	$presetTitle = $_POST['preset-title'];
	$presetKey   = $_POST['preset-key'];
	if (!$presetKey || !$presetTitle) return "error";
	GantryINI::write($file, array($presetTitle => array($presetKey => array())), 'delete-key');
	gantry_admin_clear_cache();

} else {
	return "error";
}

?>
