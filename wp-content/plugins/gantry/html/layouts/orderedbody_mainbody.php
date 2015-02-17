<?php
/**
 * @version   $Id: orderedbody_mainbody.php 59361 2013-03-13 23:10:27Z btowles $
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2014 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 */
defined('GANTRY_VERSION') or die();

gantry_import('core.gantrybodylayout');

/**
 *
 * @package    gantry
 * @subpackage html.layouts
 */
class GantryLayoutOrderedBody_MainBody extends GantryBodyLayout
{
	var $render_params = array(
		'schema'            => null,
		'pushPull'          => null,
		'classKey'          => null,
		'sidebars'          => '',
		'contentTop'        => null,
		'contentBottom'     => null,
		'component_content' => '',
		'extraClass'        => ''
	);

	function render($params = array())
	{
		/** @global $gantry Gantry */
		global $gantry;

		$fparams = $this->_getParams($params);

		// logic to determine if the component should be displayed
		$display_mainbody = !($gantry->get("mainbody-enabled", true) == false);
		$display_component = !($gantry->get("component-enabled", true) == false);
		ob_start();

		$mbClasses = trim("rt-grid-" . trim($fparams->schema['mb'] . " " . $fparams->pushPull[0] . " " . $fparams->extraClass));
		$mbClasses = preg_replace('/\s\s+/', ' ', $mbClasses);

		// XHTML LAYOUT
		?>
		<?php if ($display_mainbody) : ?>
		<div id="rt-main" class="<?php echo $fparams->classKey; ?>">
			<div class="rt-container">
				<?php foreach ($fparams->schema as $position => $value): ?>
					<?php if ($position != 'mb'): ?>
						<?php echo $fparams->sidebars[$position]; ?>
					<?php else: ?>
						<div class="<?php echo $mbClasses; ?>">
							<?php if (isset($fparams->contentTop)) : ?>
							<div id="rt-content-top">
								<?php echo $fparams->contentTop; ?>
							</div>
							<?php endif; ?>
							<?php if ($display_component) : ?>
							<div class="rt-block">
								<div id="rt-mainbody">
									<div class="component-content">
										<?php
										if ('' == $fparams->component_content) {
											$this->include_type();
										} else {
											echo $fparams->component_content;
										}
										?>
									</div>
								</div>
							</div>
							<?php endif; ?>
							<?php if (isset($fparams->contentBottom)) : ?>
							<div id="rt-content-bottom">
								<?php echo $fparams->contentBottom; ?>
							</div>
							<?php endif; ?>
						</div>
					<?php endif; ?>
				<?php endforeach; ?>
				<div class="clear"></div>
			</div>
		</div>
		<?php endif; ?>
		<?php
		return ob_get_clean();
	}
}