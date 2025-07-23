<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Handles AJAX requests for car filtering.
 */
class Car_Dealer_Pro_Ajax {

    /**
     * The single instance of the class.
     *
     * @var Car_Dealer_Pro_Ajax
     * @since 1.0.0
     */
    protected static $_instance = null;

    /**
     * Main Car_Dealer_Pro_Ajax Instance.
     *
     * Ensures only one instance of Car_Dealer_Pro_Ajax is loaded or can be loaded.
     *
     * @since 1.0.0
     * @static
     * @return Car_Dealer_Pro_Ajax - Main instance.
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Car_Dealer_Pro_Ajax constructor.
     *
     * Private constructor to prevent direct instantiation.
     */
    private function __construct() {
        add_action( 'wp_ajax_car_dealer_pro_filter_cars', array( $this, 'filter_cars' ) );
        add_action( 'wp_ajax_nopriv_car_dealer_pro_filter_cars', array( $this, 'filter_cars' ) );
    }

    /**
     * Filter cars based on AJAX request.
     */
    public function filter_cars() {
        check_ajax_referer( 'car_dealer_pro_nonce', 'nonce' );

        $filters      = isset( $_POST['filters'] ) ? map_deep( wp_unslash( $_POST['filters'] ), 'sanitize_text_field' ) : array();
        $search_query = isset( $_POST['search_query'] ) ? sanitize_text_field( wp_unslash( $_POST['search_query'] ) ) : '';

        $args = array(
            'post_type'      => 'car',
            'posts_per_page' => -1, // Adjust as needed, -1 for all
            'post_status'    => 'publish',
            'meta_query'     => array(),
            's'              => $search_query, // Handle general search query
        );

        // Default to 'Available' if no status filter is provided
        $car_status = isset( $filters['car_status'] ) ? $filters['car_status'] : 'Available';
        if ( $car_status && $car_status !== 'any' ) {
            $args['meta_query'][] = array(
                'key'     => '_car_status',
                'value'   => $car_status,
                'compare' => '=',
            );
        }

        // Add filters from the AJAX request
        foreach ( $filters as $key => $value ) {
            if ( empty( $value ) || $value === 'any' ) {
                continue;
            }

            switch ( $key ) {
                case 'car_make':
                    $args['meta_query'][] = array(
                        'key'     => '_car_make',
                        'value'   => $value,
                        'compare' => '=',
                    );
                    break;
                case 'car_model':
                    $args['meta_query'][] = array(
                        'key'     => '_car_model',
                        'value'   => $value,
                        'compare' => '=',
                    );
                    break;
                case 'car_year':
                    $args['meta_query'][] = array(
                        'key'     => '_car_year',
                        'value'   => $value,
                        'compare' => '=',
                    );
                    break;
                case 'car_transmission':
                    $args['meta_query'][] = array(
                        'key'     => '_car_transmission',
                        'value'   => $value,
                        'compare' => '=',
                    );
                    break;
                case 'car_fuel_type':
                    $args['meta_query'][] = array(
                        'key'     => '_car_fuel_type',
                        'value'   => $value,
                        'compare' => '=',
                    );
                    break;
                case 'car_min_price':
                    $args['meta_query'][] = array(
                        'key'     => '_car_price',
                        'value'   => $value,
                        'type'    => 'NUMERIC',
                        'compare' => '>=',
                    );
                    break;
                case 'car_max_price':
                    $args['meta_query'][] = array(
                        'key'     => '_car_price',
                        'value'   => $value,
                        'type'    => 'NUMERIC',
                        'compare' => '<=',
                    );
                    break;
                case 'car_min_mileage':
                    $args['meta_query'][] = array(
                        'key'     => '_car_mileage',
                        'value'   => $value,
                        'type'    => 'NUMERIC',
                        'compare' => '>=',
                    );
                    break;
                case 'car_max_mileage':
                    $args['meta_query'][] = array(
                        'key'     => '_car_mileage',
                        'value'   => $value,
                        'type'    => 'NUMERIC',
                        'compare' => '<=',
                    );
                    break;
                case 'car_color':
                    $args['meta_query'][] = array(
                        'key'     => '_car_color',
                        'value'   => $value,
                        'compare' => '=',
                    );
                    break;
                // Add more cases for other filterable fields as needed
            }
        }

        // If there are multiple meta queries, ensure they are combined with 'AND'
        if ( count( $args['meta_query'] ) > 1 ) {
            $args['meta_query']['relation'] = 'AND';
        }

        $query = new WP_Query( $args );
        ob_start();

        if ( $query->have_posts() ) {
            while ( $query->have_posts() ) {
                $query->the_post();
                include CAR_DEALER_PRO_PLUGIN_DIR . 'templates/parts/car-card.php';
            }
        } else {
            ?>
            <div class="no-results">
                <p><?php esc_html_e( 'No cars found matching your criteria.', 'car-dealer-pro' ); ?></p>
            </div>
            <?php
        }

        wp_reset_postdata();

        $html = ob_get_clean();

        wp_send_json_success( array( 'html' => $html ) );
    }
}