<?php
/**
 * AJAX class.
 *
 * @since 1.0.9
 *
 * @package Envira_WooCommerce
 * @author  Tim Carr
 */
class Envira_WooCommerce_AJAX {

    /**
     * Holds the class object.
     *
     * @since 1.0.9
     *
     * @var object
     */
    public static $instance;

    /**
     * Path to the file.
     *
     * @since 1.0.9
     *
     * @var string
     */
    public $file = __FILE__;

    /**
     * Primary class constructor.
     *
     * @since 1.0.9
     */
    public function __construct() {

        // Get WooCommerce Add to Cart Form
        add_action( 'wp_ajax_envira_woocommerce_get_add_to_cart_form', array( $this, 'get_add_to_cart_form' ) );
        add_action( 'wp_ajax_nopriv_envira_woocommerce_get_add_to_cart_form', array( $this, 'get_add_to_cart_form' ) );

    }

    /**
     * Returns the WooCommerce Add to Cart form for the specified Gallery
     * and Attachment ID combination.
     *
     * @since 1.0.9
     *
     * @return  string                      WooCommerce Form HTML Markup
     */
    public function get_add_to_cart_form() {

        // Run a security check first
        check_ajax_referer( 'envira-woocommerce-get-add-to-cart-form', 'nonce' );

        // Prepare variables.
        $gallery_id     = (int) $_POST['gallery_id'];
        $attachment_id  = (int) $_POST['attachment_id'];

        // Check we have the required inputs.
        if ( empty( $gallery_id ) ) {
            wp_send_json_error( __( 'No gallery ID specified.', 'envira-woocommerce' ) );
        }
        if ( empty( $attachment_id ) ) {
            wp_send_json_error( __( 'No attachment ID specified.', 'envira-woocommerce' ) );
        }

        // Get gallery
        $data = Envira_Gallery::get_instance()->get_gallery( $gallery_id );

        // Get HTML, using the shortcode class' add to cart function.
        $woocommerce_form = Envira_WooCommerce_Shortcode::get_instance()->output_add_to_cart( '', $id, $data['gallery'][ $attachment_id ], $data, 0, true );

        // Return results
        wp_send_json_success( array(
            'woocommerce_form' => $woocommerce_form,
        ) );

    }

    /**
     * Returns the singleton instance of the class.
     *
     * @since 1.0.9
     *
     * @return object The Envira_WooCommerce_AJAX object.
     */
    public static function get_instance() {

        if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Envira_WooCommerce_AJAX ) ) {
            self::$instance = new Envira_WooCommerce_AJAX();
        }

        return self::$instance;

    }

}

// Load the AJAX class.
$envira_woocommerce_ajax = Envira_WooCommerce_AJAX::get_instance();