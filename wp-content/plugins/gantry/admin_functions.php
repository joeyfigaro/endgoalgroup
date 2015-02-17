<?php
/**
 * @version   $Id: admin_functions.php 60853 2014-05-14 23:05:36Z jakub $
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2014 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

function gantry_admin_init()
{
	/** @global $gantry Gantry */
	global $gantry;
	$gantry->adminInit();
}


function gantry_can_render_admin()
{
	global $current_screen, $gantry_pages;
	if (empty($gantry_pages)) {
		$gantry_pages = array(
			"widgets",
			"toplevel_page_gantry-theme-settings",
			get_plugin_page_hook("gantry-theme-settings", null)
		);
	}
	return in_array($current_screen->id, $gantry_pages);
}


// Put the header token in the admin header
function gantry_admin_head()
{
	/** @global $gantry Gantry */
	global $gantry;
	if (gantry_can_render_admin()) {
		echo $gantry->displayHead();
	}
}

// Run the finalize for the admin side
function gantry_admin_end()
{
	/** @global $gantry Gantry */
	global $gantry;
	if (gantry_can_render_admin()) {
		$gantry->finalizeAdmin();
	}
}

function gantry_admin_register_theme_settings()
{
	/** @global $gantry Gantry */
	global $gantry;

	//    if (isset($_GET['page']) && $_GET['page'] == $gantry->templateName . '-settings') {
	//        wp_enqueue_script('admin-widgets');
	//        wp_admin_css('widgets');
	//    }
	//register_setting('theme-options-array', $gantry->templateName . '-template-options');
}

function gantry_admin_start_buffer()
{
	// start buffering output
	ob_start();
}

function gantryLang()
{
	global $ajaxurl, $gantry;
	return "";
//		var AdminURI = '" . $ajaxurl . "';
//		var GantryURL = '" . $gantry->gantryUrl . "';
//		var GantryParamsPrefix = '" . $gantry->templateName . "-template_options_';
//        var GantryLang = {
//            'preset_title': '" . _g('Gantry Presets Saver') . "',
//            'preset_select': '" . _g('Select the Presets you want to save and choose a new name for them. Hit "skip" on a Presets section if you do not want to save as new that specific Preset.') . "',
//            'preset_name': '" . _g('Preset Name') . "',
//            'key_name': '" . _g('Key Name') . "',
//            'preset_naming': '" . _g('Preset Naming for') . "',
//            'preset_skip': '" . _g('Skip') . "',
//            'success_save': '" . _g('NEW PRESET SAVED WITH SUCCESS!') . "',
//            'success_msg': '" . _g('<p>The new Presets have been successfully saved and they are ready to be used right now. You will find them from the list of the respective presets.</p><p>Click "Close" button below to close this window.</p>') . "',
//            'fail_save': '" . _g('SAVE FAILED') . "',
//            'fail_msg': '" . _g('<p>It looks like the saving of the new Preset did not succeed. Make sure your theme folder and "custom/presets.ini" at your theme folder root have write permissions.</p><p>Once you think you have fixed the permission, hit the button "Retry" below.</p><p>If it still fails, please ask for support on RocketTheme forums</p>') . "',
//            'cancel': '" . _g('Cancel') . "',
//            'save': '" . _g('Save') . "',
//            'retry': '" . _g('Retry') . "',
//            'close': '" . _g('Close') . "',
//            'show_parameters': '" . _g('Show Involved Params') . "',
//			'are_you_sure': '" . _g('This will delete all widgets and settings for this override.  Are you sure you want to do this?') . "'
//        };
//    ";
}

function gantry_add_old_ui_class( $classes ) {
    if ( version_compare( $GLOBALS['wp_version'], '3.8-alpha', '<' ) ) {
        $classes = explode( " ", $classes );
        if ( ! in_array( 'gantry-pre-mp6', $classes ) ) {
            $classes[] = 'gantry-pre-mp6';
        }
        $classes = implode( " ", $classes );
    } else {
    	$classes = explode( " ", $classes );
        if ( ! in_array( 'gantry-mp6', $classes ) ) {
            $classes[] = 'gantry-mp6';
        }
        $classes = implode( " ", $classes );
    }

    return $classes;
}

function gantry_admin_menu()
{
	/** @global $gantry Gantry */
	global $gantry;
	add_menu_page($gantry->get('template_author', 'RocketTheme') . ' ' . $gantry->get('template_full_name') . ' Theme Settings', $gantry->get('template_full_name') . ' Theme', 'edit_theme_options', 'gantry-theme-settings', 'gantry_show_theme_settings', $gantry->gantryUrl . '/admin/rt_fav.png');
	//add_submenu_page('gantry-theme-settings', $gantry->get('template_author', 'RocketTheme') . ' ' . $gantry->get('template_full_name') . ' Theme Override Settings', '', 'edit_theme_options', 'gantry-theme-overrides', 'gantry_show_theme_override_settings');
	add_action('admin_head', 'gantry_remove_menu_items');
}

function gantry_remove_menu_items()
{
	global $submenu;
	unset($submenu['gantry-theme-settings']);
}

function gantry_show_theme_settings()
{
	/** @global $gantry Gantry */
	global $gantry;
	include($gantry->gantryPath . '/admin/index.php');
}

