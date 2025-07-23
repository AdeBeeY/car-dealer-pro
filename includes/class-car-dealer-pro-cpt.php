<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Registers the 'Car' Custom Post Type.
 */
class Car_Dealer_Pro_CPT {

    /**
     * The single instance of the class.
     *
     * @var Car_Dealer_Pro_CPT
     * @since 1.0.0
     */
    protected static $_instance = null;

    /**
     * Main Car_Dealer_Pro_CPT Instance.
     *
     * Ensures only one instance of Car_Dealer_Pro_CPT is loaded or can be loaded.
     *
     * @since 1.0.0
     * @static
     * @return Car_Dealer_Pro_CPT - Main instance.
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Car_Dealer_Pro_CPT constructor.
     *
     * Private constructor to prevent direct instantiation.
     */
    private function __construct() {
        add_action( 'init', array( $this, 'register_cpt' ) );
        add_filter( 'template_include', array( $this, 'load_plugin_templates' ) );
    }

    /**
     * Register the Custom Post Type.
     */
    public function register_cpt() {
        $labels = array(
            'name'                  => _x( 'Cars', 'Post Type General Name', 'car-dealer-pro' ),
            'singular_name'         => _x( 'Car', 'Post Type Singular Name', 'car-dealer-pro' ),
            'menu_name'             => __( 'Car Listings', 'car-dealer-pro' ),
            'name_admin_bar'        => __( 'Car', 'car-dealer-pro' ),
            'archives'              => __( 'Car Archives', 'car-dealer-pro' ),
            'attributes'            => __( 'Car Attributes', 'car-dealer-pro' ),
            'parent_item_colon'     => __( 'Parent Car:', 'car-dealer-pro' ),
            'all_items'             => __( 'All Cars', 'car-dealer-pro' ),
            'add_new_item'          => __( 'Add New Car', 'car-dealer-pro' ),
            'add_new'               => __( 'Add New', 'car-dealer-pro' ),
            'new_item'              => __( 'New Car', 'car-dealer-pro' ),
            'edit_item'             => __( 'Edit Car', 'car-dealer-pro' ),
            'update_item'           => __( 'Update Car', 'car-dealer-pro' ),
            'view_item'             => __( 'View Car', 'car-dealer-pro' ),
            'view_items'            => __( 'View Cars', 'car-dealer-pro' ),
            'search_items'          => __( 'Search Car', 'car-dealer-pro' ),
            'not_found'             => __( 'Not found', 'car-dealer-pro' ),
            'not_found_in_trash'    => __( 'Not found in Trash', 'car-dealer-pro' ),
            'featured_image'        => __( 'Car Image', 'car-dealer-pro' ),
            'set_featured_image'    => __( 'Set car image', 'car-dealer-pro' ),
            'remove_featured_image' => __( 'Remove car image', 'car-dealer-pro' ),
            'use_featured_image'    => __( 'Use as car image', 'car-dealer-pro' ),
            'insert_into_item'      => __( 'Insert into car', 'car-dealer-pro' ),
            'uploaded_to_this_item' => __( 'Uploaded to this car', 'car-dealer-pro' ),
            'items_list'            => __( 'Cars list', 'car-dealer-pro' ),
            'items_list_navigation' => __( 'Cars list navigation', 'car-dealer-pro' ),
            'filter_items_list'     => __( 'Filter cars list', 'car-dealer-pro' ),
        );
        $args = array(
            'label'                 => __( 'Car', 'car-dealer-pro' ),
            'description'           => __( 'Car listings for your dealership.', 'car-dealer-pro' ),
            'labels'                => $labels,
            'supports'              => array( 'title', 'editor', 'thumbnail', 'comments' ),
            'taxonomies'            => array(), // Add custom taxonomies here if needed
            'hierarchical'          => false,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_position'         => 5,
            'menu_icon'             => 'dashicons-car', // Dashicon for car
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => true, // Enable archive page
            'exclude_from_search'   => false,
            'publicly_queryable'    => true,
            'capability_type'       => 'post',
            'show_in_rest'          => true, // Enable for Gutenberg editor
            'rewrite'               => array( 'slug' => 'car', 'with_front' => false ), // Custom slug for single and archive
        );
        register_post_type( 'car', $args );
    }

    /**
     * Load plugin templates for CPT.
     * This filter is technically also handled in the main plugin file's template_loader,
     * but leaving it here for completeness or if it were to handle other CPT specific template overrides.
     * The main plugin's template_loader is the primary one being used.
     */
    public function load_plugin_templates( $template ) {
        // This method exists to show it's possible to handle templates here,
        // but the primary template loading for 'car' post type is now in Car_Dealer_Pro::template_loader().
        // If you were to have different CPTs handled by this class, you might use this method.
        return $template;
    }
}