<?php
/**
 * @version   $Id: widget_sidebar.php 59361 2013-03-13 23:10:27Z btowles $
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
class GantryLayoutWidget_Sidebar extends GantryLayout
{
	var $render_params = array(
		'contents'  => null,
		'position'  => null,
		'gridCount' => null,
		'pushPull'  => ''
	);

	function render($params = array())
	{
		/** @global $gantry Gantry */
		global $gantry;

		$chars  = "abcdefghijk";
		$instance_params = $this->_getWidgetInstanceParams($params[0]['widget_id']);
		$chrome_to_use   = (isset($instance_params['widget_chrome']) && !empty($instance_params['widget_chrome'])) ? $instance_params['widget_chrome'] : $params[0]['chrome'];
		$params          = $gantry->renderLayout("chrome_" .$chrome_to_use, $params);

		$params[0]['position_open']  = '';
		$params[0]['position_close'] = '';

		$rparams   = $this->_getParams($params[0]);
		$start_tag = "";

		// see if this is the first widget in the postion
		if (property_exists($rparams, 'start') && $rparams->start == $rparams->widget_id) {
			ob_start();
			?>
		<div class="rt-grid-<?php echo $rparams->widget_map[$rparams->position]['gridCount'];?> <?php echo $rparams->widget_map[$rparams->position]['pushPull']; ?>">
		<div id="rt-sidebar-<?php echo substr($chars, $rparams->position - 1, 1); ?>">
			<?php
			$start_tag                  = ob_get_clean();
			$params[0]['position_open'] = $start_tag;
		}

		if (property_exists($rparams, 'end') && $rparams->end == $rparams->widget_id) {
			$params[0]['position_close'] = "</div></div>";
		}

		$params[0]['before_widget'] = $params[0]['position_open'] . $params[0]['before_widget'];
		$params[0]['after_widget']  = $params[0]['after_widget'] . $params[0]['position_close'];

		return $params;
	}
}