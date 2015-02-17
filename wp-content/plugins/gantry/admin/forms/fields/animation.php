<?php
/**
 * @version   $Id: animation.php 59361 2013-03-13 23:10:27Z btowles $
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2014 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */
defined('GANTRY_VERSION') or die;

gantry_import('core.config.gantryformfield');
gantry_import('core.config.gantryhtmlselect');
require_once(gantry_dirname(__FILE__) . '/selectbox.php');


/**
 * Renders an animation element
 *
 * @package    gantry
 * @subpackage admin.elements
 */
class GantryFormFieldAnimation extends GantryFormFieldSelectBox
{

	protected $type = 'animation';
	protected $basetype = 'select';

	/**
	 * Method to get the field options.
	 *
	 * @return    array    The field option objects.
	 * @since    1.6
	 */
	protected function getOptions()
	{
		/** @global $gantry Gantry */
		global $gantry;
		$options = array();
		$options = parent::getOptions();

		$choices = array(
			"linear",
			"Quad.easeOut",
			"Quad.easeIn",
			"Quad.easeInOut",
			"Cubic.easeOut",
			"Cubic.easeIn",
			"Cubic.easeInOut",
			"Quart.easeOut",
			"Quart.easeIn",
			"Quart.easeInOut",
			"Quint.easeOut",
			"Quint.easeIn",
			"Quint.easeInOut",
			"Expo.easeOut",
			"Expo.easeIn",
			"Expo.easeInOut",
			"Circ.easeOut",
			"Circ.easeIn",
			"Circ.easeInOut",
			"Sine.easeOut",
			"Sine.easeIn",
			"Sine.easeInOut",
			"Back.easeOut",
			"Back.easeIn",
			"Back.easeInOut",
			"Bounce.easeOut",
			"Bounce.easeIn",
			"Bounce.easeInOut",
			"Elastic.easeOut",
			"Elastic.easeIn",
			"Elastic.easeInOut"
		);

		foreach ($choices as $choice) {
			// Create a new option object based on the <option /> element.
			$tmp       = GantryHtmlSelect::option($choice, $choice, 'value', 'text', false);
			$options[] = $tmp;
		}
		return $options;
	}
}
