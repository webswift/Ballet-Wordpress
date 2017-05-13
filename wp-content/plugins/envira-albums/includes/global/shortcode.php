<?php
/**
 * Shortcode class.
 *
 * @since 1.0.0
 *
 * @package Envira_Albums
 * @author  Tim Carr
 */
class Envira_Albums_Shortcode {

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
     * Holds the unfiltered album data.
     *
     * @since 1.3.0.4
     *
     * @var array
     */
    public $unfiltered_albums;

    /**
     * Holds the album data.
     *
     * @since 1.0.0
     *
     * @var array
     */
    public $data;

    /**
     * Holds gallery IDs for init firing checks.
     *
     * @since 1.0.0
     *
     * @var array
     */
    public $done = array();

    /**
     * Iterator for galleries on the page.
     *
     * @since 1.0.0
     *
     * @var int
     */
    public $counter = 1;

    /**
     * Holds image URLs for indexing.
     *
     * @since 1.0.0
     *
     * @var array
     */
    public $index = array();

    /**
     * Primary class constructor.
     *
     * @since 1.0.0
     */
    public function __construct() {
    
        // Load the base class object.
        $this->base = Envira_Albums::get_instance();
        $this->galleryBase = Envira_Gallery::get_instance();
        
        // Register main gallery style from Envira Gallery
        wp_register_style( $this->galleryBase->plugin_slug . '-style', plugins_url( 'assets/css/envira.css', $this->galleryBase->file ), array(), $this->galleryBase->version );

        // if ( $this->get_config( 'columns', $data ) == 0 ) :
        wp_register_style( $this->galleryBase->plugin_slug . '-jgallery', plugins_url( 'assets/css/justifiedGallery.css', $this->galleryBase->file ), array(), $this->galleryBase->version );

        // Register main script from Envira Gallery
        wp_register_script( $this->galleryBase->plugin_slug . '-script', plugins_url( 'assets/js/min/envira-min.js', $this->galleryBase->file ), array( 'jquery' ), $this->galleryBase->version, true );

        // Register Envira Album CSS
        wp_register_style( $this->base->plugin_slug . '-style', plugins_url( 'assets/css/albums.css', $this->base->file ), array(), $this->base->version );

        // Load hooks and filters.
        add_shortcode( 'envira-album', array( $this, 'shortcode' ) );
        add_filter( 'widget_text', 'do_shortcode' );
        add_filter( 'envira_gallery_output_before_container', array( $this, 'maybe_add_back_link' ), 10, 2 );
        
    }

    /**
    * Maybe add a back to Album link on a Gallery, if the user navigated from an Album and that Album
    * has this functionality enabled
    *
    * @since 1.1.0.1
    *
    * @param string $gallery Gallery HTML
    * @param array $data Gallery Data
    * @return string Gallery HTML
    */
    public function maybe_add_back_link( $gallery, $data ) {

        // Check if the user was referred from an Album
        if ( ! isset( $_SERVER['HTTP_REFERER'] ) ) {
            return $gallery;
        }

        // If first part of referrer URL matches the Envira Album slug, the visitor clicked on a gallery from an album
        $referer_url = str_replace( get_bloginfo( 'url' ), '', $_SERVER['HTTP_REFERER'] );
        $referer_url_parts = array_values ( array_filter( explode( '/', $referer_url ) ) );

        if ( ! is_array( $referer_url_parts ) || count ( $referer_url_parts ) < 1 ) { // why was it 2 before?
            return $gallery;
        }

        $slug = envira_standalone_get_slug( 'albums' );
        if ( $referer_url_parts[0] != $slug ) {
            // This might be a regular WordPress page the user has embedded an album into, so let's check
            $args = array(
              'name'        => end($referer_url_parts),
              'post_type'   => array ('page','post'),
              'post_status' => 'publish',
              'numberposts' => 1
            );
            $maybe_album_page = get_posts( $args );
            if ( !$maybe_album_page ) { 
                // Giving up, if there is a page it's not published
                return $gallery;
            }

            // Determine if the page has the shortcode
            if ( !has_shortcode( $maybe_album_page[0]->post_content, 'envira-album' ) ) {
                // no shortcode, so this won't get a back link
                return $gallery;
            }

            // If there is a shortcode, parse it for the album ID and get the album data from that

            $regex_pattern = get_shortcode_regex();
            preg_match ('/'.$regex_pattern.'/s', $maybe_album_page[0]->post_content, $regex_matches);

            if ($regex_matches[2] == 'envira-album') :
                //  Found the album, now need to find out the ID
                //  Turn the attributes into a URL parm string
                $attribureStr = str_replace (" ", "&", trim ($regex_matches[3]));
                $attribureStr = str_replace ('"', '', $attribureStr);

                //  Parse the attributes
                $defaults = array (
                    'preview' => '1',
                );
                $attributes = wp_parse_args ($attribureStr, $defaults);
                if ( isset( $attributes["id"] ) ) {
                    $album_data = $this->base->_get_album( $attributes["id"] );
                } else if ( isset( $attributes["slug"] ) ) {
                    $album_data = $this->base->get_album_by_slug( $attributes["slug"] );
                } else {
                    return $gallery;
                }

            endif;

        } else {
            // Referred from an Envira Album
            // Check that Album exists
            $album_data = $this->base->get_album_by_slug( $referer_url_parts[1] );            
        }


        if ( ! $album_data ) {
            return $gallery;
        }

        // Check that Album has "Back to Album" functionality enabled
        if ( ! $this->get_config( 'back', $album_data ) ) {
            return $gallery;
        }

        // Prepend Back to Album Button
        $gallery = '<a href="' . $_SERVER['HTTP_REFERER'] . '" title="' . $this->get_config( 'back_label', $album_data ) . '">' . $this->get_config( 'back_label', $album_data ) . '</a>' . $gallery;
        return $gallery;

    }

