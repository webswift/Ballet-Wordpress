<?php
/**
 * Plugin Name: Envira Gallery - Slideshow Addon
 * Plugin URI:  http://enviragallery.com
 * Description: Enables slideshows for Envira galleries.
 * Author:      Envira Gallery Team
 * Author       http://enviragallery.com
 * Version:     1.1.1
 * Text Domain: envira-slideshow
 * Domain Path: languages
 *
 * Envira Gallery is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * Envira Gallery is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Envira Gallery. If not, see <http://www.gnu.org/licenses/>.
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Main plugin class.
 *
 * @since 1.0.8
 *
 * @package Envira_Slideshow
 * @author  Tim Carr
 */
class Envira_Slideshow {

    /**
     * Holds the class object.
     *
     * @since 1.0.8
     *
     * @var object
     */
    public static $instance;

    /**
     * Plugin version, used for cache-busting of style and script file references.
     *
     * @since 1.0.8
     *
     * @var string
     */
    public $version = '1.1.1';

    /**
     * The name of the plugin.
     *
     * @since 1.0.8
     *
     * @var string
     */
    public $plugin_name = 'Envira Slideshow';

    /**
     * Unique plugin slug identifier.
     *
     * @since 1.0.8
     *
     * @var string
     */
    public $plugin_slug = 'envira-slideshow';

    /**
     * Plugin file.
     *
     * @since 1.0.8
     *
     * @var string
     */
    public $file = __FILE__;

    /**
     * Primary class constructor.
     *
     * @since 1.0.8
     */
    public function __construct() {

        // Load the plugin textdomain.
        add_action( 'plugins_loaded', array( $this, 'load_plugin_textdomain' ) );

        // Load the plugin.
        add_action( 'envira_gallery_init', array( $this, 'init' ), 99 );

    }

    /**
     * Loads the plugin textdomain for translation.
     *
     * @since 1.0.8
     */
    public function load_plugin_textdomain() {

        load_plugin_textdomain( $this->plugin_slug, false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

    }

    /**
     * Loads the plugin into WordPress.
     *
     * @since 1.0.8
     */
    public function init() {

        // Load global components.
        $this->require_global();

        // Load admin only components.
        if ( is_admin() ) {
            $this->require_admin();
        }

        // Load the updater
        add_action( 'envira_gallery_updater', array( $this, 'updater' ) );

    }

    /**
     * Loads all admin related files into scope.
     *
     * @since 1.0.8
     */
    public function require_admin() {

        require plugin_dir_path( __FILE__ ) . 'includes/admin/metaboxes.php';

    }

    /**
     * Initializes the addon updater.
     *
     * @since 1.0.8
     *
     * @param string $key The user license key.
     */
    function updater( $key ) {

        $args = array(
            'plugin_name' => $this->plugin_name,
            'plugin_slug' => $this->plugin_slug,
            'plugin_path' => plugin_basename( __FILE__ ),
            'plugin_url'  => trailingslashit( WP_PLUGIN_URL ) . $this->plugin_slug,
            'remote_url'  => 'http://enviragallery.com/',
            'version'     => $this->version,
            'key'         => $key
        );
        
        $updater = new Envira_Gallery_Updater( $args );

    }

    /**
     * Loads all global files into scope.
     *
     * @since 1.0.8
     */
    public function require_global() {

        require plugin_dir_path( __FILE__ ) . 'includes/global/common.php';
        require plugin_dir_path( __FILE__ ) . 'includes/global/shortcode.php';

    }

     /**
     * Returns the singleton instance of the class.
     *
     * @since 1.0.8
     *
     * @return object The Envira_Slideshow object.
     */
    public static function get_instance() {

        if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Envira_Slideshow ) ) {
            self::$instance = new Envira_Slideshow();
        }

        return self::$instance;

    }

}

// Load the main plugin class.
$envira_slideshow = Envira_Slideshow::get_instance();