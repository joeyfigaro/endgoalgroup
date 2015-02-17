<?php
/**
 * @version   $Id: gantrywidgetsrenderer.class.php 60342 2014-01-03 17:12:22Z jakub $
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2014 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */
defined('GANTRY_VERSION') or die();


/**
 * @package     gantry
 * @subpackage  core.renderers
 */
class GantryWidgetsRenderer
{
	/**
	 * @param        $positionStub
	 * @param string $layout
	 * @param string $chrome
	 * @param string $gridsize
	 * @param null   $pattern
	 *
	 * @return string
	 */
	public static function display($positionStub, $layout = 'standard', $chrome = 'standard', $gridsize = GRID_SYSTEM, $pattern = null)
	{
		/** @global $gantry Gantry */
		global $gantry;
		global $wp_registered_sidebars, $wp_registered_widgets;

		$position_info = $gantry->getPositionInfo($positionStub);
		$max_positions = $position_info->max_positions;

		$output        = '';
		$index         = 0;
		$poscount      = 1;
		$uniqpositions = $gantry->getUniquePositions();

		$showAllParam = $gantry->get($positionStub . '-showall');
		$showMaxParam = $gantry->get($positionStub . '-showmax');

		if (!in_array($positionStub, $uniqpositions)) {
			return "";
		}

		$sidebars_widgets = wp_get_sidebars_widgets();

		// Add extra data to sidebar
		$sidebar             = & $wp_registered_sidebars[$positionStub];
		$sidebar['layout']   = $layout;
		$sidebar['chrome']   = $chrome;
		$sidebar['gridsize'] = $gridsize;
		$sidebar['pattern']  = $pattern;
		$sidebar['showall']  = $showAllParam;
		$sidebar['showmax']  = $showMaxParam;

		$filtered_widgets = GantryWidgetsRenderer::filterWidgetCount($sidebars_widgets);
		$widgets          = $filtered_widgets[$positionStub];

		// Map widgets to positions without the dividers
		$widget_map = array();
		$pos        = 1;
		if (null != $widgets) {
			foreach ($widgets as $widget) {
				if (preg_match("/^gantrydivider/", $widget)) {
					$pos++;
				} else {
					$widget_map[$pos]['widgets'][$widget] = array('name' => $widget);
				}
			}
		}

		$count = count($widget_map);
		if ($showAllParam == 1) $count = $showMaxParam;

		$keys  		 = array_keys($widget_map);
		$end         = end($keys);
		$start       = reset($keys);
		$prefixCount = 0;


		for ($position = 1; $position <= $max_positions; $position++) {

			$widgets_in_position = false;
			if (array_key_exists($position, $widget_map)) {
				$widgets_in_position = true;
			}

			$extraClass = '';
			if ($position == $start) $extraClass = " rt-alpha";
			if ($position == $end) $extraClass = " rt-omega";
			if ($position == $start && $position == $end) $extraClass = " rt-alpha rt-omega";


			if ($showAllParam == 1 && !$widgets_in_position) {
				$prefixCount += $gantry->getPositionSchema($positionStub, $gridsize, $count, $index);
				$index++;
			} else if ($widgets_in_position) {
				// Apply chrome and render module
				$paramSchema                           = $gantry->getPositionSchema($positionStub, $gridsize, $count, $index);
				$widget_map[$position]['extraClass']   = $extraClass;
				$widget_map[$position]['prefixCount']  = $prefixCount;
				$widget_map[$position]['paramsSchema'] = $paramSchema;
				$prefixCount                           = 0; // reset prefix count
				$index++;
			}
		}

		$sidebar['widget_map'] = $widget_map;

		if (get_bloginfo('text_direction') == 'rtl' && $gantry->get('rtl-enabled')) {
			add_filter('sidebars_widgets', array('GantryWidgetsRenderer', 'invertPositionOrder'));
		}
		add_filter('dynamic_sidebar_params', array('GantryWidgetsRenderer', 'filterWidget'));
		ob_start();
		do_action('get_sidebar', $positionStub);
		dynamic_sidebar($positionStub);
		$output = ob_get_clean();
		remove_filter('dynamic_sidebar_params', array('GantryWidgetsRenderer', 'filterWidget'));

		if (get_bloginfo('text_direction') == 'rtl' && $gantry->get('rtl-enabled')) {
			remove_filter('sidebars_widgets', array('GantryWidgetsRenderer', 'invertPositionOrder'));
		}
		return $output;
	}