    /**
     * Creates the shortcode for the plugin.
     *
     * @since 1.0.0
     *
     * @global object $post The current post object.
     *
     * @param array $atts Array of shortcode attributes.
     * @return string     The gallery output.
     */
    public function shortcode( $atts ) {

        global $post;
        
        $album_id = false;
        if ( isset( $atts['id'] ) ) {
            $album_id = (int) $atts['id'];
            $data       = is_preview() ? $this->base->_get_album( $album_id ) : $this->base->get_album( $album_id );
        } else if ( isset( $atts['slug'] ) ) {
            $album_id = $atts['slug'];
            $data       = is_preview() ? $this->base->_get_album_by_slug( $album_id ) : $this->base->get_album_by_slug( $album_id );
        } else {
            // A custom attribute must have been passed. Allow it to be filtered to grab data from a custom source.
            $data = apply_filters( 'envira_albums_custom_gallery_data', false, $atts, $post );
        }

        // Lets remove any galleries that may have been deleted or moved to the trash
        foreach ( $data['galleries'] as $id => $value ) {
            if ( get_post_status( $id ) !== 'publish' ) {
                unset( $data['galleries'][ $id ] );
            }
        }

        // Store the unfiltered Album in the class array
        // This can be used in the Lightbox later on to build the Galleries and Images to display.
        $this->unfiltered_albums[ $data['id'] ]  = $data;

        // Allow the data to be filtered before it is stored and used to create the album output.
        $data = apply_filters( 'envira_albums_pre_data', $data, $album_id );
        
        // If there is no data to output or the gallery is inactive, do nothing.
        if ( ! $data || empty( $data['galleryIDs'] ) ) {
            return;
        }

        // Change the album order, if specified
        $data = $this->maybe_sort_album( $data, $album_id ); 
        
        // Get rid of any external plugins trying to jack up our stuff where a gallery is present.
        $this->plugin_humility();

        // Prepare variables.
        $this->data[ $data['id'] ]  = $data;
        $this->index[ $data['id'] ] = array();
        $album                      = '';
        $i                          = 1;

        // If this is a feed view, customize the output and return early.
        if ( is_feed() ) {
            return $this->do_feed_output( $data );
        }

        // Load scripts and styles.
        wp_enqueue_style( $this->galleryBase->plugin_slug . '-style' );
        wp_enqueue_style( $this->base->plugin_slug . '-style' );
        wp_enqueue_style( $this->galleryBase->plugin_slug . '-jgallery' );
        wp_enqueue_script( $this->galleryBase->plugin_slug . '-script' );
        
        // Load custom gallery themes if necessary.
        if ( 'base' !== $this->get_config( 'gallery_theme', $data ) ) {
            $this->load_album_theme( $this->get_config( 'gallery_theme', $data ) );
        } 

        // Load custom lightbox themes if necessary.
        if ( 'base' !== $this->get_config( 'lightbox_theme', $data ) ) {
            $this->load_lightbox_theme( $this->get_config( 'lightbox_theme', $data ) );
        }
        
        // Load album init code in the footer.
        add_action( 'wp_footer', array( $this, 'album_init' ), 1000 );

        // Run a hook before the gallery output begins but after scripts and inits have been set.
        do_action( 'envira_albums_before_output', $data );
        
        // Apply a filter before starting the gallery HTML.
        $album = apply_filters( 'envira_gallery_output_start', $album, $data );
        
        // Build out the album HTML.
        $album .= '<div id="envira-gallery-wrap-' . sanitize_html_class( $data['id'] ) . '" class="envira-album-wrap ' . $this->get_album_classes( $data ) . '">';
            $album  = apply_filters( 'envira_albums_output_before_container', $album, $data );
            
            // Description
            if ( isset( $data['config']['description_position'] ) && $data['config']['description_position'] == 'above' ) {
                $album = $this->description( $album, $data );  
            }

            // add justified CSS?
            $extra_css = 'envira-gallery-justified-public';
            if ( $this->get_config( 'columns', $data ) > 0 ) {
                $extra_css = false;
            }
            
            $album .= '<div id="envira-gallery-' . sanitize_html_class( $data['id'] ) . '" class="envira-gallery-public '.$extra_css.' envira-gallery-' . sanitize_html_class( $this->get_config( 'columns', $data ) ) . '-columns envira-clear' . ( $this->get_config( 'isotope', $data ) ? ' enviratope' : '' ) . ( $this->get_config( 'css_animations', $data ) ? ' envira-gallery-css-animations' : '' ) . '" data-envira-columns="' . $this->get_config( 'columns', $data ) . '">';
                
                foreach ( $data['galleryIDs'] as $id ) {
                    
                    // Add the album item to the markup
                    $album = $this->generate_album_item_markup( $album, $data, $id, $i );

                    // Increment the iterator.
                    $i++;

                }

            $album .= '</div>';
            
            // Description
            if ( isset( $data['config']['description_position'] ) && $data['config']['description_position'] == 'below' ) {
                $album = $this->description( $album, $data );  
            }
            
            $album  = apply_filters( 'envira_albums_output_after_container', $album, $data );
        $album .= '</div>';
        $album  = apply_filters( 'envira_albums_output_end', $album, $data );

        // Increment the counter.
        $this->counter++;

        // Add no JS fallback support.
        $no_js    = '<noscript>';
        $no_js   .= $this->get_indexable_images( $data['id'] );
        $no_js   .= '</noscript>';
        $album .= $no_js;

        // Return the album HTML.
        return apply_filters( 'envira_albums_output', $album, $data );

    }

