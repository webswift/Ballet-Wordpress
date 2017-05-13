<?php
/** 
 * Metabox class.
 *
 * @since 1.0.0
 *
 * @package Envira_WooCommerce
 * @author  Tim Carr
 */
class Envira_WooCommerce_Metaboxes {

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

    	// Load the base class object.
        $this->base = Envira_WooCommerce::get_instance();

        // Gallery
        add_action( 'envira_gallery_metabox_scripts', array( $this, 'scripts' ) );
        add_filter( 'envira_gallery_tab_nav', array( $this, 'register_tabs' ) );
        add_action( 'envira_gallery_tab_woocommerce', array( $this, 'woocommerce_tab' ) );
        add_filter( 'envira_gallery_save_settings', array( $this, 'save_gallery' ), 10, 2 );

        // Gallery: Individual Image Settings
        add_action( 'print_media_templates', array( $this, 'meta_settings' ), 10, 3 );

        // Album
        add_filter( 'envira_albums_tab_nav', array( $this, 'register_tabs' ) );
        add_action( 'envira_albums_tab_woocommerce', array( $this, 'woocommerce_tab' ) );
        add_filter( 'envira_albums_save_settings', array( $this, 'save_album' ), 10, 2 );

    }

    /**
     * Enqueues the Media Editor script, which is used when editing a gallery image
     * This outputs the WooCommerce settings for each individual image
     *
     * @since 1.0.4
     */
    public function scripts() {

        wp_enqueue_script( $this->base->plugin_slug . '-media-edit', plugins_url( 'assets/js/media-edit.js', $this->base->file ), array( 'jquery' ), $this->base->version, true );
        wp_localize_script(
            $this->base->plugin_slug . '-media-edit',
            'envira_woocommerce',
            array(
                'get_product_attributes_nonce' => wp_create_nonce( 'envira-woocommerce-get-product-attributes' ),
            )
        );
    }

    /**
     * Registers tab(s) for this Addon in the Settings screen
     *
     * @since 1.0.0
     *
     * @param   array   $tabs   Tabs
     * @return  array           Tabs
     */
    function register_tabs( $tabs ) {

        $tabs['woocommerce'] = __( 'WooCommerce', 'envira-woocommerce' );
        return $tabs;

    }
    
    /**
     * Adds addon settings UI to the WooCommerce tab
     *
     * @since 1.0.0
     *
     * @param object $post The current post object.
     */
    function woocommerce_tab( $post ) {
        
        // Get post type so we load the correct metabox instance and define the input field names
        // Input field names vary depending on whether we are editing a Gallery or Album
        $post_type = get_post_type( $post );
        switch ( $post_type ) {
            /**
            * Gallery
            */
            case 'envira':
                $instance = Envira_Gallery_Metaboxes::get_instance();
                $key = '_envira_gallery';
                break;
            
            /**
            * Album
            */
            case 'envira_album':
                $instance = Envira_Albums_Metaboxes::get_instance();
                $key = '_eg_album_data[config]';
                break;
        }
        
        // Gallery options only apply to Galleries, not Albums
        if ( 'envira' == $post_type ) {
            ?>
            <p class="envira-intro">
                <?php _e( 'Gallery Options.', 'envira-woocommerce' ); ?>
                <small>
                    <?php _e( 'Need some help?', 'envira-woocommerce' ); ?>
                    <a href="http://enviragallery.com/docs/woocommerce-addon/" class="envira-doc" target="_blank">
                        <?php _e( 'Read the Documentation', 'envira-woocommerce' ); ?>
                    </a>
                    or
                    <a href="https://www.youtube.com/embed/H5zAzLUJZTg/?rel=0" class="envira-video" target="_blank">
                        <?php _e( 'Watch a Video', 'envira-woocommerce' ); ?>
                    </a>
                </small>
            </p>
            <table class="form-table">
                <tbody>
                    <tr id="envira-config-woocommerce-box">
                        <th scope="row">
                            <label for="envira-config-woocommerce"><?php _e( 'Enable WooCommerce?', 'envira-woocommerce' ); ?></label>
                        </th>
                        <td>
                            <input id="envira-config-woocommerce" type="checkbox" name="<?php echo $key; ?>[woocommerce]" value="1"<?php checked( $instance->get_config( 'woocommerce', $instance->get_config_default( 'woocommerce' ) ), 1 ); ?> />
                            <span class="description"><?php _e( 'Enables WooCommerce Add to Cart functionality for each image in the gallery grid, if the image is assigned to a WooCommerce Product.', 'envira-woocommerce' ); ?></span>
                        </td>
                    </tr>
                </tbody>
            </table>
            <?php
        }

        // Lightbox Options
        ?>
        <p class="envira-intro">
            <?php _e( 'Lightbox Options.', 'envira-woocommerce' ); ?>
            <small>
                <?php _e( 'Need some help?', 'envira-woocommerce' ); ?>
                <a href="http://enviragallery.com/docs/woocommerce-addon/" class="envira-doc" target="_blank">
                    <?php _e( 'Read the Documentation', 'envira-woocommerce' ); ?>
                </a>
                or
                <a href="https://www.youtube.com/embed/H5zAzLUJZTg/?rel=0" class="envira-video" target="_blank">
                    <?php _e( 'Watch a Video', 'envira-woocommerce' ); ?>
                </a>
            </small>
        </p>
        <table class="form-table">
            <tbody>
                <tr id="envira-config-woocommerce-box">
                    <th scope="row">
                        <label for="envira-config-lightbox-woocommerce"><?php _e( 'Enable WooCommerce?', 'envira-woocommerce' ); ?></label>
                    </th>
                    <td>
                        <input id="envira-config-lightbox-woocommerce" type="checkbox" name="<?php echo $key; ?>[lightbox_woocommerce]" value="1"<?php checked( $instance->get_config( 'lightbox_woocommerce', $instance->get_config_default( 'lightbox_woocommerce' ) ), 1 ); ?> />
                        <span class="description"><?php _e( 'Enables WooCommerce Add to Cart functionality for each image in the Lightbox view, if the image is assigned to a WooCommerce Product.', 'envira-woocommerce' ); ?></span>
                    </td>
                </tr>
            </tbody>
        </table>
        <?php
        
    }

    /**
     * Saves the addon's settings for Galleries.
     *
     * @since 1.0.0
     *
     * @param array $settings  Array of settings to be saved.
     * @param int $pos_tid     The current post ID.
     * @return array $settings Amended array of settings to be saved.
     */
    function save_gallery( $settings, $post_id ) {

        $settings['config']['woocommerce']                = ( isset( $_POST['_envira_gallery']['woocommerce'] ) ? 1 : 0 );
        $settings['config']['lightbox_woocommerce']       = ( isset( $_POST['_envira_gallery']['lightbox_woocommerce'] ) ? 1 : 0 );

        return $settings;
    
    }

    /**
     * Saves the addon's settings for Albums.
     *
     * @since 1.0.9
     *
     * @param array $settings  Array of settings to be saved.
     * @param int $pos_tid     The current post ID.
     * @return array $settings Amended array of settings to be saved.
     */
    function save_album( $settings, $post_id ) {

        $settings['config']['lightbox_woocommerce']       = ( isset( $_POST['_eg_album_data']['config']['lightbox_woocommerce'] ) ? 1 : 0 );

        return $settings;
    
    }

    /**
     * Outputs fields in the modal window when editing an existing image,
     * allowing the user to choose which WooCommerce Product/Variant to
     * link the image to.
     *
     * @since 1.0.0
     *
     * @param int $id      The ID of the item to retrieve.
     * @param array $data  Array of data for the item.
     * @param int $post_id The current post ID.
     */
    public function meta_settings( $post_id ) {

        // Get WooCommerce Products
        $args = array(
            'post_type'     => 'product',
            'posts_per_page'=> -1,
        );
        $products = new WP_Query( $args );

        // WooCommerce Meta Editor
        // Use: wp.media.template( 'envira-meta-editor-woocommerce' )
        ?>
        <script type="text/html" id="tmpl-envira-meta-editor-woocommerce">
            <label class="setting">
                <span class="name"><?php _e( 'WooCommerce Product', 'envira-woocommerce' ); ?></span>
                <select name="woocommerce_product" size="1">
                    <option value="0"><?php _e( '(No Product)', 'envira-woocommerce' ); ?></option>
                    <?php
                    if ( $products->have_posts() ) {
                        foreach ( $products->posts as $product ) {
                            ?>
                            <option value="<?php echo $product->ID; ?>"><?php echo $product->post_title; ?></option>
                            <?php
                        }
                    }
                    ?>
                </select>
                <span class="description">
                    <?php _e( 'Choose a WooCommerce Product which relates to this image.  Visitors will then be able to order the selected product in the gallery and/or lightbox views.', 'envira-woocommerce' ); ?>
                </span>
            </label>
        </script>
        <?php

    }
	
    /**
     * Returns the singleton instance of the class.
     *
     * @since 1.0.0
     *
     * @return object The Envira_WooCommerce_Metaboxes object.
     */
    public static function get_instance() {

        if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Envira_WooCommerce_Metaboxes ) ) {
            self::$instance = new Envira_WooCommerce_Metaboxes();
        }

        return self::$instance;

    }

}

// Load the metabox class.
$envira_woocommerce_metaboxes = Envira_WooCommerce_Metaboxes::get_instance();