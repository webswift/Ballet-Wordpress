<?php

class WPML_ICL_Migration {
	/** @var  WPML_Translation_Proxy_Networking $networking */
	private $networking;

	function __construct( &$networking ) {
		wpml_load_core_tm();
		$this->networking = $networking;
	}

	public function get_icl_job_count() {
		global $wpdb;

		return $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(translation_id)
								FROM {$wpdb->prefix}icl_translation_status
								WHERE translation_service = 'icl'
									AND status = %d ", ICL_TM_IN_PROGRESS ) );
	}

	public function check_translator_availability() {
		global $wpdb;

		remove_filter( 'otgs_translation_get_services', 'filter_tp_services' );

		$data = $wpdb->get_results( "SELECT s.translation_id, s.translator_id, t.language_code, t.source_language_code
									 FROM {$wpdb->prefix}icl_translations AS t
									 JOIN {$wpdb->prefix}icl_translation_status AS s
										ON s.translation_id = t.translation_id
									 WHERE s.translation_service = 'icl'" );

		$issues          = array();
		$available       = array();
		$translator_data = $this->get_icl_translators();

		foreach ( $data as $item ) {
			if ( $this->find_translator_in_data( $translator_data,
					$item->source_language_code,
					$item->language_code,
					$item->translator_id ) === true
			) {
				$available[] = $item->translation_id;
			} else {
				$issues[] = $item->translation_id;
			}
		}

		return array( 'issues' => $issues, 'ok' => $available );
	}

	public function migrate_one_icl_job() {
		global $wpdb;

		$translation = $wpdb->get_row( $wpdb->prepare(
			" 	SELECT s.translation_id, s.status, s.rid, j.job_id
											FROM {$wpdb->prefix}icl_translation_status s
											JOIN (SELECT MAX(jj.job_id) AS job_id, jj.rid FROM {$wpdb->prefix}icl_translate_job jj GROUP BY jj.rid) AS j
												ON j.rid = s.rid
											WHERE s.translation_service = 'icl'
												AND s.status = %d
											LIMIT 1", ICL_TM_IN_PROGRESS ) );
		if ( ! $translation ) {
			return 0;
		}
		$translation_id = $translation->translation_id;
		$batch_id       = TranslationProxy_Batch::update_translation_batch( 'ICanLocalize Migration ' . $translation_id );
		$wpdb->update( $wpdb->prefix . 'icl_translation_status',
			array(
				'batch_id' => $batch_id,
				'status'   => ICL_TM_COMPLETE
			),
			array( 'translation_id' => $translation_id ) );
		$job = new WPML_Post_Translation_Job( $translation->job_id );
		$job->load_terms_from_post_into_job();
		$this->delete_legacy_term_job_rows( $translation->job_id );
		$rev = $wpdb->get_var( $wpdb->prepare( "SELECT max(revision) FROM {$wpdb->prefix}icl_translate_job WHERE rid = %d AND job_id < %d LIMIT 1", $translation->rid, $translation->job_id ) );
		$wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->prefix}icl_translate_job SET revision = %d WHERE job_id = %d", $rev + 1, $translation->job_id ) );
		$this->add_translation_id_to_basket( $translation_id );
		$this->commit_basket( $translation_id );

		return $this->get_icl_job_count();
	}

	private function delete_legacy_term_job_rows( $job_id ) {
		global $wpdb;

		$wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->prefix}icl_translate WHERE job_id = %d AND field_format = 'csv_base64'", $job_id ) );
	}

	public function does_icl_account_need_upgrade() {
		global $sitepress;

		remove_filter( 'otgs_translation_get_services', 'filter_tp_services' );
		try {
			$site_id       = $sitepress->get_setting( 'site_id' );
			$access_key    = $sitepress->get_setting( 'access_key' );
			$icl_service   = TranslationProxy_Service::get_service( $this->get_icl_service_id() );
			$project_index = TranslationProxy_Project::generate_service_index( $icl_service );
			$projects      = TranslationProxy::get_translation_projects();
		} catch ( TranslationProxy_Api_Error $e ) {
			return false;
		}

		return $site_id
		       && $access_key
		       && ! ( (bool) $projects
		              && is_array( $projects )
		              && isset( $projects[ $project_index ] )
		              && isset( $projects[ $project_index ]['ts_id'] )
		              && $projects[ $project_index ]['ts_id'] == $site_id
		              && isset( $projects[ $project_index ]['ts_access_key'] )
		              && $projects[ $project_index ]['ts_access_key'] == $access_key );
	}

	public function icl_account_in_tp() {
		remove_filter( 'otgs_translation_get_services', 'filter_tp_services' );

		try {
			$project_index = $this->icl_project_hash_key();
			$projects      = TranslationProxy::get_translation_projects();
		} catch ( TranslationProxy_Api_Error $e ) {
			return false;
		}

		return $project_index && $projects && isset( $projects[ $project_index ] ) ? $projects[ $project_index ]
			: false;
	}

	public function enqueue_migration_in_tp() {
		global $sitepress;

		remove_filter( 'otgs_translation_get_services', 'filter_tp_services' );

		$site_id    = $sitepress->get_setting( 'site_id' );
		$access_key = $sitepress->get_setting( 'access_key' );
		$delivery   = wpml_get_setting_filter( false, 'translation_pickup_method' );
		$delivery   = $delivery === 'polling' ? 'polling' : 'xmlrpc';
		$service    = TranslationProxy_Service::get_service( $this->get_icl_service_id() );

		$params = array(
			'project' => array(
				'ts_accesskey'    => $access_key,
				'external_id'     => $site_id,
				'delivery_method' => $delivery,
				'url'             => get_option( 'siteurl' ),
				'name'            => get_option( 'blogname' ),
				'description'     => get_option( 'blogdescription' ),
				'suid'            => isset( $service->suid ) ? $service->suid : ""
			)
		);

		$project    = false;
		$error_data = false;
		try {
			$response = TranslationProxy_Api::proxy_request( '/projects/migrate.json', $params, 'POST' );

			if ( isset( $response->project ) ) {
				$project                    = (array) $response->project;
				$project_index              = $this->icl_project_hash_key();
				$projects                   = TranslationProxy::get_translation_projects();
				$projects[ $project_index ] = $project;
				icl_set_setting( 'icl_translation_projects', $projects, true );
				icl_set_setting( 'translation_service',
					TranslationProxy_Service::get_service( $this->get_icl_service_id() ),
					true );
			}
		} catch ( Exception $e ) {
			$error_data = $params;
		}

		if ( ! $error_data ) {
			$this->move_finished_jobs_to_batch();
			if ( defined( 'ICL_DEBUG_MODE' ) && ICL_DEBUG_MODE ) {
				$this->regenerate_wrong_service_data();
			}
		}

		return array( $project, $error_data );
	}

	public function get_icl_translators() {
		remove_filter( 'otgs_translation_get_services', 'filter_tp_services' );

		$icl_data = TranslationProxy_Translator::get_icl_translator_status();

		return isset ( $icl_data['icl_lang_status'] ) ? $icl_data['icl_lang_status'] : array();
	}

	private function move_finished_jobs_to_batch() {
		global $wpdb;

		remove_filter( 'otgs_translation_get_services', 'filter_tp_services' );

		$batch_id = TranslationProxy_Batch::update_translation_batch(
			'Migrated Completed ICanLocalize Jobs',
			$this->get_icl_service_id()
		);

		$wpdb->update(
			$wpdb->prefix . 'icl_translation_status',
			array( 'batch_id' => $batch_id ),
			array(
				'translation_service' => 'icl',
				'status'              => ICL_TM_COMPLETE
			) );

		return $batch_id;
	}

	private function regenerate_wrong_service_data() {
		global $wpdb;

		remove_filter( 'otgs_translation_get_services', 'filter_tp_services' );
		TranslationProxy::services( true );
		$translators          = $this->get_icl_translators();
		$local_translators    = TranslationManagement::get_blog_translators();
		$icl_translator_ids   = array();
		$local_translator_ids = array();
		foreach ( $translators as $translator ) {
			$icl_translator_ids[] = $translator['id'];
		}
		foreach ( $local_translators as $translator ) {
			$local_translator_ids[] = $translator->ID;
		}

		if ( (bool) $icl_translator_ids === true ) {
			$icl_translators_snippet   = " AND translator_id IN (" . wpml_prepare_in( $icl_translator_ids ) . ") ";
			$local_translators_snippet = (bool) $local_translator_ids === true ? " AND translator_id NOT IN (" . wpml_prepare_in( $local_translator_ids ) . ') ': '';
			$wpdb->query( $wpdb->prepare( "	UPDATE {$wpdb->prefix}icl_translation_status s
										SET s.translation_service = 'icl'
										WHERE s.status = %d
											AND s.translation_service != %d
											{$icl_translators_snippet}
											{$local_translators_snippet}",
				ICL_TM_IN_PROGRESS, $this->get_icl_service_id() ) );
		}
	}

	private function find_translator_in_data( $data, $from, $to, $id ) {
		$res = false;
		foreach ( $data as $item ) {
			if ( $item['from'] === $from && $item['to'] === $to && isset( $item['translators'] ) ) {
				$translators = $item['translators'];
				foreach ( $translators as $trans ) {
					if ( $trans['id'] == $id ) {
						$res = true;
						break;
					}
				}
				if ( $res === true ) {
					break;
				}
			}
		}

		return $res;
	}

	private function icl_project_hash_key() {
		$icl_service = TranslationProxy_Service::get_service( $this->get_icl_service_id() );

		return TranslationProxy_Project::generate_service_index( $icl_service );
	}

	private function get_icl_service_id() {
		$service = TranslationProxy_Service::get_service_by_suid( ICLM_ICL_SUID );

		return $service->id;
	}

	private function add_translation_id_to_basket( $translation_id ) {
		global $wpdb;

		$element = $wpdb->get_row( $wpdb->prepare(
			"	SELECT o.element_id, i.source_language_code, i.language_code
				FROM {$wpdb->prefix}icl_translations i
				JOIN {$wpdb->prefix}icl_translation_status s
					ON s.translation_id = i.translation_id
				JOIN {$wpdb->prefix}icl_translations o
					ON o.trid = i.trid
						AND o.language_code = i.source_language_code
				WHERE i.translation_id = %d
				LIMIT 1",
			$translation_id ) );
		if ( $element ) {
			TranslationProxy_Basket::add_posts_to_basket( array(
				'tr_action'      => array( $element->language_code => 1 ),
				'translate_from' => $element->source_language_code,
				'post'           => array(
					$element->element_id => array(
						'checked' => $element->element_id,
						'type'    => 'post'
					)
				)
			) );
		}

		return (bool) $element;
	}

	private function commit_basket( $translation_id ) {
		$basket_networking = wpml_tm_load_basket_networking();
		$translator_array  = $this->build_translator_array( $translation_id );
		if ( $translator_array ) {
			$basket_networking->commit_basket_chunk( array(),
				TranslationProxy_Basket::get_basket_name(),
				$translator_array
			);
		}
		$project = TranslationProxy::get_current_project();
		$project->commit_batch_job();
		TranslationProxy_Basket::delete_all_items_from_basket();
	}

	private function language_code_by_trans_id( $translation_id ) {
		global $wpdb;

		return $wpdb->get_var( $wpdb->prepare( " SELECT language_code
												 FROM {$wpdb->prefix}icl_translations
												 WHERE translation_id = %d
												 LIMIT 1",
			$translation_id ) );
	}

	private function icl_translator_by_trans_id( $translation_id ) {
		global $wpdb;

		return $wpdb->get_var( $wpdb->prepare( " SELECT translator_id
												 FROM {$wpdb->prefix}icl_translation_status
												 WHERE translation_id = %d
												 LIMIT 1",
			$translation_id ) );
	}

	private function build_translator_array( $translation_id ) {

		$lang_code     = $this->language_code_by_trans_id( $translation_id );
		$translator_id = $this->icl_translator_by_trans_id( $translation_id );

		if ( $lang_code && $translator_id ) {
			$trans_array = array(
				$lang_code => 'ts-' . $this->get_icl_service_id() . '-' . $translator_id
			);
		} else {
			$trans_array = false;
		}

		return $trans_array;
	}
}
