<?php
/**
 * Registering meta boxes
 *
 * In this file, I'll show you how to extend the class to add more field type (in this case, the 'taxonomy' type)
 * All the definitions of meta boxes are listed below with comments, please read them carefully.
 * Note that each validation method of the Validation Class MUST return value instead of boolean as before
 *
 * You also should read the changelog to know what has been changed
 *
 * For more information, please visit: http://www.deluxeblogtips.com/2010/04/how-to-create-meta-box-wordpress-post.html
 *
 */

global $options;

/********************* BEGIN DEFINITION OF META BOXES ***********************/

// prefix of meta keys, optional
// use underscore (_) at the beginning to make keys hidden, for example $prefix = '_rw_';
// you also can make prefix empty to disable it
$prefix = 'izi_';

$default['default'] = 'Default';

$enable_disbale['enable'] = 'Enable';
$enable_disbale['disable'] = 'Disable';

$enable_disbale_post = array_merge($default,$enable_disbale);

$meta_boxes = array();

// meta box ===> Pages
$meta_boxes[] = array(
	'id' => 'pages_meta',
	'title' => 'Page Options',
	'pages' => array('page'),

	'fields' => array(
		array(
			 'name' => 'Enable/Disable comment form',
            
			'id' => $prefix . 'comment',
			'type' => 'select',
			'options' => $enable_disbale
		)
		
	)
);

// meta box ===> Posts
$meta_boxes[] = array(
	'id' => 'posts_meta',
	'title' => 'Post Options',
	'pages' => array('post'),
	'fields' => array(
		array(
			'name' => 'Single Featured Image',
			'id' => $prefix . 'featured_image',
			'type' => 'select',
			'options' => $enable_disbale_post
		),
		array(
			'name' => 'Image Description',
			'desc' => '',
			'id' => $prefix . 'image_desc',
			'type' => 'text',
			'std' => ''
		)
		
	)
);


$custom_title = array(
			'name' => 'Custom title',
			'desc' => '',
			'id' => $prefix . 'seo_single_field_title',
			'type' => 'text',
			'std' => ''
		);

if($options['seo_single_title'] == 'On'){
	
	array_push($meta_boxes[1]['fields'],$custom_title);
}

$description = array(
			'name' => 'Description',
			'desc' => '',
			'id' => $prefix . 'seo_single_field_description',
			'type' => 'textarea',
			'std' => ''
		);
if($options['seo_single_description'] == 'On'){
	
	array_push($meta_boxes[1]['fields'],$description);
}	

$keywords = array(
		'name' => 'Keywords',
		'desc' => '',
		'id' => $prefix . 'seo_single_field_keywords',
		'type' => 'text',
		'std' => ''
	);
if($options['seo_single_keywords'] == 'On'){
	
	array_push($meta_boxes[1]['fields'],$keywords);
}	



foreach ($meta_boxes as $meta_box) {
	new RW_Meta_Box($meta_box);
}

/********************* END DEFINITION OF META BOXES ***********************/
?>
