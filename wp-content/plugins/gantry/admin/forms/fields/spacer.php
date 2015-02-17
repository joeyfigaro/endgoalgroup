<?php
/**
 * @version   $Id: spacer.php 60813 2014-05-08 11:41:06Z jakub $
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2014 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */
defined('GANTRY_VERSION') or die;

gantry_import('core.config.gantryformfield');


class GantryFormFieldSpacer extends GantryFormField
{
	/**
	 * The form field type.
	 *
	 * @var        string
	 * @since    1.6
	 */
	protected $type = 'spacer';
	protected $basetype = 'none';

	/**
	 * Method to get the field input markup.
	 *
	 * @return    string    The field input markup.
	 * @since    1.6
	 */
	public function getInput()
	{
		return ' ';
	}

	/**
	 * Method to get the field label markup.
	 *
	 * @return    string    The field label markup.
	 * @since    1.6
	 */
	public function getLabel()
	{
		echo '<div class="clr"></div>';
		if ((string)$this->element['hr'] == 'true') {
            return '<hr />';
        } elseif ((string)$this->element['empty'] == 'true') {
            (isset($this->element['height']) && (string)$this->element['height'] != '') ? $height = $this->element['height'] : $height = 5;
            return '<div class="empty-spacer" style="height: ' . $height . 'px"></div>';
		} else {
			return parent::getLabel();
		}
		echo '<div class="clr"></div>';
	}

}