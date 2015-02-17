<?php
/**
 * @version   $Id: iphonemenu.php 60832 2014-05-12 09:47:23Z jakub $
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2014 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

if (!class_exists('GantryWidgetMenu')) {
	require_once(dirname(__FILE__) . '/menu.php');
}

/**
 *
 */
class GantryWidgetiPhoneMenu extends GantryWidgetMenu
{
	static $themes = array();

	var $short_name = 'iphonemenu';
	var $wp_name = 'gantry_iphonemenu';
	var $long_name = 'Gantry Mobile Menu';
	var $description = 'Gantry iPhone/Android Menu';
	var $css_classname = 'widget_gantry_iphonemenu';
	var $width = 200;
	var $height = 400;
	var $_defaults = array(
		'limit_levels'    => 0,
		'startLevel'      => 0,
		'endLevel'        => 0,
		'showAllChildren' => 1,
		'maxdepth'        => 10
	);

	public static function init()
	{
		/** @global $gantry Gantry */
        global $gantry;
		register_widget("GantryWidgetiPhoneMenu");
		/*		$gantry->addInlineScript("var animation = '" . $gantry->get('touchmenu-animation', 'cube') . "';");
				$gantry->addScript('imenu.js');*/
		parent::init();
	}

	public function render($args, $instance)
	{
		/** @global $gantry Gantry */
        global $gantry;
		if ($gantry->browser->platform == 'iphone' || $gantry->browser->platform == 'android') {
			parent::render($args, $instance);
		}
	}

	function form($instance)
	{
		gantry_import('core.config.gantryform');

		/** @global $gantry Gantry */
        global $gantry;

		$defaults = $this->_defaults;

		$gantry->addScript('mootools.js');

		$instance = wp_parse_args((array)$instance, $defaults);

		foreach ($instance as $variable => $value) {
			$$variable           = GantryWidget::_cleanOutputVariable($variable, $value);
			$instance[$variable] = $$variable;
		}

		$this->_values = $instance;
		$form          = GantryForm::getInstance($this, $this->short_name, $this->short_name);
		$form->bind($this->_values);

		ob_start();

		$fieldSets = $form->getFieldsets();

		foreach ($fieldSets as $name => $fieldSet) {
			?>
			<fieldset class="panelform">
				<?php foreach ($form->getFieldset($name) as $field) : ?>
					<div class="field-wrapper">
						<?php echo $field->label; ?>
						<?php echo $field->input; ?>
					</div>
				<?php endforeach; ?>
			</fieldset>
		<?php

		}
		echo ob_get_clean();
	}
}