function gantry_show_theme_override_settings()
{
	/** @global $gantry Gantry */
	global $gantry;
	include($gantry->gantryPath . '/admin/override.php');
}

/**
 * Action to add template detail defined widget styles to all widgets that have options
 *
 * @param  $instance
 * @param  $return
 * @param  $values
 *
 * @return void
 */
function gantry_add_widget_styles_action(&$instance, &$return, $values)
{
	if ($return != "noform") {

		/** @global $gantry Gantry */
		global $gantry;

		$widget_styles = $gantry->getWidgetStyles();

        if( $gantry->get('dropdown_widget_variations') ) {
            if( !empty( $widget_styles ) ) { ?>
                <p class="widget-variations-select">
                    <label for="<?php echo $instance->get_field_id('variations'); ?>"><?php _ge('Widget Variations');?></label>
                    <select data-select-variations id="<?php echo $instance->get_field_id('variations'); ?>" name="<?php echo $instance->get_field_name('variations') ?>[]" class="gantry-multiselect gantry-variations-multiselect" multiple="multiple">
                        <?php foreach ($widget_styles as $style_info) :
                            if (!array_key_exists($style_info['name'], $values)) $values[$style_info['name']] = ''; ?>
                            <optgroup label="<?php _ge($style_info['label']); ?>"><?php _ge($style_info['label']); ?></optgroup>
                            <?php
                            foreach ($style_info['styles'] as $style_name => $style_label) {
                                if (!array_key_exists('variations', $values)) $values['variations'] = array();
                                (in_array($style_name, $values['variations'])) ? $selected = ' selected="selected"' : $selected = ''; ?>
                                <option value="<?php echo $style_name; ?>"<?php echo $selected; ?>><?php _re($style_label); ?></option>
                            <?php
                            }
                        endforeach; ?>
                    </select>
                </p>
        <?php
            }
        } else {
            foreach ($widget_styles as $style_info) :
                if (!array_key_exists($style_info['name'], $values)) $values[$style_info['name']] = '';
                ?>
                <p>
                    <label for="<?php echo $instance->get_field_id($style_info['name']); ?>"><?php _ge($style_info['label']);?></label>
                    <select id="<?php echo $instance->get_field_id($style_info['name']); ?>" name="<?php echo $instance->get_field_name($style_info['name']) ?>">
                        <option value="" <?php if (empty($values[$style_info['name']])): ?> selected="selected"<?php endif;?>>-</option>
                        <?php foreach ($style_info['styles'] as $style_name => $style_label): ?>
                            <option value="<?php echo $style_name; ?>" <?php if ($values[$style_info['name']] == $style_name): ?>selected="selected"<?php endif;?>><?php _re($style_label);?></option>
                        <?php endforeach; ?>
                    </select>
                </p>
                <?php
            endforeach;
        }

		$widget_chromes = $gantry->_template->getWidgetChromes();
		if (!empty($widget_chromes)):
			if (!array_key_exists('widget_chrome', $values)) $values['widget_chrome'] = '';?>
			<p>
				<label for="<?php echo $instance->get_field_id('widget_chrome'); ?>"><?php _ge('Custom Chrome');?></label>
				<select id="<?php echo $instance->get_field_id('widget_chrome'); ?>"
				        name="<?php echo $instance->get_field_name('widget_chrome') ?>">
					<option value="" <?php if (empty($values['widget_chrome'])): ?>
						selected="selected"<?php endif;?>>-
					</option>
					<?php foreach ($widget_chromes as $widget_chrome_value => $widget_chrome_label): ?>
							<option value="<?php echo $widget_chrome_value; ?>" <?php if ($values['widget_chrome'] == $widget_chrome_value): ?>
								selected="selected"<?php endif;?>><?php _re($widget_chrome_label);?></option>
					<?php endforeach;?>
				</select>
			</p>
		<?php endif;

		if ($gantry->get('custom_widget_variations')) : ?>
			<p>
				<label for="<?php echo $instance->get_field_id('custom-variations'); ?>"><?php _ge('Custom Variations'); ?></label>
				<input type="text" id="<?php echo $instance->get_field_id('custom-variations'); ?>" name="<?php echo $instance->get_field_name('custom-variations') ?>" value="<?php if (isset($values['custom-variations'])) echo $values['custom-variations']; ?>" size="25" />
			</p>
		<?php endif;

	}
}

/**
 * Filter to modify the widget instance save to include the template details defined widget styles
 *
 * @param  $instance
 * @param  $new_instance
 * @param  $old_instance
 *
 * @return array modifed widget instance
 */
function gantry_widget_style_udpate_filter($instance, $new_instance, $old_instance)
{
	/** @global $gantry Gantry */
	global $gantry;

	$widget_styles = $gantry->_template->getWidgetStyles();

	foreach ($widget_styles as $style_info) {
		if (array_key_exists($style_info['name'], $new_instance)) {
			$instance[$style_info['name']] = $new_instance[$style_info['name']];
		}
	}

    if (array_key_exists('variations', $new_instance)) {
        $instance['variations'] = iterator_to_array(new RecursiveIteratorIterator(new RecursiveArrayIterator($new_instance['variations'])), FALSE);
    }

	if (array_key_exists('custom-variations', $new_instance)) {
		$instance['custom-variations'] = $new_instance['custom-variations'];
	}

	if (array_key_exists('widget_chrome', $new_instance)) {
		$instance['widget_chrome'] = $new_instance['widget_chrome'];
	}

	return $instance;
}

