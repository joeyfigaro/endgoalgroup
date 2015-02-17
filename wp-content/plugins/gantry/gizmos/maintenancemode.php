<?php
/**
 * @version   $Id: maintenancemode.php 60717 2014-04-10 20:32:32Z jakub $
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2014 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

defined( 'GANTRY_VERSION' ) or die();

gantry_import( 'core.gantrygizmo' );

/**
 * @package     gantry
 * @subpackage  features
 */
class GantryGizmoMaintenanceMode extends GantryGizmo {

	var $_name = 'maintenancemode';

	function init() {
		global $gantry, $pagenow;

		$gantry->addBodyClass( 'maintenance-mode' );

		$enabled_msg = _r( "Maintenance Mode Enabled. Please don\'t forget to disable it once you\'re done." );

		$js = "window.addEvent('domready', function() {
				var MaintenanceMessage = new Element('div', {
					'id': 'maintenance-notice',
					'text': '" . $enabled_msg . "'
				});
				MaintenanceMessage.inject(document.body , 'top');
			});\n";

		/* Check if user is administrator or can manage options */
		if( !( is_super_admin() || current_user_can( 'manage_options' ) || $pagenow == 'wp-login.php' ) ) {

			/* First try to load the maintenance.php file from the theme */
			if ( locate_template( array( 'maintenance.php' ) ) ) {
				add_filter( 'template_include', array( &$this, 'maintenancemode_template' ) );
			} else {
				wp_die( $this->get( 'message' ), get_bloginfo( 'title' ) );
			}
		} else {
			$gantry->addInlineScript( $js );
		}
	}

	static function maintenancemode_template( $template ) {
		return locate_template( array( 'maintenance.php' ) );
	}
}