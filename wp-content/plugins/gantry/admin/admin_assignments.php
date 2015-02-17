<?php
/**
 * @version   $Id: admin_assignments.php 59954 2013-10-02 17:54:24Z btowles $
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2014 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */
?>
<div id="assignments-panel" class="g4-panel panel-<?php echo ($i + 1);?> panel-assignments <?php echo $width;?><?php echo $activePanel;?>">
	<div class="g4-panel-left">
		<div class="assignments-left">
			<div class="assignments-search">
				<div class="assignments-search-global-wrapper">
					<label>Global Filter <input type="text" class="large" placeholder="Start typing to filter the lists below" /></label>
					<div class="assignment-search-clear">&times;</div>
				</div>
			</div>
			<div class="left-list">
				<?php
				ob_start();
				do_assignment_meta_boxes('gantry_assignments', 'panel', null, $assignments, $assignment_info);
				echo ob_get_clean();
				?>
			</div>
		</div>
		<div class="assignments-right">
			<div id="selection-list" class="assignments-block">
				<h2><?php _ge('Assigned Overrides');?></h2>
				<ul id="assigned-list">
					<?php
					global $gantry_override_types;
					global $gantry_override_assignment_info;
					if (empty($assignments)) {
						?>
						<li class="empty"><?php _ge('No Item.');?></li>
					<?php
					} else {
						foreach ($assignments as $archetype => $assignment) {
							foreach ($assignment as $type => $value) {
								if (is_bool($value) && $value) {
									$data        = $archetype . '::' . $type;
									$label       = (isset($gantry_override_assignment_info[$data])) ? $gantry_override_assignment_info[$data]->title : $gantry_override_types[$data]->type_label;
									$type_string = (isset($gantry_override_assignment_info[$data])) ? $gantry_override_assignment_info[$data]->single_label : _g('Type');
									?>
									<li class="list-type clearfix">
										<span class="type"><?php echo $type_string;?></span>
										<span class="delete-assigned">&times;</span>
										<span class="link">
											<span class="<?php echo $data;?>"><?php echo $label;?></span>
										</span>
									</li>
								<?php
								} else {
									foreach ($value as $item_id) {

										$data  = $archetype . '::' . $type . '::' . $item_id;
										if (isset($gantry_override_assignment_info[$data])){
											$title = $gantry_override_assignment_info[$data]->title;
											?>
											<li class="list-type clearfix">
												<span class="type"><?php echo $gantry_override_assignment_info[$data]->single_label;?></span>
												<span class="delete-assigned">&times;</span>
												<span class="link">
													<a class="no-link-item" href="#" rel="<?php echo $data;?>"><?php echo $title;?></a>
												</span>
											</li>
									<?php
										}
									}
								}
							}
						}

					}
					?>
				</ul>
				<div class="footer-block clearfix"<?php if (count($assignments)) echo ' style="display: block;"';?>>
				<div class="clear-list"><a href="#"><?php _ge('Clear List');?></a></div>
			</div>
			<textarea name="assigned_override_items" id="assigned_override_items"><?php echo serialize($assignments);?></textarea>
		</div>
	</div>
</div>
