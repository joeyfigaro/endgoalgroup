<?php
/**
 * @version   $Id: wpmenucart.php 60841 2014-05-12 20:13:48Z jakub $
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
class GantryGizmoWPMenuCart extends GantryGizmo {

	var $_name = 'wpmenucart';

	function isEnabled() {
		return true;
	}

	/**
	 * WP Menu Cart Compatibility
	 */

	function init() {
		global $gantry, $wpMenuCart;

		if( isset( $wpMenuCart ) && version_compare( $wpMenuCart->version, '2.5.3', '>' ) && ( class_exists( 'WpMenuCart' ) || class_exists( 'WpMenuCartPro' ) ) ) {

			if ( $wpMenuCart->options['menu_slugs'][1] != '0' ) {
				// Fire up WP Shopping Cart Filters
				add_action( 'init', array( $wpMenuCart, 'add_itemcart_to_menu' ) );

				// Filter RokMenu Output Nodes and update Menu Cache
				add_filter( 'gantry_nav_menu_' . $wpMenuCart->options['menu_slugs'][1] . '_output_nodes', array( &$this, 'wpmenucart_add_itemcart_to_menu' ) , 10, 2 );
				add_filter( 'gantry_menu_cache_menu_id', array( &$this, 'wpmenu_update_gantry_menu_cache' ) );
			}
		}
	}

	/**
	 * WP Menu Cart Compatibility - Create new RokMenu Node
	 *
	 * @param $outputNodes
	 * @param $args
	 *
	 * @return mixed
	 */

	function wpmenucart_add_itemcart_to_menu( $outputNodes, $args ) {
		global $gantry, $wpMenuCart;

		$item_data = $wpMenuCart->shop->menu_item();

		// Root Item data
		$menu_item    = $wpMenuCart->menu_items['menu'];
		$submenu_item = $wpMenuCart->menu_items['submenu'];

		// Root Item
		if( isset( $menu_item ) && !empty( $menu_item ) && isset( $menu_item['menu_item_a_content'] ) ) {
			$node = new RokMenuNode();
			$node->setId( 1000000 );
			$node->setParent( '0' );
			$node->setTitle( $menu_item['menu_item_a_content'] );
			$node->addLinkAttrib( 'title', $menu_item['menu_item_title'] );
			$node->setLink( $menu_item['menu_item_href'] );
			$node->setTarget( '' );
			$node->setItemType( 'custom' );
			$node->addListItemClass( $menu_item['menu_item_li_classes'] . ' item-wpmenucartli' );
			$node->addSpanClass( 'menuitem' );

			$outputNodes['1000000'] = $node;

			if( isset( $submenu_item ) && !empty( $submenu_item['items'] ) ) {
				$submenu_id = 1000001;
				foreach( $submenu_item['items'] as $child_item ) {
					$child = new RokMenuNode();
					$child->setId( $submenu_id );
					$child->setParent( 1000000 );
					$child->setTitle( $child_item['cart_submenu_item_content'] );
					$child->addLinkAttrib( 'title', $submenu_item['viewing_cart'] );
					$child->setLink( $child_item['item_permalink'] );
					$child->setTarget( '' );
					$child->setItemType( 'custom' );
					$child->addListItemClass( $child_item['item_li_classes'] . ' item-wpmenucartli' );
					$child->addSpanClass( 'menuitem' );

					// add this item as a child
					$node->addChild( $child );

					$outputNodes[ $submenu_id ] = $child;

					$submenu_id = ++$submenu_id;
				}
			}

		}

		return $outputNodes;
	}

	/**
	 * WP Menu Cart Compatibility - Update Cache on options
	 *
	 * @param $menu_id
	 * @return string
	 */

	function wpmenu_update_gantry_menu_cache( $menu_id ) {
		global $wpMenuCart;

		if( isset( $wpMenuCart->menu_items ) && !empty( $wpMenuCart->menu_items ) ) {
			$menu_hash = substr( $menu_id, 5 );
			$wpmc_hash = md5( serialize( $wpMenuCart->menu_items ) );
			$new_hash = md5( $menu_hash . '-' . $wpmc_hash );
			$menu_id = 'menu-' . $new_hash;
		}

		return $menu_id;
	}

}