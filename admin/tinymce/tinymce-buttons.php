<?php

#-----------------------------------------------------------------#
#  REGISTER BUTTONS
#-----------------------------------------------------------------#

/* -- Toggle -- */
function ay_toggle_button() { 	
	if(!current_user_can('edit_posts') && !current_user_can('edit_pages')) {
		return;
	}
	if(get_user_option('rich_editing') == 'true') {
		add_filter('mce_external_plugins', 'add_toggle_js_plugin');
		add_filter('mce_buttons', 'register_ay_toggle_button');
	}
}
add_action('init', 'ay_toggle_button');

function add_toggle_js_plugin($plugin_array) {
   $plugin_array['toggle_button'] = get_template_directory_uri().'/admin/tinymce/js/toggle.js';
   return $plugin_array;
}

function register_ay_toggle_button($buttons) {
	array_push($buttons, '|', 'toggle_button');
	return $buttons; 
}

/* -- Tabbed -- */
function ay_tabs_button() { 	
	if(!current_user_can('edit_posts') && !current_user_can('edit_pages')) {
		return;
	}
	if(get_user_option('rich_editing') == 'true') {
		add_filter('mce_external_plugins', 'add_tabs_js_plugin');
		add_filter('mce_buttons', 'register_ay_tabs_button');
	}
}
add_action('init', 'ay_tabs_button');

function add_tabs_js_plugin($plugin_array) {
   $plugin_array['tabs_button'] = get_template_directory_uri().'/admin/tinymce/js/tabs.js';
   return $plugin_array;
}

function register_ay_tabs_button($buttons) {
	array_push($buttons, '|', 'tabs_button');
	return $buttons; 
}

function ay_tab_button() { 	
	if(!current_user_can('edit_posts') && !current_user_can('edit_pages')) {
		return;
	}
	if(get_user_option('rich_editing') == 'true') {
		add_filter('mce_external_plugins', 'add_tab_js_plugin');
		add_filter('mce_buttons', 'register_ay_tab_button');
	}
}
add_action('init', 'ay_tab_button');

function add_tab_js_plugin($plugin_array) {
   $plugin_array['tab_button'] = get_template_directory_uri().'/admin/tinymce/js/tab.js';
   return $plugin_array;
}

function register_ay_tab_button($buttons) {
	array_push($buttons, '|', 'tab_button');
	return $buttons; 
}

?>