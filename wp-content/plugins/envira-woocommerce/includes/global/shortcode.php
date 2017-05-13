<?php
/**
 * Shortcode class.
 *
 * @since 1.0.0
 *
 * @package Envira_WooCommerce
 * @author  Tim Carr
 */
class Envira_WooCommerce_Shortcode {

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
     * Holds the current image ID that is being output
     *
     * @since 1.0.6
     *
     * @var int
     */
    public $id;

    /**
     * Holds the current item (image) that is being output
     *
     * @since 1.0.6
     *
     * @var array
     */
    public $item;
    
    /**
     * Holds success and error messages when saving/submitting orders
     *
     * @since 1.0.0
     *
     * @var array
     */
    public $messages = array();

    /**
     * Primary class constructor.
     *
     * @since 1.0.0
     */
    public function __construct() {
	    
	    // Load the base class object.
        $this->base = Envira_WooCommerce::get_instance();
	    
	    // Register CSS
        wp_register_style( $this->base->plugin_slug . '-style', plugins_url( 'assets/css/envira-woocommerce.css', $this->base->file ), array(), $this->base->version );
	    
        // Register JS
        wp_register_script( $this->base->plugin_slug . '-script', plugins_url( 'assets/js/envira-woocommerce.js', $this->base->file ), array( 'jquery' ), $this->base->version, true );

	    // Gallery
        add_action( 'envira_gallery_before_output', array( $this, 'output_css_js' ) );
        add_filter( 'envira_gallery_output_start', array( $this, 'output_messages' ), 10, 2 );
        add_filter( 'envira_gallery_output_after_link', array( $this, 'output_add_to_cart' ), 10, 5 );
        add_action( 'envira_gallery_api_after_show', array( $this, 'lightbox_classes' ) );
        add_action( 'envira_gallery_api_helper_config', array( $this, 'gallery_lightbox_load_woocommerce_helper' ) );

        // Album
        add_action( 'envira_albums_before_output', array( $this, 'output_css_js' ) );
        add_action( 'envira_albums_api_after_show', array( $this, 'lightbox_classes' ) );
        add_action( 'envira_albums_api_helper_config', array( $this, 'album_lightbox_load_woocommerce_helper' ) );

        // WooCommerce
        add_filter( 'woocommerce_add_cart_item_data', array( $this, 'add_cart_item_data' ), 10, 3 );
        add_filter( 'woocommerce_get_cart_item_from_session', array( $this, 'get_cart_item_from_session' ), 10, 3 );
        add_filter( 'woocommerce_cart_item_name', array( $this, 'cart_item_name' ), 10, 3 );
        add_filter( 'woocommerce_add_order_item_meta', array( $this, 'convert_cart_item_data_to_order_meta' ), 10, 3 );

    }

    /** 
	* Enqueue CSS and JS if Proofing is enabled
	*
	* @since 1.0.0
	*
	* @param array $data Gallery Data
	*/
	public function output_css_js( $data ) {

		// Check if WooCommerce is enabled
		if ( ! $this->get_config( 'woocommerce', $data ) && ! $this->get_config( 'lightbox_woocommerce', $data ) ) {
            return false;
        }

        // Enqueue CSS + JS
		wp_enqueue_style( $this->base->plugin_slug . '-style' );
        wp_enqueue_script( $this->base->plugin_slug . '-script' );

        // Localize JS
        wp_localize_script( $this->base->plugin_slug . '-script', 'envira_woocommerce', array(
            'ajax'                          => admin_url( 'admin-ajax.php' ),
            'debug'                         => ( defined( 'WP_DEBUG' ) && WP_DEBUG ? true : false ),
            'get_add_to_cart_form_nonce'    => wp_create_nonce( 'envira-woocommerce-get-add-to-cart-form' ),
            'woocommerce_is_active'         => function_exists( 'wc_get_product' )
        ) );

        // Enqueue WooCommerce JS
        wp_enqueue_script( 'woocommerce' );
        wp_enqueue_script( 'wc-add-to-cart-variation' );
        wp_enqueue_script( 'wc-single-product' );
	}

