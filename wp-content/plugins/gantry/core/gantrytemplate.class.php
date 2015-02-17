<?php
/**
`` * @version   $Id: gantrytemplate.class.php 59361 2013-03-13 23:10:27Z btowles $
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2014 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */
defined('GANTRY_VERSION') or die();

gantry_import('core.utilities.gantrytemplateinfo');

/**
 * Populates the parameters and template configuration form the templateDetails.xml and params.ini
 *
 * @package    gantry
 * @subpackage core
 */
class GantryTemplate
{
	/**
	 * @var GantrySimpleXMLElement
	 */
	protected $xml;

	/**
	 * @var array
	 */
	protected $positions = array();
	/**
	 * @var array
	 */
	protected $params = array();

	/**
	 * @var GantryTemplateInfo
	 */
	protected $template_info;

	/**
	 * @var array
	 */
	protected $widget_styles = array();

	/** @var array */
	protected $widget_chromes = array();

	/**
	 * @var string
	 */
	protected $_mainTemplateFile = '/templateDetails.xml';
	/**
	 * @var null
	 */
	protected $_pramas_ini = null;
	/**
	 * @var array
	 */
	protected $_template_settings = array();
	/**
	 * @var array
	 */
	protected $_params_content = array();
	/**
	 * @var array
	 */
	protected $_ingorables = array('spacer', 'gspacer', 'gantry');

	/**
	 * @var array
	 */
	protected $_processors = array();

	/**
	 * @return array
	 */
	function __sleep()
	{
		return array('positions', 'params', 'widget_styles', '_template_settings', 'template_info', 'widget_chromes');
	}

	/**
	 *
	 */
	public function __construct()
	{

	}

	/**
	 * @param Gantry $gantry
	 */
	public function init(&$gantry)
	{
		gantry_import("core.utilities.gantrysimplexmlelement");

		$this->xml = new GantrySimpleXMLElement($gantry->templatePath . '/templateDetails.xml', null, true);
		if ($this->xml === false) {
			// TODO: figure out way to return error properly
			echo "Unable to find templateDetails.xml file";
		}
		$this->positions = $this->loadPositions();
		$tmp_options     = get_option($gantry->templateName . '-template-options');
		if ($tmp_options !== false) {
			foreach ($tmp_options as $option_name => $option_value) {
				$this->_addTemplateSettings($option_name, $option_value);
			}
		}
		$this->params = $this->getXMLParams($gantry);
		$this->loadTemplateInfo();
		$this->widget_styles = $this->_getWidgetStyles();
		$this->widget_chromes = $this->_getWidgetChromes();
	}

	/**
	 * @param $option_name
	 * @param $option_value
	 */
	protected function _addTemplateSettings($option_name, $option_value)
	{
		if (!is_array($option_value)) {
			$this->_template_settings[$option_name] = $option_value;
		} else {
			foreach ($option_value as $sub_option_name => $sub_option_value) {
				$this->_addTemplateSettings($option_name . '-' . $sub_option_name, $sub_option_value);
			}
		}
	}

	/**
	 * @return array
	 */
	public function getParams()
	{
		return $this->params;
	}

	/**
	 * @return array
	 */
	protected function & loadPositions()
	{
		$positions = array();
		//$xml_positions = $this->xml->document->positions[0]->children();
		$xml_positions = $this->xml->xpath('//positions/position');
		foreach ($xml_positions as $position) {
			$positionObject                 = new stdClass();
			$attrs                          = $position->attributes();
			$positionObject->name           = (string)$attrs['name'];
			$positionObject->id             = (string)$attrs['id'];
			$positionObject->max_positions  = (int)((string)$attrs['max_positions']);
			$positionObject->description    = $position->data();
			$positionObject->mobile         = ((string)$attrs['mobile'] == 'true') ? true : false;
			$positions[$positionObject->id] = $positionObject;
		}
		return $positions;
	}

	/**
	 * @return array
	 */
	protected function & _getWidgetStyles()
	{
		$style_types     = array();
		$xml_stylegroups = $this->xml->xpath('//widget_styles/stylegroup');

		foreach ($xml_stylegroups as $style_group) {
			$style_group_entry          = array();
			$style_group_entry['label'] = (string)$style_group['label'];
			$style_group_entry['name']  = (string)$style_group['name'];
			$xml_styles                 = $this->xml->xpath('//widget_styles/stylegroup[@name="' . $style_group['name'] . '"]/style');
			foreach ($xml_styles as $style) {
				$style_group_entry['styles'][(string)$style['name']] = (string)$style['label'];
			}
			$style_types[] = $style_group_entry;
		}
		return $style_types;
	}
	/**
	 * @return array
	 */
	protected function & _getWidgetChromes()
	{
		$xml_chromes = $this->xml->xpath('//widget_chromes/chrome');
		$chromes          = array();
		foreach ($xml_chromes as $xml_chrome) {
			$chromes[(string)$xml_chrome['name']] = (string)$xml_chrome['label'];
		}
		return $chromes;
	}

