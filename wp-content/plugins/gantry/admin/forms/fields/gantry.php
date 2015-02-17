<?php
/**
 * @version   $Id: gantry.php 59361 2013-03-13 23:10:27Z btowles $
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

class GantryFormFieldGANTRY extends GantryFormField
{

	protected $type = 'gantry';
	protected $basetype = 'none';

	public function getInput()
	{
		/** @global $gantry Gantry */
		global $gantry;
		$gantry->addInlineScript("
				var GantryTemplate = '" . $gantry->templateName . "',
					GantryAjaxURL = '" . $gantry->getAjaxUrl() . "',
					GantryURL = '" . $gantry->gantryUrl . "',
					GantryParamsPrefix = '" . $gantry->templateName . "-template_options_';
				var AdminURI = GantryAjaxURL;");
		$gantry->addInlineScript($this->_gantryLang());

		return null;
		}

	protected function _gantryLang()
	{
		return "var GantryLang = {
				'preset_title': '" . _g('PRESET_TITLE') . "',
				'preset_select': '" . _g('PRESET_SELECT') . "',
				'preset_name': '" . _g('PRESET_NAME') . "',
				'key_name': '" ._g('KEY_NAME') . "',
				'preset_naming': '" . _g('PRESET_NAMING') . "',
				'preset_skip': '" . _g('PRESET_SKIP') . "',
				'success_save': '" . _g('SUCCESS_SAVE') . "',
				'success_msg': '" . _g('SUCCESS_MSG') . "',
				'fail_save': '" . _g('FAIL_SAVE') . "',
				'fail_msg': '" . _g('FAIL_MSG') . "',
				'cancel': '" . _g('CANCEL') . "',
				'save': '" . _g('SAVE') . "',
				'retry': '" . _g('RETRY') . "',
				'close': '" . _g('CLOSE') . "',
				'show_parameters': '" . _g('SHOW_PARAMETERS') . "',
				'are_you_sure': '" ._g('PRESETS_DELETE_ARE_YOU_SURE')  . "'
			};
		";
	}

	public function getLabel()
	{
		return "";
	}

}