    /**
    * Includes the WooCommerce templates/notices/success.php file, which will then output
    * any "Added to Cart" messages if the user has added a WC Product to their cart
    *
    * @since 1.0.0
    *
    * @param string $html Gallery HTML
    * @param array $data Gallery Data
    * @return Gallery HTML
    */
    public function output_messages( $html, $data ) {

        // Bail if WC notices function doesn't exist
        if ( ! function_exists( 'wc_print_notices' ) ) {
            return $html;
        }

        // Include template and store in variable
        ob_start();
        wc_print_notices();
        $message_html = ob_get_clean();

        // Add message HTML to gallery output
        $html .= $message_html;

        // Return
        return $html;

    }

    /**
     * Outputs the WooCommerce Add to Cart option if the given image has a WooCommerce Product ID
     * specified.
     *
     * @since 1.0.0
     *
     * @param   string  $output     HTML
     * @param   int     $id         Attachment ID
     * @param   array   $item       Image Meta
     * @param   array   $data       Gallery Config
     * @param   int     $i          Image index
     * @return string               HTML
     */
    public function output_add_to_cart( $output, $id, $item, $data, $i, $ajax = false ) {

        // If the WooCommerce plugin isn't installed, return immedately
        if ( !function_exists( 'wc_get_product') ) {
            return $output;
        }

        // Check if item has a WooCommerce Product assigned to it
        if ( ! isset( $item['woocommerce_product'] ) ) {
            return $output;
        }
        if ( empty( $item['woocommerce_product'] ) ) {
            return $output;
        }

        if ( ! $ajax && ! $this->get_config( 'woocommerce', $data ) ) {
            return $output;
        }
        elseif ( $ajax && ! $this->get_config( 'lightbox_woocommerce', $data ) ) {
            return $output;
        }

        // Get gallery theme
        $instance = Envira_Gallery_Shortcode::get_instance();

        // Check the columns - if it's zero, then it's the automatic layout which means we don't add anything since adding items messes things up
        if ( ! $ajax && $instance->get_config( 'columns', $data ) == 0 ) {
            return $output;
        }

        // Make the image ID and image accessible within this class
        $this->id = $id;
        $this->item = $item;

        // Add an action to output the Envira Image's Title and ID as hidden fields to the Add to Cart
        // form.  This action is called when we include the WooCommerce Template
        add_action( 'woocommerce_after_add_to_cart_button', array( $this, 'add_hidden_fields_to_add_to_cart_button' ) );
        
        // Get Product
        global $product, $attributes;
        $product = wc_get_product( $item['woocommerce_product'] );
        $html = '';

        // Depending on the Product Type, get some more information
        switch ( $product->product_type ) {
            /**
            * Simple
            */
            case 'simple':
                // Nothing more to do
                break;

            /**
            * External
            */
            case 'external':
                // Define product URL and button text for WC template
                global $product_url, $button_text;
                $product_url = $product->get_product_url();
                $button_text = $product->get_button_text();
                break;

            /**
            * Grouped
            */
            case 'grouped':
                // Define grouped products for WC template
                global $grouped_products, $quantites_required;
                $grouped_products = array();
                $quantites_required = array();
                break;

            /**
            * Variable
            */
            case 'variable':
                // Define variations and attributes for WC template
                $wc_product_variable = new WC_Product_Variable( $product );
                $available_variations = $wc_product_variable->get_available_variations();
                $attributes = $product->get_variation_attributes();
                break;
        }

        // Include the WooCommerce Plugin template file and capture its output into our $html var 
        ob_start();
        include( WP_PLUGIN_DIR . '/woocommerce/templates/single-product/price.php' );
        include( WP_PLUGIN_DIR . '/woocommerce/templates/single-product/add-to-cart/' . $product->product_type . '.php' );
        $html = ob_get_clean();

        // Check if WooCommerce is enabled
        // If not, we'll hide this markup
        if ( ! $this->get_config( 'woocommerce', $data ) ) {
            $css_class = ' envira-woocommerce-hidden envira-hidden';
        } else {
            $css_class = '';
        }

        // Remove action
        remove_action( 'woocommerce_after_add_to_cart_button', array( $this, 'add_hidden_fields_to_add_to_cart_button' ) );

        // Return
        return $output . '<div class="envira-woocommerce' . $css_class . '">' . $html . '</div>';

    }

