<?php
/**
 * The template for displaying a single car.
 * This template can be overridden by copying it to yourtheme/car-dealer-pro/single-car.php.
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header(); // Loads your theme's header.php

while ( have_posts() ) : the_post();
    $car_id         = get_the_ID();
    $make           = get_post_meta( $car_id, '_car_make', true );
    $model          = get_post_meta( $car_id, '_car_model', true );
    $year           = get_post_meta( $car_id, '_car_year', true );
    $price          = get_post_meta( $car_id, '_car_price', true );
    $car_status     = get_post_meta( $car_id, '_car_status', true );
    $transmission   = get_post_meta( $car_id, '_car_transmission', true );
    $fuel_type      = get_post_meta( $car_id, '_car_fuel_type', true );
    $mileage        = get_post_meta( $car_id, '_car_mileage', true );
    $color          = get_post_meta( $car_id, '_car_color', true );
    $vin_number     = get_post_meta( $car_id, '_car_vin_number', true );
    $gallery_ids    = get_post_meta( $car_id, '_car_gallery_ids', true );
    $gallery_ids    = is_array( $gallery_ids ) ? $gallery_ids : ( ! empty( $gallery_ids ) ? explode( ',', $gallery_ids ) : array() );

    // Add featured image to gallery if it exists and not already in gallery
    $featured_image_id = get_post_thumbnail_id( $car_id );
    if ( $featured_image_id && ! in_array( $featured_image_id, $gallery_ids ) ) {
        array_unshift( $gallery_ids, $featured_image_id ); // Add to beginning
    }

    ?>

    <div class="car-dealer-pro-container">
        <div class="single-car-container">
            <header class="single-car-header">
                <h1><?php the_title(); ?></h1>
                <?php if ( ! empty( $price ) ) : ?>
                    <p class="single-car-price">
                        <?php echo esc_html( 'NGN ' . number_format( $price, 2 ) ); ?>
                    </p>
                <?php endif; ?>
                <?php if ( ! empty( $car_status ) ) : ?>
                    <div class="car-status-badge <?php echo esc_attr( strtolower( $car_status ) ); ?>">
                        <?php echo esc_html( $car_status ); ?>
                    </div>
                <?php endif; ?>
            </header>

            <div class="single-car-gallery">
                <div class="main-image">
                    <?php
                    $first_image_url = CAR_DEALER_PRO_PLUGIN_URL . 'assets/images/placeholder.jpg';
                    if ( ! empty( $gallery_ids ) ) {
                        $first_image_url = wp_get_attachment_image_url( $gallery_ids[0], 'large' );
                        if ( ! $first_image_url ) { // Fallback if large size doesn't exist
                            $first_image_url = wp_get_attachment_image_url( $gallery_ids[0], 'full' );
                        }
                    }
                    ?>
                    <img src="<?php echo esc_url( $first_image_url ); ?>" alt="<?php the_title_attribute(); ?>">
                </div>
                <?php if ( count( $gallery_ids ) > 0 ) : ?>
                    <div class="thumbnails">
                        <?php foreach ( $gallery_ids as $image_id ) :
                            $thumbnail_url = wp_get_attachment_image_url( $image_id, 'thumbnail' );
                            $full_url      = wp_get_attachment_image_url( $image_id, 'large' );
                            if ( ! $full_url ) {
                                $full_url = wp_get_attachment_image_url( $image_id, 'full' );
                            }
                            if ( $thumbnail_url && $full_url ) :
                                ?>
                                <img src="<?php echo esc_url( $thumbnail_url ); ?>" data-full-src="<?php echo esc_url( $full_url ); ?>" alt="<?php echo esc_attr( get_the_title( $image_id ) ); ?>">
                                <?php
                            endif;
                        endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="single-car-details-grid">
                <div class="single-car-details-block">
                    <h2><?php esc_html_e( 'Key Specifications', 'car-dealer-pro' ); ?></h2>
                    <ul>
                        <?php if ( ! empty( $make ) ) : ?><li><strong><?php esc_html_e( 'Make:', 'car-dealer-pro' ); ?></strong> <?php echo esc_html( $make ); ?></li><?php endif; ?>
                        <?php if ( ! empty( $model ) ) : ?><li><strong><?php esc_html_e( 'Model:', 'car-dealer-pro' ); ?></strong> <?php echo esc_html( $model ); ?></li><?php endif; ?>
                        <?php if ( ! empty( $year ) ) : ?><li><strong><?php esc_html_e( 'Year:', 'car-dealer-pro' ); ?></strong> <?php echo esc_html( $year ); ?></li><?php endif; ?>
                        <?php if ( ! empty( $transmission ) ) : ?><li><strong><?php esc_html_e( 'Transmission:', 'car-dealer-pro' ); ?></strong> <?php echo esc_html( $transmission ); ?></li><?php endif; ?>
                        <?php if ( ! empty( $fuel_type ) ) : ?><li><strong><?php esc_html_e( 'Fuel Type:', 'car-dealer-pro' ); ?></strong> <?php echo esc_html( $fuel_type ); ?></li><?php endif; ?>
                    </ul>
                </div>
                <div class="single-car-details-block">
                    <h2><?php esc_html_e( 'General Information', 'car-dealer-pro' ); ?></h2>
                    <ul>
                        <?php if ( ! empty( $mileage ) ) : ?><li><strong><?php esc_html_e( 'Mileage:', 'car-dealer-pro' ); ?></strong> <?php echo esc_html( number_format( $mileage ) . ' km' ); ?></li><?php endif; ?>
                        <?php if ( ! empty( $color ) ) : ?><li><strong><?php esc_html_e( 'Color:', 'car-dealer-pro' ); ?></strong> <?php echo esc_html( $color ); ?></li><?php endif; ?>
                        <?php if ( ! empty( $vin_number ) ) : ?><li><strong><?php esc_html_e( 'VIN:', 'car-dealer-pro' ); ?></strong> <?php echo esc_html( $vin_number ); ?></li><?php endif; ?>
                    </ul>
                </div>
            </div>

            <div class="single-car-description">
                <h2><?php esc_html_e( 'Description', 'car-dealer-pro' ); ?></h2>
                <?php the_content(); ?>
            </div>

            <?php
            // If comments are open or we have at least one comment, load up the comment template.
            if ( comments_open() || get_comments_number() ) :
                comments_template();
            endif;
            ?>

        </div></div><?php
endwhile; // End of the loop.

get_footer(); // Loads your theme's footer.php
?>