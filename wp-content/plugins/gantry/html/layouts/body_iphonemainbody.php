<?php
/**
 * @version   $Id: body_iphonemainbody.php 59361 2013-03-13 23:10:27Z btowles $
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
class GantryLayoutBody_iPhoneMainBody extends GantryBodyLayout
{
	var $render_params = array(
		'schema'   => null,
		'classKey' => null
	);

	function render($params = array())
	{
		/** @global $gantry Gantry */
		global $gantry;

		$fparams = $this->_getParams($params);

		// logic to determine if the component should be displayed
		$display_component = !($gantry->get("component-enabled", true) == false);
		ob_start();
// XHTML LAYOUT
		?>
		<div id="rt-main" class="<?php echo $fparams->classKey; ?>">
			<div class="rt-container">
				<div class="rt-grid-12">
					<div class="rt-block">
						<?php if ($display_component) : ?>
						<div id="rt-mainbody">
							<div class="component-content">
								<?php $this->include_type();?>
							</div>
						</div>
						<?php endif; ?>
					</div>
				</div>
				<div class="clear"></div>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}
}