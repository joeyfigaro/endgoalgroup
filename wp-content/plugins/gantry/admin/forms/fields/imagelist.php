<?php
/**
 * @version 	$Id: imagelist.php 2325 2012-08-13 17:46:48Z btowles $
 * @author    	RocketTheme http://www.rockettheme.com
 * @copyright 	Copyright (C) 2007 - 2014 RocketTheme, LLC
 * @license   	http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

defined('GANTRY_VERSION') or die();

gantry_import('core.config.gantryformfield');
gantry_import('core.config.gantryformhelper');
GantryFormHelper::loadFieldType('filelist');

/**
 * Supports an HTML select list of file
 */
class GantryFormFieldImageList extends GantryFormFieldFileList
{
	/**
	 * The form field type.
	 *
	 * @var        string
	 */
	public $type = 'ImageList';

	/**
	 * Method to get the field options.
	 *
	 * @return    array    The field option objects.
	 */
	public function getOptions()
	{
		// Define the image file type filter.
		$filter = '\.png$|\.gif$|\.jpg$|\.bmp$|\.ico$|\.jpeg$|\.psd$|\.eps$';

		// Set the form field element attribute for file type filter.
		$this->element->addAttribute('filter', $filter);

		// Get the field options.
		return parent::getOptions();
	}
}
