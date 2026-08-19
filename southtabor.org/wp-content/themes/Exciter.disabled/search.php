<?php get_header(); ?>
			
			<!-- BEGIN MAIN -->
			<div id="main">
				<div class="block archive">
					<h3><?php _e('Search results for', 'IziThemes'); ?> "<?php echo get_search_query(); ?>"</h3>
					
					<?php get_template_part( 'includes/loop','category' ); ?> 
				
				</div>
			
			</div>
			<!-- END MAIN -->
			
<?php get_sidebar(); ?>
			
<?php get_footer(); ?>