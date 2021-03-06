<?php
/**
 * Common class.
 *
 * @since 1.0.0
 *
 * @package Envira_Dynamic
 * @author  Tim Carr
 */
class Envira_Dynamic_Common {

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

        add_filter( 'envira_gallery_defaults', array( $this, 'defaults' ), 10, 2 );

    }

    /**
     * Adds the default settings for this addon.
     *
     * @since 1.1.6
     *
     * @param array     $defaults   Array of default config values.
     * @param int       $post_id    The current post ID.
     * @return array    $defaults   Amended array of default config values.
     */
    function defaults( $defaults, $post_id ) {

        // Disabled by default.
        $defaults['native_gallery_override'] = 0;
    
        // Return
        return $defaults;
    
    }
    
    /**
	 * Retrieves the dynamic gallery ID for holding dynamic settings.
	 *
	 * @since 1.0.0
	 *
	 * @return int The post ID for the dynamic settings.
	 */
	function get_gallery_dynamic_id() {
	
	    return get_option( 'envira_dynamic_gallery' );
	
	}
	
	/**
	 * Retrieves the dynamic album ID for holding dynamic settings.
	 *
	 * @since 1.0.0
	 *
	 * @return int The post ID for the dynamic settings.
	 */
	function get_album_dynamic_id() {
	
	    return get_option( 'envira_dynamic_album' );
	
	}

    /**
     * Returns the singleton instance of the class.
     *
     * @since 1.0.0
     *
     * @return object The Envira_Dynamic_Common object.
     */
    public static function get_instance() {

        if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Envira_Dynamic_Common ) ) {
            self::$instance = new Envira_Dynamic_Common();
        }

        return self::$instance;

    }

}

// Load the common class.
$envira_dynamic_common = Envira_Dynamic_Common::get_instance();