function gantry_update_options()
{
	/** @global $gantry Gantry */
	global $gantry;
	$ret = new stdClass();
	// enable theme settings for lower level users, but with limitations
	check_admin_referer('gantry-theme-settings');
	$ret = new stdClass();
	if (!current_user_can('switch_themes')) {
		$ret->status  = 'error';
		$ret->message = _g('You are not authorised to perform this operation.');
		echo json_encode($ret);
		die();
	}
	$option = $gantry->templateName . '-template-options';

	// clean the cache
	gantry_admin_clear_cache();

	$option = trim($option);
	$value  = null;
	if (isset($_POST[$option])) $value = $_POST[$option];
	if (!is_array($value)) $value = trim($value);
	$value = stripslashes_deep($value);
	update_option($option, $value);

	$ret->status  = 'success';
	$ret->message = _g('%s have been saved.', _g('Default Settings'));
	echo json_encode($ret);
	die();

}

function gantry_update_override()
{
	/** @global $gantry Gantry */
	global $gantry;
	$ret = new stdClass();

	// clean the cache
	gantry_admin_clear_cache();

	$form_option_name = $gantry->templateName . '-template-options';

	// get the overrides catalog
	$override_id      = $_POST['override_id'];
	$override_name    = $_POST['override_name'];
	$override_catalog = gantry_get_override_catalog($gantry->templateName);

	// if its a new override add the basics to the catalog
	if ($override_id == 0) {
		$new_override_id = (count($override_catalog) > 0) ? max(array_keys($override_catalog)) + 1 : 1;
		$override_id     = $new_override_id;
	}

	$override_catalog[$override_id] = $override_name;
	gantry_udpate_override_catalog($override_catalog);


	// get the base override name
	$override_option_name             = $gantry->templateName . '-template-options-override-' . $override_id;
	$override_assignments_option_name = $gantry->templateName . '-template-options-override-assignments-' . $override_id;


	// save the override data
	$form_option_name = trim($form_option_name);
	$value            = null;
	if (isset($_POST[$form_option_name])) $value = $_POST[$form_option_name];
	if (!is_array($value)) $value = trim($value);
	$value       = stripslashes_deep($value);
	$overriddens = array();
	if (isset($_POST['overridden-' . $form_option_name])) {
		$overriddens = $_POST['overridden-' . $form_option_name];
	}
	$overrides = gantry_array_recursive_get_matching_keys($overriddens, $value);

	// save overide
	update_option($override_option_name, $overrides);

	// Get the assignments for the override
	$assigned_override_items = array();
	if (isset($_POST['assigned_override_items'])) {
		$assigned_override_items = unserialize(stripcslashes($_POST['assigned_override_items']));
	}

	//save the assignments
	update_option($override_assignments_option_name, $assigned_override_items);

	//populate the facts
	$ret->status        = 'success';
	$ret->override_id   = $override_id;
	$ret->override_name = $override_name;
	$ret->message       = _g("%s override has been saved.", $override_name);

	return $ret;

}


function gantry_post_update_override()
{
	global $gantry;
	check_admin_referer('gantry-theme-settings');
	// enable theme settings for lower level users, but with limitations
	if (!current_user_can('switch_themes')) wp_die(_g('You are not authorised to perform this operation.'), $gantry->get('template_full_name', 'Gantry Template') . _g('Settings'));
	$status = gantry_update_override();
	gantry_set_admin_message('gantry-theme-settings', _g("%s override has been saved.", $status->override_name));
	wp_redirect(add_query_arg('override_id', $status->override_id, admin_url('admin.php?page=gantry-theme-settings')));
}

function gantry_ajax_update_override()
{

	/** @global $gantry Gantry */
	global $gantry;
	check_admin_referer('gantry-theme-settings');
	$ret = new stdClass();
	if (!current_user_can('switch_themes')) {
		$ret->status  = 'error';
		$ret->message = _g('You are not authorised to perform this operation.');
		echo json_encode($ret);
		die();
	}
	$status       = gantry_update_override();
	$ret->status  = 'success';
	$ret->message = $status->message;
	echo json_encode($ret);
	die();
}

