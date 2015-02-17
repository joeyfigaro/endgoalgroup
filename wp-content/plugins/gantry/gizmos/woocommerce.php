<?php
/**
 * @version   $Id: woocommerce.php 60800 2014-05-07 13:08:13Z jakub $
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
class GantryGizmoWooCommerce extends GantryGizmo {

    var $_name = 'woocommerce';

    function isEnabled() {
        return true;
    }

    /**
     *     Copyright (C) 2012 Jakub Baran & Hassan Derakhshandeh
     *      Contains parts of code from the WooCommerce plugin by WooThemes
     */

    function admin_init() {

        /**
         * WooCommerce Compatibility
         */

        add_theme_support( 'woocommerce' );
    }

    function init() {
        /** @global $gantry Gantry */
        global $gantry;

        /**
         *     WooCommerce Compatibility
         */

        if ( defined( 'WOOCOMMERCE_VERSION' ) ) {
            // Set the number of the items on the WooCommerce pages
            if ( $gantry->get( 'woocommerce-items-count' ) != '' ) {
                $shop_items_count = $gantry->get( 'woocommerce-items-count' );
            } else if ( $gantry->get( 'archive-count' ) != '' ) {
                $shop_items_count = $gantry->get( 'archive-count' );
            } else if ( $gantry->get( 'blog-count' ) != '' ) {
                $shop_items_count = $gantry->get( 'blog-count' );
            } else {
                $shop_items_count = get_option( 'posts_per_page', '10' );
            }

            add_filter( 'loop_shop_per_page', create_function( '$cols', "return $shop_items_count;" ) );
            add_action( 'wp_enqueue_scripts', array( &$this, 'wc_cart_variation_script' ) );
            remove_filter( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
            add_theme_support( 'woocommerce' );
        }

    }

    /**
     *     WooCommerce - Fix for Add-To-Cart Variations
     */

    function wc_cart_variation_script() {
        global $gantry, $woocommerce;

        if( defined( 'WOOCOMMERCE_VERSION' ) && is_woocommerce() ) {
            if( is_single() && get_post_type() == 'product' ) {
                wp_enqueue_script( 'wc-add-to-cart-variation', $woocommerce->plugin_url() . '/assets/js/frontend/add-to-cart-variation.js', array('jquery'), '1.6', true );
            }
        }
    }

}