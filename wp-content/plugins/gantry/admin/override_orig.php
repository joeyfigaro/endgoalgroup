<?php
/**
 * @version   $Id: override_orig.php 59366 2013-03-14 09:59:08Z jakub $
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2014 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

require_once(gantry_dirname(__FILE__).'/admin_functions.php');

/** @global $gantry Gantry */
global $gantry;
if (!current_user_can('edit_theme_options')) wp_die($gantry->get('template_full_name', 'Gantry') . ' ' . _g('THEME_SETTINGS'));

gantry_import('core.config.gantryform');
gantry_import('core.utilities.gantrytemplateinfo');

gantry_admin_compile_less();
define('GANTRY_CSS', 1);



/* This should be separated from index.php */
include(gantry_dirname(__FILE__) . '/assignment_functions.php');
gantry_assignment_template_pages_meta_boxes();
gantry_assignment_menus_meta_boxes();
gantry_assignment_post_type_meta_boxes();
gantry_assignment_archives_meta_boxes();
/* _EOF_ */


$gantry->addScript('mootools.js');
$gantry->addScript($gantry->gantryUrl . "/admin/widgets/moofx.js");
$gantry->addScript($gantry->gantryUrl . "/admin/widgets/Twipsy.js");
$gantry->addScript($gantry->gantryUrl . "/admin/widgets/gantry.js");
$gantry->addScript($gantry->gantryUrl . "/admin/widgets/gantry.popupbuttons.js");
$gantry->addScript($gantry->gantryUrl . '/admin/widgets/ajaxbutton/js/ajaxbutton.js');
$gantry->addScript($gantry->gantryUrl . "/admin/widgets/growl.js");
$gantry->addScript($gantry->gantryUrl . '/admin/widgets/radios/js/radios.js');
$gantry->addScript($gantry->gantryUrl . '/admin/widgets/assignments/js/assignments.js');
$gantry->addDomReadyScript("InputsMorph.init('checkbox', '#panel-sortables'); InputsMorph.init('checkbox', '.inherit-checkbox')");
// Setup the JS for the admin


gantry_admin_prep_needed_dirs();

if (file_exists($gantry->templatePath . "/gantry.scripts.php") && is_readable($gantry->templatePath . "/gantry.scripts.php")) {
	include_once($gantry->templatePath . "/gantry.scripts.php");
	if (function_exists('gantry_params_init')) {
		gantry_params_init();
	}
}

$override_catalog = gantry_get_override_catalog($gantry->templateName);
$data = array();
$data['template-options'] = get_option($gantry->templateName . '-template-options');

$assignments = array();
global $gantry_override_assignment_info;
$gantry_override_assignment_info = array();
$override_id = 0;
if (isset($_GET['override_id'])) {
	$override_id = urldecode($_GET['override_id']);
}
if ($override_id != 0) {
	$override_option = $gantry->templateName . '-template-options-override-' . $override_id;
	$override_data   = get_option($override_option);
	if ($override_data === false) $override_data = array();
	$data['template-options']         = array_merge_replace_recursive($data['template-options'], $override_data);
	$override_name                    = $override_catalog[$override_id];
	$override_assignments_option_name = $gantry->templateName . '-template-options-override-assignments-' . $override_id;
	$override_assignments             = get_option($override_assignments_option_name);
	if ($override_assignments === false) $override_assignments = array();
	$assignments = $override_assignments;
} else {
	$next_override = (count($override_catalog) > 0) ? max(array_keys($override_catalog)) + 1 : 1;
	$override_name = sprintf(_g('Custom Override %d'), $next_override);
}

GantryForm::addFormPath($gantry->templatePath);
GantryForm::addFieldPath($gantry->templatePath . '/fields');
GantryForm::addFieldPath($gantry->templatePath . '/admin/forms/fields');

$form = GantryForm::getInstance($gantry->_template->getTemplateInfo(), 'template-options', 'templateDetails', array(), true, '//config');
$form->bind($data);

$fieldSets = $form->getFieldsets('template-options');



