<?php get_header(); ?>
			<!-- BEGIN MAIN -->
			<div id="main">
				
				<div class="block archive">
					<h3>
						<?php if(is_day()): ?>
							<?php printf(__('Daily Archives: <span>%s</span>', 'IziThemes'), get_the_date()); ?>
						<?php elseif (is_month()) : ?>
							<?php printf(__('Monthly Archives: <span>%s</span>', 'IziThemes'), get_the_date('F Y')); ?>
						<?php elseif (is_year()) : ?>
							<?php printf(__('Yearly Archives: <span>%s</span>', 'IziThemes'), get_the_date('Y')); ?>
						<?php else : ?>
							<?php _e('Blog Archives', 'IziThemes'); ?>
						<?php endif; ?>
					</h3>
					
					<?php get_template_part( 'includes/loop','archive' ); ?> 
					
				</div>
			
			</div>
			<!-- END MAIN -->
			
<?php get_sidebar(); ?>
			
<?php get_footer(); ?>