<?php
$hf_navigation_tabs = array();
$hf_sidebar_sections = array();
$hf_sidebar_sections = array();

if (!class_exists('HFSocialFollowersCounterHelper')) {
	include_once HF_PLUGIN_ROOT.'lib/core/hf-social-followers-counter-helper.php';
}

HFOptionsStructureHelper::init();
HFOptionsStructureHelper::tab('social', __('Follower Settings', 'hellofollowers'), __('Follower Settings', 'hellofollowers'));
HFOptionsStructureHelper::tab('color', __('Color Customizer', 'hellofollowers'), __('Color Customizer', 'hellofollowers'));
HFOptionsStructureHelper::tab('layout', __('Layout Builder', 'hellofollowers'), __('Layout Builder', 'hellofollowers'));
HFOptionsStructureHelper::tab('shortcode', __('Shortcode Generator', 'hellofollowers'), __('Shortcode Generator', 'hellofollowers'));

//-- menu
HFOptionsStructureHelper::menu_item('social', 'global', __('Global Options', 'hellofollowers'), 'default');
HFOptionsStructureHelper::field_heading('social', 'global', 'heading5', __('Global Options', 'hellofollowers'));
HFOptionsStructureHelper::field_select('social', 'global', 'hf_update', __('Update period', 'hellofollowers'), __('Choose the time when counters will be updated. Default is 1 day if nothing is selected.', 'hellofollowers'), HFSocialFollowersCounterHelper::available_cache_periods());
HFOptionsStructureHelper::field_select('social', 'global', 'hf_format', __('Number format', 'hellofollowers'), __('Choose default number format', 'hellofollowers'), HFSocialFollowersCounterHelper::available_number_formats());
HFOptionsStructureHelper::field_switch('social', 'global', 'hf_uservalues', __('Allow user values', 'hellofollowers'), __('Activate this option to allow enter of user values for each social network. In this case when automatic value is less than user value the user value will be used', 'hellofollowers'), '', __('Yes', 'hellofollowers'), __('No', 'hellofollowers'));
HFOptionsStructureHelper::field_checkbox_list_sortable('social', 'global', 'hf_networks', __('Social Networks', 'hellofollowers'), __('Order and activate networks you wish to use in widget and shortcodes'), HFSocialFollowersCounterHelper::available_social_networks(false));

HFOptionsStructureHelper::menu_item('social', 'followers', __('Networks', 'hellofollowers'), 'default', 'activate_first', 'followers-1');

hf_draw_fanscounter_settings('social', 'followers');

// Color Customizer
HFOptionsStructureHelper::menu_item('color', 'color1', __('Single Color Scheme', 'hellofollowers'), 'default');
HFOptionsStructureHelper::field_heading('color', 'color1', 'heading5', __('Single Color Scheme', 'hellofollowers'));
HFOptionsStructureHelper::field_color('color', 'color1', 'hf_monotone_bgcolor1', __('Button background color', 'hellofollowers'), __('Provide Provide custom background color that will be used for all buttons.', 'hellofollowers'));
HFOptionsStructureHelper::field_color('color', 'color1', 'hf_monotone_bgcolor2', __('Button background color on hover', 'hellofollowers'), __('Provide Provide custom background color that will be used for all buttons on hover.', 'hellofollowers'));
HFOptionsStructureHelper::field_color('color', 'color1', 'hf_monotone_color1', __('Elements color', 'hellofollowers'), __('Provide custom elements color - this color will be used for icon and texts inside button', 'hellofollowers'));
HFOptionsStructureHelper::field_color('color', 'color1', 'hf_monotone_color2', __('Elements color on hover', 'hellofollowers'), __('Provide custom elements color that will be used on hover - this color will be used for icon and texts inside button', 'hellofollowers'));


HFOptionsStructureHelper::menu_item('color', 'color2', __('Multi Color Scheme', 'hellofollowers'), 'default');
hf_draw_fanscounter_color_settings('color', 'color2');

