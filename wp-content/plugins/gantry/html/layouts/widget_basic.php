<?php
/**
 * @version   $Id: widget_basic.php 59361 2013-03-13 23:10:27Z btowles $
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2014 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 */
defined('GANTRY_VERSION') or die();

gantry_import('core.gantrylayout');

/**
 *
 * @package    gantry
 * @subpackage html.layouts
 */
class GantryLayoutWidget_Basic extends GantryLayout
{
	var $render_params = array(
		'gridCount'   => null,
		'prefixCount' => 0,
		'extraClass'  => ''
	);

	function render($params = array())
	{
		/** @global $gantry Gantry */
		global $gantry;
		$instance_params = $this->_getWidgetInstanceParams($params[0]['widget_id']);
		$chrome_to_use   = (isset($instance_params['widget_chrome']) && !empty($instance_params['widget_chrome'])) ? $instance_params['widget_chrome'] : $params[0]['chrome'];
		$params          = $gantry->renderLayout("chrome_" .$chrome_to_use, $params);
		$params[0]['position_open']  = '';
		$params[0]['position_close'] = '';
		return $params;
	}
}