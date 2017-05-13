<?php

/*
 * Plugin Name: Hello Followers - Social Counter Plugin for WordPress
 * Description: Hello Followers is social counter plugin for WordPress that allows you easy to add links to your social profiles with displaying number of followers using more that 16 templates and different layouts
 * Plugin URI: http://codecanyon.net/user/appscreo/portfolio?ref=appscreo
 * Version: 1.0
 * Author: CreoApps
 * Author URI: http://codecanyon.net/user/appscreo/portfolio?ref=appscreo
 */


if (! defined ( 'WPINC' ))
	die ();

define ( 'HF_VERSION', '1.0' );
define ( 'HF_PLUGIN_ROOT', dirname ( __FILE__ ) . '/' );
define ( 'HF_PLUGIN_URL', plugins_url () . '/' . basename ( dirname ( __FILE__ ) ) );
define ( 'HF_PLUGIN_BASE_NAME', plugin_basename ( __FILE__ ) );
define ( 'HF_OPTIONS_NAME', 'hello-followers');
define ( 'HF_TEXT_DOMAIN', 'hellofollowers');

class HelloFollowers {
	
	private $factory = array();
	
	private static $_instance;
	
	private $settings;
	
	public function __construct() {
		add_action( 'init', array( &$this, 'on_init' ), 9);
		add_action( 'plugins_loaded', array( &$this, 'on_plugins_loaded' ), 9);		

	}
	
	/**
	 * Get static instance of class
	 *
	 * @return HelloFollowers
	 */
	public static function getInstance() {
		if ( ! ( self::$_instance instanceof self ) ) {
			self::$_instance = new self();
		}
	
		return self::$_instance;
	}
	
	/**
	 * Cloning disabled
	 */
	private function __clone() {
	}
	
	/**
	 * Serialization disabled
	 */
	private function __sleep() {
	}
	
	/**
	 * De-serialization disabled
	 */
	private function __wakeup() {
	}	

	/**
	 * on_init
	 * 
	 * Execute main plugin component on init
	 */
	public function on_init() {
		
		$this->factory_activate('hf', 'HFSocialFollowersCounter');
		
		if (is_admin()) {
			$this->as_admin();
		}
	}
	
	public function on_plugins_loaded() {
		include_once HF_PLUGIN_ROOT.'lib/hf-core-includes.php';
		include_once (HF_PLUGIN_ROOT . 'lib/profile-analytics/hf-profile-analytics.php');
		load_plugin_textdomain( 'hellofollowers', false, HF_PLUGIN_ROOT . 'locate' );
		$this->load_settings();
	}
	
	public function followers() {
		if (!isset($this->factory['hf'])) {
			$this->factory['hf'] = new HFSocialFollowersCounter;
		}
		
		return $this->factory['hf'];
	}
	
	/**
	 * as_admin
	 * 
	 * Execute admin part of code (settings and plugin setup)
	 */
	private function as_admin() {
		include_once (HF_PLUGIN_ROOT . 'lib/admin/hf-admin-includes.php');
		
		// Activate main plugin instance
		$this->factory_activate('hfadmin', 'HelloFollowersAdmin');
		
		
	}
	
	private function factory_activate($component, $class_name) {
		if (!isset($this->factory[$component])) {
			$this->factory[$component] = new $class_name;
		}		
	}
	
	private function load_settings() {
		$this->settings = get_option(HF_OPTIONS_NAME);
				
		if (!$this->settings) {
			$this->settings = array();
			
			$default_options = HFSocialFollowersCounterHelper::options_structure();
			$default_options = HFSocialFollowersCounterHelper::create_default_options_from_structure($default_options);
			
			$this->settings = $default_options;
			update_option(HF_OPTIONS_NAME, $default_options);
		}
	}
	
	public function options_value($param, $default = '') {
		if (strpos($param, 'hf_') !== true) {
			$param = 'hf_'.$param;
		}
		
		return isset ( $this->settings [$param] ) ? $this->settings [$param]  : $default;
	}
	
	public function options_bool_value($param) {
		if (strpos($param, 'hf_') !== true) {
			$param = 'hf_'.$param;
		}
		
		$value = isset ( $this->settings [$param] ) ? $this->settings [$param]  : 'false';
	
		if ($value == "true") {
			return true;
		}
		else {
			return false;
		}
	}
	
	public function options() {
		if (isset($this->settings)) {
			return $this->settings;
		}
		else {
			return array();
		}
	}
}

global $hf_manager;
if (!$hf_manager) {
	$hf_manager = HelloFollowers::getInstance();
}