HFOptionsStructureHelper::menu_item('layout', 'layout1', __('Custom Layout #1', 'hellofollowers'), 'default');
HFOptionsStructureHelper::menu_item('layout', 'layout2', __('Custom Layout #2', 'hellofollowers'), 'default');
HFOptionsStructureHelper::menu_item('layout', 'layout3', __('Custom Layout #3', 'hellofollowers'), 'default');
HFOptionsStructureHelper::menu_item('layout', 'layout4', __('Custom Layout #4', 'hellofollowers'), 'default');
HFOptionsStructureHelper::menu_item('layout', 'layout5', __('Custom Layout #5', 'hellofollowers'), 'default');

hf_draw_fanscounter_layout_settings('layout', 'layout1', __('Custom Layout #1', 'hellofollowers'), '1');
hf_draw_fanscounter_layout_settings('layout', 'layout2', __('Custom Layout #2', 'hellofollowers'), '2');
hf_draw_fanscounter_layout_settings('layout', 'layout3', __('Custom Layout #3', 'hellofollowers'), '3');
hf_draw_fanscounter_layout_settings('layout', 'layout4', __('Custom Layout #4', 'hellofollowers'), '4');
hf_draw_fanscounter_layout_settings('layout', 'layout5', __('Custom Layout #5', 'hellofollowers'), '5');

function hf_draw_fanscounter_layout_settings($tab_id, $menu_id, $title, $layout_id) {
	HFOptionsStructureHelper::field_heading($tab_id, $menu_id, 'heading5', $title);
	HFOptionsStructureHelper::field_checkbox_list_sortable($tab_id, $menu_id, 'hf_networks_layout'.$layout_id, __('Social Networks', 'hellofollowers'), __('Order and activate networks you wish to use in widget and shortcodes'), HFSocialFollowersCounterHelper::available_social_networks(true));
	HFOptionsStructureHelper::field_select($tab_id, $menu_id, 'hf_columns_layout'.$layout_id, __('Columns', 'hellofollowers'), __('Choose number of columns for this layout', 'hellofollowers'), HFSocialFollowersCounterHelper::default_columns());

	HFOptionsStructureHelper::field_heading($tab_id, $menu_id, 'heading5', __('Social Network Block Size', 'hellofollowers'));
	HFOptionsStructureHelper::field_section_start_full_panels($tab_id, $menu_id);
	$all_networks = HFSocialFollowersCounterHelper::available_social_networks(true);
	foreach ($all_networks as $network => $title) {
		HFOptionsStructureHelper::field_select_panel($tab_id, $menu_id, 'hf_column_layout'.$layout_id.'_'.$network, $title, __('Provide custom size of block for this social network', 'hellofollowers'), HFSocialFollowersCounterHelper::defalut_block_size());
	}
	
	HFOptionsStructureHelper::field_section_end_full_panels($tab_id, $menu_id);
}

function hf_draw_fanscounter_color_settings($tab_id, $menu_id) {
	$all_networks = HFSocialFollowersCounterHelper::available_social_networks();
	foreach ($all_networks as $network => $title) {
		HFOptionsStructureHelper::field_heading($tab_id, $menu_id, 'heading5', $title);
		HFOptionsStructureHelper::field_section_start_full_panels($tab_id, $menu_id);
		//HFOptionsStructureHelper::field_color_panel($tab_id, $menu_id, 'hf_'.$network.'_color1', __('Accent color', 'hellofollowers'), __('Provide accent color that will be used to change default template colors - background for templates with background color or color for templates without background color', 'hellofollowers'));
		//HFOptionsStructureHelper::field_color_panel($tab_id, $menu_id, 'hf_'.$network.'_color2', __('Secondary color', 'hellofollowers'), __('Provide secondary color that can be used to correct text color on templates with background color', 'hellofollowers'));
		HFOptionsStructureHelper::field_color_panel('color', $menu_id, 'hf_'.$network.'_bgcolor1', __('Button background color', 'hellofollowers'), __('Provide Provide custom background color that will be used for all buttons.', 'hellofollowers'));
		HFOptionsStructureHelper::field_color_panel('color', $menu_id, 'hf_'.$network.'_bgcolor2', __('Button background color on hover', 'hellofollowers'), __('Provide Provide custom background color that will be used for all buttons on hover.', 'hellofollowers'));
		HFOptionsStructureHelper::field_color_panel('color', $menu_id, 'hf_'.$network.'_color1', __('Elements color', 'hellofollowers'), __('Provide custom elements color - this color will be used for icon and texts inside button', 'hellofollowers'));
		HFOptionsStructureHelper::field_color_panel('color', $menu_id, 'hf_'.$network.'_color2', __('Elements color on hover', 'hellofollowers'), __('Provide custom elements color that will be used on hover - this color will be used for icon and texts inside button', 'hellofollowers'));
		
		HFOptionsStructureHelper::field_section_end_full_panels($tab_id, $menu_id);
	}
}


