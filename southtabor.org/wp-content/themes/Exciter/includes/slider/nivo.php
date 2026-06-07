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
$htmltitle = '';
?>
<div class="slider-wrapper">
	<div id="nivoslider" class="nivoSlider">
		<?php while($featured_posts->have_posts()): $featured_posts->the_post(); ?>
			<?php if(has_post_thumbnail()): ?>
				<?php $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), '612x300'); ?>
				<a href="<?php the_permalink(get_the_ID());?>"><img src="<?php echo $image[0];?>" title="#sliderCaption<?php the_ID();?>" alt="<?php the_title_attribute();?>" width="612" height="300" /></a> 
			<?php else:?>
			<a href="<?php the_permalink(get_the_ID());?>"><img src="<?php bloginfo('template_url');?>/images/thumb.png" title="#sliderCaption<?php the_ID();?>" alt="<?php the_title_attribute();?>" width="612" height="300" /></a> 
			<?php endif; ?>
			<?php
			$htmltitle .='<div id="sliderCaption'.get_the_ID().'" class="nivo-html-caption">';
			$htmltitle .='<h2><a href="'.get_permalink(get_the_ID()).'" title="'.the_title_attribute( 'echo=0').'">'.text_trim(get_the_title(),7,'...').'</a></h2>';
			$htmltitle .= text_trim(get_the_excerpt(),25,'...');
			$htmltitle .='</div>';
			?>
		<?php endwhile; ?>
		<?php wp_reset_query(); ?>		
	</div>
	<?php
	echo $htmltitle;?>
</div>
<script type="text/javascript">
jQuery(document).ready(function($){
	$("#nivoslider").nivoSlider({
		effect:"sliceDown",
		opacity:1, 
		slices:1,
		boxCols:8,
		boxRows:8,
		animSpeed:500,
		pauseTime:5000,
		startSlide:0,
		directionNav:true,
		directionNavHide:true,
		controlNav:false,
		controlNavThumbs:false,
		controlNavThumbsFromRel:true,
		keyboardNav:true,
		pauseOnHover:true,
		manualAdvance:false,
		afterLoad: function(){
					$(".nivo-caption").css("bottom","0px");
				},
				beforeChange: function(){
					$(".nivo-caption").stop().animate({bottom:"-40px"}, {easing:"easeInBack"});
				},
				afterChange: function(){
					$(".nivo-caption").stop().animate({bottom:"0px"}, {easing:"easeOutBack"});
				}
				
	});
});
</script>