<?php
// How comments are displayed
function comment($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment; ?>
	<li <?php comment_class(); ?> id="comment-<?php comment_ID() ?>">
	
		<div class="the-comment">
		
			<?php echo get_avatar($comment,$size='60','',''); ?>
			
			<div class="comment-arrow"></div>
			
			<div class="comment-box">
			
				<div class="comment-author">
					<strong><?php echo get_comment_author_link() ?></strong>
					<small><?php printf(__('%1$s at %2$s', 'IziThemes'), get_comment_date(),  get_comment_time()) ?></a><?php edit_comment_link(__('Edit'),'  ','') ?> - <?php comment_reply_link(array_merge( $args, array('reply_text' => 'Reply', 'add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?></small>
				</div>
			
				<div class="comment-text">
					<?php if ($comment->comment_approved == '0') : ?>
					<em><?php _e('Your comment is awaiting moderation.', 'IziThemes') ?></em>
					<br />
					<?php endif; ?>
					<?php comment_text() ?>
				</div>
			
			</div>
			
		</div>

<?php }
// Trim end of excerpt
function trim_excerpt($text) {
	return rtrim($text, '[...]');
}
add_filter('get_the_excerpt', 'trim_excerpt');


/*this function controls the meta titles display*/
function titles() {
	global $options;

	#if the title is being displayed on the homepage
	if (is_home() || is_front_page()) {
		if ($options['seo_home_title'] == 'On') echo $options['seo_home_titletext'];  
		else { 
			if ($options['seo_home_type'] == 'home_type_1') echo get_bloginfo('name').$options['seo_home_separate'].get_bloginfo('description'); 
			if ($options['seo_home_type'] == 'home_type_2') echo get_bloginfo('description').$options['seo_home_separate'].get_bloginfo('name');
			if ($options['seo_home_type'] == 'home_type_3') echo get_bloginfo('name');
		}
	}
	#if the title is being displayed on single posts/pages
	if (is_single() || is_page()) { 
		global $wp_query; 
		$postid = $wp_query->post->ID; 
		$key = 'izi_seo_single_field_title';
		$exists3 = get_post_meta($postid, $key, true);
				if ($options['seo_single_title'] == 'On' && $exists3 !== '' ) echo $exists3; 
				else { 
					if ($options['seo_single_type'] == 'single_type_1') echo get_bloginfo('name').$options['seo_single_separate'].wp_title('',false,''); 
					if ($options['seo_single_type'] == 'single_type_2') echo wp_title('',false,'').$options['seo_single_separate'].get_bloginfo('name');
					if ($options['seo_single_type'] == 'single_type_3') echo wp_title('',false,'');
			    }
					
	}
	#if the title is being displayed on index pages (categories/archives/search results)
	if (is_category() || is_archive() || is_search()) { 
		if ($options['seo_index_type'] == 'index_type_1') echo get_bloginfo('name').$options['seo_index_separate'].wp_title('',false,''); 
		if ($options['seo_index_type'] == 'index_type_2') echo wp_title('',false,'').$options['seo_index_separate'].get_bloginfo('name');
		if ($options['seo_index_type'] == 'index_type_3') echo wp_title('',false,'');
		 }	  
} 

/*this function controls the meta description display*/
function description() {
	global $options;
	
	#homepage descriptions
	if (is_home() && $options['seo_home_description'] == 'On') echo '<meta name="description" content="'.$options['seo_home_descriptiontext'].'" />';
	
	#single page descriptions
	global $wp_query; 
	if (isset($wp_query->post->ID)) $postid = $wp_query->post->ID; 
	$key2 = 'izi_seo_single_field_description';
	if (isset($postid)) $exists = get_post_meta($postid, $key2, true);
	if ($options['seo_single_description'] == 'On' && $exists !== '') {
		if (is_single() || is_page()) echo '<meta name="description" content="'.$exists.'" />';
	}
	
	#index descriptions
	remove_filter('term_description','wpautop');
	$cat = get_query_var('cat'); 
    $exists2 = category_description($cat);
	if ($exists2 !== '' && $options['seo_index_description'] == 'On') {
		if (is_category()) echo '<meta name="description" content="'. $exists2 .'" />';
	}
	if (is_archive() && $options['seo_index_description'] == 'On') echo '<meta name="description" content="Currently viewing archives from'. wp_title('',false,'') .'" />';
	if (is_search() && $options['seo_index_description'] == 'On') echo '<meta name="description" content="'. wp_title('',false,'') .'" />';
}


/*this function controls the meta keywords display*/
function keywords() {
	global $options;
	
	#homepage keywords
	if (is_home() && $options['seo_home_keywords'] == 'On') echo '<meta name="keywords" content="'.$options['seo_home_keywordstext'].'" />';
	
	#single page keywords
	global $wp_query; 
	if (isset($wp_query->post->ID)) $postid = $wp_query->post->ID; 
	$key3 = 'izi_seo_single_field_keywords';
	if (isset($postid)) $exists4 = get_post_meta($postid, $key3, true);
	if (isset($exists4) && $exists4 !== '' && $options['seo_single_keywords'] == 'On') {
		if (is_single() || is_page()) echo '<meta name="keywords" content="'.$exists4.'" />';	
	}
}


/*this function controls canonical urls*/
function canonical() {
	global $options;
	
	#homepage urls
	if (is_home() && $options['seo_home_canonical'] == 'On') echo '<link rel="canonical" href="'.get_bloginfo('url').'" />';
	
	#single page urls
	global $wp_query; 
	if (isset($wp_query->post->ID)) $postid = $wp_query->post->ID; 
	if ($options['seo_single_canonical'] == 'On') {
		if (is_single() || is_page()) echo '<link rel="canonical" href="'.get_permalink().'" />';	
	}
	
	#index page urls
	if ($options['seo_index_canonical'] == 'On') {
		if (is_archive() || is_category() || is_search()) echo '<link rel="canonical" href="'.get_permalink().'" />';	
	}
}

// Add Facebook like thumbbail
function fb_like_thumbnails()
{
	global $posts;
	$default = get_bloginfo('template_directory').'/images/thumb-small.png';
	
	if(has_post_thumbnail()){
		$thumb = wp_get_attachment_image_src(get_post_thumbnail_id($posts[0]->ID), '130x90');
		$thumb = $thumb[0];
	}else{
		$thumb = $default;
	}
	echo "\n\n<!-- Facebook Like Thumbnail -->\n<link rel=\"image_src\" href=\"$thumb\" />\n<!-- End Facebook Like Thumbnail -->\n\n";
}

add_action( 'wp_head', 'fb_like_thumbnails' );

// Shorten
function text_trim($text, $words = 50, $more = '...')
{ 
	$matches = preg_split("/\s+/", $text, $words + 1);
	$sz = count($matches);
	if ($sz > $words) 
	{
		unset($matches[$sz-1]);
		return implode(' ',$matches)." ".$more;
	}
	return $text;
}

	
function pagination($pages = '', $range = 2){  
	 $showitems = ($range * 2)+1;  

	 global $paged;
	 if(empty($paged)) $paged = 1;

	 if($pages == '')
	 {
		 global $wp_query;
		 $pages = $wp_query->max_num_pages;
		 if(!$pages)
		 {
			 $pages = 1;
		 }
	 }   

	 if(1 != $pages)
	 {
		 echo "<div class='pagination'>";
		 if($paged > 2 && $paged > $range+1 && $showitems < $pages) echo "<a href='".get_pagenum_link(1)."'><span class='arrows'>&laquo;</span> First</a>";
		 if($paged > 1 && $showitems < $pages) echo "<a href='".get_pagenum_link($paged - 1)."'><span class='arrows'>&lsaquo;</span> Previous</a>";

		 for ($i=1; $i <= $pages; $i++)
		 {
			 if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
			 {
				 echo ($paged == $i)? "<span class='current'>".$i."</span>":"<a href='".get_pagenum_link($i)."' class='inactive' >".$i."</a>";
			 }
		 }

		 if ($paged < $pages && $showitems < $pages) echo "<a href='".get_pagenum_link($paged + 1)."'>Next <span class='arrows'>&rsaquo;</span></a>";  
		 if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) echo "<a href='".get_pagenum_link($pages)."'>Last <span class='arrows'>&raquo;</span></a>";
		 echo "</div>\n";
	 }
}
?>