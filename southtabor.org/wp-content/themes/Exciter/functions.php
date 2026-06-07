<?php
// Remove junk from head
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'feed_links', 2);
remove_action('wp_head', 'index_rel_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'feed_links_extra', 3);
remove_action('wp_head', 'start_post_rel_link', 10, 0);
remove_action('wp_head', 'parent_post_rel_link', 10, 0);
remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0);

// Get scripts
add_action('wp_enqueue_scripts','my_theme_scripts_function');
function my_theme_scripts_function() {
	global $options;

	wp_enqueue_script('jquery');
	
	wp_enqueue_script('superfish', get_bloginfo('template_directory'). '/js/superfish.js');
	wp_enqueue_script('tooltips', get_bloginfo('template_directory'). '/js/tooltips.js'); 
	wp_enqueue_script('settings', get_bloginfo('template_directory'). '/js/settings.js');
	
	if(is_home()){
		wp_enqueue_script('jCarousel', get_bloginfo('template_directory'). '/js/jcarousellite_1.0.1.pack.js');
		wp_enqueue_script('easing', get_bloginfo('template_directory'). '/js/jquery.easing.js');
	}
	
	if($options['slider_type']=='LofjSlider'){
		wp_enqueue_style('lof_css', get_bloginfo('template_directory').'/css/lof.css','','1');
		wp_enqueue_script('lof', get_bloginfo('template_directory'). '/js/jquery.lof.slider.js');
	}
	
	if($options['slider_type']=='Nivo'){
		wp_enqueue_style('nivo_css', get_bloginfo('template_directory').'/css/nivo-slider.css','','1');
		wp_enqueue_script('nivo', get_bloginfo('template_directory'). '/js/jquery.nivo.slider.js');
	}
	
	if(is_singular()) { 
		wp_enqueue_style('prettyPhoto', get_bloginfo('template_directory').'/css/prettyPhoto.css','','1');
		wp_enqueue_script('prettyPhoto', get_bloginfo('template_directory'). '/js/jquery.prettyPhoto.js');
		wp_enqueue_script('single', get_bloginfo('template_directory'). '/js/single.js');
		wp_enqueue_script('comment-reply'); 
	}
	
	if($options['skins']!=''){
		wp_enqueue_style('skin', get_bloginfo('template_directory').'/skins/'.$options['skins'].'/style.css','','1');
	}
	include (TEMPLATEPATH .'/includes/appearance.php');
}

// Enable Custom Background
add_custom_background();

// Add post thumbnail functionality
add_theme_support('post-thumbnails', array('post'));

// Main includes
include (TEMPLATEPATH . '/framework/IziFramework.php');
include (TEMPLATEPATH . '/framework/widgets/widgets.php');
include (TEMPLATEPATH . '/framework/shortcodes.php');

// Meta includes
include (TEMPLATEPATH . '/framework/meta-box/meta-box-class.php');
include (TEMPLATEPATH . '/framework/meta-box/meta-box-usage.php');

load_theme_textdomain('IziThemes', TEMPLATEPATH . '/languages/');

// Register Widgetized Areas
if(function_exists('register_sidebar')) {
	register_sidebar(array(
		'name' => 'Sidebar',
		'before_widget' => '<div id="%1$s" class="widget %2$s clear">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	));
	
	register_sidebar(array(
		'name' => 'Homepage',
		'before_widget' => '<div id="%1$s" class="widget %2$s clear">',
		'after_widget' => '</div>',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
	));
		
	register_sidebar(array(
		'name' => 'Footer Widget 1',
		'before_widget' => '<div id="%1$s" class="%2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	));
	
	register_sidebar(array(
		'name' => 'Footer Widget 2',
		'before_widget' => '<div id="%1$s" class="%2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	));
	
	register_sidebar(array(
		'name' => 'Footer Widget 3',
		'before_widget' => '<div id="%1$s" class="%2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	));
}

// Custom functions
include (TEMPLATEPATH . '/framework/custom_image_sizes.php');
include (TEMPLATEPATH . '/framework/custom_functions.php');


// Presstrends
function presstrends() {

// Add your PressTrends and Theme API Keys
$api_key = 'pkqgap89pzh53fhzxhhlai53pxw9v0hj9udb';
$auth = 'jjz71jd7vuxp6xtno3pbcrtwpbnxtytdq';

// NO NEED TO EDIT BELOW
$data = get_transient( 'presstrends_data' );
if (!$data || $data == ''){
$api_base = 'http://api.presstrends.io/index.php/api/sites/add/auth/';
$url = $api_base . $auth . '/api/' . $api_key . '/';
$data = array();
$count_posts = wp_count_posts();
$count_pages = wp_count_posts('page');
$comments_count = wp_count_comments();
$theme_data = get_theme_data(get_stylesheet_directory() . '/style.css');
$plugin_count = count(get_option('active_plugins'));
$all_plugins = get_plugins();
foreach($all_plugins as $plugin_file => $plugin_data) {
$plugin_name .= $plugin_data['Name'];
$plugin_name .= '&';
}
$data['url'] = stripslashes(str_replace(array('http://', '/', ':' ), '', site_url()));
$data['posts'] = $count_posts->publish;
$data['pages'] = $count_pages->publish;
$data['comments'] = $comments_count->total_comments;
$data['approved'] = $comments_count->approved;
$data['spam'] = $comments_count->spam;
$data['theme_version'] = $theme_data['Version'];
$data['theme_name'] = $theme_data['Name'];
$data['site_name'] = str_replace( ' ', '', get_bloginfo( 'name' ));
$data['plugins'] = $plugin_count;
$data['plugin'] = urlencode($plugin_name);
$data['wpversion'] = get_bloginfo('version');
foreach ( $data as $k => $v ) {
$url .= $k . '/' . $v . '/';
}
$response = wp_remote_get( $url );
set_transient('presstrends_data', $data, 60*60*24);
}}
add_action('admin_init', 'presstrends'); 

?>