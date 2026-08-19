<?php include_once('header.php'); ?>

<?php
$body_font_sizes = array('11px', '12px', '13px', '14px', '16px', '18px');
$google_fonts = array('Default','Arial','Georgia','Lucida Sans Unicode','Times New Roman','Verdana','Aclonica', 'Allan','Allerta','Allerta Stencil','Amaranth','Annie Use Your Telescope','Anonymous Pro','Anton','Architects Daughter','Arimo','Artifika','Arvo','Astloch','Bangers','Bentham','Bevan','Bigshot One','Brawler','Buda','Cabin','Cabin','Cabin Sketch','Calligraffitti','Candal','Cantarell','Cardo','Carter One','Caudex','Cedarville Cursive','Cherry Cream Soda','Chewy','Coda','Coming Soon','Copse','Corben','Cousine','Covered By Your Grace','Crafty Girls','Crimson Text','Crushed','Cuprum','Damion','Dancing Script','Dawning of a New Day','Didact Gothic','Droid Sans','Droid Sans Mono','Droid Serif','EB Garamond','Expletus Sans','Fontdiner Swanky','Francois','Geo','Goudy Bookletter','Gruppo','Holtwood One SC','Homemade Apple','IM Fell','Inconsolata','Indie Flower', 'Istok Web', 'Irish Grover','Josefin Sans','Josefin Slab','Judson','Jura','Just Another Hand','Just Me Again Down Here','Kameron','Kenia','Kranky','Kreon','Kristi','La Belle Aurore','Lato','League Script','Lekton','Limelight','Lobster','Lobster Two','Lora','Luckiest Guy','Maiden Orange','Mako','Maven pro','Meddon','MedievalSharp','Megrim','Merriweather','Metrophobic','Michroma','Miltonian','Molengo','Monofett','Mountains of Christmas','Muli','Neucha','Neutron','News Cycle','Nixie One','Nobile','Nova','Nunito','OFL Sorts Mill Goudy TT','Old Standard','Open Sans','Orbitron','Oswald','Over the Rainbow','PT Sans','PT Serif','Pacifico','Paytone One','Permanent Marker','Philosopher','Play','Playfair Display','Podkova','Puritan','Quattrocento','Quattrocento Sans','Radley','Raleway','Redressed','Reenie Beanie','Rock Salt','Rokkitt','Ruslan Display','Schoolbell','Shadows Into Light','Shanti','Sigmar One','Six Caps','Slackey','Smythe','Sniglet','Special Elite','Sue Ellen Francisco','Sunshiney','Swanky and Moo Moo','Syncopate','Tangerine','Tenor','Terminal Dosis Light','The Girl next Door','Tinos','Ubunto','Ultra','UnifrakturCook','UnifrakturMaguntia','Unkempt','VT323','Vibur','Vollkorn','Waiting For The Sunrise','Wallpoet','Walter Turncoat','Wire One','Yanone Kaffeesatz','Zeyada');

foreach ($body_font_sizes as $v) {
	$font_sizes[$v] = $v;	
}

foreach ($google_fonts as $v) {
	$fonts[$v] = $v;	
}
?>

