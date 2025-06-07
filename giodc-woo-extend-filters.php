<?php
/**
 * Plugin Name: Giodc Woo Extend Filters
 * Plugin URI: https://example.com/plugins/giodc-woo-extend-filters
 * Description: Extends WooCommerce with additional filter options in a sidebar widget (On Sale and In Stock filters).
 * Version: 1.0.0
 * Author: Giovanni De Carlo
 * Author URI: https://giodc.com
 * Text Domain: giodc-woo-extend-filters
 * Domain Path: /languages
 * Requires at least: 5.0
 * Requires PHP: 7.2
 * WC requires at least: 3.0
 * WC tested up to: 8.0
 *
 * @package GIODC_Woo_Extend_Filters
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Define plugin constants
define( 'GIODC_WEF_VERSION', '1.0.0' );
define( 'GIODC_WEF_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'GIODC_WEF_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

/**
 * Check if WooCommerce is active
 */
function giodc_wef_is_woocommerce_active() {
    $active_plugins = (array) get_option( 'active_plugins', array() );
    
    if ( is_multisite() ) {
        $active_plugins = array_merge( $active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
    }
    
    return in_array( 'woocommerce/woocommerce.php', $active_plugins ) || array_key_exists( 'woocommerce/woocommerce.php', $active_plugins );
}

/**
 * Initialize the plugin
 */
function giodc_wef_init() {
    // Load plugin text domain
    load_plugin_textdomain( 'giodc-woo-extend-filters', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
    
    // Include required files
    require_once GIODC_WEF_PLUGIN_DIR . 'includes/class-giodc-wef-widget.php';
    
    // Register widget
    add_action( 'widgets_init', 'giodc_wef_register_widgets' );
    
    // Enqueue styles
    add_action( 'wp_enqueue_scripts', 'giodc_wef_enqueue_scripts' );
}

/**
 * Register widgets
 */
function giodc_wef_register_widgets() {
    register_widget( 'GIODC_WEF_Widget' );
}

/**
 * Plugin activation hook
 */
function giodc_wef_activate() {
    // Activation tasks if needed
}

/**
 * Plugin deactivation hook
 */
function giodc_wef_deactivate() {
    // Deactivation tasks if needed
}

// Check if WooCommerce is active before initializing the plugin
if ( giodc_wef_is_woocommerce_active() ) {
    add_action( 'plugins_loaded', 'giodc_wef_init' );
    register_activation_hook( __FILE__, 'giodc_wef_activate' );
    register_deactivation_hook( __FILE__, 'giodc_wef_deactivate' );
}

/**
 * Add shortcode for the filter widget
 */
function giodc_wef_filters_shortcode( $atts ) {
    ob_start();
    the_widget( 'GIODC_WEF_Widget', array(), array(
        'before_widget' => '<div class="giodc-wef-shortcode-widget">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="giodc-wef-shortcode-title">',
        'after_title'   => '</h4>'
    ) );
    return ob_get_clean();
}
add_shortcode( 'giodc_wef_filters', 'giodc_wef_filters_shortcode' );

/**
 * Enqueue scripts and styles
 */
function giodc_wef_enqueue_scripts() {
    // Only enqueue on WooCommerce pages
    if ( is_shop() || is_product_category() || is_product_tag() || is_product() ) {
        wp_enqueue_style( 'giodc-wef-style', GIODC_WEF_PLUGIN_URL . 'assets/css/giodc-wef-style.css', array(), GIODC_WEF_VERSION );
    }
}
