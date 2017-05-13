<?php
/**
 * AJAX class.
 *
 * @since 1.0.0
 *
 * @package Envira_WooCommerce
 * @author  Tim Carr
 */
class Envira_WooCommerce_Ajax_Admin {

    /**
     * Holds the class object.
     *
     * @since 1.0.0
     *
     * @var object
     */
    public static $instance;

    /**
     * Path to the file.
     *
     * @since 1.0.0
     *
     * @var string
     */
    public $file = __FILE__;

    /**
     * Holds the base class object.
     *
     * @since 1.0.0
     *
     * @var object
     */
    public $base;

    /**
     * Primary class constructor.
     *
     * @since 1.0.0 
     */
    public function __construct() {

        add_action( 'envira_gallery_ajax_save_meta', array( $this, 'save_meta' ), 10, 4 );
        add_action( 'envira_gallery_ajax_save_bulk_meta', array( $this, 'save_meta' ), 10, 4 );

    }

    /**
     * Saves the WooCommerce Product ID, if specified, in the gallery data
     * when editing an image within the modal window.
     *
     * @since 1.0.0
     *
     * @param array $gallery_data 	Gallery Data
     * @param array $meta 		  	Meta
     * @param int $attach_id 		Attachment ID
     * @param int $post_id 			Post (Gallery) ID
     * @return array 				Gallery Data
     */
    public function save_meta( $gallery_data, $meta, $attach_id, $post_id ) {
		
		if ( isset( $meta['woocommerce_product'] ) && ! empty( $meta['woocommerce_product'] ) ) {
        	$gallery_data['gallery'][ $attach_id ]['woocommerce_product'] = absint( $meta['woocommerce_product'] );
    	} else {
            // possibly remove the item from the gallery
            unset( $gallery_data['gallery'][ $attach_id ]['woocommerce_product'] );
        }
        if ( isset( $meta['woocommerce_product_attribute'] ) && ! empty( $meta['woocommerce_product_attribute'] ) ) {
            $gallery_data['gallery'][ $attach_id ]['woocommerce_product_attribute'] = sanitize_text_field( $meta['woocommerce_product_attribute'] );
        } else {
            // possibly remove the item from the gallery
            unset( $gallery_data['gallery'][ $attach_id ]['woocommerce_product_attribute'] );
        }

    	return $gallery_data;

    }

    /**
     * Returns the singleton instance of the class.
     *
     * @since 1.0.0
     *
     * @return object The Envira_WooCommerce_Ajax_Admin object.
     */
    public static function get_instance() {

        if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Envira_WooCommerce_Ajax_Admin ) ) {
            self::$instance = new Envira_WooCommerce_Ajax_Admin();
        }

        return self::$instance;

    }

}

// Load the AJAX class.
$envira_woocommerce_ajax_admin = Envira_WooCommerce_Ajax_Admin::get_instance();