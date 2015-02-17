<?php
/**
 * @version   $Id: widgets.php 59361 2013-03-13 23:10:27Z btowles $
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2014 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

defined('GANTRY_VERSION') or die();

/** @global $gantry Gantry */
global $gantry;

$action = $_POST['gantry_action'];

/**
 * Set the sidebar widget option to update sidebars.
 *
 * @since 2.2.0
 * @access private
 *
 * @param array $sidebars_widgets Sidebar widgets and their settings.
 */

gantry_admin_clear_cache();

switch ($action) {
	case 'create-new':
		check_ajax_referer('save-sidebar-widgets', 'savewidgets');

		if (!current_user_can('edit_theme_options')) die('-1');

		unset($_POST['savewidgets'], $_POST['action'], $$_POST['gantry_action']);

		$override_catalog                 = gantry_get_override_catalog($gantry->templateName);
		$next_override                    = (count($override_catalog) > 0) ? max(array_keys($override_catalog)) + 1 : 1;
		$override_name                    = sprintf(_g('Custom Override %d'), $next_override);
		$override_catalog[$next_override] = $override_name;
		gantry_udpate_override_catalog($override_catalog);
		$retrun_url = admin_url('widgets.php?override_id=' . $next_override);
		echo $retrun_url;
		break;
	case 'save-info':
		check_ajax_referer('save-sidebar-widgets', 'savewidgets');
		if (!current_user_can('edit_theme_options')) die('-1');

		unset($_POST['savewidgets'], $_POST['action'], $$_POST['gantry_action']);

		if (!isset($_POST['override_id']) || !isset($_POST['override_name'])) {
			return 'error';
		}

		$override_id   = $_POST['override_id'];
		$override_name = $_POST['override_name'];
		if ($override_id == 0) {
			$new_override_id = (count($override_catalog) > 0) ? max(array_keys($override_catalog)) + 1 : 1;
			$override_id     = $new_override_id;
		}
		$override_catalog               = gantry_get_override_catalog($gantry->templateName);
		$override_catalog[$override_id] = $override_name;
		gantry_udpate_override_catalog($override_catalog);
		echo "success";
		break;

	case 'widgets-order' :
		check_ajax_referer('save-sidebar-widgets', 'savewidgets');

		if (!current_user_can('edit_theme_options')) die('-1');

		unset($_POST['savewidgets'], $_POST['action'], $$_POST['gantry_action']);

		if (!isset($_POST['override_id'])) {
			return "error - no override id";
		}
		$override_id = $_POST['override_id'];

		// save widgets order for all sidebars
		if (is_array($_POST['sidebars'])) {
			$sidebars = array();
			foreach ($_POST['sidebars'] as $key => $val) {
				$sb = array();
				if (!empty($val)) {
					$val = explode(',', $val);
					foreach ($val as $k => $v) {
						if (strpos($v, 'widget-') === false) continue;

						$sb[$k] = substr($v, strpos($v, '_') + 1);
					}
				}
				$sidebars[$key] = $sb;
			}
			gantry_save_override_sidebars_widgets($override_id, $sidebars);
			die('1');
		}
		die('-1');
		break;

	case 'widgets-save' :
		gantry_widgets_save();
		die();
		break;
	case 'widgets-mass-actions':
		$tmp  = array();
		$data = stripcslashes($_POST['data']);
		foreach (json_decode($data) as $d) {
			array_push($tmp, $d);
		}
		foreach ($tmp as $widget_instance) {
			$instance_array = get_object_vars($widget_instance);
			$id_base        = $instance_array['id_base'];
			$widget_number  = $instance_array['widget_number'];
			foreach ($instance_array as $key => $value) {
				if (preg_match('/^widget-' . $id_base . '\[' . $widget_number . '\]/', $key)) {

					$subkey = preg_replace('/^widget-' . $id_base . '/', '', $key);
					preg_match_all('/\[(\S+)\]/U', $subkey, $matches);
					$sub_array_keys = $matches[1];
					$post_eval      = '$_POST[\'widget-' . $id_base . '\']';
					foreach ($sub_array_keys as $subarraykey) {
						$post_eval .= '[\'' . $subarraykey . '\']';
					}
					$post_eval .= ' = $value;';
					eval($post_eval);
				} else {
					$_POST[$key] = $value;
				}
			}
			gantry_widgets_save(true);
		}
		die();
		break;
	default:
		echo "error";
}


