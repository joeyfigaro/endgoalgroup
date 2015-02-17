<?php
/**
 * @version   $Id: positionslist.php 59361 2013-03-13 23:10:27Z btowles $
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2014 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

defined('GANTRY_VERSION') or die;

gantry_import('core.config.gantryformfield');

require_once(gantry_dirname(__FILE__) . '/selectbox.php');

/**
 *
 */
class GantryFormFieldPositionsList extends GantryFormFieldSelectBox
{
	/**
	 * The form field type.
	 *
	 * @var        string
	 * @since    1.6
	 */
	public $type = 'positionslist';
	protected $basetype = 'select';

	protected function getOptions()
	{
		/** @global $gantry Gantry */
		global $gantry;
		$options = array();
		$options = parent::getOptions();

		$hide_mobile = ('true' == (string)$this->element['hide_mobile']) ? true : false;

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
			//  if (isset($positionInfo->max_positions))
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