function gantry_override_save_as_copy()
{
	/** @global $gantry Gantry */
	global $gantry;
	check_admin_referer('gantry-theme-settings');
	// enable theme settings for lower level users, but with limitations
	if (!current_user_can('switch_themes')) wp_die(_g('You are not authorised to perform this operation.', $gantry->get('template_full_name', 'Gantry Template') . _g('Settings')));

	// clean the cache
	gantry_admin_clear_cache();

	$form_option_name = $gantry->templateName . '-template-options';

	// get the overrides catalog
	$override_id      = 0;
	$override_name    = _g('SAVE_AS_COPY_SUFFIX', $_POST['override_name']);
	$override_catalog = gantry_get_override_catalog($gantry->templateName);

	// if its a new override add the basics to the catalog
	if ($override_id == 0) {
		$new_override_id = (count($override_catalog) > 0) ? max(array_keys($override_catalog)) + 1 : 1;
		$override_id     = $new_override_id;
	}

	$override_catalog[$override_id] = $override_name;
	gantry_udpate_override_catalog($override_catalog);


	// get the base override name
	$override_option_name             = $gantry->templateName . '-template-options-override-' . $override_id;
	$override_assignments_option_name = $gantry->templateName . '-template-options-override-assignments-' . $override_id;


	// save the override data
	$form_option_name = trim($form_option_name);
	$value            = null;
	if (isset($_POST[$form_option_name])) $value = $_POST[$form_option_name];
	if (!is_array($value)) $value = trim($value);
	$value       = stripslashes_deep($value);
	$overriddens = array();
	if (isset($_POST['overridden-' . $form_option_name])) {
		$overriddens = $_POST['overridden-' . $form_option_name];
	}
	$overrides = gantry_array_recursive_get_matching_keys($overriddens, $value);

	// save overide
	update_option($override_option_name, $overrides);

	// Get the assignments for the override
	$assigned_override_items = array();
	if (isset($_POST['assigned_override_items'])) {
		$assigned_override_items = unserialize(stripcslashes($_POST['assigned_override_items']));
	}

	//save the assignments
	update_option($override_assignments_option_name, $assigned_override_items);

	//populate the facts

	gantry_set_admin_message('gantry-theme-settings', sprintf(_g("%s override has been saved."), $override_name));
	wp_redirect(add_query_arg('override_id', $override_id, admin_url('admin.php?page=gantry-theme-settings')));

}

function gantry_delete_override()
{
	/** @global $gantry Gantry */
	global $gantry;
	// enable theme settings for lower level users, but with limitations
	if (!current_user_can('switch_themes')) wp_die(_g('You are not authorised to perform this operation.', $gantry->get('template_full_name', 'Gantry Template') . _g('Settings')));

	// clean the cache
	gantry_admin_clear_cache();

	$form_option_name = $gantry->templateName . '-template-options';

	if (!array_key_exists('override_id', $_GET)) {
		wp_redirect(admin_url('admin.php?page=gantry-theme-settings'));
		return;
	}


	// get the overrides catalog
	$override_id      = $_GET['override_id'];
	$override_catalog = gantry_get_override_catalog($gantry->templateName);

	if (!array_key_exists($override_id, $override_catalog)) {
		gantry_set_admin_message('gantry-theme-settings', _g("Unable to find override to delete!"));
		wp_redirect(admin_url('admin.php?page=gantry-theme-settings'));
		return;
	}

	$override_name = $override_catalog[$override_id];

	unset($override_catalog[$override_id]);
	gantry_udpate_override_catalog($override_catalog);

	// get the base override name
	$override_option_name = $gantry->templateName . '-template-options-override-' . $override_id;
	delete_option($override_option_name);
	$override_option_name = $gantry->templateName . '-template-options-override-assignments-' . $override_id;
	delete_option($override_option_name);
	$override_option_name = $gantry->templateName . '-template-options-override-sidebar-' . $override_id;
	delete_option($override_option_name);
	$override_option_name = $gantry->templateName . '-template-options-override-widgets-' . $override_id;
	delete_option($override_option_name);

	gantry_set_admin_message('gantry-theme-settings', sprintf(_g("%s has been removed."), $override_name));
	$redirect_url = admin_url('admin.php?page=gantry-theme-settings');
	if (isset($_GET['from']) && $_GET['from'] == 'widgets') {
		$redirect_url = admin_url('widgets.php');
	}
	wp_redirect($redirect_url);
}

function gantry_reset_theme_settings($option)
{
	if (isset($_POST['reset'])) delete_option($option);
}

function gantry_show_updated_theme_message()
{
	?>
	<?php if (isset($_GET['updated'])): ?>
	<div class="updated fade below-h2">
		<p><?php printf(_g('Settings saved.'), '<a href="' . user_trailingslashit(get_bloginfo('url')) . '">' . _g('View site') . '</a>'); ?></p>
	</div>
<?php endif; ?>
<?php

}


function add_meta_button($id, $text, $url, $link, $options = null)
{
	/** @global $gantry Gantry */
	global $gantry;
	$ds = '/';
	include_once(gantry_clean_path(dirname(__FILE__)) . '/admin/screen-meta-links.php');
	add_screen_meta_link($id, $text, $url, $link, $options);
}

function gantry_add_meta_buttons()
{
	add_meta_button('meta-preset-link', 'Presets', '#contextual-preset', array(
	                                                                          get_plugin_page_hook('gantry-theme-settings', ''),
	                                                                          get_plugin_page_hook('gantry-theme-overrides', 'gantry-theme-settings')
	                                                                     ));
	$options = get_option(get_template() . "-template-options");
	add_meta_button('cache-clear', 'Clear Cache', '?clear-cache', array(
	                                                                   get_plugin_page_hook('gantry-theme-settings', ''),
	                                                                   get_plugin_page_hook('gantry-theme-overrides', 'gantry-theme-settings')
	                                                              ), array('class' => 'clear-cache'));
}

function gantry_array_recursive_get_matching_keys($keyArray, $valueArray)
{
	$aReturn = array();

	foreach ($keyArray as $mKey => $mValue) {
		if (array_key_exists($mKey, $valueArray)) {
			if (is_array($mValue)) {
				$aRecursiveDiff = gantry_array_recursive_get_matching_keys($mValue, $valueArray[$mKey]);
				if (count($aRecursiveDiff)) {
					$aReturn[$mKey] = $aRecursiveDiff;
				}
			} else {
				$aReturn[$mKey] = $valueArray[$mKey];
			}
		}
	}
	return $aReturn;
}

