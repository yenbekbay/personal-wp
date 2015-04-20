<?php 

function ay_metabox_page(){
    
	#-----------------------------------------------------------------#
	#  HEADER
	#-----------------------------------------------------------------#
	
    $meta_box = array(
		'id' => 'ay-metabox-page-header',
		'title' => __('Page Header Settings', 'yenbekbay'),
		'description' => null,
		'post_type' => 'page',
		'context' => 'normal',
		'priority' => 'high',
		'fields' => array(
			array( 
				'name' => __('Page Header Image', 'yenbekbay'),
				'id' => '_ay_header_bg',
				'type' => 'file',
				'std' => ''
			),
			array( 
				'name' => __('Page Header Height', 'yenbekbay'),
				'id' => '_ay_header_bg_height',
				'type' => 'text',
				'std' => ''
			),
			array( 
				'name' => __('Page Header Title', 'yenbekbay'),
				'id' => '_ay_header_title',
				'type' => 'text',
				'std' => ''
			),
			array( 
				'name' => __('Page Header Subtitle', 'yenbekbay'),
				'id' => '_ay_header_subtitle',
				'type' => 'text',
				'std' => ''
				),
			array( 
				'name' => __('Page Header ID', 'yenbekbay'),
				'id' => '_ay_header_id',
				'type' => 'text',
				'std' => ''
			)
		)
	);
	$callback = create_function('$post,$meta_box', 'ay_create_meta_box($post, $meta_box["args"]);');
	add_meta_box($meta_box['id'], $meta_box['title'], $callback, $meta_box['post_type'], $meta_box['context'], $meta_box['priority'], $meta_box);
}
add_action('add_meta_boxes', 'ay_metabox_page');

?>