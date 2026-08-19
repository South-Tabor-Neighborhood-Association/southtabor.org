<?php get_header();?>
		<?php 
		if($options['slider_type']=='LofjSlider'){
			include(TEMPLATEPATH . '/includes/slider/lof.php');
		}
		?> 
		
		<!-- BEGIN MAIN -->
		<div id="main">	
			<?php 
			if($options['slider_type']=='Nivo'){
				include(TEMPLATEPATH . '/includes/slider/nivo.php');
			}
			?>	
			<?php if($options['homepage_style']=='magazine'){?>
			<!-- BEGIN HOMEPAGE BLOCKS -->
			<?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('Homepage')): ?>
			<?php endif; ?>
			<!-- END HOMEPAGE BLOCKS -->
			<?php } else {?>
			<div class="block archive">
			<h3><?php _e('Latest Posts','IziThemes');?></h3>
			<?php get_template_part( 'includes/loop','index' ); ?>
			</div>	
			<?php } ?> 
				
		</div>
		<!-- END MAIN -->
		
<?php get_sidebar();?>	
<?php get_footer();?>