function gantry_set_admin_message($page, $message, $timeout = 5)
{
	if (empty($message)) return;
	$tansitent_id = md5($page . '-message-' . $_COOKIE['PHPSESSID']);
	set_transient($tansitent_id, $message, $timeout);
}

function gantry_get_admin_message($page)
{
	$ret          = null;
	$tansitent_id = md5($page . '-message-' . $_COOKIE['PHPSESSID']);
	$ret          = get_transient($tansitent_id);
	if ($ret != false) delete_transient($tansitent_id);
	return $ret;
}


/*****  widgets admin page *********/
/**
 * add filter to esc_html to add checkbox code to widget sidebar title for overrides
 * @return void
 */
function gantry_widgets_admin_add_filter_for_sidebar_title()
{
	add_filter('esc_html', 'gantry_admin_dynamic_sidebar', 10, 2);
}

/**
 * esc_html filter to change the checkbox tag for the widgets titles for overrides
 *
 * @param  $safe_text
 * @param  $text
 *
 * @return mixed
 */
function gantry_admin_dynamic_sidebar($safe_text, $text)
{
	/** @global $gantry Gantry */
	global $gantry;
	$checked = '';
	if (preg_match("/#override_checkbox#(.*)#/", $safe_text, $matches)) {
		$overridden_sidebar       = $matches[1];
		$override_sidebar_widgets = get_option($gantry->templateName . '-template-options-override-sidebar-' . $_REQUEST['override_id']);
		if ($override_sidebar_widgets !== false && array_key_exists($overridden_sidebar, $override_sidebar_widgets)) {
			$checked = ' checked="checked"';
		}
	}
	return preg_replace("/#override_checkbox#(.*)#/", '<input type="checkbox" class="override-checkbox" id="override-$1"' . $checked . '/>', $safe_text);
}

/**
 * Action to change sidebar title in widgets page to add the checkbox code
 *
 * @param  $sidebar
 *
 * @return void
 */
function gantry_widgets_admin_add_checkbox_to_sidebars($sidebar)
{
	global $wp_registered_sidebars;

	$accessibility = (isset($_REQUEST['widgets-access']) && $_REQUEST['widgets-access'] == 'on') && isset($_REQUEST['editwidget']);
	if (!$accessibility) {
		$sidebar['name']                        = '#override_checkbox#' . $sidebar['id'] . '# ' . $sidebar['name'];
		$wp_registered_sidebars[$sidebar['id']] = $sidebar;
	}
}

/**
 * Filter to change the widgets title on an override
 *
 * @param  $translation
 * @param  $text
 * @param  $domain
 *
 * @return string|void
 */
function gantry_widgets_admin_change_title($translation, $text, $domain)
{
	if ($text == 'Widgets') {
		$translation = _g('Widgets Override');
		remove_filter('gettext', 'gantry_widgets_admin_change_title', 1000, 3);
	}
	return $translation;
}

/**
 * Add the filter to change the widgets page title
 * @return void
 */
function gantry_widgets_admin_add_page_title_filter()
{
	add_filter('gettext', 'gantry_widgets_admin_change_title', 1000, 3);
}

