<?php
/*
Plugin Name: LambertGroup - AllInOne - Carousel
Description: This plugin will allow you to administrate an advanced carousel.
Version: 3.5.1
Author: Lambert Group
Author URI: http://www.lambertgroup.ro cu http://codecanyon.net/user/LambertGroup/portfolio?ref=LambertGroup
*/

ini_set('display_errors', 0);
//$wpdb->show_errors();
$all_in_one_carousel_path = trailingslashit(dirname(__FILE__));  //empty

//all the messages
$all_in_one_carousel_messages = array(
		'version' => '<div class="error">LambertGroup - AllInOne Carousel plugin requires WordPress 3.0 or newer. <a href="http://codex.wordpress.org/Upgrading_WordPress">Please update!</a></div>',
		'empty_img' => 'Image - required',
		'invalid_request' => 'Invalid Request!',
		'generate_for_this_player' => 'You can start customizing this carousel.',
		'data_saved' => 'Data Saved!'
	);

	
global $wp_version;

if ( !version_compare($wp_version,"3.0",">=")) {
	die ($all_in_one_carousel_messages['version']);
}




function all_in_one_carousel_activate() {
	//db creation, create admin options etc.
	global $wpdb;
	//$wpdb->show_errors();
	
	$all_in_one_carousel_collate = ' COLLATE utf8_general_ci';
	
	$sql0 = "CREATE TABLE `" . $wpdb->prefix . "all_in_one_carousel_carousels` (
			`id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
			`name` VARCHAR( 255 ) NOT NULL ,
			PRIMARY KEY ( `id` )
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8";
	
	$sql1 = "CREATE TABLE `" . $wpdb->prefix . "all_in_one_carousel_settings` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `width` smallint(5) unsigned NOT NULL DEFAULT '990',
  `height` smallint(5) unsigned NOT NULL DEFAULT '454',
  `width100Proc` varchar(8) NOT NULL DEFAULT 'false',
  `height100Proc` varchar(8) NOT NULL DEFAULT 'false',   
  `skin` varchar(255) NOT NULL DEFAULT 'charming',
  `autoPlay` smallint(5) unsigned NOT NULL DEFAULT '3',
  `numberOfVisibleItems` smallint(5) unsigned NOT NULL DEFAULT '3',
  `elementsHorizontalSpacing` smallint(5) unsigned NOT NULL DEFAULT '110',
  `elementsVerticalSpacing` smallint(5) unsigned NOT NULL DEFAULT '20',
  `verticalAdjustment` smallint(5) NOT NULL DEFAULT '50',
  `animationTime` float unsigned NOT NULL DEFAULT '0.5',
  `easing` varchar(50) NOT NULL DEFAULT 'easeOutQuad',
  `resizeImages` varchar(8) NOT NULL DEFAULT 'true',
  `target` varchar(8) NOT NULL DEFAULT '_blank',
  `showElementTitle` varchar(8) NOT NULL DEFAULT 'false',
  `showAllControllers` varchar(8) NOT NULL DEFAULT 'true',
  `showNavArrows` varchar(8) NOT NULL DEFAULT 'true',
  `showOnInitNavArrows` varchar(8) NOT NULL DEFAULT 'true',
  `autoHideNavArrows` varchar(8) NOT NULL DEFAULT 'true',
  `showBottomNav` varchar(8) NOT NULL DEFAULT 'true',
  `showOnInitBottomNav` varchar(8) NOT NULL DEFAULT 'true',
  `autoHideBottomNav` varchar(8) NOT NULL DEFAULT 'false',
  `showPreviewThumbs` varchar(8) NOT NULL DEFAULT 'false',
  `enableTouchScreen` varchar(8) NOT NULL DEFAULT 'true',
  `nextPrevMarginTop` smallint(5) NOT NULL DEFAULT '23',
  `playMovieMarginTop` smallint(5) NOT NULL DEFAULT '0',
  `bottomNavMarginBottom` smallint(5) NOT NULL DEFAULT '-10',
  `showCircleTimer` varchar(8) NOT NULL DEFAULT 'true',
  `showCircleTimerIE8IE7` varchar(8) NOT NULL DEFAULT 'false',
  `circleRadius` smallint(5) unsigned NOT NULL DEFAULT '10',
  `circleLineWidth` smallint(5) unsigned NOT NULL DEFAULT '4',
  `circleColor` varchar(8) NOT NULL DEFAULT 'ff0000',
  `circleAlpha` smallint(5) unsigned NOT NULL DEFAULT '100',
  `behindCircleColor` varchar(8) NOT NULL DEFAULT '000000',
  `behindCircleAlpha` smallint(5) unsigned NOT NULL DEFAULT '50',  
  `circleLeftPositionCorrection` smallint(5) NOT NULL DEFAULT '3',
  `circleTopPositionCorrection` smallint(5) NOT NULL DEFAULT '3',
  `responsive` varchar(8) NOT NULL DEFAULT 'false',
  `responsiveRelativeToBrowser` varchar(8) NOT NULL DEFAULT 'true',
	  PRIMARY KEY  (`id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8";
	
	$sql2 = "CREATE TABLE `". $wpdb->prefix . "all_in_one_carousel_playlist` (
	  `id` int(10) unsigned NOT NULL auto_increment,
	  `carouselid` int(10) unsigned NOT NULL,
	  `img` text,
	  `thumbnail` text,
	  `data-title` text,
	  `data-video` text,
	  `data-target` varchar(8) NOT NULL DEFAULT '_blank',
	  `data-link` text,		  
	  `content` text,
	  `ord` int(10) unsigned NOT NULL,
	  PRIMARY KEY  (`id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8";
	
	$sql3 = "CREATE TABLE `". $wpdb->prefix . "all_in_one_carousel_texts` (
	  `id` int(10) unsigned NOT NULL auto_increment,
	  `photoid` int(10) unsigned NOT NULL,
	  `content` text,
	  `data-initial-left` smallint(5),
	  `data-initial-top` smallint(5),
	  `data-final-left` smallint(5),
	  `data-final-top` smallint(5),
	  `data-duration` float unsigned,
	  `data-fade-start` smallint(5) unsigned,
	  `data-delay` float unsigned,
	  `css` text,
	  PRIMARY KEY  (`id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8";	
	
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql0.$all_in_one_carousel_collate);
	dbDelta($sql1.$all_in_one_carousel_collate);
	dbDelta($sql2.$all_in_one_carousel_collate);
	dbDelta($sql3.$all_in_one_carousel_collate);
	
	//initialize the carousels table with the first carousel type
	$rows_count = $wpdb->get_var( "SELECT COUNT(*) FROM ". $wpdb->prefix ."all_in_one_carousel_carousels;" );
	if (!$rows_count) {
		$wpdb->insert( 
			$wpdb->prefix . "all_in_one_carousel_carousels", 
			array( 
				'name' => 'First Carousel'
			), 
			array(
				'%s'			
			) 
		);	
	}	
	
	// initialize the settings
	$rows_count = $wpdb->get_var( "SELECT COUNT(*) FROM ". $wpdb->prefix ."all_in_one_carousel_settings;" );
	if (!$rows_count) {
		all_in_one_carousel_insert_settings_record(1);
	}	
	
	


	
}


