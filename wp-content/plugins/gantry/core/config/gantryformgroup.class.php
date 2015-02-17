<?php
/**
 * @version        $Id: gantryformgroup.class.php 58623 2012-12-15 22:01:32Z btowles $
 * @author         RocketTheme http://www.rockettheme.com
 * @copyright      Copyright (C) 2007 - 2014 RocketTheme, LLC
 * @license        http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 * original copyright
 * @copyright      Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license        GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('GANTRY_VERSION') or die;

gantry_import('core.config.gantryformitem');

abstract class GantryFormGroup extends GantryFormItem
{
	/**
	 * @var array
	 */
	protected $fields = array();

	protected $prelabel_function = null;

	protected $postlabel_function = null;

	/**
	 * Method to attach a JForm object to the field.
	 *
	 * @param    object    $element      The JXMLElement object representing the <field /> tag for the
	 *                                   form field object.
	 * @param    mixed     $value        The form field default value for display.
	 * @param    string    $group        The field name group control value. This acts as as an array
	 *                                   container for the field. For example if the field has name="foo"
	 *                                   and the group value is set to "bar" then the full field name
	 *                                   would end up being "bar[foo]".
	 *
	 * @return    boolean    True on success.
	 * @since    1.6
	 */
	public function setup(& $element, $value, $group = null)
	{
		// Make sure there is a valid JFormField XML element.
		if (!($element instanceof GantrySimpleXMLElement) || (string)$element->getName() != 'fields') {
			return false;
		}

		if (!parent::setup($element, $value, $group)) return false;

		$this->fields = $this->form->getSubFields($this->element);

		foreach ($this->fields as $field) {
			if ($field->variance) $this->variance = true;
		}
		return true;
	}

	protected function getId($fieldId, $fieldName, $group = null)
	{

		// Initialize variables.
		$id = ($fieldId ? $fieldId : $fieldName);

		if (is_a($this->form->control, 'WP_Widget')) {
			$id = $this->form->control->get_field_id($id);
		} else if (is_a($this->form->control, 'GantryTemplateInfo')) {
			$id = $this->form->control->get_field_id($id, $group);
		}

		return $id;
	}

	/**
	 * Method to get the name used for the field input tag.
	 *
	 * @param    string    $fieldName    The field element name.
	 * @param    string    $group        The optional name of the group that the field element is a
	 *                                   member of.
	 *
	 * @return    string    The name to be used for the field input tag.
	 * @since    1.6
	 */
	protected function getName($fieldName, $group = null)
	{
		// Initialize variables.
		$name = '';

		if (is_a($this->form->control, 'WP_Widget')) {
			$name = $this->form->control->get_field_name($fieldName);
		} else if (is_a($this->form->control, 'GantryTemplateInfo')) {
			$name = $this->form->control->get_field_name($fieldName, $group);
		}

		return $name;
	}


	public function setLabelWrapperFunctions($prelabel_function = null, $postlabel_function = null)
	{
		$this->prelabel_function  = $prelabel_function;
		$this->postlabel_function = $postlabel_function;
	}

	protected function preLabel($field)
	{
		if ($this->prelabel_function == null || !function_exists($this->prelabel_function)) return '';
		return call_user_func_array($this->prelabel_function, array($field));
	}

	protected function postLabel($field)
	{
		if ($this->postlabel_function == null) return '';
		return call_user_func_array($this->postlabel_function, array($field));
	}
}