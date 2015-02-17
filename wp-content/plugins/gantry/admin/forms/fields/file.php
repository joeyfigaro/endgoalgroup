<?php
/**
 * @version   $Id: file.php 59361 2013-03-13 23:10:27Z btowles $
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

class GantryFormFieldFile extends GantryFormField
{


	protected $type = 'html';
	protected $basetype = 'none';

	public function getInput()
	{
		/** @global $gantry Gantry */
		global $gantry;
		$html = '';

		$filepath = $this->element['path'];
		$filepath = gantry_clean_path(realpath($gantry->templatePath . $filepath));
		if ($filepath != false) {
			ob_start();
			include($filepath);
			$html = ob_get_clean();
		}
		return "<div class='html'>" . $html . "</div>";
	}

	public function getLabel()
	{
		return "";
	}
}