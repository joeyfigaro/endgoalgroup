<?php
/**
 * @version   $Id: admin_functions.php 60288 2013-12-10 13:10:52Z jakub $
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2014 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

//function gantry_admin_render_edit_item($element)
//{
//	$buffer = '';
//	$buffer .= "				<div class=\"gantry-field " . $element->type . "-field\">\n";
//	$label = '';
//	if ($element->show_label) $label = $element->getLabel() . "\n";
//	$buffer .= $label;
//	$buffer .= $element->getInput() . "\n";
//	$buffer .= "					<div class=\"clr\"></div>\n";
//	$buffer .= "				</div>\n";
//	return $buffer;
//}

//function  gantry_admin_render_edit_override_item($element)
//{
//	$buffer = '';
//	$buffer .= "				<div class=\"gantry-field " . $element->type . "-field\">\n";
//	$label   = '';
//	$checked = ($element->variance) ? ' checked="checked"' : '';
//	if ($element->show_label) {
//		if (!$element->setinoverride) {
//			$label = $element->getLabel() . "\n";
//		} else {
//			$label = '<div class="field-label"><span class="inherit-checkbox"><input  name="overridden-' . $element->name . '" type="checkbox"' . $checked . '/></span><span class="base-label">' . $element->getLabel() . '</span></div>';
//		}
//	}
//	$buffer .= $label;
//	$buffer .= $element->getInput() . "\n";
//	$buffer .= "					<div class=\"clr\"></div>\n";
//	$buffer .= "				</div>\n";
//	return $buffer;
//}

function gantry_admin_prep_needed_dirs()
{
	/**
	 * @global $gantry Gantry
	 */
	global $gantry;

	//create dirs needed by gantry
	$gantry_created_dirs = array(
		$gantry->custom_dir,
		$gantry->custom_menuitemparams_dir
	);

	foreach ($gantry_created_dirs as $dir) {
		if (is_readable(dirname($dir)) && is_writeable(dirname($dir)) && !file_exists($dir)) {
			mkdir($dir, 0775);
		}
	}
}

function gantry_overrides_innertab_wrappers_pre($field)
{
	$checked = ($field->variance) ? ' checked="checked"' : '';

	if (!$field->setinoverride) return ""; else return '<div class="field-label"><span class="inherit-checkbox"><input name="overridden-' . $field->name . '" type="checkbox"' . $checked . '/></span><span class="base-label">';
}

function gantry_overrides_innertab_wrappers_post($field)
{
	if (!$field->setinoverride) return ""; else return '</span></div>';
}

function get_badges_layout($involved)
{
	return '
	<span class="badges-involved">' . "\n" . '
		<span class="presets-involved"> <span>0</span></span> ' . "\n" . '
		<span class="overrides-involved"> <span>' . $involved . '</span></span>' . "\n" . '

	</span>' . "\n";
}

function gantry_admin_override_css()
{
	/**
	 * @global $gantry Gantry
	 */
	global $gantry;

	// css overrides
	if ($gantry->browser->name == 'ie' && file_exists($gantry->gantryPath . DS . 'admin' . DS . 'widgets' . DS . 'gantry-ie.css')) {
		$gantry->addStyle($gantry->gantryUrl . '/admin/widgets/gantry-ie.css');
	}
	if ($gantry->browser->name == 'ie' && $gantry->browser->version == '7' && file_exists($gantry->gantryPath . DS . 'admin' . DS . 'widgets' . DS . 'gantry-ie7.css')) {
		$gantry->addStyle($gantry->gantryUrl . '/admin/widgets/gantry-ie7.css');
	}

	if (($gantry->browser->name == 'firefox' && $gantry->browser->version < '3.7') || ($gantry->browser->name == 'ie' && $gantry->browser->version > '6')) {
		$css = ".text-short, .text-medium, .text-long, .text-color {padding-top: 4px;height:19px;}";
		$gantry->addInlineStyle($css);
	}

	if ($gantry->browser->name == 'ie' && $gantry->browser->shortversion == '7') {
		$css = "
	        .g-surround, .g-inner, .g-surround > div {zoom: 1;position: relative;}
	        .text-short, .text-medium, .text-long, .text-color {border:0 !important;}
	        .selectbox {z-index:500;position:relative;}
	        .group-fusionmenu, .group-splitmenu {position:relative;margin-top:0 !important;zoom:1;}
	        .scroller .inner {position:relative;}
	        .moor-hexLabel {display:inline-block;zoom:1;float:left;}
	        .moor-hexLabel input {float:left;}
	    ";
		$gantry->addInlineStyle($css);
	}
	if ($gantry->browser->name == 'opera' && file_exists($gantry->gantryPath . DS . 'admin' . DS . 'widgets' . DS . 'gantry-opera.css')) {
		$gantry->addStyle($gantry->gantryUrl . '/admin/widgets/gantry-opera.css');
	}
}


function gantry_admin_compile_less()
{
	/** @var $gantry Gantry */
	global $gantry;
	$less_path = $gantry->gantryPath.'/admin/assets/less';
	if (is_dir($less_path)) {
		$gantry->addLess($less_path . '/global.less', $gantry->gantryUrl . '/admin/widgets/gantry-administrator.css');
		if ($gantry->browser->name == 'ie') {
			$gantry->addLess($less_path . '/fixes-ie.less', $gantry->gantryUrl . '/admin/widgets/fixes-ie.css');
		}
	} else {
		$gantry->addStyle($gantry->gantryUrl . '/admin/widgets/gantry-administrator.css');
		if ($gantry->browser->name == 'ie') {
			$gantry->addStyle($gantry->gantryUrl . '/admin/widgets/fixes-ie.css');
		}
	}
}

