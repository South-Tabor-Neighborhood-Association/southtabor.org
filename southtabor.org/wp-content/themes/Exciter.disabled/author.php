<?php get_header(); ?>
			<!-- BEGIN MAIN -->
			<div id="main">
				<div class="block archive">
					
					<?php if (have_posts()): the_post(); ?>
					<h3>
						<?php _e('All posts by', 'IziThemes'); ?> <?php echo get_the_author(); ?>
					</h3>
					
					<?php get_template_part( 'includes/loop','author' ) ?> 
					<?php endif;?>
				</div>
			</div>
			<!-- END MAIN -->
<?php get_sidebar(); ?>
<?php get_footer(); ?>