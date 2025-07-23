<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Handles shortcode functionality.
 */
class Car_Dealer_Pro_Shortcodes {

    /**
     * The single instance of the class.
     *
     * @var Car_Dealer_Pro_Shortcodes
     * @since 1.0.0
     */
    protected static $_instance = null;

    /**
     * Main Car_Dealer_Pro_Shortcodes Instance.
     *
     * Ensures only one instance of Car_Dealer_Pro_Shortcodes is loaded or can be loaded.
     *
     * @since 1.0.0
     * @static
     * @return Car_Dealer_Pro_Shortcodes - Main instance.
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Car_Dealer_Pro_Shortcodes constructor.
     *
     * Private constructor to prevent direct instantiation.
     */
    private function __construct() {
        add_shortcode( 'car_listings', array( $this, 'car_listings_shortcode' ) );
    }

    /**
     * Shortcode to display car listings.
     *
     * @param array $atts Shortcode attributes.
     * @return string HTML output.
     */
    public function car_listings_shortcode( $atts ) {
        // Enqueue public assets only when shortcode is used.
        Car_Dealer_Pro_Assets::instance()->enqueue_public_assets();

        $atts = shortcode_atts(
            array(
                'display'        => 'grid', // 'grid' or 'list'
                'posts_per_page' => -1,    // -1 for all, or a number
                'status'         => 'Available', // 'Available', 'Sold', or 'any'
                'show_filters'   => true,   // New attribute to control filter visibility
            ),
            $atts,
            'car_listings'
        );

        ob_start();

        // Pass the attributes to the template using set_query_var
        // This makes them accessible within archive-car.php via get_query_var()
        set_query_var( 'car_listings_atts', $atts );

        // Include the archive template directly.
        // This simulates the main archive page environment for consistency.
        include CAR_DEALER_PRO_PLUGIN_DIR . 'templates/archive-car.php';

        return ob_get_clean(); // Crucially, return the buffered output
    }
}