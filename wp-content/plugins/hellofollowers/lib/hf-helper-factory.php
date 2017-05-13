<?php

if (!function_exists('hf_manager')) {
	function hf_manager() {
		return HelloFollowers::getInstance();
	}	
}

if (!function_exists('hf_options_value')) {
	function hf_options_value($param, $default = '') {
		return hf_manager()->options_value($param, $default);
	}
}

if (!function_exists('hf_options_bool_value')) {
	function hf_options_bool_value($param) {
		return hf_manager()->options_bool_value($param);
	}
}

if (!function_exists('hf_options')) {
	function hf_options() {
		return hf_manager()->options();
	}
}

if (!function_exists('hf_followers')) {
	function hf_followers(){
		return hf_manager()->followers();
	}
}