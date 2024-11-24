<?php
/*
Plugin Name: Yosta show GTIN
Description: Enable invoice payment method for trusted costumers
Version: 1.3
Author: Joost Abrahams
Author URI: https://mantablog.nl
GitHub Plugin URI: https://github.com/joost-abrahams/yosta-show-gtin/
License: GPLv3
Requires Plugins: woocommerce
*/

// Exit if accessed directly
defined( 'ABSPATH' ) or die;

//declare complianz with consent level API
$plugin = plugin_basename( __FILE__ );
add_filter( "wp_consent_api_registered_{$plugin}", '__return_true' );

// Happy hacking

add_action( 'woocommerce_product_meta_start', 'yosta_display_gtin' );

if ( ! function_exists( 'yosta_display_gtin' ) ) {
    /**
     * Display GTIN.
     */
    function yosta_display_gtin() {
        if ( '' !== ( $_global_unique_id = get_post_meta( get_the_ID(), '_global_unique_id', true ) ) ) {
            printf( '<span class="gtin_wrapper">%s: <span class="gtin">%s</span></span>',
                esc_html__( 'GTIN', 'yosta-show-gtin' ), $mpn );
        }
    }
}

add_filter( 'woocommerce_available_variation', 'yosta_display_gtin_variation', 10, 3 );

if ( ! function_exists( 'yosta_display_gtin_variation' ) ) {
    /**
     * Display GTIN in variation description.
     */
    function yosta_display_gtin_variation( $args, $product, $variation ) {
        if ( '' !== ( $_global_unique_id = $variation->get_meta( '_global_unique_id' ) ) ) {
            $args['variation_description'] .= sprintf( '<span class="gtin_wrapper">%s: <span class="gtin">%s</span></span>',
                esc_html__( 'GTIN', 'yosta-show-gtin' ), $_global_unique_id );
        }
        return $args;
    }
}
