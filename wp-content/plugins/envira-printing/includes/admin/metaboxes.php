<?php
/**
 * Metabox class.
 *
 * @since 1.0.0
 *
 * @package Envira_Printing
 * @author  Tim Carr
 */
class Envira_Printing_Metaboxes {

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

        // Envira Gallery
        add_filter( 'envira_gallery_tab_nav', array( $this, 'register_tabs' ) );
        add_action( 'envira_gallery_tab_printing', array( $this, 'printing_tab' ) );
        add_filter( 'envira_gallery_save_settings', array( $this, 'gallery_settings_save' ), 10, 2 );

        // Envira Album
        add_filter( 'envira_albums_tab_nav', array( $this, 'register_tabs' ) );
        add_action( 'envira_albums_tab_printing', array( $this, 'printing_tab' ) );
        add_filter( 'envira_albums_save_settings', array( $this, 'album_settings_save' ), 10, 2 );

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

        $tabs['printing'] = __( 'Printing', 'envira-printing' );
        return $tabs;

    }
    
    /**
     * Adds addon settings UI to the Printing tab
     *
     * @since 1.0.0
     *
     * @param object $post The current post object.
     */
    function printing_tab( $post ) {
        
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
                <?php _e( 'Printing Gallery Settings', 'envira-printing' ); ?>
                <small>
                    <?php _e( 'The settings below adjust the Printing options for the Gallery output.', 'envira-printing' ); ?>
                    <br />
                    <?php _e( 'Need some help?', 'envira-printing' ); ?>
                    <a href="http://enviragallery.com/docs/printing-addon/" class="envira-doc" target="_blank">
                        <?php _e( 'Read the Documentation', 'envira-printing' ); ?>
                    </a>
                    or
                    <a href="#" class="envira-video" target="_blank">
                        <?php _e( 'Watch a Video', 'envira-printing' ); ?>
                    </a>
                </small>
            </p>
            <table class="form-table">
                <tbody>
                    <tr id="envira-config-print-box">
                        <th scope="row">
                            <label for="envira-config-print"><?php _e( 'Display Print Button?', 'envira-printing' ); ?></label>
                        </th> 
                        <td>
                            <input id="envira-config-print" type="checkbox" name="<?php echo $key; ?>[print]" value="1" <?php checked( $instance->get_config( 'print', $instance->get_config_default( 'print' ) ), 1 ); ?> data-envira-conditional="envira-config-print-position-box" />
                            <span class="description"><?php _e( 'Enables or disables displaying a print button on each image in the gallery view.', 'envira-printing' ); ?></span>
                        </td>
                    </tr>
                    <tr id="envira-config-print-position-box">
                        <th scope="row">
                            <label for="envira-config-print-position"><?php _e( 'Print Button Position', 'envira-printing' ); ?></label>
                        </th>
                        <td>
                            <select id="envira-config-print-position" name="<?php echo $key; ?>[print_position]">
                                <?php foreach ( (array) $this->get_positions() as $value => $name ) : ?>
                                    <option value="<?php echo $value; ?>"<?php selected( $value, $instance->get_config( 'print_position', $instance->get_config_default( 'print_position' ) ) ); ?>><?php echo $name; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <p class="description"><?php _e( 'Where to display the print button over the image.', 'envira-printing' ); ?></p>
                        </td>
                    </tr>
                </tbody>
            </table>
            <?php
        }

        // Lightbox Options
        ?>
        <p class="envira-intro">
            <?php _e( 'Printing Lightbox Settings', 'envira-printing' ); ?>
            <small>
                <?php _e( 'The settings below adjust the Printing options for the Lightbox output.', 'envira-printing' ); ?>
            </small>
        </p>
        <table class="form-table">
            <tbody>
                <tr id="envira-config-print-lightbox-box">
                    <th scope="row">
                        <label for="envira-config-print-lightbox"><?php _e( 'Display Print Button?', 'envira-printing' ); ?></label>
                    </th>
                    <td>
                        <input id="envira-config-print-lightbox" type="checkbox" name="<?php echo $key; ?>[print_lightbox]" value="1" <?php checked( $instance->get_config( 'print_lightbox', $instance->get_config_default( 'print_lightbox' ) ), 1 ); ?> data-envira-conditional="envira-config-print-lightbox-position-box,envira-config-print-lightbox-force-box" />
                        <span class="description"><?php _e( 'Enables or disables displaying the print button on each image in the Lightbox view.', 'envira-printing' ); ?></span>
                    </td>
                </tr>
                <tr id="envira-config-print-lightbox-position-box">
                    <th scope="row">
                        <label for="envira-config-print-lightbox-position"><?php _e( 'Print Button Position', 'envira-printing' ); ?></label>
                    </th>
                    <td>
                        <select id="envira-config-print-lightbox-position" name="<?php echo $key; ?>[print_lightbox_position]">
                            <?php foreach ( (array) $this->get_positions() as $value => $name ) : ?>
                                <option value="<?php echo $value; ?>"<?php selected( $value, $instance->get_config( 'print_lightbox_position', $instance->get_config_default( 'print_lightbox_position' ) ) ); ?>><?php echo $name; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <p class="description"><?php _e( 'Where to display the print button over the image.', 'envira-printing' ); ?></p>
                    </td>
                </tr>
            </tbody>
        </table>
        <?php

    }
    
    /**
     * Helper method for retrieving positions.
     *
     * @since 1.0.0
     *
     * @return array Array of position data.
     */
    public function get_positions() {

        $instance = Envira_Printing_Common::get_instance();
        return $instance->get_positions();

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
        
        // Gallery
        $settings['config']['print']                   = ( isset( $_POST['_envira_gallery']['print'] ) ? 1 : 0 );
        $settings['config']['print_position']          = preg_replace( '#[^a-z0-9-_]#', '', $_POST['_envira_gallery']['print_position'] );

        // Lightbox
        $settings['config']['print_lightbox']          = ( isset( $_POST['_envira_gallery']['print_lightbox'] ) ? 1 : 0 );
        $settings['config']['print_lightbox_position'] = preg_replace( '#[^a-z0-9-_]#', '', $_POST['_envira_gallery']['print_lightbox_position'] );
        
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
    function album_settings_save( $settings, $post_id ) {
        
        // Lightbox
        $settings['config']['print_lightbox']          = ( isset( $_POST['_eg_album_data']['config']['print_lightbox'] ) ? 1 : 0 );
        $settings['config']['print_lightbox_position'] = preg_replace( '#[^a-z0-9-_]#', '', $_POST['_eg_album_data']['config']['print_lightbox_position'] );
        
        return $settings;
    
    }
    
    /**
     * Returns the singleton instance of the class.
     *
     * @since 1.0.0
     *
     * @return object The Envira_Printing_Metaboxes object.
     */
    public static function get_instance() {

        if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Envira_Printing_Metaboxes ) ) {
            self::$instance = new Envira_Printing_Metaboxes();
        }

        return self::$instance;

    }

}

// Load the metabox class.
$envira_printing_metaboxes = Envira_Printing_Metaboxes::get_instance();