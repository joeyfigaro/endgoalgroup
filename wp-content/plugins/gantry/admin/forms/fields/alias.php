<?php
/**
 * @version   $Id: alias.php 59361 2013-03-13 23:10:27Z btowles $
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2014 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

defined('GANTRY_VERSION') or die();
/**
 * @package     gantry
 * @subpackage  admin.elements
 */
gantry_import('core.config.gantryformfield');

require_once(gantry_dirname(__FILE__) . '/selectbox.php');

class GantryFormFieldAlias extends GantryFormFieldSelectBox
{

	protected $type = 'alias';
	protected $basetype = 'select';

	public function getInput()
	{
		/** @global $gantry Gantry */
		global $gantry;

		$name = substr(str_replace($gantry->templateName . '-template-options', "", $this->name), 1, -1);
		$name = str_replace("][", "-", $name);

		$intro = "<div class='alias-label'>" . $name . " &rarr; </div>";
		/*include_once('position.php');
		$selectbox = new JElementPosition;
		return $intro.$selectbox->fetchElement($name, $value, $node, $control_name);*/

		//return $intro."&lt;positions dropdown&gt;";
		return $intro . parent::getInput();
	}

	public function getLabel()
	{
		return "";
	}

	protected function getOptions()
	{
		/** @global $gantry Gantry */
		global $gantry;
		$options = array();
		$options = parent::getOptions();

		$hide_mobile = false;

		$positions = $gantry->getUniquePositions();

		foreach ($positions as $position) {
			$positionInfo = $gantry->getPositionInfo($position);
			if ($hide_mobile && $positionInfo->mobile) {
				continue;
			}
			if (1 == (int)$positionInfo->max_positions) {
				$split_postions[] = $positionInfo->id;
				continue;
			}
			for ($i = 1; $i <= (int)$positionInfo->max_positions; $i++) {
				$split_postions[] = $positionInfo->id . '-' . chr(96 + $i);
			}
		}

		foreach ($split_postions as $position) {
			// Create a new option object based on the <option /> element.
			$tmp       = GantryHtmlSelect::option($position, $position, 'value', 'text', false);
			$options[] = $tmp;
		}

		return $options;
	}
}
