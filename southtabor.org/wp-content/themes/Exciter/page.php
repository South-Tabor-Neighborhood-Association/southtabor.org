<?php get_header(); ?>

			<!-- BEGIN MAIN -->
			<div id="main">
			
				<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
				
				<div class="post-heading page">
					<h1><?php the_title(); ?></h1>
				</div>
				
				<div class="post-entry">
					<?php the_content(); ?>
					<?php wp_link_pages(); ?>
					<?php edit_post_link(__('Edit this page','IziThemes')); ?>
					
					<div class="clear"></div>

				</div>
				
				<?php endwhile; endif; ?>
				<?php
				if(get_post_meta($post->ID, 'izi_comment', true) == 'enable') {?>
				<br />
				<div id="comments">
					<?php comments_template(); ?>
				</div>
				<?php } ?>
			
			</div>
			<!-- END MAIN -->
			
<?php get_sidebar(); ?>
			
<?php get_footer(); ?>