    /**
     * Adds ID, title and caption hidden fields to the Add to Cart Button Form
     *
     * We later check for these values when the form is submitted, and add them to the
     * cart item data if they exist.
     *
     * This allows a single WooCommerce Product to accept any Envira Image
     *
     * @since 1.0.6
     */
    public function add_hidden_fields_to_add_to_cart_button() {

        ?>
        <input type="hidden" name="envira_woocommerce_image_id" value="<?php echo $this->id; ?>" />
        <input type="hidden" name="envira_woocommerce_image_title" value="<?php echo $this->item['title']; ?>" />
        <input type="hidden" name="envira_woocommerce_image_caption" value="<?php echo $this->item['caption']; ?>" />
        <?php

    }

    /**
     * Appends the envirabox-proofing class to the main Lightbox wrapper
     *
     * @since 1.0
     *
     * @param array $data Gallery Data
     */
    public function lightbox_classes( $data ) {

        // Check if Proofing for Lightbox is enabled
        if ( ! $this->get_config( 'lightbox_woocommerce', $data ) ) {
            return;
        }
        ?>
        $('.envirabox-wrap').addClass('envirabox-woocommerce');
        <?php

    }

    /**
     * Gallery: Initializes the Envirabox WooCommerce helper, if WooCommerce is enabled
     * on the Gallery.
     *
     * @since 1.0.9
     *
     * @param  array $ data    Gallery Data
     * @return string          Javascript
     */
    public function gallery_lightbox_load_woocommerce_helper( $data ) {
        
        // Check if WooCommerce is enabled in the Lightbox for this Gallery
        if ( ! $this->get_config( 'lightbox_woocommerce', $data ) ) {
            return;
        }
        ?>
        woocommerce: {
            gallery_id: <?php echo $data['id']; ?>
        },
        <?php
          
    }

    /**
     * Albums: Initializes the Envirabox WooCommerce helper, if WooCommerce is enabled
     * on the Albums.
     *
     * @since 1.0.9
     *
     * @param  array $ data    Gallery Data
     * @return string          Javascript
     */
    public function album_lightbox_load_woocommerce_helper( $data ) {
        
        // Check if WooCommerce is enabled in the Lightbox for this Album
        if ( ! $this->get_config( 'lightbox_woocommerce', $data ) ) {
            return;
        }

        // envira_album_current_gallery_id is defined in Envira Albums as each Gallery Lightbox is setup.
        ?>
        woocommerce: {
            gallery_id: ''
        },
        <?php
          
    }
    
    /**
     * Helper method for retrieving gallery config values.
     *
     * @since 1.0.0
     *
     * @param string $key The config key to retrieve.
     * @param array $data The gallery data to use for retrieval.
     * @return string     Key value on success, default if not set.
     */
    public function get_config( $key, $data ) {

        // Check whether we're getting the config for a Gallery or Album

        // Get ID
        $id = ( is_numeric( $data['id'] ) ? $data['id'] : ( ( isset( $data['config']['id'] ) ? $data['config']['id'] : '' ) ) );
        
        $post_type = get_post_type( $id );

        // if the post_type is 'post' or something not matching below, abort
        if ( $post_type != 'envira' && $post_type != 'envira_album' ) {
            return false;
        }

        switch ( $post_type ) {
            case 'envira':
                $instance = Envira_Gallery_Shortcode::get_instance();
                break;

            case 'envira_album':
                $instance = Envira_Albums_Shortcode::get_instance();
                break;
        }

        return $instance->get_config( $key, $data );

    }

