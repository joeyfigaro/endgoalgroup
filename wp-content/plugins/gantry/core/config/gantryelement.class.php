<?php
/**
 * @version        $Id: gantryelement.class.php 58623 2012-12-15 22:01:32Z btowles $
 * @author         RocketTheme http://www.rockettheme.com
 * @copyright      Copyright (C) 2007 - 2014 RocketTheme, LLC
 * @license        http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 * derived from Joomla with original copyright and license
 * @copyright      Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license        GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('GANTRY_VERSION') or die();


class GantryElement
{
	/**
	 * element name
	 *
	 * This has to be set in the final
	 * renderer classes.
	 *
	 * @access    protected
	 * @var        string
	 */
	var $_name = null;

	/**
	 * reference to the object that instantiated the element
	 *
	 * @access    protected
	 * @var        object
	 */
	var $_parent = null;

	/**
	 * Constructor
	 *
	 * @access protected
	 */
	function __construct($parent = null)
	{
		$this->_parent = $parent;
	}

	/**
	 * get the element name
	 *
	 * @access    public
	 * @return    string    type of the parameter
	 */
	function getName()
	{
		return $this->_name;
	}

	function render(&$xmlElement, $value, $control_name, $control_id)
	{
		$name  = $xmlElement->attributes('name');
		$label = $xmlElement->attributes('label');
		$descr = $xmlElement->attributes('description');
		//make sure we have a valid label
		$label            = $label ? $label : $name;
		$result['render'] = $this->fetchElement($name, $value, $xmlElement, $control_name, $control_id);
		$result['descr']  = $descr;
		$result['label']  = $label;
		$result['value']  = $value;
		$result['name']   = $name;
		$result['id']     = $control_id;
		return $result;
	}

	function fetchElement($name, $value, &$xmlElement, $control_name, $control_id)
	{
		return;
	}
}
