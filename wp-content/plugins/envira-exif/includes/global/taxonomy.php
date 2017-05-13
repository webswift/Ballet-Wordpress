<?php
/**
 * Taxonomy class.
 *
 * @since 1.0.0
 *
 * @package Envira_Exif
 * @author  Tim Carr
 */
class Envira_Exif_Taxonomy {

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
     * @since 1.0.5
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

        // Build the labels for the Manufacturers taxonomy
        $manufacturer_labels = array(
			'name'                       => __( 'Manufacturers', 'envira-exif' ),
			'singular_name'              => __( 'Manufacturer', 'envira-exif' ),
			'search_items'               => __( 'Search Manufacturers', 'envira-exif' ),
			'popular_items'              => __( 'Popular Manufacturers', 'envira-exif' ),
			'all_items'                  => __( 'All Manufacturers', 'envira-exif' ),
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'                  => __( 'Edit Manufacturer', 'envira-exif' ),
			'update_item'                => __( 'Update Manufacturer', 'envira-exif' ),
			'add_new_item'               => __( 'Add New Manufacturer', 'envira-exif' ),
			'new_item_name'              => __( 'New Manufacturer Name', 'envira-exif' ),
			'separate_items_with_commas' => __( 'Separate manufacturers with commas', 'envira-exif' ),
			'add_or_remove_items'        => __( 'Add or remove manufacturers', 'envira-exif' ),
			'choose_from_most_used'      => __( 'Choose from the most used manufacturers', 'envira-exif' ),
			'not_found'                  => __( 'No manufacturers found.', 'envira-exif' ),
			'menu_name'                  => __( 'Manufacturers', 'envira-exif' ),
		);
		$manufacturer_labels = apply_filters( 'envira_exif_manufacturer_taxonomy_labels', $manufacturer_labels );
	
		// Build the taxonomy arguments for the Manufacturers taxonomy
		$manufacturer_args = array(
			'hierarchical'          => true,
			'labels'                => $manufacturer_labels,
			'show_ui'               => true,
			'query_var'             => true,
			'rewrite'               => array( 'slug' => 'envira-exif-manufacturer' ),
		);
		$manufacturer_args = apply_filters( 'envira_exif_manufacturer_taxonomy_args', $manufacturer_args );
	
		// Register the taxonomies with WordPress.
		register_taxonomy( 'envira-exif-manufacturer', 'attachment', $manufacturer_args );
		
		// Move registered taxonomy menu items from Media to Envira Gallery
		if ( is_admin() ) {
			add_action( 'admin_menu', array( $this, 'move_taxonomy_menu_items' ) );
		}
		
    }
    
    /**
	 * Moves taxonomy menu items from Media to Envira Gallery.
	 *
	 * @since 1.0.0
	 *
	 * @return null
	*/
	function move_taxonomy_menu_items() {
	
		add_submenu_page( 'edit.php?post_type=envira', __( 'Manufacturers', 'envira-exif' ), __( 'Manufacturers', 'envira-exif' ), 'edit_others_posts', 'edit-tags.php?taxonomy=envira-exif-manufacturer&post_type=envira');
	
	}

    /**
     * Returns the singleton instance of the class.
     *
     * @since 1.0.0
     *
     * @return object The Envira_Exif_Taxonomy object.
     */
    public static function get_instance() {

        if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Envira_Exif_Taxonomy ) ) {
            self::$instance = new Envira_Exif_Taxonomy();
        }

        return self::$instance;

    }

}

// Load the taxonomy class.
$envira_exif_taxonomy = Envira_Exif_Taxonomy::get_instance();