    /**
    * Outputs an individual album item in the grid
    *
    * @since 1.2.5.0
    *
    * @param    string  $album      Album HTML
    * @param    array   $data       Album Config
    * @param    int     $id         Album Gallery ID
    * @param    int     $i          Index
    * @return   string              Album HTML
    */
    public function generate_album_item_markup( $album, $data, $id, $i ) {
        
        // Skip blank entries
        if ( empty( $id ) ) {
            return $album;
        }

        // Get some config values that we'll reuse for each gallery
        $padding = absint( round( $this->get_config( 'gutter', $data ) / 2 ) );
        
        // Get Gallery
        $item = $data['galleries'][ $id ];
        $item = apply_filters( 'envira_albums_output_item_data', $item, $id, $data, $i );

        // Get image
        $imagesrc = $this->get_image_src( $item['cover_image_id'], $item, $data );

        // Get Link New Window
        if ( !empty($item['link_new_window']) ) { $link_new_window = $item['link_new_window']; } else { $link_new_window = false; }

        
        $album  = apply_filters( 'envira_albums_output_before_item', $album, $id, $item, $data, $i );
        
        $output   = '<div id="envira-gallery-item-' . sanitize_html_class( $id ) . '" class="' . $this->get_gallery_item_classes( $item, $i, $data ) . '" style="padding-left: ' . $padding . 'px; padding-bottom: ' . $this->get_config( 'margin', $data ) . 'px; padding-right: ' . $padding . 'px;" ' . apply_filters( 'envira_albums_output_item_attr', '', $id, $item, $data, $i ) . '>';

            $output .= '<div class="envira-gallery-item-inner">';
            $output  = apply_filters( 'envira_albums_output_before_link', $output, $id, $item, $data, $i );
            $output .= '<a ';
            if ( $link_new_window && $link_new_window == 1) {
              $output .= 'target="_blank" ';
            }
            $output .= 'href="'. get_permalink( $id ) .'" class="envira-album-gallery-' . $id . ' envira-gallery-link" title="' . strip_tags( html_entity_decode( $item['title'] ) ) . '" >';
            
            // Image
            $output  = apply_filters( 'envira_albums_output_before_image', $output, $id, $item, $data, $i );
            $album_theme = $this->get_config( 'columns', $data ) == 0 ? ' envira-' . $this->get_config( 'justified_gallery_theme', $data ) : '';

            $output .= '<img id="envira-gallery-image-' . sanitize_html_class( $id ) . '" class="envira-gallery-image envira-gallery-image-' . $i . $album_theme . '" src="' . esc_url( $imagesrc ) . '"' . ( $this->get_config( 'dimensions', $data ) ? ' width="' . $this->get_config( 'crop_width', $data ) . '" height="' . $this->get_config( 'crop_height', $data ) . '"' : '' ) . ' data-envira-src="' . esc_url( $imagesrc ) . '" alt="' . esc_attr( $item['alt'] ) . '" data-envira-caption="' . $item['caption'] . '" alt="' . esc_attr( $item['alt'] ) . '" title="' . strip_tags( html_entity_decode( $item['title'] ) ) . '" ' . apply_filters( 'envira_albums_output_image_attr', '', $item['cover_image_id'], $item, $data, $i ) . ' />';
            $output  = apply_filters( 'envira_albums_output_after_image', $output, $id, $item, $data, $i );
            
            $output .= '</a>';
            $output  = apply_filters( 'envira_albums_output_after_link', $output, $id, $item, $data, $i );
            $output .= '</div>';
        
            // Display Title
            if ( isset( $data['config']['display_titles'] ) && $data['config']['display_titles'] == 1 ) {
                $output  = apply_filters( 'envira_albums_output_before_title', $output, $id, $item, $data, $i );
                
                if ( empty( $item['title'] ) ) {
                    // Revert to Gallery Title
                    $item['title'] = get_the_title( $id );
                }
                
                $output .= '<div class="envira-album-title">' . $item['title'] . '</div>';
                $output  = apply_filters( 'envira_albums_output_after_title', $output, $id, $item, $data, $i ); 
            }

            // Display Caption
            if ( isset( $data['config']['display_captions'] ) && $data['config']['display_captions'] == 1 ) {
                $output  = apply_filters( 'envira_albums_output_before_caption', $output, $id, $item, $data, $i );
                
                if ( ! empty( $item['caption'] ) ) {

                    // add a <br> if there's a line break
                    $item['caption'] = str_replace( '
', '<br/>', ( $item['caption'] ) );

                    $output .= '<div class="envira-album-caption">' . $item['caption'] . '</div>';
                }

                $output  = apply_filters( 'envira_albums_output_after_caption', $output, $id, $item, $data, $i ); 
            }
            
            // Display Image Count
            if ( isset( $data['config']['display_image_count'] ) && $data['config']['display_image_count'] == 1 ) {
                $output  = apply_filters( 'envira_albums_output_before_image_count', $output, $id, $item, $data, $i );
                
                // Get count
                $count = $this->galleryBase->get_gallery_image_count( $id );
                
                // Filter count label
                $label = $count . ' ' . _n( 'Photo', 'Photos', $count, 'envira-albums');
                $output .= '<div class="envira-album-image-count">' . apply_filters( 'envira_albums_output_image_count', $label, $count ) . '</div>';
                
                $output  = apply_filters( 'envira_albums_output_after_image_count', $output, $id, $item, $data, $i ); 
            }
        
        $output .= '</div>';
        $output  = apply_filters( 'envira_albums_output_single_item', $output, $id, $item, $data, $i );
        
        // Append Album to the output.
        $album .= $output;

        // Filter the output.
        $album  = apply_filters( 'envira_albums_output_after_item', $album, $id, $item, $data, $i );

        return $album;

    }

    /**
     * Maybe sort the album galleries, if specified in the config
     *
     * @since 1.2.4.4
     *
     * @param   array   $data       Album Config
     * @param   int     $gallery_id Album ID
     * @return  array               Album Config
     */
    public function maybe_sort_album( $data, $album_id ) {

        // Get sorting method
        $sorting_method     = (string) $this->get_config( 'sorting', $data );
        $sorting_direction  = $this->get_config( 'sorting_direction', $data );

        // Sort images based on method
        switch ( $sorting_method ) {
            /**
            * Random
            */
            case 'random':
                // Shuffle keys
                $keys = array_keys( $data['galleries'] );
                shuffle( $keys );
            
                // Rebuild array in new order
                $new = array();
                foreach( $keys as $key ) {
                    $new[ $key ] = $data['galleries'][ $key ];
                }
        
                // Assign back to gallery
                $data['galleries'] = $new;    
                break;

            /**
            * Gallery Metadata
            */
            case 'title':
            case 'caption':
            case 'alt':
            case 'publish_date':
                // Get metadata
                $keys = array();
                foreach ( $data['galleries'] as $id => $item ) {
                    // If no title or publish date is specified, get it now
                    // The image's title / publish date are populated on an Album save, but if the user upgraded
                    // to the latest version of this Addon and hasn't saved their Album, this data might not be available yet
                    if ( ! isset( $item[ $sorting_method ] ) || empty( $item[ $sorting_method ] ) ) {
                        if ( $sorting_method == 'title' ) {
                            $item[ $sorting_method ] = get_the_title( $id );
                        }
                        if ( $sorting_method == 'publish_date' ) {
                            $item[ $sorting_method ] = get_the_date( 'Y-m-d', $id );
                        }
                    }

                    // Sort
                    $keys[ $id ] = strip_tags( $item[ $sorting_method ] );
                }

                // Sort titles / captions
                if ( $sorting_direction == 'ASC' ) {
                    asort( $keys );
                } else {
                    arsort( $keys );
                }

                // Iterate through sorted items, rebuilding gallery
                $new = array();
                foreach( $keys as $key => $title ) {
                    $new[ $key ] = $data['galleries'][ $key ];
                }

                // Assign back to gallery
                $data['galleries'] = $new;   
                break;

            /**
            * None
            * - Do nothing
            */
            case '0':
            case '':
                break;

            /**
            * If developers have added their own sort options, let them run them here
            */
            default:
                $data = apply_filters( 'envira_albums_sort_album', $data, $sorting_method, $album_id );
                break;

        }

        // Rebuild the galleryIDs array so it matches the new sort order
        $data['galleryIDs'] = array();

        foreach ( $data['galleries'] as $gallery_id => $gallery ) {
            $data['galleryIDs'][] = $gallery_id;
        }

        return $data;

    }

    /**
    * Builds HTML for the Album Description
    *
    * @since 1.0.0
    *
    * @param string $album Album HTML
    * @param array $data Data
    * @return HTML
    */
    public function description( $album, $data ) {
        $album .= '<div class="envira-gallery-description envira-gallery-description-above">';
            $album  = apply_filters( 'envira_albums_output_before_description', $album, $data ); 

            // Get description.
            $description = $data['config']['description'];

            // If the WP_Embed class is available, use that to parse the content using registered oEmbed providers.
            if ( isset( $GLOBALS['wp_embed'] ) ) {
                $description = $GLOBALS['wp_embed']->autoembed( $description );
            }

            // Get the description and apply most of the filters that apply_filters( 'the_content' ) would use
            // We don't use apply_filters( 'the_content' ) as this would result in a nested loop and a failure.
            $description = wptexturize( $description );
            $description = convert_smilies( $description );
            $description = wpautop( $description );
            $description = prepend_attachment( $description );
            
            // Requires WordPress 4.4+
            if ( function_exists( 'wp_make_content_images_responsive' ) ) {
                $description = wp_make_content_images_responsive( $description );
            }

            // Append the description to the gallery output.
            $album .= $description;
            
            $album  = apply_filters( 'envira_albums_output_after_description', $album, $data );
        $album .= '</div>'; 
        
        return $album;
    }
    
    /**
     * Outputs the album init script in the footer.
     *
     * @since 1.0.0
     */
    public function album_init() {
        
        // Load the base class object.
        $this->galleryBase = Envira_Gallery::get_instance();
        
        // envira_albums_galleries stores all Album Gallery Fancybox instances
        // envira_albums_galleries_images stores all Album Gallery Images
        // envira_albums_isotopes stores all Album Isotope instances
        // envira_albums_isotopes_config stores Album Isotope configs
        ?>
        <script type="text/javascript">
            var envira_albums_galleries = [],
                envira_albums_galleries_images = [],
                envira_albums_isotopes = [],
                envira_albums_isotopes_config = [];

            jQuery(document).ready(function($){<?php ob_start();
            do_action( 'envira_albums_api_start_global' );
            foreach ( $this->data as $data ) {
                // Prevent multiple init scripts for the same album ID.
                if ( in_array( $data['id'], $this->done ) ) {
                    continue;
                }
                $this->done[] = $data['id'];

                do_action( 'envira_albums_api_start', $data ); 
                
                // Define container
                ?>
                var envira_container_<?php echo $data['id']; ?> = ''; 

                    <?php if ( $this->get_config( 'columns', $data ) == 0 ) : ?>

                    <?php

                        // if the user has selected a custom theme, only output the needed JS
                        $album_theme = $this->get_config( 'justified_gallery_theme', $data );

                    ?>

                        $('#envira-gallery-<?php echo $data["id"]; ?>').enviraJustifiedGallery({
                            rowHeight : <?php echo $this->get_config( 'justified_row_height', $data ); ?>,
                            maxRowHeight: -1,
                            selector: '> div > div', 
                            lastRow: 'nojustify'
                        }); 

                        <?php if ( $album_theme == 'js-desaturate' || $album_theme == 'js-threshold' || $album_theme == 'js-blur' || $album_theme == 'js-vintage' ) : ?>

                        $('#envira-gallery-<?php echo $data["id"]; ?>').on('jg.complete', function (e) {
                            if( navigator.userAgent.match(/msie/i) || $.browser.msie || navigator.appVersion.indexOf('Trident/') > 0 ) {
                                $('#envira-gallery-<?php echo $data["id"]; ?> img').each(function() {
                                    var keep_id = $(this).attr('id');
                                    $(this).attr('id', keep_id + '-effects' );
                                    $(this).wrap('<div class="effect-wrapper" style="display:inline-block;width:' + this.width + 'px;height:' + this.height + 'px;">').clone().addClass('gotcolors').css({'position': 'absolute', 'opacity' : 0, 'z-index' : 1 }).attr('id', keep_id).insertBefore(this);
                                    <?php

                                        switch ($album_theme) {
                                            case 'js-desaturate':
                                                echo 'this.src = jg_effect_desaturate($(this).attr("src"));';
                                                break;
                                            case 'js-threshold':
                                                echo 'this.src = jg_effect_threshold(this.src);';
                                                break;
                                            case 'js-blur':
                                                echo 'this.src = jg_effect_blur(this.src);';
                                                break;
                                            case 'js-vintage':
                                                echo 'jg_effect_vintage( this );';
                                                break;
                                        }

                                    ?>
                                });
                                $('#envira-gallery-<?php echo $data["id"]; ?> img').hover(
                                    function() {
                                        $(this).stop().animate({opacity: 1}, 200);
                                    }, 
                                    function() {
                                        $(this).stop().animate({opacity: 0}, 200);
                                    }
                                );
                            }
                            else {
                                /*$('#envira-gallery-<?php echo $data["id"]; ?> img').each(function() {
                                    $(this).addClass('envira-<?php echo $album_theme; ?>');
                                });*/

                                $('#envira-gallery-<?php echo $data["id"]; ?> img').hover(
                                    function() {
                                        $(this).removeClass('envira-<?php echo $album_theme; ?>');
                                    }, 
                                    function() {
                                        $(this).addClass('envira-<?php echo $album_theme; ?>');
                                    }
                                );
                            }
                            

                        });

                        <?php endif; ?>

                        $('#envira-gallery-<?php echo $data["id"]; ?>').css('opacity', '1');

                    <?php endif; ?>

                <?php
                // Isotope: Start
                if ( $this->get_config( 'isotope', $data ) && $this->get_config( 'columns', $data ) > 0 ) {
                    // Define config for this Isotope Gallery
                    ?>
                    envira_albums_isotopes_config['<?php echo $data['id']; ?>'] = {
                        <?php do_action( 'envira_albums_api_enviratope_config', $data ); ?>
                        itemSelector: '.envira-gallery-item',
                            <?php
                            // If columns = 0, use fitRows
                            // if ( $this->get_config( 'columns', $data ) > 0 ) {
                                ?>
                                masonry: {
                                    columnWidth: '.envira-gallery-item'
                                }
                                <?php /*
                            } else {
                                ?>
                                layoutMode: 'fitRows'
                                <?php
                             } */
                            ?>
                    };
                    <?php
                    // Initialize Isotope
                    ?>
                    envira_albums_isotopes['<?php echo $data['id']; ?>'] = envira_container_<?php echo $data['id']; ?> 
                                                                        = $('#envira-gallery-<?php echo $data['id']; ?>').enviratope(envira_albums_isotopes_config['<?php echo $data['id']; ?>']);
                    <?php
                    // Re-layout Isotope when each image loads
                    ?>
                    envira_albums_isotopes['<?php echo $data['id']; ?>'].enviraImagesLoaded()
                        .done(function() {
                            envira_albums_isotopes['<?php echo $data['id']; ?>'].enviratope('layout');
                        })
                        .progress(function() {
                            envira_albums_isotopes['<?php echo $data['id']; ?>'].enviratope('layout');
                        });
                    <?php 
                    do_action( 'envira_albums_api_enviratope', $data ); 
                }
                // Isotope: End

                // CSS Animations: Start
                if ( $this->get_config( 'css_animations', $data ) ) {
                    $opacity = $this->get_config( 'css_opacity', $data );
                    
                    // Defaults Addon Gallery may not have been saved since opacity introduction, so force a value if one doesn't exist.
                    if ( empty( $opacity ) ) {
                        $opacity = 100;
                    }

                    // Reduce to factor of 1
                    $opacity = ( $opacity / 100 );
                    ?>
                    envira_container_<?php echo $data['id']; ?> = $('#envira-gallery-<?php echo $data['id']; ?>').enviraImagesLoaded( function() {
                        $('.envira-gallery-item img').fadeTo( 'slow', <?php echo $opacity; ?> );
                    });
                    <?php
                }
                // CSS Animations: End
        
                // If lightbox is enabled for this album, load envirabox for each gallery
                if ( isset( $data['config']['lightbox'] ) && $data['config']['lightbox'] == 1 ) { 
                    // We fetch the unfiltered Album, so we have a resultset comprising of
                    // all Galleries belonging to this Album.  This ensures that AJAX pagination works.
                    $data = $this->unfiltered_albums[ $data['id'] ];

                    foreach ( $data['galleryIDs'] as $id ) {    
                        // Build JS array of images and thumbnails to load in the lightbox
                        ?>
                        envira_albums_galleries_images[<?php echo $id; ?>] = [];
                       
                        <?php
                        // Get gallery
                        $gallery = $this->galleryBase->get_gallery( $id );

                        // Allow devs to filter the gallery
                        $gallery = apply_filters( 'envira_albums_shortcode_gallery', $gallery, $id );
                        
                        // Iterate through gallery images, building JS array
                        $count = 0; 
                        if ( isset( $gallery['gallery'] ) ) {
                            foreach ( $gallery['gallery'] as $image_id => $image ) {
                                // If no image ID exists, skip
                                if ( empty( $image_id ) ) {
                                    continue;
                                }

                                // If the album thumbnails option is checked, crop gallery images to the album thumbnail dimensions.
                                if ( $this->get_config( 'thumbnails', $data ) ) {
                                    $image['thumb'] = Envira_Gallery_Common::get_instance()->resize_image( 
                                        $image['src'], 
                                        apply_filters( 'envira_gallery_lightbox_thumbnail_width', $this->get_config( 'thumbnails_width', $data ), $data ),
                                        apply_filters( 'envira_gallery_lightbox_thumbnail_height', $this->get_config( 'thumbnails_height', $data ), $data ),
                                        true,   // Crop
                                        'c',    // Position
                                        100,    // Quality
                                        false   // Retina
                                    );
                                }

                                $image = $this->get_lightbox_src( $image_id, $image, $gallery );
                                $image = apply_filters( 'envira_albums_gallery_lightbox_image', $image, $gallery, $image_id, $data );
                                ?>
                                envira_albums_galleries_images[<?php echo $id; ?>].push({
                                    href: '<?php echo $image['src']; ?>',
                                    id: <?php echo $image_id; ?>,
                                    gallery_id: <?php echo $id; ?>,
                                    alt: '<?php echo addslashes( str_replace( "\n", '<br />', $image['alt'] ) ); ?>',
                                    caption: '<?php echo addslashes( str_replace( "\n", '<br />', $image['caption'] ) ); ?>',
                                    title: '<?php echo addslashes( str_replace( "\n", '<br />', $image['title'] ) ); ?>',
                                    index: <?php echo $count; ?>, 
                                    thumbnail: '<?php echo ( isset( $image['thumb'] ) ? $image['thumb'] : '' ); ?>'
                                    <?php do_action( 'envira_albums_gallery_lightbox_image_attributes', $image, $gallery, $image_id, $data ); ?>
                                });
                                <?php
                                $count++;
                            }
                        }

                        // Define the on click handler against the document, so it fires after an AJAX call
                        ?>
                        $(document).on('click', '.envira-album-gallery-<?php echo $id; ?>', function(e) {
                            e.preventDefault();
                            
                            envira_albums_galleries[<?php echo $id; ?>] = $.envirabox.open(envira_albums_galleries_images[<?php echo $id; ?>], {
                                <?php do_action( 'envira_albums_api_config', $data ); ?>
                                <?php if ( ! $this->get_config( 'keyboard', $data ) ) : ?>
                                keys: 0,
                                <?php endif; ?>
                                <?php if ( in_array( $this->get_config( 'lightbox_theme', $data ), array( 'box_dark', 'box_light' ) ) ) : ?>
                                margin: [0, 0, 198, 0],
                                <?php elseif ( in_array( $this->get_config( 'lightbox_theme', $data ), array( 'burnt_dark', 'burnt_light' ) ) ) : ?>
                                margin: [45, 0, 178, 0],
                                <?php elseif ( in_array( $this->get_config( 'lightbox_theme', $data ), array( 'base_dark', 'base_light' , 'modern-dark', 'modern-light', 'space_dark', 'space_light' ) ) ) : ?>
                                margin: [75, 75, 273, 75],
                                <?php /* increase margin when there is a social bar vertical, default is 40 in fancybox.js */
                                    elseif ( isset($data['config']['social_lightbox']) && $data['config']['social_lightbox'] == 1 && 
                                       ( null !== $data['config']['social_lightbox_orientation'] && $data['config']['social_lightbox_orientation'] == "vertical" )  ):
                                ?>
                                margin: 50,
                                <?php else: ?>
                                margin: 40,
                                <?php endif; ?>

                                arrows: <?php echo in_array( $this->get_config( 'lightbox_theme', $data ), array( 'base_dark', 'base_light' , 'modern-dark', 'modern-light', 'space_dark', 'space_light', 'box_dark', 'box_light', 'burnt_dark', 'burnt_light' ) ) ? 'true' : $this->get_config( 'arrows', $data ); ?>,
                                aspectRatio: <?php echo $this->get_config( 'aspect', $data ); ?>,
                                loop: <?php echo $this->get_config( 'loop', $data ); ?>,
                                mouseWheel: <?php echo $this->get_config( 'mousewheel', $data ); ?>,
                                preload: 1,                                
                                <?php
                                /* Get open and transition effects */
                                $lightbox_open_close_effect = $this->get_config( 'lightbox_open_close_effect', $data );
                                $lightbox_transition_effect = $this->get_config( 'effect', $data );

                                /* Get standard effects */
                                $lightbox_standard_effects = Envira_Gallery_Common::get_instance()->get_transition_effects_values();

                                /* If open/close is standard, use openEffect, closeEffect */
                                if ( in_array( $lightbox_open_close_effect, $lightbox_standard_effects ) ) {
                                    ?>
                                    openEffect: '<?php echo $lightbox_open_close_effect; ?>',
                                    closeEffect: '<?php echo $lightbox_open_close_effect; ?>',
                                    <?php
                                    } else {

                                        // easing effects have been depreciated, and will default back to 'swing'

                                        ?>
                                    openEasing: 'swing', //'<?php echo ( $lightbox_open_close_effect == "swing" ? "swing" : "easeIn" . $lightbox_open_close_effect ); ?>',
                                    closeEasing: 'swing', //'<?php echo ( $lightbox_open_close_effect == "swing" ? "swing" : "easeOut" . $lightbox_open_close_effect ); ?>',
                                    openSpeed: 500,
                                    closeSpeed: 500,
                                    <?php
                                }

                                /* If transition effect is standard, use nextEffect, prevEffect */
                                if ( in_array( $lightbox_transition_effect, $lightbox_standard_effects ) ) {
                                    ?>
                                    nextEffect: '<?php echo $lightbox_transition_effect; ?>',
                                    prevEffect: '<?php echo $lightbox_transition_effect; ?>',
                                    <?php
                                    } else {

                                        // easing effects have been depreciated, and will default back to 'swing'

                                        ?>
                                    nextEasing: 'swing', //'<?php echo ( $lightbox_transition_effect == "swing" ? "swing" : "easeIn" . $lightbox_transition_effect ); ?>',
                                    prevEasing: 'swing', //'<?php echo ( $lightbox_transition_effect == "swing" ? "swing" : "easeOut" . $lightbox_transition_effect ); ?>',
                                    nextSpeed: 600,
                                    prevSpeed: 600,
                                    <?php
                                }
                                ?>
                                tpl: {
                                    wrap     : '<?php echo $this->get_lightbox_template( $data ); ?>',
                                    image    : '<img class="envirabox-image" src="{href}" alt="" data-envira-title="" data-envira-caption="" data-envira-index="" data-envira-data="" />',
                                    iframe   : '<iframe id="envirabox-frame{rnd}" name="envirabox-frame{rnd}" class="envirabox-iframe" frameborder="0" vspace="0" hspace="0" allowtransparency="true"\></iframe>',
                                    error    : '<p class="envirabox-error"><?php echo __( 'The requested content cannot be loaded.<br/>Please try again later.</p>', 'envira-gallery' ); ?>',
                                    closeBtn : '<a title="<?php echo __( 'Close', 'envira-gallery' ); ?>" class="envirabox-item envirabox-close" href="javascript:;"></a>',
                                    next     : '<a title="<?php echo __( 'Next', 'envira-gallery' ); ?>" class="envirabox-nav envirabox-next envirabox-arrows-<?php echo $this->get_config( 'arrows_position', $data ); ?>" href="javascript:;"><span></span></a>',
                                    prev     : '<a title="<?php echo __( 'Previous', 'envira-gallery' ); ?>" class="envirabox-nav envirabox-prev envirabox-arrows-<?php echo $this->get_config( 'arrows_position', $data ); ?>" href="javascript:;"><span></span></a>'
                                }, 
                                helpers: {
                                    <?php do_action( 'envira_albums_api_helper_config', $data ); ?>
                                    title: {
                                        <?php do_action( 'envira_albums_api_title_config', $data ); ?>
                                        type: '<?php echo $this->get_config( 'title_display', $data ); ?>',
                                        <?php if ( in_array( $this->get_config( 'lightbox_theme', $data ), array( 'base_dark', 'base_light' ) ) ) : ?>
                                        alwaysShow: true,
                                        marginOffset: 25,
                                        <?php endif; ?>
                                    },
                                    <?php if ( $this->get_config( 'thumbnails', $data ) ) : ?>
                                    thumbs: {
                                        width: <?php echo apply_filters( 'envira_gallery_lightbox_thumbnail_width', $this->get_config( 'thumbnails_width', $data ), $data ); ?>,
                                        height: <?php echo apply_filters( 'envira_gallery_lightbox_thumbnail_height', $this->get_config( 'thumbnails_height', $data ), $data ); ?>,
                                        source: function(current) {
                                            /* current is our images_id array object */
                                            return current.thumbnail;
                                        },
                                        <?php if ( in_array( $this->get_config( 'lightbox_theme', $data ), array( 'base_dark', 'base_light', 'modern-dark', 'modern-light', 'space_dark', 'space_light', 'box_dark', 'box_light', 'burnt_dark', 'burnt_light' ) ) ) : ?>
                                        dynamicMargin: true,
                                        <?php if ( in_array( $this->get_config( 'lightbox_theme', $data ), array( 'modern-dark', 'modern-light', 'space_dark', 'space_light' ) ) ) : ?>
                                        dynamicMarginAmount: 40,
                                        <?php elseif ( in_array( $this->get_config( 'lightbox_theme', $data ), array( 'box_dark', 'box_light' ) ) ) : ?>

                                        dynamicMarginAmount: 30,
                                        <?php elseif ( in_array( $this->get_config( 'lightbox_theme', $data ), array( 'burnt_dark', 'burnt_light' ) ) ) : ?>

                                        dynamicMarginAmount: 50,
                                        <?php endif; ?>
                                        <?php endif; ?>
                                        position: '<?php echo in_array( $this->get_config( 'lightbox_theme', $data ), array( 'base_dark', 'base_light', 'modern-dark', 'modern-light', 'space_dark', 'space_light' ) ) 
                                                       ? 'bottom' 
                                                       : $this->get_config( 'thumbnails_position', $data ); ?>'
                                    },
                                    <?php endif; ?>
                                    <?php if ( $this->get_config( 'toolbar', $data ) && ! in_array( $this->get_config( 'lightbox_theme', $data ), array( 'base_dark', 'base_light', 'space_dark', 'space_light' ) ) ) : ?>
                                    buttons: {
                                        tpl: '<?php echo $this->get_toolbar_template( $data, $gallery ); ?>',
                                        position: '<?php echo $this->get_config( 'toolbar_position', $data ); ?>',
                                        padding: '<?php echo ( ( $this->get_config( 'toolbar_position', $data ) == 'bottom' && $this->get_config( 'thumbnails', $data ) && $this->get_config( 'thumbnails_position', $data ) == 'bottom' ) ? true : false ); ?>'
                                    },
                                    <?php endif; ?>
                                    <?php if ( in_array( $this->get_config( 'lightbox_theme', $data ), array( 'modern-dark', 'modern-light' ) ) ) : ?>
                                    navDivsRoot: true,
                                    <?php endif; ?>
                                },
                                <?php do_action( 'envira_albums_api_config_callback', $data ); ?>
                                beforeLoad: function(){
                                    if (typeof envira_albums_galleries_images[<?php echo $id; ?>][this.index].caption !== 'undefined') {
                                        this.title = envira_albums_galleries_images[<?php echo $id; ?>][this.index].caption;
                                    } else {
                                        this.title = envira_albums_galleries_images[<?php echo $id; ?>][this.index].title;
                                    }
                                    <?php do_action( 'envira_albums_api_before_load', $data ); ?>
                                },
                                afterLoad: function(current, previous){
                                    <?php do_action( 'envira_albums_api_after_load', $data ); ?>
                                },
                                beforeShow: function(){
                                    
                                    $(window).on({
                                        'resize.envirabox' : function(){
                                            $.envirabox.update();
                                        }
                                    });

                                    /* Set alt, data-envira-title, data-envira-caption and data-envira-index attributes on Lightbox image */
                                    $('img.envirabox-image').attr('alt', envira_albums_galleries_images[<?php echo $id; ?>][this.index].alt)
                                                            .attr('data-envira-gallery-id', <?php echo $id; ?>)
                                                            .attr('data-envira-item-id', envira_albums_galleries_images[<?php echo $id; ?>][this.index].id)
                                                            .attr('data-envira-title', envira_albums_galleries_images[<?php echo $id; ?>][this.index].title)
                                                            .attr('data-envira-caption', envira_albums_galleries_images[<?php echo $id; ?>][this.index].caption)
                                                            .attr('data-envira-index', this.index);

                                    <?php do_action( 'envira_albums_api_before_show', $data ); ?>
                                },
                                afterShow: function(i) {
                                    <?php
                                    if ( $this->get_config( 'mobile_touchwipe', $data ) ) {
                                        ?>
                                        $('.envirabox-wrap').swipe( {
                                            swipe: function(event, direction, distance, duration, fingerCount, fingerData) {
                                                if (direction === 'left') {
                                                    $.envirabox.next(direction);
                                                } else if (direction === 'right') {
                                                    $.envirabox.prev(direction);
                                                } else if (direction === 'up') {
                                                    <?php
                                                    if ( $this->get_config( 'mobile_touchwipe_close', $data ) ) {
                                                        ?>
                                                        $.envirabox.close();
                                                        <?php
                                                    }
                                                    ?>
                                                }
                                            }
                                        } ); 
                                        <?php
                                    }

                                    do_action( 'envira_albums_api_after_show', $data ); ?>
                                },
                                beforeClose: function(){
                                    <?php do_action( 'envira_albums_api_before_close', $data ); ?>
                                },
                                afterClose: function(){
                                    $(window).off('resize.envirabox');
                                    <?php do_action( 'envira_albums_api_after_close', $data ); ?>
                                },
                                onUpdate: function(){
                                    <?php if ( $this->get_config( 'toolbar', $data ) ) : ?>
                                    var envira_buttons_<?php echo $data['id']; ?> = $('#envirabox-buttons li').map(function(){
                                        return $(this).width();
                                    }).get(),
                                        envira_buttons_total_<?php echo $data['id']; ?> = 0;
                                        
                                    $.each(envira_buttons_<?php echo $data['id']; ?>, function(i, val){
                                        envira_buttons_total_<?php echo $data['id']; ?> += parseInt(val, 10);
                                    });
                                    envira_buttons_total_<?php echo $data['id']; ?> += 1;
                                    
                                    $('#envirabox-buttons ul').width(envira_buttons_total_<?php echo $data['id']; ?>);
                                    $('#envirabox-buttons').width(envira_buttons_total_<?php echo $data['id']; ?>).css('left', ($(window).width() - envira_buttons_total_<?php echo $data['id']; ?>)/2);
                                    <?php endif; ?>
                                    <?php do_action( 'envira_albums_api_on_update', $data ); ?>
                                },
                                onCancel: function(){
                                    <?php do_action( 'envira_albums_api_on_cancel', $data ); ?>
                                },
                                onPlayStart: function(){
                                    <?php do_action( 'envira_albums_api_on_play_start', $data ); ?>
                                },
                                onPlayEnd: function(){
                                    <?php do_action( 'envira_albums_api_on_play_end', $data ); ?>
                                }
                            }); 
                        });
        
                        <?php 
                        do_action( 'envira_albums_api_end', $data );
                        
                    } // /foreach gallery
                    
                } // /foreach album
            
            } // /if lightbox enabled

            // Minify before outputting to improve page load time.
            do_action( 'envira_albums_api_end_global' );
            echo $this->minify( ob_get_clean(), false ); ?>});</script>
        <?php

    }

    /**
     * If the Gallery Lightbox config requires a different sized image to be displayed,
     * return that image URL.
     *
     * @since ???
     *
     * @param int $id      The image attachment ID to use.
     * @param array $item  Gallery item data.
     * @param array $data  The gallery data to use for retrieval.
     * @return array       Image
     */
    public function get_lightbox_src( $id, $item, $data ) {

        // Check gallery config
        $size = Envira_Gallery_Shortcode::get_instance()->get_config( 'lightbox_image_size', $data );
       
        // Return if we are serving a full size image
        if ( $size == 'default' || $size == 'full_width' ) {
            return $item;
        }

        // Check the link is a well formed URL
        // If it isn't, it'll be a video, which we don't need to do anything with
        if ( ! filter_var( $item['src'], FILTER_VALIDATE_URL ) ) {
            return $item;
        }

        // Return if the link isn't an image
        // This ensures images with links set to webpages remain that way
        if ( ! @getimagesize( $item['src'] ) ) {
            return $item;
        }

        // Get media library attachment at requested size
        $image = wp_get_attachment_image_src( $id, $size );
        if ( ! is_array( $image ) ) {
            return $item;
        }

        // Inject new image size into $item
        $item['src'] = $image[0];
        
        // Return
        return $item;

    }

    /**
     * Loads a custom album display theme.
     *
     * @since 1.0.0
     *
     * @param string $theme The custom theme slug to load.
     */
    public function load_album_theme( $theme ) {

        // Loop through the available themes and enqueue the one called.
        foreach ( Envira_Gallery_Common::get_instance()->get_gallery_themes() as $array => $data ) {
            if ( $theme !== $data['value'] ) {
                continue;
            }

            if ( file_exists( plugin_dir_path( $data['file'] ) . 'themes/' . $theme . '/style.css' ) ) {
                wp_enqueue_style( $this->base->plugin_slug . $theme . '-theme', plugins_url( 'themes/' . $theme . '/style.css', $data['file'] ), array( $this->base->plugin_slug . '-style' ) );
            }
            else {
                wp_enqueue_style( $this->base->plugin_slug . $theme . '-theme', plugins_url( 'themes/' . $theme . '/css/style.css', $data['file'] ), array( $this->base->plugin_slug . '-style' ) );
            }
            break;
        }

    }

    /**
     * Loads a custom album lightbox theme.
     *
     * @since 1.1.1
     *
     * @param string $theme The custom theme slug to load.
     */
    public function load_lightbox_theme( $theme ) {

        // Loop through the available themes and enqueue the one called.
        foreach ( Envira_Gallery_Common::get_instance()->get_lightbox_themes() as $array => $data ) {
            if ( $theme !== $data['value'] ) {
                continue;
            }

            if ( file_exists( plugin_dir_path( $data['file'] ) . 'themes/' . $theme . '/style.css' ) ) {
                wp_enqueue_style( $this->base->plugin_slug . $theme . '-theme', plugins_url( 'themes/' . $theme . '/style.css', $data['file'] ), array( $this->base->plugin_slug . '-style' ) );
            }
            else {
                wp_enqueue_style( $this->base->plugin_slug . $theme . '-theme', plugins_url( 'themes/' . $theme . '/css/style.css', $data['file'] ), array( $this->base->plugin_slug . '-style' ) );
            }
            break;
        }

    }

    /**
     * Helper method for adding custom album classes.
     *
     * @since 1.1.1
     *
     * @param array $data The album data to use for retrieval.
     * @return string     String of space separated album classes.
     */
    public function get_album_classes( $data ) {

        // Set default class.
        $classes   = array();
        $classes[] = 'envira-gallery-wrap';

        // Add custom class based on data provided.
        $classes[] = 'envira-gallery-theme-' . $this->get_config( 'gallery_theme', $data );
        $classes[] = 'envira-lightbox-theme-' . $this->get_config( 'lightbox_theme', $data );

        // If we have custom classes defined for this gallery, output them now.
        foreach ( (array) $this->get_config( 'classes', $data ) as $class ) {
            $classes[] = $class;
        }

        // If the gallery has RTL support, add a class for it.
        if ( $this->get_config( 'rtl', $data ) ) {
            $classes[] = 'envira-gallery-rtl';
        }

        // Allow filtering of classes and then return what's left.
        $classes = apply_filters( 'envira_albums_output_classes', $classes, $data );
        return trim( implode( ' ', array_map( 'trim', array_map( 'sanitize_html_class', array_unique( $classes ) ) ) ) );

    }

    /**
     * Helper method for adding custom gallery classes.
     *
     * @since 1.0.4
     *
     * @param array $item Array of item data.
     * @param int $i      The current position in the gallery.
     * @param array $data The gallery data to use for retrieval.
     * @return string     String of space separated gallery item classes.
     */
    public function get_gallery_item_classes( $item, $i, $data ) {

        // Set default class.
        $classes   = array();
        $classes[] = 'envira-gallery-item';
        $classes[] = 'enviratope-item';
        $classes[] = 'envira-gallery-item-' . $i;

        // Allow filtering of classes and then return what's left.
        $classes = apply_filters( 'envira_albums_output_item_classes', $classes, $item, $i, $data );
        return trim( implode( ' ', array_map( 'trim', array_map( 'sanitize_html_class', array_unique( $classes ) ) ) ) );

    }

    /**
     * Helper method to retrieve the proper image src attribute based on gallery settings.
     *
     * @since 1.0.0
     *
     * @param int $id      The image attachment ID to use.
     * @param array $item  Gallery item data.
     * @param array $data  The gallery data to use for retrieval.
     * @param bool $mobile Whether or not to retrieve the mobile image.
     * @return string      The proper image src attribute for the image.
     */
    public function get_image_src( $id, $item, $data, $mobile = false ) {
        
        // Detect if user is on a mobile device - if so, override $mobile flag which may be manually set
        // by out of date addons or plugins
        if ( $this->get_config( 'mobile', $data ) ) {
            $mobile = wp_is_mobile();
        }

        // Get the full image src. If it does not return the data we need, return the image link instead.
        $image = ( isset( $item['cover_image_url'] ) ? $item['cover_image_url'] : '' );

        // Fallback to image ID
        if ( empty( $image ) ) {
            $src   = wp_get_attachment_image_src( $id, 'full' );
            $image = ! empty( $src[0] ) ? $src[0] : false;
        }
        
        // Fallback to item source
        if ( ! $image ) {
            $image = ! empty( $item['src'] ) ? $item['src'] : false;
            if ( ! $image ) {
                return apply_filters( 'envira_album_no_image_src', $id, $item, $data );
            }
        }
        
        // Resize or crop image
        // This is safe to call every time, as resize_image() will check if the image already exists, preventing thumbnails
        // from being generated every single time.
        $type = $mobile ? 'mobile' : 'crop'; // 'crop' is misleading here - it's the key that stores the thumbnail width + height      
        $common = Envira_Gallery_Common::get_instance();
        $args   = apply_filters( 'envira_gallery_crop_image_args',
            array(
                'position' => 'c',
                'width'    => $this->get_config( $type . '_width', $data ),
                'height'   => $this->get_config( $type . '_height', $data ),
                'quality'  => 100,
                'retina'   => false
            )
        );
        $resized_image = $common->resize_image( $image, $args['width'], $args['height'], $this->get_config( 'crop', $data ), $args['position'], $args['quality'], $args['retina'], $data );
        
        // If there is an error, possibly output error message and return the default image src.
        if ( is_wp_error( $resized_image ) ) {
            // If debugging is defined, print out the error.
            if ( defined( 'ENVIRA_GALLERY_CROP_DEBUG' ) && ENVIRA_GALLERY_CROP_DEBUG ) {
                echo '<pre>' . var_export( $resized_image->get_error_message(), true ) . '</pre>';
            }

            // Return the non-cropped image as a fallback.
            return apply_filters( 'envira_gallery_image_src', $image, $id, $item, $data );
        } else {
            return apply_filters( 'envira_gallery_image_src', $resized_image, $id, $item, $data );
        }
    }

    /**
     * Helper method to retrieve the proper gallery toolbar template.
     *
     * @since 1.0.0
     *
     * @param array $data Array of gallery data.
     * @return string     String template for the gallery toolbar.
     */
    public function get_toolbar_template( $data, $gallery ) {
        
        // Build out the custom template based on options chosen.
        $template  = '<div id="envirabox-buttons">';
            $template .= '<ul>';
                $template  = apply_filters( 'envira_albums_toolbar_start', $template, $data, $gallery );
                
                // Prev
                $template .= '<li><a class="btnPrev" title="' . __( 'Previous', 'envira-gallery' ) . '" href="javascript:;"></a></li>';
                $template  = apply_filters( 'envira_albums_toolbar_after_prev', $template, $data, $gallery  );
                
                // Next
                $template .= '<li><a class="btnNext" title="' . __( 'Next', 'envira-gallery' ) . '" href="javascript:;"></a></li>';
                $template  = apply_filters( 'envira_albums_toolbar_after_next', $template, $data, $gallery  );
                
                // Title
                if ( $this->get_config( 'toolbar_title', $data ) ) {
                    $template .= '<li id="envirabox-buttons-title"><span>' . htmlentities( $gallery['config']['title'], ENT_QUOTES ) . '</span></li>';
                    $template  = apply_filters( 'envira_albums_toolbar_after_title', $template, $data, $gallery  );   
                }
                
                // Close
                $template .= '<li><a class="btnClose" title="' . __( 'Close', 'envira-gallery' ) . '" href="javascript:;"></a></li>';
                $template  = apply_filters( 'envira_albums_toolbar_after_close', $template, $data, $gallery  );
                
                $template  = apply_filters( 'envira_albums_toolbar_end', $template, $data, $gallery  );
            $template .= '</ul>';
        $template .= '</div>';

        // Return the template, filters applied and all.
        return apply_filters( 'envira_albums_toolbar', $template, $data );
        
    }

    /**
    * Helper method to retrieve the gallery lightbox template
    *
    * @since 1.1.0.1
    *
    * @param array $data Array of album data
    * @return string String template for the gallery lightbox
    */
    public function get_lightbox_template( $data ) {

        // Build out the lightbox template
        $envirabox_wrap_css_classes = apply_filters( 'envirabox_wrap_css_classes', 'envirabox-wrap', $data );

        $envirabox_theme = apply_filters( 'envirabox_theme', 'envirabox-theme-' . $this->get_config( 'lightbox_theme', $data ), $data );
        
        $template = '<div class="' . $envirabox_wrap_css_classes . '" tabIndex="-1"><div class="envirabox-skin ' . $envirabox_theme . '"><div class="envirabox-outer"><div class="envirabox-inner">';

        // Lightbox Inner above
        $template = apply_filters( 'envirabox_inner_above', $template, $data );

        // Top Left box
        $template .= '<div class="envirabox-position-overlay envira-gallery-top-left">';
        $template  = apply_filters( 'envirabox_output_dynamic_position', $template, $data, 'top-left' );
        $template .= '</div>';

        // Top Right box
        $template .= '<div class="envirabox-position-overlay envira-gallery-top-right">';
        $template  = apply_filters( 'envirabox_output_dynamic_position', $template, $data, 'top-right' );
        $template .= '</div>';

        // Bottom Left box
        $template .= '<div class="envirabox-position-overlay envira-gallery-bottom-left">';
        $template  = apply_filters( 'envirabox_output_dynamic_position', $template, $data, 'bottom-left' );
        $template .= '</div>';

        // Bottom Right box
        $template .= '<div class="envirabox-position-overlay envira-gallery-bottom-right">';
        $template  = apply_filters( 'envirabox_output_dynamic_position', $template, $data, 'bottom-right' );
        $template .= '</div>';

        // Lightbox Inner below
        $template = apply_filters( 'envirabox_inner_below', $template, $data );

        $template .= '</div></div></div></div>';

        // Return the template, filters applied
        return apply_filters( 'envira_albums_lightbox_template', str_replace( "\n", '', $template ), $data );

    }

    /**
     * Helper method for retrieving config values.
     *
     * @since 1.0.0
     *
     * @param string $key The config key to retrieve.
     * @param array $data The gallery data to use for retrieval.
     * @return string     Key value on success, default if not set.
     */
    public function get_config( $key, $data ) {

        $instance = Envira_Albums_Common::get_instance();

        // If we are on a mobile device, some config keys have mobile equivalents, which we need to check instead
        if ( wp_is_mobile() ) {
            $mobile_keys = array(
                'columns'           => 'mobile_columns',
                'lightbox_enabled'  => 'mobile_lightbox',
                'arrows'            => 'mobile_arrows',
                'toolbar'           => 'mobile_toolbar',
                'thumbnails'        => 'mobile_thumbnails',
            );
            $mobile_keys = apply_filters( 'envira_albums_get_config_mobile_keys', $mobile_keys );

            if ( array_key_exists( $key, $mobile_keys ) ) {
                // Use the mobile array key to get the config value
                $key = $mobile_keys[ $key ];
            }
        }

        return isset( $data['config'][ $key ] ) ? $data['config'][ $key ] : $instance->get_config_default( $key );

    }

    /**
     * Helper method to minify a string of data.
     *
     * @since 1.0.4
     *
     * @param string $string  String of data to minify.
     * @return string $string Minified string of data.
     */
    public function minify( $string, $stripDoubleForwardslashes = true ) {

        // Added a switch for stripping double forwardslashes
        // This can be disabled when using URLs in JS, to ensure http:// doesn't get removed
        // All other comment removal and minification will take place
        $stripDoubleForwardslashes = apply_filters( 'envira_minify_strip_double_forward_slashes', $stripDoubleForwardslashes );
        
        if ( $stripDoubleForwardslashes ) {
            $clean = preg_replace( '/((?:\/\*(?:[^*]|(?:\*+[^*\/]))*\*+\/)|(?:\/\/.*))/', '', $string );
        } else {
            // Use less aggressive method
            $clean = preg_replace( '!/\*.*?\*/!s', '', $string );
            $clean = preg_replace( '/\n\s*\n/', "\n", $clean );
        }
        
        $clean = str_replace( array( "\r\n", "\r", "\t", "\n", '  ', '    ', '     ' ), '', $clean );

        return apply_filters( 'envira_gallery_minified_string', $clean, $string );

    }

    /**
     * I'm sure some plugins mean well, but they go a bit too far trying to reduce
     * conflicts without thinking of the consequences.
     *
     * 1. Prevents Foobox from completely borking envirabox as if Foobox rules the world.
     *
     * @since 1.0.0
     */
    public function plugin_humility() {

        if ( class_exists( 'fooboxV2' ) ) {
            remove_action( 'wp_footer', array( $GLOBALS['foobox'], 'disable_other_lightboxes' ), 200 );
        }

    }

    /**
     * Outputs only the first gallery of the album inside a regular <div> tag
     * to avoid styling issues with feeds.
     *
     * @since 1.0.5
     *
     * @param array $data      Array of album data.
     * @return string $gallery Custom album output for feeds.
     */
    public function do_feed_output( $data ) {

        // Check the album has galleries
        if ( ! isset( $data['galleries'] ) || count( $data['galleries'] ) == 0 ) {
            return '';
        }

        // Iterate through albums, getting the first image of the first gallery
        $gallery = '<div class="envira-gallery-feed-output">';
            foreach ( $data['galleries'] as $id => $item ) {
                $imagesrc = $this->get_image_src( $item['cover_image_id'], $item, $data );
                $gallery .= '<img class="envira-gallery-feed-image" src="' . esc_url( $imagesrc ) . '" title="' . trim( esc_html( $item['title'] ) ) . '" alt="' .trim( esc_html( $item['alt'] ) ) . '" />';
                break;
             }
        $gallery .= '</div>';

        return apply_filters( 'envira_gallery_feed_output', $gallery, $data );

    }

    /**
     * Returns a set of indexable image links to allow SEO indexing for preloaded images.
     *
     * @since 1.0.0
     *
     * @param mixed $id       The slider ID to target.
     * @return string $images String of indexable image HTML.
     */
    public function get_indexable_images( $id ) {

        // If there are no images, don't do anything.
        $images = '';
        $i      = 1;
        if ( empty( $this->index[$id] ) ) {
            return $images;
        }

        foreach ( (array) $this->index[$id] as $attach_id => $data ) {
            $images .= '<img src="' . esc_url( $data['src'] ) . '" alt="' . esc_attr( $data['alt'] ) . '" />';
            $i++;
        }

        return apply_filters( 'envira_gallery_indexable_images', $images, $this->index, $id );

    }

    /**
     * Returns the singleton instance of the class.
     *
     * @since 1.0.0
     *
     * @return object The Envira_Albums_Shortcode object.
     */
    public static function get_instance() {

        if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Envira_Albums_Shortcode ) ) {
            self::$instance = new Envira_Albums_Shortcode();
        }

        return self::$instance;

    }

}

// Load the shortcode class.
$envira_albums_shortcode = Envira_Albums_Shortcode::get_instance();