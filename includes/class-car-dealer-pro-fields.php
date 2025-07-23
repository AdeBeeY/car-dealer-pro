<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Handles custom fields for the 'Car' Custom Post Type.
 */
class Car_Dealer_Pro_Fields {

    /**
     * The single instance of the class.
     *
     * @var Car_Dealer_Pro_Fields
     * @since 1.0.0
     */
    protected static $_instance = null;

    /**
     * Main Car_Dealer_Pro_Fields Instance.
     *
     * Ensures only one instance of Car_Dealer_Pro_Fields is loaded or can be loaded.
     *
     * @since 1.0.0
     * @static
     * @return Car_Dealer_Pro_Fields - Main instance.
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Car_Dealer_Pro_Fields constructor.
     *
     * Private constructor to prevent direct instantiation.
     */
    private function __construct() {
        add_action( 'add_meta_boxes', array( $this, 'add_car_meta_boxes' ) );
        add_action( 'save_post', array( $this, 'save_car_meta_data' ) );
    }

    /**
     * Add meta boxes for car details.
     */
    public function add_car_meta_boxes() {
        add_meta_box(
            'car_details_meta_box',
            __( 'Car Details', 'car-dealer-pro' ),
            array( $this, 'render_car_details_meta_box' ),
            'car',
            'normal',
            'high'
        );

        add_meta_box(
            'car_gallery_meta_box',
            __( 'Car Image Gallery', 'car-dealer-pro' ),
            array( $this, 'render_car_gallery_meta_box' ),
            'car',
            'normal',
            'high'
        );
    }

    /**
     * Render the car details meta box.
     *
     * @param WP_Post $post The post object.
     */
    public function render_car_details_meta_box( $post ) {
        wp_nonce_field( 'car_dealer_pro_save_meta', 'car_dealer_pro_meta_nonce' );

        $make         = get_post_meta( $post->ID, '_car_make', true );
        $model        = get_post_meta( $post->ID, '_car_model', true );
        $year         = get_post_meta( $post->ID, '_car_year', true );
        $price        = get_post_meta( $post->ID, '_car_price', true );
        $car_status   = get_post_meta( $post->ID, '_car_status', true );
        $transmission = get_post_meta( $post->ID, '_car_transmission', true );
        $fuel_type    = get_post_meta( $post->ID, '_car_fuel_type', true );
        $mileage      = get_post_meta( $post->ID, '_car_mileage', true );
        $color        = get_post_meta( $post->ID, '_car_color', true );
        $vin_number   = get_post_meta( $post->ID, '_car_vin_number', true );

        ?>
        <table class="form-table">
            <tbody>
                <tr>
                    <th><label for="car_make"><?php esc_html_e( 'Make', 'car-dealer-pro' ); ?></label></th>
                    <td><input type="text" id="car_make" name="car_make" value="<?php echo esc_attr( $make ); ?>" class="regular-text" /></td>
                </tr>
                <tr>
                    <th><label for="car_model"><?php esc_html_e( 'Model', 'car-dealer-pro' ); ?></label></th>
                    <td><input type="text" id="car_model" name="car_model" value="<?php echo esc_attr( $model ); ?>" class="regular-text" /></td>
                </tr>
                <tr>
                    <th><label for="car_year"><?php esc_html_e( 'Year', 'car-dealer-pro' ); ?></label></th>
                    <td><input type="number" id="car_year" name="car_year" value="<?php echo esc_attr( $year ); ?>" class="regular-text" min="1900" max="<?php echo date('Y') + 1; ?>" /></td>
                </tr>
                <tr>
                    <th><label for="car_price"><?php esc_html_e( 'Price (NGN)', 'car-dealer-pro' ); ?></label></th>
                    <td><input type="number" id="car_price" name="car_price" value="<?php echo esc_attr( $price ); ?>" class="regular-text" step="0.01" min="0" /></td>
                </tr>
                <tr>
                    <th><label for="car_status"><?php esc_html_e( 'Status', 'car-dealer-pro' ); ?></label></th>
                    <td>
                        <select id="car_status" name="car_status">
                            <option value="Available" <?php selected( $car_status, 'Available' ); ?>><?php esc_html_e( 'Available', 'car-dealer-pro' ); ?></option>
                            <option value="Sold" <?php selected( $car_status, 'Sold' ); ?>><?php esc_html_e( 'Sold', 'car-dealer-pro' ); ?></option>
                            <option value="Pending" <?php selected( $car_status, 'Pending' ); ?>><?php esc_html_e( 'Pending', 'car-dealer-pro' ); ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th><label for="car_transmission"><?php esc_html_e( 'Transmission', 'car-dealer-pro' ); ?></label></th>
                    <td>
                        <select id="car_transmission" name="car_transmission">
                            <option value="Automatic" <?php selected( $transmission, 'Automatic' ); ?>><?php esc_html_e( 'Automatic', 'car-dealer-pro' ); ?></option>
                            <option value="Manual" <?php selected( $transmission, 'Manual' ); ?>><?php esc_html_e( 'Manual', 'car-dealer-pro' ); ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th><label for="car_fuel_type"><?php esc_html_e( 'Fuel Type', 'car-dealer-pro' ); ?></label></th>
                    <td>
                        <select id="car_fuel_type" name="car_fuel_type">
                            <option value="Petrol" <?php selected( $fuel_type, 'Petrol' ); ?>><?php esc_html_e( 'Petrol', 'car-dealer-pro' ); ?></option>
                            <option value="Diesel" <?php selected( $fuel_type, 'Diesel' ); ?>><?php esc_html_e( 'Diesel', 'car-dealer-pro' ); ?></option>
                            <option value="Electric" <?php selected( $fuel_type, 'Electric' ); ?>><?php esc_html_e( 'Electric', 'car-dealer-pro' ); ?></option>
                            <option value="Hybrid" <?php selected( $fuel_type, 'Hybrid' ); ?>><?php esc_html_e( 'Hybrid', 'car-dealer-pro' ); ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th><label for="car_mileage"><?php esc_html_e( 'Mileage (km)', 'car-dealer-pro' ); ?></label></th>
                    <td><input type="number" id="car_mileage" name="car_mileage" value="<?php echo esc_attr( $mileage ); ?>" class="regular-text" min="0" /></td>
                </tr>
                <tr>
                    <th><label for="car_color"><?php esc_html_e( 'Color', 'car-dealer-pro' ); ?></label></th>
                    <td><input type="text" id="car_color" name="car_color" value="<?php echo esc_attr( $color ); ?>" class="regular-text" /></td>
                </tr>
                <tr>
                    <th><label for="car_vin_number"><?php esc_html_e( 'VIN Number', 'car-dealer-pro' ); ?></label></th>
                    <td><input type="text" id="car_vin_number" name="car_vin_number" value="<?php echo esc_attr( $vin_number ); ?>" class="regular-text" /></td>
                </tr>
            </tbody>
        </table>
        <?php
    }

