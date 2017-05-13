<?php
/*
Plugin Name: Migrate ICanLocalize Translation to WPML 3.2
Plugin URI: https://wpml.org/
Description: Allows downloading existing Translation Jobs in ICanLocalize that were created before  WPML version 3.2 | <a href="https://wpml.org">Documentation</a>
Author: OnTheGoSystems
Author URI: http://www.onthegosystems.com/
Version: 1.1
Plugin Slug: wpml-icl-migration
*/

define( 'WPML_ICLM_VERSION', '1.1' );
define( 'WPML_ICLM_PATH', dirname( __FILE__ ) );
define( 'ICLM_ICL_SUID', '6ab1000a33e2cc9ecbcf6abc57254be8' );
require WPML_ICLM_PATH . '/inc/constants.php';
require WPML_ICLM_PATH . '/inc/functions-load.php';
require WPML_ICLM_PATH . '/inc/wpml-iclm-loader.class.php';
set_icl_job_service_ids();
add_filter( 'otgs_translation_get_services', 'filter_tp_services' );
add_action( 'wpml_tm_loaded', 'load_icl_migration' );