function gantry_widgets_admin_insert_override_header()
{
	global $current_screen, $gantry, $ajaxurl;

	if ( version_compare( $GLOBALS['wp_version'], '3.8-alpha', '<' ) ) {
		$spinner = 'wpspin_light.gif';
	} else {
		$spinner = 'spinner.gif';
	}

	if ($current_screen->id == 'widgets') {
		$isDefault        = !(isset($_GET['override_id']));
		$override_id      = 0;
		$override_catalog = gantry_get_override_catalog($gantry->templateName);
		if (isset($_GET['override_id'])) {
			$override_id   = urldecode($_GET['override_id']);
			$override_name = $override_catalog[$override_id];
		}
		if ((int)$override_id == 0) {
			$next_override = (count($override_catalog) > 0) ? max(array_keys($override_catalog)) + 1 : 1;
			$override_name = sprintf(_g('Custom Override %d'), $next_override);
		}

        $widget_styles = $gantry->getWidgetStyles();

        if( !empty( $widget_styles ) && $gantry->get( 'dropdown_widget_variations' ) ) {
            $gantry->addStyle($gantry->gantryUrl . '/admin/widgets/bootstrap-multiselect.css');
            $gantry->addScript($gantry->gantryUrl . '/admin/widgets/bootstrap-2.3.2.min.js');
            $gantry->addScript($gantry->gantryUrl . '/admin/widgets/bootstrap-multiselect.js');
        }

        $gantry->addStyle($gantry->gantryUrl . '/admin/widgets/gantry-widgets.css');
		$gantry->addScript($gantry->gantryUrl . '/admin/widgets/gantry-widgets.js');
		$gantry->addInlineScript("var AdminURI = '" . $ajaxurl . "';var GantryLang = {'are_you_sure': '" . _g('This will delete all widgets and settings for this override.  Are you sure you want to do this?') . "'};");

		$buffer = "";
		$buffer .= "<div id=\"gantry-overrides\">\n";
		$buffer .= "	<div class=\"overrides-inner\">\n";

		$overridesList = array();

		if (!$isDefault) $overridesList[] = '<div class="overrides-action"><a class="defaults" href="' . admin_url('widgets.php') . '">' . _g('Default Settings') . '</a></div>';
		foreach ($override_catalog as $o_id => $o_name) {
			$overridesList[] = '<div class="overrides-action"><a href="' . str_replace("&", "&amp;", add_query_arg(array('override_id' => $o_id))) . '">' . $o_name . '</a></div>';
		}

		$toggleStatus = (!count($overridesList)) ? ' class="hidden"' : '';

		$buffer .= "		<div id=\"overrides-actions\">\n
    							<div id=\"overrides-first\">\n
    								<a href=\"#\">" . ($isDefault ? "Default Settings" : $override_name) . "</a>\n
    							</div>\n
        						<div id=\"overrides-toggle\"" . $toggleStatus . "><br /></div>\n
   								<div id=\"overrides-inside\" class=\"slideup\">\n";
		$buffer .= implode("\n", $overridesList);
		$buffer .= "			</div>\n";
		$buffer .= "		</div>\n";
		$buffer .= "		<input type=\"hidden\" id=\"override_id\" value=\"" . ($isDefault ? "-1" : $override_id) . "\" />\n";
		$buffer .= "		<input type=\"hidden\" id=\"override_name\" value=\"" . $override_name . "\" />\n";
		$buffer .= "		<div id=\"overrides-toolbar\"" . (($isDefault) ? " class=\"defaults-wrap\"" : "") . ">\n";
		if ($isDefault) {
			$buffer .= "			<a class=\"text-button button-add\" href=\"" . admin_url('widgets.php?override_id=0') . "\"><span>New Override</span></a>\n";
		} else {
			$buffer .= "			<a class=\"overrides-button button-add\" href=\"" . admin_url('widgets.php?override_id=0') . "\"><span>Add</span></a>\n";
			$buffer .= "			<a class=\"overrides-button button-del\"
               href=\"" . admin_url('admin-post.php?action=gantry_theme_delete_override&amp;from=widgets&amp;override_id=' . $override_id) . "\"><span>Delete</span></a>\n
            						<div class=\"overrides-button button-edit\"></div>\n
									<img width=\"16\" height=\"16\" src=\"images/$spinner\" style=\"display: none;\" class=\"ajax-loading\">\n";
		}
		$buffer .= "		</div>\n";
		$buffer .= "		<div id=\"overrides-switch\">\n";
		if ((!$isDefault)) {
			$buffer .= '    		<a class="text-button button-widget" href="' . admin_url('admin.php?page=gantry-theme-settings&amp;override_id=' . $override_id) . '"><span>Gantry Settings</span></a>' . "\n";
		} else {
			$buffer .= '    		<a class="text-button button-widget" href="' . admin_url('admin.php?page=gantry-theme-settings') . '"><span>Gantry Settings</span></a>' . "\n";
		}
		$buffer .= "		</div>\n";
		$buffer .= "	</div>\n";
		$buffer .= "</div>\n";


		if ($message = gantry_get_admin_message('gantry-theme-settings')) {
			$buffer .= "<div class=\"updated gantry-notice\">\n";
			$buffer .= "<p>" . $message . "</p>\n";
			$buffer .= "<div class=\"close\"><span>x</span></div>\n";
			$buffer .= "</div>";
		}

		echo $buffer;

		/*  echo "Theme config overrides HTML goes here";
				if (isset($_GET['override_id'])) echo " Override id is " . $_GET['override_id'];
				$gantry->addScript('iscroll.js');
		*/


	}
}

function gantry_widgets_admin_force_accessibility_off()
{
	global $wp_filter;
	if (isset($wp_filter['admin_body_class'])) {
		$filters = $wp_filter['admin_body_class'];
		if (is_array($filters)) {
			foreach ($filters as $priority_filters) {
				foreach ($priority_filters as $filter) {
					if (is_string($filter['function']) && ($filter['function'] == 'wp_widgets_access_body_class' || preg_match('/lambda_/u', $filter['function']))) {
						remove_filter('admin_body_class', $filter['function']);
						set_user_setting('widgets_access', 'off');
						add_action('admin_notices', create_function(null, 'echo "<div class=\'error\'><p>"._g("Gantry themes currently do not support Widget Accessability Mode")."</p></div>";'));
						break(2);
					}
				}
			}
		}
	}
	wp_enqueue_script('admin-widgets');
}

function gantry_filter_get_user_option_widgets_access($widgets_access)
{
	$widgets_access = 'off';
	return 'off';
}

/************************ Combined sidebar and widget settings *********************/
/**
 * Filter for siderbar_widgets to return the combined default and override sidebar widgets and set the filters
 * to get the combined widget settings.
 *
 * @param  $sidebars_widgets
 *
 * @return array
 */
function gantry_widget_admin_combined_sidebars_widgets_filter($sidebars_widgets)
{
	if (isset($_REQUEST['override_id'])) {
		$override_id             = $_REQUEST['override_id'];
		$default_sidebar_widgets = $sidebars_widgets;
		$sidebars_widgets        = gantry_widget_admin_combined_sidebars_widgets_intercept($override_id, $default_sidebar_widgets);
		gantry_widget_admin_register_combined_widget_settings($sidebars_widgets);

	}
	return $sidebars_widgets;
}

