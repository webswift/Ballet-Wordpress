<?php
/**
 * Download class.
 *
 * @since 1.0.0
 *
 * @package Envira_Downloads
 * @author  Tim Carr
 */
class Envira_Downloads_Download {

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

        add_action( 'init', array( $this, 'force_download' ) );

    }

    /**
    * Forces a browser download of the requested image from the requested Envira Gallery
    *
    * @since 1.0
    */
    public function force_download() {

        // Check if a gallery ID and download ID have been specified
        if ( ! isset( $_REQUEST['envira-downloads-gallery-id'] ) || ! isset( $_REQUEST['envira-downloads-gallery-image'] ) ) {
            return;
        }

        // Prepare vars
        $gallery_id         = absint( $_REQUEST['envira-downloads-gallery-id'] );
        $gallery_image_id   = intval(sanitize_text_field( $_REQUEST['envira-downloads-gallery-image'] ));
        $envira_dynamic     = ( isset($_REQUEST['envira-dynamic']) ? absint( $_REQUEST['envira-dynamic'] ) : false);

        // Get gallery, unless we are a dynamic gallery with "Render all WordPress Galleries using Envira?" enabled
        
        if ( strpos( $_REQUEST['envira-downloads-gallery-id'], 'custom_gallery_') !== false ) { // with 'custom_gallery', we assume it's a converted WP gallery

            $gallery_image_id = 'wp_gallery';

        } else if ( $envira_dynamic == 1 ) {

            $tags = explode(",", $_REQUEST['envira-downloads-gallery-id']);
            $tag_array = array( 'dynamic' => $tags);
            $gallery_data = Envira_Dynamic_Gallery_Shortcode::get_instance()->parse_shortcode_attributes( false, $tag_array, false );
            $gallery_id = 'dynamic';

        } else { // it's a run-of-the-mill gallery, so attempt to grab data

            $gallery_data = Envira_Gallery::get_instance()->get_gallery( $gallery_id );
            if ( ! $gallery_data ) {
                return;
            }

        }

        // If The Gallery Image Is Zero and If We Are Requesting All Images, Make The Var "all"
        if ( ( $gallery_image_id == 0 ) || $_REQUEST['envira-downloads-gallery-image'] == "all" ) {
            $gallery_image_id = 'all';
        }


        if ( ( is_integer( $gallery_image_id ) && $gallery_image_id > 0 ) || $gallery_image_id == "all" ) :

        /**
        * If Password Protection is enabled on this gallery:
        * - Check if a cookie exists for this gallery ID. If so, check it matches the password
        * - Check if a password was sent as part of the request. If so, check it matches the password + store as a cookie
        * - Bail, as we don't have a password from the user
        */
        // If Password Protection is enabled, check we have a cookie set
        if ( isset( $gallery_data['config']['password_protection_download'] ) && ! empty( $gallery_data['config']['password_protection_download'] ) ) {
            // Password required
            $password_success = false;
            $password = $gallery_data['config']['password_protection_download'];

            // Check cookies
            if ( isset( $_COOKIE['envira_password_protection_download_' . $gallery_id ] ) ) {
                if ( wp_check_password( $password, $_COOKIE['envira_password_protection_download_' . $gallery_id ] ) ) {
                    // OK
                    $password_success = true;
                    setcookie( 'envira_password_protection_download_' . $gallery_id, wp_hash_password( $password ), time() + ( 3600 * 24 ) );
                }
            }

            // Check request
            if ( isset( $_REQUEST['envira_password_protection_download'] ) ) {
                if ( $_REQUEST['envira_password_protection_download'] == $password ) {
                    // OK
                    $password_success = true;
                    setcookie( 'envira_password_protection_download_' . $gallery_id, wp_hash_password( $password ), time() + ( 3600 * 24 ) );
                }
            }

            // If password was not successful, redirect with an error message so the user knows what went wrong.
            if ( ! $password_success ) {
                // Clear any cookie that might have been set. This ensures users can re-attempt authentication
                // when a Gallery password is changed.
                setcookie( 'envira_password_protection_download_' . $gallery_id, '', time() - ( 3600 * 24 ) );

                // Build the redirect URL, by removing the existing query args and adding a new message.
                $redirect_url = remove_query_arg( array(
                    'envira-downloads-gallery-image',
                    'envira_password_protection_download',
                ), $_SERVER['REQUEST_URI'] );

                // Add an error flag.
                $redirect_url = add_query_arg( array(
                    'envira-downloads-invalid-password' => 1
                ), $redirect_url );

                wp_redirect( $redirect_url );
                die();
            }
        }

        if ( ! isset( $gallery_data['gallery'] ) ) {
            return;
        }

        endif;

        // If the requested image ID is 'all', build a ZIP file comprising of all images in the Gallery
        switch ( $gallery_image_id ) {

            /**
            * Download All
            */
            case 'wp_gallery':
                // envira-downloads-gallery-image is the image attachment ID
                $attachment_id = intval( $_REQUEST['envira-downloads-gallery-image'] );
                if ( !$attachment_id ) { return; }

                // Get image and filename
                $filename = get_attached_file( $attachment_id ); // Full path
                
                header( "Content-type: application/x-msdownload" );
                header( "Content-Disposition: attachment; filename=" . $filename );
                header( "Pragma: no-cache" );
                header( "Expires: 0" );
                echo file_get_contents( $filename );
                exit();
                break;


            /**
            * Download All
            */
            case 'all':
                // Build an array of image paths
                $images = array();
                foreach ( $gallery_data['gallery'] as $image_id => $image ) {
                    $images[] = get_attached_file( $image_id );
                }
                
                // ZIP
                $upload_dir = wp_upload_dir();
                $filename   = $upload_dir['basedir'] . '/envira-downloads.zip';
                $result     = $this->zip( $images, $filename );

                if ( $result ) {
                    header( "Pragma: no-cache");
                    header( "Expires: 0");
                    header( "Cache-Control: must-revalidate, post-check=0, pre-check=0");
                    header( "Cache-Control: public");
                    header( "Content-Description: File Transfer");
                    header( "Content-type: application/octet-stream");
                    header( "Content-Disposition: attachment; filename=envira-downloads.zip" );
                    header( "Content-Transfer-Encoding: binary");
                    echo file_get_contents( $filename );
                    exit();
                }
                break;

            /**
            * Download Specific Image
            */
            default:
                if ( ! isset( $gallery_data['gallery'][ $gallery_image_id ] ) ) {
                    return;
                }

                // Get image and filename
                $image = $gallery_data['gallery'][ $gallery_image_id ];
                $filename_parts = explode( '/', $image['src'] );
                $filename = $filename_parts[ count( $filename_parts ) - 1 ];

                header( "Pragma: no-cache");
                header( "Expires: 0");
                header( "Cache-Control: must-revalidate, post-check=0, pre-check=0");
                header( "Cache-Control: public");
                header( "Content-Description: File Transfer");
                header( "Content-type: application/octet-stream");
                header( "Content-Disposition: attachment; filename=" . $filename );
                header( "Content-Transfer-Encoding: binary");
                echo file_get_contents( $image['src'] );
                exit();
                break;

        }

    }

    /**
     * Zips the given array of files into the given destination ZIP file.
     *
     * @since   1.0.1
     *
     * @param   array   $files          Absolute paths and filename to source files
     * @param   string  $destination    Absolute path and filename of destination ZIP file
     * @return
     */
    private function zip( $files, $destination ) {
        
        // Check the ZIP extension is loaded
        if ( ! extension_loaded( 'zip' ) ) {
            return false;
        }

        // Delete the ZIP file if it already exists
        if ( file_exists( $destination ) ) {
            unlink ( $destination );
        }

        $zip = new ZipArchive();
        if ( ! $zip->open( $destination, ZIPARCHIVE::CREATE ) ) {
            return false;
        }

        foreach ( $files as $file ) {
            $zip->addFile( $file, basename( $file ) );
        }

        $zip->close();

        return true;

    }

    /**
     * Returns the singleton instance of the class.
     *
     * @since 1.0.0
     *
     * @return object The Envira_Downloads_Download object.
     */
    public static function get_instance() {

        if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Envira_Downloads_Download ) ) {
            self::$instance = new Envira_Downloads_Download();
        }

        return self::$instance;

    }

}

// Load the download class.
$envira_downloads_download = Envira_Downloads_Download::get_instance();