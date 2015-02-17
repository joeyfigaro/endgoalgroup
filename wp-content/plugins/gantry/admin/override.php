<?php
/**
 * @version   $Id: override.php 60288 2013-12-10 13:10:52Z jakub $
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2014 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

require_once(gantry_dirname(__FILE__) . '/admin_functions.php');

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
$gantry->addInlineScript("var GantryIsMaster = false;");


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

$tabs = gantry_admin_get_tabs($form);

$activeTab = (isset($_COOKIE['gantry-admin-tab'])) ? $_COOKIE['gantry-admin-tab'] + 1 : 1;
$assignmentCount = 0;
?>
<div class="g4-wrap <?php echo 'override-wrap'; ?>">
<div id="g4-toolbar">
	<div class="icon32" id="icon-themes"></div>
	<h1><?php _ge($gantry->get('template_full_name', 'Gantry') . ' ' . _g('THEME_SETTINGS')); ?></h1>
	<?php echo gantry_admin_render_menu(); ?>
</div>

<form action="<?php echo admin_url('admin-post.php?action=gantry_theme_update_override'); ?>" method="post" name="adminForm" id="adminForm" class="form-validate">
<?php wp_nonce_field('gantry-theme-overrides-update'); ?>
<input type="hidden" name="override_id" value="<?php echo $override_id ?>"/>
<input type="hidden" name="override_name" value="<?php echo $override_name ?>"/>
<input type="hidden" name="id" value="<?php echo $gantry->templateName; ?>"/>
<?php echo $form->getInput('client_id'); ?>
<?php if ($message = gantry_get_admin_message('gantry-theme-settings')): ?>
	<div class="updated gantry-notice">
		<p><?php echo $message;?></p>

		<div class="close"><span>x</span></div>
	</div>
<?php endif; ?>
<?php
$status = (isset($_COOKIE['gantry-' . $gantry->templateName . '-adminpresets'])) ? htmlentities($_COOKIE['gantry-' . $gantry->templateName . '-adminpresets']) : 'hide';
$presetsShowing = ($status == 'hide') ? "" : ' class="presets-showing"';

if ($override !== false) {
	$flag      = 'g4-flag-override';
	$flag_text = _g('Override');
} else {
	$flag      = 'g4-flag-master';
	$flag_text = '&#10029; ' . _g('Default');
}
?>
<div id="g4-details-wrapper">
	<div id="g4-master" class="<?php echo $flag; ?> g4-size-13">
		<div id="g4-flag">
			<?php echo $flag_text; ?>
			<span class="rt-arrow"><span></span></span>
		</div>
	</div>
	<div id="g4-details"<?php echo $presetsShowing; ?>>
		<div id="gantry-overrides">
			<div class="overrides-inner">
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
				<div id="overrides-actions">
					<div id="overrides-first">
						<a href="#"><?php echo $override_name; ?></a>
					</div>
					<div id="overrides-toggle"<?php echo $toggleStatus; ?>><br/></div>
					<div id="overrides-inside" class="slideup">
						<?php echo implode("\n", $overridesList); ?>
					</div>
				</div>
				<div id="overrides-toolbar">
					<a class="overrides-button button-add"
					   href="<?php echo admin_url('admin.php?page=gantry-theme-overrides&amp;override_id=0'); ?>"><span>Add</span></a>
					<a class="overrides-button button-del"
					   href="<?php echo admin_url('admin-post.php?action=gantry_theme_delete_override&amp;override_id=' . $override_id);?>"><span>Delete</span></a>

					<div class="overrides-button button-edit"></div>
				</div>
				<div id="overrides-switch">
					<a class="text-button button-widget" href="<?php echo admin_url('widgets.php'); ?>"><span><?php _ge('Widgets');?></span></a>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="g4-presets">
	<div class="submit-wrapper png"></div>
	<?php include(gantry_dirname(__FILE__) . '/admin_presets.php'); ?>
</div>
<div id="g4-container">
	<div class="g4-header">
		<div class="g4-wrapper">
			<div class="g4-row">
				<div class="g4-column">
					<div id="g4-logo"><span></span></div>
					<ul class="g4-tabs">
						<?php
						$panels = array();
						$positions = array(
							'hiddens' => array(),
							'top'     => array(),
							'left'    => array(),
							'right'   => array(),
							'bottom'  => array()
						);

						$involvedCounts = array();
						foreach ($fieldSets as $name => $fieldSet) {
							if ($name == 'toolbar-panel') continue;
							$fields   = $form->getFullFieldset($name);
							$involved = 0;
							array_push($panels, array(
							                         "name"   => $name,
							                         "height" => (isset($fieldSet->height)) ? $fieldSet->height : null
							                    ));
							foreach ($fields as $fname => $field) {
								$position = $field->panel_position;

								if ($field->type != 'hidden' && $field->setinoverride && $field->variance) $involved++;
								if ($field->type == 'hidden') $position = 'hiddens';
								if (!isset($positions[$position][$name])) $positions[$position][$name] = array();
								array_push($positions[$position][$name], $field //array("name" => $field->name, "label" => $field->label, "input" => $field->input, "show_label" => $field->show_label, 'type' => $field->type)
								);
							}
							$involvedCounts[$name] = $involved;
						}


						foreach ($fieldSets as $name => $fieldSet):
							if ($name == 'toolbar-panel') continue;
							?>
							<li class="<?php echo $tabs[$name];?>">
								<span class="badge"><?php echo get_badges_layout($involved);?></span>
								<?php echo _g($fieldSet->label);?>
								<span class="rt-arrow"><span><span></span></span></span>
							</li>
						<?php endforeach;?>
						<li class="assignments">
							<span class="badge"><?php echo get_badges_layout($involved);?></span>
							<?php _ge('Assignments');?>
							<span class="rt-arrow"><span><span></span></span></span>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
	<div class="g4-body">
		<div id="g4-panels">
			<?php
			if (count($panels) > 0) {
				for ($i = 0; $i < count($panels); $i++) {
					$panel = $panels[$i]['name'];
					$width = '';
					if ((@count($positions['left'][$panels[$i]['name']]) && !@count($positions['right'][$panels[$i]['name']])) || (!@count($positions['left'][$panels[$i]['name']]) && @count($positions['right'][$panels[$i]['name']]))) {
						$width = 'width-100pc';
					}

					$activePanel = "";
					if ($i == $activeTab - 1) $activePanel = " active-panel"; else $activePanel = "";
					?>
					<div class="g4-panel panel-<?php echo ($i + 1);?> panel-<?php echo $panel;?> <?php echo $width;?><?php echo $activePanel;?>">
						<?php
						$buffer = "";
						foreach ($positions as $name => $position) {

							if (isset($positions[$name][$panel])) {
								// hide right panels in Gantry4 for all but overview tab
								if (!($name == "right" && $panel != "overview")) {
									$buffer .= "		<div class=\"g4-panel-" . $name . "\">\n";
									$panel_name = $name == 'left' ? 'panelform' : 'paneldesc';

									$buffer .= "			<div class=\"" . $panel_name . "\">\n";

									if ($panel_name == 'paneldesc' && $panel == 'overview') {
										//$buffer .= get_version_update_info();

									}
									foreach ($positions[$name][$panel] as $element) {
										if (!$override === false) {
											$buffer .= $element->render('gantry_admin_render_edit_item');
										} else {
											$buffer .= $element->render('gantry_admin_render_edit_override_item');
										}
									}
									$buffer .= "			</div>\n";
									$buffer .= "		</div>\n";
								}

								if ($panel != 'overview' && $name == 'right') {
									foreach ($positions[$name][$panel] as $element) {
										if (get_class($element) != 'GantryFormFieldTips') continue;

										if ($override === false) {
											$buffer .= $element->render('gantry_admin_render_edit_item');
										} else {
											$buffer .= $element->render('gantry_admin_render_edit_override_item');
										}
									}
								}
							}
						}
						echo $buffer;
						?>
					</div>
				<?php
				}
			}
			if ($i == $activeTab - 1) $activePanel = " active-panel"; else $activePanel = "";
			include(gantry_dirname(__FILE__) . '/admin_assignments.php');
			?>
		</div>
	</div>
	<div class="clr"></div>
</div>
</form>
</div>
<?php
gantry_admin_override_css();
$form->finalize();
?>
