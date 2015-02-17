<?php
/**
 * @version   $Id: index_orig.php 59361 2013-03-13 23:10:27Z btowles $
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2014 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

require_once(gantry_dirname(__FILE__).'/admin_functions.php');

/** @global $gantry Gantry */
global $gantry;
if (!current_user_can('edit_theme_options')) wp_die(_g($gantry->get('template_full_name', 'Gantry Template') . _g('Settings')));

gantry_import('core.config.gantryform');
gantry_import('core.utilities.gantrytemplateinfo');

$gantry->addScript('mootools.js');
$gantry->addScript($gantry->gantryUrl . '/admin/widgets/gantry.js');
// Setup the JS for the admin
$gantry->addInlineScript(gantryLang());

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


<div class="wrap defaults-wrap">
	<form id="gantry-mega-form" method="post" action="<?php echo admin_url('admin-post.php?action=gantry_theme_update'); ?>" enctype="multipart/form-data">
		<?php wp_nonce_field('gantry-theme-settings'); ?>
		<div class="icon32" id="icon-themes"><br/></div>
		<h2>
			<?php echo $gantry->get('template_full_name'); ?> Settings
		<span>
			<input type="submit" class="button-secondary" name="reset" value="<?php _ge("Reset to Defaults"); ?>" onclick="if(confirm('<?php _e("Reset all theme settings to the default values? Are you sure?"); ?>')) return true; else return false;"/>
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
		<input type="hidden" name="id" value="<?php echo $gantry->templateName; ?>"/>
		<?php //settings_fields('theme-options-array'); ?>
		<div class="fltrt">
			<div class="submit-wrapper png">

			</div>
			<div class="gantry-wrapper">
				<div id="gantry-logo"></div>
				<div id="gantry-overrides">
					<div class="overrides-inner">
						<?php
						$overridesList = array();

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
								<a href="#">Default Settings</a>
							</div>
							<div id="overrides-toggle"<?php echo $toggleStatus; ?>><br/></div>
							<div id="overrides-inside" class="slideUp">
								<?php
								$overridesList = array();
								foreach ($override_catalog as $o_id => $o_name) {
									$overridesList[] = '<div class="overrides-action"><a href="' . str_replace("&", "&amp;", add_query_arg(array(
									                                                                                                            'page'        => 'gantry-theme-overrides',
									                                                                                                            'override_id' => $o_id
									                                                                                                       ))) . '">' . $o_name . '</a></div>';
								}
								?>
								<?php echo implode("\n", $overridesList); ?>
							</div>
						</div>
						<div id="overrides-toolbar">
							<a class="text-button button-add" href="<?php echo admin_url('admin.php?page=gantry-theme-overrides&amp;override_id=0'); ?>"><span>New Override</span></a>
						</div>
						<div id="overrides-switch">
							<a class="text-button button-widget" href="<?php echo admin_url('widgets.php'); ?>"><span>Widgets</span></a>
						</div>
					</div>

				</div>
				<ul id="gantry-tabs">
					<?php
					$i = 1;
					$activeTab = (isset($_COOKIE['gantry-admin-tab'])) ? $_COOKIE['gantry-admin-tab'] + 1 : 1;
					if ($activeTab > count($fieldSets) - 1) $activeTab = 1;
					foreach ($fieldSets as $name => $fieldSet):
						if ($name == 'toolbar-panel') continue;
						$classes = '';
						if ($i == 1) $classes .= "first";
						if ($i == count($fieldSets)) $classes .= "last";
						if ($i == $activeTab) $classes .= " active ";
						?>
						<li class="<?php echo $classes;?>">
                        <span class="outer">
                            <span class="inner"><span style="float:left;"><?php _ge($fieldSet->label);?></span> <span class="presets-involved"><span>0</span></span></span>
                        </span>
						</li>

						<?php $i++; endforeach;?>
				</ul>
				<?php
				$panels = array();
				$positions = array(
					'hiddens' => array(),
					'top'     => array(),
					'left'    => array(),
					'right'   => array(),
					'bottom'  => array()
				);

				foreach ($fieldSets as $name => $fieldSet) {
					if ($name == 'toolbar-panel') continue;
					$fields = $form->getFullFieldset($name);

					array_push($panels, array(
					                         "name"   => $name,
					                         "height" => (isset($fieldSet->height)) ? $fieldSet->height : null
					                    ));
					foreach ($fields as $fname => $field) {
						$position = $field->panel_position;
						if ($field->type == 'hidden') $position = 'hiddens';
						if (!isset($positions[$position][$name])) $positions[$position][$name] = array();
						array_push($positions[$position][$name], $field);
					}
				}

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

						$output .= "	<div class=\"gantry-panel panel-" . ($i + 1) . " panel-" . $panel . " " . $width . $activePanel . "\">\n";

						$buffer = "";
						foreach ($positions as $name => $position) {
							if (isset($positions[$name][$panel])) {
								$buffer .= "		<div class=\"gantry-panel-" . $name . "\">\n";
								$panel_name = $name == 'left' ? 'panelform' : 'paneldesc';

								$buffer .= "			<div class=\"" . $panel_name . "\">\n";
								foreach ($positions[$name][$panel] as $element) {
									$buffer .= $element->render('gantry_admin_render_edit_item');
								}

								$buffer .= "			</div>\n";
								$buffer .= "		</div>\n";
							}
						}
						$output .= $buffer;

						$output .= "	</div>";
					}
				}
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
