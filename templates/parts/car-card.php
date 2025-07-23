<?php
/**
 * Template part for displaying a single car card.
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Ensure the global post object is set, as get_the_ID() and get_the_title() rely on it.
// This is usually handled by the_post() in the loop, but it's good to be aware.
global $post;
setup_postdata( $post ); // Explicitly set up post data if not already within a fresh loop.

$car_id         = get_the_ID();
// All meta keys here should exactly match the _car_ fields saved in class-car-dealer-pro-fields.php
$make           = get_post_meta( $car_id, '_car_make', true );
$model          = get_post_meta( $car_id, '_car_model', true );
$year           = get_post_meta( $car_id, '_car_year', true );
$price          = get_post_meta( $car_id, '_car_price', true );
$car_status     = get_post_meta( $car_id, '_car_status', true );
$transmission   = get_post_meta( $car_id, '_car_transmission', true );
$fuel_type      = get_post_meta( $car_id, '_car_fuel_type', true );
$mileage        = get_post_meta( $car_id, '_car_mileage', true );
$color          = get_post_meta( $car_id, '_car_color', true );

$thumbnail_id = get_post_thumbnail_id( $car_id );
$image_url    = $thumbnail_id ? wp_get_attachment_image_url( $thumbnail_id, 'medium' ) : CAR_DEALER_PRO_PLUGIN_URL . 'assets/images/placeholder.jpg'; // Placeholder image
?>

<div class="car-dealer-pro-car-card <?php echo esc_attr( strtolower( $car_status ) ); ?>">
    <div class="car-image">
        <a href="<?php the_permalink(); ?>">
            <img src="<?php echo esc_url( $image_url ); ?>" alt="<?php the_title_attribute(); ?>">
        </a>
    </div>
    <div class="car-details">
        <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
        <p class="car-price">
            <?php
            // Only display price if it's set
            if ( ! empty( $price ) ) {
                echo esc_html( 'NGN ' . number_format( $price, 2 ) );
            } else {
                echo esc_html__( 'Price on request', 'car-dealer-pro' );
            }
            ?>
        </p>
        <ul class="car-specs">
            <?php if ( ! empty( $make ) ) : ?><li><strong><?php esc_html_e( 'Make:', 'car-dealer-pro' ); ?></strong> <?php echo esc_html( $make ); ?></li><?php endif; ?>
            <?php if ( ! empty( $model ) ) : ?><li><strong><?php esc_html_e( 'Model:', 'car-dealer-pro' ); ?></strong> <?php echo esc_html( $model ); ?></li><?php endif; ?>
            <?php if ( ! empty( $year ) ) : ?><li><strong><?php esc_html_e( 'Year:', 'car-dealer-pro' ); ?></strong> <?php echo esc_html( $year ); ?></li><?php endif; ?>
            <?php if ( ! empty( $transmission ) ) : ?><li><strong><?php esc_html_e( 'Transmission:', 'car-dealer-pro' ); ?></strong> <?php echo esc_html( $transmission ); ?></li><?php endif; ?>
            <?php if ( ! empty( $fuel_type ) ) : ?><li><strong><?php esc_html_e( 'Fuel:', 'car-dealer-pro' ); ?></strong> <?php echo esc_html( $fuel_type ); ?></li><?php endif; ?>
            <?php if ( ! empty( $mileage ) ) : ?><li><strong><?php esc_html_e( 'Mileage:', 'car-dealer-pro' ); ?></strong> <?php echo esc_html( number_format( $mileage ) . ' km' ); ?></li><?php endif; ?>
            <?php if ( ! empty( $color ) ) : ?><li><strong><?php esc_html_e( 'Color:', 'car-dealer-pro' ); ?></strong> <?php echo esc_html( $color ); ?></li><?php endif; ?>
        </ul>
        <?php if ( ! empty( $car_status ) ) : ?>
        <div class="car-status-badge <?php echo esc_attr( strtolower( $car_status ) ); ?>">
            <?php echo esc_html( $car_status ); ?>
        </div>
        <?php endif; ?>
        <a href="<?php the_permalink(); ?>" class="view-details-button cta-button"><?php esc_html_e( 'View Details', 'car-dealer-pro' ); ?></a>
    </div>
</div>