    /**
     * Stores the Envira Image's Title and ID when an Envira Image is added to the WooCommerce Cart.
     *
     * @since 1.0.6
     *
     * @param   array   $cart_item_data
     * @param   int     $product_id
     * @param   int     $variation_id
     * @return  array   $cart_item_data
     */
    public function add_cart_item_data( $cart_item_data, $product_id, $variation_id ) {

        // Get fields.
        $fields = Envira_WooCommerce_Common::get_instance()->get_cart_hidden_fields();

        // Populate
        foreach ( $fields as $field => $label ) {
            if ( isset( $_REQUEST[ $field ]) ) {
                $cart_item_data[ $field ] = sanitize_text_field( $_REQUEST[ $field ] );
            }  
        }

        return $cart_item_data;

    }

    /**
     * Fetches the cart item data from the session (Envira Image Title and ID), adding it to the cart
     * item.
     *
     * @since 1.0.6
     *
     * @param   array   $item
     * @param   array   $values
     * @param   string  $key
     * @return  array   $item
     */
    public function get_cart_item_from_session( $item, $values, $key ) {

        // Get fields.
        $fields = Envira_WooCommerce_Common::get_instance()->get_cart_hidden_fields();

        // Populate
        foreach ( $fields as $field => $label ) {
            if ( array_key_exists( $field, $values ) ) {
                $item[ $field ] = $values[ $field ];
            }  
        }

        return $item;

    }

    /**
     * Appends the Envira Image Title and Media ID to the Product Title when in the Cart / Checkout.
     *
     * @since 1.0.6
     *
     * @param   string  $title          Product Title
     * @param   array   $cart_item      Cart Item
     * @param   string  $cart_item_key  Cart Item Key
     * @return  string                  Product Title
     */
    public function cart_item_name( $title, $cart_item, $cart_item_key ) {

        // Get fields.
        $fields = Envira_WooCommerce_Common::get_instance()->get_cart_hidden_fields();

        // Populate
        foreach ( $fields as $field => $label ) {
            if ( array_key_exists( $field, $cart_item ) ) {
                if ( ! empty( $cart_item[ $field ] ) ) {
                    $title .= ' ' . $cart_item[ $field ];
                }
            }  
        }
        
        return $title;

    }

    /**
     * When an order is made, the existing cart item data is stored as WooCommerce Order Item Meta
     * This allows the data to be displayed under the Order Items section when viewing an Order
     * in the WordPress Admin or frontend
     *
     * @since 1.0.6
     *
     * @param   int     $item_id        Item ID
     * @param   array   $values         Values
     * @param   string  $cart_item_key  Cart Item Key
     */
    public function convert_cart_item_data_to_order_meta( $item_id, $values, $cart_item_key ) {

        // Get fields.
        $fields = Envira_WooCommerce_Common::get_instance()->get_cart_hidden_fields();

        // Populate
        foreach ( $fields as $field => $label ) {
            if ( array_key_exists( $field, $values ) ) {
                wc_add_order_item_meta( $item_id, $label, $values[ $field ] );
            }  
        }

    }

    /**
     * Returns the singleton instance of the class.
     *
     * @since 1.0.0
     *
     * @return object The Envira_WooCommerce_Shortcode object.
     */
    public static function get_instance() {

        if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Envira_WooCommerce_Shortcode ) ) {
            self::$instance = new Envira_WooCommerce_Shortcode();
        }

        return self::$instance;

    }

}

// Load the shortcode class.
$envira_woocommerce_shortcode = Envira_WooCommerce_Shortcode::get_instance();