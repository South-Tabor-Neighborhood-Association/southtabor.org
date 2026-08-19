<?php get_header(); ?>
<!-- BEGIN MAIN -->
<div id="main">
	<div class="block archive">
		<h3>
			<?php echo single_cat_title('', false); ?>
			<a href="<?php echo get_category_feed_link( get_query_var('cat'), 'rss2' ); ?>" class="rss"></a>
		</h3>
		
		<?php if(category_description($category)): ?>
		<?php echo category_description($category); ?>
		<br /><br />
		<?php endif; ?>
		
		<?php get_template_part( 'includes/loop','category' ); ?> 
		
	
	</div>

</div>
<!-- END MAIN -->
			
<?php get_sidebar(); ?>
			
<?php get_footer(); ?>