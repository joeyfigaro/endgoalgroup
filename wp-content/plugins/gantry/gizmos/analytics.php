<?php
/**
 * @version   $Id: analytics.php 60809 2014-05-08 08:48:01Z jakub $
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2014 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

defined('GANTRY_VERSION') or die();

gantry_import('core.gantrygizmo');

/**
 * @package     gantry
 * @subpackage  features
 */
class GantryGizmoAnalytics extends GantryGizmo
{

	var $_name = 'analytics';

	function init()
	{
		/** @global $gantry Gantry */
		global $gantry;

        $ga_plugins = '';

        if( $this->get( 'plugins' ) != '' ) {
            $plugins = explode( ' ', $this->get( 'plugins' ) );
            $ga_plugins = "'" . implode( "', '", $plugins ) . "'";
        }

		ob_start();
		// start of Google Analytics javascript
		?>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
        ga('create', '<?php echo $this->get( 'code' ); ?>', 'auto');
        <?php if( $ga_plugins != '' ) echo "ga('require', " . $ga_plugins . ");\n"; ?>
        ga('send', 'pageview');
        <?php
		// end of Google Analytics javascript
		$gantry->addInlineScript(ob_get_clean());

	}
}