<?php 

function ay_metabox_project(){
    
	#-----------------------------------------------------------------#
	#  TITLE
	#-----------------------------------------------------------------#
	
    $meta_box = array(
		'id' => 'ay-metabox-project',
		'title' => __('Project Options', 'yenbekbay'),
		'description' => null,
		'post_type' => 'portfolio',
		'context' => 'normal',
		'priority' => 'high',
		'fields' => array(
			array( 
				'name' => __('Title Anchor', 'yenbekbay'),
				'id' => '_ay_project_anchor',
				'type' => 'text',
				'std' => ''
			),
			array( 
				'name' => __('Title Extra', 'yenbekbay'),
				'id' => '_ay_project_extra',
				'type' => 'text',
				'std' => ''
			),
			array( 
				'name' => __('Website Domain Name', 'yenbekbay'),
				'id' => '_ay_project_link',
				'type' => 'text',
				'std' => ''
			),
			array( 
				'name' => __('Hide this project on the home page?', 'yenbekbay'),
				'id' => '_ay_project_hide',
				'type' => 'checkbox',
				'std' => ''
			)
		)
	);
	$callback = create_function('$post,$meta_box', 'ay_create_meta_box($post, $meta_box["args"]);');
	add_meta_box($meta_box['id'], $meta_box['title'], $callback, $meta_box['post_type'], $meta_box['context'], $meta_box['priority'], $meta_box);
}
add_action('add_meta_boxes', 'ay_metabox_project');

?>