function hf_draw_fanscounter_settings($tab_id, $menu_id) {
	$setting_fields = HFSocialFollowersCounterHelper::options_structure();
	$network_list = HFSocialFollowersCounterHelper::available_social_networks(true);
	
	$networks_same_authentication = array();
	
	$count = 1;
	foreach ($network_list as $network => $title) {
		HFOptionsStructureHelper::submenu_item($tab_id, $menu_id.'-'.$count, __($title, 'hellofollowers'));
		
		HFOptionsStructureHelper::field_heading($tab_id, $menu_id.'-'.$count, 'heading5', $title);
		
		$default_options_key = $network;
		$is_extended_key = false;
		
		if (strpos($default_options_key, '_') !== false && $default_options_key != 'wp_posts' && $default_options_key != 'wp_comments' && $default_options_key != 'wp_users') {
			$key_array = explode('_', $default_options_key);
			$default_options_key = $key_array[0];
			$is_extended_key = true;
		}
		
		$single_network_options = isset($setting_fields[$default_options_key]) ? $setting_fields[$default_options_key] : array();
		
		foreach ($single_network_options as $field => $options) {
			$field_id = "hf_".$network."_".$field;
			
			$field_type = isset($options['type']) ? $options['type'] : 'textbox';
			$field_text = isset($options['text']) ? $options['text'] : '';
			$field_description = isset($options['description']) ? $options['description'] : '';
			$field_values = isset($options['values']) ? $options['values'] : array();
			
			$is_authfield = isset($options['authfield']) ? $options['authfield'] : false;
			
			if ($is_extended_key && $is_authfield) {
				if (isset($networks_same_authentication[$default_options_key])) {
					continue;
				}
			}
			
			if ($field_type == "textbox") {
				HFOptionsStructureHelper::field_textbox_stretched($tab_id, $menu_id.'-'.$count, $field_id, $field_text, $field_description);
			}
			if ($field_type == "select") {
				HFOptionsStructureHelper::field_select($tab_id, $menu_id.'-'.$count, $field_id, $field_text, $field_description, $field_values);
			}
			if ($field_type == "color") {
				HFOptionsStructureHelper::field_color($tab_id, $menu_id.'-'.$count, $field_id, $field_text, $field_description);
			}
		}
		
		$count++;
	}
}



/**
 * Options Creator Helper Class
 * ---
 * @author appscreo
 *
 */
class HFOptionsStructureHelper {
	
	public static function capitalize($text) {
		return ucfirst($text);
	}
	
	public static function init() {
		global $hf_navigation_tabs, $hf_sidebar_sections, $hf_section_options;

		$hf_navigation_tabs = array();
		$hf_sidebar_sections = array();
		$hf_sidebar_sections = array();
	}

	public static function tab($tab_id, $tab_text, $tab_title, $hide_update_button = false, $hide_in_navigation = false, $wizard_tab = false) {
		global $hf_navigation_tabs, $hf_sidebar_sections, $hf_section_options;
		
		$hf_navigation_tabs[$tab_id] = $tab_text;
		$hf_sidebar_sections[$tab_id] = array(
				'title' => $tab_title,
				'fields' => array(),
				'hide_update_button' => $hide_update_button,
				'hide_in_navigation' => $hide_in_navigation,
				'wizard_tab' => $wizard_tab
				);
		
		$hf_section_options[$tab_id] = array();
	}
	
	public static function menu_item($tab_id, $id, $title, $icon = 'default', $action = '', $default_child = '') {
		global $hf_navigation_tabs, $hf_sidebar_sections, $hf_section_options;
		
		$hf_sidebar_sections[$tab_id]['fields'][] = array(
				'field_id' => $id,
				'title' => $title,
				'icon' => $icon,
				'type' => 'menu_item',
				'action' => $action,
				'default_child' => $default_child
				);
	}
	
