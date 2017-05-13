<?php
/**
 * Metabox class.
 *
 * @since 1.0.0
 *
 * @package Envira_Exif
 * @author  Tim Carr
 */
class Envira_Exif_Metaboxes {

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

        $this->base = Envira_Exif::get_instance();

        add_action( 'admin_enqueue_scripts', array( $this, 'metabox_scripts' ) );

		// Envira Gallery 
		add_filter( 'envira_gallery_tab_nav', array( $this, 'register_tabs' ) );
        add_action( 'envira_gallery_tab_exif', array( $this, 'exif_tab' ) );
        add_action( 'envira_gallery_mobile_box', array( $this, 'mobile_tab' ) );
		add_filter( 'envira_gallery_save_settings', array( $this, 'gallery_settings_save' ), 10, 2 );

		// Envira Albums
		add_filter( 'envira_albums_tab_nav', array( $this, 'register_tabs' ) );
        add_action( 'envira_albums_tab_exif', array( $this, 'exif_tab' ) );
		add_filter( 'envira_albums_save_settings', array( $this, 'albums_settings_save' ), 10, 2 );
		
		// Tags Addon
		add_action( 'envira_tags_tag_box', array( $this, 'tags_screen' ) );

    }

    /**
     * Initializes scripts for the metabox admin.
     *
     * @since 1.0.0
     *
     * @param string $key The user license key.
     */
    public function metabox_scripts() {
        // Conditional Fields
        wp_register_script( $this->base->plugin_slug . '-conditional-fields-script', plugins_url( 'assets/js/min/conditional-fields-min.js', $this->base->file ), array( 'jquery', Envira_Gallery::get_instance()->plugin_slug . '-conditional-fields-script' ), $this->base->version, true );
        wp_enqueue_script( $this->base->plugin_slug . '-conditional-fields-script' );
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

        $tabs['exif'] = __( 'EXIF', 'envira-exif' );
        return $tabs;

    }
    
    /**
     * Adds addon settings UI to the EXIF tab
     *
     * @since 1.0.0
     *
     * @param object $post The current post object.
     */
	function exif_tab( $post ) {
		
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
                <?php _e( 'EXIF Gallery Settings', 'envira-exif' ); ?>
                <small>
                    <?php _e( 'The settings below adjust EXIF metadata options on your Gallery output.', 'envira-exif' ); ?>
                    <br />
                    <?php _e( 'Need some help?', 'envira-exif' ); ?>
                    <a href="http://enviragallery.com/docs/exif-addon/" class="envira-doc" target="_blank">
                        <?php _e( 'Read the Documentation', 'envira-exif' ); ?>
                    </a>
                    or
                    <a href="https://www.youtube.com/embed/8ciTrYnJUVQ/?rel=0" class="envira-video" target="_blank">
                        <?php _e( 'Watch a Video', 'envira-exif' ); ?>
                    </a>
                </small>
            </p>
            <table class="form-table">
                <tbody>
                    <!-- EXIF -->
                    <tr id="envira-config-exif-box">
                        <th scope="row">
                            <label for="envira-config-exif"><?php _e( 'Display EXIF Metadata?', 'envira-exif' ); ?></label>
                        </th>
                        <td>
                            <input id="envira-config-exif" type="checkbox" name="<?php echo $key; ?>[exif]" value="1" <?php checked( $instance->get_config( 'exif', $instance->get_config_default( 'exif' ) ), 1 ); ?> data-envira-conditional="envira-config-exif-metadata-box" />
                            <span class="description"><?php _e( 'Enables or disables displaying EXIF metadata in the gallery view.', 'envira-exif' ); ?></span>
                        </td>
                    </tr>
                    <tr id="envira-config-exif-metadata-box">
                        <th scope="row">
                            <label for="envira-config-exif-metadata"><?php _e( 'EXIF Metadata to Display', 'envira-exif' ); ?></label>
                        </th>
                        <td>
                            <label for="envira-config-exif-make" class="full-width">
                                <input id="envira-config-exif-make" type="checkbox" name="<?php echo $key; ?>[exif_make]" value="1" <?php checked( $instance->get_config( 'exif_make', $instance->get_config_default( 'exif_make' ) ), 1 ); ?> />
                                <?php _e( 'Camera Make', 'envira-exif' ); ?>
                            </label>

                            <label for="envira-config-exif-model" class="full-width">
                                <input id="envira-config-exif-model" type="checkbox" name="<?php echo $key; ?>[exif_model]" value="1" <?php checked( $instance->get_config( 'exif_model', $instance->get_config_default( 'exif_model' ) ), 1 ); ?> />
                                <?php _e( 'Camera Model', 'envira-exif' ); ?>
                            </label>

                            <label for="envira-config-exif-aperture-model" class="full-width">
                                <input id="envira-config-exif-aperture-model" type="checkbox" name="<?php echo $key; ?>[exif_aperture]" value="1" <?php checked( $instance->get_config( 'exif_aperture', $instance->get_config_default( 'exif_aperture' ) ), 1 ); ?> />
                                <?php _e( 'Aperture', 'envira-exif' ); ?>
                            </label>

                            <label for="envira-config-exif-shutter-speed" class="full-width">
                                <input id="envira-config-exif-shutter-speed" type="checkbox" name="<?php echo $key; ?>[exif_shutter_speed]" value="1" <?php checked( $instance->get_config( 'exif_shutter_speed', $instance->get_config_default( 'exif_shutter_speed' ) ), 1 ); ?> />
                                <?php _e( 'Shutter Speed', 'envira-exif' ); ?>
                            </label>

                            <label for="envira-config-exif-focal-length" class="full-width">
                                <input id="envira-config-exif-focal-length" type="checkbox" name="<?php echo $key; ?>[exif_focal_length]" value="1" <?php checked( $instance->get_config( 'exif_focal_length', $instance->get_config_default( 'exif_focal_length' ) ), 1 ); ?> />
                                <?php _e( 'Focal Length', 'envira-exif' ); ?>
                            </label>

                            <label for="envira-config-exif-iso" class="full-width">
                                <input id="envira-config-exif-iso" type="checkbox" name="<?php echo $key; ?>[exif_iso]" value="1" <?php checked( $instance->get_config( 'exif_iso', $instance->get_config_default( 'exif_iso' ) ), 1 ); ?> />
                                <?php _e( 'ISO', 'envira-exif' ); ?>
                            </label>
                        </td>
                    </tr>
                </tbody>
            </table>
            <?php
        }

        // Lightbox Options
        ?>
        <p class="envira-intro">
            <?php _e( 'EXIF Lightbox Settings', 'envira-downloads' ); ?>
            <small>
                <?php _e( 'The settings below adjust EXIF metadata options on your Lightbox output.', 'envira-exif' ); ?>
                <br />
            </small>
        </p>
        <table class="form-table">
            <tbody>
                <!-- EXIF -->
                <tr id="envira-config-exif-lightbox-box">
                    <th scope="row">
                        <label for="envira-config-exif-lightbox"><?php _e( 'Display EXIF Metadata?', 'envira-exif' ); ?></label>
                    </th>
                    <td>
                        <input id="envira-config-exif-lightbox" type="checkbox" name="<?php echo $key; ?>[exif_lightbox]" value="1" <?php checked( $instance->get_config( 'exif_lightbox', $instance->get_config_default( 'exif_lightbox' ) ), 1 ); ?> />
                        <span class="description"><?php _e( 'Enables or disables displaying EXIF metadata in the Lightbox view.', 'envira-exif' ); ?></span>
                    </td>
                </tr>
                <tr id="envira-config-exif-lightbox-metadata-box">
                    <th scope="row">
                        <label for="envira-config-exif-metadata"><?php _e( 'EXIF Metadata to Display', 'envira-exif' ); ?></label>
                    </th>
                    <td>
                        <label for="envira-config-exif-lightbox-make" class="full-width">
                            <input id="envira-config-exif-lightbox-make" type="checkbox" name="<?php echo $key; ?>[exif_lightbox_make]" value="1" <?php checked( $instance->get_config( 'exif_lightbox_make', $instance->get_config_default( 'exif_lightbox_make' ) ), 1 ); ?> />
                            <?php _e( 'Camera Make', 'envira-exif' ); ?>
                        </label>

                        <label for="envira-config-exif-lightbox-model" class="full-width">
                            <input id="envira-config-exif-lightbox-model" type="checkbox" name="<?php echo $key; ?>[exif_lightbox_model]" value="1" <?php checked( $instance->get_config( 'exif_lightbox_model', $instance->get_config_default( 'exif_lightbox_model' ) ), 1 ); ?> />
                            <?php _e( 'Camera Model', 'envira-exif' ); ?>
                        </label>

                        <label for="envira-config-exif-lightbox-aperture" class="full-width">
                            <input id="envira-config-exif-lightbox-aperture" type="checkbox" name="<?php echo $key; ?>[exif_lightbox_aperture]" value="1" <?php checked( $instance->get_config( 'exif_lightbox_aperture', $instance->get_config_default( 'exif_lightbox_aperture' ) ), 1 ); ?> />
                            <?php _e( 'Aperture', 'envira-exif' ); ?>
                        </label>

                        <label for="envira-config-exif-lightbox-shutter-speed" class="full-width">
                            <input id="envira-config-exif-lightbox-shutter-speed" type="checkbox" name="<?php echo $key; ?>[exif_lightbox_shutter_speed]" value="1" <?php checked( $instance->get_config( 'exif_lightbox_shutter_speed', $instance->get_config_default( 'exif_lightbox_shutter_speed' ) ), 1 ); ?> />
                            <?php _e( 'Shutter Speed', 'envira-exif' ); ?>
                        </label>

                        <label for="envira-config-exif-lightbox-focal-length" class="full-width">
                            <input id="envira-config-exif-lightbox-focal-length" type="checkbox" name="<?php echo $key; ?>[exif_lightbox_focal_length]" value="1" <?php checked( $instance->get_config( 'exif_lightbox_focal_length', $instance->get_config_default( 'exif_lightbox_focal_length' ) ), 1 ); ?> />
                            <?php _e( 'Focal Length', 'envira-exif' ); ?>
                        </label>

                        <label for="envira-config-exif-lightbox-iso" class="full-width">
                            <input id="envira-config-exif-lightbox-iso" type="checkbox" name="<?php echo $key; ?>[exif_lightbox_iso]" value="1" <?php checked( $instance->get_config( 'exif_lightbox_iso', $instance->get_config_default( 'exif_lightbox_iso' ) ), 1 ); ?> />
                            <?php _e( 'ISO', 'envira-exif' ); ?>
                        </label>
                    </td>
                </tr>
                <tr id="envira-config-exif-lightbox-position-box">
                    <th scope="row">
                        <label for="envira-config-exif-lightbox-position"><?php _e( 'EXIF Metadata Position', 'envira-exif' ); ?></label>
                    </th>
                    <td>
                        <select id="envira-config-exif-lightbox-position" name="<?php echo $key; ?>[exif_lightbox_position]">
                            <?php 
                            foreach ( (array) $this->get_positions() as $value => $name ) {
                                ?>
                                <option value="<?php echo $value; ?>"<?php selected( $value, $instance->get_config( 'exif_lightbox_position', $instance->get_config_default( 'exif_lightbox_position' ) ) ); ?>><?php echo $name; ?></option>
                                <?php
                            }
                            ?>
                        </select>
                        <p class="description"><?php _e( 'Where to display the EXIF metadata relative to the image.', 'envira-exif' ); ?></p>
                    </td>
                </tr>
                <tr id="envira-config-exif-lightbox-outside-box">
                    <th scope="row">
                        <label for="envira-config-exif-lightbox-outside"><?php _e( 'Display EXIF Outside of Image?', 'envira-exif' ); ?></label>
                    </th>
                    <td>
                        <input id="envira-config-exif-lightbox-outside" type="checkbox" name="<?php echo $key; ?>[exif_lightbox_outside]" value="1" <?php checked( $instance->get_config( 'exif_lightbox_outside', $instance->get_config_default( 'exif_lightbox_outside' ) ), 1 ); ?> />
                        <span class="description"><?php _e( 'If enabled, displays the EXIF metadata outside of the lightbox/image frame.', 'envira-exif' ); ?></span>
                    </td>
                </tr>
            </tbody>
        </table>
        <?php
	
	}
	
    /**
     * Adds addon settings UI to the Mobile tab
     *
     * @since 1.1.0
     *
     * @param object $post The current post object.
     */
    function mobile_tab( $post ) {
        
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
        ?>
        <tr id="envira-config-exif-mobile-box">
            <th scope="row">
                <label for="envira-config-exif-mobile"><?php _e( 'Display EXIF Metadata?', 'envira-exif' ); ?></label>
            </th>
            <td>
                <input id="envira-config-exif-mobile" type="checkbox" name="<?php echo $key; ?>[mobile_exif]" value="1" <?php checked( $instance->get_config( 'mobile_exif', $instance->get_config_default( 'mobile_exif' ) ), 1 ); ?> />
                <span class="description"><?php _e( 'If enabled, will display EXIF metadata based on the settings under the Config and Lightbox tabs. If disabled, no EXIF metadata is displayed on mobile.', 'envira-exif' ); ?></span>
            </td>
        </tr>
        <?php

    }

    /**
     * Helper method for retrieving positions.
     *
     * @since 1.0.8
     *
     * @return array Array of position data.
     */
    public function get_positions() {

        $instance = Envira_EXIF_Common::get_instance();
        return $instance->get_positions();

    }

	/**
	 * Adds addon settings UI to the Tags tab (Galleries only)
	 *
	 * @since 1.0.0
	 *
	 * @param object $post The current post object.
	 */
	function tags_screen( $post ) {
		
		// Post type will always be an Envira Gallery
		$instance = Envira_Gallery_Metaboxes::get_instance();
		$key = '_envira_gallery';
		?>
		<tr id="envira-config-exif-tags-box">
            <th scope="row">
                <label for="envira-config-exif-tags"><?php _e( 'Enable EXIF Tag Filtering?', 'envira-exif' ); ?></label>
            </th>
            <td>
                <input id="envira-config-exif-tags" type="checkbox" name="<?php echo $key; ?>[exif_tags]" value="1" <?php checked( $instance->get_config( 'exif_tags', $instance->get_config_default( 'exif_tags' ) ), 1 ); ?> />
            	<span class="description"><?php _e( 'Enables or disables tag filtering by Camera Make and Model', 'envira-exif' ); ?></span>
            </td>
        </tr>
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
	function gallery_settings_save( $settings, $post_id ) {
		
		// Gallery: EXIF
	    $settings['config']['exif']          			= ( isset( $_POST['_envira_gallery']['exif'] ) ? 1 : 0 );
	    $settings['config']['exif_make']          		= ( isset( $_POST['_envira_gallery']['exif_make'] ) ? 1 : 0 );
	    $settings['config']['exif_model']          		= ( isset( $_POST['_envira_gallery']['exif_model'] ) ? 1 : 0 );
	    $settings['config']['exif_aperture']          	= ( isset( $_POST['_envira_gallery']['exif_aperture'] ) ? 1 : 0 );
	    $settings['config']['exif_shutter_speed']      	= ( isset( $_POST['_envira_gallery']['exif_shutter_speed'] ) ? 1 : 0 );
	    $settings['config']['exif_focal_length']      	= ( isset( $_POST['_envira_gallery']['exif_focal_length'] ) ? 1 : 0 );
	    $settings['config']['exif_iso']      			= ( isset( $_POST['_envira_gallery']['exif_iso'] ) ? 1 : 0 );

	    // Lightbox: EXIF
	    $settings['config']['exif_lightbox']          	  = ( isset( $_POST['_envira_gallery']['exif_lightbox'] ) ? 1 : 0 );
	    $settings['config']['exif_lightbox_make']         = ( isset( $_POST['_envira_gallery']['exif_lightbox_make'] ) ? 1 : 0 );
	    $settings['config']['exif_lightbox_model']        = ( isset( $_POST['_envira_gallery']['exif_lightbox_model'] ) ? 1 : 0 );
	    $settings['config']['exif_lightbox_aperture']     = ( isset( $_POST['_envira_gallery']['exif_lightbox_aperture'] ) ? 1 : 0 );
	    $settings['config']['exif_lightbox_shutter_speed']= ( isset( $_POST['_envira_gallery']['exif_lightbox_shutter_speed'] ) ? 1 : 0 );
		$settings['config']['exif_lightbox_focal_length'] = ( isset( $_POST['_envira_gallery']['exif_lightbox_focal_length'] ) ? 1 : 0 );
		$settings['config']['exif_lightbox_iso']      	  = ( isset( $_POST['_envira_gallery']['exif_lightbox_iso'] ) ? 1 : 0 );
        $settings['config']['exif_lightbox_position']     = preg_replace( '#[^a-z0-9-_]#', '', $_POST['_envira_gallery']['exif_lightbox_position'] );
        $settings['config']['exif_lightbox_outside']      = ( isset( $_POST['_envira_gallery']['exif_lightbox_outside'] ) ? 1 : 0 );

        // Mobile
        $settings['config']['mobile_exif']                = ( isset( $_POST['_envira_gallery']['mobile_exif'] ) ? 1 : 0 );

	    // Tags
	    $settings['config']['exif_tags']          		  = ( isset( $_POST['_envira_gallery']['exif_tags'] ) ? 1 : 0 );

	    return $settings;
	
	}
		
	/**
	 * Saves the addon's settings for Albums.
	 *
	 * @since 1.0.0
	 *
	 * @param array $settings  Array of settings to be saved.
	 * @param int $pos_tid     The current post ID.
	 * @return array $settings Amended array of settings to be saved.
	 */
	function albums_settings_save( $settings, $post_id ) {
	
		// Lightbox: EXIF
	    $settings['config']['exif_lightbox']          	   = ( isset( $_POST['_eg_album_data']['config']['exif_lightbox'] ) ? 1 : 0 );
	    $settings['config']['exif_lightbox_make']          = ( isset( $_POST['_eg_album_data']['config']['exif_lightbox_make'] ) ? 1 : 0 );
	   	$settings['config']['exif_lightbox_model']         = ( isset( $_POST['_eg_album_data']['config']['exif_lightbox_model'] ) ? 1 : 0 );
	    $settings['config']['exif_lightbox_aperture']      = ( isset( $_POST['_eg_album_data']['config']['exif_lightbox_aperture'] ) ? 1 : 0 );
	    $settings['config']['exif_lightbox_shutter_speed'] = ( isset( $_POST['_eg_album_data']['config']['exif_lightbox_shutter_speed'] ) ? 1 : 0 );
	    $settings['config']['exif_lightbox_focal_length']  = ( isset( $_POST['_eg_album_data']['config']['exif_lightbox_focal_length'] ) ? 1 : 0 );
	    $settings['config']['exif_lightbox_iso']		   = ( isset( $_POST['_eg_album_data']['config']['exif_lightbox_iso'] ) ? 1 : 0 );
        $settings['config']['exif_lightbox_position']      = preg_replace( '#[^a-z0-9-_]#', '', $_POST['_eg_album_data']['config']['exif_lightbox_position'] );
	    $settings['config']['exif_lightbox_outside']       = ( isset( $_POST['_eg_album_data']['config']['exif_lightbox_outside'] ) ? 1 : 0 );

        // Mobile
        $settings['config']['mobile_exif']                 = ( isset( $_POST['_eg_album_data']['config']['mobile_exif'] ) ? 1 : 0 );

	    return $settings;
	
	}
	
    /**
     * Returns the singleton instance of the class.
     *
     * @since 1.0.0
     *
     * @return object The Envira_Pagination_Metaboxes object.
     */
    public static function get_instance() {

        if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Envira_Exif_Metaboxes ) ) {
            self::$instance = new Envira_Exif_Metaboxes();
        }

        return self::$instance;

    }

}

// Load the metabox class.
$envira_exif_metaboxes = Envira_Exif_Metaboxes::get_instance();