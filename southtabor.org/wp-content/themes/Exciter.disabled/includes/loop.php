<?php global $options;?>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
<div id="post-<?php the_ID(); ?>" <?php post_class('item_large'); ?>>
	<h2><a href='<?php the_permalink(); ?>' title='<?php the_title(); ?>'><?php the_title(); ?></a></h2>
	<?php if(has_post_thumbnail()): ?>
	<?php $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), '130x90'); ?>
	<div class="block-image"><a href='<?php the_permalink(); ?>' title='<?php the_title(); ?>'><img src="<?php echo $image[0]; ?>" alt="<?php the_title(); ?>"  width='130' height='90' /></a></div>
	<?php endif; ?>
	
	<span class="block-meta">
		<span class="heading-author"><?php the_author_posts_link(); ?></span> 
		<span class="heading-date"><?php the_time('F j, Y'); ?></span>
		<span class="heading-comments"><?php comments_popup_link(__('No Comments &#187;','IziThemes'), __('1 Comment &#187;','IziThemes'), '% '.__('Comments &#187;','IziThemes')); ?></span>
		<?php edit_post_link(__('Edit this post','IziThemes'),' | <span>','</span>'); ?>
	</span>
	<p><?php echo text_trim(get_the_excerpt(), 40,'...'); ?> <a href='<?php the_permalink(); ?>' title='<?php the_title(); ?>'><span class="block-arrows"><?php _e('Read More', 'IziThemes'); ?> &raquo;</span></a></p>
	<div class="clear"></div>
</div>
<?php endwhile; endif; ?>

<?php pagination($pages = '', $range = 2); ?>