	public static function submenu_item ($tab_id, $id, $title, $icon = 'default', $action = 'menu', $level2 = '') {
		global $hf_navigation_tabs, $hf_sidebar_sections, $hf_section_options;
		$hf_sidebar_sections[$tab_id]['fields'][] = array(
				'field_id' => $id,
				'title' => $title,
				'icon' => $icon,
				'type' => 'sub_menu_item',
				'action' => $action,
				'level2' => $level2
		);
		
		if ($action == 'menu') {
			$hf_section_options[$tab_id][$id] = array();
		}
	}
	
	public static function field_heading($tab_id, $menu_id, $level = 'heading1', $title = '') {
		global $hf_navigation_tabs, $hf_sidebar_sections, $hf_section_options;
		$hf_section_options[$tab_id][$menu_id][] = array(
				'type' => $level,
				'title' => $title
				);
		
	}
	
	public static function field_switch ($tab_id, $menu_id, $id, $title, $description, $recommended = '', $on_label = '', $off_label = '', $default_value = '') {
		global $hf_navigation_tabs, $hf_sidebar_sections, $hf_section_options;
		$hf_section_options[$tab_id][$menu_id][] = array(
				'id' => $id,
				'type' => 'switch',
				'title' => $title,
				'description' => $description,
				'recommended' => $recommended,
				'on_label' => $on_label,
				'off_label' => $off_label,
				'default_value' => $default_value
		);
	}

	public static function field_switch_panel ($tab_id, $menu_id, $id, $title, $description, $recommended = '', $on_label = '', $off_label = '', $default_value = '') {
		global $hf_navigation_tabs, $hf_sidebar_sections, $hf_section_options;
		$hf_section_options[$tab_id][$menu_id][] = array(
				'id' => $id,
				'type' => 'switch-in-panel',
				'title' => $title,
				'description' => $description,
				'recommended' => $recommended,
				'on_label' => $on_label,
				'off_label' => $off_label,
				'default_value' => $default_value
		);
	}
	
	public static function field_textbox ($tab_id, $menu_id, $id, $title, $description, $recommended = '', $class = '', $icon = '', $icon_position = '') {
		global $hf_navigation_tabs, $hf_sidebar_sections, $hf_section_options;
		$hf_section_options[$tab_id][$menu_id][] = array(
				'id' => $id,
				'type' => 'text',
				'title' => $title,
				'description' => $description,
				'recommeded' => $recommended,
				'class' => $class,
				'icon' => $icon,
				'icon_position' => $icon_position
		);
	}

	public static function field_textbox_panel ($tab_id, $menu_id, $id, $title, $description, $recommended = '', $class = '', $icon = '', $icon_position = '') {
		global $hf_navigation_tabs, $hf_sidebar_sections, $hf_section_options;
		$hf_section_options[$tab_id][$menu_id][] = array(
				'id' => $id,
				'type' => 'text-in-panel',
				'title' => $title,
				'description' => $description,
				'recommeded' => $recommended,
				'class' => $class,
				'icon' => $icon,
				'icon_position' => $icon_position
		);
	}
	
	public static function field_textbox_stretched ($tab_id, $menu_id, $id, $title, $description, $recommended = '', $class = '', $icon = '', $icon_position = '') {
		global $hf_navigation_tabs, $hf_sidebar_sections, $hf_section_options;
		$hf_section_options[$tab_id][$menu_id][] = array(
				'id' => $id,
				'type' => 'text-stretched',
				'title' => $title,
				'description' => $description,
				'recommeded' => $recommended,
				'class' => $class,
				'icon' => $icon,
				'icon_position' => $icon_position
		);
	}
	
	public static function field_checkbox ($tab_id, $menu_id, $id, $title, $description, $recommended = '') {
		global $hf_navigation_tabs, $hf_sidebar_sections, $hf_section_options;
		$hf_section_options[$tab_id][$menu_id][] = array(
				'id' => $id,
				'type' => 'checkbox',
				'title' => $title,
				'description' => $description,
				'recommeded' => $recommended
		);
	}
	