	/**
	 * @param $gantry
	 */
	protected function loadProcessors(&$gantry)
	{
		$processor_path = $gantry->gantryPath . '/core/params/processors';

		if (file_exists($processor_path) && is_dir($processor_path)) {
			$d = dir($gantry->gantryPath . '/core/params/processors');
			while (false !== ($entry = $d->read())) {
				if ($entry != '.' && $entry != '..') {
					$processor_name = basename($entry, ".php");
					$path           = $processor_path . '/' . $processor_name . '.php';
					$className      = 'GantryParamProcessor' . ucfirst($processor_name);
					if (!class_exists($className)) {
						if (file_exists($path)) {
							require_once($path);
							if (class_exists($className)) {
								$this->_processors[$className] = new $className();
							}
						}
					}
				}
			}
			$d->close();
		}
	}

	/**
	 * @param $gantry
	 * @param $param_name
	 * @param $param_element
	 * @param $data
	 */
	protected function runProcessorPreLoad(&$gantry, $param_name, &$param_element, &$data)
	{
		/** @var $processor GantryParamProcessor */
		foreach ($this->_processors as $processor) {

			$processor->preLoad($gantry, $param_name, $param_element, $data);
		}
	}

	/**
	 * @param $gantry
	 * @param $param_name
	 * @param $param_element
	 * @param $data
	 */
	protected function runProcessorPostLoad(&$gantry, $param_name, &$param_element, &$data)
	{
		/** @var $processor GantryParamProcessor */
		foreach ($this->_processors as $processor) {
			$processor->postLoad($gantry, $param_name, $param_element, $data);
		}
	}

	/**
	 * @return array
	 */
	public function getUniquePositions()
	{
		return array_keys($this->positions);
	}

	/**
	 * @param $position_name
	 *
	 * @return mixed
	 */
	public function getPositionInfo($position_name)
	{
		return $this->positions[$position_name];
	}

	/**
	 * @return array
	 */
	public function getPositions()
	{
		return $this->positions;
	}

	/**
	 * @param $position
	 * @param $pattern
	 *
	 * @return array
	 */
	public function parsePosition($position, $pattern)
	{
		if (null == $pattern) {
			$pattern = "(-)?";
		}
		$filtered_positions = array();

		if (count($this->positions) > 0) {
			$regpat = "/^" . $position . $pattern . "/";
			foreach (array_keys($this->positions) as $value) {
				if (preg_match($regpat, $value) == 1) {
					$filtered_positions[] = $value;
				}
			}
		}
		return $filtered_positions;
	}

	/**
	 * @param $gantry
	 *
	 * @return array
	 */
	protected function getXMLParams(&$gantry)
	{
		$this->_params_content = array();

		$this->loadParamsContent($gantry);

		$data = array();
		//$params = $this->xml->document->config[0]->fields[0]->fieldset[0]->children();
		$params = $this->xml->xpath('//config/fields[@name="template-options"]//field');

		foreach ($params as $param) {
			//skip for unsupported types
			if (in_array($param['type'], $this->_ingorables)) continue;

			$attrs  = $param->xpath('ancestor::fields[@name]/@name');
			$groups = array_map('strval', $attrs ? $attrs : array());
			$groups = array_flip($groups);
			if (array_key_exists('template-options', $groups)) unset($groups['template-options']);
			$groups = array_flip($groups);
			$prefix = '';
			foreach ($groups as $parent) {
				$prefix .= $parent . "-";
			}
			$param_name = $prefix . $param['name'];
			$this->_getParamInfo($gantry, $param_name, $param, $data);
		}
		$this->params = $data;
		return $data;
	}

	/**
	 * Loads the params.ini content
	 *
	 * @param  $gantry
	 *
	 * @return bool
	 */
	protected function loadParamsContent(&$gantry)
	{
		$templateOptions = get_option($gantry->templateName . "-template-options", "");
		if (!empty($templateOptions) && $templateOptions === false) {
			$this->_params_content = $templateOptions;
			return true;
		}
		return false;
	}

	/**
	 * @return string
	 */
	function getParamsHash()
	{
		return md5($this->implodeWithKey("&", $this->_params_content));
	}

