<?php
/**
 * @version   $Id: gantryorderedmainbodyrenderer.class.php 60342 2014-01-03 17:12:22Z jakub $
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2014 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */
defined('GANTRY_VERSION') or die();
/**
 * @package     gantry
 * @subpackage  core.renderers
 */
gantry_import('core.renderers.gantrywidgetsrenderer');
class GantryOrderedMainBodyRenderer
{
	/**
	 * @param string $bodyLayout
	 * @param string $sidebarLayout
	 * @param string $sidebarChrome
	 * @param string $contentTopLayout
	 * @param string $contentTopChrome
	 * @param string $contentBottomLayout
	 * @param string $contentBottomChrome
	 * @param null   $grid
	 * @param string $component_content
	 *
	 * @return string
	 */
	public static function display($bodyLayout = 'mainbody', $sidebarLayout = 'sidebar', $sidebarChrome = 'standard', $contentTopLayout = 'standard', $contentTopChrome = 'standard', $contentBottomLayout = 'standard', $contentBottomChrome = 'standard', $grid = null, $component_content = '')
	{
		/** @global $gantry Gantry */
		global $gantry;
		global $wp_registered_sidebars, $wp_registered_widgets, $_wp_sidebars_widgets;

		if ($grid == null) {
			$grid = GRID_SYSTEM;
		}

		// get sidebar count
		$sidebars_widgets = wp_get_sidebars_widgets();
		$sidebarCount     = self::countSidebars($sidebars_widgets['sidebar']);
		$columnCount      = $sidebarCount + 1;

		$position_renders = array();

		//here we would see if the mainbody schema was set to soemthing else
		$defaultSchema = $gantry->mainbodySchemas[$grid][$columnCount];

		$mbp      = $gantry->get('mainbodyPosition');
		$position = @unserialize($mbp);

		if (!isset($position[$grid]) || !isset($position[$grid]) || !array_key_exists($columnCount, $position[$grid])) {
			$schema = $defaultSchema;
		} else {
			$schema = $position[$grid][$columnCount];
		}

		$end   = end(array_keys($schema));
		$start = reset(array_keys($schema));

		$rtl_enabled = (get_bloginfo('text_direction') == 'rtl' && $gantry->get('rtl-enabled')) ? true : false;

		// If RTL then flip the array
		if ($rtl_enabled) {
			//$schema = $gantry->_flipBodyPosition($schema);
			$schema = array_reverse($schema, true);
		}


		$classKey = $gantry->getKey($schema);
		$pushPull = $gantry->pushPullSchemas[$classKey];

		$output        = '';
		$sidebars      = '';
		$contentTop    = null;
		$contentBottom = null;

		$index = 1;
		// remove the mainbody and use the schema array for grid sizes
		$sidebarSchema = $schema;
		unset ($sidebarSchema['mb']);

		// Add extra data to sidebar
		$sidebar           = & $wp_registered_sidebars['sidebar'];
		$sidebar['layout'] = $sidebarLayout;
		$sidebar['chrome'] = $sidebarChrome;

		// clean to max sidebars
		$filtered_widgets = GantryWidgetsRenderer::filterWidgetCount($sidebars_widgets);

		$widgets = $filtered_widgets['sidebar'];

		if (null == $widgets) $widgets = array();
		// Map widgets to sidebars without the dividers
		$widget_map   = array();
		$pos          = 1;
		$pos_info_set = false;

		foreach ($widgets as $widget) {
			if (!$pos_info_set) {
				$widget_map[$pos]['gridCount'] = current($sidebarSchema);
				$widget_map[$pos]['pushPull']  = $pushPull[$index++];
				$pos_info_set                  = true;
			}
			if (preg_match("/^gantrydivider/", $widget)) {
				$pos++;
				$pos_info_set = false;
				next($sidebarSchema);
			} else {
				$widget_map[$pos]['widgets'][$widget] = array('name' => $widget);
			}
		}

		$sidebar['widget_map'] = $widget_map;

		$sidebar['start'] = $start;
		$sidebar['end']   = $end;


		$schemaShorts = array_keys($sidebarSchema);
		if ($rtl_enabled) {
			$schemaShorts = array_reverse($schemaShorts);
		}
		$sbcount = 0;
		foreach ($widget_map as $pos => $widgets) {
			$extraClass = '';
			$newsb      = self::array_copy($sidebar);

			unset($newsb['widget_map']);
			$newsb['widget_map'][$pos] = $widgets;
			if ($schemaShorts[$sbcount] == $sidebar['start']) $extraClass = " rt-alpha";
			if ($schemaShorts[$sbcount] == $sidebar['end']) $extraClass = " rt-omega";
			if ($schemaShorts[$sbcount] == $sidebar['start'] && $schemaShorts[$sbcount] == $sidebar['end']) $extraClass = " rt-alpha rt-omega";
			$newsb['widget_map'][$pos]['extraClass'] = $extraClass;
			$newsb['widget_map'][$pos]['pushPull']   = $extraClass;

			$wp_registered_sidebars['sidebar-' . $schemaShorts[$sbcount]] = $newsb;

			foreach ($widgets['widgets'] as $name => $widget) {
				$_wp_sidebars_widgets['sidebar-' . $schemaShorts[$sbcount]][] = $name;
			}
			$sbcount++;
		}

		$sidebars = array();

		if ($rtl_enabled) {
			add_filter('sidebars_widgets', array('GantryOrderedMainBodyRenderer', 'invertPositionOrder'));
		}
		add_filter('dynamic_sidebar_params', array('GantryOrderedMainBodyRenderer', 'filterWidget'));


		foreach ($schemaShorts as $sshort) {
			ob_start();
			dynamic_sidebar('sidebar-' . $sshort);
			$sidebars[$sshort] .= ob_get_clean();
		}


		remove_filter('dynamic_sidebar_params', array('GantryOrderedMainBodyRenderer', 'filterWidget'));
		if ($rtl_enabled) {
			remove_filter('sidebars_widgets', array('GantryOrderedMainBodyRenderer', 'invertPositionOrder'));
		}


		if ($gantry->countModules('content-top')) {
			$contentTop = $gantry->displayModules('content-top', $contentTopLayout, $contentTopChrome, $schema['mb']);
		}

		if ($gantry->countModules('content-bottom')) {
			$contentBottom = $gantry->displayModules('content-bottom', $contentBottomLayout, $contentBottomChrome, $schema['mb']);
		}

		$extraClass = '';
		if ('mb' == $start) $extraClass = " rt-alpha";
		if ('mb' == $end) $extraClass = " rt-omega";
		if ('mb' == $start && 'mb' == $end) $extraClass = " rt-alpha rt-omega";

		$output = $gantry->renderLayout('orderedbody_' . $bodyLayout, array(
		                                                                   'schema'            => $schema,
		                                                                   'pushPull'          => '',
		                                                                   'classKey'          => $classKey,
		                                                                   'sidebars'          => $sidebars,
		                                                                   'contentTop'        => $contentTop,
		                                                                   'contentBottom'     => $contentBottom,
		                                                                   'component_content' => $component_content,
		                                                                   'extraClass'        => $extraClass
		                                                              ));
		return $output;


	}

