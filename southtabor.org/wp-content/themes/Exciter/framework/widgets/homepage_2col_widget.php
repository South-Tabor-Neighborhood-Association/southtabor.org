<?php
add_action('widgets_init', 'izi_homepage_2col_load_widgets');

function izi_homepage_2col_load_widgets()
{
	register_widget('Homepage_2col_Widget');
}

class Homepage_2col_Widget extends WP_Widget {
	
	function Homepage_2col_Widget()
	{
		$widget_ops = array('classname' => 'izi_homepage_2col', 'description' => 'Homepage 2-column recent posts widget.');

		$control_ops = array('id_base' => 'izi_homepage_2col-widget');

		$this->WP_Widget('izi_homepage_2col-widget', '&radic; IziThemes: Home 2-column', $widget_ops, $control_ops);
	}
	
	function widget($args, $instance)
	{
		global $options;
		extract($args);
		
		$show_excerpt = isset($instance['show_excerpt']) ? 'true' : 'false';
		$show_rss = isset($instance['show_rss']) ? 'true' : 'false';
		
		$title = $instance['title'];
		$post_type = 'all';
		$categories = $instance['categories'];
		$posts = $instance['posts'];
		$images = true;

		$title_2 = $instance['title_2'];
		$post_type_2 = 'all';
		$categories_2 = $instance['categories_2'];
		$posts_2 = $instance['posts_2'];
		$images_2 = true;
		
		echo $before_widget;
		?>
		<div class="block half">
			<?php if($categories!='all'){?>
			<h3><a href="<?php echo get_category_link( $categories ); ?>"><?php echo get_the_category_by_ID( $categories); ?></a> <?php if($show_rss == 'true'): ?><a href="<?php echo get_category_feed_link( $categories, 'rss2' ); ?>" class="rss"></a><?php endif; ?></h3>
			<?php }else {?>
			<h3><?php echo $title; ?> </h3>
			<?php } ?>
			
			<?php
			$recent_posts = new WP_Query(array(
				'showposts' => $posts,
				'post_type' => 'post',
				'cat' => $categories,
			));
			?>
			<?php $counter = 1; while($recent_posts->have_posts()): $recent_posts->the_post(); ?>
			<?php if($counter == 1): ?>
			<div class="item_large">
				<?php if($images && has_post_thumbnail()): ?>
				<?php $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), '292x150'); ?>
				<div class="block-image"><a href='<?php the_permalink(); ?>' title='<?php the_title(); ?>'><img src="<?php echo $image[0]; ?>" alt="<?php the_title(); ?>"  width='292' height='150' /></a></div>
				<?php else: ?>
				<div class="block-image"><a href='<?php the_permalink(); ?>' title='<?php the_title(); ?>'><img src="<?php bloginfo('template_directory'); ?>/images/thumb-small.png" alt="<?php the_title(); ?>"  width='292' height='150' /></a></div>
				<?php endif; ?>
				<h2 class="clear"><a href='<?php the_permalink(); ?>' title='<?php the_title(); ?>'><?php the_title(); ?></a></h2>
				<span class="block-meta"><?php the_time('F j, Y'); ?>, <?php comments_popup_link(); ?></span>
				<?php if($show_excerpt == 'true'): ?><p><?php echo text_trim(get_the_excerpt(), 15, '...'); ?></p><?php endif; ?>
			</div>
			<?php else: ?>
			<?php if($counter == 2){ ?><ul class="arr_list"><?php } ?>
			<li><a href='<?php the_permalink(); ?>' title='<?php the_title(); ?>'><?php the_title(); ?></a></li>
			<?php if($counter == $posts_2){ ?></ul><?php } ?>
			<?php endif; ?>
			<?php $counter++; endwhile; ?>
			
		</div>
		
		
		<div class="block half last">
			<?php if($categories_2!='all'){?>
			<h3><a href="<?php echo get_category_link( $categories_2 ); ?>"><?php echo get_the_category_by_ID( $categories_2); ?></a> <?php if($show_rss == 'true'): ?><a href="<?php echo get_category_feed_link( $categories_2, 'rss2' ); ?>" class="rss"></a><?php endif; ?></h3>
			<?php }else {?>
			<h3><?php echo $title_2; ?> </h3>
			<?php } ?>
			
			<?php
			$recent_posts = new WP_Query(array(
				'showposts' => $posts_2,
				'post_type' => 'post',
				'cat' => $categories_2,
			));
			?>			
			<?php $counter = 1; while($recent_posts->have_posts()): $recent_posts->the_post(); ?>
			<?php if($counter == 1): ?>
			<div class="item_large">
				<?php if($images && has_post_thumbnail()): ?>
				<?php $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), '292x150'); ?>
				<div class="block-image"><a href='<?php the_permalink(); ?>' title='<?php the_title(); ?>'><img src="<?php echo $image[0]; ?>" alt="<?php the_title(); ?>"  width='292' height='150' /></a></div>
				<?php else: ?>
				<div class="block-image"><a href='<?php the_permalink(); ?>' title='<?php the_title(); ?>'><img src="<?php bloginfo('template_directory'); ?>/images/thumb-small.png" alt="<?php the_title(); ?>" width='292' height='150' /></a></div>
				<?php endif; ?>
				<h2 class="clear"><a href='<?php the_permalink(); ?>' title='<?php the_title(); ?>'><?php the_title(); ?></a></h2>
				<span class="block-meta"><?php the_time('F j, Y'); ?>, <?php comments_popup_link(); ?></span>
				<?php if($show_excerpt == 'true'): ?><p><?php echo text_trim(get_the_excerpt(), 15,'...'); ?></p><?php endif; ?>
			</div>
			
			<?php else: ?>
			<?php if($counter == 2){ ?><ul class="arr_list"><?php } ?>
			<li><a href='<?php the_permalink(); ?>' title='<?php the_title(); ?>'><?php the_title(); ?></a></li>
			<?php if($counter == $posts_2){ ?></ul><?php } ?>
			<?php endif; ?>
			
			<?php $counter++; endwhile; ?>
			
		</div>
		<?php
		echo $after_widget;
	}
	
	function update($new_instance, $old_instance)
	{
		$instance = $old_instance;
		
		$instance['show_excerpt'] = $new_instance['show_excerpt'];
		$instance['show_rss'] = $new_instance['show_rss'];
		
		$instance['title'] = $new_instance['title'];
		$instance['post_type'] = 'all';
		$instance['categories'] = $new_instance['categories'];
		$instance['posts'] = $new_instance['posts'];
		$instance['show_images'] = true;
		
		$instance['title_2'] = $new_instance['title_2'];
		$instance['post_type_2'] = 'all';
		$instance['categories_2'] = $new_instance['categories_2'];
		$instance['posts_2'] = $new_instance['posts_2'];
		$instance['show_images_2'] = true;
		
		return $instance;
	}

	function form($instance)
	{
		$defaults = array('show_excerpt' => 'on','show_rss' => 'on', 'title' => 'Recent Posts', 'post_type' => 'all', 'categories' => 'all', 'posts' => 4, 'title_2' => 'Recent Posts', 'post_type_2' => 'all', 'categories_2' => 'all', 'posts_2' => 4);
		$instance = wp_parse_args((array) $instance, $defaults); ?>
		
		<p>
			<input class="checkbox" type="checkbox" <?php checked($instance['show_excerpt'], 'on'); ?> id="<?php echo $this->get_field_id('show_excerpt'); ?>" name="<?php echo $this->get_field_name('show_excerpt'); ?>" /> 
			<label for="<?php echo $this->get_field_id('show_excerpt'); ?>">Show excerpt</label>
		</p>
		
		<p>
			<input class="checkbox" type="checkbox" <?php checked($instance['show_rss'], 'on'); ?> id="<?php echo $this->get_field_id('show_rss'); ?>" name="<?php echo $this->get_field_name('show_rss'); ?>" /> 
			<label for="<?php echo $this->get_field_id('show_rss'); ?>">Show Rss Link</label>
		</p>
		
		<h3>Column One</h3>
		
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
		
		<h3 style='margin-top: 40px;'>Column Two</h3>
		
		<p>
			<label for="<?php echo $this->get_field_id('title_2'); ?>">Title: <em>(all categories)</em></label>
			<input class="widefat" style="width: 216px;" id="<?php echo $this->get_field_id('title_2'); ?>" name="<?php echo $this->get_field_name('title_2'); ?>" value="<?php echo $instance['title_2']; ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('categories_2'); ?>">Filter by Category:</label> 
			<select id="<?php echo $this->get_field_id('categories_2'); ?>" name="<?php echo $this->get_field_name('categories_2'); ?>" class="widefat categories" style="width:100%;">
				<option value='all' <?php if ('all' == $instance['categories_2']) echo 'selected="selected"'; ?>>all categories</option>
				<?php $categories = get_categories('hide_empty=0&depth=1&type=post'); ?>
				<?php foreach($categories as $category) { ?>
				<option value='<?php echo $category->term_id; ?>' <?php if ($category->term_id == $instance['categories_2']) echo 'selected="selected"'; ?>><?php echo $category->cat_name; ?></option>
				<?php } ?>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('posts_2'); ?>">Number of posts:</label>
			<input class="widefat" style="width: 30px;" id="<?php echo $this->get_field_id('posts_2'); ?>" name="<?php echo $this->get_field_name('posts_2'); ?>" value="<?php echo $instance['posts_2']; ?>" />
		</p>
	<?php }
}
?>