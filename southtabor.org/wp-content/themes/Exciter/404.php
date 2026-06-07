<?php get_header(); ?>

			<!-- BEGIN MAIN -->
			<div id="main">
				
				<div class="post-heading page">
					<h1><?php _e('Page Not Found', 'IziThemes'); ?></h1>
				</div>
				
				<div class="entry404">
					<div class="text404">
						<p><?php _e('Sorry, the page you are looking for could not be found. Try using the search box below!', 'IziThemes'); ?></p>
						<?php get_search_form(); ?>
					</div>
				</div>
			
			</div>
			<!-- END MAIN -->
			
<?php get_sidebar(); ?>
			
<?php get_footer(); ?>