function gantry_widgets_save($batch = false)
{
	global $wp_widget_factory, $wp_registered_sidebars, $wp_registered_widgets, $wp_registered_widget_controls, $wp_registered_widget_updates;
	check_ajax_referer('save-sidebar-widgets', 'savewidgets');

	if (!current_user_can('edit_theme_options') || !isset($_POST['id_base'])) die('-1');

	unset($_POST['savewidgets'], $_POST['action']);

	if (!isset($_POST['override_id'])) {
		return "error - no override id";
	}
	$override_id = $_POST['override_id'];


	do_action('load-widgets.php');
	do_action('widgets.php');
	do_action('sidebar_admin_setup');

	$id_base      = $_POST['id_base'];
	$widget_id    = $_POST['widget-id'];
	$sidebar_id   = $_POST['sidebar'];
	$multi_number = !empty($_POST['multi_number']) ? (int)$_POST['multi_number'] : 0;
	$settings     = isset($_POST['widget-' . $id_base]) && is_array($_POST['widget-' . $id_base]) ? $_POST['widget-' . $id_base] : false;
	$error        = '<p>' . __('An error has occured. Please reload the page and try again.') . '</p>';

	$option_name                 = 'widget_' . $id_base;
	$_POST['widget_option_name'] = $option_name;

	$_REQUEST['override_id'] = $override_id;

	// Set sidebars_widgets filter to point to combined for init of widgets
	add_filter('sidebars_widgets', 'gantry_widget_admin_override_sidebars_widgets_filter', -10000);
	// Register the override for the widget type this is for
	//add_filter('pre_option_widget_' . $id_base, 'gantry_widget_admin_load_override_widget_settings_filter', -1000, 1);

	// do action to do base widget init
	do_action('gantry_override_widgets_init');

	$sidebars = wp_get_sidebars_widgets();
	$sidebar  = isset($sidebars[$sidebar_id]) ? $sidebars[$sidebar_id] : array();

	if (empty($sidebar) || !in_array($widget_id, $sidebar)) {
		if (empty($_POST['add_new'])) $sidebar[] = $widget_id;
		$sidebars[$sidebar_id] = $sidebar;
		gantry_save_override_sidebars_widgets($override_id, $sidebars);
	}

	// delete
	if (isset($_POST['delete_widget']) && $_POST['delete_widget']) {

		if (!isset($wp_registered_widgets[$widget_id])) {
			if (!$batch) die($error);
			return;
		}


		$sidebar = array_diff($sidebar, array($widget_id));
		$_POST   = array(
			'sidebar'            => $sidebar_id,
			'widget-' . $id_base => array(),
			'the-widget-id'      => $widget_id,
			'delete_widget'      => '1'
		);
	} elseif ($settings && preg_match('/__i__|%i%/', key($settings))) {
		if (!$multi_number) {
			if (!$batch) die($error);
			return;
		}

		$_POST['widget-' . $id_base] = array($multi_number => array_shift($settings));
		$widget_id                   = $id_base . '-' . $multi_number;
		$sidebar[]                   = $widget_id;
	}
	$_POST['widget-id'] = $sidebar;

	$registered = false;
	foreach ((array)$wp_registered_widget_updates as $name => $control) {
		if ($name == $id_base) {
			if (!is_callable($control['callback'])) continue;
			$_REQUEST['override_id'] = $override_id;
			//add_filter('pre_update_option_' . $option_name, 'gantry_widget_admin_ajax_save_widget_option_intercept', -1000, 2);
			ob_start();
			call_user_func_array($control['callback'], $control['params']);
			ob_end_clean();

			// add_filter('pre_update_option_' . $option_name, 'gantry_widget_admin_ajax_save_widget_option_intercept', -1000, 2);

			break;
		}
	}

	if (isset($_POST['delete_widget']) && $_POST['delete_widget']) {
		$sidebars[$sidebar_id] = $sidebar;
		gantry_save_override_sidebars_widgets($override_id, $sidebars);
		if (!$batch) {
			echo "deleted:$widget_id";
			die();
		}
		return;
	}

	if (!empty($_POST['add_new'])) {
		if (!$batch) die();
		return;
	}

	if ($form = $wp_registered_widget_controls[$widget_id]) call_user_func_array($form['callback'], $form['params']);
}