/**
 *  - Combines the override and default sidebar_widgets
 *
 * @param  $sidebar_widgets
 *
 * @return array - the combined override and default list of widgets
 */
function gantry_widget_admin_combined_sidebars_widgets_intercept($override_id, $sidebar_widgets)
{
	/** @global $gantry Gantry */
	global $gantry;
	$default_sidebar_widgets  = $sidebar_widgets;
	$override_sidebar_widgets = get_option($gantry->templateName . '-template-options-override-sidebar-' . $override_id);
	if ($override_sidebar_widgets !== false) {
		foreach ($default_sidebar_widgets as $sidebar => $default_widgets) {
			if (array_key_exists($sidebar, $override_sidebar_widgets)) {
				$sidebar_widgets[$sidebar] = $override_sidebar_widgets[$sidebar];
			}
		}
	}
	return $sidebar_widgets;
}

/**
 * Register the filters for the option_widget_x to get the combined set of widget settings for a widget type
 *
 * @param  $sidebars_widgets
 *
 * @return void
 */
function gantry_widget_admin_register_combined_widget_settings($sidebars_widgets)
{
	$widget_base_types = array();
	foreach ($sidebars_widgets as $sidebar) {
		if(is_null($sidebar)) continue;
		foreach ($sidebar as $widget_instance) {
			$widget_base_types[] = substr($widget_instance, 0, strrpos($widget_instance, '-'));
		}
	}
	$widget_base_types = array_unique($widget_base_types);
	foreach ($widget_base_types as $widget_type) {
		add_filter('option_widget_' . $widget_type, 'gantry_widget_admin_load_combined_widget_settings_filter', -1000, 1);
	}
}

/**
 * Filter for option_x - Grabs the merged default and override widget settings
 *
 * @param  $widget_instance
 *
 * @return array
 */
function gantry_widget_admin_load_combined_widget_settings_filter($widget_instance)
{
	/** @global $gantry Gantry */
	global $gantry;
	$override                 = $_REQUEST['override_id'];
	$current_widget_type      = str_replace('option_', '', current_filter());
	$override_widget_settings = get_option($gantry->templateName . '-template-options-override-widgets-' . $override);
	if ($override_widget_settings !== false) {
		if (array_key_exists($current_widget_type, $override_widget_settings)) {
			$widget_instance = $override_widget_settings[$current_widget_type] + $widget_instance;
		}
	}
	return $widget_instance;
}


/************************ Override sidebar and widget settings *********************/
/**
 * Filter for siderbar_widgets to return the combined default and override sidebar widgets and set the filters
 * to get the combined widget settings.
 *
 * @param  $sidebars_widgets
 *
 * @return array
 */
function gantry_widget_admin_override_sidebars_widgets_filter($sidebars_widgets)
{
	if (isset($_REQUEST['override_id'])) {
		$override_id      = $_REQUEST['override_id'];
		$sidebars_widgets = gantry_widget_admin_get_override_sidebars_widget_intercept($override_id, $sidebars_widgets);
		gantry_widget_admin_register_override_widget_settings($sidebars_widgets);
	}
	return $sidebars_widgets;
}

/**
 * Filter for siderbar_widgets - Grabs only the override sidebar info
 *
 * @param  $sidebar_widgets
 *
 * @return array - the combined override and default list of widgets
 */
function gantry_widget_admin_get_override_sidebars_widget_intercept($override_id, $sidebar_widgets)
{
	/** @global $gantry Gantry */
	global $gantry;
	$option          = $gantry->templateName . '-template-options-override-sidebar-' . $override_id;
	$sidebar_widgets = get_option($option);
	if ($sidebar_widgets === false) $sidebar_widgets = array();
	return $sidebar_widgets;
}

/**
 * Register the filters for the pre_option_widget_x to get only the override set of widget settings for a widget type
 *
 * @param  $sidebars_widgets
 *
 * @return void
 */
function gantry_widget_admin_register_override_widget_settings($sidebars_widgets)
{
	$widget_base_types = array();
	foreach ($sidebars_widgets as $sidebar) {
		foreach ($sidebar as $widget_instance) {
			$widget_base_types[] = substr($widget_instance, 0, strrpos($widget_instance, '-'));
		}
	}
	$widget_base_types = array_unique($widget_base_types);
	foreach ($widget_base_types as $widget_type) {
		add_filter('pre_option_widget_' . $widget_type, 'gantry_widget_admin_load_override_widget_settings_filter', -1000, 1);
	}
}

/**
 * Filter for pre_option_widget_x - Grabs only the override widget settings
 *
 * @param  $widget_instance
 *
 * @return array
 */
function gantry_widget_admin_load_override_widget_settings_filter($widget_instance)
{
	/** @global $gantry Gantry */
	global $gantry;
	$override                 = $_REQUEST['override_id'];
	$current_widget_type      = str_replace('pre_option_', '', current_filter());
	$override_widget_settings = get_option($gantry->templateName . '-template-options-override-widgets-' . $override);
	$widget_instance          = array();
	if ($override_widget_settings !== false) {
		if (array_key_exists($current_widget_type, $override_widget_settings)) {
			$widget_instance = $override_widget_settings[$current_widget_type];
		}
	}
	return $widget_instance;
}


