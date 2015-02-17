<?php
/**
 * @version   $Id: index.php 60344 2014-01-03 22:06:04Z jakub $
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

$gantry->addScript('mootools.js');
$gantry->addScript($gantry->gantryUrl . "/admin/widgets/moofx.js");
$gantry->addScript($gantry->gantryUrl . "/admin/widgets/Twipsy.js");
$gantry->addScript($gantry->gantryUrl . "/admin/widgets/gantry.js");
$gantry->addScript($gantry->gantryUrl . "/admin/widgets/gantry.popupbuttons.js");
$gantry->addScript($gantry->gantryUrl . '/admin/widgets/ajaxbutton/js/ajaxbutton.js');
$gantry->addScript($gantry->gantryUrl . "/admin/widgets/growl.js");


$override = false;
$override_id = 0;
$override_name = '';

if (isset($_GET['override_id'])) {
	$override_id = (int)urldecode($_GET['override_id']);
	$override    = true;
}

$override_catalog = gantry_get_override_catalog($gantry->templateName);

$form_action = admin_url('admin-ajax.php?action=gantry_admin_save_theme_default');
$widget_page = admin_url('widgets.php');
$gantry_is_master = 'true';
$override_is_new = 'false';

$data = array();
$data['template-options'] = get_option($gantry->templateName . '-template-options');

if ($override) {
	include(gantry_dirname(__FILE__) . '/assignment_functions.php');
	gantry_assignment_template_pages_meta_boxes();
	gantry_assignment_menus_meta_boxes();
	gantry_assignment_post_type_meta_boxes();
	gantry_assignment_archives_meta_boxes();

	//$gantry->addScript($gantry->gantryUrl . '/admin/widgets/radios/js/radios.js');
	$gantry->addScript($gantry->gantryUrl . '/admin/widgets/assignments/js/assignments.js');
	//$gantry->addDomReadyScript("InputsMorph.init('checkbox', '#panel-sortables'); InputsMorph.init('checkbox', '.inherit-checkbox')");
	$gantry_is_master = 'false';
	if ($override_id != 0) {
		$form_action = admin_url('admin-ajax.php?action=gantry_admin_save_theme_override');
		$override_option = $gantry->templateName . '-template-options-override-' . $override_id;
		$override_data   = get_option($override_option);
		if ($override_data === false) $override_data = array();
		$data['template-options']         = array_merge_replace_recursive($data['template-options'], $override_data);
		$override_name                    = $override_catalog[$override_id];
		$override_assignments_option_name = $gantry->templateName . '-template-options-override-assignments-' . $override_id;
		$override_assignments             = get_option($override_assignments_option_name);
		if ($override_assignments === false) $override_assignments = array();
		$assignments = $override_assignments;
		$widget_page = add_query_arg(array('override_id'=>$override_id),$widget_page);
	} else {
		$assignments = array();
		$form_action = admin_url('admin-post.php?action=gantry_theme_update_override');
		$next_override = (count($override_catalog) > 0) ? max(array_keys($override_catalog)) + 1 : 1;
		$override_name = sprintf(_g('Custom Override %d'), $next_override);
		$override_is_new = 'true';
	}
}

// Setup the JS for the admin
$gantry->addInlineScript('var GantryIsMaster = ' . $gantry_is_master . ', GantryOverrideIsNew = ' . $override_is_new . ';');

gantry_admin_prep_needed_dirs();

if (file_exists($gantry->templatePath . "/gantry.scripts.php") && is_readable($gantry->templatePath . "/gantry.scripts.php")) {
	include_once($gantry->templatePath . "/gantry.scripts.php");
	if (function_exists('gantry_params_init')) {
		gantry_params_init();
	}
}



GantryForm::addFormPath($gantry->templatePath);
GantryForm::addFieldPath($gantry->templatePath . '/fields');
GantryForm::addFieldPath($gantry->templatePath . '/admin/forms/fields');

$template_info = $gantry->_template->getTemplateInfo();
$form = GantryForm::getInstance($template_info, 'template-options', 'templateDetails', array(), true, '//config');
$form->bind($data);

$fieldSets = $form->getFieldsets('template-options');



$form->initialize();

$tabs = gantry_admin_get_tabs($form, $override);

$activeTab = (isset($_COOKIE['gantry-admin-tab'])) ? $_COOKIE['gantry-admin-tab'] + 1 : 1;
$assignmentCount = 0;
?>
<form action="<?php echo $form_action ?>" method="post" name="adminForm" id="adminForm" class="form-validate">

<?php if ($message = gantry_get_admin_message('gantry-theme-settings')): ?>
	<div class="updated g4-notice">
		<p><?php echo $message;?></p>

		<div class="close"><span>&times;</span></div>
	</div>
<?php endif; ?>

<div class="g4-wrap <?php echo (!$override) ? 'defaults-wrap' : 'override-wrap'; ?>">
<div id="g4-toolbar">
	<div class="icon32" id="icon-themes"></div>
	<h1><?php _ge($gantry->get('template_full_name', 'Gantry') . ' ' . _g('THEME_SETTINGS')); ?></h1>
	<?php echo gantry_admin_render_menu($override_is_new == 'true' ? true : false); ?>
</div>

<?php wp_nonce_field('gantry-theme-settings'); ?>
<input type="hidden" name="id" value="<?php echo $gantry->templateName; ?>"/>
<?php echo $form->getInput('client_id'); ?>
<?php if ($override): ?>
	<input type="hidden" name="override_id" value="<?php echo $override_id ?>"/>
	<input type="hidden" name="override_name" value="<?php echo $override_name ?>"/>
<?php endif;?>

<?php
$status = (isset($_COOKIE['gantry-' . $gantry->templateName . '-adminpresets'])) ? htmlentities($_COOKIE['gantry-' . $gantry->templateName . '-adminpresets']) : 'hide';
$presetsShowing = ($status == 'hide') ? "" : ' class="presets-showing"';

if ($override) {
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
					                                                                                                            'page'        => 'gantry-theme-settings',
					                                                                                                            'override_id' => $o_id
					                                                                                                       ))) . '">' . $o_name . '</a></div>';
				}
				$toggleStatus = (!count($overridesList)) ? ' class="hidden"' : '';
				?>
				<div id="overrides-actions">
					<div id="overrides-first">
						<?php $override_list_display_name = (!$override) ? _g('Default Settings') : $override_name;?>
						<a href="#"><?php echo $override_list_display_name?></a>
					</div>
					<div id="overrides-toggle"<?php echo $toggleStatus; ?>><br/></div>
					<div id="overrides-inside" class="slideup">
						<?php echo implode("\n", $overridesList); ?>
					</div>
				</div>
				<div id="overrides-toolbar">
					<?php if (!$override): ?>
						<a class="text-button button-add" href="<?php echo admin_url('admin.php?page=gantry-theme-settings&amp;override_id=0'); ?>"><span><?php _ge('New Override');?></span></a>
					<?php else: ?>
						<a class="overrides-button button-add"
						   href="<?php echo admin_url('admin.php?page=gantry-theme-settings&amp;override_id=0'); ?>"><span><?php _ge('Add');?></span></a>
						<a class="overrides-button button-del"
						   href="<?php echo admin_url('admin-post.php?action=gantry_theme_delete_override&amp;override_id=' . $override_id);?>"><span><?php _ge('Delete');?></span></a>
						<div class="overrides-button button-edit"></div>
					<?php endif;?>
				</div>
				<div id="overrides-switch">
					<a class="text-button button-widget" href="<?php echo $widget_page; ?>"><span><?php _ge('Widgets');?></span></a>
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

								if($field->type != 'hidden' && $field->type != 'innertabs' && $field->setinoverride && $field->variance) $involved++;
								if($field->type == 'innertabs') {
									foreach($field->fields as $inner_tab) {
										foreach($inner_tab->fields as $inner_field) {
											if($inner_field->type != 'hidden' && $inner_field->setinoverride && $inner_field->variance) $involved++;
										}
									}
								}
								if($field->type == 'hidden') $position = 'hiddens';
								if(!isset($positions[$position][$name])) $positions[$position][$name] = array();
								array_push($positions[$position][$name], $field //array("name" => $field->name, "label" => $field->label, "input" => $field->input, "show_label" => $field->show_label, 'type' => $field->type)
								);
							}
							$involvedCounts[$name] = $involved;
						}


						foreach ($fieldSets as $name => $fieldSet):
							if ($name == 'toolbar-panel') continue;
							?>
							<li class="<?php echo $tabs[$name];?>">
								<span class="badge"><?php echo get_badges_layout($involvedCounts[$name]);?></span>
								<?php echo _g($fieldSet->label);?>
								<span class="rt-arrow"><span><span></span></span></span>
							</li>
						<?php endforeach;?>
						<?php if ($override): ?>
							<?php $active_assignments = $activeTab == count($fieldSets) ? ' active' : ''; ?>
							<?php
							$assigned_to = 0;
							if(isset($override_assignments) && is_array($override_assignments)) {
								foreach(new RecursiveIteratorIterator(new RecursiveArrayIterator($override_assignments), RecursiveIteratorIterator::LEAVES_ONLY) as $countable_item) {
									$assigned_to++;
								};
							}
							?>
							<li class="assignments<?php echo $active_assignments; ?>">
								<span class="badge"><?php echo get_badges_layout($assigned_to);?></span>
								<?php _ge('Assignments');?>
								<span class="rt-arrow"><span><span></span></span></span>
							</li>
						<?php endif;?>
					</ul>
				</div>
			</div>
		</div>
	</div>
	<div class="g4-body">
		<div id="g4-panels">
			<?php
			$output = "";
			$output .= "<div id=\"g4-panels\">\n";
			if (count($panels) > 0) {
				for ($i = 0; $i < count($panels); $i++) {
					$panel = $panels[$i]['name'];
					$width = '';
					if ((@count($positions['left'][$panels[$i]['name']]) && !@count($positions['right'][$panels[$i]['name']])) || (!@count($positions['left'][$panels[$i]['name']]) && @count($positions['right'][$panels[$i]['name']]))) {
						$width = 'width-100pc';
					}

					$activePanel = "";
					if ($activeTab > count($panels) + ($override ? 1 : 0)) $activeTab = 1;
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
										if (!$override) {
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
										if (!$override) {
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
			if ($override) {
				if ($i == $activeTab - 1) $activePanel = " active-panel"; else $activePanel = "";
				include(gantry_dirname(__FILE__) . '/admin_assignments.php');
			}
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
