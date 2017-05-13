<?php 

if (! defined ( 'WPINC' ))
	die ();


define ( 'HF_SPA_VERSION', '1.0' );
define ( 'HF_SPA_PLUGIN_ROOT', dirname ( __FILE__ ) . '/' );
define ( 'HF_SPA_PLUGIN_URL', plugins_url () . '/' . basename ( dirname ( __FILE__ ) ) );
define ( 'HF_SPA_PLUGIN_BASE_NAME', plugin_basename ( __FILE__ ) );
define ( 'HF_SPA_TEXT_DOMAIN', 'hfspa');
define ( 'HF_SPA_TRACKER_TABLE', 'hf_spa');
define ( 'HF_SPA_DBVERSION', '0.2');

include_once (HF_SPA_PLUGIN_ROOT . 'lib/hf-helpers.php');
include_once (HF_SPA_PLUGIN_ROOT . 'lib/hf-database.php');
include_once (HF_SPA_PLUGIN_ROOT . 'lib/hf-followers-bridge.php');

class HFProfileAnalytics {
	private static $instance = null;
	public static function get_instance() {
	
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
	
		return self::$instance;
	
	} // end get_instance;
	
	
	function __construct() {
	
		if (is_admin()) {
			add_action ( 'admin_menu', 	array ($this, 'register_menu' ), 99 );
			add_action ( 'admin_enqueue_scripts', array ($this, 'register_admin_assets' ), 99 );
		}
	}
	
	public function register_menu() {
	
		$hf_access = "edit_pages";
		add_submenu_page( 'hellofollowers', __('Profile Analytics', 'hellofollowers'), ''.__('Profile Analytics', 'hellofollowers').'', $hf_access, 'hf_spa', array ($this, 'hf_spa_settings_redirect' ));
	}
	
	
	public function register_admin_assets($hook) {
		global $essb_admin_options;
	
		$requested = isset($_REQUEST['page']) ? $_REQUEST['page'] : "";
		if ($requested == "hf_spa") {
			wp_register_style ( 'hfspa', HF_PLUGIN_URL . '/lib/profile-analytics/assets/css/hfspa.css', array (), HF_VERSION );
			wp_enqueue_style ( 'hfspa' );

			wp_register_style ( 'hfspa-morris', HF_PLUGIN_URL . '/lib/profile-analytics/assets/css/morris.css', array (), HF_VERSION );
			wp_enqueue_style ( 'hfspa-morris' );
				
			
			wp_enqueue_script ( 'hfspa-datepicker-moment', HF_PLUGIN_URL . '/lib/profile-analytics/assets/js/moment.js', array ('jquery' ), HF_VERSION, true );
			wp_enqueue_script ( 'hfspa-datepicker', HF_PLUGIN_URL . '/lib/profile-analytics/assets/js/pikaday.js', array ('jquery' ), HF_VERSION, true );
			wp_register_style ( 'hfspa-datepicker', HF_PLUGIN_URL . '/lib/profile-analytics/assets/css/pikaday.css', array (), HF_VERSION );
			wp_enqueue_style ( 'hfspa-datepicker' );				
			wp_enqueue_script ( 'hfspa-dtbuttons', HF_PLUGIN_URL . '/lib/profile-analytics/assets/js/dataTables.buttons.js', array ('jquery' ), HF_VERSION, true );
			wp_enqueue_script ( 'hfspa-dtbuttons-html5', HF_PLUGIN_URL . '/lib/profile-analytics/assets/js/buttons.html5.js', array ('jquery' ), HF_VERSION, true );
			wp_enqueue_script ( 'hfspa-dtbuttons-flash', HF_PLUGIN_URL . '/lib/profile-analytics/assets/js/buttons.flash.js', array ('jquery' ), HF_VERSION, true );
				
			wp_register_style ( 'hfspa-dtbuttons', HF_PLUGIN_URL . '/lib/profile-analytics/assets/css/buttons.dataTables.css', array (), HF_VERSION );
			wp_enqueue_style ( 'hfspa-dtbuttons' );
			
			wp_register_style ( 'hfspa-datatable', HF_PLUGIN_URL . '/lib/profile-analytics/assets/css/datatable/jquery.dataTables.css', array (), HF_VERSION );
			wp_enqueue_style ( 'hfspa-datatable' );
			wp_enqueue_script ( 'hfspa-datatable', HF_PLUGIN_URL . '/lib/profile-analytics/assets/css/datatable/jquery.dataTables.js', array ('jquery' ), HF_VERSION, true );

			wp_enqueue_script ( 'hfspa-morris', HF_PLUGIN_URL . '/lib/profile-analytics/assets/js/morris.js', array ('jquery' ), HF_VERSION, true );
			wp_enqueue_script ( 'hfspa-raphael', HF_PLUGIN_URL . '/lib/profile-analytics/assets/js/raphael-min.js', array ('jquery' ), HF_VERSION, true );
		}
	}
	
	public function hf_spa_settings_redirect() {
		include_once (HF_SPA_PLUGIN_ROOT . 'lib/view/hf-view-home.php');
	}
	
	public static function install() {
		global $wpdb;
	
		$sql = "";
	
		$table_name = $wpdb->prefix . HF_SPA_TRACKER_TABLE;
	
		$sql .= "CREATE TABLE $table_name (
		hfspa_id mediumint(11) NOT NULL AUTO_INCREMENT,
		hfspa_date date NOT NULL,
		hfspa_network varchar(40) NOT NULL,
		hfspa_profile varchar(250) NOT NULL,
		hfspa_value varchar(50)  NOT NULL,
		hfspa_lastupdate TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
		UNIQUE KEY hfspa_id (hfspa_id)
		); ";
	
		require_once (ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta ( $sql );
	}
	
	public static function activate () {
		HFProfileAnalytics::install();
		
		if (class_exists('HFFollowersCounterBridge')) {
			HFFollowersCounterBridge::log_profile_values_on_install();
		}
	}
}

global $hf_spa;
function HF_SPA() {
	global $hf_spa;
	$hf_spa = HFProfileAnalytics::get_instance();
	
	if ( get_site_option( 'hf_spa' ) != HF_SPA_DBVERSION ) {
		add_option('hf_spa', HF_SPA_DBVERSION);
		HFProfileAnalytics::activate();
	}
}
HF_SPA();

?>