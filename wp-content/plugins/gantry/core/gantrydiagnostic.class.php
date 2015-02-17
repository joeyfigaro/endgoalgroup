<?php
/**
 * @version   $Id: gantrydiagnostic.class.php 59361 2013-03-13 23:10:27Z btowles $
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2014 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */
defined('GANTRY_VERSION') or die();

/**
 * @package    gantry
 * @subpackage core
 */
class GantryDiagnostic
{

	var $errors = array();
	var $customFolder = false;

	public function runChecks()
	{

		$this->checkWritableDirs();
		$this->variablesCheck();
		return $this->errors;
	}

	protected function checkWritableDirs()
	{
		/** @global $gantry Gantry */
		global $gantry;
		$writable_dirs = array(
			$gantry->templatePath . '/cache',
			$gantry->templatePath . '/css',
			$gantry->gantryPath . '/css',
			$gantry->gantryPath . '/admin/widgets',
		);

		foreach ($writable_dirs as $dir) {
			$output = "";
			if (!is_writable($dir)) {
				$output .= "<div class='detail'>";
				$output .= "Folder <span>" . $dir . "</span> is not writeable.";
				$output .= "</div>";
				$this->errors[] = $output;
			}
		}

		return $output;
	}

	function variablesCheck()
	{
		/** @global $gantry Gantry */
		global $gantry;
		$checks = array();

		$list = array(
			'grid'                  => $gantry->grid,
			'layoutSchemas'         => $gantry->layoutSchemas,
			'mainbodySchemas'       => $gantry->mainbodySchemas,
			'pushPullSchemas'       => $gantry->pushPullSchemas,
			'mainbodySchemasCombos' => $gantry->mainbodySchemasCombos
		);

		foreach ($list as $key => $entry) {
			if (!isset($entry)) $checks[] = "Variable <span>" . $key . "</span> is not set.";
		}

		$output = "";
		foreach ($checks as $check) {
			$output .= "<div class='detail'>";
			$output .= $check;
			$output .= "</div>";
		}

		if (!defined('GANTRY_VERSION')) {
			$output .= "<div class='detail'>";
			$output .= "Constant <span>GANTRY_VERSION</span> is not defined.";
			$output .= "</div>";
		}

		return $output;

	}

}