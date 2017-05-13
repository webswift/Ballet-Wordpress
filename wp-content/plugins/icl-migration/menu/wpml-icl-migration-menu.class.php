<?php
require WPML_ICLM_PATH . '/inc/wpml-icl-migration.class.php';

class WPML_ICL_Migration_Display {

	/** @var WPML_ICL_Migration $wpml_icl_migration */
	private $wpml_icl_migration;

	/**
	 * @var int $not_migrated_count
	 */
	private $not_migrated_count;

	/**
	 * Set to true once a translation project for the website's icl account has been created.
	 * @var bool $account_migrated
	 */
	private $account_migrated;

	public function init() {
		$networking               = wpml_tm_load_tp_networking();
		$this->wpml_icl_migration = new WPML_ICL_Migration( $networking );

		add_action( 'wp_ajax_upgrade_icl_account', array( $this, 'upgrade_icl_account' ) );
		add_action( 'wp_ajax_show_translator_list', array( $this, 'show_translator_list' ) );
		add_action( 'wp_ajax_migrate_one_job', array( $this, 'migrate_one_job' ) );
		add_action( 'wp_ajax_upgrade_icl_jobs', array( $this, 'upgrade_icl_jobs' ) );
		add_action( 'wp_ajax_icl_job_count', array( $this, 'icl_job_count' ) );
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
	}

	public function upgrade_icl_account() {
		if ( wpml_is_action_authenticated( 'upgrade_icl_account' ) ) {
			$migrated = $this->wpml_icl_migration->does_icl_account_need_upgrade();
			list( , $error_data ) = $this->wpml_icl_migration->enqueue_migration_in_tp();
			if ( (bool) $migrated && ! $error_data ) {
				wp_send_json_success( true );
			} else {
				$error_data['project']['ts_accesskey'] = md5( $error_data['project']['ts_accesskey'] );
				wp_send_json_error( '</br>' . __( 'Your ICanLocalize account could not be migrated to WPML 3.2, please contact the WPML support.', 'wpml-translation-management' )
				                    . '</br><div style="border:solid;" id="iclm-error">' . json_encode( $error_data ) . '</div>' );
			}
		}

		wp_send_json_error( 'Wrong Nonce' );
	}

	public function icl_job_count() {
		if ( wpml_is_action_authenticated( 'upgrade_icl_account' ) ) {
			wp_send_json_success( $this->wpml_icl_migration->get_icl_job_count() );
		}

		wp_send_json_error( 'Wrong Nonce' );
	}

	/**
	 * Gets a json encoded list of the translators that the ICL API returns for the site.
	 */
	public function show_translator_list() {
		if ( wpml_is_action_authenticated( 'upgrade_icl_account' ) ) {
			wp_send_json_success( json_encode( $this->wpml_icl_migration->get_icl_translators() ) );
		} else {
			wp_send_json_error( 'Wrong Nonce' );
		}
	}

	public function add_admin_menu() {
		$top_page = apply_filters( 'icl_menu_main_page', basename( ICL_PLUGIN_PATH ) . '/menu/languages.php' );

		$menu_label = __( 'Migrate ICanLocalize Translation to WPML 3.2', 'wpml-translation-management' );
		add_submenu_page( $top_page, $menu_label, $menu_label, 'wpml_manage_translation_management', WPML_TM_FOLDER . '/menu/migration.php', array(
			$this,
			'display_icl_migration_status'
		) );
	}

	public function display_icl_migration_status() {
		?>
		<div id="wpml-icl-migration-wrap">
			<h2><?php _e( 'Migrate ICanLocalize Translation to WPML 3.2', 'wpml-translation-management' ) ?></h2>
			<?php echo $this->display_existing_icl_account() ?>
			<?php echo $this->display_existing_jobs() ?>
		</div>
		<?php
		wp_nonce_field( 'upgrade_icl_account_nonce', 'upgrade_icl_account_nonce' );
		wp_enqueue_script( 'wpml_icl_migration_js', WPML_ICLM_URL . '/res/js/wpml-icl-migration.js', array( 'jquery' ) );
	}

	private function migrate_button( $action, $caption, $disabled = false ) {

		$disabled_string = $disabled ? ' disabled="disabled" ' : '';

		return '<button class="button-secondary" id="wpml_icl_' . $action . '"' . $disabled_string . '>' . $caption . '</button>';
	}

	public function upgrade_icl_jobs() {
		if ( wpml_is_action_authenticated( 'upgrade_icl_account' ) ) {
			wp_send_json_success( json_encode( $this->wpml_icl_migration->check_translator_availability() ) );
		}

		wp_send_json_error( 'Wrong Nonce' );
	}

	public function migrate_one_job() {
		if ( wpml_is_action_authenticated( 'upgrade_icl_account' ) ) {
			wp_send_json_success( $this->wpml_icl_migration->migrate_one_icl_job() );
		} else {
			wp_send_json_error( 'Wrong Nonce' );
		}
	}

	private function display_existing_jobs() {
		$existing_job_count       = $this->wpml_icl_migration->get_icl_job_count();
		$this->not_migrated_count = $existing_job_count;
		$hidden                   = $existing_job_count > 0 ? '' : 'style="display:none;"';
		$html                     = '<div ' . $hidden . '><p>' . sprintf( __( 'You do have <span id="icl-jobs-left-count">%d</span> job(s) in progress with ICanLocalize that have not yet been migrated to Translation Proxy.',
				'wpml-translation-management' ),
				$existing_job_count ) .'</p>' . $this->migrate_button( 'migrate_jobs',
				__( 'Migrate Jobs to Translation Proxy',
					'wpml-translation-management' ),
				! $this->account_migrated ) . '</div>';

		return $this->render_paragraph( 'p_icl_jobs_exist_count', $html );
	}

	private function display_existing_icl_account() {
		$html = 'It looks like you have not used ICanLocalize before. This plugin cannot do anything for your site. Please go to the Plugins admin and disable it.';
		if ( (bool) $this->wpml_icl_migration->does_icl_account_need_upgrade() === true ) {
			$html                   = '<p>' . __( 'This site has an account correctly registered with ICanLocalize and WPML 3.2.', 'wpml-translation-management' ) . '</p>' . $this->migrate_button( 'migrate_account', __( 'Migrate Account to WPML 3.2', 'wpml-translation-management' ) );
			$this->account_migrated = false;
		} elseif ( (bool) ( $icl_account = $this->wpml_icl_migration->icl_account_in_tp() ) === true ) {
			$html                   = '<p>' . __( 'ICanLocalize is now re-connected to your site. This plugin did its job and is no longer needed. Please go to the Plugins admin and deactivate "Migrate ICanLocalize Translation to WPML 3.2".', 'wpml-translation-management' ) . '</p>';
			$this->account_migrated = true;
		}

		return $this->render_paragraph( 'p_icl_account_exists', $html );
	}

	private function render_paragraph( $id, $content ) {

		return '<div id="' . $id . '">' . $content . '</div>';
	}
}