	/**
	 * @param        $gantry
	 * @param        $param_name
	 * @param        $param
	 * @param        $data
	 * @param string $prefix
	 */
	protected function _getParamInfo(&$gantry, $param_name, &$param, &$data, $prefix = "")
	{

		$attributes = array();
		foreach ($param->attributes() as $key => $val) {
			$attributes[$key] = (string)$val;
		}

		$full_param_name = $prefix . $param_name;

		$default = (array_key_exists('default', $attributes)) ? $attributes['default'] : false;
		$value   = (array_key_exists($full_param_name, $this->_template_settings)) ? $this->_template_settings[$full_param_name] : (array_key_exists('default', $attributes) ? $attributes['default'] : false);

		// run the preload of the processors
		$this->runProcessorPreLoad($gantry, $full_param_name, $param, $data);

		$data[$full_param_name] = array(
			'name'          => $full_param_name,
			'type'          => $attributes['type'],
			'default'       => $default,
			'value'         => $value,
			'sitebase'      => $value,
			'setbyurl'      => (array_key_exists('setbyurl', $attributes)) ? ($attributes['setbyurl'] == 'true') ? true : false : false,
			'setbycookie'   => (array_key_exists('setbycookie', $attributes)) ? ($attributes['setbycookie'] == 'true') ? true : false : false,
			'setbysession'  => (array_key_exists('setbysession', $attributes)) ? ($attributes['setbysession'] == 'true') ? true : false : false,
			'setincookie'   => (array_key_exists('setbycookie', $attributes)) ? ($attributes['setbycookie'] == 'true') ? true : false : false,
			'setinsession'  => (array_key_exists('setinsession', $attributes)) ? ($attributes['setinsession'] == 'true') ? true : false : false,
			'setinoverride' => (array_key_exists('setinoverride', $attributes)) ? ($attributes['setinoverride'] == 'true') ? true : false : true,
			'setbyoverride' => (array_key_exists('setbyoverride', $attributes)) ? ($attributes['setbyoverride'] == 'true') ? true : false : true,
			'isbodyclass'   => (array_key_exists('isbodyclass', $attributes)) ? ($attributes['isbodyclass'] == 'true') ? true : false : false,
			'setclassbytag' => (array_key_exists('setclassbytag', $attributes)) ? $attributes['setclassbytag'] : false,
			'setby'         => 'default',
			'attributes'    => &$attributes
		);

		if ($data[$full_param_name]['setbyurl']) $gantry->_setbyurl[] = $full_param_name;
		if ($data[$full_param_name]['setbysession']) $gantry->_setbysession[] = $full_param_name;
		if ($data[$full_param_name]['setbycookie']) $gantry->_setbycookie[] = $full_param_name;
		if ($data[$full_param_name]['setinsession']) $gantry->_setinsession[] = $full_param_name;
		if ($data[$full_param_name]['setincookie']) $gantry->_setincookie[] = $full_param_name;
		if ($data[$full_param_name]['setinoverride']) {
			$gantry->_setinoverride[] = $full_param_name;
		} else {
			$gantry->dontsetinoverride[] = $full_param_name;
		}
		if ($data[$full_param_name]['setbyoverride']) $gantry->_setbyoverride[] = $full_param_name;
		if ($data[$full_param_name]['isbodyclass']) $gantry->_bodyclasses[] = $full_param_name;
		if ($data[$full_param_name]['setclassbytag']) $gantry->_classesbytag[$data[$full_param_name]['setclassbytag']][] = $full_param_name;

		$this->runProcessorPostLoad($gantry, $full_param_name, $param, $data);

	}

	/**
	 * @param null   $glue
	 * @param        $pieces
	 * @param string $hifen
	 *
	 * @return string
	 */
	protected function implodeWithKey($glue = null, $pieces, $hifen = ',')
	{
		$return = null;
		foreach ($pieces as $tk => $tv) $return .= $glue . $tk . $hifen . $tv;
		return substr($return, 1);
	}

	/**
	 *
	 */
	protected function loadTemplateInfo()
	{
		$this->template_info = new GantryTemplateInfo;

		if ($this->xml->name) $this->template_info->setName((string)$this->xml->name);
		if ($this->xml->version) $this->template_info->setVersion((string)$this->xml->version);
		if ($this->xml->creationDate) $this->template_info->setCreationDate((string)$this->xml->creationDate);
		if ($this->xml->author) $this->template_info->setAuthor((string)$this->xml->author);
		if ($this->xml->authorUrl) $this->template_info->setAuthorUrl((string)$this->xml->authorUrl);
		if ($this->xml->authorEmail) $this->template_info->setAuthorEmail((string)$this->xml->authorEmail);
		if ($this->xml->copyright) $this->template_info->setCopyright((string)$this->xml->copyright);
		if ($this->xml->license) $this->template_info->setLicense((string)$this->xml->license);
		if ($this->xml->description) $this->template_info->setDescription((string)$this->xml->description);
		if ($this->xml->legacycss) $this->template_info->setLegacycss((string)$this->xml->legacycss);
		if ($this->xml->gridcss) $this->template_info->setGridcss((string)$this->xml->gridcss);
	}

	/**
	 * @return \GantryTemplateInfo
	 */
	public function getTemplateInfo()
	{
		return $this->template_info;
	}

	/**
	 * @return array
	 */
	public function getWidgetStyles()
	{
		return $this->widget_styles;
	}

	/**
	 * @return array
	 */
	public function getWidgetChromes()
	{
		return $this->widget_chromes;
	}
}