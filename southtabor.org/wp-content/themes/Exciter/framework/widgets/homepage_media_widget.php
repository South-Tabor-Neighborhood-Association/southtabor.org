<?php
add_action('widgets_init', 'homepage_media_load_widgets');

function homepage_media_load_widgets()
{
	register_widget('Homepage_Media_Widget');
}

class Homepage_Media_Widget extends WP_Widget {
	
	function Homepage_Media_Widget()
	{
		$widget_ops = array('classname' => 'wrapper-carousel', 'description' => 'Homepage media slider widget.');

		$control_ops = array('id_base' => 'izi_homepage_media-widget');

		$this->WP_Widget('izi_homepage_media-widget', '&radic; Izithemes: Home Carousel', $widget_ops, $control_ops);
	}
	
	function widget($args, $instance)
	{	
		global $options;
		extract($args);
		
		$title = $instance['title'];
		$post_type = $instance['post_type'];
		$categories = $instance['categories'];
		$posts = $instance['posts'];
		
		echo $before_widget;
		?>
		<?php
		$recent_posts = new WP_Query(array(
			'showposts' => $posts,
			'post_type' => 'post',
			'cat' => $categories,
		));
		if($recent_posts->found_posts >= 4):
		?>
		<script type="text/javascript">
		jQuery(document).ready(function($){	

			// Implementation of jCarouselLite
			$('.anyClass').each(function() {
				var id = $(this).attr('id');
				var number = id.replace('anyClass_', '');
				$('#'+id).jCarouselLite({
					btnNext: "#next_"+number,
					btnPrev: "#prev_"+number,
					visible: 4,
					scroll: 2
				});
			});
		});
		</script>
		
		<div class="block large">
			
			<?php if($categories!='all'){?>
			<h3><a href="<?php echo get_category_link( $categories ); ?>"><?php echo get_the_category_by_ID( $categories); ?></a> <?php if($show_rss == 'true'): ?><a href="<?php echo get_category_feed_link( $categories, 'rss2' ); ?>" class="rss"></a><?php endif; ?></h3>
			<?php }else {?>
			<h3><?php echo $title; ?> </h3>
			<?php } ?>
			
				<div class="anyClass" id="anyClass_<?php echo $this->number; ?>">
					<ul>
						<?php
						if($categories != 'all') {
							$categories_array = array($categories);
						}
						?>
						
						<?php while($recent_posts->have_posts()): $recent_posts->the_post(); ?>
						<li><div class="block-image"><?php if(has_post_thumbnail()): ?>
						
						<?php $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), '130x90'); ?>
						<a href='<?php the_permalink(); ?>' title='<?php the_title(); ?>'><img src="<?php echo $image[0]; ?>" alt="<?php the_title_attribute(); ?>"  width='130' height='90' /></a>
						<?php else: ?>
						<a href='<?php the_permalink(); ?>' title='<?php the_title_attribute(); ?>'><img src="<?php bloginfo('template_directory'); ?>/images/thumb-small.png"  width='130' height='90' alt="<?php the_title_attribute(); ?>" /></a><?php endif; ?>
						</div></li>
						<?php endwhile; ?>
					</ul>
				</div>
				
				<a class="prev" id="prev_<?php echo $this->number; ?>" href="#"><span>prev</span></a>
				<a class="next" id="next_<?php echo $this->number; ?>" href="#"><span>next</span></a>

			</div>
		<?php endif; ?>
		<?php
		echo $after_widget;
	}
	
	function update($new_instance, $old_instance)
	{
		$instance = $old_instance;
		
		$instance['title'] = $new_instance['title'];
		$instance['post_type'] = $new_instance['post_type'];
		$instance['categories'] = $new_instance['categories'];
		$instance['posts'] = $new_instance['posts'];
		
		return $instance;
	}

	function form($instance)
	{
		$defaults = array('title' => 'Media', 'post_type' => 'all', 'categories' => 'all', 'posts' => 8);
		$instance = wp_parse_args((array) $instance, $defaults); ?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>">Title: <em>(all categories)</em></label>
			<input class="widefat" style="width: 216px;" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('categories'); ?>">Filter by Category:</label> 
			<select id="<?php echo $this->get_field_id('categories'); ?>" name="<?php echo $this->get_field_name('categories'); ?>" class="widefat categories" style="width:100%;">
				<option value='all' <?php if ('all' == $instance['categories']) echo 'selected="selected"'; ?>>all categories</option>
				<?php $categories = get_categories('hide_empty=0&depth=1&type=post'); ?>
				<?php foreach($categories as $category) { ?>
				<option value='<?php echo $category->term_id; ?>' <?php if ($category->term_id == $instance['categories']) echo 'selected="selected"'; ?>><?php echo $category->cat_name; ?></option>
				<?php } ?>
			</select>
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('posts'); ?>">Number of posts:</label>
			<input class="widefat" style="width: 30px;" id="<?php echo $this->get_field_id('posts'); ?>" name="<?php echo $this->get_field_name('posts'); ?>" value="<?php echo $instance['posts']; ?>" />
		</p>
	<?php }
}
?>