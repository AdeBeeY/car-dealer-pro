<?php
/**
 * Plugin Name: Car Dealer Pro
 * Plugin URI: https://mydevplug.com.ng/
 * Description: A professional car dealer plugin for WordPress.
 * Version: 1.0.0
 * Author: myDevPlug
 * Author URI: https://mydevplug.com.ng/
 * Text Domain: car-dealer-pro
 * Domain Path: /languages
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Define CAR_DEALER_PRO_PLUGIN_DIR
if ( ! defined( 'CAR_DEALER_PRO_PLUGIN_DIR' ) ) {
    define( 'CAR_DEALER_PRO_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}

// Define CAR_DEALER_PRO_PLUGIN_URL
if ( ! defined( 'CAR_DEALER_PRO_PLUGIN_URL' ) ) {
    define( 'CAR_DEALER_PRO_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}

/**
 * The main plugin class.
 */
final class Car_Dealer_Pro {

    /**
     * The single instance of the class.
     *
     * @var Car_Dealer_Pro
     * @since 1.0.0
     */
    protected static $_instance = null;

    /**
     * Main Car_Dealer_Pro Instance.
     *
     * Ensures only one instance of Car_Dealer_Pro is loaded or can be loaded.
     *
     * @since 1.0.0
     * @static
     * @return Car_Dealer_Pro - Main instance.
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Car_Dealer_Pro constructor.
     *
     * Private constructor to prevent direct instantiation.
     */
    private function __construct() {
        $this->define_constants();
        $this->includes();
        $this->init_hooks();
    }

    /**
     * Cloning is forbidden.
     *
     * @since 1.0.0
     */
    public function __clone() {
        _doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'car-dealer-pro' ), '1.0.0' );
    }

    /**
     * Unserializing instances of this class is forbidden.
     *
     * @since 1.0.0
     */
    public function __wakeup() {
        _doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'car-dealer-pro' ), '1.0.0' );
    }

    /**
     * Define constants needed for the plugin.
     */
    private function define_constants() {
        // No additional constants defined here currently, but this method is good for future expansion.
    }

    /**
     * Include required core files.
     */
    private function includes() {
        // Core includes
        include_once CAR_DEALER_PRO_PLUGIN_DIR . 'includes/class-car-dealer-pro-cpt.php';
        include_once CAR_DEALER_PRO_PLUGIN_DIR . 'includes/class-car-dealer-pro-fields.php';
        include_once CAR_DEALER_PRO_PLUGIN_DIR . 'includes/class-car-dealer-pro-assets.php';
        include_once CAR_DEALER_PRO_PLUGIN_DIR . 'includes/class-car-dealer-pro-shortcodes.php';
        include_once CAR_DEALER_PRO_PLUGIN_DIR . 'includes/class-car-dealer-pro-ajax.php';
    }

    /**
     * Hook into actions and filters.
     */
    private function init_hooks() {
        add_action( 'plugins_loaded', array( $this, 'on_plugins_loaded' ) );
        register_activation_hook( __FILE__, array( $this, 'activate' ) );
        register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );
        add_filter( 'template_include', array( $this, 'template_loader' ) );
    }

    /**
     * Runs on plugins loaded.
     */
    public function on_plugins_loaded() {
        // Initialize other components here as needed
        Car_Dealer_Pro_CPT::instance();
        Car_Dealer_Pro_Fields::instance();
        Car_Dealer_Pro_Assets::instance();
        Car_Dealer_Pro_Shortcodes::instance();
        Car_Dealer_Pro_Ajax::instance();
    }

    /**
     * Activation hook.
     */
    public function activate() {
        // Ensure CPT is registered on activation
        Car_Dealer_Pro_CPT::instance()->register_cpt();
        // Flush rewrite rules to make sure our custom post type URLs work.
        flush_rewrite_rules();
    }

    /**
     * Deactivation hook.
     */
    public function deactivate() {
        flush_rewrite_rules(); // Flush rewrite rules on deactivation too.
    }

    /**
     * Load custom templates for CPT.
     *
     * @param string $template The path to the template.
     * @return string The path to the custom template or the original.
     */
    public function template_loader( $template ) {
        if ( is_singular( 'car' ) ) {
            $plugin_template = CAR_DEALER_PRO_PLUGIN_DIR . 'templates/single-car.php';
            if ( file_exists( $plugin_template ) ) {
                return $plugin_template;
            }
        } elseif ( is_post_type_archive( 'car' ) ) {
            $plugin_template = CAR_DEALER_PRO_PLUGIN_DIR . 'templates/archive-car.php';
            if ( file_exists( $plugin_template ) ) {
                return $plugin_template;
            }
        }
        return $template;
    }
}

/**
 * Initialize the main plugin class.
 *
 * @return Car_Dealer_Pro
 */
function CAR_DEALER_PRO() {
    return Car_Dealer_Pro::instance();
}

// Global for accessing the main instance.
$GLOBALS['car_dealer_pro'] = CAR_DEALER_PRO();