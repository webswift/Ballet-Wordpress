<?php

///////////////////////////////
///// CLASS seg_instaportfolio_admin
///////////////////////////////
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
if ( ! class_exists( 'seg_instaportfolio_admin' ) ){
    class seg_instaportfolio_admin{
        ///// CONSTRUCTOR
        public function __construct(){
            $this->options = array();
            $this->namespace = 'seg-instaportfolio';
            $this->friendly_name = 'Instagram Portfolio';
            $this->prefix = 'seg_';
            $this->effects_scroll = array(   "No effect" ,
                                             "Fade",
                                             "Scale"
                                          );
            $this->effects_hover = array(
                                           "No effect",
                                           "Flinders",
                                           "Clayton",
                                           "Bells",
                                           "Swanston",
                                           "Caulfield",
                                           "Chapel",
                                           "St Kilda",
                                           "Tamachi",
                                           "Shibuya",
                                           "Asakusa",
                                           "Shinjuku",
                                           "Tiradentes",
                                           "Cinquentenario",
                                           "Yoyogi",
                                           "Halong",
                                           "Chiang",
                                           "Saigon",
                                           "Angkor"
                                        );


            include( SEG_INSTAPORTFOLIO_CURRENT_FOLDER . '/admin/options.php' );
            $data_default_update = array();
            $data_default_update_sanitazed = array();
            $option_value = "";
            //lets loop throught our fields and check if they are already stored, if not store default values
            foreach($this->my_opts as $opt) {
                if($opt['tabs'] != NULL) {
                    foreach($opt['tabs'] as $tab) {
                        if($tab['fields'] != NULL) {
                            foreach($tab['fields'] as $field) {
                                $option_value = (string) $this->get_option( $field['name'] );
                                //// /IF THERE IS NO VALUE ON A FIELD WITH DEFAULT VALUE, WE UPDATE WITH UPDATE VALUE
                                if( empty($option_value) && !empty($field['default']) ){
                                    update_option( $field['name'], $field['default'] );
                                }
                           }
                        }
                    }
                }
            }
            $this->add_hooks();
        }

        ///// LETS ADD OUR HOOKS
        private function add_hooks() {
            ///////////////////////////////////////
            ///// ADMIN AREA
            ///////////////////////////////////////
            // OPTIONS PAGE FOR CONFIGURATION
            add_action( 'admin_menu', array( $this, 'admin_menu' ) );
            // REGISTER STYLES ADMIN
            add_action( 'admin_init', array( &$this, 'wp_register_styles_admin' ), 1 );
            // ENQUEUE STYLES ADMIN
            add_action( 'admin_init', array( &$this, 'wp_enqueue_styles_admin' ) );
            // REGISTER SCRIPT ADMIN
            add_action( 'admin_init', array( &$this, 'wp_register_script_admin' ), 1 );
            // ENQUEUE SCRIPT ADMIN
            add_action( 'admin_init', array( &$this, 'wp_enqueue_script_admin' ) );
            // Route requests for form processing
            add_action( 'init', array( &$this, 'route' ) );

            ////////////////////////////////////
            ///// FRONTEND
            /////////////////////////////////////
            //// LETS ADD OUR SHORTCODE
            add_shortcode( $this->prefix . 'instaportfolio', array( &$this, $this->prefix . 'instaportfolio_shortcode' ) );
            //// REGISTER OUR FRONTEND STYLES
            add_action( 'init', array( &$this, 'wp_register_styles_front' ), 1 );
            //// ENQUEUE OUR FRONTEND STYLES
            add_action( 'init', array( &$this, 'wp_enqueue_styles_front' ) );
            //// REGISTER OUR FRONTEND SCRIPTS
            add_action( 'init', array( &$this, 'wp_register_script_front' ), 1 );
            // ENQUEUE OUR FRONTEND SCRIPT
            add_action( 'init', array( &$this, 'wp_enqueue_script_front' ) );
            // REGISTER WIDGET
           // add_action( 'widgets_init', array( &$this, 'seg_instaportfolio_widget' ) );
        }

        ////////////////////////////////////////////////////////////
        ///// styles frontpage
        ///////////////////////////////////////////////////////////
        function wp_register_styles_front(){
            wp_register_style( "{$this->namespace}-frontend", SEG_INSTAPORTFOLIO_URLPATH . "/front/style.css", array(), SEG_INSTAPORTFOLIO_VERSION, 'screen' );
            wp_register_style( "{$this->namespace}-lightbox", SEG_INSTAPORTFOLIO_URLPATH . "/front/css/lightgallery.min.css", array(), SEG_INSTAPORTFOLIO_VERSION, 'screen' );
            wp_register_style( "{$this->namespace}-transitions", SEG_INSTAPORTFOLIO_URLPATH . "/front/css/lg-transitions.min.css", array(), SEG_INSTAPORTFOLIO_VERSION, 'screen' );
            wp_register_style( "{$this->namespace}-comments", SEG_INSTAPORTFOLIO_URLPATH . "/front/css/lg-fb-comment-box.min.css", array(), SEG_INSTAPORTFOLIO_VERSION, 'screen' );
            wp_register_style( "{$this->namespace}-justified", SEG_INSTAPORTFOLIO_URLPATH . "/front/css/justifiedGallery.min.css", array(), SEG_INSTAPORTFOLIO_VERSION, 'screen' );
            wp_register_style( "{$this->namespace}-video", SEG_INSTAPORTFOLIO_URLPATH . "/front/css/video.css", array(), SEG_INSTAPORTFOLIO_VERSION, 'screen' );
        }

        function wp_enqueue_styles_front(){
            wp_enqueue_style("jquery");
            wp_enqueue_style("{$this->namespace}-frontend");
            wp_enqueue_style("{$this->namespace}-lightbox");
            wp_enqueue_style("{$this->namespace}-transitions");
            wp_enqueue_style("{$this->namespace}-comments");
            wp_enqueue_style("{$this->namespace}-justified");
            wp_enqueue_style("{$this->namespace}-video");    
        }

        ///////////////////////////////////////////////////////////
        ///// STYLES ADMIN
        ///////////////////////////////////////////////////////////
        function wp_register_styles_admin(){
            wp_register_style( "{$this->namespace}-jquery-ui", SEG_INSTAPORTFOLIO_URLPATH . "/admin/css/jquery-ui.min.css", array(), SEG_INSTAPORTFOLIO_VERSION, 'screen' );
             wp_register_style( "{$this->namespace}-admin", SEG_INSTAPORTFOLIO_URLPATH . "/admin/style.css", array(), SEG_INSTAPORTFOLIO_VERSION, 'screen' );
             wp_register_style( "{$this->namespace}-colorpicker", SEG_INSTAPORTFOLIO_URLPATH . "/admin/css/colorpicker.css", array(), SEG_INSTAPORTFOLIO_VERSION, 'screen' );
        }

        function wp_enqueue_styles_admin(){
            wp_enqueue_style("{$this->namespace}-jquery-ui");
            wp_enqueue_style("{$this->namespace}-admin");
            wp_enqueue_style("{$this->namespace}-colorpicker");
        }

        ////////////////////////////////////////////////
        ///// SCRIPTS FRONTPAGE
        ///////////////////////////////////////////////
        function wp_register_script_front(){
           wp_register_script( "{$this->namespace}-frontend-js", SEG_INSTAPORTFOLIO_URLPATH . "/front/js/script.js", array( 'jquery' ), SEG_INSTAPORTFOLIO_VERSION );
           wp_register_script( "{$this->namespace}-appear-js", SEG_INSTAPORTFOLIO_URLPATH . "/front/js/appear.min.js", array( 'jquery' ), "" );
           wp_register_script( "{$this->namespace}-lightbox-js", SEG_INSTAPORTFOLIO_URLPATH . "/front/js/lightgallery-all.min.js", array( 'jquery' ), "" );
           wp_register_script( "{$this->namespace}-justified-js", SEG_INSTAPORTFOLIO_URLPATH . "/front/js/jquery.justifiedGallery.min.js", array( 'jquery' ), "" );
           wp_register_script( "{$this->namespace}-video-js", SEG_INSTAPORTFOLIO_URLPATH . "/front/js/video.js", array( 'jquery' ), "" );
        }

        function wp_enqueue_script_front(){
            wp_enqueue_script( "jquery" );
            wp_enqueue_script( "{$this->namespace}-appear-js" );
            wp_enqueue_script( "{$this->namespace}-frontend-js" );
            wp_enqueue_script( "{$this->namespace}-lightbox-js" );
            wp_enqueue_script( "{$this->namespace}-justified-js" );
            wp_enqueue_script( "{$this->namespace}-video-js" );
        }

        //////////////////////////////////////////
        ///// SCRIPT ADMIN
        //////////////////////////////////////////
        function wp_register_script_admin(){
            //wp_register_script( "{$this->namespace}-jquery-ui-js", "//code.jquery.com/ui/1.11.2/jquery-ui.js", array( 'jquery' ), SEG_INSTAPORTFOLIO_VERSION, true );
            wp_register_script( "{$this->namespace}-jquery-ui-js", SEG_INSTAPORTFOLIO_URLPATH . "/admin/js/jquery-ui.min.js", array( 'jquery' ), SEG_INSTAPORTFOLIO_VERSION, true );
            wp_register_script( "{$this->namespace}-admin-js", SEG_INSTAPORTFOLIO_URLPATH . "/admin/js/script.js", array( 'jquery' ), SEG_INSTAPORTFOLIO_VERSION, true );
            wp_register_script( "{$this->namespace}-colorpicker-js", SEG_INSTAPORTFOLIO_URLPATH . "/admin/js/colorpicker.js", array( 'jquery' ), SEG_INSTAPORTFOLIO_VERSION, true );
        }

        function wp_enqueue_script_admin(){
            wp_enqueue_script("{$this->namespace}-admin-js");
            wp_enqueue_script("{$this->namespace}-colorpicker-js");
            wp_enqueue_script("{$this->namespace}-jquery-ui-js");
        }

        function admin_menu(){
            add_menu_page($this->friendly_name, $this->friendly_name, 'administrator', $this->namespace, array($this, 'createAdminHTML'));
        }

         function seg_instaportfolio_shortcode( $atts_param ){
            //$demolph_output = "";
            $atts = shortcode_atts( array(
                'user' => '',
                'filter_users' => '',
                'hashtag' => '',
                'location' => '',
                'number_photos' => '',
                'height' => '',
                'fixed_height' => '',
                'last_row' => '',
                'width' => '',
                'shadow' => '',
                'hover_effect' => '',
                'padding' => '',
                'photo_effect' => '',
                'photo_background' => '',
                'photo_background2' => '',
                'photo_opacity' => '',
                'photo_shape' => '',
                'scroll_effect' => '',
                'scroll_delay' => '',
                'hover_background' => '',
                'hover_background2' => '',
                'hover_opacity' => '',
                'hover_photo_filter_effect' => '',
                'hover_effect' => '',
                'display_animated_lines' => '',
                'animated_lines_colour' => '',
                'display_icon_instagram' => '',
                'icon_instagram_colour' => '',
                'icon_instagram_effect' => '',
                'icon_instagram_size' => '',
                'display_photo_description' => '',
                'photo_description_colour' => '',
                'photo_description_effect' => '',
                'photo_description_size' => '',
                'photo_description_limit' => '',
                'display_photo_likes' => '',
                'photo_likes_colour' => '',
                'photo_likes_effect' => '',
                'photo_likes_size' => '',
                'photo_likes_like' => '',
                'load_more' => '',
                'load_more_dinamically' => '',
                'lightbox' => '',
                'display_header' => '',
                'header_background' => '',
                'header_panel_button_colour' => '',
                'header_text_colour' => '',
                'display_social_icons' => '',
                'responsive' => '',
                'loading_color' => ''
            ), $atts_param );
            ob_start();
            $this->callFrontendHTML( $atts );
            $demolph_output = ob_get_clean();
            return $demolph_output;
         }

         function seg_instaportfolio_widget(){
            return $this->callFrontendHTML( array() );
        }

        function callFrontendHTML( $atts ){
            include( SEG_INSTAPORTFOLIO_CURRENT_FOLDER . '/front/index.php' );
            //return $htmlOutput;
         }

         function createAdminHTML(){
            if( !current_user_can( 'manage_options' ) ) {
                wp_die( 'You do not have sufficient permissions to access this page' );
            }
            $namespace = $this->namespace;
            $page_title = $this->friendly_name . ' ' . __( 'Settings', $namespace );
            $page_subtitle = __( 'Creating and customizing the beauty', $namespace );
            include( SEG_INSTAPORTFOLIO_CURRENT_FOLDER . '/admin/view/index.php' );
         }

         /**
         * Retrieve the stored plugin option or the default if no user specified value is defined
         *
         * @param string $option_name The name of the option you wish to retrieve
         *
         * @uses get_option()
         *
         * @return mixed Returns the option value or false(boolean) if the option is not found
         */
        function get_option( $option_name_param, $reload = false ) {

            $option_value = get_option($option_name_param);

            if(empty($option_value) || !isset($option_value)){



            }

            return $option_value;

        }

         /**
         * Route the user based off of environment conditions
         *
         * This function will handling routing of form submissions to the appropriate
         * form processor.
         *
         * @uses RelatedServiceComments::_admin_options_update()
         */
        function route() {
            $uri = $_SERVER['REQUEST_URI'];
            $protocol = isset( $_SERVER['HTTPS'] ) ? 'https' : 'http';
            $hostname = $_SERVER['HTTP_HOST'];
            $url = "{$protocol}://{$hostname}{$uri}";
            $is_post = (bool) ( strtoupper( $_SERVER['REQUEST_METHOD'] ) == "POST" );

            // Check if a nonce was passed in the request
            if( isset( $_REQUEST['_wpnonce'] ) ) {
                $nonce = $_REQUEST['_wpnonce'];

                // Handle POST requests
                if( $is_post ) {
                    if( wp_verify_nonce( $nonce, "{$this->namespace}-update-options" ) ) {
                        $this->_admin_options_update();
                    }
                }
                // Handle GET requests
                else {
                    // Nothing here yet...
                }
            }
        }

         /**
         * Process update page form submissions
         *
         * @uses RelatedServiceComments::sanitize()
         * @uses wp_redirect()
         * @uses wp_verify_nonce()
         */
         function _admin_options_update() {
            // Verify submission for processing using wp_nonce
            if( wp_verify_nonce( $_REQUEST['_wpnonce'], "{$this->namespace}-update-options" ) ) {
                $array_data = array();

               ///// LETS LOOP EACH OPTION AND UPDATE IT
                foreach( $_POST['data'] as $key => $val ) {
                    update_option( $key, $this->_sanitize( $val ) );
                }

                // Redirect back to the options page with the message flag to show the saved message
                wp_safe_redirect( $_REQUEST['_wp_http_referer'] );
                exit;
            }
        }


        /**
         * Sanitize data
         *
         * @param mixed $str The data to be sanitized
         *
         * @uses wp_kses()
         *
         * @return mixed The sanitized version of the data
         */
        function _sanitize( $str ) {
            if ( !function_exists( 'wp_kses' ) ) {
                require_once( ABSPATH . 'wp-includes/kses.php' );
            }
            global $allowedposttags;
            global $allowedprotocols;

            if ( is_string( $str ) ) {
                $str = wp_kses( $str, $allowedposttags, $allowedprotocols );
            } elseif( is_array( $str ) ) {
                $arr = array();
                foreach( (array) $str as $key => $val ) {
                    $arr[$key] = $this->_sanitize( $val );
                }
                $str = $arr;
            }

            return $str;
        }

        function get_option_value($option_name_param){

            return $this->get_option( $this->prefix . $option_name_param );

        }
    }

}

?>
