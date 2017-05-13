<?php
/**
* Plugin Name: Instagram Portfolio
* Plugin URI: http://instagramwordpress.rafsegat.com/
* Description: Beautiful, modern and responsive Instagram Portfolio Wordpress Plugin.
* Version: 1.3
* Author: Rafael Segat
* Author URI: http://rafsegat.com/
**/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


try{


	///////////////////////////////////////
	//// LETS DEFINE OUR CONSTANTS
	///////////////////////////////////////

	///// PLUGIN VERSION
	if( !defined( 'SEG_INSTAPORTFOLIO_VERSION' ) ) define( 'SEG_INSTAPORTFOLIO_VERSION', '1.3' );

	///// THIS IS OUR PLUGIN CURRENT FILE
	if( !defined( 'SEG_INSTAPORTFOLIO_CURRENT_FILE' ) ) define( 'SEG_INSTAPORTFOLIO_CURRENT_FILE', __FILE__ );

	//// THIS CONSTANT IS OUT PLUFIN CURRENT FOLDER
	if( !defined( 'SEG_INSTAPORTFOLIO_CURRENT_FOLDER' ) ) define( 'SEG_INSTAPORTFOLIO_CURRENT_FOLDER', dirname( __FILE__ ) );

	// The URL path of this plugin
	if( !defined( 'SEG_INSTAPORTFOLIO_URLPATH' ) ) define( 'SEG_INSTAPORTFOLIO_URLPATH', WP_PLUGIN_URL . "/" . plugin_basename( SEG_INSTAPORTFOLIO_CURRENT_FOLDER ) );

	// Is the current request Ajax?
	if( !defined( 'IS_AJAX_REQUEST' ) ) define( 'IS_AJAX_REQUEST', ( !empty( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && strtolower( $_SERVER['HTTP_X_REQUESTED_WITH'] ) == 'xmlhttprequest' ) );

	require_once SEG_INSTAPORTFOLIO_CURRENT_FOLDER . '/seg_instaportfolio_admin.php';

	global $seg_instaportfolio_admin;

	if (class_exists("seg_instaportfolio_admin")) {

		$seg_instaportfolio_admin = new seg_instaportfolio_admin();

	}


	///// LETS CREATE OUR WIDGET
	class seg_instaportfolio_widget extends WP_Widget {
		public function __construct() {
			$widget_ops = array( 
				'classname' => 'instagram_widget',
				'description' => 'Instagram Portfolio Widget',
			);
			parent::__construct( 'instagram_widget', 'Instagram Portfolio', $widget_ops );
		}
		function widget( $args, $instance ) {
			// Widget output
			$seg_instaportfolio_admin = new seg_instaportfolio_admin();
			$seg_instaportfolio_admin->seg_instaportfolio_widget();
		}
		function update( $new_instance, $old_instance ) {
			// Save widget options
		}
		function form( $instance ) {
			// Output admin widget options form
		}
	}

	function myplugin_register_widgets() {
		register_widget( 'seg_instaportfolio_widget' );
	}
	add_action( 'widgets_init', 'myplugin_register_widgets' );


}catch(Exception $e){

	$message = $e->getMessage();
	$trace = $e->getTraceAsString();
	echo "Instagram Portfolio Error: <b>".$message."</b>";

}


?>
