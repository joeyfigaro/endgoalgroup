<?php
/**
 * @version   $Id: search-highlight.php 58623 2012-12-15 22:01:32Z btowles $
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
class GantryGizmoSearchHighlight extends GantryGizmo
{
	var $_name = 'searchhighlight';

	function isEnabled()
	{
		return true;
	}

	function query_parsed_init()
	{
		global $gantry, $s;
		if(is_search()) {
			$gantry->addScript( 'gantry-search-highlight.js' );
			$js = 'window.addEvent(\'domready\', function() { highlight(\'' . $s . '\'); });';
			$gantry->addInlineScript( $js );
		}
	}
}