<script type='text/javascript'>
jQuery(document).ready(function() {
	jQuery("input:text,input:checkbox,input:radio, textarea, select").uniform();
	
	//AJAX Upload
	jQuery('.upload_button').each(function(){
		
		var clickedObject = jQuery(this);
		var clickedID = jQuery(this).attr('id');
		
		new AjaxUpload(clickedID, {
			  action: '<?php echo admin_url("admin-ajax.php"); ?>',
			  name: clickedID, // File upload name
			  data: { // Additional data to send
					action: 'izi_upload',
					type: 'upload',
					data: clickedID },
			  autoSubmit: true, // Submit file after selection
			  responseType: false,
			  onChange: function(file, extension){},
			  onSubmit: function(file, extension){
				  this.disable(); // If you want to allow uploading only 1 file at time, you can disable upload button
			  },
			  onComplete: function(file, response) {
				  this.enable();
				  jQuery('[name="'+clickedID+'"]').val(response);
			  }
		});
	
	});
	
	// Radio tabs
	jQuery('[name=featured_source]').click(function() {
		var radioValue = jQuery(this).val();
		jQuery('.radio_container .radio_content').hide();
		jQuery('.radio_container').find('#'+ radioValue ).css('display','block');

	});



	// Save Changes
	jQuery('.save_btn').click(function(e) {
		e.preventDefault();
		jQuery('.loading_tip').fadeIn(400).delay(2000).fadeOut(400);
		var form = jQuery(this).parents('form');
		//alert(jQuery(form).serialize());
		jQuery.ajax({
			url: '<?php echo admin_url("admin-ajax.php"); ?>',
			data: jQuery(form).serialize()+'&action=izi_save_fields',
			type: 'POST',
			success: function() {
				jQuery('.saved_tip').fadeIn(400).delay(3000).fadeOut(400);
			}
		});
	});

	// Reset Changes
	jQuery('.reset_btn').click(function(e) {
		e.preventDefault();
		var answer = confirm('Click OK to reset. Any settings will be lost!');
		
		if(answer){
			jQuery('.loading_tip').fadeIn(400).delay(2000).fadeOut(400);
			var form = jQuery(this).parents('form');
			
			
			jQuery.ajax({
				url: '<?php echo admin_url("admin-ajax.php"); ?>',
				data: 'action=izi_reset_fields',
				type: 'POST',
				success: function() {
					jQuery('.reset_tip').fadeIn(400).delay(5000).fadeOut(400);
					setTimeout('location.reload(true);', 1200)
				}
			});
			
			return false;
		}
	});

	// Main tabs
	jQuery('.main_tabs a').click(function(e) {
		e.preventDefault();
		
		jQuery('.subtab ul').hide();
		jQuery('.subtab ul:first').hide();
		var href = jQuery(this).attr('href')
		var parent = jQuery(href).parent();
		var name = href.replace('#', '');
		
		jQuery(this).parents('ul').find('li').removeClass('selected');
		jQuery(this).parent().addClass('selected');

		jQuery('.subtab ul').fadeOut();
		jQuery('.subtab').find('.'+name).fadeIn();

		jQuery(parent).find('> div.mainTab').slideUp();
		jQuery(href).slideDown();
	});
	
	// Sub tabs
	jQuery('.subtab a').click(function(e) {
		e.preventDefault();

		var href = jQuery(this).attr('href')
		var parent = jQuery(href).parent();

		jQuery(this).parents('ul').find('li').removeClass('selected');
		jQuery(this).parent().addClass('selected');
		
		jQuery(parent).find('> div').slideUp();
		jQuery(href).slideDown();
	});

	// Skins
	jQuery('.skins img').live('click', function(e) {
		e.preventDefault();

		var id = jQuery(this).attr('id');
		var bg_color = jQuery(this).data('background');
		var pattern = jQuery(this).data('pattern');
		var link_color = jQuery(this).data('link');
		
		jQuery(this).parent().find('img').removeClass('selected');
		jQuery(this).addClass('selected');

		jQuery('[name=pattern]').parent().find('img').removeClass('selected');
		jQuery('#' + pattern).addClass('selected');
		
		jQuery(this).parent().find('input').val(id);

		jQuery('#bg_color').val(bg_color);
		jQuery('#colorpicker_bg_color .colorSelector').ColorPickerSetColor(bg_color);
		jQuery('#colorpicker_bg_color .colorSelector div').css('background-color', '#' + bg_color);
		
		jQuery('[name=pattern]').val(pattern);
		
		jQuery('#link_color').val(link_color);
		jQuery('#colorpicker_link_color .colorSelector').ColorPickerSetColor(link_color);
		jQuery('#colorpicker_link_color .colorSelector div').css('background-color', '#' + link_color);
		
	});
	
	jQuery('.skins img.selected').live('click', function(e) {
		e.preventDefault();

		jQuery(this).removeClass('selected');
		jQuery(this).parent().find('input').val('');
	});
	
	// Images
	jQuery('.images img').live('click', function(e) {
		e.preventDefault();

		var id = jQuery(this).attr('id');

		jQuery(this).parent().find('img').removeClass('selected');
		jQuery(this).addClass('selected');
		
		jQuery(this).parent().find('input').val(id);
	});
	
	jQuery('.images img.selected').live('click', function(e) {
		e.preventDefault();

		jQuery(this).removeClass('selected');
		jQuery(this).parent().find('input').val('');
	});
});
</script>
<div class="#preload_bg"></div>
<div id="izi">
	<div class="main-top">
		<div class="main-bottom">
			<div class="main-middle">
				<div class="overlay">
					<div id="left">
						<ul class="main_tabs">
							<li class='selected'><a href="#general_settings"><span class="general">General Settings</span></a></li>
							<li><a href="#slideshow"><span class="slider">Homepage Slider</span></a></li>
							<li><a href="#layout_settings"><span class="layout">Layout Settings</span></a></li>
							<li><a href="#appearence"><span class="color">Appearence</span></a></li>
							<li><a href="#seo"><span class="seo">SEO Options</span></a></li>
							<li><a href="#advertising"><span class="ads">Advertising</span></a></li>
							<!--<li><a href="#support" target="_blank"><span class="support">Support</span></a></li>-->
						</ul>
					</div><!-- #left -->
					<div id="main">
					<?php
					global $theme_data;
					?>
					<form id="izithemes" method="post" action="" enctype="multipart/form-data">
						<div>
							<div class="theme">
								<a href="http://izithemes.com/themes/<?php echo str_replace(' ','-',strtolower($theme_data['Name'])); ?>" target="_blank" class="themename">Theme: <strong><?php echo $theme_data['Name']; ?></strong></a>
								<span class="ver">version <?php echo $theme_data['Version']; ?></span>
							</div>
							<div class='top_button'>
								<input type='button' name='save_changes' value='' class='save_btn' />
							</div>
						</div>
						
						<div class="clear"></div>
						
						<div class="subtab">
							<ul class="general_settings selected">
								<li class="selected"><a href="#general"><span>General</span></a></li>
								<li><a href="#social_media"><span>Social Media</span></a></li>
								<li><a href="#intergration"><span>Intergration</span></a></li>
								<!--<li><a href="#transfer"><span>Transfer</span></a></li>-->
							</ul>
							
							<ul class="slideshow selected" style="display:none">
								<li class="selected"><a href="#sample1"><span>Slider Settings</span></a></li>
							</ul>
							
							<ul class="layout_settings selected" style="display:none">
								<li class="selected"><a href="#homepage_settings"><span>Homepage Settings</span></a></li>
								<li><a href="#posts_settings"><span>Posts Settings</span></a></li>
							</ul>
							
							<ul class="appearence selected" style="display:none">
								<li class="selected"><a href="#skins"><span>Skins Settings</span></a></li>
								<li><a href="#fonts"><span>Fonts Settings</span></a></li>
							</ul>
							
							<ul class="seo selected" style="display:none">
								<li class="selected"><a href="#homepage_seo"><span>Homepage SEO</span></a></li>
								<li><a href="#single_post_page_seo"><span>Single Post Page SEO</span></a></li>
								<li><a href="#index_page_seo"><span>Index Page SEO</span></a></li>
							</ul>
							
							<ul class="advertising selected" style="display:none">
								<li class="selected"><a href="#advertising_settings"><span>Advertising</span></a></li>
							</ul>
							
							<ul class="support selected" style="display:none">
								<li class="selected"><a href="#support_zone"><span>Support Zone</span></a></li>
							</ul>
							
							
						</div>