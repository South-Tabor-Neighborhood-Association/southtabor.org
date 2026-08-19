<?php global $options;?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<title><?php titles(); ?></title>
<?php description();?>
<?php keywords(); ?>
<?php canonical(); ?>
<?php if($options['favicon']): ?>
<link rel="shortcut icon" href="<?php echo $options['favicon']; ?>" />
<?php endif; ?>
<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" />

<!-- Google Fonts -->
<?php $fonts = array('Arial','Georgia','Lucida Sans Unicode','Times New Roman','Verdana', 'Oswald'); ?>
<?php if (!empty($options['content_font']) && !in_array($options['content_font'], $fonts)) { ?>
<link href='http://fonts.googleapis.com/css?family=<?php echo str_replace(' ', '+', $options['content_font']); ?>' rel='stylesheet' type='text/css'>
<?php array_push($fonts, $options['content_font']); ?> <?php } ?>
<?php if (!empty($options['titles_font']) && !in_array($options['titles_font'], $fonts)) { ?>
<link href='http://fonts.googleapis.com/css?family=<?php echo str_replace(' ', '+', $options['titles_font']); ?>' rel='stylesheet' type='text/css'>
<?php array_push($fonts, $options['titles_font']); ?>
<?php } ?>

<?php if (!empty($options['nav_menu_font']) && !in_array($options['nav_menu_font'], $fonts)) { ?>
<link href='http://fonts.googleapis.com/css?family=<?php echo str_replace(' ', '+', $options['nav_menu_font']); ?>' rel='stylesheet' type='text/css'>
<?php array_push($fonts, $options['nav_menu_font']); ?>
<?php } ?>
<?php
if($options['header_codes']) {
	echo $options['header_codes'];
}
?>
<?php if($options['custom_css']!=''){?>
<style type="text/css">
<?php echo $options['custom_css'];?>
</style>
<?php } ?>
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<!-- BEGIN WRAPPER -->
<div id="wrapper">

	<!-- BEGIN HEADER -->
	<div id="header">
		<!-- BEGIN HEADER-TOP -->
		<div id="header-top">
			<?php
			if(function_exists('wp_nav_menu')) {
				wp_nav_menu( 'depth=4&theme_location=topNav&menu_class=topNav&fallback_cb=topmenu_default');
				?>
				<?php
			} else {
				topmenu_default();
			}
			
			function topmenu_default()
			{
				?>
			
				<ul class="topNav">
					<?php wp_list_pages('depth=4&sort_column=menu_order&title_li=' ); ?>
				</ul>
			<?php } ?>
<p align="right" style="font-weight:normal;padding-top:7px;font-size:18px;color:#000000">South Tabor Neighborhood Association</p>

<!--			
			<div id="social-media" class="tooltip">
				<?php if($options['feedburner']){?>
				<a href="<?php echo $options['feedburner'];?>" title="RSS" target="_blank"><img src="<?php bloginfo('template_url');?>/images/social_icons/rss.png" ></a>
				<?php }?>
				<?php if($options['youtube_url']){?>
				<a href="<?php echo $options['youtube_url'];?>" title="Youtube" target="_blank"><img src="<?php bloginfo('template_url');?>/images/social_icons/youtube.png" ></a>
				<?php }?>
				<?php if($options['facebook_url']){?>
				<a href="<?php echo $options['facebook_url'];?>" title="Facebook" target="_blank"><img src="<?php bloginfo('template_url');?>/images/social_icons/facebook.png" ></a>
				<?php }?>
				<?php if($options['twitter_url']){?>
				<a href="<?php echo $options['twitter_url'];?>" title="Twitter" target="_blank"><img src="<?php bloginfo('template_url');?>/images/social_icons/twitter.png" ></a>
				<?php }?>
				<?php if($options['delicious_url']){?>
				<a href="<?php echo $options['delicious_url'];?>" title="Delicious" target="_blank"><img src="<?php bloginfo('template_url');?>/images/social_icons/delicious.png" ></a>
				<?php }?>
				<?php if($options['digg_url']){?>
				<a href="<?php echo $options['digg_url'];?>" title="Digg" target="_blank"><img src="<?php bloginfo('template_url');?>/images/social_icons/digg.png" ></a>
				<?php }?>
				<?php if($options['flickr_url']){?>
				<a href="<?php echo $options['flickr_url'];?>" title="Flickr" target="_blank"><img src="<?php bloginfo('template_url');?>/images/social_icons/flickr.png" ></a>
				<?php }?>
				<?php if($options['linkedin_url']){?>
				<a href="<?php echo $options['linkedin_url'];?>" title="LinkedIn" target="_blank"><img src="<?php bloginfo('template_url');?>/images/social_icons/linkedin.png" ></a>
				<?php }?>
				
				<?php if($options['tumblr_url']){?>
				<a href="<?php echo $options['tumblr_url'];?>" title="Tumblr" target="_blank"><img src="<?php bloginfo('template_url');?>/images/social_icons/tumblr.png" ></a>
				<?php }?>
				<?php if($options['vimeo_url']){?>
				<a href="<?php echo $options['vimeo_url'];?>" title="Vimeo" target="_blank"><img src="<?php bloginfo('template_url');?>/images/social_icons/vimeo.png" ></a>
				<?php }?>
			</div>
