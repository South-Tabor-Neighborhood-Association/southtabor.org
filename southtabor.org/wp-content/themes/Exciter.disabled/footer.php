<?php global $options;?>
	</div>	
	<!-- END WAPPER MAIN -->
</div>
	<!-- END WRAPPER-CONTENT -->		
		
		<!-- BEGIN FOOTER WRAPPER -->
		<div id="wrapper-footer">
		
			<div id="footer">
				<div class="widget">
				<?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('Footer Widget 1')): ?>
				<?php endif; ?>
				</div>
				<div class="widget">
				<?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('Footer Widget 2')): ?>
				<?php endif; ?>
				</div>
				<div class="widget last">
				<?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('Footer Widget 3')): ?>
				<?php endif; ?>
				</div>
				<div class="clear" id="footer-bottom">
					<p class="left">Copyright © 2013–2025 - <a href="<?php bloginfo('home');?>"><?php bloginfo('name');?></a>. <?php bloginfo('description');?>. | <a href="http://www.southtabor.org/wp-login.php">login</a></p>
					<p class="right"></p>
									
				</div>
									
			</div>
	</div>
	<!-- END FOOTER WRAPPER -->
			
		
	</div>
	<!-- END WRAPPER -->

<div id="toTop"></div>
<?php wp_footer(); ?>
<?php
if($options['footer_codes']!='') {
	echo $options['footer_codes'];
}
?>
</body>
</html>