<?php

// check for Visual Composer Added and Activated
if (!function_exists('vc_map')) {
	return;
}

// using shortcode generator options for better shortcode mapping
if (!class_exists('HelloFollowersCodeGenerator')) {
	include_once HF_PLUGIN_ROOT. 'lib/admin/hf-shortcode-generator-class.php';
}

// creating instance of Shortcode Generator
$scg = new HelloFollowersCodeGenerator();

$listOfMappedShortcodes = array(
		"hello-followers" => array("name" => "Hello Followers", "description" => "Display social followers counter"),
		"hello-total-followers" => array("name" => "Hello Total Followers", "description" => "Display number of total social followers"),		
		
		);

$vc_shortcode_settings = array ();

foreach ( $listOfMappedShortcodes as $shortcode => $data ) {
	$scg->activate ( $shortcode );
	$last_used_group = $data ['name'];
	$vc_shortcode_settings[$shortcode] = array ();
	$exist_network_names = false;
	$exist_sections = false;
	
	//print_r($scg->shortcodeOptions);
	
	foreach ( $scg->shortcodeOptions as $param => $settings ) {
		$type = isset ( $settings ['type'] ) ? $settings ['type'] : 'textbox';
		$text = isset ( $settings ['text'] ) ? $settings ['text'] : '';
		if (($type == "section" || $type == 'subsection' ) && !empty($text)) {
			$exist_sections = true;
		}
	}
	
	foreach ( $scg->shortcodeOptions as $param => $settings ) {
		$type = isset ( $settings ['type'] ) ? $settings ['type'] : 'textbox';
		$text = isset ( $settings ['text'] ) ? $settings ['text'] : '';
		if (($type == "section" || $type == 'subsection') && !empty($text)) {
			$last_used_group = $text;
		}
		if ($type == "section" || $type == "subsection" || $type == 'separator') {
			continue;
		}
		
		// additional options
		
		$comment = isset ( $settings ['comment'] ) ? $settings ['comment'] : '';
		$default_value = isset ( $settings ['value'] ) ? $settings ['value'] : '';
		$values = isset ( $settings ['sourceOptions'] ) ? $settings ['sourceOptions'] : array ();
		
		$vc_type = $type;
		
		if ($vc_type == "textbox") {
			$vc_type = "textfield";
		}
		
		$is_networks_selection = false;
		
		
		$singleParam = array ();
		$singleParam ['type'] = $vc_type;
		$singleParam ['heading'] = $text;
		$singleParam ['param_name'] = $param;
		$singleParam ['description'] = $comment;
		if ($exist_sections) {
			$singleParam ['group'] = $last_used_group;
		}		
		
		if ($param == "title" || $param == "columns" || $param == "template") {
			$singleParam ['admin_label'] = true;
		}
		
		if ($vc_type == "checkbox") {
			if (! $is_networks_selection) {
				$singleParam ['value'] = array ();
				$singleParam ['value'] ["Yes"] = $default_value;
			} else {
				$singleParam ['value'] = array ();
				$singleParam ['admin_label'] = true;
				
			}
		}
		if ($vc_type == "dropdown") {
			$singleParam ['value'] = array ();
			foreach ( $values as $key => $value ) {
				$singleParam ['value'] [$value] = $key;
			}
		}
		
		$vc_shortcode_settings[$shortcode]  [] = $singleParam;
	}
	
	//print_r($vc_shortcode_settings[$shortcode]);
	
	vc_map ( array ("name" => $data ['name'], 
			"base" => $shortcode, 
			"icon" => 'vc-'.$shortcode, 
			"category" => __ ( 'Hello Followers', 'hellofollowers' ), 
			"description" => $data ['description'], 
			"value" => $data ['description'], 
			"params" => $vc_shortcode_settings[$shortcode]  ) );
}
?>