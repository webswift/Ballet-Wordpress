<?php

class HelloFollowersOptionsInterface {
	
	public static function draw_form_start($custom = false, $group = '') {
		global $_REQUEST, $current_tab;
		
		$active_section = isset($_REQUEST['section']) ? $_REQUEST['section'] : '';
		$active_subsection = isset($_REQUEST['subsection']) ? $_REQUEST['subsection'] : '';
		
		$admin_template = hf_options_value('admin_template');
		if (!empty($admin_template)) {
			$admin_template = "hf-template-".$admin_template;
		}
		
		echo '<div id="hf-scroll-top"></div>';
		echo '<form id="hf_options_form" enctype="multipart/form-data" method="post" action="">';
		if ($custom && !empty($group)) {
			settings_fields( $group );
		}
		else {
			settings_fields( 'hf_settings_group' );
		}
		echo '<input id="section" name="section" type="hidden" value="'.$active_section.'"/>';
		echo '<input id="subsection" name="subsection" type="hidden" value="'.$active_subsection.'"/>';
		echo '<input id="tab" name="tab" type="hidden" value="'.$current_tab.'"/>';
		echo '<div class="hf-options '.$admin_template.'" id="hf-options">';
	}
	
	public static function draw_header($title = '', $hide_update_button = false, $wizard_tab = false) {
		if ($hide_update_button) {
			echo '<div class="hf-options-header" id="hf-options-header">
			<div class="hf-options-title">
			' . $title . '
			</div>
			<a href="#" text="Back to top" class="button button-hf hf-button-backtotop">' . __ ( 'Back To Top', 'hellofollowers' ) . '</a>
			
			</div>';
		
		} 
		else {
			$update_button_text = __('Update Settings', 'hellofollowers');
			$next_prev_buttons = "";
			if ($wizard_tab) {
				$update_button_text = __('Save Settings', 'hellofollowers');
				$next_prev_buttons = '<a name="prevbutton" id="prevbutton" class="button hf-wizard-prev">< Previous</a>&nbsp;<a name="nextbutton" id="nextbutton" class="button hf-wizard-next">Next ></a>&nbsp;&nbsp;&nbsp;';
			}
			
			echo '<div class="hf-options-header" id="hf-options-header">
				<div class="hf-options-title">
			  	' . $title . '<span class="hf-options-subtitle"></span>
				</div>		
				<a href="#" text="Back to top" class="button button-hf hf-button-backtotop">' . __ ( 'Back To Top', 'hellofollowers' ) . '</a>
				'.$next_prev_buttons.'
				<input type="Submit" name="Submit" value="' . $update_button_text . '" class="button-primary" />				
			</div>';
		}
	}
	
	public static function draw_sidebar($options = array()) {
		
		echo '<div class="hf-options-sidebar" id="hf-options-sidebar">';

		echo '<ul class="hf-options-group-menu" id="sticky-navigation">';
		
		foreach ($options as $single) {
			$type = $single['type'];
			$field_id = isset($single['field_id']) ? $single['field_id'] : '';
			$title = isset($single['title']) ? $single['title'] : '';
			$sub_menuaction = isset($single['action']) ? $single['action'] : '';
			$default_child = isset($single['default_child']) ? $single['default_child'] : '';
			$icon = isset($single['icon']) ? $single['icon'] : '';
			
			$level2 = isset($single['level2']) ? $single['level2'] : '';
			
			if ($icon == 'default') {
				$icon = 'gear';
			}
			
			if ($level2 == 'true') {
				$icon = 'circle hf-navigation-small-icon';
			}
			
			if ($icon != '') {
				$icon = sprintf('<i class="hf-sidebar-icon fa fa-%1$s"></i>', $icon);
			}
			
			$css_class = "";
			switch ($type) {
				case "menu_item":
					$css_class = "hf-menu-item";
					
					if ($sub_menuaction == "activate_first") {
						$css_class .= " hf-activate-first";
					}
					break;
				case "sub_menu_item":
					$css_class = "hf-submenu-item";
					
					if ($sub_menuaction == 'menu') {
						$css_class .= " hf-submenu-menuitem";
					}
					
					if ($level2 == 'true') {
						$css_class .= " level2";
					}
					
					if ($level2 != 'title') {
						$css_class .= ' hf-submenu-with-action';
					}
					
					break;
				case "heading":
					$css_class = "hf-title";
					break;
				default:
					$css_class = "hf-menu-item";
					break;
			}
			
			printf('<li class="%1$s" data-menu="%2$s" data-activate-child="%4$s" id="hf-menu-%2$s"><a href="#">%5$s%3$s</a></li>', $css_class, $field_id, $title, $default_child, $icon);
		}
		
		echo '</ul>';
		
		echo '</div>';
		
	}
	
	public static function draw_content($options = array(), $custom = false, $user_settings = array()) {
		echo '<div class="hf-options-container" style="min-height: 1500px;">';
		
		//print_r($options);
		
		foreach($options as $section => $fields) {
			printf('<div id="hf-container-%1$s" class="hf-data-container">',$section);
									
			echo '<table border="0" cellpadding="5" cellspacing="0" width="100%">
						<col width="25%" />
						<col width="75%" />';
			
			$section_options = $fields;
			
			HelloFollowersOptionsFramework::reset_row_status();
			
			foreach ($section_options as $option) {
				HelloFollowersOptionsFramework::draw_options_field($option, $custom, $user_settings);
			}
			
			echo '</table>';
			
			if (count(HelloFollowersOptionsFramework::$heading_navigations) > 1) {
				echo '<div class="hf-internal-navigation">';
				echo '<div class="hf-internal-navigation-title">Quick Navigate <a class="hf-internal-navigation-close" href="#"></a></div>';
				echo '<div class="hf-internal-navigation-inner">';
				foreach (HelloFollowersOptionsFramework::$heading_navigations as $navigation_item) {
					echo '<a href="#'.$navigation_item['id'].'" data-goto="'.$navigation_item['id'].'" class="hf-internal-navigation-item">'.$navigation_item['title'].'</a>';
				}
				echo '</div></div>';
			}
			
			echo '</div>';
		}
		
		echo '</div>';
	}	
	
	public static function draw_form_end() {
		echo '</div>';
		echo '</form>';
	}
	
}

?>