	public static function invertPositionOrder($sidebar_widgets)
	{

		$inverted_sidebar_widgets = array();
		foreach ($sidebar_widgets as $position => $widgets) {
			$new_ordered = array();
			if (count($widgets) > 0) {
				$last_divider = count($widgets);
				for ($i = count($widgets) - 1; $i >= 0; $i--) {
					if (preg_match("/^gantrydivider/", $widgets[$i])) {
						for ($j = $i + 1; $j < $last_divider; $j++) {
							$new_ordered[] = $widgets[$j];
						}
						$last_divider  = $i;
						$new_ordered[] = $widgets[$i];
					} else if ($i == 0) {
						for ($j = 0; $j < $last_divider; $j++) {
							$new_ordered[] = $widgets[$j];
						}
					}
				}
			}
			$inverted_sidebar_widgets[$position] = $new_ordered;
		}
		return $inverted_sidebar_widgets;
	}

	public static function filterWidget($params)
	{
		/** @global $gantry Gantry */
		global $gantry;

		$widget_id = $params[0]['widget_id'];
		$layout    = $params[0]['layout'];

		// find the widget and its position
		foreach ($params[0]['widget_map'] as $pos => $position_info) {
			$position_widgets = $position_info['widgets'];
			if (empty($position_widgets) || !array_key_exists($widget_id, $position_widgets)) continue;
			$keys				   = array_keys($position_widgets);
			$params[0]['position'] = $pos;
			$params[0]['end']      = end($keys);
			$params[0]['start']    = reset($keys);
			break;
		}

		$params = $gantry->renderLayout('widget_' . $layout, $params);
		return $params;
	}

	protected static function countSidebars($widgets)
	{
		/** @global $gantry Gantry */
		global $gantry;
		$MAX_SIDEBARS = 3;
		// TODO  make this pull from templates xml
		$sidebar_count = 0;
		if (count($widgets) > 0) {
			$sidebar_count = 1;
			foreach ($widgets as $widget) {
				if (preg_match("/^gantrydivider/", $widget)) {
					$sidebar_count++;
					if ($sidebar_count > $MAX_SIDEBARS) {
						break;
					}
				}
			}
		}
		return $sidebar_count;
	}

	/**
	 * make a recursive copy of an array
	 *
	 * @param array $aSource
	 *
	 * @return array    copy of source array
	 */
	public static function array_copy($aSource)
	{
		// check if input is really an array
		if (!is_array($aSource)) {
			throw new Exception("Input is not an Array");
		}

		// initialize return array
		$aRetAr = array();

		// get array keys
		$aKeys = array_keys($aSource);
		// get array values
		$aVals = array_values($aSource);

		// loop through array and assign keys+values to new return array
		for ($x = 0; $x < count($aKeys); $x++) {
			// clone if object
			if (is_object($aVals[$x])) {
				$aRetAr[$aKeys[$x]] = clone $aVals[$x];
				// recursively add array
			} elseif (is_array($aVals[$x])) {
				$aRetAr[$aKeys[$x]] = self::array_copy($aVals[$x]);
				// assign just a plain scalar value
			} else {
				$aRetAr[$aKeys[$x]] = $aVals[$x];
			}
		}

		return $aRetAr;
	}

}