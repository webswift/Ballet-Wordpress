<?php


global $hf_navigation_tabs, $hf_sidebar_sections, $hf_section_options;

$tab_1 = 'social';

global $current_tab;
$current_tab = (empty ( $_GET ['tab'] )) ? $tab_1 : sanitize_text_field ( urldecode ( $_GET ['tab'] ) );


$tabs = $hf_navigation_tabs;
$section = $hf_sidebar_sections [$current_tab];
$options = $hf_section_options [$current_tab];

?>
<div class="wrap">

	<div class="hf-title-panel">
	
	
	<?php 
		
	?>
		
	<div class="hf-title-panel-buttons">
	<?php echo '<a href="http://support.creoworx.com" target="_blank" text="' . __ ( 'Need Help? Click here to visit our support center', ESSB3_TEXT_DOMAIN ) . '" class="button float_right"><i class="fa fa-question"></i>&nbsp;' . __ ( 'Support Center', ESSB3_TEXT_DOMAIN ) . '</a>'; ?>
	<?php echo '<a href="http://bit.ly/essb3docs" target="_blank" text="' . __ ( 'Plugin Documentation', ESSB3_TEXT_DOMAIN ) . '" class="button float_right" style="margin-right: 5px;"><i class="fa fa-book"></i>&nbsp;' . __ ( 'Documentation', ESSB3_TEXT_DOMAIN ) . '</a>'; ?>
	</div>
	<div class="hf-title-panel-inner">
	
	<h3>Hello Followers - Social Counter Plugin for WordPress</h3>
		<p>
			Version <strong><?php echo HF_VERSION;?></strong>. 
		</p>
		</div>
	</div>


	<div class="hf-tabs">

		<ul>
    <?php
				$is_first = true;
				foreach ( $tabs as $name => $label ) {
					$tab_sections = isset ( $hf_sidebar_sections [$name] ) ? $hf_sidebar_sections [$name] : array ();
					$hidden_tab = isset ( $tab_sections ['hide_in_navigation'] ) ? $tab_sections ['hide_in_navigation'] : false;
					if ($hidden_tab) {
						continue;
					}
					
					$options_handler = 'hellofollowers';
					echo '<li><a href="' . admin_url ( 'admin.php?page=' . $options_handler . '&tab=' . $name ) . '" class="hf-nav-tab ';
					if ($current_tab == $name)
						echo 'active';
					echo '">' . $label . '</a></li>';
					$is_first = false;
				}
				
				?>
    </ul>

	</div>
	<div class="hf-clear"></div>
	
	<?php
	
		if ($current_tab != 'shortcode') {
	
			HelloFollowersOptionsInterface::draw_form_start ();
			
			HelloFollowersOptionsInterface::draw_header ( $section ['title'], $section ['hide_update_button'], $section ['wizard_tab'] );
			HelloFollowersOptionsInterface::draw_sidebar ( $section ['fields'] );
			HelloFollowersOptionsInterface::draw_content ( $options );
			
			HelloFollowersOptionsInterface::draw_form_end ();
			
			HelloFollowersOptionsFramework::register_color_selector ();
		}
		else {
			include_once HF_PLUGIN_ROOT. 'lib/admin/hf-shortcode-generator.php';
		}
		
		?>

	
</div>