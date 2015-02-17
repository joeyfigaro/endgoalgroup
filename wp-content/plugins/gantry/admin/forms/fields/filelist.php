<?php
/**
 * @version 	$Id: filelist.php 2325 2012-08-13 17:46:48Z btowles $
 * @author    	RocketTheme http://www.rockettheme.com
 * @copyright 	Copyright (C) 2007 - 2014 RocketTheme, LLC
 * @license   	http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

defined('GANTRY_VERSION') or die();

gantry_import('core.utilities.gantryfile');
gantry_import('core.utilities.gantryfolder');
gantry_import('core.utilities.gantrypath');
gantry_import('core.config.gantryformfield');
gantry_import('core.config.gantryformhelper');
GantryFormHelper::loadFieldType('list');

/**
 * Supports an HTML select list of file
 */
class GantryFormFieldFileList extends GantryFormFieldSelectBox
{
	/**
	 * The form field type.
	 *
	 * @var        string
	 */
	public $type = 'FileList';
	protected $basetype = 'select';

	/**
	 * Method to get the field options.
	 *
	 * @return    array    The field option objects.
	 */
	public function getOptions()
	{
		// Initialize variables.
		$options = array();

		// Initialize some field attributes.
		$filter      = (string)$this->element['filter'];
		$exclude     = (string)$this->element['exclude'];
		$stripExt    = (string)$this->element['stripext'];
		$hideNone    = (string)$this->element['hide_none'];
		$hideDefault = (string)$this->element['hide_default'];

		// Get the path in which to search for file options.
		$path = (string)$this->element['directory'];
		if (!is_dir($path)) {
			$path = ABSPATH . $path;
		}

		// Prepend some default options based on field attributes.
		if (!$hideNone) {
			$options[] = GantryHtmlSelect::option('-1', _g(trim((string)'NONE_SELECTED')));
		}
		if (!$hideDefault) {
			$options[] = GantryHtmlSelect::option('', _g(trim((string)'USE_DEFAULT')));
		}

		// Get a list of files in the search path with the given filter.
		$files = GantryFolder::files($path, $filter);

		// Build the options list from the list of files.
		if (is_array($files)) {
			foreach ($files as $file) {

				// Check to see if the file is in the exclude mask.
				if ($exclude) {
					if (preg_match(chr(1) . $exclude . chr(1), $file)) {
						continue;
					}
				}

				// If the extension is to be stripped, do it.
				if ($stripExt) {
					$file = GantryFile::stripExt($file);
				}

				$options[] = GantryHtmlSelect::option($file, $file);
			}
		}

		// Merge any additional options in the XML definition.
		$options = array_merge(parent::getOptions(), $options);

		return $options;
	}
}