function gantry_admin_get_badges_layout($name, $override = 0, $involved = 0, $assignments = 0)
{
	if ($name == 'assignment') {
		return '<span class="menuitems-involved"><span>' . $assignments . '</span></span>';
	} else {
		if ($override) {
			return '
				<span class="badges-involved">' . "\n" . '
				<span class="presets-involved"> <span>0</span></span> ' . "\n" . '
				<span class="overrides-involved"> <span>' . $involved . '</span></span>' . "\n" . '
			</span>';
		} else {
			return '<span class="presets-involved"><span>0</span></span>';
		}
	}
}


/**
 * @param GantryFormItem $element
 *
 * @return string
 */
function  gantry_admin_render_edit_override_item($element)
{
	if ($element->type == 'tips' && (isset($element->element['tab']) && (string)$element->element['tab'] != 'overview')) return $element->getInput();

	$buffer = "";
	$buffer .= "				<div class=\"gantry-field " . $element->type . "-field g4-row\">\n";
	$label   = '';
	$checked = ($element->variance) ? ' checked="checked"' : '';
	if ($element->show_label && !$element->setinoverride){
		$label = $element->getLabel();
	}
	elseif ($element->setinoverride){
		$label = '<div class="field-label"><span class="inherit-checkbox"><input  name="overridden-' . $element->name . '" type="checkbox"' . $checked . '/></span><span class="base-label">' . $element->getLabel() . '</span></div>';
	}
	$buffer .= "<div class=\"g4-cell g4-col1\">\n";
	$buffer .= $label;
	$buffer .= "</div>";
	$buffer .= "<div class=\"g4-cell g4-col2\"><div class=\"g4-col2-wrap\">\n";
	$buffer .= "<span class=\"rt-arrow\"><span></span></span>";
	$buffer .= $element->getInput() . "\n";
	$buffer .= "</div></div>\n";
	$buffer .= "</div>\n";
	return $buffer;
}

function gantry_admin_render_edit_item($element)
{
	if ($element->type == 'tips' && (isset($element->element['tab']) && (string)$element->element['tab'] != 'overview')) return $element->getInput();

	$buffer = '';
	$buffer .= "				<div class=\"gantry-field " . $element->type . "-field g4-row\">\n";
	$label = '';
	if ($element->show_label) $label = $element->getLabel() . "\n";
	$buffer .= "<div class=\"g4-cell g4-col1\">\n";
	$buffer .= $label;
	$buffer .= "</div>";
	$buffer .= "<div class=\"g4-cell g4-col2\"><div class=\"g4-col2-wrap\">\n";
	$buffer .= "<span class=\"rt-arrow\"><span></span></span>";
	$buffer .= $element->getInput() . "\n";
	$buffer .= "</div></div>\n";
	$buffer .= "</div>\n";
	return $buffer;
}

function gantry_admin_render_menu($isNewOverride = false)
{
	ob_start();
	?>
	<ul class="g4-actions">
		<span data-actions-spinner class="spinner"></span>
		<li class="rok-dropdown-group">
			<div class="rok-buttons-group">

				<div class="rok-button rok-button-primary" id="toolbar-apply" data-g4-toolbaraction="template.apply">
					Save
				</div>
				<div data-g4-toggle="save" class="rok-button rok-button-primary">
					<span class="caret"></span>
					<ul data-g4-dropdown="save" class="rok-dropdown">
						<?php if (!$isNewOverride): ?>
						<li><a href="#" id="toolbar-save-copy" data-g4-toolbaraction="admin-post.php?action=gantry_theme_save_as_copy">Save as Copy</a></li>
						<li class="divider"></li>
						<?php endif; ?>
						<li><a href="#" id="toolbar-save-preset">Save Preset</a></li>

					</ul>
				</div>
			</div>
		</li>
		<li class="rok-button rok-button-secondary" id="toolbar-show-presets">Presets</li>
		<li class="rok-button" id="toolbar-clearcache" data-ajaxbutton="{action: 'gantry_admin', model: 'cache', gantry_action: 'clear'}">Clear Cache
		</li>
		<!--<li class="rok-button" id="toolbar-purge">Reset</li>-->
		<!--<li class="rok-button" data-g4-toolbaraction="template.cancel">Close</li>-->
	</ul>
	<?php
	$buffer = ob_get_clean();
	return $buffer;
}

function gantry_admin_get_tabs($form, $override)
{
	$tabs      = array();
	$fieldSets = $form->getFieldsets();
	$i         = 1;
	 $activeTab       = (isset($_COOKIE['gantry-admin-tab'])) ? $_COOKIE['gantry-admin-tab'] + 1 : 1;
	if (!$override && $activeTab > count($fieldSets) - 1) $activeTab = 1;
	$fieldsetCount = count($fieldSets);

	foreach ($fieldSets as $name => $fieldSet) {
		if ($name == 'toolbar-panel') {
			$fieldsetCount--;
			continue;
		}
		$classes = '';
		if ($i == 1) $classes .= "first";
		if ($i == $fieldsetCount) $classes .= "last";
		if ($i == $activeTab) $classes .= " active ";
		$tabs[$name] = $classes;
		$i++;
	}
	return $tabs;
}
