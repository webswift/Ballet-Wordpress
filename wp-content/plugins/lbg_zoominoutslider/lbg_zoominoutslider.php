<?php
/*
Plugin Name: LambertGroup - Zoom In/Out Effect Sliders Full Collection
Description: This plugin will allow you to administrate an advanced slider with Zoom In/Out Effect  and animated text from any direction: top, bottom, left and right.
Version: 3.5.1
Author: Lambert Group
Author URI: http://codecanyon.net/user/LambertGroup/portfolio?ref=LambertGroup
*/


ini_set('display_errors', 0);
//$wpdb->show_errors();
$lbg_zoominoutslider_path = trailingslashit(dirname(__FILE__));  //empty

//all the messages
$lbg_zoominoutslider_messages = array(
		'version' => '<div class="error">LambertGroup - Zoom In/Out Effect Sliders Full Collection plugin requires WordPress 3.0 or newer. <a href="http://codex.wordpress.org/Upgrading_WordPress">Please update!</a></div>',
		'empty_img' => 'Image - required',
		'invalid_request' => 'Invalid Request!',
		'generate_for_this_player' => 'You can start customizing this slider.',
		'data_saved' => 'Data Saved!'
	);
global $general_param; // for activation_hook only
$general_param = array(
	'css_styles_const' => '/*textWhiteBgBlack*/
.lbg_zoominoutslider_textWhiteBgBlack_small {
	font-family: Arial;
	font-size:12px;
	line-height:12px;
	font-weight:bold;
	color:#FFF;
	background:#000;
	padding:5px 6px;
	margin:0;
}
.lbg_zoominoutslider_textWhiteBgBlack_small a {
	color:#FFF;
	text-decoration:underline;
}
.lbg_zoominoutslider_textWhiteBgBlack_small a:hover {
	color:#F00;
	text-decoration:underline;
}
.lbg_zoominoutslider_textWhiteBgBlack_medium {
	font-family: Arial;
	font-size:22px;
	line-height:22px;
	font-weight:normal;
	color:#FFF;
	background:#000;
	padding:6px 7px;
	margin:0;
}
.lbg_zoominoutslider_textWhiteBgBlack_medium a {
	color:#FFF;
	text-decoration:underline;
}
.lbg_zoominoutslider_textWhiteBgBlack_medium a:hover {
	color:#F00;
	text-decoration:underline;
}
.lbg_zoominoutslider_textWhiteBgBlack_large {
	font-family: Arial;
	font-size:36px;
	line-height:36px;
	font-weight:normal;
	color:#FFF;
	background:#000;
	padding:7px 10px;
	margin:0;
}
.lbg_zoominoutslider_textWhiteBgBlack_large a {
	color:#FFF;
	text-decoration:underline;
}
.lbg_zoominoutslider_textWhiteBgBlack_large a:hover {
	color:#F00;
	text-decoration:underline;
}
/*textBlackBgWhite*/
.lbg_zoominoutslider_textBlackBgWhite_small {
	font-family: Arial;
	font-size:12px;
	line-height:12px;
	font-weight:bold;
	color:#000;
	background:#FFF;
	padding:5px 6px;
	margin:0;
}
.lbg_zoominoutslider_textBlackBgWhite_small a {
	color:#000;
	text-decoration:underline;
}
.lbg_zoominoutslider_textBlackBgWhite_small a:hover {
	color:#F00;
	text-decoration:underline;
}
.lbg_zoominoutslider_textBlackBgWhite_medium {
	font-family: Arial;
	font-size:22px;
	line-height:22px;
	font-weight:normal;
	color:#000;
	background:#FFF;
	padding:6px 7px;
	margin:0;
}
.lbg_zoominoutslider_textBlackBgWhite_medium a {
	color:#000;
	text-decoration:underline;
}
.lbg_zoominoutslider_textBlackBgWhite_medium a:hover {
	color:#F00;
	text-decoration:underline;
}
.lbg_zoominoutslider_textBlackBgWhite_large {
	font-family: Arial;
	font-size:36px;
	line-height:36px;
	font-weight:normal;
	color:#000;
	background:#FFF;
	padding:7px 10px;
	margin:0;
}
.lbg_zoominoutslider_textBlackBgWhite_large a {
	color:#000;
	text-decoration:underline;
}
.lbg_zoominoutslider_textBlackBgWhite_large a:hover {
	color:#F00;
	text-decoration:underline;
}
/*textRedBgWhite*/
.lbg_zoominoutslider_textRedBgWhite_small {
	font-family: Arial;
	font-size:12px;
	line-height:12px;
	font-weight:bold;
	color:#F00;
	background:#FFF;
	padding:5px 6px;
	margin:0;
}
.lbg_zoominoutslider_textRedBgWhite_small a {
	color:#F00;
	text-decoration:underline;
}
.lbg_zoominoutslider_textRedBgWhite_small a:hover {
	color:#000;
	text-decoration:underline;
}
.lbg_zoominoutslider_textRedBgWhite_medium {
	font-family: Arial;
	font-size:22px;
	line-height:22px;
	font-weight:normal;
	color:#F00;
	background:#FFF;
	padding:6px 7px;
	margin:0;
}
.lbg_zoominoutslider_textRedBgWhite_medium a {
	color:#F00;
	text-decoration:underline;
}
.lbg_zoominoutslider_textRedBgWhite_medium a:hover {
	color:#000;
	text-decoration:underline;
}
.lbg_zoominoutslider_textRedBgWhite_large {
	font-family: Arial;
	font-size:36px;
	line-height:36px;
	font-weight:normal;
	color:#F00;
	background:#FFF;
	padding:7px 10px;
	margin:0;
}
.lbg_zoominoutslider_textRedBgWhite_large a {
	color:#F00;
	text-decoration:underline;
}
.lbg_zoominoutslider_textRedBgWhite_large a:hover {
	color:#000;
	text-decoration:underline;
}
/*textBlueBgWhite*/
.lbg_zoominoutslider_textBlueBgWhite_small {
	font-family: Arial;
	font-size:12px;
	line-height:12px;
	font-weight:bold;
	color:#00F;
	background:#FFF;
	padding:5px 6px;
	margin:0;
}
.lbg_zoominoutslider_textBlueBgWhite_small a {
	color:#00F;
	text-decoration:underline;
}
.lbg_zoominoutslider_textBlueBgWhite_small a:hover {
	color:#F00;
	text-decoration:underline;
}
.lbg_zoominoutslider_textBlueBgWhite_medium {
	font-family: Arial;
	font-size:22px;
	line-height:22px;
	font-weight:normal;
	color:#00F;
	background:#FFF;
	padding:6px 7px;
	margin:0;
}
.lbg_zoominoutslider_textBlueBgWhite_medium a {
	color:#00F;
	text-decoration:underline;
}
.lbg_zoominoutslider_textBlueBgWhite_medium a:hover {
	color:#F00;
	text-decoration:underline;
}

.lbg_zoominoutslider_textBlueBgWhite_large {
	font-family: Arial;
	font-size:36px;
	line-height:36px;
	font-weight:normal;
	color:#00F;
	background:#FFF;
	padding:7px 10px;
	margin:0;
}
.lbg_zoominoutslider_textBlueBgWhite_large a {
	color:#00F;
	text-decoration:underline;
}
.lbg_zoominoutslider_textBlueBgWhite_large a:hover {
	color:#F00;
	text-decoration:underline;
}
/*textWhiteBgTransparent*/
.lbg_zoominoutslider_textWhiteBgTransparent_small {
	font-family: Arial;
	font-size:12px;
	line-height:12px;
	font-weight:bold;
	color:#FFF;
	background:none;
	padding:0;
	margin:0;
}
.lbg_zoominoutslider_textWhiteBgTransparent_small a {
	color:#FFF;
	text-decoration:underline;
}
.lbg_zoominoutslider_textWhiteBgTransparent_small a:hover {
	color:#F00;
	text-decoration:underline;
}
.lbg_zoominoutslider_textWhiteBgTransparent_medium {
	font-family: Arial;
	font-size:22px;
	line-height:22px;
	font-weight:normal;
	color:#FFF;
	background:none;
	padding:0;
	margin:0;
}
.lbg_zoominoutslider_textWhiteBgTransparent_medium a {
	color:#FFF;
	text-decoration:underline;
}
.lbg_zoominoutslider_textWhiteBgTransparent_medium a:hover {
	color:#F00;
	text-decoration:underline;
}
.lbg_zoominoutslider_textWhiteBgTransparent_large {
	font-family: Arial;
	font-size:36px;
	line-height:36px;
	font-weight:normal;
	color:#FFF;
	background:none;
	padding:0;
	margin:0;
}
.lbg_zoominoutslider_textWhiteBgTransparent_large a {
	color:#FFF;
	text-decoration:underline;
}
.lbg_zoominoutslider_textWhiteBgTransparent_large a:hover {
	color:#F00;
	text-decoration:underline;
}
/*textBlackBgTransparent*/
.lbg_zoominoutslider_textBlackBgTransparent_small {
	font-family: Arial;
	font-size:12px;
	line-height:12px;
	font-weight:bold;
	color:#000;
	background:none;
	padding:0;
	margin:0;
}
.lbg_zoominoutslider_textBlackBgTransparent_small a {
	color:#000;
	text-decoration:underline;
}
.lbg_zoominoutslider_textBlackBgTransparent_small a:hover {
	color:#F00;
	text-decoration:underline;
}
.lbg_zoominoutslider_textBlackBgTransparent_medium {
	font-family: Arial;
	font-size:22px;
	line-height:22px;
	font-weight:normal;
	color:#000;
	background:none;
	padding:0;
	margin:0;
}
.lbg_zoominoutslider_textBlackBgTransparent_medium a {
	color:#000;
	text-decoration:underline;
}
.lbg_zoominoutslider_textBlackBgTransparent_medium a:hover {
	color:#F00;
	text-decoration:underline;
}
.lbg_zoominoutslider_textBlackBgTransparent_large {
	font-family: Arial;
	font-size:36px;
	line-height:36px;
	font-weight:normal;
	color:#000;
	background:none;
	padding:0;
	margin:0;
}
.lbg_zoominoutslider_textBlackBgTransparent_large a {
	color:#000;
	text-decoration:underline;
}
.lbg_zoominoutslider_textBlackBgTransparent_large a:hover {
	color:#F00;
	text-decoration:underline;
}
/*textRedBgTransparent*/
.lbg_zoominoutslider_textRedBgTransparent_small {
	font-family: Arial;
	font-size:12px;
	line-height:12px;
	font-weight:bold;
	color:#F00;
	background:none;
	padding:0;
	margin:0;
}
.lbg_zoominoutslider_textRedBgTransparent_small a {
	color:#F00;
	text-decoration:underline;
}
.lbg_zoominoutslider_textRedBgTransparent_small a:hover {
	color:#000;
	text-decoration:underline;
}
.lbg_zoominoutslider_textRedBgTransparent_medium {
	font-family: Arial;
	font-size:22px;
	line-height:22px;
	font-weight:normal;
	color:#F00;
	background:none;
	padding:0;
	margin:0;
}
.lbg_zoominoutslider_textRedBgTransparent_medium a {
	color:#F00;
	text-decoration:underline;
}
.lbg_zoominoutslider_textRedBgTransparent_medium a:hover {
	color:#000;
	text-decoration:underline;
}
.lbg_zoominoutslider_textRedBgTransparent_large {
	font-family: Arial;
	font-size:36px;
	line-height:36px;
	font-weight:normal;
	color:#F00;
	background:none;
	padding:0;
	margin:0;
}
.lbg_zoominoutslider_textRedBgTransparent_large a {
	color:#F00;
	text-decoration:underline;
}
.lbg_zoominoutslider_textRedBgTransparent_large a:hover {
	color:#000;
	text-decoration:underline;
}
/*textBlueBgTransparent*/
.lbg_zoominoutslider_textBlueBgTransparent_small {
	font-family: Arial;
	font-size:12px;
	line-height:12px;
	font-weight:bold;
	color:#00F;
	background:none;
	padding:0;
	margin:0;
}
.lbg_zoominoutslider_textBlueBgTransparent_small a {
	color:#00F;
	text-decoration:underline;
}
.lbg_zoominoutslider_textBlueBgTransparent_small a:hover {
	color:#F00;
	text-decoration:underline;
}
.lbg_zoominoutslider_textBlueBgTransparent_medium {
	font-family: Arial;
	font-size:22px;
	line-height:22px;
	font-weight:normal;
	color:#00F;
	background:none;
	padding:0;
	margin:0;
}
.lbg_zoominoutslider_textBlueBgTransparent_medium a {
	color:#00F;
	text-decoration:underline;
}
.lbg_zoominoutslider_textBlueBgTransparent_medium a:hover {
	color:#F00;
	text-decoration:underline;
}
.lbg_zoominoutslider_textBlueBgTransparent_large {
	font-family: Arial;
	font-size:36px;
	line-height:36px;
	font-weight:normal;
	color:#00F;
	background:none;
	padding:0;
	margin:0;
}
.lbg_zoominoutslider_textBlueBgTransparent_large a {
	color:#00F;
	text-decoration:underline;
}
.lbg_zoominoutslider_textBlueBgTransparent_large a:hover {
	color:#F00;
	text-decoration:underline;
}'
);	

	
global $wp_version;

if ( !version_compare($wp_version,"3.0",">=")) {
	die ($lbg_zoominoutslider_messages['version']);
}