	/**
	 * @param $sidebar_widgets
	 *
	 * @return array
	 */
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

	/**
	 * @param $params
	 *
	 * @return string
	 */
	public static function filterWidget($params)
	{
		if( !isset( $params[0]['widget_map'] )) return $params;
		
		/** @global $gantry Gantry */
		global $gantry;

		$widget_id = $params[0]['widget_id'];
		$layout    = $params[0]['layout'];

		// find the widget and its position
		foreach ($params[0]['widget_map'] as $pos => $position_info) {
			$position_widgets = $position_info['widgets'];
			if (!array_key_exists($widget_id, $position_widgets)) continue;
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
	 * @param $sidebars_widgets
	 *
	 * @return array
	 */
	public static function filterWidgetCount($sidebars_widgets)
	{
		global $gantry, $wp_registered_widgets;
		$cleaned_sidebar_widgets = array();
		foreach ($sidebars_widgets as $sidebar => $widgets) {
			if ($sidebar == "wp_inactive_widgets" || strpos($sidebar,'orphaned_widgets_') === 0) continue;
			$position_info  = $gantry->getPositionInfo($sidebar);
			$max_positions  = $position_info->max_positions;
			$position_count = 1;
			if (count($widgets) > 0) {
				$showAllParam = $gantry->get($sidebar . '-showall');
				$showMaxParam = $gantry->get($sidebar . '-showmax');
				if ($showAllParam == 1) $max_positions = $showMaxParam;
				$cleaned_sidebar_widgets[$sidebar] = array();
				foreach ($widgets as $widget) {
					if (!array_key_exists($widget, $wp_registered_widgets)) continue;
					if (preg_match("/^gantrydivider/", $widget)) {
						$position_count++;
						if ($position_count > $max_positions) {
							break;
						}
					}
					$cleaned_sidebar_widgets[$sidebar][] = $widget;
				}
			} else {
				$cleaned_sidebar_widgets[$sidebar] = $widgets;
			}
		}

		$aliased_sidebar_widgets = array();
		foreach ($cleaned_sidebar_widgets as $sidebar => $widgets) {
			if (!array_key_exists($sidebar, $gantry->_aliases)) {
				$aliased_sidebar_widgets[$sidebar] = $widgets;
				continue;
			}
			$aliased_sidebar     = $gantry->_aliases[$sidebar];
			$aliased_sub_sidebar = false;

			if (preg_match('/(.*)-(\w?)$/', $aliased_sidebar, $sub_position_match)) {
				$aliased_sidebar     = $sub_position_match[1];
				$aliased_sub_sidebar = $sub_position_match[2];
			}

			$aliased_widgets = $cleaned_sidebar_widgets[$aliased_sidebar];
			if ($aliased_sub_sidebar !== false && !empty($aliased_widgets)) {
				// assign only the sub position widgets to the asliased postion
				$sub_widgets    = array();
				$sub_position   = ord($aliased_sub_sidebar) - 96;
				$position_count = 1;
				foreach ($aliased_widgets as $widget) {
					$is_divider = false;
					if (preg_match("/^gantrydivider/", $widget)) $is_divider = true;

					if (!$is_divider && $sub_position == $position_count) {
						$sub_widgets[] = $widget;
					}
					if ($is_divider) {
						$position_count++;
						if ($position_count > $sub_position) break;
					}
				}
				$aliased_widgets = $sub_widgets;
			} else {
				// assign all widgets for the aliased position
				$aliased_widgets = $cleaned_sidebar_widgets[$aliased_sidebar];
			}
			//assign the widgets from the aliased postiion to the current postion
			$aliased_sidebar_widgets[$sidebar] = $aliased_widgets;
		}
		return $aliased_sidebar_widgets;
	}


}