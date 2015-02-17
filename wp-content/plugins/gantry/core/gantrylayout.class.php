<?php
/**
 * @version   $Id: gantrylayout.class.php 59702 2013-05-17 21:54:07Z btowles $
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2014 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */
defined('GANTRY_VERSION') or die();

/**
 * Base class for all Gantry custom features.
 *
 * @package    gantry
 * @subpackage core
 */
class GantryLayout
{
	protected $render_params = array();

	public function render($params = array())
	{
		return $params;
	}

	function _getParams($params = array())
	{
		$ret       = new stdClass();
		$ret_array = array_merge($this->render_params, $params);
		foreach ($ret_array as $param_name => $param_value) {
			$ret->$param_name = $param_value;
		}
		return $ret;
	}

	function _getWidgetInstanceParams($widget_id)
	{
		global $wp_registered_widgets;
		$widget_info =& $wp_registered_widgets[$widget_id];

		if (is_array($widget_info['callback'])) {
			$widget =& $widget_info['callback'][0];
			if (is_object($widget) && $widget instanceof WP_Widget) {
				$instances       = $widget->get_settings();
				$instance_params = $instances[$widget_info['params'][0]['number']];
			}
		} else {
			$instance_params = $wp_registered_widgets[$widget_id]['params'];
		}
		if (empty($instance_params)) $instance_params = array();
		return $instance_params;
	}
}