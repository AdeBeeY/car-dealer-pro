<?php
/**
 * The template for displaying car archive (listings).
 * This template can be overridden by copying it to yourtheme/car-dealer-pro/archive-car.php.
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header(); // Loads your theme's header.php

// Get attributes passed from shortcode, if any
$car_listings_atts = get_query_var( 'car_listings_atts', array() );
$show_filters      = isset( $car_listings_atts['show_filters'] ) ? filter_var( $car_listings_atts['show_filters'], FILTER_VALIDATE_BOOLEAN ) : true;

?>

<div class="car-dealer-pro-container">
    <header class="page-header">
        <h1 class="page-title"><?php post_type_archive_title( 'Car Listings: ' ); ?></h1>
    </header>

    <?php if ( $show_filters ) : // Only display filters if enabled by shortcode or default ?>
        <form id="car-dealer-pro-filter-form" class="car-dealer-pro-filter-form">
            <div>
                <label for="car_search"><?php esc_html_e( 'Search', 'car-dealer-pro' ); ?></label>
                <input type="text" id="car_search" name="car_search" placeholder="<?php esc_attr_e( 'Search by title...', 'car-dealer-pro' ); ?>">
            </div>
            <div>
                <label for="car_make"><?php esc_html_e( 'Make', 'car-dealer-pro' ); ?></label>
                <select id="car_make" name="car_make">
                    <option value="any"><?php esc_html_e( 'Any Make', 'car-dealer-pro' ); ?></option>
                    <?php
                    // Dynamically get makes from existing cars
                    $makes = $wpdb->get_col( "SELECT DISTINCT meta_value FROM {$wpdb->postmeta} WHERE meta_key = '_car_make' ORDER BY meta_value ASC" );
                    foreach ( $makes as $make ) {
                        if ( ! empty( $make ) ) {
                            echo '<option value="' . esc_attr( $make ) . '">' . esc_html( $make ) . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>
            <div>
                <label for="car_year"><?php esc_html_e( 'Year', 'car-dealer-pro' ); ?></label>
                <select id="car_year" name="car_year">
                    <option value="any"><?php esc_html_e( 'Any Year', 'car-dealer-pro' ); ?></option>
                    <?php
                    // Dynamically get years from existing cars
                    $years = $wpdb->get_col( "SELECT DISTINCT meta_value FROM {$wpdb->postmeta} WHERE meta_key = '_car_year' ORDER BY meta_value DESC" );
                    foreach ( $years as $year ) {
                        if ( ! empty( $year ) ) {
                            echo '<option value="' . esc_attr( $year ) . '">' . esc_html( $year ) . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>
            <div>
                <label for="car_min_price"><?php esc_html_e( 'Min Price (NGN)', 'car-dealer-pro' ); ?></label>
                <input type="number" id="car_min_price" name="car_min_price" placeholder="e.g., 500000" min="0">
            </div>
            <div>
                <label for="car_max_price"><?php esc_html_e( 'Max Price (NGN)', 'car-dealer-pro' ); ?></label>
                <input type="number" id="car_max_price" name="car_max_price" placeholder="e.g., 10000000" min="0">
            </div>
            <div>
                <label for="car_status"><?php esc_html_e( 'Status', 'car-dealer-pro' ); ?></label>
                <select id="car_status" name="car_status">
                    <option value="Available" <?php selected( 'Available', isset( $car_listings_atts['status']) ? $car_listings_atts['status'] : 'Available' ); ?>><?php esc_html_e( 'Available', 'car-dealer-pro' ); ?></option>
                    <option value="Sold" <?php selected( 'Sold', isset( $car_listings_atts['status']) ? $car_listings_atts['status'] : '' ); ?>><?php esc_html_e( 'Sold', 'car-dealer-pro' ); ?></option>
                    <option value="any" <?php selected( 'any', isset( $car_listings_atts['status']) ? $car_listings_atts['status'] : '' ); ?>><?php esc_html_e( 'Any Status', 'car-dealer-pro' ); ?></option>
                </select>
            </div>
            <button type="submit"><?php esc_html_e( 'Apply Filters', 'car-dealer-pro' ); ?></button>
            <button type="button" class="car-dealer-pro-reset-button"><?php esc_html_e( 'Reset Filters', 'car-dealer-pro' ); ?></button>
        </form>
    <?php endif; ?>

    <div id="car-listings-results" class="car-listings-grid">
        <?php
        $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

        $args = array(
            'post_type'      => 'car',
            'posts_per_page' => 12, // Default posts per page for archive
            'post_status'    => 'publish',
            'paged'          => $paged,
            'meta_query'     => array(
                array(
                    'key'     => '_car_status',
                    'value'   => 'Available',
                    'compare' => '=',
                ),
            ),
        );

        // Override posts_per_page if set by shortcode
        if ( isset( $car_listings_atts['posts_per_page'] ) ) {
            $args['posts_per_page'] = intval( $car_listings_atts['posts_per_page'] );
        }

        // Override status if set by shortcode
        if ( isset( $car_listings_atts['status'] ) && $car_listings_atts['status'] !== 'any' ) {
            $args['meta_query'] = array(
                array(
                    'key'     => '_car_status',
                    'value'   => sanitize_text_field( $car_listings_atts['status'] ),
                    'compare' => '=',
                ),
            );
        } elseif ( isset( $car_listings_atts['status'] ) && $car_listings_atts['status'] === 'any' ) {
             // If status is 'any', remove the status meta_query
            unset( $args['meta_query'] );
        }


        $car_query = new WP_Query( $args );

        if ( $car_query->have_posts() ) :
            while ( $car_query->have_posts() ) : $car_query->the_post();
                include CAR_DEALER_PRO_PLUGIN_DIR . 'templates/parts/car-card.php';
            endwhile;
        else :
            ?>
            <div class="no-results">
                <p><?php esc_html_e( 'No cars found.', 'car-dealer-pro' ); ?></p>
            </div>
            <?php
        endif;
        wp_reset_postdata();
        ?>
    </div><?php
    // Pagination for the main query
    if ( $car_query->max_num_pages > 1 ) : ?>
        <nav class="car-pagination">
            <?php
            echo paginate_links( array(
                'base'      => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
                'format'    => '?paged=%#%',
                'current'   => max( 1, get_query_var( 'paged' ) ),
                'total'     => $car_query->max_num_pages,
                'prev_text' => __( '&laquo; Previous', 'car-dealer-pro' ),
                'next_text' => __( 'Next &raquo;', 'car-dealer-pro' ),
                'type'      => 'list',
            ) );
            ?>
        </nav>
    <?php endif; ?>

</div><?php
get_footer(); // Loads your theme's footer.php
?>