<?php
/**
 * @version   $Id: showmax.php 59361 2013-03-13 23:10:27Z btowles $
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2014 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

defined('GANTRY_VERSION') or die;

gantry_import('core.config.gantryformfield');

require_once(gantry_dirname(__FILE__) . '/selectbox.php');

class GantryFormFieldShowMax extends GantryFormFieldSelectBox
{
	/**
	 * The form field type.
	 *
	 * @var        string
	 * @since    1.6
	 */
	public $type = 'showmax';
	protected $basetype = 'select';

	public $position_info = null;

	protected function getOptions()
	{
		/** @global $gantry Gantry */
		global $gantry;

		$options = array();
		$options = parent::getOptions();

		if ($this->position_info != null) {
			if ($this->position_info->max_positions < (int)$this->value) {
				$gantry->set($this->id, $this->position_info->max_positions);
				$this->value = $this->position_info->max_positions;
			}
			for ($i = 1; $i <= $this->position_info->max_positions; $i++) {
				// Create a new option object based on the <option /> element.
				$tmp       = GantryHtmlSelect::option($i, $i, 'value', 'text', false);
				$options[] = $tmp;
			}
		}
		return $options;
	}
}
