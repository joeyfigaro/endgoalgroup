<?php
/**
 * @version   $Id: widgetshortcodes.php 60530 2014-02-10 12:59:26Z jakub $
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2014 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

defined( 'GANTRY_VERSION' ) or die();

gantry_import( 'core.gantrygizmo' );

/**
 * @package     gantry
 * @subpackage  features
 */
class GantryGizmoWidgetShortcodes extends GantryGizmo {

	var $_name = 'widgetshortcodes';

	function query_parsed_init() {

		add_filter( 'widget_text', 'do_shortcode' );

	}
}