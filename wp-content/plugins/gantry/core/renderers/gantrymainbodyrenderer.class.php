<?php
/**
 * @version   $Id: gantrymainbodyrenderer.class.php 60373 2014-01-09 20:57:44Z jakub $
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
/**
 *
 */
class GantryMainBodyRenderer
{
	/**
	 * wrapper for mainbody display
	 *
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
		/** @var $gantry Gantry */
		global $gantry;
		global $wp_registered_sidebars, $wp_registered_widgets;

		if ($grid == null) {
			$grid = GRID_SYSTEM;
		}

		// get sidebar count
		$sidebars_widgets = wp_get_sidebars_widgets();
		(isset($sidebars_widgets['sidebar'])) ? $sidebarCount = GantryMainBodyRenderer::countSidebars($sidebars_widgets['sidebar']) : $sidebarCount = 0;
		$columnCount      = $sidebarCount + 1;

		//here we would see if the mainbody schema was set to soemthing else
		$defaultSchema = $gantry->mainbodySchemas[$grid][$columnCount];

		$mbp      = $gantry->get('mainbodyPosition');
		$position = @unserialize($mbp);

		if (!isset($position[$grid]) || !isset($position[$grid]) || !array_key_exists($columnCount, $position[$grid])) $schema = $defaultSchema; else {
			$schema = $position[$grid][$columnCount];
		}

		// If RTL then flip the array
		if (get_bloginfo('text_direction') == 'rtl' && $gantry->get('rtl-enabled')) {
			$schema = $gantry->flipBodyPosition($schema);
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

		(isset($filtered_widgets['sidebar'])) ? $widgets = $filtered_widgets['sidebar'] : $widgets = array();

		// Map widgets to sidebars without the dividers
		$widget_map   = array();
		$pos          = 1;
		$pos_info_set = false;

		if (get_bloginfo('text_direction') == 'rtl' && $gantry->get('rtl-enabled')) {
			$main_body_pp = array_shift($pushPull);
			$pushPull     = array_reverse($pushPull);
			array_unshift($pushPull, $main_body_pp);
		}

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


		$sidebars = "";
		if (get_bloginfo('text_direction') == 'rtl' && $gantry->get('rtl-enabled')) {
			add_filter('sidebars_widgets', array('GantryMainBodyRenderer', 'invertPositionOrder'));
		}
		add_filter('dynamic_sidebar_params', array('GantryMainBodyRenderer', 'filterWidget'));
		ob_start();
		dynamic_sidebar('sidebar');
		$sidebars .= ob_get_clean();
		remove_filter('dynamic_sidebar_params', array('GantryMainBodyRenderer', 'filterWidget'));
		if (get_bloginfo('text_direction') == 'rtl' && $gantry->get('rtl-enabled')) {
			remove_filter('sidebars_widgets', array('GantryMainBodyRenderer', 'invertPositionOrder'));
		}

		if ($gantry->countModules('content-top')) {
			$contentTop = $gantry->displayModules('content-top', $contentTopLayout, $contentTopChrome, $schema['mb']);
		}

		if ($gantry->countModules('content-bottom')) {
			$contentBottom = $gantry->displayModules('content-bottom', $contentBottomLayout, $contentBottomChrome, $schema['mb']);
		}

		$output = $gantry->renderLayout('body_' . $bodyLayout, array(
		                                                            'schema'            => $schema,
		                                                            'pushPull'          => $pushPull,
		                                                            'classKey'          => $classKey,
		                                                            'sidebars'          => $sidebars,
		                                                            'contentTop'        => $contentTop,
		                                                            'contentBottom'     => $contentBottom,
		                                                            'component_content' => $component_content
		                                                       ));
		return $output;


	}

	/**
	 * @param $sidebar_widgets
	 *
	 * @return array
	 */
	public function invertPositionOrder($sidebar_widgets)
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

	/**
	 * @param $params
	 *
	 * @return string
	 */
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

	/**
	 * @param $widgets
	 *
	 * @return int
	 */
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

}