function all_in_one_carousel_uninstall() {
	global $wpdb;
	mysql_query("DROP TABLE `" . $wpdb->prefix . "all_in_one_carousel_settings`" );
	mysql_query("DROP TABLE `" . $wpdb->prefix . "all_in_one_carousel_playlist`" );
	mysql_query("DROP TABLE `" . $wpdb->prefix . "all_in_one_carousel_carousels`" );
	mysql_query("DROP TABLE `" . $wpdb->prefix . "all_in_one_carousel_texts`" );
}

function all_in_one_carousel_insert_settings_record($carousel_id) {
	global $wpdb;
	$wpdb->insert( 
			$wpdb->prefix . "all_in_one_carousel_settings", 
			array( 
				'width' => 990, 
				'height' => 454,
				'skin' => 'charming',
				'autoPlay' => 3,
				'numberOfVisibleItems' => 3,
				'elementsHorizontalSpacing' => 110,
				'elementsVerticalSpacing' => 20,
				'verticalAdjustment' => 50,
				'animationTime' => 0.5,
				'easing' => 'easeOutQuad',
				'resizeImages' => 'true',
				'showElementTitle' => 'false',
				'showAllControllers' => 'true',
				'showNavArrows' => 'true',
				'showOnInitNavArrows' => 'true',
				'autoHideNavArrows' => 'true',
				'showBottomNav' => 'true',
				'showOnInitBottomNav' => 'true',
				'autoHideBottomNav' => 'false',
				'showPreviewThumbs' => 'false',
				'enableTouchScreen' => 'true'
			), 
			array( 
				'%d', 
				'%d',
				'%s',
				'%d', 
				'%d',
				'%d',
				'%d',
				'%d',
				'%f',
				'%s', 
				'%s', 
				'%s', 
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s'
			) 
		);
}


function all_in_one_carousel_init_sessions() {
	global $wpdb;
	if (is_admin()) { 
		if (!session_id()) {
			session_start();
			
			//initialize the session
			if (!isset($_SESSION['xid'])) {
				$safe_sql="SELECT * FROM (".$wpdb->prefix ."all_in_one_carousel_carousels) LIMIT 0, 1";
				$row = $wpdb->get_row($safe_sql,ARRAY_A);
				//$row=all_in_one_carousel_unstrip_array($row);		
				$_SESSION['xid'] = $row['id'];
				$_SESSION['xname'] = $row['name'];
			}		
		}
	}
}


function all_in_one_carousel_load_styles() {
	if(strpos($_SERVER['PHP_SELF'], 'wp-admin') !== false) {
		$page = (isset($_GET['page'])) ? $_GET['page'] : '';
		if(preg_match('/all_in_one_carousel/i', $page)) {
			wp_enqueue_style('all_in_one_carousel_css', plugins_url('css/styles.css', __FILE__));
			wp_enqueue_style('all_in_one_carousel_jquery-custom_css', plugins_url('css/custom-theme/jquery-ui-1.8.10.custom.css', __FILE__));
			wp_enqueue_style('all_in_one_carousel_colorpicker_css', plugins_url('css/colorpicker/colorpicker.css', __FILE__));
			
			
			wp_enqueue_style('thickbox');
		}
	} else if (!is_admin()) { //loads css in front-end
		wp_enqueue_style('all_in_one_carousel_site_css', plugins_url('carousel/allinone_carousel.css', __FILE__));
		
		wp_register_style('googleFontsDroidSans', 'http://fonts.googleapis.com/css?family=Droid+Sans');
		wp_enqueue_style( 'googleFontsDroidSans');	
	}
}

