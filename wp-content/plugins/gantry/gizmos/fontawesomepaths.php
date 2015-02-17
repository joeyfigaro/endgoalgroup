<?php
/**
 * @version   $Id: fontawesomepaths.php 60841 2014-05-12 20:13:48Z jakub $
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2014 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 */

defined('GANTRY_VERSION') or die();

gantry_import('core.gantrygizmo');

/**
 * @package     gantry
 * @subpackage  features
 */
class GantryGizmoFontAwesomePaths extends GantryGizmo {

    var $_name = 'fontawesomepaths';

    function isEnabled() {
        return true;
    }

    function init() {
        global $gantry;

        add_filter( 'gantry_less_compile_options', array( &$this, 'filter_fontawesome_paths' ), 10, 2 );
    }

    /**
     * Adds proper relative paths for FontAwesome font files to compiled css files
     *
     * @param $options
     * @param $default_compiled_css_dir
     *
     * @return mixed
     */
    function filter_fontawesome_paths( $options, $default_compiled_css_dir ) {
        global $gantry;

        $options['fontAwesomePath'] = "'" . untrailingslashit( $gantry->getRelativePath( $default_compiled_css_dir, $gantry->gantryPath . '/assets/jui/fonts' ) ) . "'";
        $options['FontAwesomePath'] = "'" . untrailingslashit( $gantry->getRelativePath( $default_compiled_css_dir, $gantry->gantryPath . '/assets/jui/fonts' ) ) . "'";
        $options['fa-font-path'] = "'" . untrailingslashit( $gantry->getRelativePath( $default_compiled_css_dir, $gantry->gantryPath . '/assets/jui/fonts/font-awesome4' ) ) . "'";

        return $options;
    }
}