-->			
			<div class="clear"></div>
		</div>
		<!-- END HEADER-TOP -->
		
		<!-- BEGIN HEADER-MIDDLE -->
		<div id="header-middle">
			<div id="logo">
			<?php
			if($options['logo']) {
				$logo = $options['logo'];
			} else {
				if($options['skins']) {
					$logo = get_bloginfo('template_directory') . '/skins/'.$options['skins'].'/images/logo.png';
				} else {
					$logo = get_bloginfo('template_directory') . '/images/logo.png';
				}
			};
			
			?>
			<a href='<?php bloginfo('home'); ?>'><img src="<?php echo $logo; ?>" alt="<?php bloginfo('name'); ?>" /></a></div>
			
			<?php 
			if($options['ads468x60']=='On'){
				if($options['ads468x60_adsense']!=''){
					$banner_468 = stripcslashes($options['ads468x60_adsense']);
				}else{
					$banner_468  = '<a href="'.trim($options['ads468x60_destination']).'" target="'.$options['ads468x60_target'].'">';
					$banner_468 .= '<img src="'.stripcslashes(trim($options['ads468x60_image'])).'" alt="" /></a>';
				}
			?>
			
			<div id="header-banner"><?php echo $banner_468;?></div>
			<?php } ?>				
		</div>

		<!-- END HEADER-MIDDLE -->
						
	</div>
	<!-- END HEADER -->
	
	<!-- BEGIN WRAPPER-NAVIGATION -->
	<div id="wrapper-navigation">

			<div id="social-media" class="tooltip">
				<?php if($options['feedburner']){?>
				<a href="<?php echo $options['feedburner'];?>" title="RSS" target="_blank"><img src="<?php bloginfo('template_url');?>/images/social_icons/rss.png" ></a>
				<?php }?>
                                <?php if($options['email']){?>
				<a href="http://www.southtabor.org/contact" title="Contact"><img src="http://www.southtabor.org/wp-content/uploads/2013/02/mail.png" ></a>
				<?php }?>
                                <?php if($options['email']){?>
				<a href="http://groups.yahoo.com/group/southtabor/" target="_blank" title="Join our Email Group"><img src="http://www.southtabor.org/wp-content/uploads/2013/02/yahoo.png" ></a>
				<?php }?>
				<?php if($options['youtube_url']){?>
				<a href="<?php echo $options['youtube_url'];?>" title="Youtube" target="_blank"><img src="<?php bloginfo('template_url');?>/images/social_icons/youtube.png" ></a>
				<?php }?>
				<?php if($options['facebook_url']){?>
				<a href="<?php echo $options['facebook_url'];?>" title="Facebook" target="_blank"><img src="<?php bloginfo('template_url');?>/images/social_icons/facebook.png" ></a>
				<?php }?>
				<?php if($options['twitter_url']){?>
				<a href="<?php echo $options['twitter_url'];?>" title="Twitter" target="_blank"><img src="<?php bloginfo('template_url');?>/images/social_icons/twitter.png" ></a>
				<?php }?>
				<?php if($options['delicious_url']){?>
				<a href="<?php echo $options['delicious_url'];?>" title="Delicious" target="_blank"><img src="<?php bloginfo('template_url');?>/images/social_icons/delicious.png" ></a>
				<?php }?>
				<?php if($options['digg_url']){?>
				<a href="<?php echo $options['digg_url'];?>" title="Digg" target="_blank"><img src="<?php bloginfo('template_url');?>/images/social_icons/digg.png" ></a>
				<?php }?>
				<?php if($options['flickr_url']){?>
				<a href="<?php echo $options['flickr_url'];?>" title="Flickr" target="_blank"><img src="<?php bloginfo('template_url');?>/images/social_icons/flickr.png" ></a>
				<?php }?>
				<?php if($options['linkedin_url']){?>
				<a href="<?php echo $options['linkedin_url'];?>" title="LinkedIn" target="_blank"><img src="<?php bloginfo('template_url');?>/images/social_icons/linkedin.png" ></a>
				<?php }?>
				
				<?php if($options['tumblr_url']){?>
				<a href="<?php echo $options['tumblr_url'];?>" title="Tumblr" target="_blank"><img src="<?php bloginfo('template_url');?>/images/social_icons/tumblr.png" ></a>
				<?php }?>
				<?php if($options['vimeo_url']){?>
				<a href="<?php echo $options['vimeo_url'];?>" title="Vimeo" target="_blank"><img src="<?php bloginfo('template_url');?>/images/social_icons/vimeo.png" ></a>
				<?php }?>
			</div>
		<?php
		if(function_exists('wp_nav_menu')) {
			wp_nav_menu( 'depth=3&theme_location=mainNav&menu_class=sf-menu&menu_id=navigation&fallback_cb=mainmenu_default');
			?>
			<?php
		} else {
			mainmenu_default();
		}
		
		function mainmenu_default()
		{
			?>
		
			<ul class="sf-menu" id="navigation">
				<li <?php if(is_home()) { echo ' class="current_page_item" '; } ?>><a href="<?php bloginfo('url'); ?>"><?php _e('Home','IziThemes');?></a></li>
				<?php wp_list_categories('depth=3&hide_empty=0&orderby=name&show_count=0&use_desc_for_title=1&title_li='); ?>
				
			</ul>
		<?php } ?>
	</div>
	<!-- END WRAPPER-NAVIGATION -->
	
	<!-- BEGIN WRAPPER-CONTENT -->
	<div id="wrapper-content">
		<!-- BEGIN WAPPER MAIN -->
		<div id="wrapper-main">