function all_in_one_carousel_load_scripts() {
	global $is_IE;
	$page = (isset($_GET['page'])) ? $_GET['page'] : '';
	if(preg_match('/all_in_one_carousel/i', $page)) {
		//loads scripts in admin
		//if (is_admin()) {
			//wp_deregister_script('jquery');
			/*wp_register_script('lbg-admin-jquery', plugins_url('js/jquery-1.5.1.js', __FILE__));
			wp_enqueue_script('lbg-admin-jquery');*/
			wp_deregister_script('jquery-ui-core');
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
			wp_deregister_script('jquery-ui-button');	/**/			
			
			wp_enqueue_script('jquery');
			
			//wp_register_script('lbg-admin-jquery-ui-min', plugins_url('js/jquery-ui-1.8.10.custom.min.js', __FILE__));
			//wp_register_script('lbg-admin-jquery-ui-min', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/jquery-ui.min.js');
			wp_register_script('lbg-admin-jquery-ui-min', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js');
			wp_enqueue_script('lbg-admin-jquery-ui-min');
			
			wp_register_script('lbg-admin-colorpicker', plugins_url('js/colorpicker/colorpicker.js', __FILE__));
			wp_enqueue_script('lbg-admin-colorpicker');	

			wp_register_script('lbg-admin-toggle', plugins_url('js/myToggle.js', __FILE__));
			wp_enqueue_script('lbg-admin-toggle');
			

			wp_enqueue_script('media-upload');
			wp_enqueue_script('thickbox');
			
		
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
			wp_deregister_script('jquery-ui-button');	*/
				
		wp_enqueue_script('jquery');
	
		//wp_enqueue_script('jquery-ui-core');
		
		//wp_register_script('lbg-jquery-ui-min', plugins_url('carousel/js/jquery-ui-1.8.16.custom.min.js', __FILE__));
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
	
		wp_register_script('lbg-touch', plugins_url('carousel/js/jquery.ui.touch-punch.min.js', __FILE__));
		wp_enqueue_script('lbg-touch');		
		
		wp_register_script('lbg-all_in_one_carousel', plugins_url('carousel/js/allinone_carousel.js', __FILE__));
		wp_enqueue_script('lbg-all_in_one_carousel');
		
		/*if ($is_IE) {
			wp_register_script('lbg-excanvas', plugins_url('carousel/js/excanvas.compiled.js', __FILE__));
			wp_enqueue_script('lbg-excanvas');
		}*/
	}

}



// adds the menu pages
function all_in_one_carousel_plugin_menu() {
	add_menu_page('ALLINONE-CAROUSEL Admin Interface', 'ALLINONE-CAROUSEL', 'edit_posts', 'all_in_one_carousel', 'all_in_one_carousel_overview_page',
	plugins_url('images/plg_icon.png', __FILE__));
	add_submenu_page( 'all_in_one_carousel', 'ALLINONE-CAROUSEL Overview', 'Overview', 'edit_posts', 'all_in_one_carousel', 'all_in_one_carousel_overview_page');
	add_submenu_page( 'all_in_one_carousel', 'ALLINONE-CAROUSEL Manage Carousels', 'Manage Carousels', 'edit_posts', 'all_in_one_carousel_Manage_Carousels', 'all_in_one_carousel_manage_carousels_page');
	add_submenu_page( 'all_in_one_carousel', 'ALLINONE-CAROUSEL Manage Carousels Add New', 'Add New', 'edit_posts', 'all_in_one_carousel_Add_New', 'all_in_one_carousel_manage_carousels_add_new_page');
	add_submenu_page( 'ALLINONE-CAROUSEL Manage Carousels', 'ALLINONE-CAROUSEL Carousel Settings', 'Carousel Settings', 'edit_posts', 'all_in_one_carousel_Settings', 'all_in_one_carousel_settings_page');
	add_submenu_page( 'ALLINONE-CAROUSEL Manage Carousels', 'ALLINONE-CAROUSEL Carousel Playlist', 'Playlist', 'edit_posts', 'all_in_one_carousel_Playlist', 'all_in_one_carousel_playlist_page');
	add_submenu_page( 'all_in_one_carousel', 'ALLINONE-CAROUSEL Help', 'Help', 'edit_posts', 'all_in_one_carousel_Help', 'all_in_one_carousel_help_page');
}


//HTML content for overview page
function all_in_one_carousel_overview_page()
{
	include_once($all_in_one_carousel_path . 'tpl/overview.php');
}

//HTML content for Manage Carousels
function all_in_one_carousel_manage_carousels_page()
{
	global $wpdb;
	global $all_in_one_carousel_messages;
	
	//delete carousel
	if (isset($_GET['id'])) {
		

		

		//delete from wp_all_in_one_carousel_carousels
		$wpdb->query($wpdb->prepare("DELETE FROM ".$wpdb->prefix."all_in_one_carousel_carousels WHERE id = %d",$_GET['id']));
		
		//delete from wp_all_in_one_carousel_settings
		$wpdb->query($wpdb->prepare("DELETE FROM ".$wpdb->prefix."all_in_one_carousel_settings WHERE id = %d",$_GET['id']));
		
		//delete all_in_one_carousel_texts
		$safe_sql=$wpdb->prepare("SELECT id FROM ".$wpdb->prefix."all_in_one_carousel_playlist WHERE carouselid = %d",$_GET['id']);
		$result = $wpdb->get_results($safe_sql,ARRAY_A);
		if ($wpdb->num_rows) {
			foreach ( $result as $row ) {	
				$wpdb->query($wpdb->prepare("DELETE FROM ".$wpdb->prefix."all_in_one_carousel_texts WHERE photoid = %d",$row['id']));
			}
		}
		
		//delete from wp_all_in_one_carousel_playlist
		$wpdb->query($wpdb->prepare("DELETE FROM ".$wpdb->prefix."all_in_one_carousel_playlist WHERE carouselid = %d",$_GET['id']));
		
		//initialize the session
		$safe_sql="SELECT * FROM (".$wpdb->prefix ."all_in_one_carousel_carousels) ORDER BY id";
		$row = $wpdb->get_row($safe_sql,ARRAY_A);
		$row=all_in_one_carousel_unstrip_array($row);
		if ($row['id']) {
			$_SESSION['xid']=$row['id'];
			$_SESSION['xname']=$row['name'];
		}		
	}
	
	
	$safe_sql="SELECT * FROM (".$wpdb->prefix ."all_in_one_carousel_carousels) ORDER BY id";
	$result = $wpdb->get_results($safe_sql,ARRAY_A);	
	include_once($all_in_one_carousel_path . 'tpl/carousels.php');

}


//HTML content for Manage Carousels - Add New
function all_in_one_carousel_manage_carousels_add_new_page()
{
	global $wpdb;
	global $all_in_one_carousel_messages;
	
	if($_POST['Submit'] == 'Add New') {
		$errors_arr=array();
		if (empty($_POST['name']))
			$errors_arr[]=$all_in_one_carousel_messages['empty_name'];

		if (count($errors_arr)) { 
				include_once($all_in_one_carousel_path . 'tpl/add_carousel.php'); ?>
				<div id="error" class="error"><p><?php echo implode("<br>", $errors_arr);?></p></div>
		  	<?php } else { // no errors
					$wpdb->insert( 
						$wpdb->prefix . "all_in_one_carousel_carousels", 
						array( 
							'name' => $_POST['name']
						), 
						array( 
							'%s'			
						) 
					);	
					//insert default Carousel Settings for this new carousel
					all_in_one_carousel_insert_settings_record($wpdb->insert_id);
					?>
						<div class="wrap">
							<div id="lbg_logo">
								<h2>Manage Carousels - Add New Carousel</h2>
				 			</div>
							<div id="message" class="updated"><p><?php echo $all_in_one_carousel_messages['data_saved'];?></p><p><?php echo $all_in_one_carousel_messages['generate_for_this_carousel'];?></p></div>
							<div>
								<p>&raquo; <a href="?page=all_in_one_carousel_Add_New">Add New (carousel)</a></p>
								<p>&raquo; <a href="?page=all_in_one_carousel_Manage_Carousels">Back to Manage Carousels</a></p>
							</div>
						</div>	
		  	<?php }			
	} else {
		include_once($all_in_one_carousel_path . 'tpl/add_carousel.php');
	}

}


//HTML content for carouselsettings
function all_in_one_carousel_settings_page()
{
	global $wpdb;
	global $all_in_one_carousel_messages;
	
	if (isset($_GET['id']) && isset($_GET['name'])) {
		$_SESSION['xid']=$_GET['id'];
		$_SESSION['xname']=$_GET['name'];
	}

	//$wpdb->show_errors();
	/*if (check_admin_referer('all_in_one_carousel_settings_update')) {
		echo "update";		
	}*/
	
	
	if($_POST['Submit'] == 'Update Carousel Settings') {
		$_GET['xmlf']='';
		$except_arr=array('Submit','name');

			$wpdb->update( 
				$wpdb->prefix .'all_in_one_carousel_carousels', 
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
				$wpdb->prefix .'all_in_one_carousel_settings', 
				$_POST, 
				array( 'id' => $_SESSION['xid'] )
			);
			
			?>
			<div id="message" class="updated"><p><?php echo $all_in_one_carousel_messages['data_saved'];?></p></div>
	<?php 

	}
	
	if ($_GET['xmlf']=='carouselsettings') {
		all_in_one_carousel_generate_videoSettings();
	}	
	
	//echo "WP_PLUGIN_URL: ".WP_PLUGIN_URL;
	$safe_sql=$wpdb->prepare( "SELECT * FROM (".$wpdb->prefix ."all_in_one_carousel_settings) WHERE id = %d",$_SESSION['xid'] );
	$row = $wpdb->get_row($safe_sql,ARRAY_A);
	$row=all_in_one_carousel_unstrip_array($row);
	$_POST = $row; 
	//$_POST['existingWatermarkPath']=$_POST['watermarkPath'];
	$_POST=all_in_one_carousel_unstrip_array($_POST);
		
	//echo "width: ".$row['width'];
	include_once($all_in_one_carousel_path . 'tpl/settings_form.php');
	
}

function all_in_one_carousel_playlist_page()
{
	global $wpdb;
	global $all_in_one_carousel_messages;
	//$wpdb->show_errors();
	
	if (isset($_GET['id']) && isset($_GET['name'])) {
		$_SESSION['xid']=$_GET['id'];
		$_SESSION['xname']=$_GET['name'];
	}	

	
	if ($_GET['xmlf']=='add_playlist_record') {
		if($_POST['Submit'] == 'Add Record') {
			$errors_arr=array();
			/*if (empty($_POST['img']))
				 $errors_arr[]=$all_in_one_carousel_messages['empty_img'];*/

				 	
		if (count($errors_arr)) {
			include_once($all_in_one_carousel_path . 'tpl/add_playlist_record.php'); ?>
			<div id="error" class="error"><p><?php echo implode("<br>", $errors_arr);?></p></div>
	  	<?php } else { // no upload errors
				$max_ord = 1+$wpdb->get_var( $wpdb->prepare( "SELECT max(ord) FROM ". $wpdb->prefix ."all_in_one_carousel_playlist WHERE carouselid = %d",$_SESSION['xid'] ) );

				$wpdb->insert( 
					$wpdb->prefix . "all_in_one_carousel_playlist", 
					array( 
						'carouselid' => $_POST['carouselid'],
						'img' => $_POST['img'],
						'thumbnail' => $_POST['thumbnail'],
						'data-title' => $_POST['data-title'],
						'data-video' => $_POST['data-video'],
						'data-link' => $_POST['data-link'],
						'data-target' => $_POST['data-target'],						
						'content' => $_POST['content'],
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
						'%d'			
					) 
				);	

	  			if (isset($_POST['setitfirst'])) {
					$sql_arr=array();
					$ord_start=$max_ord;
					$ord_stop=1;
					$elem_id=$wpdb->insert_id;
					$ord_direction='+1';

					$sql_arr[]="UPDATE ".$wpdb->prefix."all_in_one_carousel_playlist SET ord=ord+1  WHERE carouselid = ".$_SESSION['xid']." and ord>=".$ord_stop." and ord<".$ord_start;
					$sql_arr[]="UPDATE ".$wpdb->prefix."all_in_one_carousel_playlist SET ord=".$ord_stop." WHERE id=".$elem_id;		
					
					//echo "elem_id: ".$elem_id."----ord_start: ".$ord_start."----ord_stop: ".$ord_stop;
					foreach ($sql_arr as $sql)
						$wpdb->query($sql);				
				}				
				?>
					<div class="wrap">
						<div id="lbg_logo">
							<h2>Playlist for carousel: <span style="color:#FF0000; font-weight:bold;"><?php echo $_SESSION['xname']?> - ID #<?php echo $_SESSION['xid']?></span> - Add New</h2>
			 			</div>
						<div id="message" class="updated"><p><?php echo $all_in_one_carousel_messages['data_saved'];?></p></div>
						<div>
							<p>&raquo; <a href="?page=all_in_one_carousel_Playlist&xmlf=add_playlist_record">Add New</a></p>
							<p>&raquo; <a href="?page=all_in_one_carousel_Playlist">Back to Playlist</a></p>
						</div>
					</div>	
	  	<?php }
		} else {
			include_once($all_in_one_carousel_path . 'tpl/add_playlist_record.php');	
		}
		
	} else {
		$safe_sql=$wpdb->prepare( "SELECT * FROM (".$wpdb->prefix ."all_in_one_carousel_playlist) WHERE carouselid = %d ORDER BY ord",$_SESSION['xid'] );
		$result = $wpdb->get_results($safe_sql,ARRAY_A);
		
		//$_POST=all_in_one_carousel_unstrip_array($_POST);		
		include_once($all_in_one_carousel_path . 'tpl/playlist.php');
	}
}



function all_in_one_carousel_help_page()
{
	//include_once(plugins_url('tpl/help.php', __FILE__));
	include_once($all_in_one_carousel_path . 'tpl/help.php');
}


function all_in_one_carousel_shortcode($atts, $content=null) {
	global $wpdb;
	
	shortcode_atts( array('settings_id'=>''), $atts);
	if ($atts['settings_id']=='')
		$atts['settings_id']=1;

		
	$safe_sql=$wpdb->prepare( "SELECT * FROM (".$wpdb->prefix ."all_in_one_carousel_settings) WHERE id = %d",$atts['settings_id'] );
	$row = $wpdb->get_row($safe_sql,ARRAY_A);
	$row=all_in_one_carousel_unstrip_array($row);
	
	$path_to_plugin = WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__));

		
	$safe_sql=$wpdb->prepare( "SELECT * FROM (".$wpdb->prefix ."all_in_one_carousel_playlist) WHERE carouselid = %d ORDER BY ord",$atts['settings_id'] );
	$result = $wpdb->get_results($safe_sql,ARRAY_A);
	$playlist_str='';
	$text_str='';
	foreach ( $result as $row_playlist ) {

		$row_playlist=all_in_one_carousel_unstrip_array($row_playlist);			
		
		$img_over='';
		if ($row_playlist['img']!='')
			$img_over='<img src="'.$row_playlist['img'].'" />';		
	
		//get texts
		$safe_sql=$wpdb->prepare( "SELECT * FROM (".$wpdb->prefix ."all_in_one_carousel_texts) WHERE photoid = %d ORDER BY id",$row_playlist['id'] );
		$result_text = $wpdb->get_results($safe_sql,ARRAY_A);
		if ($wpdb->num_rows) { // i have texts
			$playlist_str.='<li data-video="'.$row_playlist['data-video'].'" data-bottom-thumb="'.$row_playlist['thumbnail'].'" data-title="'.$row_playlist['data-title'].'" data-link="'.$row_playlist['data-link'].'" data-target="'.$row_playlist['data-target'].'"> '.$img_over.$row_playlist['content'].'</li>';
			
			
			$text_str.='<div id="allinone_carousel_photoText'.$row_playlist['id'].'" class="allinone_carousel_texts">';
			foreach ( $result_text as $row_text ) {
				$row_text=all_in_one_carousel_unstrip_array($row_text);
				//echo $row_text['id']."; ";
			
				$text_str.='<div class="allinone_carousel_text_line" style="'.$row_text['css'].'" data-initial-left="'.$row_text['data-initial-left'].'" data-initial-top="'.$row_text['data-initial-top'].'" data-final-left="'.$row_text['data-final-left'].'" data-final-top="'.$row_text['data-final-top'].'" data-duration="'.$row_text['data-duration'].'" data-fade-start="'.$row_text['data-fade-start'].'" data-delay="'.$row_text['data-delay'].'">'.$row_text['content'].'</div>';
			}
			$text_str.='</div>';
		} else { // no data-text-id, only image
			$playlist_str.='<li data-video="'.$row_playlist['data-video'].'" data-bottom-thumb="'.$row_playlist['thumbnail'].'" data-title="'.$row_playlist['data-title'].'" data-link="'.$row_playlist['data-link'].'" data-target="'.$row_playlist['data-target'].'"> '.$img_over.$row_playlist['content'].'</li>';
			
		}

	}
	
	
	
	
	return '<script>
		jQuery(function() {
			jQuery("#allinone_carousel_'.$row["id"].'").allinone_carousel({
				skin:"'.$row["skin"].'",
				width:'.$row["width"].',
				height:'.$row["height"].',
				width100Proc:false,
				height100Proc:false,				
				autoPlay:'.$row["autoPlay"].',
				numberOfVisibleItems:'.$row["numberOfVisibleItems"].',
				elementsHorizontalSpacing:'.$row["elementsHorizontalSpacing"].',
				elementsVerticalSpacing:'.$row["elementsVerticalSpacing"].',
				verticalAdjustment:'.$row["verticalAdjustment"].',
				animationTime:'.$row["animationTime"].',
				easing:"'.$row["easing"].'",
				resizeImages:'.$row["resizeImages"].',
				target:"'.$row["target"].'",
				showElementTitle:'.$row["showElementTitle"].',
				showAllControllers:'.$row["showAllControllers"].',
				showNavArrows:'.$row["showNavArrows"].',
				showOnInitNavArrows:'.$row["showOnInitNavArrows"].',
				autoHideNavArrows:'.$row["autoHideNavArrows"].',
				showBottomNav:'.$row["showBottomNav"].',
				showOnInitBottomNav:'.$row["showOnInitBottomNav"].',
				autoHideBottomNav:'.$row["autoHideBottomNav"].',
				showPreviewThumbs:'.$row["showPreviewThumbs"].',
				nextPrevMarginTop:'.$row["nextPrevMarginTop"].',
				playMovieMarginTop:'.$row["playMovieMarginTop"].',
				bottomNavMarginBottom:'.$row["bottomNavMarginBottom"].',
				enableTouchScreen:'.$row["enableTouchScreen"].',
				absUrl:"'.plugins_url("", __FILE__).'/carousel/",
				showCircleTimer:'.$row["showCircleTimer"].',
				showCircleTimerIE8IE7:'.$row["showCircleTimerIE8IE7"].',
				circleRadius:'.$row["circleRadius"].',
				circleLineWidth:'.$row["circleLineWidth"].',
				circleColor:"#'.$row["circleColor"].'",
				circleAlpha:'.$row["circleAlpha"].',
				behindCircleColor:"#'.$row["behindCircleColor"].'",
				behindCircleAlpha:'.$row["behindCircleAlpha"].',
				responsive:'.$row["responsive"].',
				responsiveRelativeToBrowser:'.$row["responsiveRelativeToBrowser"].',
				circleLeftPositionCorrection:'.$row["circleLeftPositionCorrection"].',
				circleTopPositionCorrection:'.$row["circleTopPositionCorrection"].'				
			});	
		});
	</script>	
            <div id="allinone_carousel_'.$row["id"].'"><div class="myloader"></div><ul class="allinone_carousel_list">'.$playlist_str.''.$text_str.'</ul></div>';
}



