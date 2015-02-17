<?php
/**
 * @version   $Id: jstools.php 59361 2013-03-13 23:10:27Z btowles $
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
class GantryGizmoJSTools extends GantryGizmo
{
	var $_name = 'jstools';

	function isEnabled()
	{
		return true;
	}

	function query_parsed_init()
	{
		/** @global $gantry Gantry */
		global $gantry;

		$gantry->addScript('mootools.js');

		// build spans
		if ($gantry->get('buildspans-enabled')) {
			$modules = "['rt-block']";
			$headers = "['h3','h2','h1']";
			$gantry->addScript('gantry-buildspans.js');
			$gantry->addDomReadyScript($this->_buildSpans($modules, $headers));
		}
		// inputs
		if ($gantry->get('inputstyling-enabled') && !($gantry->browser->name == 'ie' && $gantry->browser->shortversion == '6')) {
			$exclusions = $gantry->get('inputstyling-exclusions');
			$gantry->addScript('gantry-inputs.js');
			$gantry->addInlineScript("InputsExclusion.push($exclusions)");
		}
	}

	function _buildSpans($modules, $headers)
	{
		/** @global $gantry Gantry */
		global $gantry;

		$js = "
				var modules = $modules;
				var header = $headers;
				GantryBuildSpans(modules, header);
		\n";

		return $js;
	}
}