	public static function field_checkbox_list ($tab_id, $menu_id, $id, $title, $description, $values, $recommended = '') {
		global $hf_navigation_tabs, $hf_sidebar_sections, $hf_section_options;
		$hf_section_options[$tab_id][$menu_id][] = array(
				'id' => $id,
				'type' => 'checkbox_list',
				'title' => $title,
				'description' => $description,
				'recommeded' => $recommended,
				'values' => $values
		);
	}
	
	public static function field_checkbox_list_sortable ($tab_id, $menu_id, $id, $title, $description, $values, $recommended = '') {
		global $hf_navigation_tabs, $hf_sidebar_sections, $hf_section_options;
		$hf_section_options[$tab_id][$menu_id][] = array(
				'id' => $id,
				'type' => 'checkbox_list_sortable',
				'title' => $title,
				'description' => $description,
				'recommeded' => $recommended,
				'values' => $values
		);
	}
	
	public static function field_select ($tab_id, $menu_id, $id, $title, $description, $values, $recommended = '') {
		global $hf_navigation_tabs, $hf_sidebar_sections, $hf_section_options;
		$hf_section_options[$tab_id][$menu_id][] = array(
				'id' => $id,
				'type' => 'select',
				'title' => $title,
				'description' => $description,
				'recommeded' => $recommended,
				'values' => $values
		);
	}

	public static function field_select_panel ($tab_id, $menu_id, $id, $title, $description, $values, $recommended = '') {
		global $hf_navigation_tabs, $hf_sidebar_sections, $hf_section_options;
		$hf_section_options[$tab_id][$menu_id][] = array(
				'id' => $id,
				'type' => 'select-in-panel',
				'title' => $title,
				'description' => $description,
				'recommeded' => $recommended,
				'values' => $values
		);
	}
	
	public static function field_textarea ($tab_id, $menu_id, $id, $title, $description, $recommended = '') {
		global $hf_navigation_tabs, $hf_sidebar_sections, $hf_section_options;
		$hf_section_options[$tab_id][$menu_id][] = array(
				'id' => $id,
				'type' => 'textarea',
				'title' => $title,
				'description' => $description,
				'recommeded' => $recommended
		);
	}

	public static function field_editor ($tab_id, $menu_id, $id, $title, $description, $mode = 'javascript', $recommended = '') {
		global $hf_navigation_tabs, $hf_sidebar_sections, $hf_section_options;
		$hf_section_options[$tab_id][$menu_id][] = array(
				'id' => $id,
				'type' => 'editor',
				'title' => $title,
				'description' => $description,
				'recommeded' => $recommended,
				'mode' => $mode
		);
	}
	
	public static function field_wpeditor ($tab_id, $menu_id, $id, $title, $description, $recommended = '') {
		global $hf_navigation_tabs, $hf_sidebar_sections, $hf_section_options;
		$hf_section_options[$tab_id][$menu_id][] = array(
				'id' => $id,
				'type' => 'wpeditor',
				'title' => $title,
				'description' => $description,
				'recommeded' => $recommended
		);
	}	

	public static function field_color ($tab_id, $menu_id, $id, $title, $description, $recommended = '') {
		global $hf_navigation_tabs, $hf_sidebar_sections, $hf_section_options;
		$hf_section_options[$tab_id][$menu_id][] = array(
				'id' => $id,
				'type' => 'color',
				'title' => $title,
				'description' => $description,
				'recommeded' => $recommended
		);
	}
	
	public static function field_color_panel ($tab_id, $menu_id, $id, $title, $description, $recommended = '') {
		global $hf_navigation_tabs, $hf_sidebar_sections, $hf_section_options;
		$hf_section_options[$tab_id][$menu_id][] = array(
				'id' => $id,
				'type' => 'color-in-panel',
				'title' => $title,
				'description' => $description,
				'recommeded' => $recommended
		);
	}
	
	public static function field_image_checkbox ($tab_id, $menu_id, $id, $title, $description, $values, $recommended = '') {
		global $hf_navigation_tabs, $hf_sidebar_sections, $hf_section_options;
		$hf_section_options[$tab_id][$menu_id][] = array(
				'id' => $id,
				'type' => 'image_checkbox',
				'title' => $title,
				'description' => $description,
				'recommeded' => $recommended,
				'values' => $values
		);
	}

