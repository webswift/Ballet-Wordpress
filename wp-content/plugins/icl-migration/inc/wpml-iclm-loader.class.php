<?php

class WPML_ICLM_Loader {

	/** @var  SitePress $sitepress */
	private $sitepress;

	/** @var wpdb $wpdb */
	private $wpdb;

	public function __construct( &$sitepress, &$wpdb ) {
		$this->wpdb      = $wpdb;
		$this->sitepress = $sitepress;
	}

	public function load_icl_migration() {
		if ( ! $this->sitepress || (bool) $this->sitepress->get_setting( 'wpml_icl_migration_completed' ) === false ) {
			require_once WPML_ICLM_PATH . '/menu/wpml-icl-migration-menu.class.php';
			$migration_menu = new WPML_ICL_Migration_Display();
			$migration_menu->init();
		}
	}
}
