<?php

$active_shortcode = isset($_REQUEST['code']) ? $_REQUEST['code'] : 'hello-followers';


$scg = new HelloFollowersCodeGenerator();
$scg->activate($active_shortcode);

?>



		<div class="hf-shortcode-container hf-shortcode-select">
			<div class="hf-shortcode-title" style="margin-top: 20px;">Choose shortcode</div>
			<a href="<?php echo esc_url(add_query_arg ( 'code', 'hello-followers', 'admin.php?page=hellofollowers&tab=shortcode' ));?>" class="hf-shortcode hf-shortcode-fixed">[hello-followers]<br/><span class="hf-shortcode-comment">Display followers counter</span></a>
			<a href="<?php echo esc_url(add_query_arg ( 'code', 'hello-total-followers', 'admin.php?page=hellofollowers&tab=shortcode' ));?>" class="hf-shortcode hf-shortcode-fixed">[hello-total-followers]<br/><span class="hf-shortcode-comment">Display total followers counter</span></a>
			
			</div>

		<?php 
		
		$cmd = isset($_REQUEST['cmd']) ? $_REQUEST['cmd'] : '';
		
		if ($cmd == 'generate') {
			$options = isset($_REQUEST[$scg->optionsGroup]) ? $_REQUEST[$scg->optionsGroup]: array();
			
			echo '<div class="hf-shortcode-container">';
			
			$scg->generate($options);
			
			echo '</div>';
		}
		
		?>
	
		<form name="general_form" method="post"
		action="admin.php?page=hellofollowers&tab=shortcode">
		<input type="hidden" id="cmd" name="cmd" value="generate" />
		<input type="hidden" id="code" name="code" value="<?php echo $active_shortcode; ?>"/>
 			<?php wp_nonce_field('essb'); ?>
			<div class="hf-options">
			<div class="hf-options-header" id="hf-options-header">
				<div class="hf-options-title">
					Shortcode Generator
				</div>		
		<?php echo '<input type="Submit" name="Submit" value="' . __ ( 'Generate Shortcode', ESSB3_TEXT_DOMAIN ) . '" class="button-primary" />'; ?>
	</div>
			<div class="hf-options-sidebar">
				<ul class="hf-options-group-menu">
					<?php 
					$scg->renderNavigation();
					?>
				</ul>
			</div>
			<div class="hf-options-container">
				<div id="hf-container-1" class="hf-data-container">

				<?php 
				
				$scg->render();
				
				?>

				</div>
			</div>
		</div>
	</form>