register_activation_hook(__FILE__,"all_in_one_carousel_activate"); //activate plugin and create the database
register_uninstall_hook(__FILE__, 'all_in_one_carousel_uninstall'); // on unistall delete all databases 
add_action('init', 'all_in_one_carousel_init_sessions');	// initialize sessions
add_action('init', 'all_in_one_carousel_load_styles');	// loads required styles
add_action('init', 'all_in_one_carousel_load_scripts');			// loads required scripts  
add_action('admin_menu', 'all_in_one_carousel_plugin_menu'); // create menus
add_shortcode('all_in_one_carousel', 'all_in_one_carousel_shortcode');				// ALLINONE-CAROUSEL shortcode 









/** OTHER FUNCTIONS **/

//stripslashes for an entire array
function all_in_one_carousel_unstrip_array($array){
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











/* ajax update playlist record */

add_action('admin_head', 'all_in_one_carousel_update_playlist_record_javascript');

function all_in_one_carousel_update_playlist_record_javascript() {
	global $wpdb;
	//Set Your Nonce
	$all_in_one_carousel_update_playlist_record_ajax_nonce = wp_create_nonce("all_in_one_carousel_update_playlist_record-special-string");
	$all_in_one_carousel_add_text_record_ajax_nonce = wp_create_nonce("all_in_one_carousel_add_text_record-special-string");
	$all_in_one_carousel_delete_text_record_ajax_nonce = wp_create_nonce("all_in_one_carousel_delete_text_record-special-string");
?>




<script type="text/javascript" >
//delete the entire record
function all_in_one_carousel_delete_entire_record (delete_id) {
	jQuery("#all_in_one_carousel_sortable").sortable('disable');
	jQuery("#"+delete_id).css("display","none");
	//jQuery("#all_in_one_carousel_sortable").sortable('refresh');
	jQuery("#all_in_one_carousel_updating_witness").css("display","block");
	var data = "action=all_in_one_carousel_update_playlist_record&security=<?php echo $all_in_one_carousel_update_playlist_record_ajax_nonce; ?>&updateType=all_in_one_carousel_delete_entire_record&delete_id="+delete_id;
	// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
	jQuery.post(ajaxurl, data, function(response) {
		jQuery("#all_in_one_carousel_sortable").sortable('enable');
		jQuery("#all_in_one_carousel_updating_witness").css("display","none");
		//alert('Got this from the server: ' + response);
	});		
}


function all_in_one_carousel_open_dialog(ord) {
	jQuery('#dialog'+ord).dialog({
		minWidth: 0.8*document.body.offsetWidth,
		minHeight: 500, position: [180,70],
		modal:true,
		zIndex: 100000,
		close: function(event, ui) {
			 jQuery(this).dialog('destroy'); 
			 jQuery(this).appendTo('#form-playlist-all_in_one_carousel-'+ord);
		} 
	});
}


function all_in_one_carousel_add_text_line(photoid) {
	var data ="action=all_in_one_carousel_add_text_record&security=<?php echo $all_in_one_carousel_add_text_record_ajax_nonce; ?>&photoid="+photoid;

	// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
	jQuery.post(ajaxurl, data, function(response) {
		//alert('Got this from the server: ' + response);

		//var randNo=Math.floor(Math.random()*10000);
		//var textID=response;
		var textID=parseInt(response,10);
		jQuery("#photo_div"+photoid).append('<div id="draggable'+textID+'" class="my_draggable"><h2>&nbsp;</h2><textarea name="content'+textID+'" id="content'+textID+'" cols="30" rows="1">Text Here</textarea></div>');
		jQuery("#draggable"+textID).draggable( { 
			handle: 'h2',
			start: function(event, ui) {
				jQuery('#text_line_settings'+textID).css('background','#cccccc');
			},
			stop: function(event, ui) {
				jQuery('#text_line_settings'+textID).css('background','#ffffff');
			},
			drag: function(event, ui) { 
				jQuery('#data-initial-left'+textID).val(all_in_one_carousel_process_val(jQuery(this).css('left'),'left'));
				jQuery('#data-initial-top'+textID).val(all_in_one_carousel_process_val(jQuery(this).css('top'),'top'));
			}
		});

		var div_data='<div class="text_line_settings" id="text_line_settings'+textID+'">';
			div_data+='<table width="100%" border="0">';
			div_data+='<tr>';
			div_data+='<td>Initial Left:</td>';
			div_data+='<td><input name="data-initial-left'+textID+'" type="text" id="data-initial-left'+textID+'" size="10" value="0" /> px</td>';
			div_data+='<td>Initial Top:</td>';
			div_data+='<td><input name="data-initial-top'+textID+'" type="text" id="data-initial-top'+textID+'" size="10" value="0" /> px</td>';
			div_data+='<td>Final Left:</td>';
			div_data+='<td><input name="data-final-left'+textID+'" type="text" id="data-final-left'+textID+'" size="10" value="0" /> px</td>';
			div_data+='<td>Final Top:</td>';
			div_data+='<td><input name="data-final-top'+textID+'" type="text" id="data-final-top'+textID+'" size="10" value="0" /> px</td>';
			div_data+='</tr>';
			div_data+='<tr>';
			div_data+='<td>Duration:</td>';
			div_data+='<td><input name="data-duration'+textID+'" type="text" id="data-duration'+textID+'" size="10" value="0" /> s</td>';
			div_data+='<td>Initial Opacity:</td>';
			div_data+='<td><input name="data-fade-start'+textID+'" type="text" id="data-fade-start'+textID+'" size="10" value="0" /> (Value between 0-100)</td>';
			div_data+='<td>Delay:</td>';
			div_data+='<td><input name="data-delay'+textID+'" type="text" id="data-delay'+textID+'" size="10" value="0" /> s</td>';
			div_data+='<td>CSS Styles</td>';
			div_data+='<td><textarea name="css'+textID+'" id="css'+textID+'" cols="30" rows="3"></textarea></td>';
			div_data+='</tr>';
			div_data+='<tr>';
			div_data+='<td colspan="8"><div class="delete_text" onclick="all_in_one_carousel_delete_text_line('+textID+')">&nbsp;</div></td>';
			div_data+='</tr>';
			div_data+='</table>';
			div_data+='</div>';
	    	
		jQuery("#photo_div"+photoid).append(div_data);		
	});


}


function all_in_one_carousel_delete_text_line(textid) {
	jQuery('#text_line_settings'+textid).remove();
	jQuery('#draggable'+textid).draggable( "destroy" );
	jQuery('#draggable'+textid).remove();
	
	var data ="action=all_in_one_carousel_delete_text_record&security=<?php echo $all_in_one_carousel_delete_text_record_ajax_nonce; ?>&textid="+textid;

	// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
	jQuery.post(ajaxurl, data, function(response) {
		//alert ("ok");
	});
}



function all_in_one_carousel_process_val(val,cssprop) {
	retVal=parseInt(val.substring(0, val.length-2))-50;
	if (cssprop=="top")
		retVal=retVal+32;
	return retVal;
}


jQuery(document).ready(function($) {
	if (jQuery('#all_in_one_carousel_sortable').length) {
		jQuery( '#all_in_one_carousel_sortable' ).sortable({
			placeholder: "ui-state-highlight",
			start: function(event, ui) {
	            ord_start = ui.item.prevAll().length + 1;
	        },
			update: function(event, ui) {
	        	jQuery("#all_in_one_carousel_sortable").sortable('disable');
	        	jQuery("#all_in_one_carousel_updating_witness").css("display","block");
				var ord_stop=ui.item.prevAll().length + 1;
				var elem_id=ui.item.attr("id");
				//alert (ui.item.attr("id"));
				//alert (ord_start+' --- '+ord_stop);
				var data = "action=all_in_one_carousel_update_playlist_record&security=<?php echo $all_in_one_carousel_update_playlist_record_ajax_nonce; ?>&updateType=change_ord&ord_start="+ord_start+"&ord_stop="+ord_stop+"&elem_id="+elem_id;
				// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
				jQuery.post(ajaxurl, data, function(response) {
					jQuery("#all_in_one_carousel_sortable").sortable('enable');
					jQuery("#all_in_one_carousel_updating_witness").css("display","none");
					//alert('Got this from the server: ' + response);
				});			
			}
		});
	}


	

	
	<?php 
		$rows_count = $wpdb->get_var( "SELECT COUNT(*) FROM ". $wpdb->prefix . "all_in_one_carousel_playlist;");
		for ($i=1;$i<=$rows_count;$i++) {
	?>

	


		jQuery('#upload_img_button_carousel_<?php echo $i?>').click(function() {
		 formfield = 'img';
		 the_i=<?php echo $i?>;
		 tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
		 return false;
		});

		jQuery('#upload_thumbnail_button_carousel_<?php echo $i?>').click(function() {
		 formfield = 'thumbnail';
		 the_i=<?php echo $i?>;
		 tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
		 return false;
		});
		 


	

	jQuery("#form-playlist-all_in_one_carousel-<?php echo $i?>").submit(function(event) {

		/* stop form from submitting normally */
		event.preventDefault(); 
		
		//show loading image
		jQuery('#ajax-message-<?php echo $i?>').html('<img src="<?php echo plugins_url('all_in_one_carousel/images/ajax-loader.gif', dirname(__FILE__))?>" />');

		//alert (jQuery('#data-initial-left24').val());
		//var data = {
			//action: 'all_in_one_carousel_update_playlist_record',
			//security: '<?php echo $all_in_one_carousel_update_playlist_record_ajax_nonce; ?>',
			//whatever: 1234
		//};
		var data ="action=all_in_one_carousel_update_playlist_record&security=<?php echo $all_in_one_carousel_update_playlist_record_ajax_nonce; ?>&"+jQuery("#form-playlist-all_in_one_carousel-<?php echo $i?>").serialize();

		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(ajaxurl, data, function(response) {
			//alert('Got this from the server: ' + response);
			//alert(jQuery("#form-playlist-all_in_one_carousel-<?php echo $i?>").serialize());
			var new_img = '';
			if (document.forms["form-playlist-all_in_one_carousel-<?php echo $i?>"].img.value!='')
				new_img=document.forms["form-playlist-all_in_one_carousel-<?php echo $i?>"].img.value;
			jQuery('#top_image_'+document.forms["form-playlist-all_in_one_carousel-<?php echo $i?>"].id.value).attr('src',new_img);
			jQuery('#ajax-message-<?php echo $i?>').html(response);
		});
	});
	<?php } ?>
	
});
</script>
<?php
}

//all_in_one_carousel_update_playlist_record is the action=all_in_one_carousel_update_playlist_record

add_action('wp_ajax_all_in_one_carousel_update_playlist_record', 'all_in_one_carousel_update_playlist_record_callback');

function all_in_one_carousel_update_playlist_record_callback() {
	
	check_ajax_referer( 'all_in_one_carousel_update_playlist_record-special-string', 'security' ); //security=<?php echo $all_in_one_carousel_update_playlist_record_ajax_nonce; 
	global $wpdb;
	global $all_in_one_carousel_messages;
	$errors_arr=array();
	//$wpdb->show_errors();
	
	//delete entire record
	if ($_POST['updateType']=='all_in_one_carousel_delete_entire_record') {
		$delete_id=$_POST['delete_id'];
		$safe_sql=$wpdb->prepare("SELECT * FROM ".$wpdb->prefix."all_in_one_carousel_playlist WHERE id = %d",$delete_id);
		$row = $wpdb->get_row($safe_sql, ARRAY_A);
		$row=all_in_one_carousel_unstrip_array($row);

		//delete the entire record
		$wpdb->query($wpdb->prepare("DELETE FROM ".$wpdb->prefix."all_in_one_carousel_playlist WHERE id = %d",$delete_id));
		//delete texts
		$wpdb->query($wpdb->prepare("DELETE FROM ".$wpdb->prefix."all_in_one_carousel_texts WHERE photoid = %d",$delete_id));
		//update the order for the rest ord=ord-1 for > ord
		$wpdb->query($wpdb->prepare("UPDATE ".$wpdb->prefix."all_in_one_carousel_playlist SET ord=ord-1 WHERE carouselid = %d and  ord>".$row['ord'],$_SESSION['xid']));
	}

	//update elements order
	if ($_POST['updateType']=='change_ord') {
		$sql_arr=array();
		$ord_start=$_POST['ord_start'];
		$ord_stop=$_POST['ord_stop'];
		$elem_id=(int)$_POST['elem_id'];
		$ord_direction='+1';
		if ($ord_start<$ord_stop) 
			$sql_arr[]="UPDATE ".$wpdb->prefix."all_in_one_carousel_playlist SET ord=ord-1  WHERE carouselid = ".$_SESSION['xid']." and ord>".$ord_start." and ord<=".$ord_stop;
		else
			$sql_arr[]="UPDATE ".$wpdb->prefix."all_in_one_carousel_playlist SET ord=ord+1  WHERE carouselid = ".$_SESSION['xid']." and ord>=".$ord_stop." and ord<".$ord_start;
		$sql_arr[]="UPDATE ".$wpdb->prefix."all_in_one_carousel_playlist SET ord=".$ord_stop." WHERE id=".$elem_id;		
		
		//echo "elem_id: ".$elem_id."----ord_start: ".$ord_start."----ord_stop: ".$ord_stop;
		foreach ($sql_arr as $sql)
			$wpdb->query($sql);
	}
	
	
	
	//submit update
	/*if (empty($_POST['img']))
			 $errors_arr[]=$all_in_one_carousel_messages['empty_img'];*/

	
	$theid=isset($_POST['id'])?$_POST['id']:0;
	if($theid>0 && !count($errors_arr)) {
		/*$except_arr=array('Submit'.$theid,'id','ord','action','security','updateType','uniqueUploadifyID');
		foreach ($_POST as $key=>$val){
			if (in_array($key,$except_arr)) {
				unset($_POST[$key]);
			}
		}*/
		//update playlist
		$wpdb->update( 
			$wpdb->prefix .'all_in_one_carousel_playlist',
				array( 
				'img' => $_POST['img'],
				'thumbnail' => $_POST['thumbnail'],
				'data-title' => $_POST['data-title'],
				'data-video' => $_POST['data-video'],
				'data-link' => $_POST['data-link'],
				'data-target' => $_POST['data-target'],				
				'content' => $_POST['content']
				), 
			array( 'id' => $theid )
		);
		
		//update texts
		$safe_sql=$wpdb->prepare( "SELECT * FROM (".$wpdb->prefix ."all_in_one_carousel_texts) WHERE photoid = %d ORDER BY id",$theid );
		$result_text = $wpdb->get_results($safe_sql,ARRAY_A);
		
		foreach ( $result_text as $row_text ) {
			$textid=$row_text['id'];
			$wpdb->update( 
				$wpdb->prefix .'all_in_one_carousel_texts',
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
		}

		?>
			<div id="message" class="updated"><p><?php echo $all_in_one_carousel_messages['data_saved'];?></p></div>
	<?php 
	} else if (!isset($_POST['updateType'])) {
		$errors_arr[]=$all_in_one_carousel_messages['invalid_request'];
	}
    //echo $theid;
    
	if (count($errors_arr)) { ?>
		<div id="error" class="error"><p><?php echo implode("<br>", $errors_arr);?></p></div>
	<?php }

	die(); // this is required to return a proper result
}




add_action('wp_ajax_all_in_one_carousel_add_text_record', 'all_in_one_carousel_add_text_record_callback');

function all_in_one_carousel_add_text_record_callback() {
	
	check_ajax_referer( 'all_in_one_carousel_add_text_record-special-string', 'security' ); //security=<?php echo $all_in_one_carousel_update_playlist_record_ajax_nonce; 
	global $wpdb;
	//$wpdb->show_errors();
	
	$wpdb->insert( 
			$wpdb->prefix . "all_in_one_carousel_texts", 
			array( 
				'photoid' => $_POST['photoid'],
				'data-initial-left' => 0,
				'data-initial-top' => 0,
				'data-final-left' => 0,
				'data-final-top' => 0,
				'data-duration' => 0,
				'data-fade-start' => 0,
				'data-delay' => 0
			), 
			array( 
				'%d', 
				'%d',
				'%d',
				'%d',
				'%d',
				'%f',
				'%d',
				'%f'
			) 
		);

		echo $wpdb->insert_id;
		
		die(); // this is required to return a proper result
}




add_action('wp_ajax_all_in_one_carousel_delete_text_record', 'all_in_one_carousel_delete_text_record_callback');

function all_in_one_carousel_delete_text_record_callback() {
	
	check_ajax_referer( 'all_in_one_carousel_delete_text_record-special-string', 'security' ); //security=<?php echo $all_in_one_carousel_update_playlist_record_ajax_nonce; 
	global $wpdb;
	//$wpdb->show_errors();
	
	
	$wpdb->query(
	"
	DELETE FROM ".$wpdb->prefix ."all_in_one_carousel_texts
	WHERE id = ".$_POST['textid']."
	"
	);

		
	die(); // this is required to return a proper result
}


?>