$form->initialize();
?>


	<div id="hack-panel">
		<?php
		$fields = $form->getFullFieldset('toolbar-panel');
		foreach ($fields as $name => $field) {
			$gantry->addDomReadyScript("Gantry.ToolBar.add('" . $field->type . "');");

			echo "<div id=\"contextual-" . $field->type . "-wrap\" class=\"hidden contextual-custom-wrap\">\n";
			echo "		<div class=\"metabox-prefs\">\n";

			echo $field->input;

			echo "		</div>\n";
			echo "</div>\n";
		}
		?>
	</div>


	<div class="wrap override-wrap">
	<form id="gantry-mega-form" method="post" action="<?php echo admin_url('admin-post.php?action=gantry_theme_update_override'); ?>" enctype="multipart/form-data">
	<?php wp_nonce_field('gantry-theme-overrides-update'); ?>
	<div class="icon32" id="icon-themes"><br/></div>
	<h2>
		<?php echo $gantry->get('template_full_name'); ?> Override Settings
		<span>
			<input type="submit" class="button-secondary" name="reset" value="<?php _ge("Reset to pres"); ?>"
			       onclick="if(confirm('<?php _e("Reset all theme settings to the default values? Are you sure?"); ?>')) return true; else return false;"/>
			<input type="button" class="button-secondary preset-saver action" value="Save Custom Preset as New"/>
			<input type="submit" class="button-primary action" value="<?php _ge('Save Changes'); ?>"/>
		</span>
	</h2>
	<?php

	if ($message = gantry_get_admin_message('gantry-theme-settings')): ?>
		<div class="updated gantry-notice">
			<p><?php echo $message;?></p>

			<div class="close"><span>x</span></div>
		</div>
	<?php endif; ?>
	<input type="hidden" name="override_id" value="<?php echo $override_id ?>"/>
	<input type="hidden" name="override_name" value="<?php echo $override_name ?>"/>
	<input type="hidden" name="id" value="<?php echo $gantry->templateName; ?>"/>
	<?php //settings_fields('theme-options-array'); ?>
	<div class="fltrt">
		<div class="submit-wrapper png">

		</div>
		<div class="gantry-wrapper">
			<div id="gantry-logo"></div>
			<div id="gantry-overrides">
				<?php
				$overridesList = array();
				$overridesList[] = '<div class="overrides-action"><a class="defaults" href="' . str_replace("&", "&amp;", add_query_arg(array('page' => 'gantry-theme-settings'), admin_url('admin.php'))) . '">' . _g('Default Settings') . '</a></div>';

				foreach ($override_catalog as $o_id => $o_name) {
					$overridesList[] = '<div class="overrides-action"><a href="' . str_replace("&", "&amp;", add_query_arg(array(
					                                                                                                            'page'        => 'gantry-theme-overrides',
					                                                                                                            'override_id' => $o_id
					                                                                                                       ))) . '">' . $o_name . '</a></div>';
				}
				$toggleStatus = (!count($overridesList)) ? ' class="hidden"' : '';
				?>
				<div class="overrides-inner">
					<div id="overrides-actions">
						<div id="overrides-first">
							<a href="#"><?php echo $override_name; ?></a>
						</div>
						<div id="overrides-toggle"<?php echo $toggleStatus; ?>><br/></div>
						<div id="overrides-inside"
						     class="slideup"><?php echo implode("\n", $overridesList); ?></div>
					</div>

					<div id="overrides-toolbar">
						<a class="overrides-button button-add"
						   href="<?php echo admin_url('admin.php?page=gantry-theme-overrides&amp;override_id=0'); ?>"><span>Add</span></a>
						<a class="overrides-button button-del"
						   href="<?php echo admin_url('admin-post.php?action=gantry_theme_delete_override&amp;override_id=' . $override_id);?>"><span>Delete</span></a>

						<div class="overrides-button button-edit"></div>
					</div>
					<div id="overrides-switch">
						<a class="text-button button-widget" href="<?php echo admin_url('widgets.php?override_id=' . $override_id);?>"><span><?php _ge('Widgets')?></span></a>
					</div>
				</div>
			</div>
			<ul id="gantry-tabs">
				<?php
				$panels = array();
				$positions = array(
					'hiddens' => array(),
					'top'     => array(),
					'left'    => array(),
					'right'   => array(),
					'bottom'  => array()
				);

				$i = 1;
				$activeTab = (isset($_COOKIE['gantry-admin-tab'])) ? $_COOKIE['gantry-admin-tab'] + 1 : 1;
				if ($activeTab > count($fieldSets) + 1 || $activeTab == 1) $activeTab = 2;
				foreach ($fieldSets as $name => $fieldSet):
					if ($name != 'toolbar-panel') {
						$classes = '';
						if ($i == 2) $classes .= "first";
						//if ($i == count($fieldSets)) $classes .= "last";
						if ($i == $activeTab) $classes .= " active ";
						if ($i == 1) $style = " style='display:none;'"; else $style = "";
						$involved = 0;

						$fields = $form->getFullFieldset($name);
						array_push($panels, array("name" => $name));
						foreach ($fields as $fname => $field) {
							$position = $field->panel_position;
							if ($field->variance) $involved++;
							if ($field->type == 'hidden') $position = 'hiddens';
							if (!isset($positions[$position][$name])) $positions[$position][$name] = array();
							array_push($positions[$position][$name], $field);
						}
						?>
						<li class="<?php echo $classes;?>"<?php echo $style; ?>>
                        <span class="outer">
                            <span class="inner"><span style="float:left;"><?php _ge($fieldSet->label);?></span> <?php echo get_badges_layout($involved); ?></span>
                        </span>
						</li>

						<?php $i++;
					} endforeach;?>
				<li class="last <?php if ($activeTab == $i) {
					echo "active";
				}?>">
                        <span class="outer">
                            <span class="inner"><span style="float:left;"><?php _ge('Assignments');?></span> <span class="presets-involved"> <span>0</span></span></span>
                        </span>
				</li>
			</ul>
			<?php

			$output = "";
			$output .= "<div id=\"gantry-panel\">\n";
			if (count($panels) > 0) {
				for ($i = 0; $i < count($panels); $i++) {
					$panel = $panels[$i]['name'];

					$width = '';
					if ((@count($positions['left'][$panels[$i]['name']]) && !@count($positions['right'][$panels[$i]['name']])) || (!@count($positions['left'][$panels[$i]['name']]) && @count($positions['right'][$panels[$i]['name']]))) {
						$width = 'width-auto';
					}

					$activePanel = "";
					if ($i == $activeTab - 1) $activePanel = " active-panel"; else $activePanel = "";

					if ($i == 0) $style = " style='display:none;'"; else $style = "";

					$output .= "	<div class=\"gantry-panel panel-" . ($i + 1) . " panel-" . $panel . " " . $width . $activePanel . "\"" . $style . ">\n";

					$buffer = "";
					foreach ($positions as $name => $position) {
						if (isset($positions[$name][$panel])) {
							$buffer .= "		<div class=\"gantry-panel-" . $name . "\">\n";
							$panel_name = $name == 'left' ? 'panelform' : 'paneldesc';

							$buffer .= "			<div class=\"" . $panel_name . "\">\n";
							foreach ($positions[$name][$panel] as $element) {
								$buffer .= $element->render('gantry_admin_render_edit_override_item');
							}

							$buffer .= "			</div>\n";
							$buffer .= "		</div>\n";
						}
					}
					$output .= $buffer;

					$output .= "	</div>";
				}
			}
			if ($i == $activeTab - 1) $activePanel = " active-panel"; else $activePanel = "";
			$output .= "	<div id=\"assignments-panel\" class=\"gantry-panel panel-" . ($i + 1) . " panel-assignments " . $width . $activePanel . "\">\n";
			$output .= "		<div class=\"gantry-panel-left\">\n";
			$output .= "		    <div class=\"panelform\">\n";
			$output .= "				<div class=\"left-list\">\n";
			ob_start();
			do_assignment_meta_boxes('gantry_assignments', 'panel', null, $assignments, $assignment_info);
			$output .= ob_get_clean();
			$output .= "				</div>\n";
			$output .= "			<div class='clr'></div>\n";
			$output .= "			</div>\n";
			$output .= "		</div>\n";
			$output .= "				<div class=\"gantry-panel-right\">\n";
			$output .= "			    <div class=\"panelform\">\n";
			$output .= "					<div id=\"selection-list\" class=\"assignments-block\">\n";
			$output .= "						<h2>" . _g('Assigned Overrides') . "</h2>\n";
			$output .= "						<ul id=\"assigned-list\">\n";
			global $gantry_override_types;
			if (empty($assignments)) {
				$output .= "						<li class=\"empty\">" . _g('No Item.') . "</li>\n";
			} else {
				foreach ($assignments as $archetype => $assignment) {
					foreach ($assignment as $type => $value) {
						if (is_bool($value) && $value) {
							$data        = $archetype . '::' . $type;
							$label       = (isset($gantry_override_assignment_info[$data])) ? $gantry_override_assignment_info[$data]->title : $gantry_override_types[$data]->type_label;
							$type_string = (isset($gantry_override_assignment_info[$data])) ? $gantry_override_assignment_info[$data]->single_label : _g('Type');
							$output .= '<li class="list-type">' . "\n";
							$output .= '	<span class="type">' . $type_string . '</span>' . "\n";
							$output .= '	<span class="delete-assigned"></span>' . "\n";
							$output .= '	<span class="link">' . "\n";
							$output .= '		<span class="' . $data . '">' . $label . '</span>' . "\n";
							$output .= '	</span>' . "\n";
							$output .= '</li>' . "\n";
						} else {
							foreach ($value as $item_id) {
								$data  = $archetype . '::' . $type . '::' . $item_id;
								$title = $gantry_override_assignment_info[$data]->title;
								$output .= '<li class="list-type">' . "\n";
								$output .= '	<span class="type">' . $gantry_override_assignment_info[$data]->single_label . '</span>' . "\n";
								$output .= '	<span class="delete-assigned"></span>' . "\n";
								$output .= '	<span class="link">' . "\n";
								$output .= '		<a class="no-link-item" href="#" rel="' . $data . '">' . $title . '</a>' . "\n";
								$output .= '	</span>' . "\n";
								$output .= '</li>' . "\n";
							}
						}
					}
				}

			}
			$output .= "						</ul>\n";
			$output .= "						<div class=\"footer-block\"" . (count($assignments) ? " style='display: block;'" : "") . ">\n";
			$output .= "							<div class=\"clear-list\"><a href=\"#\">Clear List</a></div>\n";
			$output .= "						</div>\n";
			$output .= "						<textarea style=\"width: 395px; height: 260px;\" name=\"assigned_override_items\" id=\"assigned_override_items\">" . serialize($assignments) . "</textarea>\n";
			$output .= "					</div>\n";
			$output .= "				</div>\n";
			$output .= "				</div>\n";
			$output .= "	</div>";
			$output .= "</div>\n";
			echo $output;
			?>
		</div>
		<div class="clr"></div>
	</div>
	<div class="clr"></div>
	</form>
	</div>

<?php
gantry_admin_override_css();
$form->finalize();
?>
