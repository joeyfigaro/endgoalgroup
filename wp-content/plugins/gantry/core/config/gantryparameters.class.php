<?php
/**
 * @version        $Id: gantryparameters.class.php 59361 2013-03-13 23:10:27Z btowles $
 * @author         RocketTheme http://www.rockettheme.com
 * @copyright      Copyright (C) 2007 - 2014 RocketTheme, LLC
 * @license        http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 * derived from Joomla with original copyright and license
 * @copyright      Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license        GNU General Public License version 2 or later; see LICENSE.txt
 */
class GantryParameters
{
	/**
	 * The xml params element
	 *
	 * @access    private
	 * @var        object
	 * @since    1.5
	 */
	var $_xml = null;

	/**
	 * loaded elements
	 *
	 * @access    private
	 * @var        array
	 * @since    1.5
	 */
	var $_elements = array();

	/**
	 * directories, where element types can be stored
	 *
	 * @access    private
	 * @var        array
	 * @since    1.5
	 */
	var $_elementPaths = array();


	var $_values = null;


	/**
	 * @param array $paths The paths to check for elements
	 *
	 * @return void
	 */
	function GantryParameters(&$xml, &$values = array(), $paths = array())
	{
		/** @global $gantry Gantry */
		global $gantry;

		if ($ps =& $xml->document->params) {
			foreach ($ps as $p) {
				$this->_xml = $p;
			}
		}

		//$this->_xml = &$xml;

		$this->_values = $values;

		$this->addElementPath($gantry->templatePath . "/admin/elements");
		if (count($paths > 0)) {
			foreach ($paths as $path) {
				$this->addElementPath($path);
			}
		}
		$this->addElementPath($gantry->gantryPath . "/admin/elements");
	}

	/**
	 * @param  $path
	 *
	 * @return void
	 */
	function addElementPath($path)
	{
		if (file_exists($path) && is_dir($path)) {
			$this->_elementPaths[] = $path;
		}
	}

	/**
	 * Render all parameters
	 *
	 * @access    public
	 *
	 * @param    string    The name of the control, or the default text area if a setup file is not found
	 *
	 * @return    array    Aarray of all parameters, each as array Any array of the label, the form element and the tooltip
	 * @since    1.5
	 */
	function getParams(&$control = null)
	{
		if (!isset($this->_xml)) {
			return false;
		}
		$results = array();
		foreach ($this->_xml->children() as $param) {
			$results[] = $this->getParam($param, $control);
		}
		return $results;
	}

	/**
	 * Render a parameter type
	 *
	 * @param    object    A param tag node
	 * @param    string    The control name
	 *
	 * @return    array    Any array of the label, the form element and the tooltip
	 * @since    1.5
	 */
	function getParam(&$node, &$control = null)
	{
		//get the type of the parameter
		$type = $node->attributes('type');

		$element = $this->loadElement($type);

		// error happened
		if ($element === false) {
			$result    = array();
			$result[0] = $node->attributes('name');
			$result[1] = _g('Element not defined for type') . ' = ' . $type;
			$result[5] = $result[0];
			return $result;
		}

		//get value
		$value = $node->attributes('default');
		if (array_key_exists($node->attributes('name'), $this->_values)) {
			$value = $this->_values[$node->attributes('name')];
		}

		// get the control name and id
		$control_name = $node->attributes('name');
		$control_id   = $node->attributes('name');

		if (!empty($control) && method_exists($control, 'get_field_id')) {
			$control_id = $control->get_field_id($node->attributes('name'));
		}
		if (!empty($control) && method_exists($control, 'get_field_name')) {
			$control_name = $control->get_field_name($node->attributes('name'));
		}

		return $element->render($node, $value, $control_name, $control_id);
	}

	/**
	 * Loads a element type
	 *
	 * @access    public
	 *
	 * @param    string    elementType
	 *
	 * @return    object
	 * @since    1.5
	 */
	function &loadElement($type, $new = false)
	{
		$signature = md5($type);

		if ((isset($this->_elements[$signature]) && !is_a($this->_elements[$signature], '__PHP_Incomplete_Class')) && $new === false) {
			return $this->_elements[$signature];
		}

		$elementClass = 'GantryElement' . $type;
		if (!class_exists($elementClass)) {
			if (!empty($this->_elementPaths)) {
				$dirs = $this->_elementPaths;
				$file = str_replace('_', DS, $type) . '.php';

				foreach ($this->_elementPaths as $searchDir) {
					if (file_exists($searchDir) && is_dir($searchDir)) {
						$path = $searchDir . DS . $file;
						if (file_exists($path) && is_readable($path)) {
							require_once($path);
							break;
						}
					}
				}
			}
		}
		if (!class_exists($elementClass)) {
			return false;
		}
		$this->_elements[$signature] = new $elementClass($this);

		return $this->_elements[$signature];
	}
}