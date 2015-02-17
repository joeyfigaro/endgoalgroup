<?php
/**
 * @version   $Id: position.php 2381 2012-08-15 04:14:26Z btowles $
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2014 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */
defined('GANTRY_VERSION') or die;

gantry_import('core.config.gantryformfield');

require_once(gantry_dirname(__FILE__) . '/selectbox.php');

class GantryFormFieldPosition extends GantryFormFieldSelectBox
{
	/**
	 * The form field type.
	 *
	 * @var        string
	 */
	public $type = 'position';
	protected $basetype = 'select';

	/**
	 * Method to get the field options.
	 *
	 * @return    array    The field option objects.
	 */
	protected function getOptions()
	{

		// Merge any additional options in the XML definition.
		/** @var $gantry Gantry */
		global $gantry;
		$options = parent::getOptions();

		$unique = $this->getBool('unique', false);

		if ($unique) $positions = $gantry->getUniquePositions(); else $positions = $gantry->getPositions();

		$hide_mobile = $this->getBool('hide_mobile', false);

		$options = array();
		foreach ($positions as $position) {
			$positionInfo = $gantry->getPositionInfo($position);
			if ($hide_mobile && $positionInfo->mobile) {
				continue;
			}

			$val       = $position;
			$text      = $position;
			$tmp       = GantryHtmlSelect::option($val, $text, 'value', 'text', false);
			$options[] = $tmp;
		}
		return $options;
	}
}