function lbg_zoominoutslider_activate() {
	//db creation, create admin options etc.
	global $wpdb;
	global $general_param;
	
	//$wpdb->show_errors();

	$lbg_zoominoutslider_collate = ' COLLATE utf8_general_ci';
	
	$sql0 = "CREATE TABLE `" . $wpdb->prefix . "lbg_kenburnsslider_banners` (
			`id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
			`name` VARCHAR( 255 ) NOT NULL ,
			PRIMARY KEY ( `id` )
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8";
	
	$sql1 = "CREATE TABLE `" . $wpdb->prefix . "lbg_kenburnsslider_settings` (
	  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
	  `width` smallint(5) unsigned NOT NULL DEFAULT '940',
	  `height` smallint(5) unsigned NOT NULL DEFAULT '364',
	  `width100Proc` varchar(8) NOT NULL DEFAULT 'false',
	  `height100Proc` varchar(8) NOT NULL DEFAULT 'false',  
	  `skin` varchar(255) NOT NULL DEFAULT 'opportune',
	  `autoPlay` smallint(5) unsigned NOT NULL DEFAULT '16',
	  `loop` varchar(8) NOT NULL DEFAULT 'true',
	  `fadeSlides` varchar(8) NOT NULL DEFAULT 'true',
	  `setAsBg` varchar(8) NOT NULL DEFAULT 'false',
	  `horizontalPosition` varchar(50) NOT NULL DEFAULT 'center',
	  `verticalPosition` varchar(50) NOT NULL DEFAULT 'center',
	  `initialZoom` float unsigned NOT NULL DEFAULT '1',
	  `finalZoom` float unsigned NOT NULL DEFAULT '0.8',
	  `duration` smallint(5) unsigned NOT NULL DEFAULT '20',
	  `durationIEfix` smallint(5) unsigned NOT NULL DEFAULT '30',
	  `zoomEasing` varchar(255) NOT NULL DEFAULT 'ease',
	  `target` varchar(8) NOT NULL DEFAULT '_blank',
	  `pauseOnMouseOver` varchar(8) NOT NULL DEFAULT 'true',  
	  `showAllControllers` varchar(8) NOT NULL DEFAULT 'true',
	  `showNavArrows` varchar(8) NOT NULL DEFAULT 'true',
	  `showOnInitNavArrows` varchar(8) NOT NULL DEFAULT 'true',
	  `autoHideNavArrows` varchar(8) NOT NULL DEFAULT 'true',
	  `showBottomNav` varchar(8) NOT NULL DEFAULT 'true',
	  `showOnInitBottomNav` varchar(8) NOT NULL DEFAULT 'true',
	  `autoHideBottomNav` varchar(8) NOT NULL DEFAULT 'false',
	  `showPreviewThumbs` varchar(8) NOT NULL DEFAULT 'true',
	  `enableTouchScreen` varchar(8) NOT NULL DEFAULT 'true',
	  `showCircleTimer` varchar(8) NOT NULL DEFAULT 'true',
	  `showCircleTimerIE8IE7` varchar(8) NOT NULL DEFAULT 'false',
	  `circleRadius` smallint(5) unsigned NOT NULL DEFAULT '8',
	  `circleLineWidth` smallint(5) unsigned NOT NULL DEFAULT '4',
	  `circleColor` varchar(8) NOT NULL DEFAULT 'ffffff',
	  `circleAlpha` smallint(5) unsigned NOT NULL DEFAULT '50',
	  `behindCircleColor` varchar(8) NOT NULL DEFAULT '000000',
	  `behindCircleAlpha` smallint(5) unsigned NOT NULL DEFAULT '20',  
	  `responsive` varchar(8) NOT NULL DEFAULT 'false',
	  `responsiveRelativeToBrowser` varchar(8) NOT NULL DEFAULT 'true',
	  `numberOfThumbsPerScreen` smallint(5) NOT NULL DEFAULT '0',  
	  `thumbsWrapperMarginTop` smallint(5) NOT NULL DEFAULT '30',
	  `thumbsOnMarginTop` smallint(5) NOT NULL DEFAULT '0', 
	  
	  `scrollSlideDuration` float NOT NULL DEFAULT '0.8',
	  `scrollSlideEasing` varchar(255) NOT NULL DEFAULT 'swing',
	
	  `defaultEasing` varchar(255) NOT NULL DEFAULT 'swing',
	  `myloaderTime` smallint(5) unsigned NOT NULL DEFAULT '1',
	  `defaultExitLeft` smallint(5) unsigned NOT NULL DEFAULT '0',
	  `defaultExitTop` smallint(5) unsigned NOT NULL DEFAULT '0',
	  `defaultExitFade` float unsigned NOT NULL DEFAULT '1',
	  `defaultExitDuration` smallint(5) unsigned NOT NULL DEFAULT '0',
	  `defaultExitDelay` smallint(5) unsigned NOT NULL DEFAULT '0',
	  `defaultExitEasing` varchar(255) NOT NULL DEFAULT 'swing',
	  `defaultExitOFF` varchar(8) NOT NULL DEFAULT 'true',  
	  PRIMARY KEY  (`id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8";
	
	$sql2 = "CREATE TABLE `". $wpdb->prefix . "lbg_kenburnsslider_playlist` (
	  `id` int(10) unsigned NOT NULL auto_increment,
	  `bannerid` int(10) unsigned NOT NULL,
	  `img` text,
	  `thumbnail` text,
	  `alt_text` text,
	  `content` text,
	  `data-video` varchar(8),
	  `data-horizontalPosition` varchar(30),
	  `data-verticalPosition` varchar(30),
	  `data-initialZoom` FLOAT NOT NULL DEFAULT '0',
	  `data-finalZoom` FLOAT NOT NULL DEFAULT '0',
	  `data-duration` smallint(5) NOT NULL DEFAULT '0',
	  `data-zoomEasing` varchar(30),
	  `data-link` text,
	  `data-target` varchar(8),
	  `ord` int(10) unsigned NOT NULL,
	  `data-autoPlay` smallint(5) NOT NULL DEFAULT '0',
	  PRIMARY KEY  (`id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8";
	
	$sql3 = "CREATE TABLE `". $wpdb->prefix . "lbg_kenburnsslider_texts` (
	  `id` int(10) unsigned NOT NULL auto_increment,
	  `photoid` int(10) unsigned NOT NULL,
	  `content` text,
	  `data-initial-left` smallint(5),
	  `data-initial-top` smallint(5),	  
	  `data-initial-skew` varchar(30),
	  `data-initial-scale` varchar(30),	  
	  `data-final-left` smallint(5),	  
	  `data-final-top` smallint(5),	  
	  `data-final-skew` varchar(30),
	  `data-final-scale` varchar(30),	  
	  `data-duration` float unsigned,
	  `data-fade-start` smallint(5) unsigned,
	  `data-delay` float unsigned,
	  `css` text,	  
	  `img_src` text,
	  `element-link` text,
	  `element-link-target` varchar(20),
	  `data-easing` varchar(20),
	  `data-exit-left` smallint(5),
	  `data-exit-top` smallint(5),
	  `data-exit-fade` float,
	  `data-exit-duration` float unsigned,
	  `data-exit-delay` float unsigned,
	  `data-exit-easing` varchar(20),
	  `data-exit-off` varchar(8),	  
	  `data-exit-skew` varchar(30),
	  `data-exit-scale` varchar(30),	  
	  `data-intermediate-left` smallint(5),
	  `data-intermediate-top` smallint(5),
	  `data-intermediate-duration` float unsigned,
	  `data-intermediate-easing` varchar(20),
	  `data-intermediate-delay` float unsigned,	  
	  `data-intermediate-skew` varchar(30),
	  `data-intermediate-scale` varchar(30),
	  `ord` int(10) unsigned NOT NULL,
	  PRIMARY KEY  (`id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8";	
	
	$sql4 = "CREATE TABLE `". $wpdb->prefix . "lbg_kenburnsslider_css_definitions` (
	  `id` int(2) unsigned NOT NULL auto_increment,
	  `css_styles` text,
	  `css_styles_orig` text,
	  PRIMARY KEY  (`id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8";		
	
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql0.$lbg_zoominoutslider_collate);
	dbDelta($sql1.$lbg_zoominoutslider_collate);
	dbDelta($sql2.$lbg_zoominoutslider_collate);
	dbDelta($sql3.$lbg_zoominoutslider_collate);
	dbDelta($sql4.$lbg_zoominoutslider_collate);
	

	

	
	
	//initialize the banners table with the first banner type
	$rows_count = $wpdb->get_var( "SELECT COUNT(*) FROM ". $wpdb->prefix ."lbg_kenburnsslider_banners;" );
	if (!$rows_count) {
		$wpdb->insert( 
			$wpdb->prefix . "lbg_kenburnsslider_banners", 
			array( 
				'name' => 'First Slider'
			), 
			array(
				'%s'			
			) 
		);	
	}	
	
	// initialize the settings
	$rows_count = $wpdb->get_var( "SELECT COUNT(*) FROM ". $wpdb->prefix ."lbg_kenburnsslider_settings;" );
	if (!$rows_count) {
		lbg_zoominoutslider_insert_settings_record(1);
	}	
	
	//initialize the css styles
	$rows_count = $wpdb->get_var( "SELECT COUNT(*) FROM ". $wpdb->prefix ."lbg_kenburnsslider_css_definitions;" );
	if (!$rows_count) {
		$wpdb->insert( 
			$wpdb->prefix . "lbg_kenburnsslider_css_definitions", 
			array( 
				'css_styles' => $general_param['css_styles_const'],
				'css_styles_orig' => $general_param['css_styles_const']
			), 
			array(
				'%s',
				'%s'			
			) 
		);	
	}		
	//echo $wpdb->last_query;
	
}


function lbg_zoominoutslider_uninstall() {
	global $wpdb;
	mysql_query("DROP TABLE `" . $wpdb->prefix . "lbg_kenburnsslider_settings`" );
	mysql_query("DROP TABLE `" . $wpdb->prefix . "lbg_kenburnsslider_playlist`" );
	mysql_query("DROP TABLE `" . $wpdb->prefix . "lbg_kenburnsslider_banners`" );
	mysql_query("DROP TABLE `" . $wpdb->prefix . "lbg_kenburnsslider_texts`" );
	mysql_query("DROP TABLE `" . $wpdb->prefix . "lbg_kenburnsslider_css_definitions`" );
}

function lbg_zoominoutslider_insert_settings_record($banner_id) {
	global $wpdb;
	$wpdb->insert( 
			$wpdb->prefix . "lbg_kenburnsslider_settings", 
			array( 
				'width' => 940, 
				'height' => 364,
				'skin' => 'opportune'
			), 
			array( 
				'%d', 
				'%d',
				'%s'
			) 
		);
}


function lbg_zoominoutslider_init_sessions() {
	global $wpdb;
	if (is_admin()) { 
		if (!session_id()) {
			session_start();
			
			//initialize the session
			if (!isset($_SESSION['xid'])) {
				$safe_sql="SELECT * FROM (".$wpdb->prefix ."lbg_kenburnsslider_banners) LIMIT 0, 1";
				$row = $wpdb->get_row($safe_sql,ARRAY_A);
				//$row=lbg_zoominoutslider_unstrip_array($row);		
				$_SESSION['xid'] = $row['id'];
				$_SESSION['xname'] = $row['name'];
				
				$_SESSION['duplicate_layer']=0;
			}		
		}
	}
}


function lbg_zoominoutslider_load_styles() {
	global $wpdb;
	if(strpos($_SERVER['PHP_SELF'], 'wp-admin') !== false) {
		$page = (isset($_GET['page'])) ? $_GET['page'] : '';
		if(preg_match('/lbg_zoominoutslider/i', $page)) {
			wp_enqueue_style('lbg_zoominoutslider_css', plugins_url('css/styles.css', __FILE__));
			wp_enqueue_style('lbg_zoominoutslider_jquery-custom_css', plugins_url('css/custom-theme/jquery-ui-1.8.10.custom.css', __FILE__));
			wp_enqueue_style('lbg_zoominoutslider_colorpicker_css', plugins_url('css/colorpicker/colorpicker.css', __FILE__));
			
			
			wp_enqueue_style('thickbox');
			
			wp_enqueue_style('lbg_zoominoutslider_site_css', plugins_url('zoominoutslider/bannerscollection_zoominout.css', __FILE__));
			//wp_enqueue_style('lbg_zoominoutslider_text_classes', plugins_url('zoominoutslider/text_classes.css', __FILE__));			
		}
	} else if (!is_admin()) { //loads css in front-end
		wp_enqueue_style('lbg_zoominoutslider_site_css', plugins_url('zoominoutslider/bannerscollection_zoominout.css', __FILE__));
		wp_enqueue_style('lbg_zoominoutslider_text_classes', plugins_url('zoominoutslider/text_classes.css', __FILE__));
	}
}

function lbg_zoominoutslider_load_scripts() {
	global $is_IE;
	$page = (isset($_GET['page'])) ? $_GET['page'] : '';
	if(preg_match('/lbg_zoominoutslider/i', $page)) {
		//loads scripts in admin
		//if (is_admin()) {
			//wp_deregister_script('jquery');
			/*wp_register_script('lbg-admin-jquery', plugins_url('js/jquery-1.5.1.js', __FILE__));
			wp_enqueue_script('lbg-admin-jquery');*/
			/*wp_deregister_script('jquery-ui-core');
			wp_deregister_script('jquery-ui-widget');
			wp_deregister_script('jquery-ui-mouse');
			wp_deregister_script('jquery-ui-accordion');
			wp_deregister_script('jquery-ui-autocomplete');
			wp_deregister_script('jquery-ui-slider');
			wp_deregister_script('jquery-ui-tabs');
			wp_deregister_script('jquery-ui-sortable');
			wp_deregister_script('jquery-ui-draggable');
			wp_deregister_script('jquery-ui-droppable');
			wp_deregister_script('jquery-ui-selectable');
			wp_deregister_script('jquery-ui-position');
			wp_deregister_script('jquery-ui-datepicker');
			wp_deregister_script('jquery-ui-resizable');
			wp_deregister_script('jquery-ui-dialog');
			wp_deregister_script('jquery-ui-button');			*/	
			
			wp_enqueue_script('jquery');
			
			//wp_register_script('lbg-admin-jquery-ui-min', plugins_url('js/jquery-ui-1.8.10.custom.min.js', __FILE__));
			//wp_register_script('lbg-admin-jquery-ui-min', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/jquery-ui.min.js');
			wp_register_script('lbg-admin-jquery-ui-min', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js');
			wp_enqueue_script('lbg-admin-jquery-ui-min');/**/
			
			
			//wp_enqueue_script('jquery-ui-core');
			/*wp_deregister_script( 'jquery-ui-core' );
   			wp_enqueue_script( 'jquery-ui-core', site_url( '/wp-includes/js/jquery/ui/jquery.ui.core.min.js' ), array('jquery'), false, false );*/
			
			/*wp_enqueue_script('jquery-ui-widget');
			wp_enqueue_script('jquery-ui-mouse');
			wp_enqueue_script('jquery-ui-accordion');
			wp_enqueue_script('jquery-ui-autocomplete');
			wp_enqueue_script('jquery-ui-slider');
			wp_enqueue_script('jquery-ui-tabs');
			wp_enqueue_script('jquery-ui-sortable');
			wp_enqueue_script('jquery-ui-draggable');
			wp_enqueue_script('jquery-ui-droppable');
			wp_enqueue_script('jquery-ui-selectable');
			wp_enqueue_script('jquery-ui-position');
			wp_enqueue_script('jquery-ui-datepicker');
			wp_enqueue_script('jquery-ui-resizable');
			wp_enqueue_script('jquery-ui-dialog');
			wp_enqueue_script('jquery-ui-button');
			
			wp_enqueue_script('jquery-form');
			wp_enqueue_script('jquery-color');
			wp_enqueue_script('jquery-masonry');
			wp_enqueue_script('jquery-ui-progressbar');
			wp_enqueue_script('jquery-ui-tooltip');
			
			wp_enqueue_script('jquery-effects-core');
			wp_enqueue_script('jquery-effects-blind');
			wp_enqueue_script('jquery-effects-bounce');
			wp_enqueue_script('jquery-effects-clip');
			wp_enqueue_script('jquery-effects-drop');
			wp_enqueue_script('jquery-effects-explode');
			wp_enqueue_script('jquery-effects-fade');
			wp_enqueue_script('jquery-effects-fold');
			wp_enqueue_script('jquery-effects-highlight');
			wp_enqueue_script('jquery-effects-pulsate');
			wp_enqueue_script('jquery-effects-scale');
			wp_enqueue_script('jquery-effects-shake');
			wp_enqueue_script('jquery-effects-slide');			
			wp_enqueue_script('jquery-effects-transfer'); */	
			
			wp_register_script('my-colorpicker', plugins_url('js/colorpicker/colorpicker.js', __FILE__));
			wp_enqueue_script('my-colorpicker');	

			wp_register_script('lbg-admin-toggle', plugins_url('js/myToggle.js', __FILE__));
			wp_enqueue_script('lbg-admin-toggle');
			

			wp_enqueue_script('media-upload');
			wp_enqueue_script('thickbox');
			
			/*wp_register_script('lbg-touch', plugins_url('zoominoutslider/js/jquery.ui.touch-punch.min.js', __FILE__));
			wp_enqueue_script('lbg-touch');		
			
			wp_register_script('lbg-lbg_zoominoutslider', plugins_url('zoominoutslider/js/bannerscollection_zoominout.js', __FILE__));
			wp_enqueue_script('lbg-lbg_zoominoutslider');	*/		
			
		
		//}
		
		//wp_enqueue_script('jquery');
		//wp_enqueue_script('jquery-ui-core');
		//wp_enqueue_script('jquery-ui-sortable');
		//wp_enqueue_script('thickbox');
		//wp_enqueue_script('media-upload');
		//wp_enqueue_script('farbtastic');
	} else if (!is_admin()) { //loads scripts in front-end
			/*wp_deregister_script('jquery-ui-core');
			wp_deregister_script('jquery-ui-widget');
			wp_deregister_script('jquery-ui-mouse');
			wp_deregister_script('jquery-ui-accordion');
			wp_deregister_script('jquery-ui-autocomplete');
			wp_deregister_script('jquery-ui-slider');
			wp_deregister_script('jquery-ui-tabs');
			wp_deregister_script('jquery-ui-sortable');
			wp_deregister_script('jquery-ui-draggable');
			wp_deregister_script('jquery-ui-droppable');
			wp_deregister_script('jquery-ui-selectable');
			wp_deregister_script('jquery-ui-position');
			wp_deregister_script('jquery-ui-datepicker');
			wp_deregister_script('jquery-ui-resizable');
			wp_deregister_script('jquery-ui-dialog');
			wp_deregister_script('jquery-ui-button');*/	
	
		wp_enqueue_script('jquery');
	
		//wp_enqueue_script('jquery-ui-core');
		
		//wp_register_script('lbg-jquery-ui-min', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/jquery-ui.min.js');
		/*wp_register_script('lbg-jquery-ui-min', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js');
		wp_enqueue_script('lbg-jquery-ui-min');*/
		
			wp_enqueue_script('jquery-ui-core');
			wp_enqueue_script('jquery-ui-widget');
			wp_enqueue_script('jquery-ui-mouse');
			wp_enqueue_script('jquery-ui-accordion');
			wp_enqueue_script('jquery-ui-autocomplete');
			wp_enqueue_script('jquery-ui-slider');
			wp_enqueue_script('jquery-ui-tabs');
			wp_enqueue_script('jquery-ui-sortable');
			wp_enqueue_script('jquery-ui-draggable');
			wp_enqueue_script('jquery-ui-droppable');
			wp_enqueue_script('jquery-ui-selectable');
			wp_enqueue_script('jquery-ui-position');
			wp_enqueue_script('jquery-ui-datepicker');
			wp_enqueue_script('jquery-ui-resizable');
			wp_enqueue_script('jquery-ui-dialog');
			wp_enqueue_script('jquery-ui-button');/***************************/
			
			wp_enqueue_script('jquery-form');
			wp_enqueue_script('jquery-color');
			wp_enqueue_script('jquery-masonry');
			wp_enqueue_script('jquery-ui-progressbar');
			wp_enqueue_script('jquery-ui-tooltip');
			
			wp_enqueue_script('jquery-effects-core');
			wp_enqueue_script('jquery-effects-blind');
			wp_enqueue_script('jquery-effects-bounce');
			wp_enqueue_script('jquery-effects-clip');
			wp_enqueue_script('jquery-effects-drop');
			wp_enqueue_script('jquery-effects-explode');
			wp_enqueue_script('jquery-effects-fade');
			wp_enqueue_script('jquery-effects-fold');
			wp_enqueue_script('jquery-effects-highlight');
			wp_enqueue_script('jquery-effects-pulsate');
			wp_enqueue_script('jquery-effects-scale');
			wp_enqueue_script('jquery-effects-shake');
			wp_enqueue_script('jquery-effects-slide');			
			wp_enqueue_script('jquery-effects-transfer');	
	
		wp_register_script('lbg-touch', plugins_url('zoominoutslider/js/jquery.ui.touch-punch.min.js', __FILE__));
		wp_enqueue_script('lbg-touch');
		
		wp_register_script('lbg-lbg_zoominoutslider', plugins_url('zoominoutslider/js/bannerscollection_zoominout.js', __FILE__));
		wp_enqueue_script('lbg-lbg_zoominoutslider');

	}
	
	
	

}



// adds the menu pages
function lbg_zoominoutslider_plugin_menu() {
	add_menu_page('LBG-ZOOMINOUT Admin Interface', 'LBG-ZOOMINOUT', 'edit_posts', 'lbg_zoominoutslider', 'lbg_zoominoutslider_overview_page',
	plugins_url('images/plg_icon.png', __FILE__));
	add_submenu_page( 'lbg_zoominoutslider', 'LBG-ZOOMINOUT Overview', 'Overview', 'edit_posts', 'lbg_zoominoutslider', 'lbg_zoominoutslider_overview_page');
	add_submenu_page( 'lbg_zoominoutslider', 'LBG-ZOOMINOUT Manage Sliders', 'Manage Sliders', 'edit_posts', 'lbg_zoominoutslider_Manage_Sliders', 'lbg_zoominoutslider_manage_sliders_page');
	add_submenu_page( 'lbg_zoominoutslider', 'LBG-ZOOMINOUT Manage Sliders Add New', 'Add New', 'edit_posts', 'lbg_zoominoutslider_Add_New', 'lbg_zoominoutslider_manage_sliders_add_new_page');
	add_submenu_page( 'lbg_zoominoutslider_Manage_Sliders', 'LBG-ZOOMINOUT Slider Settings', 'Slider Settings', 'edit_posts', 'lbg_zoominoutslider_Settings', 'lbg_kenburnsslider_settings_page');
	add_submenu_page( 'lbg_zoominoutslider_Manage_Sliders', 'LBG-ZOOMINOUT Slider Playlist', 'Playlist', 'edit_posts', 'lbg_zoominoutslider_Playlist', 'lbg_zoominoutslider_playlist_page');
	add_submenu_page( 'lbg_zoominoutslider_Manage_Sliders', 'LBG-ZOOMINOUT Edit Elements', 'Edit Elements', 'edit_posts', 'lbg_zoominoutslider_Edit_Elements', 'lbg_zoominoutslider_editElements_page');
	add_submenu_page( 'lbg_zoominoutslider', 'LBG-ZOOMINOUT Help', 'Help', 'edit_posts', 'lbg_zoominoutslider_Help', 'lbg_zoominoutslider_help_page');
}


//HTML content for overview page
function lbg_zoominoutslider_overview_page()
{
	include_once($lbg_zoominoutslider_path . 'tpl/overview.php');
}

//HTML content for Manage Banners
function lbg_zoominoutslider_manage_sliders_page()
{
	global $wpdb;
	global $lbg_zoominoutslider_messages;
	
	//delete banner
	if (isset($_GET['id'])) {
		

		

		//delete from wp_lbg_kenburnsslider_banners
		$wpdb->query($wpdb->prepare("DELETE FROM ".$wpdb->prefix."lbg_kenburnsslider_banners WHERE id = %d",$_GET['id']));
		
		//delete from wp_lbg_kenburnsslider_settings
		$wpdb->query($wpdb->prepare("DELETE FROM ".$wpdb->prefix."lbg_kenburnsslider_settings WHERE id = %d",$_GET['id']));
		
		//delete lbg_kenburnsslider_texts
		$safe_sql=$wpdb->prepare("SELECT id FROM ".$wpdb->prefix."lbg_kenburnsslider_playlist WHERE bannerid = %d",$_GET['id']);
		$result = $wpdb->get_results($safe_sql,ARRAY_A);
		if ($wpdb->num_rows) {
			foreach ( $result as $row ) {	
				$wpdb->query($wpdb->prepare("DELETE FROM ".$wpdb->prefix."lbg_kenburnsslider_texts WHERE photoid = %d",$row['id']));
			}
		}
		
		//delete from wp_lbg_kenburnsslider_playlist
		$wpdb->query($wpdb->prepare("DELETE FROM ".$wpdb->prefix."lbg_kenburnsslider_playlist WHERE bannerid = %d",$_GET['id']));
		
		//initialize the session
		$safe_sql="SELECT * FROM (".$wpdb->prefix ."lbg_kenburnsslider_banners) ORDER BY id";
		$row = $wpdb->get_row($safe_sql,ARRAY_A);
		$row=lbg_zoominoutslider_unstrip_array($row);
		if ($row['id']) {
			$_SESSION['xid']=$row['id'];
			$_SESSION['xname']=$row['name'];
		}		
	}
	
	
	if ($_GET['duplicate_id']!='') {	
			//banners
			$safe_sql=$wpdb->prepare( "INSERT INTO ".$wpdb->prefix ."lbg_kenburnsslider_banners ( `name` ) SELECT `name` FROM (".$wpdb->prefix ."lbg_kenburnsslider_banners) WHERE id = %d",$_GET['duplicate_id'] );
			$wpdb->query($safe_sql);			
			$bannerid=$wpdb->insert_id;
			
			//settings
			$safe_sql=$wpdb->prepare( "INSERT INTO ".$wpdb->prefix ."lbg_kenburnsslider_settings (`width`, `height`, `width100Proc`, `height100Proc`, `skin`, `autoPlay`, `loop`, `horizontalPosition`, `verticalPosition`, `initialZoom`, `finalZoom`, `duration`, `durationIEfix`, `target`, `pauseOnMouseOver`, `showAllControllers`, `showNavArrows`, `showOnInitNavArrows`, `autoHideNavArrows`, `showBottomNav`, `showOnInitBottomNav`, `autoHideBottomNav`, `showPreviewThumbs`, `enableTouchScreen`, `showCircleTimer`, `showCircleTimerIE8IE7`, `circleRadius`, `circleLineWidth`, `circleColor`, `circleAlpha`, `behindCircleColor`, `behindCircleAlpha`, `responsive`, `responsiveRelativeToBrowser`, `numberOfThumbsPerScreen`, `thumbsWrapperMarginTop`, `thumbsOnMarginTop`, `fadeSlides`, `setAsBg`,`scrollSlideDuration`, `scrollSlideEasing`, `defaultEasing`, `myloaderTime`, `defaultExitLeft`, `defaultExitTop`, `defaultExitDuration`, `defaultExitDelay`, `defaultExitEasing`, `defaultExitOFF`, `defaultExitFade`, `zoomEasing` ) SELECT `width`, `height`, `width100Proc`, `height100Proc`, `skin`, `autoPlay`, `loop`, `horizontalPosition`, `verticalPosition`, `initialZoom`, `finalZoom`, `duration`, `durationIEfix`, `target`, `pauseOnMouseOver`, `showAllControllers`, `showNavArrows`, `showOnInitNavArrows`, `autoHideNavArrows`, `showBottomNav`, `showOnInitBottomNav`, `autoHideBottomNav`, `showPreviewThumbs`, `enableTouchScreen`, `showCircleTimer`, `showCircleTimerIE8IE7`, `circleRadius`, `circleLineWidth`, `circleColor`, `circleAlpha`, `behindCircleColor`, `behindCircleAlpha`, `responsive`, `responsiveRelativeToBrowser`, `numberOfThumbsPerScreen`, `thumbsWrapperMarginTop`, `thumbsOnMarginTop`, `fadeSlides`, `setAsBg`, `scrollSlideDuration`, `scrollSlideEasing`, `defaultEasing`, `myloaderTime`, `defaultExitLeft`, `defaultExitTop`, `defaultExitDuration`, `defaultExitDelay`, `defaultExitEasing`, `defaultExitOFF`, `defaultExitFade`, `zoomEasing` FROM (".$wpdb->prefix ."lbg_kenburnsslider_settings) WHERE id = %d",$_GET['duplicate_id'] );
			$wpdb->query($safe_sql);
			//echo $wpdb->last_query;
			
			//playlist
			$safe_sql=$wpdb->prepare( "SELECT * FROM (".$wpdb->prefix ."lbg_kenburnsslider_playlist) WHERE bannerid = %d",$_GET['duplicate_id'] );
			$result = $wpdb->get_results($safe_sql,ARRAY_A);
			foreach ( $result as $row_playlist ) {
				$row_playlist=lbg_zoominoutslider_unstrip_array($row_playlist);
				
				$safe_sql=$wpdb->prepare( "INSERT INTO ".$wpdb->prefix ."lbg_kenburnsslider_playlist ( `bannerid`, `img`, `thumbnail`, `alt_text`, `content`, `data-video`, `data-horizontalPosition`, `data-verticalPosition`, `data-initialZoom`, `data-finalZoom`, `data-duration`, `data-link`, `data-target`, `ord`, `data-autoPlay`, `data-zoomEasing` ) SELECT ".$bannerid.", `img`, `thumbnail`, `alt_text`, `content`, `data-video`, `data-horizontalPosition`, `data-verticalPosition`, `data-initialZoom`, `data-finalZoom`, `data-duration`, `data-link`, `data-target`, `ord`, `data-autoPlay`, `data-zoomEasing` FROM (".$wpdb->prefix ."lbg_kenburnsslider_playlist) WHERE id = %d",$row_playlist['id'] );
				$wpdb->query($safe_sql);	
				$photoid=$wpdb->insert_id;			
				//echo $wpdb->last_query;
				
				//layers/texts
				$safe_sql=$wpdb->prepare( "SELECT * FROM (".$wpdb->prefix ."lbg_kenburnsslider_texts) WHERE photoid = %d",$row_playlist['id'] );
				$result_texts = $wpdb->get_results($safe_sql,ARRAY_A);
				foreach ( $result_texts as $row_texts ) {
					$row_texts=lbg_zoominoutslider_unstrip_array($row_texts);
					
					$safe_sql=$wpdb->prepare( "INSERT INTO ".$wpdb->prefix ."lbg_kenburnsslider_texts (`photoid`, `content`, `data-initial-left`, `data-initial-top`, `data-initial-skew`, `data-initial-scale`, `data-final-left`, `data-final-top`, `data-final-skew`, `data-final-scale`, `data-duration`, `data-fade-start`, `data-delay`, `css`, `img_src`, `element-link`, `element-link-target`, `data-easing`, `data-exit-left`, `data-exit-top`, `data-exit-fade`, `data-exit-duration`, `data-exit-delay`, `data-exit-easing`, `data-exit-off`, `data-exit-skew`, `data-exit-scale`, `data-intermediate-left`, `data-intermediate-top`, `data-intermediate-duration`, `data-intermediate-easing`, `data-intermediate-delay`, `data-intermediate-skew`, `data-intermediate-scale`, `ord`) SELECT ".$photoid.", `content`, `data-initial-left`, `data-initial-top`, `data-initial-skew`, `data-initial-scale`, `data-final-left`, `data-final-top`, `data-final-skew`, `data-final-scale`, `data-duration`, `data-fade-start`, `data-delay`, `css`, `img_src`, `element-link`, `element-link-target`, `data-easing`, `data-exit-left`, `data-exit-top`, `data-exit-fade`, `data-exit-duration`, `data-exit-delay`, `data-exit-easing`, `data-exit-off`, `data-exit-skew`, `data-exit-scale`, `data-intermediate-left`, `data-intermediate-top`, `data-intermediate-duration`, `data-intermediate-easing`, `data-intermediate-delay`, `data-intermediate-skew`, `data-intermediate-scale`, `ord` FROM (".$wpdb->prefix ."lbg_kenburnsslider_texts) WHERE id = %d",$row_texts['id'] );
					$wpdb->query($safe_sql);				
					//echo $wpdb->last_query;	
				}
			}

	}	
	
	$safe_sql="SELECT * FROM (".$wpdb->prefix ."lbg_kenburnsslider_banners) ORDER BY id";
	$result = $wpdb->get_results($safe_sql,ARRAY_A);	
	include_once($lbg_zoominoutslider_path . 'tpl/banners.php');

}


//HTML content for Manage Banners - Add New
function lbg_zoominoutslider_manage_sliders_add_new_page()
{
	global $wpdb;
	global $lbg_zoominoutslider_messages;
	
	if($_POST['Submit'] == 'Add New') {
		$errors_arr=array();
		if (empty($_POST['name']))
			$errors_arr[]=$lbg_zoominoutslider_messages['empty_name'];

		if (count($errors_arr)) { 
				include_once($lbg_zoominoutslider_path . 'tpl/add_banner.php'); ?>
				<div id="error" class="error"><p><?php echo implode("<br>", $errors_arr);?></p></div>
		  	<?php } else { // no errors
					$wpdb->insert( 
						$wpdb->prefix . "lbg_kenburnsslider_banners", 
						array( 
							'name' => $_POST['name']
						), 
						array( 
							'%s'			
						) 
					);	
					//insert default Slider Settings for this new Slider
					lbg_zoominoutslider_insert_settings_record($wpdb->insert_id);
					?>
						<div class="wrap">
							<div id="lbg_logo">
								<h2>Manage Sliders - Add New Slider</h2>
				 			</div>
							<div id="message" class="updated"><p><?php echo $lbg_zoominoutslider_messages['data_saved'];?></p><p><?php echo $lbg_zoominoutslider_messages['generate_for_this_slider'];?></p></div>
							<div>
								<p>&raquo; <a href="?page=lbg_zoominoutslider_Add_New">Add New (Slider)</a></p>
								<p>&raquo; <a href="?page=lbg_zoominoutslider_Manage_Sliders">Back to Manage Sliders</a></p>
							</div>
						</div>	
		  	<?php }			
	} else {
		include_once($lbg_zoominoutslider_path . 'tpl/add_banner.php');
	}

}


//HTML content for bannersettings
function lbg_kenburnsslider_settings_page()
{
	global $wpdb;
	global $lbg_zoominoutslider_messages;
	
	if (isset($_GET['id']) && isset($_GET['name'])) {
		$_SESSION['xid']=$_GET['id'];
		$_SESSION['xname']=$_GET['name'];
	}

	//$wpdb->show_errors();
	/*if (check_admin_referer('lbg_kenburnsslider_settings_update')) {
		echo "update";		
	}*/
	
	
	if($_POST['Submit'] == 'Update Slider Settings') {
		$_GET['xmlf']='';
		$except_arr=array('Submit','name','page_scroll_to_id_instances');

			$wpdb->update( 
				$wpdb->prefix .'lbg_kenburnsslider_banners', 
				array( 
				'name' => $_POST['name']
				), 
				array( 'id' => $_SESSION['xid'] )
			);	
			$_SESSION['xname']=stripslashes($_POST['name']);
						
			
			foreach ($_POST as $key=>$val){
				if (in_array($key,$except_arr)) {
					unset($_POST[$key]);
				}
			}
		
			$wpdb->update( 
				$wpdb->prefix .'lbg_kenburnsslider_settings', 
				$_POST, 
				array( 'id' => $_SESSION['xid'] )
			);
			//echo $wpdb->last_query;
			?>
			<div id="message" class="updated"><p><?php echo $lbg_zoominoutslider_messages['data_saved'];?></p></div>
	<?php 

	}
	

	
	//echo "WP_PLUGIN_URL: ".WP_PLUGIN_URL;
	$safe_sql=$wpdb->prepare( "SELECT * FROM (".$wpdb->prefix ."lbg_kenburnsslider_settings) WHERE id = %d",$_SESSION['xid'] );
	$row = $wpdb->get_row($safe_sql,ARRAY_A);
	$row=lbg_zoominoutslider_unstrip_array($row);
	$_POST = $row; 
	//$_POST['existingWatermarkPath']=$_POST['watermarkPath'];
	$_POST=lbg_zoominoutslider_unstrip_array($_POST);
		
	//echo "width: ".$row['width'];
	include_once($lbg_zoominoutslider_path . 'tpl/settings_form.php');
	
}

function lbg_zoominoutslider_playlist_page()
{
	global $wpdb;
	global $lbg_zoominoutslider_messages;
	//$wpdb->show_errors();
	
	if (isset($_GET['id']) && isset($_GET['name'])) {
		$_SESSION['xid']=$_GET['id'];
		$_SESSION['xname']=$_GET['name'];
	}	

	
	if ($_GET['xmlf']=='add_playlist_record') {
		if($_POST['Submit'] == 'Add Record') {
			$errors_arr=array();
			/*if (empty($_POST['img']))
				 $errors_arr[]=$lbg_zoominoutslider_messages['empty_img'];*/

				 	
		if (count($errors_arr)) {
			include_once($lbg_zoominoutslider_path . 'tpl/add_playlist_record.php'); ?>
			<div id="error" class="error"><p><?php echo implode("<br>", $errors_arr);?></p></div>
	  	<?php } else { // no upload errors
				$max_ord = 1+$wpdb->get_var( $wpdb->prepare( "SELECT max(ord) FROM ". $wpdb->prefix ."lbg_kenburnsslider_playlist WHERE bannerid = %d",$_SESSION['xid'] ) );

				$wpdb->insert( 
					$wpdb->prefix . "lbg_kenburnsslider_playlist", 
					array( 
						'bannerid' => $_POST['bannerid'],
						'img' => $_POST['img'],
						'thumbnail' => $_POST['thumbnail'],
						'alt_text' => $_POST['alt_text'],
						'content' => $_POST['content'],
						'data-video' => $_POST['data-video'],
						'data-link' => $_POST['data-link'],
						'data-target' => $_POST['data-target'],
						'data-horizontalPosition' => $_POST['data-horizontalPosition'],
						'data-verticalPosition' => $_POST['data-verticalPosition'],
						'data-initialZoom' => $_POST['data-initialZoom'],
						'data-finalZoom' => $_POST['data-finalZoom'],
						'data-duration' => $_POST['data-duration'],
						'data-autoPlay' => $_POST['data-autoPlay'],
						'data-zoomEasing' => $_POST['data-zoomEasing'],
						'ord' => $max_ord

					), 
					array( 
						'%d',
						'%s',
						'%s',
						'%s',
						'%s',
						'%s',
						'%s',
						'%s',
						'%s',
						'%s',
						'%f',
						'%f',
						'%d',
						'%d',
						'%s',
						'%d'
					) 
				);	
				
	  			if (isset($_POST['setitfirst'])) {
					$sql_arr=array();
					$ord_start=$max_ord;
					$ord_stop=1;
					$elem_id=$wpdb->insert_id;
					$ord_direction='+1';

					$sql_arr[]="UPDATE ".$wpdb->prefix."lbg_kenburnsslider_playlist SET ord=ord+1  WHERE bannerid = ".$_SESSION['xid']." and ord>=".$ord_stop." and ord<".$ord_start;
					$sql_arr[]="UPDATE ".$wpdb->prefix."lbg_kenburnsslider_playlist SET ord=".$ord_stop." WHERE id=".$elem_id;		
					
					//echo "elem_id: ".$elem_id."----ord_start: ".$ord_start."----ord_stop: ".$ord_stop;
					foreach ($sql_arr as $sql)
						$wpdb->query($sql);				
				}				
				?>
					<div class="wrap">
						<div id="lbg_logo">
							<h2>Playlist for Slider: <span style="color:#FF0000; font-weight:bold;"><?php echo strip_tags($_SESSION['xname'])?> - ID #<?php echo strip_tags($_SESSION['xid'])?></span> - Add New</h2>
			 			</div>
						<div id="message" class="updated"><p><?php echo $lbg_zoominoutslider_messages['data_saved'];?></p></div>
						<div>
							<p>&raquo; <a href="?page=lbg_zoominoutslider_Playlist&xmlf=add_playlist_record">Add New</a></p>
							<p>&raquo; <a href="?page=lbg_zoominoutslider_Playlist">Back to Playlist</a></p>
						</div>
					</div>	
	  	<?php }
		} else {
			include_once($lbg_zoominoutslider_path . 'tpl/add_playlist_record.php');	
		}
		
	} else {
		if ($_GET['duplicate_id']!='') {			
			$max_ord = 1+$wpdb->get_var( $wpdb->prepare( "SELECT max(ord) FROM ". $wpdb->prefix ."lbg_kenburnsslider_playlist WHERE bannerid = %d",$_SESSION['xid'] ) );
			$safe_sql=$wpdb->prepare( "INSERT INTO ".$wpdb->prefix ."lbg_kenburnsslider_playlist ( `bannerid`, `img`, `thumbnail`, `alt_text`, `content`, `data-video`, `data-horizontalPosition`, `data-verticalPosition`, `data-initialZoom`, `data-finalZoom`, `data-duration`, `data-link`, `data-target`, `ord`, `data-autoPlay`, `data-zoomEasing`  ) SELECT `bannerid`, `img`, `thumbnail`, `alt_text`, `content`, `data-video`, `data-horizontalPosition`, `data-verticalPosition`, `data-initialZoom`, `data-finalZoom`, `data-duration`, `data-link`, `data-target`, ".$max_ord.", `data-autoPlay`, `data-zoomEasing` FROM (".$wpdb->prefix ."lbg_kenburnsslider_playlist) WHERE id = %d",$_GET['duplicate_id'] );
			$wpdb->query($safe_sql);			
			$lastID=$wpdb->insert_id;
			//echo $wpdb->last_query;


			$safe_sql=$wpdb->prepare( "SELECT * FROM (".$wpdb->prefix ."lbg_kenburnsslider_texts) WHERE photoid = %d",$_GET['duplicate_id'] );
			$result = $wpdb->get_results($safe_sql,ARRAY_A);
			foreach ( $result as $row_playlist ) {
				$row_playlist=lbg_zoominoutslider_unstrip_array($row_playlist);
				
				$safe_sql=$wpdb->prepare( "INSERT INTO ".$wpdb->prefix ."lbg_kenburnsslider_texts (`photoid`, `content`, `data-initial-left`, `data-initial-top`, `data-initial-skew`, `data-initial-scale`, `data-final-left`, `data-final-top`, `data-final-skew`, `data-final-scale`, `data-duration`, `data-fade-start`, `data-delay`, `css`, `img_src`, `element-link`, `element-link-target`, `data-easing`, `data-exit-left`, `data-exit-top`, `data-exit-fade`, `data-exit-duration`, `data-exit-delay`, `data-exit-easing`, `data-exit-off`, `data-exit-skew`, `data-exit-scale`, `data-intermediate-left`, `data-intermediate-top`, `data-intermediate-duration`, `data-intermediate-easing`, `data-intermediate-delay`, `data-intermediate-skew`, `data-intermediate-scale`, `ord`) SELECT ".$lastID.", `content`, `data-initial-left`, `data-initial-top`, `data-initial-skew`, `data-initial-scale`, `data-final-left`, `data-final-top`, `data-final-skew`, `data-final-scale`, `data-duration`, `data-fade-start`, `data-delay`, `css`, `img_src`, `element-link`, `element-link-target`, `data-easing`, `data-exit-left`, `data-exit-top`, `data-exit-fade`, `data-exit-duration`, `data-exit-delay`, `data-exit-easing`, `data-exit-off`, `data-exit-skew`, `data-exit-scale`, `data-intermediate-left`, `data-intermediate-top`, `data-intermediate-duration`, `data-intermediate-easing`, `data-intermediate-delay`, `data-intermediate-skew`, `data-intermediate-scale`, `ord` FROM (".$wpdb->prefix ."lbg_kenburnsslider_texts) WHERE id = %d",$row_playlist['id'] );
				$wpdb->query($safe_sql);				
				//echo $wpdb->last_query;	
			}
			echo "<script>location.href='?page=lbg_zoominoutslider_Playlist&id=".$_SESSION['xid']."&name=".$_SESSION['xname']."'</script>";
			
		}
		
		$safe_sql=$wpdb->prepare( "SELECT * FROM (".$wpdb->prefix ."lbg_kenburnsslider_playlist) WHERE bannerid = %d ORDER BY ord",$_SESSION['xid'] );
		$result = $wpdb->get_results($safe_sql,ARRAY_A);
		//echo $wpdb->last_query;
		
		/*$safe_sql=$wpdb->prepare( "SELECT width,height FROM (".$wpdb->prefix ."lbg_kenburnsslider_settings) WHERE id = %d",$_SESSION['xid'] );
		$row_settings = $wpdb->get_row($safe_sql);		*/
		
		//$_POST=lbg_zoominoutslider_unstrip_array($_POST);		
		include_once($lbg_zoominoutslider_path . 'tpl/playlist.php');
	}
}



function lbg_zoominoutslider_editElements_page()
{
	global $wpdb;
	//global $lbg_zoominoutslider_messages;
	global $general_param;
	
	if (isset($_GET['id']) && isset($_GET['name'])) {
		$_SESSION['xid']=$_GET['id'];
		$_SESSION['xname']=$_GET['name'];
	} 
	
	if (isset($_GET['deletealllayers']) && ($_POST['Submit'] != 'Save All Changes')) {
		$wpdb->query(
		"
		DELETE FROM ".$wpdb->prefix ."lbg_kenburnsslider_texts
		WHERE photoid = ".$_GET['deletealllayers']."
		"
		);
	}
	
	$safe_sql=$wpdb->prepare( "SELECT * FROM (".$wpdb->prefix ."lbg_kenburnsslider_playlist) WHERE id = %d",$_GET['playlistID'] );
	$row = $wpdb->get_row($safe_sql, ARRAY_A);
	
	$safe_sql=$wpdb->prepare( "SELECT * FROM (".$wpdb->prefix ."lbg_kenburnsslider_texts) WHERE photoid = %d ORDER BY ord",$_GET['playlistID'] );
	$result_text = $wpdb->get_results($safe_sql,ARRAY_A);
	
	//$wpdb->show_errors();
	

	
	
	if($_POST['Submit'] == 'Save All Changes' || $_SESSION['duplicate_layer']!=0) {
		foreach ( $result_text as $row_text ) {
			$textid=$row_text['id'];
			$wpdb->update( 
				$wpdb->prefix .'lbg_kenburnsslider_texts',
				array( 
					'img_src' => $_POST['img_src'.$textid],
					'content' => $_POST['content'.$textid],
					'css' => $_POST['css'.$textid],
					'element-link' => $_POST['element-link'.$textid],
					'element-link-target' => $_POST['element-link-target'.$textid],
					'data-initial-left' => $_POST['data-initial-left'.$textid],
					'data-initial-top' => $_POST['data-initial-top'.$textid],
					'data-initial-scale' => $_POST['data-initial-scale'.$textid],
					'data-initial-skew' => $_POST['data-initial-skew'.$textid],					
					'data-final-left' => $_POST['data-final-left'.$textid],
					'data-final-top' => $_POST['data-final-top'.$textid],
					'data-final-scale' => $_POST['data-final-scale'.$textid],
					'data-final-skew' => $_POST['data-final-skew'.$textid],						
					'data-duration' => $_POST['data-duration'.$textid],
					'data-fade-start' => $_POST['data-fade-start'.$textid],
					'data-delay' => $_POST['data-delay'.$textid],
					'data-easing' => $_POST['data-easing'.$textid],					
					'data-exit-left' => $_POST['data-exit-left'.$textid],
					'data-exit-top' => $_POST['data-exit-top'.$textid],
					'data-exit-fade' => $_POST['data-exit-fade'.$textid],
					'data-exit-scale' => $_POST['data-exit-scale'.$textid],
					'data-exit-skew' => $_POST['data-exit-skew'.$textid],						
					'data-exit-duration' => $_POST['data-exit-duration'.$textid],
					'data-exit-delay' => $_POST['data-exit-delay'.$textid],
					'data-exit-easing' => $_POST['data-exit-easing'.$textid],
					'data-exit-off' => $_POST['data-exit-off'.$textid],
					'data-intermediate-left' => $_POST['data-intermediate-left'.$textid],
					'data-intermediate-top' => $_POST['data-intermediate-top'.$textid],
					'data-intermediate-scale' => $_POST['data-intermediate-scale'.$textid],
					'data-intermediate-skew' => $_POST['data-intermediate-skew'.$textid],					
					'data-intermediate-duration' => $_POST['data-intermediate-duration'.$textid],
					'data-intermediate-delay' => $_POST['data-intermediate-delay'.$textid],					
					'data-intermediate-easing' => $_POST['data-intermediate-easing'.$textid]
					), 
				array( 'id' => $textid ),
				array( 
'%s',
'%s',
'%s',
'%s',
'%s',
'%d',
'%d',
'%s',
'%s',
'%d',
'%d',
'%s',
'%s',
'%f',
'%d',
'%f',
'%s',
'%d',
'%d',
'%f',
'%s',
'%s',
'%f',
'%f',
'%s',
'%s',
'%d',
'%d',
'%s',
'%s',
'%f',
'%f',
'%s'
	), 
				array( '%d' )
			);
		}
			
			?>
	<?php 
	}
	
	if ($_SESSION['duplicate_layer']!=0) {
		$safe_sql=$wpdb->prepare( "SELECT `photoid` , `data-final-left` , `data-final-top` FROM (".$wpdb->prefix ."lbg_kenburnsslider_texts) WHERE id = %d", $_SESSION['duplicate_layer']);
		$row_dupl = $wpdb->get_row($safe_sql,ARRAY_A);

		
		$new_final_left=$row_dupl['data-final-left']+10;
		$new_final_top=$row_dupl['data-final-top']+10;
		$photoid=$row_dupl['photoid'];
		$max_ord=1;
		
		$wpdb->query($wpdb->prepare("UPDATE ".$wpdb->prefix."lbg_kenburnsslider_texts SET ord=ord+1 WHERE photoid = %d",$photoid));
		
		$safe_sql=$wpdb->prepare( "INSERT INTO ".$wpdb->prefix ."lbg_kenburnsslider_texts (`photoid`, `content`, `data-initial-left`, `data-initial-top`, `data-initial-skew`, `data-initial-scale`, `data-final-left`, `data-final-top`, `data-final-skew`, `data-final-scale`, `data-duration`, `data-fade-start`, `data-delay`, `css`, `img_src`, `element-link`, `element-link-target`, `data-easing`, `data-exit-left`, `data-exit-top`, `data-exit-fade`, `data-exit-duration`, `data-exit-delay`, `data-exit-easing`, `data-exit-off`, `data-exit-skew`, `data-exit-scale`, `data-intermediate-left`, `data-intermediate-top`, `data-intermediate-duration`, `data-intermediate-easing`, `data-intermediate-delay`, `data-intermediate-skew`, `data-intermediate-scale`, `ord`) SELECT `photoid`, `content`, `data-initial-left`, `data-initial-top`, `data-initial-skew`, `data-initial-scale`, ".$new_final_left.", ".$new_final_top.", `data-final-skew`, `data-final-scale`, `data-duration`, `data-fade-start`, `data-delay`, `css`, `img_src`, `element-link`, `element-link-target`, `data-easing`, `data-exit-left`, `data-exit-top`, `data-exit-fade`, `data-exit-duration`, `data-exit-delay`, `data-exit-easing`, `data-exit-off`, `data-exit-skew`, `data-exit-scale`, `data-intermediate-left`, `data-intermediate-top`, `data-intermediate-duration`, `data-intermediate-easing`, `data-intermediate-delay`, `data-intermediate-skew`, `data-intermediate-scale`, ".$max_ord." FROM (".$wpdb->prefix ."lbg_kenburnsslider_texts) WHERE id = %d",$_SESSION['duplicate_layer'] );
		$wpdb->query($safe_sql);
		//echo $wpdb->last_query;			
		$lastID=$wpdb->insert_id;
		
		
		/*$safe_sql=$wpdb->prepare( "SELECT `photoid` , `data-final-left` , `data-final-top` FROM (".$wpdb->prefix ."lbg_kenburnsslider_texts) WHERE id = %d", $lastID);
		$row_dupl = $wpdb->get_row($safe_sql,ARRAY_A);

		
		$new_final_left=$row_dupl['data-final-left']+10;
		$new_final_top=$row_dupl['data-final-top']+10;
		$photoid=$row_dupl['photoid'];
		
		$wpdb->query($wpdb->prepare("UPDATE ".$wpdb->prefix."lbg_kenburnsslider_texts SET ord=ord+1 WHERE photoid = %d",$photoid));
		$max_ord=1;
		
		$wpdb->query(
		"UPDATE ".$wpdb->prefix."lbg_kenburnsslider_texts SET `ord`='".$max_ord."',`data-final-left`='".$new_final_left."',`data-final-top`='".$new_final_top."'  WHERE id = ".$lastID
		);*/		
		
		$_SESSION['duplicate_layer']=0;
	}
	
	//get slider settings
	$safe_sql=$wpdb->prepare( "SELECT width,height FROM (".$wpdb->prefix ."lbg_kenburnsslider_settings) WHERE id = %d",$_SESSION['xid'] );
	$row_settings = $wpdb->get_row($safe_sql, ARRAY_A);
	
	//get latest text parameters
	$safe_sql=$wpdb->prepare( "SELECT * FROM (".$wpdb->prefix ."lbg_kenburnsslider_texts) WHERE photoid = %d ORDER BY ord",$_GET['playlistID'] );
	$result_text = $wpdb->get_results($safe_sql,ARRAY_A);
	
	$safe_sql="SELECT css_styles FROM (".$wpdb->prefix ."lbg_kenburnsslider_css_definitions)";
	$row_css = $wpdb->get_row($safe_sql, ARRAY_A);

	include_once($lbg_zoominoutslider_path . 'tpl/playlist_elements_over_image.php');
	
}




function lbg_zoominoutslider_help_page()
{
	//include_once(plugins_url('tpl/help.php', __FILE__));
	include_once($lbg_zoominoutslider_path . 'tpl/help.php');
}

function lbg_zoominoutslider_generate_preview_code($sliderID) {
	global $wpdb;
	
	$safe_sql=$wpdb->prepare( "SELECT * FROM (".$wpdb->prefix ."lbg_kenburnsslider_settings) WHERE id = %d",$sliderID );
	$row = $wpdb->get_row($safe_sql,ARRAY_A);
	$row=lbg_zoominoutslider_unstrip_array($row);
	//echo $wpdb->last_query;

		
	$safe_sql=$wpdb->prepare( "SELECT * FROM (".$wpdb->prefix ."lbg_kenburnsslider_playlist) WHERE bannerid = %d ORDER BY ord",$sliderID );
	$result = $wpdb->get_results($safe_sql,ARRAY_A);
	$playlist_str='';
	$text_str='';
	foreach ( $result as $row_playlist ) {

		$row_playlist=lbg_zoominoutslider_unstrip_array($row_playlist);

		$img_over='';
		if ($row_playlist['img']!='') {
			if (strpos($row_playlist['img'], 'wp-content',9)===false)
				list($width, $height, $type, $attr) = getimagesize($row_playlist['img']);
			else
				list($width, $height, $type, $attr) = getimagesize( ABSPATH.substr($row_playlist['img'],strpos($row_playlist['img'], 'wp-content',9)) );	
			
			$img_over='<img src="'.$row_playlist['img'].'" width="'.$width.'" height="'.$height.'" style="width:'.$width.'px; height:'.$height.'px;" alt="'.$row_playlist['alt_text'].'" class="ken_img" />';
			//$img_over='<img src="'.$row_playlist['img'].'" style="width:'.$width.'px !important;height:'.$height.'px !important;" alt="'.$row_playlist['alt_text'].'" />';			
		}
	
		//get texts
		$safe_sql=$wpdb->prepare( "SELECT * FROM (".$wpdb->prefix ."lbg_kenburnsslider_texts) WHERE photoid = %d ORDER BY ord",$row_playlist['id'] );
		$result_text = $wpdb->get_results($safe_sql,ARRAY_A);
		$text_id='';
		if ($wpdb->num_rows) { // i have texts
			$text_id='#bannerscollection_zoominout_photoText'.$row_playlist['id'];
			
			
			$text_str.='<div id="bannerscollection_zoominout_photoText'.$row_playlist['id'].'" class="bannerscollection_zoominout_texts">';
			foreach ( $result_text as $row_text ) {
				$row_text=lbg_zoominoutslider_unstrip_array($row_text);
				//echo $row_text['id']."; ";
				$zindex_aux=100-$row_text['ord'];
				$content_aux=$row_text['content'];
				$link_start='';
				if ($row_text['img_src']!='') {
					if (strpos($row_text['img_src'], 'wp-content',9)===false)
						list($width, $height, $type, $attr) = getimagesize($row_text['img_src']);
					else
						list($width, $height, $type, $attr) = getimagesize( ABSPATH.substr($row_text['img_src'],strpos($row_text['img_src'], 'wp-content',9)) );			
					$content_aux='<img src="'.$row_text['img_src'].'" width="'.$width.'" height="'.$height.'" style="width:'.$width.'px; height:'.$height.'px; border:0;" alt="'.$row_text['content'].'" />';
				}
				$link_start='';
				$link_end='';
				if ($row_text['element-link']!='') {
					$link_start='<a href="'.$row_text['element-link'].'" target="'.$row_text['element-link-target'].'">';
					$link_end='</a>';
				}
				$exit_param='';
				if ($row_text['data-exit-duration']!=0) {
					$exit_param='  data-exit-left="'.$row_text['data-exit-left'].'" data-exit-top="'.$row_text['data-exit-top'].'" data-exit-fade="'.$row_text['data-exit-fade'].'" data-exit-duration="'.$row_text['data-exit-duration'].'" data-exit-delay="'.$row_text['data-exit-delay'].'" data-exit-easing="'.$row_text['data-exit-easing'].'" data-exit-off="'.$row_text['data-exit-off'].'"';
				}
				$intermediate_move_param='';
				if ($row_text['data-intermediate-duration']!=0) {
					$intermediate_move_param=' data-intermediate-left="'.$row_text['data-intermediate-left'].'" data-intermediate-top="'.$row_text['data-intermediate-top'].'" data-intermediate-skew="'.$row_text['data-intermediate-skew'].'" data-intermediate-scale="'.$row_text['data-intermediate-scale'].'" data-intermediate-duration="'.$row_text['data-intermediate-duration'].'" data-intermediate-delay="'.$row_text['data-intermediate-delay'].'" data-intermediate-easing="'.$row_text['data-intermediate-easing'].'"';
				}
				
				$text_str.='<div class="bannerscollection_zoominout_text_line '.$row_text['css'].'" data-initial-left="'.$row_text['data-initial-left'].'" data-initial-top="'.$row_text['data-initial-top'].'" data-initial-skew="'.$row_text['data-initial-skew'].'" data-initial-scale="'.$row_text['data-initial-scale'].'" data-final-left="'.$row_text['data-final-left'].'" data-final-top="'.$row_text['data-final-top'].'" data-final-skew="'.$row_text['data-final-skew'].'" data-final-scale="'.$row_text['data-final-scale'].'" data-duration="'.$row_text['data-duration'].'" data-fade-start="'.$row_text['data-fade-start'].'" data-delay="'.$row_text['data-delay'].'" data-easing="'.$row_text['data-easing'].'" '.$exit_param.' '.$intermediate_move_param.' style="z-index:'.$zindex_aux.';">'.$link_start.$content_aux.$link_end.'</div>';
			}
			$text_str.='</div>';
		} /*else { // no data-text-id, only image
			$playlist_str.='<li data-text-id="" data-video="'.$row_playlist['data-video'].'" data-bottom-thumb="'.$row_playlist['thumbnail'].'" data-link="'.$row_playlist['data-link'].'" data-target="'.$row_playlist['data-target'].'" >'.$img_over.$row_playlist['content'].'</li>';
			
		}*/

		$data_initialZoom='';
		if ($row_playlist['data-initialZoom']!=0) {
			$data_initialZoom=$row_playlist['data-initialZoom'];
		}	
		$data_finalZoom='';
		if ($row_playlist['data-finalZoom']!=0) {
			$data_finalZoom=$row_playlist['data-finalZoom'];
		}	
		$data_duration='';
		if ($row_playlist['data-duration']!=0) {
			$data_duration=$row_playlist['data-duration'];
		}
		$data_autoPlay='';
		if ($row_playlist['data-autoPlay']!=0) {
			$data_autoPlay='data-autoPlay="'.$row_playlist['data-autoPlay'].'"';
		}	
	
			
		$playlist_str.='<li data-text-id="'.$text_id.'" data-video="'.$row_playlist['data-video'].'" data-bottom-thumb="'.$row_playlist['thumbnail'].'" data-link="'.$row_playlist['data-link'].'" data-target="'.$row_playlist['data-target'].'" '.$data_autoPlay.' data-horizontalPosition="'.$row_playlist['data-horizontalPosition'].'" data-verticalPosition="'.$row_playlist['data-verticalPosition'].'" data-initialZoom="'.$data_initialZoom.'" data-finalZoom="'.$data_finalZoom.'" data-duration="'.$data_duration.'" data-zoomEasing="'.$row_playlist['data-zoomEasing'].'" >'.$img_over.$row_playlist['content'].'</li>';
	}
	
	
	
	
	return '<script>
		jQuery(function() {
			jQuery("#bannerscollection_zoominout_'.$row["id"].'").bannerscollection_zoominout({
				skin:"'.$row["skin"].'",
				width:'.$row["width"].',
				height:'.$row["height"].',
				width100Proc:'.$row["width100Proc"].',
				height100Proc:'.$row["height100Proc"].',
				responsive:'.$row["responsive"].',
				responsiveRelativeToBrowser:'.((is_admin())?'false':$row["responsiveRelativeToBrowser"]).',				
				setAsBg:'.((is_admin())?'false':$row["setAsBg"]).',			
				autoPlay:'.$row["autoPlay"].',
				loop:'.$row["loop"].',
				fadeSlides:'.$row["fadeSlides"].',
				horizontalPosition:"'.$row["horizontalPosition"].'",
				verticalPosition:"'.$row["verticalPosition"].'",
				initialZoom:'.$row["initialZoom"].',
				finalZoom:'.$row["finalZoom"].',
				duration:'.$row["duration"].',
				durationIEfix:'.$row["durationIEfix"].',
				zoomEasing:"'.$row["zoomEasing"].'",
				target:"'.$row["target"].'",
				pauseOnMouseOver:'.$row["pauseOnMouseOver"].',
				showAllControllers:'.$row["showAllControllers"].',
				showNavArrows:'.$row["showNavArrows"].',
				showOnInitNavArrows:'.$row["showOnInitNavArrows"].',
				autoHideNavArrows:'.$row["autoHideNavArrows"].',
				showBottomNav:'.$row["showBottomNav"].',
				showOnInitBottomNav:'.$row["showOnInitBottomNav"].',
				autoHideBottomNav:'.$row["autoHideBottomNav"].',
				showPreviewThumbs:'.$row["showPreviewThumbs"].',
				enableTouchScreen:'.$row["enableTouchScreen"].',
				absUrl:"'.plugins_url("", __FILE__).'/zoominoutslider/",
				scrollSlideDuration:'.$row["scrollSlideDuration"].',
				scrollSlideEasing:"'.$row["scrollSlideEasing"].'",
				defaultEasing:"'.$row["defaultEasing"].'",
				myloaderTime:'.$row["myloaderTime"].',				
				showCircleTimer:'.$row["showCircleTimer"].',
				circleRadius:'.$row["circleRadius"].',
				circleLineWidth:'.$row["circleLineWidth"].',
				circleColor:"#'.$row["circleColor"].'",
				circleAlpha:'.$row["circleAlpha"].',
				behindCircleColor:"#'.$row["behindCircleColor"].'",
				behindCircleAlpha:'.$row["behindCircleAlpha"].',
				numberOfThumbsPerScreen:'.$row["numberOfThumbsPerScreen"].',
				thumbsWrapperMarginTop:'.$row["thumbsWrapperMarginTop"].',
				thumbsOnMarginTop:'.$row["thumbsOnMarginTop"].',
				defaultExitLeft:'.$row["defaultExitLeft"].',
				defaultExitTop:'.$row["defaultExitTop"].',
				defaultExitFade:'.$row["defaultExitFade"].',
				defaultExitDuration:'.$row["defaultExitDuration"].',
				defaultExitDelay:'.$row["defaultExitDelay"].',
				defaultExitEasing:"'.$row["defaultExitEasing"].'",
				defaultExitOFF:'.$row["defaultExitOFF"].'
			});	
		});
	</script>	
            '.((is_admin())?'<div style="width:99%; height:580px;">':'').'<div id="bannerscollection_zoominout_'.$row["id"].'"><div class="myloader"></div><ul class="bannerscollection_zoominout_list">'.$playlist_str.'</ul>'.$text_str.'</div>'.((is_admin())?'</div>':'');
}


function lbg_zoominoutslider_shortcode($atts, $content=null) {
	global $wpdb;
	
	shortcode_atts( array('settings_id'=>''), $atts);
	if ($atts['settings_id']=='')
		$atts['settings_id']=1;

	return lbg_zoominoutslider_generate_preview_code($atts['settings_id']);	

}



register_activation_hook(__FILE__,"lbg_zoominoutslider_activate"); //activate plugin and create the database
register_uninstall_hook(__FILE__, 'lbg_zoominoutslider_uninstall'); // on unistall delete all databases 
add_action('init', 'lbg_zoominoutslider_init_sessions');	// initialize sessions
add_action('init', 'lbg_zoominoutslider_load_styles');	// loads required styles
add_action('init', 'lbg_zoominoutslider_load_scripts');			// loads required scripts  
add_action('admin_menu', 'lbg_zoominoutslider_plugin_menu'); // create menus
add_shortcode('lbg_zoominoutslider', 'lbg_zoominoutslider_shortcode');				// LBG-ZOOMINOUT shortcode 






/** OTHER FUNCTIONS **/

//stripslashes for an entire array
function lbg_zoominoutslider_unstrip_array($array){
	if (is_array($array)) {	
		foreach($array as &$val){
			if(is_array($val)){
				$val = unstrip_array($val);
			} else {
				$val = stripslashes($val);
				
			}
		}
	}
	return $array;
}


function lbg_zoominoutslider_getCssStyles($currentCssClass,$id,$div_data){
	global $wpdb;
	
	$safe_sql="SELECT * FROM (".$wpdb->prefix ."lbg_kenburnsslider_css_definitions) LIMIT 0, 1";
	$row = $wpdb->get_row($safe_sql,ARRAY_A);
	$temp_class='';
	$classes_arr=array();
	$startPos=0;
	$endPos=0;
	$endClassContentPos=0;
	//echo $row['css_styles'];
	
	
	$startPos=strpos($row['css_styles'],'.',$startPos);
	$endPos=strpos($row['css_styles'],'{',$startPos);
	while ($startPos!==FALSE) {
		$temp_class=trim(substr($row['css_styles'],$startPos+1,$endPos-1-$startPos));
		//$classes_arr[]=trim(substr($row['css_styles'],$startPos+1,$endPos-1-$startPos));
		if (strpos($temp_class,' a')===FALSE)
			$classes_arr[]=$temp_class;
		$endClassContentPos=strpos($row['css_styles'],'}',$endPos);
		$startPos=strpos($row['css_styles'],'.',$endClassContentPos);
		$endPos=strpos($row['css_styles'],'{',$startPos);
	}
	//print_r($classes_arr);
	
	//echo $startPos.'  ---  '.$endPos ;
	
	$div_data_end="";
	if ($div_data!='')
		$div_data_end="';";
	
	?>
    <?php echo $div_data;?><select name="css<?php echo $id;?>" id="css<?php echo $id;?>"  onchange="change_text_div_css_class(<?php echo $id;?>,this)"><?php echo $div_data_end;?>
    <?php 
		foreach ($classes_arr as $class_elem) {
	?>
              <?php echo $div_data;?><option value="<?php echo $class_elem;?>" <?php echo (($class_elem==$currentCssClass)?'selected="selected"':'')?>><?php echo $class_elem;?></option><?php echo $div_data_end;?>
    <?php 
		}
	?>              
            <?php echo $div_data;?></select><?php echo $div_data_end;?>
<?php }  




/* ajax update playlist record */

add_action('admin_head', 'lbg_zoominoutslider_update_playlist_record_javascript');
//add_action('in_admin_footer', 'lbg_zoominoutslider_update_playlist_record_javascript');

function lbg_zoominoutslider_update_playlist_record_javascript() {
	global $wpdb;
	global $general_param;
	//Set Your Nonce
	$lbg_zoominoutslider_update_playlist_record_ajax_nonce = wp_create_nonce("lbg_zoominoutslider_update_playlist_record-special-string");
	$lbg_zoominoutslider_add_text_record_ajax_nonce = wp_create_nonce("lbg_zoominoutslider_add_text_record-special-string");
	$lbg_zoominoutslider_delete_text_record_ajax_nonce = wp_create_nonce("lbg_zoominoutslider_delete_text_record-special-string");
	$lbg_zoominoutslider_edit_css_classes_record_ajax_nonce = wp_create_nonce("lbg_zoominoutslider_edit_css_classes_record-special-string");
	$lbg_zoominoutslider_preview_record_ajax_nonce = wp_create_nonce("lbg_zoominoutslider_preview_record-special-string");
	$lbg_zoominoutslider_duplicate_record_ajax_nonce = wp_create_nonce("lbg_zoominoutslider_duplicate_record-special-string");
	
	if(strpos($_SERVER['PHP_SELF'], 'wp-admin') !== false) {
		$page = (isset($_GET['page'])) ? $_GET['page'] : '';
		if(preg_match('/lbg_zoominoutslider/i', $page)) {
?>




<script type="text/javascript" >

//delete the entire record
function lbg_zoominoutslider_delete_entire_record (delete_id) {
	if (confirm('Are you sure?')) {
		jQuery("#lbg_zoominoutslider_sortable").sortable('disable');
		jQuery("#"+delete_id).css("display","none");
		//jQuery("#lbg_zoominoutslider_sortable").sortable('refresh');
		jQuery("#lbg_zoominoutslider_updating_witness").css("display","block");
		var data = "action=lbg_zoominoutslider_update_playlist_record&security=<?php echo $lbg_zoominoutslider_update_playlist_record_ajax_nonce; ?>&updateType=lbg_zoominoutslider_delete_entire_record&delete_id="+delete_id;
		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(ajaxurl, data, function(response) {
			jQuery("#lbg_zoominoutslider_sortable").sortable('enable');
			jQuery("#lbg_zoominoutslider_updating_witness").css("display","none");
			//alert('Got this from the server: ' + response);
		});		
	}
}


function lbg_zoominoutslider_add_text_line_CONTENT(textID,maxORD,photoid,img_src) {

		newZindex=100-maxORD;
		
		if (img_src=='') {
			content_aux='TEXT HERE '+(jQuery('#lbg_zoominoutslider_layers_sortable').children().size()+1);
			content_title='HTML Content';
			image_path='';
			draggable_aux=content_aux;
			css_aux="lbg_zoominoutslider_textWhiteBgBlack_medium";
			img_src_hidden="hidden";
			changeImgBtn_css="none";
		} else {
			content_aux='IMAGE '+(jQuery('#lbg_zoominoutslider_layers_sortable').children().size()+1);
			content_title='Image Title';
			image_path='Image Path';
			draggable_aux='<img src="'+img_src+'">';
			css_aux="lbg_zoominoutslider_textWhiteBgTransparent_small";
			img_src_hidden="text";
			changeImgBtn_css="block";
		}
		
		//jQuery("#lbg_zoominoutsliderTable"+textID).css('display','none');
		jQuery("#no_layers").css('display','none');
		jQuery("#photo_div"+photoid).append('<div id="draggable'+textID+'" rel="'+textID+'" class="my_draggable '+css_aux+'" style="left:0px;top:0px;z-index:'+newZindex+';" onclick="activate_layer('+textID+');">'+draggable_aux+'</div>');
		
		jQuery("#lbg_zoominoutslider_layers_sortable").prepend('<li class="ui-state-default cursor_move" id="'+textID+'" data-photoid="'+photoid+'" onclick="activate_layer('+textID+');"><div id="li_div'+textID+'">'+content_aux+'</div> <input name="ord_input_'+textID+'" type="text" disabled="disabled" id="ord_input_'+textID+'" style="float:right; margin-top:-20px;" value="'+maxORD+'" size="3" readonly="readonly" /></li>');
		activate_layers_order_li(textID);
		
		//activate delete layer button
		jQuery('input[name=currentTextID]').val(textID);
		jQuery('#deletelayer').removeAttr("disabled");
		jQuery('#duplicatelayer').removeAttr("disabled");
		
		jQuery("#draggable"+textID).draggable( {
			drag: function(event, ui) { 
				jQuery("#data-final-left"+textID).val(lbg_zoominoutslider_process_val(jQuery(this).css('left'),'left'));
				jQuery("#data-final-top"+textID).val(lbg_zoominoutslider_process_val(jQuery(this).css('top'),'top'));
			}			
		});
		
		jQuery("[id^='lbg_zoominoutsliderTable']").css({
			'display':'none'
		});
		
		
		jQuery('[id^="draggable"]').removeClass("my_draggable_activated");
		jQuery("#draggable"+textID).addClass("my_draggable_activated");	
		

		var div_data='<div id="lbg_zoominoutsliderTable'+textID+'">';
		//div_data+='<input name="img_src'+textID+'" id="img_src'+textID+'" type="hidden" value="'+img_src+'" />';
		div_data+='<table width="100%" cellspacing="0" class="widefat">';
		div_data+='<tr>';
		div_data+='<td width="15%" align="left" valign="middle" class="row-title">'+content_title+'</td>';
		div_data+='<td colspan="3" align="left" valign="middle"><textarea name="content'+textID+'" cols="80" rows="5" id="content'+textID+'" onkeyup="change_text_div_content('+textID+',this);">'+content_aux+'</textarea></td>';
		div_data+='</tr>';
		div_data+='<tr>';
		div_data+='<td align="left" valign="middle" class="row-title">'+image_path+'</td>';
		div_data+='<td colspan="3" align="left" valign="middle"><input name="img_src'+textID+'" id="img_src'+textID+'" value="'+img_src+'" size="60" type="'+img_src_hidden+'" /> <input name="changeImage'+textID+'" id="changeImage'+textID+'" type="button" class="button-primary" value="Change Image" style="float:right; display:'+changeImgBtn_css+';" onClick="change_layer_image()"></td>';
		div_data+='</tr>';
		div_data+='<tr>';
		div_data+='<td align="left" valign="middle" class="row-title">CSS Style</td>';
		div_data+='<td colspan="3" align="left" valign="middle"><div id="lbg_zoominoutsliderCSS_div'+textID+'">';
		<?php lbg_zoominoutslider_getCssStyles('lbg_zoominoutslider_textWhiteBgBlack_medium','unique_xyz','div_data+=\'');?>
		div_data+='</div> <input name="EditCssClasses'+textID+'" id="EditCssClasses'+textID+'" type="button" class="button-primary" value="Edit CSS Clases" style="float:right; margin-top:-25px;" onclick="lbg_zoominoutslider_edit_css_classes(\'open\',\'\',\'\')"></td>';
		div_data+='</tr>';
		div_data+='<tr>';
		div_data+='<td align="left" valign="middle" class="row-title">Element Link</td>';
		div_data+='<td colspan="3" align="left" valign="middle"><input name="element-link'+textID+'" type="text" id="element-link'+textID+'" value="" size="80" /></td>';
		div_data+='</tr>';
		div_data+='<tr>';
		div_data+='<td align="left" valign="middle" class="row-title">Element Link Target</td>';
		div_data+='<td colspan="3" align="left" valign="middle"><select name="element-link-target'+textID+'" id="element-link-target'+textID+'">';
        div_data+='<option value="_self">_self</option>';
        div_data+='<option value="_blank">_blank</option>';
        div_data+='</select></td>';
		div_data+='</tr>';
		div_data+='<tr>';
		div_data+='<td align="left" valign="middle">&nbsp;</td>';
		div_data+='<td width="35%" align="left" valign="middle">&nbsp;</td>';
		div_data+='<td width="15%" align="left" valign="middle">&nbsp;</td>';
		div_data+='<td width="35%" align="left" valign="middle">&nbsp;</td>';
		div_data+='</tr>';
		div_data+='<tr>';
		div_data+='<td colspan="4" align="left" valign="middle" class="lbg_regGrayWithBg">Enter Values</td>';
		div_data+='</tr>';
		div_data+='<tr>';
		div_data+='<td align="left" valign="middle" class="row-title">Initial Left</td>';
		div_data+='<td align="left" valign="middle"><input name="data-initial-left'+textID+'" type="text" id="data-initial-left'+textID+'" size="10" value="0" /> px</td>';
		div_data+='<td align="left" valign="middle" class="row-title">Inital Top</td>';
		div_data+='<td align="left" valign="middle"><input name="data-initial-top'+textID+'" type="text" id="data-initial-top'+textID+'" size="10" value="0" /> px</td>';
		div_data+='</tr>';
		div_data+='<tr>';
		div_data+='<td align="left" valign="middle" class="row-title">Final Left</td>';
		div_data+='<td align="left" valign="middle"><input name="data-final-left'+textID+'" type="text" id="data-final-left'+textID+'" size="10" value="0" /> px</td>';
		div_data+='<td align="left" valign="middle" class="row-title">Final Top</td>';
		div_data+='<td align="left" valign="middle"><input name="data-final-top'+textID+'" type="text" id="data-final-top'+textID+'" size="10" value="0" /> px</td>';
		div_data+='</tr>';
		div_data+='<tr>';
		div_data+='<td align="left" valign="middle" class="row-title">Intial Scale</td>';
		div_data+='<td align="left" valign="middle"><input name="data-initial-scale'+textID+'" type="text" id="data-initial-scale'+textID+'" size="20" value="1" /> (0-100)</td>';
		div_data+='<td align="left" valign="middle" class="row-title">Final Scale</td>';
		div_data+='<td align="left" valign="middle"><input name="data-final-scale'+textID+'" type="text" id="data-final-scale'+textID+'" size="20" value="1" /> (0-100)</td>';
		div_data+='</tr>';	
		div_data+='<tr>';
		div_data+='<td align="left" valign="middle" class="row-title">Intial Skew</td>';
		div_data+='<td align="left" valign="middle"><input name="data-initial-skew'+textID+'" type="text" id="data-initial-skew'+textID+'" size="20" value="0deg,0deg" /> <br />(Ex: 50deg,0deg)</td>';
		div_data+='<td align="left" valign="middle" class="row-title">Final Skew</td>';
		div_data+='<td align="left" valign="middle"><input name="data-final-skew'+textID+'" type="text" id="data-final-skew'+textID+'" size="20" value="0deg,0deg" /> <br />(Ex: 0deg,0deg)</td>';
		div_data+='</tr>';				
		div_data+='<tr>';
		div_data+='<td align="left" valign="middle" class="row-title">Duration</td>';
		div_data+='<td align="left" valign="middle"><input name="data-duration'+textID+'" type="text" id="data-duration'+textID+'" size="10" value="0" /> seconds</td>';
		div_data+='<td align="left" valign="middle" class="row-title">Delay</td>';
		div_data+='<td align="left" valign="middle"><input name="data-delay'+textID+'" type="text" id="data-delay'+textID+'" size="10" value="0" /> seconds</td>';
		div_data+='</tr>';
		div_data+='<tr>';
		div_data+='<td align="left" valign="middle" class="row-title">Initial Fade</td>';
		div_data+='<td align="left" valign="middle"><input name="data-fade-start'+textID+'" type="text" id="data-fade-start'+textID+'" size="10" value="0" /> (0-100)</td>';
		div_data+='<td align="left" valign="middle" class="row-title">Easing</td>';
		div_data+='<td align="left" valign="middle"><select name="data-easing'+textID+'" id="data-easing'+textID+'">';
        div_data+='<option value="swing">swing</option>';
        div_data+='<option value="linear">linear</option>';
        div_data+='<option value="ease">ease</option>';
        div_data+='<option value="ease-in">ease-in</option>';
        div_data+='<option value="ease-out">ease-out</option>';
        div_data+='<option value="ease-in-out">ease-in-out</option>';
		div_data+='<option value="easeInQuad">easeInQuad</option>';
		div_data+='<option value="easeOutQuad">easeOutQuad</option>';
		div_data+='<option value="easeInOutQuad">easeInOutQuad</option>';
		div_data+='<option value="easeInCubic">easeInCubic</option>';
		div_data+='<option value="easeOutCubic">easeOutCubic</option>';
		div_data+='<option value="easeInOutCubic">easeInOutCubic</option>';
		div_data+='<option value="easeInQuart">easeInQuart</option>';
		div_data+='<option value="easeOutQuart">easeOutQuart</option>';
		div_data+='<option value="easeInOutQuart">easeInOutQuart</option>';
		div_data+='<option value="easeInSine">easeInSine</option>   ';
		div_data+='<option value="easeOutSine">easeOutSine</option>';
		div_data+='<option value="easeInOutSine">easeInOutSine</option>';
		div_data+='<option value="easeInExpo">easeInExpo</option>';
		div_data+='<option value="easeOutExpo">easeOutExpo</option>';
		div_data+='<option value="easeInOutExpo">easeInOutExpo</option>';
		div_data+='<option value="easeInQuint">easeInQuint</option>';
		div_data+='<option value="easeOutQuint">easeOutQuint</option>';
		div_data+='<option value="easeInOutQuint">easeInOutQuint</option>';
		div_data+='<option value="easeInCirc">easeInCirc</option>';
		div_data+='<option value="easeOutCirc">easeOutCirc</option>';
		div_data+='<option value="easeInOutCirc">easeInOutCirc</option>';
		div_data+='<option value="easeInBack">easeInBack</option>';
		div_data+='<option value="easeOutBack">easeOutBack</option>';
		div_data+='<option value="easeInOutBack">easeInOutBack</option>';
		div_data+='</select></td>';
		div_data+='</tr>';
		div_data+='<tr>';
		div_data+='<td colspan="4" align="left" valign="middle" class="lbg_regGrayWithBg">Exit Values</td>';
		div_data+='</tr>';
		div_data+='<tr>';
		div_data+='<td align="left" valign="middle" class="row-title">Exit Left</td>';
		div_data+='<td align="left" valign="middle"><input name="data-exit-left'+textID+'" type="text" id="data-exit-left'+textID+'" size="10" value="0" /> px</td>';
		div_data+='<td align="left" valign="middle" class="row-title">Exit Top</td>';
		div_data+='<td align="left" valign="middle"><input name="data-exit-top'+textID+'" type="text" id="data-exit-top'+textID+'" size="10" value="0" /> px</td>';
		div_data+='</tr>';
		div_data+='<tr>';
		div_data+='<td align="left" valign="middle" class="row-title">Exit Scale</td>';
		div_data+='<td align="left" valign="middle"><input name="data-exit-scale'+textID+'" type="text" id="data-exit-scale'+textID+'" size="20" value="1" /> (0-100)</td>';
		div_data+='<td align="left" valign="middle" class="row-title">Exit Skew</td>';
		div_data+='<td align="left" valign="middle"><input name="data-exit-skew'+textID+'" type="text" id="data-exit-skew'+textID+'" size="20" value="0deg,0deg" /> <br />(Ex: 50deg,0deg)</td>';
		div_data+='</tr>';			
		div_data+='<tr>';
		div_data+='<td align="left" valign="middle" class="row-title">Exit Duration</td>';
		div_data+='<td align="left" valign="middle"><input name="data-exit-duration'+textID+'" type="text" id="data-exit-duration'+textID+'" size="10" value="0" /> seconds</td>';
		div_data+='<td align="left" valign="middle" class="row-title">Exit Delay</td>';
		div_data+='<td align="left" valign="middle"><input name="data-exit-delay'+textID+'" type="text" id="data-exit-delay'+textID+'" size="10" value="0" /> seconds</td>';
		div_data+='</tr>';	
		div_data+='<tr>';
		div_data+='<td align="left" valign="middle" class="row-title">Exit Fade</td>';
		div_data+='<td align="left" valign="middle"><input name="data-exit-fade'+textID+'" type="text" id="data-exit-fade'+textID+'" size="20" value="1" /> (0-1)</td>';
		div_data+='<td align="left" valign="middle"><span class="row-title">Exit Easing</span></td>';
		div_data+='<td align="left" valign="middle"><select name="data-exit-easing'+textID+'" id="data-exit-easing'+textID+'">';
        div_data+='<option value="swing">swing</option>';
        div_data+='<option value="linear">linear</option>';
        div_data+='<option value="ease">ease</option>';
        div_data+='<option value="ease-in">ease-in</option>';
        div_data+='<option value="ease-out">ease-out</option>';
        div_data+='<option value="ease-in-out">ease-in-out</option>';		
		div_data+='<option value="easeInQuad">easeInQuad</option>';
		div_data+='<option value="easeOutQuad">easeOutQuad</option>';
		div_data+='<option value="easeInOutQuad">easeInOutQuad</option>';
		div_data+='<option value="easeInCubic">easeInCubic</option>';
		div_data+='<option value="easeOutCubic">easeOutCubic</option>';
		div_data+='<option value="easeInOutCubic">easeInOutCubic</option>';
		div_data+='<option value="easeInQuart">easeInQuart</option>';
		div_data+='<option value="easeOutQuart">easeOutQuart</option>';
		div_data+='<option value="easeInOutQuart">easeInOutQuart</option>';
		div_data+='<option value="easeInSine">easeInSine</option>   ';
		div_data+='<option value="easeOutSine">easeOutSine</option>';
		div_data+='<option value="easeInOutSine">easeInOutSine</option>';
		div_data+='<option value="easeInExpo">easeInExpo</option>';
		div_data+='<option value="easeOutExpo">easeOutExpo</option>';
		div_data+='<option value="easeInOutExpo">easeInOutExpo</option>';
		div_data+='<option value="easeInQuint">easeInQuint</option>';
		div_data+='<option value="easeOutQuint">easeOutQuint</option>';
		div_data+='<option value="easeInOutQuint">easeInOutQuint</option>';
		div_data+='<option value="easeInCirc">easeInCirc</option>';
		div_data+='<option value="easeOutCirc">easeOutCirc</option>';
		div_data+='<option value="easeInOutCirc">easeInOutCirc</option>';
		div_data+='<option value="easeInBack">easeInBack</option>';
		div_data+='<option value="easeOutBack">easeOutBack</option>';
		div_data+='<option value="easeInOutBack">>easeInOutBack</option>';

		div_data+='</select></td>';
		div_data+='</tr>';
		div_data+='<tr>';
		div_data+='<td align="left" valign="middle" class="row-title">Cancel Exit</td>';
		div_data+='<td align="left" valign="middle"><select name="data-exit-off'+textID+'" id="data-exit-off'+textID+'">';
		div_data+='<option value="true">true</option>';
		div_data+='<option value="false" selected>false</option>';
		div_data+='</select></td>';
		div_data+='<td align="left" valign="middle">&nbsp;</td>';
		div_data+='<td align="left" valign="middle">&nbsp;</td>';
		div_data+='</tr>';		
		div_data+='<tr>';
		div_data+='<td align="left" valign="middle">&nbsp;</td>';
		div_data+='<td align="left" valign="middle">&nbsp;</td>';
		div_data+='<td align="left" valign="middle">&nbsp;</td>';
		div_data+='<td align="left" valign="middle">&nbsp;</td>';
		div_data+='</tr>';
		div_data+='<tr>';
		div_data+='<td colspan="4" align="left" valign="middle" class="lbg_regGrayWithBg">Intermediate Move Parameters</td>';
		div_data+='</tr>';
		div_data+='<tr>';
		div_data+='<td align="left" valign="middle" class="row-title">Intermediate Left</td>';
		div_data+='<td align="left" valign="middle"><input name="data-intermediate-left'+textID+'" type="text" id="data-intermediate-left'+textID+'" size="10" value="0" /> px</td>';
		div_data+='<td align="left" valign="middle" class="row-title">Intermediate Top</td>';
		div_data+='<td align="left" valign="middle"><input name="data-intermediate-top'+textID+'" type="text" id="data-intermediate-top'+textID+'" size="10" value="0" /> px</td>';
		div_data+='</tr>';
		div_data+='<tr>';
		div_data+='<td align="left" valign="middle" class="row-title">Intermediate Scale</td>';
		div_data+='<td align="left" valign="middle"><input name="data-intermediate-scale'+textID+'" type="text" id="data-intermediate-scale'+textID+'" size="20" value="1" /> (0-100)</td>';
		div_data+='<td align="left" valign="middle" class="row-title">Intermediate Skew</td>';
		div_data+='<td align="left" valign="middle"><input name="data-intermediate-skew'+textID+'" type="text" id="data-intermediate-skew'+textID+'" size="20" value="0deg,0deg" /> <br />(Ex: 50deg,0deg)</td>';
		div_data+='</tr>';		
		div_data+='<tr>';
		div_data+='<td align="left" valign="middle" class="row-title">Intermediate Duration</td>';
		div_data+='<td align="left" valign="middle"><input name="data-intermediate-duration'+textID+'" type="text" id="data-intermediate-duration'+textID+'" size="10" value="0" /> seconds<br /><br />(if value is 0, \'Intermediate Move Parameters\' will be ignored)</td>';
		div_data+='<td align="left" valign="middle" class="row-title">Intermediate Easing</td>';
		div_data+='<td align="left" valign="middle"><select name="data-intermediate-easing'+textID+'" id="data-intermediate-easing'+textID+'">';
        div_data+='<option value="swing">swing</option>';
        div_data+='<option value="linear">linear</option>';
        div_data+='<option value="ease">ease</option>';
        div_data+='<option value="ease-in">ease-in</option>';
        div_data+='<option value="ease-out">ease-out</option>';
        div_data+='<option value="ease-in-out">ease-in-out</option>';
		div_data+='<option value="easeInQuad">easeInQuad</option>';
		div_data+='<option value="easeOutQuad">easeOutQuad</option>';
 		div_data+='<option value="easeInOutQuad">easeInOutQuad</option>';
		div_data+='<option value="easeInCubic">easeInCubic</option>';
		div_data+='<option value="easeOutCubic">easeOutCubic</option>';
	    div_data+='<option value="easeInOutCubic">easeInOutCubic</option>';              
	    div_data+='<option value="easeInQuart">easeInQuart</option>';
	    div_data+='<option value="easeOutQuart">easeOutQuart</option>';
	    div_data+='<option value="easeInOutQuart">easeInOutQuart</option>';
	    div_data+='<option value="easeInSine">easeInSine</option>';   
	    div_data+='<option value="easeOutSine">easeOutSine</option>';
	    div_data+='<option value="easeInOutSine">easeInOutSine</option>';
	    div_data+='<option value="easeInExpo">easeInExpo</option>';
	    div_data+='<option value="easeOutExpo">easeOutExpo</option>';
	    div_data+='<option value="easeInOutExpo">easeInOutExpo</option>';
	    div_data+='<option value="easeInQuint">easeInQuint</option>';
	    div_data+='<option value="easeOutQuint">easeOutQuint</option>';
	    div_data+='<option value="easeInOutQuint">easeInOutQuint</option>';
	    div_data+='<option value="easeInCirc">easeInCirc</option>';
	    div_data+='<option value="easeOutCirc">easeOutCirc</option>';
	    div_data+='<option value="easeInOutCirc">easeInOutCirc</option>';
	    div_data+='<option value="easeInBack">easeInBack</option>';
	    div_data+='<option value="easeOutBack">easeOutBack</option>';
	    div_data+='<option value="easeInOutBack">easeInOutBack</option>';
	    div_data+='</select></td>';
	    div_data+='</tr>';           
	    div_data+='<tr>';
		div_data+='<td align="left" valign="middle" class="row-title">Intermediate Delay</td>';
		div_data+='<td align="left" valign="middle"><input name="data-intermediate-delay'+textID+'" type="text" id="data-intermediate-delay'+textID+'" size="10" value="0" /> seconds</td>';
	    div_data+='<td align="left" valign="middle" class="row-title">&nbsp;</td>';
	    div_data+='<td align="left" valign="middle">&nbsp;</td>';
	    div_data+='</tr>'; 

		
		div_data+='</table>';
		div_data+='</div>';
		
		jQuery("#all_texts_settings").append(div_data.replace(/unique_xyz/g,textID));
		
		jQuery("#css"+textID).val(css_aux);
		

		var lis = jQuery('#lbg_zoominoutslider_layers_sortable').children();
		
		var i=0;
		lis.each(function() {
			i++;
		   currentLi = jQuery(this);
		   //new order
		   jQuery('input[name=ord_input_'+currentLi.attr('id')+']').val(i);
		   
		   //new z-index
			jQuery('#draggable'+currentLi.attr('id')).css({
				'zIndex':100-i
			});
			//alert (currentLi.attr('id')+'  ---  '+jQuery('#draggable'+currentLi.attr('id')).css('zIndex'));
		});	
}


function lbg_zoominoutslider_add_text_line(photoid,img_src) {
	var data ="action=lbg_zoominoutslider_add_text_record&security=<?php echo $lbg_zoominoutslider_add_text_record_ajax_nonce; ?>&photoid="+photoid+"&img_src="+img_src;

	// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
	jQuery.post(ajaxurl, data, function(response) {
		//alert('Got this from the server: ' + response);

		//var randNo=Math.floor(Math.random()*10000);
		//var textID=response;
		response_arr=response.split("####"); 
		//var textID=parseInt(response,10);
		textID=response_arr[0];
		maxORD=response_arr[1];		

		lbg_zoominoutslider_add_text_line_CONTENT(textID,maxORD,photoid,img_src);
		
	});


}


function lbg_zoominoutslider_delete_text_line(textid) {
	var data ="action=lbg_zoominoutslider_delete_text_record&security=<?php echo $lbg_zoominoutslider_delete_text_record_ajax_nonce; ?>&textid="+textid;

	// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
	jQuery.post(ajaxurl, data, function(response) {
		//alert ("ok");
			var deleted_ord=response;
			
			jQuery('#'+textid).remove();
			var lis = jQuery('#lbg_zoominoutslider_layers_sortable').children();
			lis.each(function() {
			   currentLi = jQuery(this);
			   old_ord=jQuery('input[name=ord_input_'+currentLi.attr('id')+']').val();
			   if (parseInt(old_ord,10)>parseInt(deleted_ord,10)) {				   		
			   		jQuery('input[name=ord_input_'+currentLi.attr('id')+']').val(old_ord-1);
			   }
			});	
			
			
			jQuery('#draggable'+textid).remove();
			
			jQuery('[id^="lbg_zoominoutsliderTable"]').css({
					'display':'none'
			});
			
			//disable delete button
			jQuery('#deletelayer').attr("disabled", "disabled");
			jQuery('#duplicatelayer').attr("disabled", "disabled");
			
			/*jQuery('#text_line_settings'+textid).remove();
			jQuery('#draggable'+textid).draggable( "destroy" );
			jQuery('#draggable'+textid).remove();*/		
	});
}



function change_text_div_css_class(theID,sel) {
	//alert (sel.options[sel.selectedIndex].value);
	//alert (jQuery("#draggable"+theID).attr('class'));
	jQuery("#draggable"+theID).removeClass(jQuery("#draggable"+theID).attr('class'));
	/*jQuery("#draggable"+theID).removeAttr('class');
	jQuery("#draggable"+theID).attr('class', '');	*/
	
	jQuery("#draggable"+theID).addClass('my_draggable');
	jQuery("#draggable"+theID).addClass(sel.options[sel.selectedIndex].value);
	jQuery("#draggable"+theID).addClass("my_draggable_activated");
}

function change_text_div_content(theID,textareaID) {
	var theTEXT=jQuery(textareaID).val();
	//theTEXT=theTEXT.replace(/(<([^>]+)>)/ig,"");
	theTEXT=theTEXT.replace(/<\/?[^>]+>/gi, '');
	if (jQuery('input[name=img_src'+theID+']').val()=='')
		jQuery('#draggable'+theID).html(theTEXT);
	if (theTEXT.length>50) {
		theTEXT=theTEXT.substring(0,50);
		theTEXT+='...';		
	}
	jQuery('#li_div'+theID).html(theTEXT);
}

function change_layer_image() {
	 //formfield = jQuery('#img').attr('name');
	 formfield = 'img_src';
	 tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
	 return false;
}

function lbg_zoominoutslider_process_val(val,cssprop) {
	retVal=parseInt(val.substring(0, val.length-2));
	if (cssprop=="top") {
		//retVal=retVal-148;
		retVal=retVal-0;
	} else {
		//retVal=retVal+1;
	}
	return retVal;
}

function deactivate_all_layers() {
	jQuery('[id^="draggable"]').removeClass('my_draggable_activated');
	
	//order div
	activate_layers_order_li(0);
	
	//disable settings table
	jQuery("[id^='lbg_zoominoutsliderTable']").css({
		'display':'none'
	});
	
	//disable delete button
	jQuery('#deletelayer').attr("disabled", "disabled");
	jQuery('#duplicatelayer').attr("disabled", "disabled");
}

function activate_layer(theID) {
		//activate delete layer button
		jQuery('input[name=currentTextID]').val(theID);
		jQuery('#deletelayer').removeAttr("disabled");
		jQuery('#duplicatelayer').removeAttr("disabled");		
		
		jQuery('[id^="lbg_zoominoutsliderTable"]').css({
			'display':'none'
		});
		
		jQuery('#lbg_zoominoutsliderTable'+theID).css({
			'display':'block'
		});
		
		jQuery('[id^="draggable"]').removeClass("my_draggable_activated");
		jQuery("#draggable"+theID).addClass("my_draggable_activated");	
		
		//order div
		activate_layers_order_li(theID);	
}


function activate_layers_order_li(theID) {
	var list = jQuery("#lbg_zoominoutslider_layers_sortable").children();
	list.each(function() {
		//jQuery(this).addClass('selectedLayer');
		if (jQuery(this).attr('id')==theID)
			jQuery(this).css({'border':'1px solid #000000'});
		else
			jQuery(this).css({'border':'1px solid #dfdfdf'});
	});
}



function lbg_zoominoutslider_duplicate(action_type,recordID) {
	var data ="action=lbg_zoominoutslider_duplicate_record&security=<?php echo $lbg_zoominoutslider_duplicate_record_ajax_nonce; ?>&action_type="+action_type+"&recordID="+recordID;

	// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
	jQuery.post(ajaxurl, data, function(response) {
		//alert ("ok");
		jQuery('#form-elements-lbg_zoominoutslider').submit();
	});
}



function lbg_zoominoutslider_edit_css_classes(action_type,theVal,playlistID) {
	//alert (action_type+'   ---   '+theVal);
	if (action_type=='open') {
		jQuery( "#dialogEditCSSClasses" ).dialog( "open" );
	} else {
		var data ="action=lbg_zoominoutslider_edit_css_classes_record&security=<?php echo $lbg_zoominoutslider_edit_css_classes_record_ajax_nonce; ?>&action_type="+action_type+"&theVal="+theVal+"&playlistID="+playlistID;
	
		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(ajaxurl, data, function(response) {
			//alert (response);
			response_arr=response.split("#*#*");
			for(i=0;i<response_arr.length ;i++) { 
				//class_and_id_arr=String(response_arr[i]).split("^&^&");
				if (i==0) {
					new_css_select=response_arr[0];
				} else {
					if (i==response_arr.length-1) {
						//alert (response_arr[response_arr.length-1] );
						jQuery('#css_styles_div').html(response_arr[response_arr.length-1]);
						jQuery('textarea#css_classes').val(response_arr[response_arr.length-1]);
					} else {
						//alert (class_and_id_arr[1]+'  ---   '+class_and_id_arr[0]);
						//alert (jQuery("#css"+class_and_id_arr[1]).val());
						orig_css=jQuery("#css"+response_arr[i]).val();
						jQuery('#lbg_zoominoutsliderCSS_div'+response_arr[i]).html(new_css_select.replace(/unique_xyz/g,response_arr[i]));
						jQuery("#css"+response_arr[i]).val(orig_css);
					}
				}
			}

			
			jQuery('#editCSSClasses_message').css('display','block');
			jQuery('#editCSSClasses_message').html('CSS classes updated!');

			setTimeout(function(){
					 jQuery('#editCSSClasses_message').css('display','none');
       				 jQuery('#dialogEditCSSClasses').dialog('close');                
    			}, 1000);
		
		});
	}
}

function showDialogPreview(theSliderID) {  //load content and open dialog
	var data ="action=lbg_zoominoutslider_preview_record&security=<?php echo $lbg_zoominoutslider_preview_record_ajax_nonce; ?>&theSliderID="+theSliderID;
	
	// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
	jQuery.post(ajaxurl, data, function(response) {
		//jQuery("#previewDialog").html(response);
		jQuery('#previewDialogIframe').attr('src','<?php echo plugins_url("tpl/preview.html?d=".time(), __FILE__)?>');
		jQuery("#previewDialog").dialog("open");
	});
}	



jQuery(document).ready(function($) {
	/*PREVIEW DIALOG BOX*/
	jQuery( "#previewDialog" ).dialog({
	  minWidth:1200,
	  minHeight:500,
	  title:"Slider Preview",
	  modal: true,
	  autoOpen:false,
	  hide: "fade",
	  resizable: false,
	  open: function() {
		//jQuery( this ).html();
	  },
	  close: function() {
		//jQuery("#previewDialog").html('');
		jQuery('#previewDialogIframe').attr('src','');
	  }
	});		
	
	/* THE PLAYLIST */
	if (jQuery('#lbg_zoominoutslider_sortable').length) {
		jQuery( '#lbg_zoominoutslider_sortable' ).sortable({
			placeholder: "ui-state-highlight",
			start: function(event, ui) {
	            ord_start = ui.item.prevAll().length + 1;
	        },
			update: function(event, ui) {
	        	jQuery("#lbg_zoominoutslider_sortable").sortable('disable');
	        	jQuery("#lbg_zoominoutslider_updating_witness").css("display","block");
				var ord_stop=ui.item.prevAll().length + 1;
				var elem_id=ui.item.attr("id");
				//alert (ui.item.attr("id"));
				//alert (ord_start+' --- '+ord_stop);
				var data = "action=lbg_zoominoutslider_update_playlist_record&security=<?php echo $lbg_zoominoutslider_update_playlist_record_ajax_nonce; ?>&updateType=change_ord&ord_start="+ord_start+"&ord_stop="+ord_stop+"&elem_id="+elem_id;
				// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
				jQuery.post(ajaxurl, data, function(response) {
					jQuery("#lbg_zoominoutslider_sortable").sortable('enable');
					jQuery("#lbg_zoominoutslider_updating_witness").css("display","none");
					//alert('Got this from the server: ' + response);
				});			
			}
		});
	}

/* THE LAYERS */
	if (jQuery('#lbg_zoominoutslider_layers_sortable').length) {
		jQuery( '#lbg_zoominoutslider_layers_sortable' ).sortable({
			placeholder: "ui-state-highlight",
			start: function(event, ui) {
	            ord_start = ui.item.prevAll().length + 1;
	        },
			update: function(event, ui) {
	        	jQuery("#lbg_zoominoutslider_layers_sortable").sortable('disable');
	        	jQuery("#lbg_zoominoutslider_updating_witness").css("display","block");
				var ord_stop=ui.item.prevAll().length + 1;
				var elem_id=ui.item.attr("id");
				var photoid=ui.item.attr("data-photoid");
				//alert (elem_id+' --- '+photoid+' --- '+ord_start+' --- '+ord_stop);
				
				var lis = jQuery('#lbg_zoominoutslider_layers_sortable').children();
				var i=0;
				lis.each(function() {
					i++;
				   currentLi = jQuery(this);
				   //new order
				   jQuery('input[name=ord_input_'+currentLi.attr('id')+']').val(i);
				   
				   //new z-index
				    jQuery('#draggable'+currentLi.attr('id')).css({
						'zIndex':100-i
					});
					//alert (currentLi.attr('id')+'  ---  '+jQuery('#draggable'+currentLi.attr('id')).css('zIndex'));
				});	
				
				
				var data = "action=lbg_zoominoutslider_update_playlist_record&security=<?php echo $lbg_zoominoutslider_update_playlist_record_ajax_nonce; ?>&updateType=change_layers_ord&ord_start="+ord_start+"&ord_stop="+ord_stop+"&photoid="+photoid+"&elem_id="+elem_id;
				// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
				jQuery.post(ajaxurl, data, function(response) {
					jQuery("#lbg_zoominoutslider_layers_sortable").sortable('enable');
					jQuery("#lbg_zoominoutslider_updating_witness").css("display","none");
					//alert('Got this from the server: ' + response);
				});			
			}
		});
	}	

	
	<?php 
		$rows_count = $wpdb->get_var( "SELECT COUNT(*) FROM ". $wpdb->prefix . "lbg_kenburnsslider_playlist;" );
		for ($i=1;$i<=$rows_count;$i++) {
	?>

	


		jQuery('#upload_img_button_lbg_zoominoutslider_<?php echo $i?>').click(function() {
		 formfield = 'img';
		 the_i=<?php echo $i?>;
		 tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
		 return false;
		});

		jQuery('#upload_thumbnail_button_lbg_zoominoutslider_<?php echo $i?>').click(function() {
		 formfield = 'thumbnail';
		 the_i=<?php echo $i?>;
		 tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
		 return false;
		});
		 


	

	jQuery("#form-playlist-lbg_zoominoutslider-<?php echo $i?>").submit(function(event) {

		/* stop form from submitting normally */
		event.preventDefault(); 
		
		//show loading image
		jQuery('#ajax-message-<?php echo $i?>').html('<img src="<?php echo plugins_url('lbg_zoominoutslider/images/ajax-loader.gif', dirname(__FILE__))?>" />');
		var data ="action=lbg_zoominoutslider_update_playlist_record&security=<?php echo $lbg_zoominoutslider_update_playlist_record_ajax_nonce; ?>&"+jQuery("#form-playlist-lbg_zoominoutslider-<?php echo $i?>").serialize();

		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(ajaxurl, data, function(response) {
			//alert('Got this from the server: ' + response);
			//alert(jQuery("#form-playlist-lbg_zoominoutslider-<?php echo $i?>").serialize());
			var new_img = '';
			if (document.forms["form-playlist-lbg_zoominoutslider-<?php echo $i?>"].img.value!='')
				new_img=document.forms["form-playlist-lbg_zoominoutslider-<?php echo $i?>"].img.value;
			jQuery('#top_image_'+document.forms["form-playlist-lbg_zoominoutslider-<?php echo $i?>"].id.value).attr('src',new_img);
			jQuery('#ajax-message-<?php echo $i?>').html(response);
		});
	});
	<?php } ?>
	
});
</script>
<?php
	$safe_sql="SELECT * FROM (".$wpdb->prefix ."lbg_kenburnsslider_css_definitions) LIMIT 0, 1";
	$row = $wpdb->get_row($safe_sql,ARRAY_A);
	echo '<style id="css_styles_div">'.$row['css_styles'].'</style>';
		}
	}
}

//lbg_zoominoutslider_update_playlist_record is the action=lbg_zoominoutslider_update_playlist_record

add_action('wp_ajax_lbg_zoominoutslider_update_playlist_record', 'lbg_zoominoutslider_update_playlist_record_callback');

function lbg_zoominoutslider_update_playlist_record_callback() {
	
	check_ajax_referer( 'lbg_zoominoutslider_update_playlist_record-special-string', 'security' ); //security=<?php echo $lbg_zoominoutslider_update_playlist_record_ajax_nonce; 
	global $wpdb;
	global $lbg_zoominoutslider_messages;
	$errors_arr=array();
	//$wpdb->show_errors();
	
	//delete entire record
	if ($_POST['updateType']=='lbg_zoominoutslider_delete_entire_record') {
		$delete_id=$_POST['delete_id'];
		$safe_sql=$wpdb->prepare("SELECT * FROM ".$wpdb->prefix."lbg_kenburnsslider_playlist WHERE id = %d",$delete_id);
		$row = $wpdb->get_row($safe_sql, ARRAY_A);
		$row=lbg_zoominoutslider_unstrip_array($row);

		//delete the entire record
		$wpdb->query($wpdb->prepare("DELETE FROM ".$wpdb->prefix."lbg_kenburnsslider_playlist WHERE id = %d",$delete_id));
		//delete texts
		$wpdb->query($wpdb->prepare("DELETE FROM ".$wpdb->prefix."lbg_kenburnsslider_texts WHERE photoid = %d",$delete_id));
		//update the order for the rest ord=ord-1 for > ord
		$wpdb->query($wpdb->prepare("UPDATE ".$wpdb->prefix."lbg_kenburnsslider_playlist SET ord=ord-1 WHERE bannerid = %d and  ord>".$row['ord'],$_SESSION['xid']));
	}

	//update elements order
	if ($_POST['updateType']=='change_ord') {
		$sql_arr=array();
		$ord_start=$_POST['ord_start'];
		$ord_stop=$_POST['ord_stop'];
		$elem_id=(int)$_POST['elem_id'];
		$ord_direction='+1';
		if ($ord_start<$ord_stop) 
			$sql_arr[]="UPDATE ".$wpdb->prefix."lbg_kenburnsslider_playlist SET ord=ord-1  WHERE bannerid = ".$_SESSION['xid']." and ord>".$ord_start." and ord<=".$ord_stop;
		else
			$sql_arr[]="UPDATE ".$wpdb->prefix."lbg_kenburnsslider_playlist SET ord=ord+1  WHERE bannerid = ".$_SESSION['xid']." and ord>=".$ord_stop." and ord<".$ord_start;
		$sql_arr[]="UPDATE ".$wpdb->prefix."lbg_kenburnsslider_playlist SET ord=".$ord_stop." WHERE id=".$elem_id;		
		
		//echo "elem_id: ".$elem_id."----ord_start: ".$ord_start."----ord_stop: ".$ord_stop;
		foreach ($sql_arr as $sql)
			$wpdb->query($sql);
	}
	
	
	//update layers order
	if ($_POST['updateType']=='change_layers_ord') {
		$sql_arr=array();
		$ord_start=$_POST['ord_start'];
		$ord_stop=$_POST['ord_stop'];
		$elem_id=(int)$_POST['elem_id'];
		$photoid=(int)$_POST['photoid'];
		$ord_direction='+1';
		if ($ord_start<$ord_stop) 
			$sql_arr[]="UPDATE ".$wpdb->prefix."lbg_kenburnsslider_texts SET ord=ord-1  WHERE photoid = ".$photoid." and ord>".$ord_start." and ord<=".$ord_stop;
		else
			$sql_arr[]="UPDATE ".$wpdb->prefix."lbg_kenburnsslider_texts SET ord=ord+1  WHERE photoid = ".$photoid." and ord>=".$ord_stop." and ord<".$ord_start;
		$sql_arr[]="UPDATE ".$wpdb->prefix."lbg_kenburnsslider_texts SET ord=".$ord_stop." WHERE id=".$elem_id;		
		
		//echo "elem_id: ".$elem_id."----ord_start: ".$ord_start."----ord_stop: ".$ord_stop;
		foreach ($sql_arr as $sql)
			$wpdb->query($sql);
	}
	
	
	
	//submit update
	/*if (empty($_POST['img']))
		$errors_arr[]=$lbg_zoominoutslider_messages['empty_img'];*/
	
	$theid=isset($_POST['id'])?$_POST['id']:0;
	if($theid>0 && !count($errors_arr)) {
		/*$except_arr=array('Submit'.$theid,'id','ord','action','security','updateType','uniqueUploadifyID');
		foreach ($_POST as $key=>$val){
			if (in_array($key,$except_arr)) {
				unset($_POST[$key]);
			}
		}*/
		//update playlist
		if ($_POST['data-initialZoom']=='')
			$_POST['data-initialZoom']=0;
			
		if ($_POST['data-finalZoom']=='')
			$_POST['data-finalZoom']=0;			
			
		if ($_POST['data-duration']=='')
			$_POST['data-duration']=0;
			
		if ($_POST['data-autoPlay']=='')
			$_POST['data-autoPlay']=0;				
					
		$wpdb->update( 
			$wpdb->prefix .'lbg_kenburnsslider_playlist',
				array( 
				'img' => $_POST['img'],
				'thumbnail' => $_POST['thumbnail'],
				'alt_text' => $_POST['alt_text'],
				'data-video' => $_POST['data-video'],
				'data-link' => $_POST['data-link'],
				'data-target' => $_POST['data-target'],
				'content' => $_POST['content'],
				'data-autoPlay' => $_POST['data-autoPlay'],
				'data-horizontalPosition' => $_POST['data-horizontalPosition'],
				'data-verticalPosition' => $_POST['data-verticalPosition'],
				'data-initialZoom' => $_POST['data-initialZoom'],
				'data-finalZoom' => $_POST['data-finalZoom'],
				'data-duration' => $_POST['data-duration'],
				'data-zoomEasing' => $_POST['data-zoomEasing']				
				), 
			array( 'id' => $theid )
		);
		//echo $wpdb->last_query;
		
		//update texts
		/*$safe_sql=$wpdb->prepare( "SELECT * FROM (".$wpdb->prefix ."lbg_kenburnsslider_texts) WHERE photoid = %d ORDER BY ord",$theid );
		$result_text = $wpdb->get_results($safe_sql,ARRAY_A);
		
		foreach ( $result_text as $row_text ) {
			$textid=$row_text['id'];
			$wpdb->update( 
				$wpdb->prefix .'lbg_kenburnsslider_texts',
					array( 
					'content' => $_POST['content'.$textid],
					'data-initial-left' => $_POST['data-initial-left'.$textid],
					'data-initial-top' => $_POST['data-initial-top'.$textid],
					'data-final-left' => $_POST['data-final-left'.$textid],
					'data-final-top' => $_POST['data-final-top'.$textid],
					'data-duration' => $_POST['data-duration'.$textid],
					'data-fade-start' => $_POST['data-fade-start'.$textid],
					'data-delay' => $_POST['data-delay'.$textid],
					'css' => $_POST['css'.$textid]
					), 
				array( 'id' => $textid )
			);
		}*/

		?>
			<div id="message" class="updated"><p><?php echo $lbg_zoominoutslider_messages['data_saved'];?></p></div>
	<?php 
	} else if (!isset($_POST['updateType'])) {
		$errors_arr[]=$lbg_zoominoutslider_messages['invalid_request'];
	}
    //echo $theid;
    
	if (count($errors_arr)) { ?>
		<div id="error" class="error"><p><?php echo implode("<br>", $errors_arr);?></p></div>
	<?php }

	die(); // this is required to return a proper result
}




add_action('wp_ajax_lbg_zoominoutslider_add_text_record', 'lbg_zoominoutslider_add_text_record_callback');

function lbg_zoominoutslider_add_text_record_callback() {
	
	check_ajax_referer( 'lbg_zoominoutslider_add_text_record-special-string', 'security' );
	global $wpdb;
	//$wpdb->show_errors();
	
	$wpdb->query($wpdb->prepare("UPDATE ".$wpdb->prefix."lbg_kenburnsslider_texts SET ord=ord+1 WHERE photoid = %d",$_POST['photoid']));
	
	if ($_POST['img_src']=='') {
			$css_aux="lbg_zoominoutslider_textWhiteBgBlack_medium";
	} else {
			$css_aux="lbg_zoominoutslider_textWhiteBgTransparent_small";
	}
	
	$new_max_ord = 1+$wpdb->get_var( $wpdb->prepare( "SELECT max(ord) FROM ". $wpdb->prefix ."lbg_kenburnsslider_texts WHERE photoid = %d",$_POST['photoid'] ) );	
	$max_ord=1;
	if ($_POST['img_src']=='') {
		$content_aux='TEXT HERE '.$new_max_ord;
	} else {
		$content_aux='IMAGE '.$new_max_ord;
	}
$wpdb->insert( 
			$wpdb->prefix . "lbg_kenburnsslider_texts", 
			array( 
				'photoid' => $_POST['photoid'],
				'img_src' => $_POST['img_src'],
				'content' => $content_aux,
				'element-link' =>'',
				'element-link-target' =>'',
				'data-initial-left' => 0,
				'data-initial-top' => 0,
				'data-initial-scale' => '1',
				'data-initial-skew' => '0deg,0deg',
				'data-final-left' => 0,
				'data-final-top' => 0,
				'data-final-scale' => '1',
				'data-final-skew' => '0deg,0deg',				
				'data-duration' => 0,
				'data-fade-start' => 0,
				'data-delay' => 0,
				'data-easing' => 'swing',
				'data-exit-left' => 0,
				'data-exit-top' => 0,
				'data-exit-fade' => 1,
				'data-exit-scale' => '1',
				'data-exit-skew' => '0deg,0deg',				
				'data-exit-duration' => 0,
				'data-exit-delay' => 0,
				'data-exit-easing' => 'swing',	
				'data-exit-off' => 'false',
				'data-intermediate-left' => 0,
				'data-intermediate-top' => 0,
				'data-intermediate-scale' => '1',
				'data-intermediate-skew' => '0deg,0deg',					
				'data-intermediate-duration' => 0,
				'data-intermediate-delay' => 0,
				'data-intermediate-easing' => 'linear',
				'css' => $css_aux,
				'ord' => 1
			), 
			array( 
'%d',
'%s',
'%s',
'%s',
'%s',
'%d',
'%d',
'%s',
'%s',
'%d',
'%d',
'%s',
'%s',
'%f',
'%d',
'%f',
'%s',
'%d',
'%d',
'%d',
'%s',
'%s',
'%f',
'%f',
'%s',
'%s',
'%d',
'%d',
'%s',
'%s',
'%f',
'%f',
'%s',
'%s',
'%d'
			) 
		);
		
		echo $wpdb->insert_id.'####'.$max_ord;
		
		die(); // this is required to return a proper result
}




add_action('wp_ajax_lbg_zoominoutslider_delete_text_record', 'lbg_zoominoutslider_delete_text_record_callback');

function lbg_zoominoutslider_delete_text_record_callback() {
	
	check_ajax_referer( 'lbg_zoominoutslider_delete_text_record-special-string', 'security' );
	global $wpdb;
	//$wpdb->show_errors();
	
	$safe_sql=$wpdb->prepare( "SELECT * FROM (".$wpdb->prefix ."lbg_kenburnsslider_texts) WHERE id = %d",$_POST['textid'] );
	$row = $wpdb->get_row($safe_sql,ARRAY_A);
	
	$wpdb->query(
	"
	DELETE FROM ".$wpdb->prefix ."lbg_kenburnsslider_texts
	WHERE id = ".$_POST['textid']."
	"
	);
	
	$wpdb->query(
	"UPDATE ".$wpdb->prefix."lbg_kenburnsslider_texts SET ord=ord-1  WHERE photoid = ".$row['photoid']." and ord>".$row['ord']
	);
	
	/*$max_ord=$wpdb->get_var( $wpdb->prepare( "SELECT max(ord) FROM ". $wpdb->prefix ."lbg_kenburnsslider_texts WHERE photoid = %d",$row['photoid'] ) );
	echo $max_ord;*/
	
	echo $row['ord'];
	
	die(); // this is required to return a proper result
}


add_action('wp_ajax_lbg_zoominoutslider_duplicate_record', 'lbg_zoominoutslider_duplicate_record_callback');

function lbg_zoominoutslider_duplicate_record_callback() {
	
	check_ajax_referer( 'lbg_zoominoutslider_duplicate_record-special-string', 'security' );
	global $wpdb;

	$return_str='';
	
	if ($_POST['action_type']='duplicate layer') {
		$_SESSION['duplicate_layer'] = $_POST['recordID'];
	}
	
	//$max_ord = 1+$wpdb->get_var( $wpdb->prepare( "SELECT max(ord) FROM ". $wpdb->prefix ."lbg_kenburnsslider_texts WHERE photoid = %d",$_POST['photoid'] ) );
	
	//echo $_POST['action_type'].'   ---  '.$_POST['recordID'];
	//echo $wpdb->last_query;
	echo $return_str;
	
	die(); // this is required to return a proper result
}



add_action('wp_ajax_lbg_zoominoutslider_edit_css_classes_record', 'lbg_zoominoutslider_edit_css_classes_record_callback');

function lbg_zoominoutslider_edit_css_classes_record_callback() {
	
	check_ajax_referer( 'lbg_zoominoutslider_edit_css_classes_record-special-string', 'security' );
	global $wpdb;

	//$wpdb->show_errors();
	if ($_POST['action_type']=='update_css') {
		$wpdb->query(
		"UPDATE ".$wpdb->prefix."lbg_kenburnsslider_css_definitions SET css_styles='".$_POST['theVal']."'  WHERE id = 1"
		);
	} else {
		$safe_sql=$wpdb->prepare( "SELECT * FROM (".$wpdb->prefix ."lbg_kenburnsslider_css_definitions) WHERE id = %d", 1);
		$row_css = $wpdb->get_row($safe_sql,ARRAY_A);
		$wpdb->query(
		"UPDATE ".$wpdb->prefix."lbg_kenburnsslider_css_definitions SET css_styles='".$row_css['css_styles_orig']."' WHERE id = 1"
		);
	}
	
	$safe_sql="SELECT * FROM (".$wpdb->prefix ."lbg_kenburnsslider_css_definitions) LIMIT 0, 1";
	$row2 = $wpdb->get_row($safe_sql,ARRAY_A);
	
	if ($_POST['action_type']!='open') {
		$filename=plugin_dir_path(__FILE__) . 'zoominoutslider/text_classes.css';
		$fp = fopen($filename, 'w+');
		$fwrite = fwrite($fp, $row2['css_styles']);
	}

	
	$safe_sql=$wpdb->prepare( "SELECT * FROM (".$wpdb->prefix ."lbg_kenburnsslider_texts) WHERE photoid = %d ORDER BY ord",$_POST['playlistID'] );
	$result_text2 = $wpdb->get_results($safe_sql,ARRAY_A);
	
	$return_arr=array();
	$return_str='';
	foreach ( $result_text2 as $row_text2 ) {
		//$return_arr[]=$row_text2['css']."^&^&".$row_text2['id'];
		$return_arr[]=$row_text2['id'];
	}
	$return_str=lbg_zoominoutslider_getCssStyles('lbg_zoominoutslider_textWhiteBgBlack_medium','unique_xyz','')."#*#*".implode("#*#*", $return_arr)."#*#*".$row2['css_styles'];
	echo $return_str;
	//echo $fwrite;
	//echo $wpdb->last_query;
	//echo json_encode($return_arr);
	
	die(); // this is required to return a proper result
}




add_action('wp_ajax_lbg_zoominoutslider_preview_record', 'lbg_zoominoutslider_preview_record_callback');

function lbg_zoominoutslider_preview_record_callback() {
	check_ajax_referer( 'lbg_zoominoutslider_preview_record-special-string', 'security' );
	
	//echo lbg_zoominoutslider_generate_preview_code($_POST['theSliderID']);
	$aux_val='<html>
					<head>
						<link href="'.plugins_url('zoominoutslider/bannerscollection_zoominout.css', __FILE__).'" rel="stylesheet" type="text/css">
						<link href="'.plugins_url('zoominoutslider/text_classes.css', __FILE__).'?'.time().'" rel="stylesheet" type="text/css">
						<link href="http://fonts.googleapis.com/css?family=Droid+Sans:400,700" rel="stylesheet" type="text/css">
						<link href="http://fonts.googleapis.com/css?family=Rokkitt" rel="stylesheet" type="text/css">

						<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js" type="text/javascript"></script>
						<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
						<script src="'.plugins_url('zoominoutslider/js/jquery.ui.touch-punch.min.js', __FILE__).'" type="text/javascript"></script>
						<script src="'.plugins_url('zoominoutslider/js/bannerscollection_zoominout.js', __FILE__).'" type="text/javascript"></script>
					</head>
					<body style="padding:0px;margin:0px;">';
						
	$aux_val.=lbg_zoominoutslider_generate_preview_code($_POST['theSliderID']);
	$aux_val.="</body>
				</html>";
	$filename=plugin_dir_path(__FILE__) . 'tpl/preview.html';
	$fp = fopen($filename, 'w+');
	$fwrite = fwrite($fp, $aux_val);
	
	echo $fwrite;
	
	die(); // this is required to return a proper result
}



?>