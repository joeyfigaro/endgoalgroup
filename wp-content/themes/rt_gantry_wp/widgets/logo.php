<?php
/**
 * @version   4.1.2 May 18, 2014
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2014 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

defined( 'GANTRY_VERSION' ) or die();

gantry_import( 'core.gantrywidget' );

class GantryWidgetLogo extends GantryWidget {
	var $short_name = 'logo';
	var $wp_name = 'gantry_logo';
	var $long_name = 'Gantry Logo';
	var $description = 'Gantry Logo Widget';
	var $css_classname = 'widget_gantry_logo';
	var $width = 200;
	var $height = 400;

	static function init() {
		register_widget( 'GantryWidgetLogo' );
	}

	function render_widget_open( $args, $instance ) {
	}
	
	function render_widget_close( $args, $instance ) {
	}
	
	function pre_render( $args, $instance ) {
	}
	
	function post_render( $args, $instance ) {
	}

	function render( $args, $instance ) {
		global $gantry;
		
		ob_start();
		?>
		<div id="<?php echo $this->id; ?>" class="widget <?php echo $this->css_classname; ?> rt-block logo-block">
			<a href="<?php echo home_url(); ?>" id="rt-logo"></a>
		</div>
		<?php
		echo ob_get_clean();
	}
}
