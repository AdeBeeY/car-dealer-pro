<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Handles plugin assets (CSS, JS).
 */
class Car_Dealer_Pro_Assets {

    /**
     * The single instance of the class.
     *
     * @var Car_Dealer_Pro_Assets
     * @since 1.0.0
     */
    protected static $_instance = null;

    /**
     * Main Car_Dealer_Pro_Assets Instance.
     *
     * Ensures only one instance of Car_Dealer_Pro_Assets is loaded or can be loaded.
     *
     * @since 1.0.0
     * @static
     * @return Car_Dealer_Pro_Assets - Main instance.
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Car_Dealer_Pro_Assets constructor.
     *
     * Private constructor to prevent direct instantiation.
     */
    private function __construct() {
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_public_assets' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );
    }

    /**
     * Enqueue public-facing scripts and stylesheets.
     */
    public function enqueue_public_assets() {
        wp_enqueue_style( 'car-dealer-pro-public', CAR_DEALER_PRO_PLUGIN_URL . 'assets/css/car-dealer-pro-public.css', array(), '1.0.0' );

        wp_enqueue_script( 'car-dealer-pro-public', CAR_DEALER_PRO_PLUGIN_URL . 'assets/js/car-dealer-pro-public.js', array( 'jquery' ), '1.0.0', true );

        // Localize script for AJAX URL and Nonce
        wp_localize_script( 'car-dealer-pro-public', 'carDealerPro', array(
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'nonce'    => wp_create_nonce( 'car_dealer_pro_nonce' ),
        ) );
    }

    /**
     * Enqueue admin-facing scripts and stylesheets.
     *
     * @param string $hook The current admin page.
     */
    public function enqueue_admin_assets( $hook ) {
        if ( 'post.php' === $hook || 'post-new.php' === $hook || 'edit.php' === $hook && isset($_GET['post_type']) && 'car' === $_GET['post_type'] ) {
            wp_enqueue_style( 'car-dealer-pro-admin', CAR_DEALER_PRO_PLUGIN_URL . 'assets/css/car-dealer-pro-admin.css', array(), '1.0.0' );
            wp_enqueue_script( 'car-dealer-pro-admin', CAR_DEALER_PRO_PLUGIN_URL . 'assets/js/car-dealer-pro-admin.js', array( 'jquery' ), '1.0.0', true );

            // For image gallery: WordPress media uploader scripts
            wp_enqueue_media();
        }
    }
}