	public static function field_image_radio ($tab_id, $menu_id, $id, $title, $description, $values, $recommended = '') {
		global $hf_navigation_tabs, $hf_sidebar_sections, $hf_section_options;
		$hf_section_options[$tab_id][$menu_id][] = array(
				'id' => $id,
				'type' => 'image_radio',
				'title' => $title,
				'description' => $description,
				'recommeded' => $recommended,
				'values' => $values
		);
	}

	public static function field_file ($tab_id, $menu_id, $id, $title, $description, $recommended = '') {
		global $hf_navigation_tabs, $hf_sidebar_sections, $hf_section_options;
		$hf_section_options[$tab_id][$menu_id][] = array(
				'id' => $id,
				'type' => 'file',
				'title' => $title,
				'description' => $description,
				'recommeded' => $recommended
		);
	}
	
	public static function field_simplesort ($tab_id, $menu_id, $id, $title, $description, $values, $recommended = '') {
		global $hf_navigation_tabs, $hf_sidebar_sections, $hf_section_options;
		$hf_section_options[$tab_id][$menu_id][] = array(
				'id' => $id,
				'type' => 'simplesort',
				'title' => $title,
				'description' => $description,
				'recommeded' => $recommended,
				'values' => $values
		);
	}
	
	public static function field_select2 ($tab_id, $menu_id, $id, $title, $description, $values, $multiple = false, $recommended = '') {
		global $hf_navigation_tabs, $hf_sidebar_sections, $hf_section_options;
		$hf_section_options[$tab_id][$menu_id][] = array(
				'id' => $id,
				'type' => 'select2',
				'title' => $title,
				'description' => $description,
				'recommeded' => $recommended,
				'values' => $values,
				'select2_options' => array('allow_clear' => false, 'multiple' => $multiple, 'placeholder' => '')
		);
	}
	
	public static function field_func ($tab_id, $menu_id, $id, $title, $description, $recommended = '') {
		global $hf_navigation_tabs, $hf_sidebar_sections, $hf_section_options;
		$hf_section_options[$tab_id][$menu_id][] = array(
				'id' => $id,
				'type' => 'func',
				'title' => $title,
				'description' => $description
		);
	}
	
	public static function field_section_start ($tab_id, $menu_id, $title, $description, $recommended = '') {
		global $hf_navigation_tabs, $hf_sidebar_sections, $hf_section_options;
		$hf_section_options[$tab_id][$menu_id][] = array(
				'type' => 'section_start',
				'title' => $title,
				'description' => $description,
				'recommended' => $recommended
		);
	}
	
	public static function field_section_end ($tab_id, $menu_id) {
		global $hf_navigation_tabs, $hf_sidebar_sections, $hf_section_options;
		$hf_section_options[$tab_id][$menu_id][] = array(
				'type' => 'section_end'
		);
	}

	public static function field_section_start_panels ($tab_id, $menu_id, $title, $description, $recommended = '') {
		global $hf_navigation_tabs, $hf_sidebar_sections, $hf_section_options;
		$hf_section_options[$tab_id][$menu_id][] = array(
				'type' => 'section_start_panels',
				'title' => $title,
				'description' => $description,
				'recommended' => $recommended
		);
	}
	
	public static function field_section_end_panels ($tab_id, $menu_id) {
		global $hf_navigation_tabs, $hf_sidebar_sections, $hf_section_options;
		$hf_section_options[$tab_id][$menu_id][] = array(
				'type' => 'section_end_panels'
		);
	}
	
	public static function field_section_end_full_panels ($tab_id, $menu_id) {
		global $hf_navigation_tabs, $hf_sidebar_sections, $hf_section_options;
		$hf_section_options[$tab_id][$menu_id][] = array(
				'type' => 'section_end_full_panels'
		);
	}
	public static function field_section_start_full_panels ($tab_id, $menu_id) {
		global $hf_navigation_tabs, $hf_sidebar_sections, $hf_section_options;
		$hf_section_options[$tab_id][$menu_id][] = array(
				'type' => 'section_start_full_panels'
		);
	}
	
}
