<?php
/**
 * @version   4.1.2 May 18, 2014
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2014 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */
defined( 'GANTRY_VERSION' ) or die();

gantry_import( 'core.gantrylayout' );

/**
 *
 * @package gantry
 * @subpackage html.layouts
 */
class GantryLayoutChrome_Menu extends GantryLayout {
	var $render_params = array(
		'gridCount'     =>  null,
		'prefixCount'   =>  0,
		'extraClass'      =>  ''
	);

	function render($params = array()){
		global $gantry, $wp_registered_widgets;
		$rparams = $this-> _getParams($params[0]);
		$instance_params = $this->_getWidgetInstanceParams($params[0]['widget_id']);
		
		$id =  $params[0]['widget_id'];
		$classname = $wp_registered_widgets[$params[0]['widget_id']]['classname'];

		// gantry render params
		$params[0]['pre_widget'] = '';
		$params[0]['widget_open'] = '';
		$params[0]['title_open'] = '';
		$params[0]['title_close'] = '';
		$params[0]['widget_close'] = '';
		$params[0]['post_widget'] = '';
		$params[0]['pre_render'] = '';
		$params[0]['post_render'] = '';

		// normal wp widget params
		$params[0]['before_widget'] = '';
		$params[0]['before_title']  = '';
		$params[0]['after_title']  = '';
		$params[0]['after_widget']  = '';

		$widget_variations = $pre_widget = $post_widget = $widget_open = $widget_close = $title_open = $title_close = $pre_render = $post_render = '';

		$variations = array();

		if( $gantry->get( 'dropdown_widget_variations' ) ) : $variations[] = 'variations'; endif;
		if( $gantry->get( 'custom_widget_variations' ) ) : $variations[] = 'custom-variations'; endif;

		foreach( $variations as $variation ) {
			if( array_key_exists( $variation, $instance_params ) && is_array( $instance_params[$variation] ) && !empty( $instance_params[$variation] ) ) {
				$widget_variations .= implode( ' ', $instance_params[$variation] );
			} elseif ( array_key_exists( $variation, $instance_params ) && $instance_params[$variation] != false ) {
				if ( $instance_params[$variation] != '' ) $widget_variations .= ' ' . $instance_params[$variation];
			}
		}

		$widget_variations = trim( $widget_variations );
		( $widget_variations != '' ) ? $widget_variations = ' ' . $widget_variations : $widget_variations = '';
		
		?>

		<?php /** Begin Chrome Layout **/ ?>

		<?php ob_start(); ?>
		<div id="<?php echo $id; ?>" class="widget <?php echo $classname . $widget_variations; ?> rt-block menu-block">
			<?php $widget_open = ob_get_clean(); ?>
			<?php ob_start(); ?>
			<div class="clear"></div>
		</div>
		<?php $widget_close = ob_get_clean(); ?>

		<?php /** End Chrome Layout **/ ?>

		<?php
		
		$params[0]['widget_open'] = $widget_open;
		$params[0]['widget_close'] = $widget_close;

		if( !empty( $instance_params['title'] ) ) {
			$params[0]['before_widget'] = $params[0]['pre_widget'] . $params[0]['widget_open'];
			$params[0]['before_title'] = $params[0]['title_open'];
			$params[0]['after_title'] =  $params[0]['title_close'] . $params[0]['pre_render'];
			$params[0]['after_widget'] = $params[0]['post_render'] . $params[0]['widget_close'] . $params[0]['post_widget'];
		} else {
			$params[0]['before_widget'] = $params[0]['pre_widget'] . $params[0]['widget_open'] . $params[0]['pre_render'];
			$params[0]['before_title'] = $params[0]['title_open'];
			$params[0]['after_title'] =  $params[0]['title_close'];
			$params[0]['after_widget'] = $params[0]['post_render'] . $params[0]['widget_close'] . $params[0]['post_widget'];
		}
		
		return $params;
		
	}
}