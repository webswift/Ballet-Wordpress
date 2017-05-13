<?php

/**
 * HelloFollowersAdmin
 * 
 * @package HelloFollowers
 * @author creoworx
 * @since 1.0
 *
 */
class HelloFollowersAdmin {
	
	/**
	 * Plugin Constructor
	 */
	public function __construct() {
		
		add_action ( 'admin_menu', 	array ($this, 'register_menu' ) );
		add_action ( 'admin_enqueue_scripts', array ($this, 'register_admin_assets' ), 99 );
		$hook = (defined ( 'WP_NETWORK_ADMIN' ) && WP_NETWORK_ADMIN) ? 'network_admin_menu' : 'admin_menu';
		add_action ( $hook, array ($this, 'handle_save_settings' ) );
		
		
	}
	
	public function register_menu() {
		$visibility = 'manage_options';
		add_menu_page( __('Hello Followers', 'hellofollowers'),  __('Hello Followers', 'hellofollowers'), $visibility, 'hellofollowers', array($this, 'admin_settings_screen'), 'dashicons-heart' );
		add_submenu_page( 'hellofollowers', __('Followers Counter', 'hellofollowers'), ''.__('Followers Counter', 'hellofollowers').'', $visibility, 'hellofollowers', array ($this, 'admin_settings_screen' ));
		
	}
	
	public function register_admin_assets() {
		wp_register_style ( 'hf-admin', HF_PLUGIN_URL . '/assets/admin/hf-admin.css', array (), HF_VERSION );
		wp_enqueue_style ( 'hf-admin' );
		
		wp_enqueue_script ( 'hf-admin-script', HF_PLUGIN_URL . '/assets/admin/hf-admin.js', array ('jquery' ), HF_VERSION, true );
		
		wp_enqueue_style ( 'hf-fontawsome', HF_PLUGIN_URL . '/assets/admin/font-awesome.min.css', array (), HF_VERSION );
				
		wp_enqueue_script( 'jquery-ui-sortable' );
		wp_enqueue_style( 'wp-color-picker');
		wp_enqueue_script( 'wp-color-picker');
		
	}
	
	public function handle_save_settings() {
		if (@$_POST && isset ( $_POST ['option_page'] )) {
			$changed = false;
			if ('hf_settings_group' == $this->getval($_POST, 'option_page' )) {
				$this->update_optons();
				$changed = true;
				hf_followers()->settle_immediate_update();
			}
		
			if ($changed) {
		
				$user_section = isset($_REQUEST['section']) ? $_REQUEST['section'] : '';
				$user_subsection = isset($_REQUEST['subsection']) ? $_REQUEST['subsection'] : '';
		
				//$goback = add_query_arg ( 'settings-updated', 'true', wp_get_referer () );
				$goback = esc_url_raw(add_query_arg(array('settings-updated' => 'true', 'section' => $user_section, 'subsection' => $user_subsection), wp_get_referer ()));
				//print $goback;
				//$goback = str_replace('#038;', '', $goback);
				wp_redirect ( $goback );
				die ();
			}
		}
				
	}
	
	function getval ($from, $what, $default=false) {
		if (is_object($from) && isset($from->$what)) return $from->$what;
		else if (is_array($from) && isset($from[$what])) return $from[$what];
		else return $default;
	}
	
	public function admin_settings_screen(){
		include_once HF_PLUGIN_ROOT. 'lib/admin/hf-settings.php';
	}
	
	public function update_optons() {
		global $hf_navigation_tabs, $hf_sidebar_sections, $hf_section_options;
	
		$current_options = get_option(HF_OPTIONS_NAME);
		if (!is_array($current_options)) {
			$current_options = array();
		}
	
		$current_tab = isset($_REQUEST['tab']) ? $_REQUEST['tab'] : '';
		$user_options = isset($_REQUEST['hf_options']) ? $_REQUEST['hf_options'] : array();
	
		$reset_settings = isset($_REQUEST['reset_settings']) ? $_REQUEST['reset_settings'] : '';
	
		//print_r($user_options);
	
		if ($current_tab == '') {
			return;
		}
	
		$options = $hf_section_options[$current_tab];
	
		foreach($options as $section => $fields) {
			$section_options = $fields;
				
			foreach ($section_options as $option) {
				$type = $option['type'];
				$id = isset($option['id']) ? $option['id'] : '';
	
				if ($id == '') {
					continue;
				}
	
				switch ($type) {
					case "network_rename":
						$option_value = isset($_REQUEST['hf_options_names']) ? $_REQUEST['hf_options_names'] : array();
	
						foreach ($option_value as $key => $value) {
							$network_option_value = "user_network_name_".$key;
							$current_options[$network_option_value] = $value;
						}
	
						break;
					case "network_select":
						$option_value = isset($user_options['networks']) ? $user_options['networks'] : array();
						$current_options['networks'] = $option_value;
						$option_value = isset($user_options['networks_order']) ? $user_options['networks_order'] : array();
						$current_options['networks_order'] = $option_value;
						break;
					case "checkbox_list_sortable":
						$option_value = isset($user_options[$id]) ? $user_options[$id] : '';
						$current_options[$id] = $option_value;
	
						$option_value = isset($user_options[$id.'_order']) ? $user_options[$id.'_order'] : '';
						$current_options[$id.'_order'] = $option_value;
						break;
					default:
						$option_value = isset($user_options[$id]) ? $user_options[$id] : '';
						$current_options[$id] = $option_value;
		
						break;
				}
			}
		}
	
		$current_options = $this->clean_blank_values($current_options);
	
		// initially reset plugin settings to default one
		update_option(HF_OPTIONS_NAME, $current_options);
	
	}
	
	function clean_blank_values($object) {
		foreach ($object as $key => $value) {
			if (!is_array($value)) {
				$value = trim($value);
	
				if (empty($value)) {
					unset($object[$key]);
				}
			}
			else {
				if (count($value) == 0) {
					unset($object[$key]);
				}
			}
		}
	
		return $object;
	}
	
}