    /**
     * Render the car image gallery meta box.
     *
     * @param WP_Post $post The post object.
     */
    public function render_car_gallery_meta_box( $post ) {
        wp_nonce_field( 'car_dealer_pro_save_gallery', 'car_dealer_pro_gallery_nonce' );
        $gallery_ids = get_post_meta( $post->ID, '_car_gallery_ids', true );
        $gallery_ids = is_array( $gallery_ids ) ? $gallery_ids : ( ! empty( $gallery_ids ) ? explode( ',', $gallery_ids ) : array() );
        ?>
        <div id="car_gallery_container">
            <ul class="car-gallery-images">
                <?php if ( ! empty( $gallery_ids ) ) : ?>
                    <?php foreach ( $gallery_ids as $image_id ) :
                        $image_url = wp_get_attachment_image_src( $image_id, 'thumbnail' );
                        if ( $image_url ) : ?>
                            <li data-id="<?php echo esc_attr( $image_id ); ?>">
                                <img src="<?php echo esc_url( $image_url[0] ); ?>" />
                                <a href="#" class="car-gallery-remove-image dashicons dashicons-no-alt"></a>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
            <input type="hidden" id="car_gallery_ids" name="car_gallery_ids" value="<?php echo esc_attr( implode( ',', $gallery_ids ) ); ?>" />
            <button type="button" class="button button-secondary" id="add_car_gallery_images">
                <?php esc_html_e( 'Add/Manage Gallery Images', 'car-dealer-pro' ); ?>
            </button>
        </div>
        <?php
    }


    /**
     * Save car meta data when the post is saved.
     *
     * @param int $post_id The ID of the post being saved.
     */
    public function save_car_meta_data( $post_id ) {
        // Check if our nonce is set and verified.
        if ( ! isset( $_POST['car_dealer_pro_meta_nonce'] ) || ! wp_verify_nonce( $_POST['car_dealer_pro_meta_nonce'], 'car_dealer_pro_save_meta' ) ) {
            return;
        }

        // Check if gallery nonce is set and verified (if gallery meta box is also present on the save action)
        if ( isset( $_POST['car_dealer_pro_gallery_nonce'] ) && ! wp_verify_nonce( $_POST['car_dealer_pro_gallery_nonce'], 'car_dealer_pro_save_gallery' ) ) {
            return;
        }

        // If this is an autosave, our form has not been submitted, so we don't want to do anything.
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }

        // Check the user's permissions.
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }

        // Sanitize and save custom fields.
        $fields = array(
            'car_make'       => '_car_make',
            'car_model'      => '_car_model',
            'car_year'       => '_car_year',
            'car_price'      => '_car_price',
            'car_status'     => '_car_status',
            'car_transmission' => '_car_transmission',
            'car_fuel_type'  => '_car_fuel_type',
            'car_mileage'    => '_car_mileage',
            'car_color'      => '_car_color',
            'car_vin_number' => '_car_vin_number',
        );

        foreach ( $fields as $input_name => $meta_key ) {
            if ( isset( $_POST[ $input_name ] ) ) {
                $value = sanitize_text_field( wp_unslash( $_POST[ $input_name ] ) );
                update_post_meta( $post_id, $meta_key, $value );
            } else {
                delete_post_meta( $post_id, $meta_key ); // Clean up if field is no longer present (e.g., checkbox)
            }
        }

        // Save gallery image IDs.
        if ( isset( $_POST['car_gallery_ids'] ) ) {
            $gallery_ids = sanitize_text_field( wp_unslash( $_POST['car_gallery_ids'] ) );
            $gallery_array = array_map( 'intval', explode( ',', $gallery_ids ) ); // Ensure integers
            $gallery_array = array_filter( $gallery_array ); // Remove empty values
            update_post_meta( $post_id, '_car_gallery_ids', $gallery_array );
        } else {
            delete_post_meta( $post_id, '_car_gallery_ids' );
        }
    }
}