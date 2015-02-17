<?php
/**
 * @version   $Id: texturize.php 58623 2012-12-15 22:01:32Z btowles $
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2014 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */


defined('GANTRY_VERSION') or die();

gantry_import('core.gantrygizmo');

/**
 * @package     gantry
 * @subpackage  features
 */
class GantryGizmoTexturize extends GantryGizmo
{

	var $_name = 'texturize';

	function query_parsed_init()
	{

		remove_filter('the_content', 'wptexturize');
		remove_filter('comment_text', 'wptexturize');

	}
}