<div class="submain-top">
	<div class="submain-bottom">
		<div class="submain-middle">
			<div id="general_settings" class="mainTab">
				<div id="general">
					<?php $this->upload('logo', 'Logo', 'Upload your Logo'); ?>
					<?php $this->upload('favicon', 'Favicon', 'Upload your Favicon'); ?>
					<?php $this->text('feedburner', 'Feedburner URL', ''); ?>
				</div>
				
				<div id="social_media" style="display:none">
					<?php $this->text('facebook_url', 'Facebook URL', 'Ex: http://www.facebook.com/izithemes'); ?>
					<?php $this->text('twitter_url', 'Twitter URL', 'Ex: http://twitter.com/izithemes'); ?>
					<?php $this->text('youtube_url', 'Youtube URL', 'Ex: http://www.youtube.com/izithemes'); ?>
					<?php $this->text('delicious_url', 'Delicious URL', 'Ex: http://www.delicious.com/izithemes'); ?>
					<?php $this->text('digg_url', 'Digg URL', 'Ex: http://digg.com/izithemes'); ?>
					<?php $this->text('flickr_url', 'Fickr URL', 'Ex: http://www.flickr.com/izithemes'); ?>
					<?php $this->text('linkedin_url', 'Linkedin URL', ''); ?>
					<?php $this->text('tumblr_url', 'Tumblr URL', 'Ex: http://izithemes.tumblr.com/'); ?>
					<?php $this->text('vimeo_url', 'Vimeo URL', 'Ex: http://www.vimeo.com/izithemes'); ?>
				</div>
				
				<div id="intergration" style="display:none">
					<?php $this->textarea('custom_css', 'Custom CSS', ''); ?>
					<?php $this->textarea('header_codes', 'Header Codes', 'Add code to the &lt;head&gt; of your theme'); ?>
					<?php $this->textarea('footer_codes', 'Footer Codes', 'Add code to the &lt;body&gt; (good for tracking codes such as google analytics)'); ?>
				</div>
				
				<div id="transfer" style="display:none">
					IMPORT or EXPORT
				</div>
				
			</div><!-- #general_settings -->
			
			<div id="slideshow" class="mainTab" style="display:none">
				<div id="slideshow_settings">
					<?php $this->select('slider_type',array(
						'Nivo' => 'Nivo (Fit to content)',
						'LofjSlider' => 'Lof JSliderNews (Fullwidth)'
					),
					'Slider Type','Select your slider style.'); ?>

					<?php $this->radio('featured_source', array(
						'fs_posts' => 'Selected Posts',
						'fs_category' => 'Category',
						'fs_tags' => 'Tags',
					),'Featured Posts Source',''); ?>
					
					
					<div class="radio_container">
						<?php if($this->options['featured_source']=='fs_posts'){?>
						<div id="fs_posts" class="radio_content">
						<?php }else{?>
						<div id="fs_posts" class="radio_content" style="display:none">
						<?php } ?>
						<?php $this->text('featured_source_postsID', 'Post IDs', 'Enter individual post IDs to display in the slideshow. You should add at last 4 post ID\'s with featured image set. <b>Separate IDs with commas</b>.'); ?>
						</div>
						
						<?php if($this->options['featured_source']=='fs_category'){?>
						<div id="fs_category" class="radio_content">
						<?php }else{?>
						<div id="fs_category" class="radio_content" style="display:none">
						<?php } ?>
						
						<?php $this->select('featured_source_category', $this->cats_to_select(),'Featured Category','The posts form the selected category will be listed in the featured slideshow. The selected category should contain at last 4 posts with featured image set.'); ?>
						</div>
						<?php if($this->options['featured_source']=='fs_tags'){?>
						<div id="fs_tags" class="radio_content">
						<?php }else{?>
						<div id="fs_tags" class="radio_content" style="display:none">
						<?php } ?>
							
							<?php $this->text('featured_source_tags', 'Featured Tags', 'The posts form these tags will be listed in the featured slideshow. <b>Separate tags by comma</b>'); ?>
						</div>
						
						<?php $this->text('featured_numbers', 'Number of Feautured Posts', 'The number of posts to display on featured slideshow.'); ?>

					</div>
					
				</div>
			</div><!-- #slideshow -->
			
			<div id="layout_settings" class="mainTab" style="display:none">
				<div id="homepage_settings">
					<?php $this->select('homepage_style', array(
						'magazine' => 'Magazine',
						'blog' => 'Blog',
					),'Homepage style'); ?>
				</div>
				<div id="posts_settings" style="display:none">
					<?php $this->checkbox('posts_featured', 'Show featured image on single posts'); ?>
					<?php $this->checkbox('author', 'Show author info box on posts'); ?>
					<?php $this->checkbox('tags', 'Show tags on posts'); ?>
					<?php $this->checkbox('categories', 'Show categories on posts'); ?>
					<?php $this->checkbox('related', 'Show related posts box on posts'); ?>
					<?php $this->checkboxes(array(
						'twitter' => 'Twitter',
						'facebook' => 'Facebook',
						'digg' => 'Digg',
						'stumbleupon' => 'StumbleUpon',
						'reddit' => 'Reddit',
						'tumblr' => 'Tumblr',
						'email' => 'Email',
						'google' => 'Google +1',
					),
					'Social Media Support'); ?>
				</div>
				
			</div>
			
			<div id="appearence" class="mainTab" style="display:none">
				<div id="skins">
				<div class="skins">
					<div class="input">
					<?php $this->get_skins('skins');?>
					</div>
					<div align="right"><a href="http://izithemes.com/skins" target="_blank">Get more skins &raquo;</a></div>
				</div>	
					<img style='margin-top: 30px; margin-bottom: 20px;'src='<?php bloginfo('template_directory'); ?>/framework/theme_options/images/create_skin.png' alt='Create your own skin' />

					<?php $this->colorpicker('bg_color', 'Background Color'); ?>
					
					<?php $this->images('pattern', array(
						'nopattern' => get_bloginfo('template_directory') . '/framework/images/bg-thumbs/none.png',
						'pattern1' => get_bloginfo('template_directory') . '/framework/images/bg-thumbs/pattern1.png',
						'pattern2' => get_bloginfo('template_directory') . '/framework/images/bg-thumbs/pattern2.png',
						'pattern3' => get_bloginfo('template_directory') . '/framework/images/bg-thumbs/pattern3.png',
						'pattern4' => get_bloginfo('template_directory') . '/framework/images/bg-thumbs/pattern4.png',
						'pattern5' => get_bloginfo('template_directory') . '/framework/images/bg-thumbs/pattern5.png',
						'pattern6' => get_bloginfo('template_directory') . '/framework/images/bg-thumbs/pattern6.png',
						'pattern7' => get_bloginfo('template_directory') . '/framework/images/bg-thumbs/pattern7.png',
						'pattern8' => get_bloginfo('template_directory') . '/framework/images/bg-thumbs/pattern8.png',
						'pattern9' => get_bloginfo('template_directory') . '/framework/images/bg-thumbs/pattern9.png',
						'pattern10' => get_bloginfo('template_directory') . '/framework/images/bg-thumbs/pattern10.png',
						'pattern11' => get_bloginfo('template_directory') . '/framework/images/bg-thumbs/pattern11.png',
						'pattern12' => get_bloginfo('template_directory') . '/framework/images/bg-thumbs/pattern12.png',
						'pattern13' => get_bloginfo('template_directory') . '/framework/images/bg-thumbs/pattern13.png',
						'pattern14' => get_bloginfo('template_directory') . '/framework/images/bg-thumbs/pattern14.png',
						'pattern15' => get_bloginfo('template_directory') . '/framework/images/bg-thumbs/pattern15.png',
						'pattern16' => get_bloginfo('template_directory') . '/framework/images/bg-thumbs/pattern16.png',
						'pattern17' => get_bloginfo('template_directory') . '/framework/images/bg-thumbs/pattern17.png',
						'pattern18' => get_bloginfo('template_directory') . '/framework/images/bg-thumbs/pattern18.png',
						'pattern19' => get_bloginfo('template_directory') . '/framework/images/bg-thumbs/pattern19.png',
						'pattern20' => get_bloginfo('template_directory') . '/framework/images/bg-thumbs/pattern20.png',
						'pattern21' => get_bloginfo('template_directory') . '/framework/images/bg-thumbs/pattern21.png',
						'pattern22' => get_bloginfo('template_directory') . '/framework/images/bg-thumbs/pattern22.png',
						'pattern23' => get_bloginfo('template_directory') . '/framework/images/bg-thumbs/pattern23.png',
						'pattern24' => get_bloginfo('template_directory') . '/framework/images/bg-thumbs/pattern24.png',
						'pattern25' => get_bloginfo('template_directory') . '/framework/images/bg-thumbs/pattern25.png',
						'pattern26' => get_bloginfo('template_directory') . '/framework/images/bg-thumbs/pattern26.png',
						'pattern27' => get_bloginfo('template_directory') . '/framework/images/bg-thumbs/pattern27.png',
						'pattern28' => get_bloginfo('template_directory') . '/framework/images/bg-thumbs/pattern28.png',
						'pattern29' => get_bloginfo('template_directory') . '/framework/images/bg-thumbs/pattern29.png',
						'pattern30' => get_bloginfo('template_directory') . '/framework/images/bg-thumbs/pattern30.png',
						'pattern31' => get_bloginfo('template_directory') . '/framework/images/bg-thumbs/pattern31.png',
						'pattern32' => get_bloginfo('template_directory') . '/framework/images/bg-thumbs/pattern32.png',
						'pattern33' => get_bloginfo('template_directory') . '/framework/images/bg-thumbs/pattern33.png',
						'pattern34' => get_bloginfo('template_directory') . '/framework/images/bg-thumbs/pattern34.png',
						'pattern35' => get_bloginfo('template_directory') . '/framework/images/bg-thumbs/pattern35.png',
						'pattern36' => get_bloginfo('template_directory') . '/framework/images/bg-thumbs/pattern36.png',
						'pattern37' => get_bloginfo('template_directory') . '/framework/images/bg-thumbs/pattern37.png',
						'pattern38' => get_bloginfo('template_directory') . '/framework/images/bg-thumbs/pattern38.png',
						'pattern39' => get_bloginfo('template_directory') . '/framework/images/bg-thumbs/pattern39.png',
						'pattern40' => get_bloginfo('template_directory') . '/framework/images/bg-thumbs/pattern40.png',
						'pattern41' => get_bloginfo('template_directory') . '/framework/images/bg-thumbs/pattern41.png',
						'pattern42' => get_bloginfo('template_directory') . '/framework/images/bg-thumbs/pattern42.png',
						'pattern43' => get_bloginfo('template_directory') . '/framework/images/bg-thumbs/pattern43.png',
						
					), 'Background Pattern'); ?>
					
					<?php $this->colorpicker('link_color', 'Link Color'); ?>
					
					<?php $this->colorpicker('newsletter_color', 'Newsletter Color','Set background color for Newsletter if you using Newsletter widget.'); ?>
					
				</div>
				
				<div id="fonts" style="display:none">
					<?php $this->select('nav_menu_font', $fonts,'Nav Menu Font'); ?>
					<?php $this->select('titles_font', $fonts ,'Titles font'); ?>
					<?php $this->select('content_font', $fonts ,'Content Font'); ?>
				</div>
			</div><!-- #appearence -->
			
			<div id="seo" class="mainTab" style="display:none">
				<div id="homepage_seo">
					<?php $this->checkbox('seo_home_title', 'Enable custom title'); ?>
					<?php $this->checkbox('seo_home_description', 'Enable meta description'); ?>
					<?php $this->checkbox('seo_home_keywords', 'Enable meta keywords'); ?>
					<?php $this->checkbox('seo_home_canonical', 'Enable canonical URL\'s'); ?>
					
					<?php $this->text('seo_home_titletext', 'Custom title', '(if enabled)'); ?>
					
					<?php $this->textarea('seo_home_descriptiontext', 'Meta description', '(if enabled)'); ?>
					
					<?php $this->text('seo_home_keywordstext', 'Meta keywords', '(if enabled)'); ?>
					
					<?php $this->select('seo_home_type', array(
						'home_type_1' => 'Blog name | Blog description',
						'home_type_2' => 'Blog description | Blog name',
						'home_type_3' => 'Blog name only',
					),'Homepage Title Type','If custom titles are disabled, choose autogeneration method'); ?>
					
					<?php $this->text('seo_home_separate', 'Separate character', 'Define a character to separate BlogName and Post title'); ?>
					
					 
				</div>
				<div id="single_post_page_seo" style="display:none">
					<?php $this->checkbox('seo_single_title', 'Enable custom title'); ?>
					<?php $this->checkbox('seo_single_description', 'Enable meta description'); ?>
					<?php $this->checkbox('seo_single_keywords', 'Enable meta keywords'); ?>
					<?php $this->checkbox('seo_single_canonical', 'Enable canonical URL\'s'); ?>
					<?php $this->text('seo_single_field_title', 'Title field', 'Custom field Name to be used for title'); ?>
					<?php $this->text('seo_single_field_description', 'Description field', 'Custom field Name to be used for description'); ?>
					<?php $this->text('seo_single_field_keywords', 'Keywords field', 'Custom field Name to be used for keywords'); ?>
					
					<?php $this->select('seo_single_type', array(
						'single_type_1' => 'Blog name | Post title',
						'single_type_2' => 'Post title | Blog name',
						'single_type_3' => 'Post title only',
					),'Single Title Type','If custom titles are disabled, choose autogeneration method'); ?>
					
					<?php $this->text('seo_single_separate', 'Separate character', 'Define a character to separate BlogName and Post title'); ?>
				</div>
				<div id="index_page_seo" style="display:none">
					<?php $this->checkbox('seo_index_canonical', 'Enable canonical URL\'s'); ?>
					<?php $this->checkbox('seo_index_description', 'Enable meta descriptions'); ?>
					
					<?php $this->select('seo_index_type', array(
						'index_type_1' => 'Blog name | Category name',
						'index_type_2' => 'Category name | Blog name',
						'index_type_3' => 'Category name only',
					),'Title Format','If custom titles are disabled, choose autogeneration method'); ?>
					
					<?php $this->text('seo_index_separate', 'Separate symbol', 'Define a character to separate BlogName and Post title'); ?>
				</div>
			</div><!-- #seo -->
			
			<div id="advertising" class="mainTab" style="display:none">
				<div id="advertising_settings">
					<?php $this->checkbox('ads468x60', 'Enable 468x60 banner'); ?>
					
					<?php $this->textarea('ads468x60_image', 'Image URL', 'Example: http://izithemes.com/ - don\'t forget the http://'); ?>
					
					<?php $this->text('ads468x60_destination', 'Destination', ''); ?>
					
					<?php $this->radio('ads468x60_target', array(
						'_blank' => '<b>_blank</b> - new window or tab.',
						'_top' => '<b>_top</b> - current window or tab, with no frames.',
						'_none' => '<b>_none</b> - same window or tab.',
					),'Target',''); ?>
					
					<?php $this->textarea('ads468x60_adsense', 'Adsense code', ''); ?>
				</div>
			</div><!-- #advertising -->
			
			<div id="support" class="mainTab" style="display:none">
				<div id="support_zone">
					<div class="support-box">
						<a href="http://izithemes.com/support/docs/" target="_blank" class="icon docs" >Documents</a>
					</div>
					<div class="support-box">
						<a href="http://izithemes.com/contact" target="_blank" class="icon contact">Contact</a>
					</div>
					<div class="support-box">
						<a href="http://izithemes.com/skins/" target="_blank" class="icon skins">Skins</a>
					</div>
					<div class="support-box">
						<a href="http://izithemes.com/themes" target="_blank" class="icon themes">Themes</a>
					</div>
					<div class="support-box">
						<a href="http://izithemes.com/chat" target="_blank" class="icon chat">Chat</a>
					</div>
					<div class="support-box">
						<a href="http://izithemes.com/updates/" target="_blank" class="icon updates">Updates</a>
					</div>
					<div class="clear"></div>
				</div>
			</div><!--#support -->
				
		</div><!-- .submain-middle -->
	</div><!-- .submain-bottom -->
</div><!-- .submain-top -->
		
	
	
<div class='reset_button'>
	<input type='button' name='reset_options' value='' class='reset_btn' />
</div>
<div class='bottom_button'>
	<input type="button" name='save_changes' value='' class='save_btn' />
</div>
							
							

<?php include_once('footer.php'); ?>