/**
 * Init for render of an override widget admin page
 * @return void
 */
function gantry_widgets_admin_page_init()
{
	global $pagenow;
	if ($pagenow == "widgets.php") {
		add_action("admin_notices", 'gantry_widgets_admin_insert_override_header', 100);
		if (isset($_REQUEST['override_id'])) {
			add_action('widgets_admin_page', 'gantry_widgets_admin_add_filter_for_sidebar_title');
			add_action('sidebar_admin_setup', 'gantry_widgets_admin_add_page_title_filter');
			add_action('register_sidebar', 'gantry_widgets_admin_add_checkbox_to_sidebars');
			add_filter('sidebars_widgets', 'gantry_widget_admin_combined_sidebars_widgets_filter', -10000);
		}
	}
}

function gantry_widget_admin_clear_widget_instance_overrides()
{
	global $pagenow, $wp_registered_widget_updates;
	if ($pagenow == "admin-ajax.php" && isset($_REQUEST['action']) && $_REQUEST['action'] == 'save-widget' && !isset($_REQUEST['override_id'])) {
		$widget_names = array_keys($wp_registered_widget_updates);
		foreach ($widget_names as $widget) {
			remove_filter('option_widget_' . $widget, 'gantry_setup_override_widget_instances_intercept', -1000);
		}
	}
}


/**
 * Relocate the wp_widgets_init action hit to fire after we have loaded the actions for laoding sidebar_widgets and widet settings
 * @return void
 */
function gantry_widgets_admin_change_widget_init_action()
{
	if (defined('DOING_AJAX') && DOING_AJAX == true && isset($_POST['override_id'])) {
		remove_action('init', 'wp_widgets_init', 1);
		add_action('gantry_override_widgets_init', 'wp_widgets_init', 1);
		add_action('widgets_init', 'gantry_widget_admin_setup_override_widget_options_filters', 99);
	}
}

function gantry_widget_admin_ajax_save_widget_option_intercept($newvalue, $oldvalue)
{
	/** @global $gantry Gantry */
	global $gantry;

	$options_name = str_replace('pre_update_option_', '', current_filter());

	$override_id = $_REQUEST['override_id'];

	$override_option          = $gantry->templateName . '-template-options-override-widgets-' . $override_id;
	$override_widget_settings = get_option($override_option);
	if ($override_widget_settings == false) $override_widget_settings = array();
	$override_widget_settings[$options_name] = $newvalue;
	update_option($override_option, $override_widget_settings);

	// return $oldvalue to short circuit the default update
	return $oldvalue;
}

function gantry_save_override_sidebars_widgets($override_id, $sidebars_widgets)
{
	/** @global $gantry Gantry */
	global $gantry;
	if (!isset($sidebars_widgets['array_version'])) $sidebars_widgets['array_version'] = 3;

	$overridden_sidebar_widgets = array();
	$tmp_overridden_sidebars    = explode(',', $_POST['overridden_sidebars']);
	$tmp_overridden_sidebars[]  = 'wp_inactive_widgets';
	foreach ($tmp_overridden_sidebars as $overridden_sidebar) {
		$overridden_sidebar = str_replace('override-', '', $overridden_sidebar);
		if (array_key_exists($overridden_sidebar, $sidebars_widgets)) {
			$overridden_sidebar_widgets[$overridden_sidebar] = $sidebars_widgets[$overridden_sidebar];
		}
		//        else {
		//            $overridden_sidebar_widgets[$overridden_sidebar] = array();
		//        }
	}
	$option = $gantry->templateName . '-template-options-override-sidebar-' . $override_id;
	update_option($option, $overridden_sidebar_widgets);
}


function gantry_widget_admin_setup_override_widget_options_filters()
{
	global $wp_widget_factory;
	foreach ($wp_widget_factory->widgets as $widget) {
		add_filter('pre_option_' . $widget->option_name, 'gantry_widget_admin_load_override_widget_settings_filter', -1000, 1);
		add_filter('pre_update_option_' . $widget->option_name, 'gantry_widget_admin_ajax_save_widget_option_intercept', -1000, 2);
	}
}

function gantry_admin_clear_cache()
{
	gantry_import('core.utilities.gantrycache');
	$cache = GantryCache::getCache(GantryCache::GROUP_NAME);
	$cache->clearGroupCache();
	$adminCache = GantryCache::getCache(GantryCache::ADMIN_GROUP_NAME);
	$adminCache->clearGroupCache();
}


function gantry_theme_switched($name, $old_theme)
{
	/** @global $gantry Gantry */
	global $gantry;
	$gantry->templatePath = get_template_directory();
	$gantry->templateName = $gantry->getCurrentTemplate();
	$gantry->_template = new GantryTemplate();
	$gantry->_template->init($gantry);
	$gantry->templateInfo          = $gantry->_template->getTemplateInfo();
	$gantry->_base_params_checksum = $gantry->_template->getParamsHash();
	// Put a base copy of the saved params in the working params
	$gantry->_working_params = $gantry->_template->getParams();
	$gantry->_param_names    = array_keys($gantry->_template->getParams());
	$gantry->template_prefix = $gantry->_working_params['template_prefix']['value'];
	gantry_admin_clear_cache();
}
