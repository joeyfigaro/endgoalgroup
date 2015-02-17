<?php
/**
 * @version   1.23 January 15, 2012
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2012 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */
defined('GANTRY_VERSION') or die;

gantry_import('core.config.gantryformgroup');


class GantryFormGroupInnerTabs extends GantryFormGroup
{

	protected $type = 'innertabs';
	protected $baseetype = 'group';

	public function getInput()
	{
		return '';
	}


	public function render($callback)
	{
		ob_start();
		?>
		<div>
			<div class="inner-tabs">
				<ul>
					<?php
					$i = 0;
					$involvedCounts = array();
					foreach ($this->fields as $field):
						$involved = 0;
						$name = $field->name;
						$classes = '';
						if (!$i) $classes .= "first active";
						if ($i == count($this->fields) - 1) $classes .= ' last';
						foreach ($field->fields as $inner_field) {
							if ($inner_field->type != 'hidden' && $inner_field->type != 'innertabs' && $inner_field->setinoverride && $inner_field->variance) $involved++;
						}
						$involvedCounts[$name] = $involved;
						?>
						<li class="<?php echo $classes;?>">
							<span class="g4-cell g4-col1">
								<span class="badge"><?php echo get_badges_layout($involvedCounts[$name]); ?></span>
								<?php _ge($field->getLabel());?>
							</span>
						</li>
						<?php
						$i++;
					endforeach;
					?>
				</ul>
			</div>
			<div class="inner-panels">
				<?php
				$i = 0;
				foreach ($this->fields as $field):
					$i++;
					?>
					<div class="inner-panel inner-panel-<?php echo $i;?>">
						<?php if ($field->type == 'hidden'): ?>
							<?php echo $field->getInput(); ?>
						<?php else: ?>
							<?php echo $field->render($callback); ?>
						<?php endif;?>
					</div>
				<?php endforeach;?>
			</div>
		</div>
		<?php
		$buffer = ob_get_clean();
		return $buffer;
	}

}
