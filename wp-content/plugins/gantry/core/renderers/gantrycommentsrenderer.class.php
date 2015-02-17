<?php
/**
 * @version   $Id: gantrycommentsrenderer.class.php 59361 2013-03-13 23:10:27Z btowles $
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2014 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */
defined('GANTRY_VERSION') or die();
/**
 * @package     gantry
 * @subpackage  core.renderers
 */
class GantryCommentsRenderer
{
	/**
	 * @param string $layout
	 * @param string $commentLayout
	 *
	 * @return string
	 */
	public static function display($layout = 'basic', $commentLayout = 'basic')
	{
		/** @global $gantry Gantry */
		global $gantry;
		$output = $gantry->renderLayout('commentstempl_' . $layout, array('commentLayout' => $commentLayout));
		return $output;
	}
}   