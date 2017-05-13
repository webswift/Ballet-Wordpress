<?php

function load_icl_migration() {
	global $sitepress, $wpdb;

	$loader = new WPML_ICLM_Loader( $sitepress, $wpdb );
	$loader->load_icl_migration();
}

function set_icl_job_service_ids() {
	global $wpdb, $pagenow;

	if ( $pagenow === 'plugins.php' && $wpdb->get_var( "SHOW TABLES LIKE '{$wpdb->prefix}icl_translation_status'" ) ) {
		$changed = $wpdb->update(
			$wpdb->prefix . 'icl_translation_status',
			array( 'translation_service' => 'icl' ),
			array( 'translation_service' => 'icanlocalize' ) );
		if ( $changed ) {
			add_action( 'init', 'reload_plugins_page' );
		}
	}
}

function filter_tp_services( $services ) {

	foreach ( $services as $key => $value ) {
		if ( $value->suid === ICLM_ICL_SUID ) {
			unset( $services[ $key ] );
		}
	}

	return $services;
}

function backup_account_credentials() {
	global $sitepress;

	if ( ! $sitepress->get_setting( 'iclm_backup_credentials' ) ) {
		$site_id    = $sitepress->get_setting( 'site_id' );
		$access_key = $sitepress->get_setting( 'access_key' );
		$sitepress->set_setting( 'iclm_backup_credentials', array(
			'site_id'    => $site_id,
			'access_key' => $access_key
		), true );
	}
}

function reload_plugins_page() {
	wp_redirect( admin_url( 'plugins.php' ) );
	exit;
}