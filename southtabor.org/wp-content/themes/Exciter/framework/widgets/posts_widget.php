<?php
add_action('widgets_init', 'posts_load_widgets');

function posts_load_widgets()
{
	register_widget('Posts_Widget');
}

class Posts_Widget extends WP_Widget {
	
	function Posts_Widget()
	{
		$widget_ops = array('classname' => 'izi_posts', 'description' => 'Get latest posts');

		$control_ops = array('id_base' => 'izi_posts-widget');

		$this->WP_Widget('izi_posts-widget', '&radic; IziThemes: Recent Posts', $widget_ops, $control_ops);
	}
	
	function widget($args, $instance)
	{
		global $options;
		extract($args);
		
		$title = $instance['title'];
		$post_type = 'all';
		$categories = $instance['categories'];
		$posts = $instance['posts'];
		$images = true;
		$rating = true;
		$show_excerpt = isset($instance['show_excerpt']) ? 'true' : 'false';
		
		echo $before_widget;
		?>
		<!-- BEGIN WIDGET -->
		<?php
		if($title) {
			echo $before_title.$title.$after_title;
		}
		?>
		
		<?php
		$recent_posts = new WP_Query(array(
			'showposts' => $posts,
			'post_type' => 'post',
			'cat' => $categories,
		));
		?>
					
		<?php while($recent_posts->have_posts()): $recent_posts->the_post(); ?>
		<div class="item_small">
			<?php if($images && has_post_thumbnail()): ?>
			<?php $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), '60x60'); ?>
			<div class="block-image"><a href='<?php the_permalink(); ?>' title='<?php the_title_attribute(); ?>'><img src="<?php echo $image[0]; ?>" alt="<?php the_title_attribute(); ?>"  width='60' height='60' /></a></div>
			<?php else: ?>
			<div class="block-image"><a href='<?php the_permalink(); ?>' title='<?php the_title_attribute(); ?>'><img src="<?php bloginfo('template_directory'); ?>/images/thumb-small.png" alt="<?php the_title_attribute(); ?>"  width='60' height='60' /></a></div>
			<?php endif; ?>
			<h2><a href='<?php the_permalink(); ?>' title='<?php the_title_attribute(); ?>'><?php echo text_trim(get_the_title(),5,'...'); ?></a></h2>
			<span class="block-meta"><?php the_time('F j, Y'); ?>, <?php comments_popup_link(); ?></span>
			<?php if($show_excerpt == 'true'): ?><p><?php echo text_trim(get_the_excerpt(),10,'...'); ?></p><?php endif; ?>
		</div>
		<?php endwhile; ?>
		<!-- END WIDGET -->
		<?php
		echo $after_widget;
	}
	
	function update($new_instance, $old_instance)
	{
		$instance = $old_instance;
		
		$instance['title'] = $new_instance['title'];
		$instance['post_type'] = 'all';
		$instance['categories'] = $new_instance['categories'];
		$instance['posts'] = $new_instance['posts'];
		$instance['show_images'] = true;
		$instance['show_excerpt'] = $new_instance['show_excerpt'];
		
		return $instance;
	}

	function form($instance)
	{
		$defaults = array('title' => 'Recent Posts', 'post_type' => 'all', 'categories' => 'all', 'posts' => 3, 'show_excerpt' => 'on');
		$instance = wp_parse_args((array) $instance, $defaults); ?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>">Title:</label>
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
			<input class="checkbox" type="checkbox" <?php checked($instance['show_excerpt'], 'on'); ?> id="<?php echo $this->get_field_id('show_excerpt'); ?>" name="<?php echo $this->get_field_name('show_excerpt'); ?>" /> 
			<label for="<?php echo $this->get_field_id('show_excerpt'); ?>">Show excerpt</label>
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('posts'); ?>">Number of posts:</label>
			<input class="widefat" style="width: 30px;" id="<?php echo $this->get_field_id('posts'); ?>" name="<?php echo $this->get_field_name('posts'); ?>" value="<?php echo $instance['posts']; ?>" />
		</p>
	<?php }
}

?>