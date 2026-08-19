<script type='text/javascript'>
jQuery(document).ready(function($){	
	$('#slider').lofJSidernews({
		interval:6000,
		easing:'easeInOutQuad',
		duration:1200,
		mainWidth: 643,
		navigatorWidth: 315,
		auto:true,
		isPreloaded: false
	});
});	

</script>		
	

<!-- BEGIN WRAPPER-FEATURED -->
<div id="wrapper-featured">
	<div id="wrapper-slides">
		
		<!-- SLIDER CONTENT -->
		<div id="slider" class="lof-main-wapper">
		 <!-- MAIN CONTENT --> 
		  <div class="slider-main-outer">
			<ul class="slider-main-wapper">
			<?php
			global $options;
			
			if($options['featured_numbers']!=''){
				$fp_numbers = $options['featured_numbers'];
			}else{
				$fp_numbers = 5;
			}
			$post_types = get_post_types();
			unset($post_types['page'], $post_types['attachment'], $post_types['revision'], $post_types['nav_menu_item']);
			
			if($options['featured_source']=='fs_posts'){
				$featured_posts = new WP_Query(array(
					'post__in' => explode(',',$options['featured_source_postsID']),
					'showposts' => $fp_numbers
				));
				
			}else if($options['featured_source']=='fs_category'){
				$featured_posts = new WP_Query(array(
					'cat' => $options['featured_source_category'],
					'post_type' => $post_types,
					'showposts' => $fp_numbers
				));
			}else if($options['featured_source']=='fs_tags'){
				$featured_posts = new WP_Query(array(
					'post_type' => $post_types,
					'showposts' => $fp_numbers,
					'tag' => trim($options['featured_source_tags'])
				));
			}else{
				$featured_posts = new WP_Query(array(
					'post_type' => $post_types,
					'showposts' => $fp_numbers
				));
			}
			$slider_navigator = '';
			?>
			<?php while($featured_posts->have_posts()): $featured_posts->the_post(); ?>
			<li style="position:relative">
				<?php if(has_post_thumbnail()): ?>
					<?php $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), '638x300'); ?>
					<?php $image_thumb = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), '60x60'); ?>
					<a href="<?php the_permalink(); ?>" title="<?php printf( __( 'Permalink to %s', 'IziThemes' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><img src="<?php echo $image[0]; ?>" width="638" height="300" alt="<?php the_title_attribute( 'echo=0' ); ?>" /></a>
				<?php else: ?>
					<a href="<?php the_permalink(); ?>" title="<?php printf( __( 'Permalink to %s', 'IziThemes' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><img src="<?php bloginfo('template_directory');?>/images/thumb.png" width="638" height="300" alt="<?php the_title_attribute( 'echo=0' ); ?>" />
					<?php $image_thumb[0] = get_bloginfo('template_directory').'/images/thumb-small.png';?></a>
				<?php endif; ?>
				         
				<div class="slider-main-item-desc"><h3><a href="<?php the_permalink(); ?>" title="<?php printf( __( 'Permalink to %s', 'IziThemes' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php echo text_trim(get_the_title(),7,'...'); ?></a></h3>
					<?php echo '<p>' . text_trim(get_the_excerpt(),25,'...') . '</p>';?>
				 </div>
			</li> 
			<?php 
			$slider_navigator .= '<li><div><img src="'.$image_thumb[0].'" width="60" height="60" /><h3>'.text_trim(get_the_title(),8,'...').'</h3>
	<span>'.get_the_time('M j, Y').'</span></div></li>'; 
			?>
			<?php endwhile; ?>
			<?php wp_reset_query(); ?>		
			  </ul>  	
		  </div>
		  <!-- END SLIDER CONTENT --> 
		 
		  <!-- NAVIGATOR -->
		  <div class="slider-navigator-outer">
				<ul class="slider-navigator">
					<?php echo $slider_navigator;?>
				</ul>
		 </div>
		 </div> 
		<!-- END OF THE SLIDER CONTENT -->
	</div>
</div>
<!-- END WRAPPER-FEATURED -->