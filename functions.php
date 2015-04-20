<?php 

/*-------------------------------------------------------------------------*/
/*	SET UP
/*-------------------------------------------------------------------------*/

// Load translation domain
load_theme_textdomain('yenbekbay', get_template_directory().'/languages');
	
function yenbekbay() {	
	// Image sizes 
	add_theme_support('post-thumbnails');
	add_image_size('portfolio-thumb', 640, 430, true); 
	update_option('large_size_w', 1840);	
	update_option('large_size_h', 9999);
	// Register custom navigation menu
	register_nav_menus( array(
		'primary-menu' => __('Primary Menu', 'yenbekbay'),
	));
}
add_action('after_setup_theme', 'yenbekbay');

// Update the home link
add_filter('home_url', 'ppqtrans_convertURL');

// Remove unnecessary code from wp_head
function ay_head_cleanup() {
	remove_action('wp_head', 'feed_links', 2);
	remove_action('wp_head', 'feed_links_extra', 3);
	remove_action('wp_head', 'rsd_link');
	remove_action('wp_head', 'wlwmanifest_link');
	remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
	remove_action('wp_head', 'wp_generator');
	remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
  
	global $wp_widget_factory;
	remove_action('wp_head', array($wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style'));
	add_filter('use_default_gallery_style', '__return_null');

	if(!class_exists('WPSEO_Frontend')) {
		remove_action('wp_head', 'rel_canonical');
		add_action('wp_head', 'ay_rel_canonical');
	}
}

function ay_rel_canonical() {
	global $wp_the_query;
	if(!is_singular()) {
		return;
	}
	if(!$id = $wp_the_query->get_queried_object_id()) {
		return;
	}
	$link = get_permalink($id);
	echo "<link rel=\"canonical\" href=\"$link\">\n";
}
add_action('init', 'ay_head_cleanup');

// Remove the WordPress version from RSS feeds
function ay_no_generator() { return ''; }
add_filter('the_generator', 'ay_no_generator');

// Clean up output of stylesheet <link> tags
function ay_clean_style_tag($input) {
	preg_match_all("!<link rel='stylesheet'\s?(id='[^']+')?\s+href='(.*)' type='text/css' media='(.*)' />!", $input, $matches);
	// Only display media if it's print
	$media = $matches[3][0] === 'print' ? ' media="print"' : '';
	return '<link rel="stylesheet" href="'.$matches[2][0].'"'. $media.'>'."\n";
}
add_filter('style_loader_tag', 'ay_clean_style_tag');

// Add and remove body_class() classes
function ay_body_class($classes) {
	// Add post/page slug
	if(is_single() || is_page() && !is_front_page()) {
		$classes[] = basename(get_permalink());
	}
	// Remove unnecessary classes
	$classes = preg_grep('/^page-id/', $classes, PREG_GREP_INVERT);
	$classes = preg_grep('/^page-template/', $classes, PREG_GREP_INVERT);
	$classes = preg_grep('/^postid/', $classes, PREG_GREP_INVERT);	
	return $classes;
}
add_filter('body_class', 'ay_body_class');

// Remove unnecessary self-closing tags
function ay_remove_self_closing_tags($input) {
	return str_replace(' />', '>', $input);
}
add_filter('get_avatar',          'ay_remove_self_closing_tags'); // <img />
add_filter('comment_id_fields',   'ay_remove_self_closing_tags'); // <input />
add_filter('post_thumbnail_html', 'ay_remove_self_closing_tags'); // <img />

// Custom email adress and name
function ay_new_mail_from($email) {
    $email = 'ayan@yenbekbay.kz'; 
    return $email;
}
add_filter('wp_mail_from', 'ay_new_mail_from');
 
function new_mail_from_name($name) {
    $name = 'Аян Енбекбай'; 
    return $name;
}
add_filter('wp_mail_from_name', 'ay_new_mail_from_name');

/*-------------------------------------------------------------------------*/
/*	REGISTER/ENQUEUE SCRIPTS
/*-------------------------------------------------------------------------*/

function ay_scripts() {	
	if(!is_admin()) {
		// Register 
		wp_deregister_script('jquery');
		wp_register_script('jquery', get_template_directory_uri().'/js/jquery.min.js', 'jquery', '1.8.3');	
		wp_deregister_script('jquery-color');
		wp_register_script('jquery-color', get_template_directory_uri().'/js/jquery.color.min.js', 'jquery', '2.1.1', true);
		wp_register_script('custom', get_template_directory_uri().'/js/custom.js', array('jquery', 'superfish', 'supersubs', 'easing'), '1.0', true);		
		wp_register_script('modernizer', get_template_directory_uri().'/js/modernizr.min.js', '', '2.6.2');	
		wp_register_script('retina', get_template_directory_uri().'/js/retina.js', '', '0.0.2', true);
		wp_register_script('superfish', get_template_directory_uri().'/js/superfish.min.js', 'jquery', '1.4.8', true);
		wp_register_script('supersubs', get_template_directory_uri().'/js/supersubs.min.js', 'jquery', '0.2b', true);
		wp_register_script('easing', get_template_directory_uri().'/js/easing.js', 'jquery', '1.3', true);
		wp_register_script('isotope', get_template_directory_uri().'/js/isotope.min.js', 'jquery', '1.5.25' ,true);	
		wp_register_script('jplayer', get_template_directory_uri().'/js/jplayer.min.js', 'jquery', '2.1', true);		
		// Enqueue
		wp_enqueue_script('jquery');
		wp_enqueue_script('jquery-color');
		wp_enqueue_script('custom');		
		wp_enqueue_script('modernizer');
		wp_enqueue_script('retina');
		wp_enqueue_script('superfish'); 
		wp_enqueue_script('supersubs'); 
		wp_enqueue_script('easing'); 
		wp_enqueue_script('isotope');
	}
}
add_action('wp_enqueue_scripts', 'ay_scripts');

function ay_extra_scripts() {	
	echo "<!--[if lt IE 9]><script type='text/javascript' src='/js/respond.min.js?ver=1.1'></script><![endif]-->\n";
	if(is_front_page() || is_page('projects') || is_singular('portfolio'))  {
		echo '<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>'."\n";
		echo "<script src='//vk.com/js/api/openapi.js?95'></script>
<script>
  VK.init({apiId: 3607693, onlyWidgets: true});
</script>\n";
	}	
}
add_action('wp_head', 'ay_extra_scripts');

/*-------------------------------------------------------------------------*/
/*	REGISTER/ENQUEUE STYLES
/*-------------------------------------------------------------------------*/

function ay_styles() {	
	echo '<link rel="stylesheet" media="all" href="'.get_template_directory_uri().'/css/main.css">'."\n";
	echo '<link rel="stylesheet" media="all" href="'.get_template_directory_uri().'/css/responsive.css">'."\n";
}
add_action('wp_print_styles', 'ay_styles');

/*-------------------------------------------------------------------------*/
/*	INCLUDES
/*-------------------------------------------------------------------------*/

// Page meta
require_once('admin/meta/page-meta.php');
// Project meta
require_once('admin/meta/project-meta.php');
// Shortcodes
require_once('admin/tinymce/shortcodes.php');
// TinyMCE buttons
require_once('admin/tinymce/tinymce-buttons.php');	

/******************** ADMIN ********************/

// Remove WP logo from toolbar
function ay_remove_admin_bar_links() {
	global $wp_admin_bar;
	$wp_admin_bar->remove_menu('wp-logo');
	$wp_admin_bar->remove_menu('updates');
}
add_action('wp_before_admin_bar_render', 'ay_remove_admin_bar_links');

// Edit admin footer link
function ay_change_footer_admin () {	
	return ppqtrans_use(ppqtrans_getLanguage(), '<a href="'. get_home_url().'" style="color: #999">© '.get_bloginfo('name').'</a>', true);
}
add_filter('admin_footer_text', 'ay_change_footer_admin', 9999);

// Remove version number
function ay_remove_footer_version() {
	return '';
}
add_filter('update_footer', 'ay_remove_footer_version', 9999);

// Modify admin title
function ay_admin_title($admin_title, $title) {
	$name = ppqtrans_use(ppqtrans_getLanguage(), get_bloginfo('name'), true);
    return $name.' &mdash; '.$title;
}
add_filter('admin_title', 'ay_admin_title', 10, 2);

// Disable dashboard widgets
function ay_disable_default_dashboard_widgets() { 
	remove_meta_box('dashboard_plugins', 'dashboard', 'core');  
	remove_meta_box('dashboard_primary', 'dashboard', 'core');  
    remove_meta_box('dashboard_secondary', 'dashboard', 'core');  
}  
add_action('admin_menu', 'ay_disable_default_dashboard_widgets');  

// Remove widgets
function ay_unregister_widget() {
    unregister_widget('WP_Widget_Pages'); 
    unregister_widget('WP_Widget_Calendar'); 
    unregister_widget('WP_Widget_Meta');  
    unregister_widget('WP_Widget_RSS');
    unregister_widget('WP_Widget_Tag_Cloud'); 
    unregister_widget('Akismet_Widget');
}
add_action('widgets_init', 'ay_unregister_widget');

// Add admin area favicon
function ay_admin_favicon() {
	echo "\n".'<link rel="shortcut icon" href="'.get_template_directory_uri().'/img/favicon.ico'.'"/>';
}
add_action('admin_head', 'ay_admin_favicon');
add_action('login_head', 'ay_admin_favicon');

// Highligt posts statuses
function ay_posts_status_color(){
	echo '<style>
	.status-draft{ background: #FCE3F2!important; }
	.status-pending{ background: #87C5D6!important; }
	.status-future{ background: #C6EBF5!important; }
	.status-private{ background:#F2D46F!important; }
</style>'."\n";
}
add_action('admin_footer','ay_posts_status_color');

// Display post id column
function ay_posts_columns_id($defaults){
    $defaults['ay_post_id'] = 'ID';
    return $defaults;
}
function ay_posts_custom_id_columns($column_name, $id){
    if($column_name === 'ay_post_id'){
        echo $id;
    }
}
function ay_posts_columns_attachment_id($defaults){
    $defaults['ay_attachments_id'] = 'ID';
	return $defaults;
}
function ay_posts_custom_columns_attachment_id($column_name, $id){
    if($column_name === 'ay_attachments_id'){
        echo $id;
    }
}
add_filter('manage_posts_columns', 'ay_posts_columns_id', 5);
add_action('manage_posts_custom_column', 'ay_posts_custom_id_columns', 5, 2);
add_filter('manage_pages_columns', 'ay_posts_columns_id', 5);
add_action('manage_pages_custom_column', 'ay_posts_custom_id_columns', 5, 2);
add_filter('manage_media_columns', 'ay_posts_columns_attachment_id', 1);
add_action('manage_media_custom_column', 'ay_posts_custom_columns_attachment_id', 1, 2);

// Display post attachment count column
function ay_posts_columns_attachment_count($defaults) {
    $defaults['ay_post_attachments'] = __('Attachments', 'ay');
    return $defaults;
}
function ay_posts_custom_columns_attachment_count($column_name, $id){
    if($column_name === 'ay_post_attachments') {
		$attachments = get_children(array('post_parent'=>$id));
		$count = count($attachments);
		if($count !=0){
			echo $count;
		}
    }
}
add_filter('manage_posts_columns', 'ay_posts_columns_attachment_count', 5);
add_action('manage_posts_custom_column', 'ay_posts_custom_columns_attachment_count', 5, 2);

// Display media library URL column
function ay_muc_column($cols) {
        $cols["media_url"] = "URL";
        return $cols;
}
function ay_muc_value( $column_name, $id ) {
        if ($column_name == "media_url") echo '<input type="text" width="100%" onclick="jQuery(this).select();" value="'.wp_get_attachment_url($id).'"/>';
}
add_filter('manage_media_columns', 'ay_muc_column');
add_action('manage_media_custom_column', 'ay_muc_value', 10, 2);

// Add styles for columns
function ay_columns_styles() {
	echo "\n".'<style type="text/css">
    .column-ay_post_id, .column-ay_attachments_id { width: 50px; }
    .column-ay_post_attachments { width: 100px; }
    .column-author { width: 130px!important; }
</style>'."\n";
}
add_action('admin_head', 'ay_columns_styles');

/*-------------------------------------------------------------------------*/
/*	LOGIN
/*-------------------------------------------------------------------------*/

// Add custom login title and link
function ay_wp_login_url($url) {
    return home_url();
}
function ay_wp_login_title() {
	return '';
}
add_filter('login_headerurl', 'ay_wp_login_url');
add_filter('login_headertitle', 'ay_wp_login_title');

// Add custom styles for login page
function ay_custom_login() { 
	echo "\n".'<link rel="stylesheet" href="'.get_bloginfo('template_directory').'/css/login.css" type="text/css" media="screen"/>'."\n";
}
add_action('login_head', 'ay_custom_login');

// Allow login with email address
function ay_login_with_email_address($username) {
    $user = get_user_by('email',$username);
    if(!empty($user->user_login))
        $username = $user->user_login;
    return $username;
}
add_action('wp_authenticate','ay_login_with_email_address');
function ay_username_text($text){
    if(in_array($GLOBALS['pagenow'], array('wp-login.php'))) {
        if ($text == 'Username') { $text = 'Username / Email'; }
		elseif ($text == 'Имя пользователя') { $text = 'Имя пользователя / Email'; }
    }
    return $text;
}
add_filter('gettext', 'ay_username_text');

// Change register to sign up
function ay_register_text($text) {
     $text = str_ireplace('Register',  'Sign up',  $text);
     return $text;
}
add_filter('gettext', 'ay_register_text');
add_filter('ngettext', 'ay_register_text');

// Redirect after login & logout
function ay_login_redirect($redirect, $request_redirect ) {
    if($request_redirect ==='')
        $redirect = home_url();
    return $redirect; 
}
add_filter('login_redirect', 'ay_login_redirect', 10, 2 );

/*-------------------------------------------------------------------------*/
/*	EXCERPT
/*-------------------------------------------------------------------------*/

// Remove "more" jump link
function ay_remove_more_jump_link($link) { 
	$offset = strpos($link, '#more-');
	if($offset) {
		$end = strpos($link, '"',$offset);
	}
	if($end) {
		$link = substr_replace($link, '', $offset, $end-$offset);
	}
	return $link;
}
add_filter('the_content_more_link', 'ay_remove_more_jump_link');

function ay_custom_excerpt_more($more) {
	return '...';
}
add_filter('excerpt_more', 'ay_custom_excerpt_more');

// Fixing filtering for shortcodes
function ay_shortcode_empty_paragraph_fix($content){   
    $array = array (
        '<p>[' => '[', 
        ']</p>' => ']', 
        ']<br />' => ']'
    );
    $content = strtr($content, $array);
    return $content;
}
add_filter('the_content', 'ay_shortcode_empty_paragraph_fix');

/*-------------------------------------------------------------------------*/
/*	METABOX
/*-------------------------------------------------------------------------*/

// Metabox styling
function  ay_metabox_styles() {
	wp_enqueue_style('ay_meta_css', get_template_directory_uri().'/admin/meta/meta.css');
}
add_action('admin_print_styles', 'ay_metabox_styles');

// Metabox scripts
function ay_metabox_scripts() {
    wp_enqueue_script('redux-opts-field-upload-js', get_template_directory_uri().'/admin/meta/upload/field_upload.js', 'jquery', '', true);	
}
add_action('admin_enqueue_scripts', 'ay_metabox_scripts');

// Metabox core functions
require_once('admin/meta/meta-config.php');

/*-------------------------------------------------------------------------*/
/*	ADMIN PORTFOLIO SECTION
/*-------------------------------------------------------------------------*/

// Register
function ay_portfolio_register() {      	 
	 $portfolio_labels = array(
	 	'name' => __('Projects', 'yenbekbay'),
		'singular_name' => __('Project', 'yenbekbay'),
		'search_items' =>  __('Search Projects', 'yenbekbay'),
		'all_items' => __('Projects', 'yenbekbay'),
		'parent_item' => __('Parent Project', 'yenbekbay'),
		'edit_item' => __('Edit Project', 'yenbekbay'),
		'update_item' => __('Update Project', 'yenbekbay'),
		'add_new_item' => __('Add New Project', 'yenbekbay')
	 );	 
	 $args = array(
			'labels' => $portfolio_labels,
			'rewrite' => array('slug' => 'projects','with_front' => false),
			'singular_label' => __('Project', 'yenbekbay'),
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true,
			'hierarchical' => false,
			'menu_position' => 9,
			'menu_icon'=> 'dashicons-portfolio',
			'supports' => array('title', 'editor', 'thumbnail', 'comments')  
       );  
  
    register_post_type('portfolio' , $args);  
}  
add_action('init', 'ay_portfolio_register');

// Add taxonomies attached to portfolio 
$category_labels = array(
	'name' => __('Project Categories', 'yenbekbay'),
	'singular_name' => __('Project Category', 'yenbekbay'),
	'search_items' =>  __('Search Project Categories', 'yenbekbay'),
	'all_items' => __('All Project Categories', 'yenbekbay'),
	'parent_item' => __('Parent Project Category', 'yenbekbay'),
	'edit_item' => __('Edit Project Category', 'yenbekbay'),
	'update_item' => __('Update Project Category', 'yenbekbay'),
	'add_new_item' => __('Add New Project Category', 'yenbekbay'),
    'menu_name' => __('Project Categories', 'yenbekbay')
); 	
register_taxonomy('project-type', 
	array('portfolio'), 
	array('hierarchical' => true, 
			'labels' => $category_labels,
			'show_ui' => true,
    		'query_var' => true,
			'rewrite' => array('slug' => 'project-type')
));

/*$attributes_labels = array(
	'name' => __('Project Attributes', 'yenbekbay'),
	'singular_name' => __('Project Attribute', 'yenbekbay'),
	'search_items' =>  __('Search Project Attributes', 'yenbekbay'),
	'all_items' => __('All Project Attributes', 'yenbekbay'),
	'parent_item' => __('Parent Project Attribute', 'yenbekbay'),
	'edit_item' => __('Edit Project Attribute', 'yenbekbay'),
	'update_item' => __('Update Project Attribute', 'yenbekbay'),
	'add_new_item' => __('Add New Project Attribute', 'yenbekbay'),
	'new_item_name' => __('New Project Attribute', 'yenbekbay'),
    'menu_name' => __('Project Attributes', 'yenbekbay')
);
register_taxonomy('project-attributes',
	array('portfolio'),
	array('hierarchical' => true,
	    'labels' => $attributes_labels,
	    'show_ui' => true,
	    'query_var' => true,
	    'rewrite' => array('slug' => 'project-attributes')
));*/


// Enable qTranslate on portfolio taxonomies
function ay_qtranslate_edit_taxonomies() {
   $args = array(
      'public' => true ,
      '_builtin' => false
   );
   $output = 'object'; // or objects
   $operator = 'and'; // 'and' or 'or'

   $taxonomies = get_taxonomies($args, $output, $operator);

   if($taxonomies) {
     foreach($taxonomies as $taxonomy) {
         add_action($taxonomy->name.'_add_form', 'ppqtrans_modifyTermFormFor');
         add_action($taxonomy->name.'_edit_form', 'ppqtrans_modifyTermFormFor');
     }
   }

}
add_action('admin_init', 'ay_qtranslate_edit_taxonomies');

/*-------------------------------------------------------------------------*/
/*	PORTFOLIO EXTRA
/*-------------------------------------------------------------------------*/

// Walker for portfolio filter
class Walker_Portfolio_Filter extends Walker_Category {
	function start_el(&$output, $category, $depth, $args) {
		extract($args);
		$cat_slug = esc_attr($category->slug);
		$cat_slug = apply_filters('list_cats', $cat_slug, $category);
		$link = '          <li><a href="#" data-filter=".'.strtolower(preg_replace('/\s+/', '-', $cat_slug)).'">';
		$cat_name = esc_attr($category->name);
		$cat_name = apply_filters('list_cats', $cat_name, $category);
		$link .= $cat_name;
		if(!empty($category->description)) {
			$link .= ' <span>'.$category->description.'</span>';
		}
		$link .= '</a>';
		$output .= $link;       
	}
}

// Get the page link back to all portfolio items
function get_portfolio_page_link($post_id) {
    global $wpdb;	
    $results = $wpdb->get_results("SELECT post_id FROM $wpdb->postmeta
    WHERE meta_key='_wp_page_template' AND meta_value='template-portfolio.php'");
    foreach ($results as $result) {
        $page_id = $result->post_id;
    }	
    return get_page_link($page_id);
}

/*-------------------------------------------------------------------------*/
/*	SNIPPETS
/*-------------------------------------------------------------------------*/

// Meta Description
function ay_description() {
	global $wp_query, $post;
	if((is_single() || is_page()) && !is_front_page()) {
		setup_postdata($post);
		$description = get_the_excerpt();
		if(!$description) $description = trim(strip_tags(get_bloginfo('description')));
	} elseif(is_category()) {
		$category = $wp_query->get_queried_object();
		$description = trim(strip_tags($category->category_description));
		if(!$description) $description = trim(strip_tags(get_bloginfo('description')));		
	} else {
		$description = trim(strip_tags(get_bloginfo('description')));
	}

	if($description) {
		return $description;
	}
}

// Meta Title
function ay_title($return = false) {
	global $page, $paged;
	if(is_page('projects')) {
		if($return == false) {
			_e('Projects', 'yenbekbay');
		} else {
			__('Projects', 'yenbekbay');
		}
	} elseif(is_home()) {
		if($return == false) {
			_e('Blog', 'yenbekbay');
		} else {
			__('Blog', 'yenbekbay');
		}
	} else {
		if($return == false) {		
			wp_title('|', true, 'right');
			bloginfo('name');
			if($paged >= 2 || $page >= 2)
				echo ' | '. sprintf(__('Page %s', 'yenbekbay'), max($paged, $page));
		} else {
			$print = wp_title('|', false, 'right');
			$print .=  get_bloginfo('name');
			if($paged >= 2 || $page >= 2)
				$print .= ' | '. sprintf(__('Page %s', 'yenbekbay'), max($paged, $page));		
			return $print;	
		}			
	}
}

// Meta Image
function ay_image() {
    global $post;   	
	if(has_post_thumbnail($post->ID)) {
		$feat_image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'large');		
		$image = 'http://yenbekbay.kz'.esc_attr($feat_image[0]);
	} else {
		$image = 'http://yenbekbay.kz'.get_template_directory_uri().'/img/photo.jpg';
	} 	
	return $image;
}

// Facebook OpenGraph Meta Tags
function ay_opengraph_tags() {
    echo '<meta property="og:site_name" content="'.get_bloginfo('name').'">'."\n";
	echo '<meta property="og:url" content="'.get_permalink().'">'."\n";    
	echo '<meta property="og:description" content="'.ay_description().'">'."\n";
	echo '<meta property="og:title" content="'.ay_title(true).'">'."\n";
    if(is_singular() && !is_front_page()) {
        echo '<meta property="og:type" content="article">'."\n";		
    } elseif(is_home() || is_front_page()) {
        echo '<meta property="og:type" content="website">'."\n";
    }
    echo '<meta property="og:image" content="'.ay_image().'">'."\n";
}

// Twitter Card
function ay_twitter_card() {
    echo '<meta name="twitter:card" content="summary">'."\n";
	echo '  <meta name="twitter:url" content="'.get_permalink().'">'."\n";      
 	echo '  <meta name="twitter:description" content="'.ay_description().'">'."\n";   
    echo '  <meta name="twitter:title" content="'.ay_title(true).'">'."\n";
    echo '  <meta name="twitter:image" content="'.ay_image().'">'."\n";
    echo '  <meta name="twitter:site" content="@yenbekbay">'."\n";
    echo '  <meta name="twitter:creator" content="@yenbekbay">'."\n";  		    
}

// Plural form
function ay_plural_form($number, $after) {
	$cases = array (2, 0, 1, 1, 1, 2);
	return $number.' '.$after[ ($number%100>4 && $number%100<20)? 2: $cases[min($number%10, 5)] ];
}

// Post date
function ay_date($postid, $type, $show_year = true) {
	if(date('Yz') == get_the_time('Yz', $postid)) {
		$day = __('Today', 'yenbekbay');
    } elseif(date('Yz')-1 == get_the_time('Yz', $postid)) {
		$day = __('Yesterday', 'yenbekbay');
    } else {
		if($show_year == true) {
			$day = get_the_time('j F Y');
		} else {
			$day = get_the_time('j F');
		}
    }
	echo '<div class="'.$type.'-date">'.$day.'</div>'."\n";
}

// Project link
function ay_project_link($postid) {
	$domain = get_post_meta($postid, '_ay_project_link', true);	
	if($domain) {
		echo '<a class="project-link" href="//'.$domain.'"><img src="https://plus.google.com/_/favicon?domain='.$domain.'" class="fav">'.$domain.'</a>'."\n";
	}
}

// Portfolio
function ay_portfolio($number = '-1', $page = true) { ?>
<script>
  jQuery(document).ready(function($){
    var container = $('#portfolio');
    $(window).load(function(){
      container.isotope({
      	layoutMode: 'fitRows',
        itemSelector: '.item',
        filter: '*'
      });
      container.css('visibility', 'visible');
    }); <?php if($page == true) { ?>
			
    $('#portfolio-filters ul li a').click(function() {
      var selector = $(this).attr('data-filter');
      container.isotope({ filter: selector });
      $('#portfolio-filters ul li a').removeClass('active');
      $(this).addClass('active');
      return false;
    });
    $('#portfolio-filters > a').click(function() {
      return false;
    }); <?php } ?>

  });
  </script>
  
  <div id="portfolio" class="row portfolio-items">
    <?php if($page == false) { 
    	$args = array(
			'posts_per_page' => $number,
			'post_type' => 'portfolio',
			'meta_key' => '_ay_project_hide',
			'meta_value' => 'off'
   		); 
    } else {
		$args = array(
			'posts_per_page' => $number,
			'post_type' => 'portfolio'
		);	
    }
    $portfolio = new WP_Query();
    $portfolio -> query($args);		
    if($portfolio->have_posts()) { while ($portfolio->have_posts()) { $portfolio->the_post();	
      $terms = get_the_terms(get_the_ID(), 'project-type');
      $project_cats = NULL;
      if(!empty($terms)) {
        foreach($terms as $term) {
          $project_cats .= strtolower($term->slug).' ';
        }
      }
      ?>
		
    <article class="item <?php echo $project_cats; ?>">	
      <div class="work-item">	
        <a href="<?php the_permalink(); ?>">	  
         <?php if(has_post_thumbnail()) {
           echo get_the_post_thumbnail(get_the_ID(), 'portfolio-thumb', array('title' => '')); 
         } else {
           echo '<img src="'.get_template_directory_uri().'/img/no-portfolio-item-small.jpg" alt="no image added yet." />';
         } ?>    
        </a>
      </div>				
      <div class="work-meta">
        <a href="<?php echo get_permalink(); ?>"><?php
		$anchor = ppqtrans_use(ppqtrans_getLanguage(), get_post_meta(get_the_ID(), '_ay_project_anchor', true), true);
		$extra = ppqtrans_use(ppqtrans_getLanguage(), get_post_meta(get_the_ID(), '_ay_project_extra', true), true);
		echo $anchor; ?></a> <span><?php echo $extra; ?></span>
      </div>
    </article>
	<?php } } wp_reset_query(); ?>	  
  </div><?php
}

// Like
function ay_like() { 
if(is_front_page() || is_page('projects') || is_singular('portfolio'))  {
    global $post;   	
	if(has_post_thumbnail($post->ID)) {
		$feat_image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'large');		
		$image = 'http://yenbekbay.kz'.esc_attr($feat_image[0]);
	} else {
		$image = 'http://yenbekbay.kz'.get_template_directory_uri().'/img/logo-wide.png';
	} ?>
<div id="like-buttons" class="clearfix">
      <div id="google">
        <div class="g-plusone" data-size="medium" data-href="<?php echo get_permalink(); ?>"></div>
      </div>
      <div id="twitter">
        <a href="https://twitter.com/share" class="twitter-share-button" data-url="<?php echo get_permalink(); ?>" data-related="yenbekbay" data-lang="en">Tweet</a>
      </div>
      <div id="fb">
        <iframe src="//facebook.com/plugins/like.php?href=<?php echo urlencode(get_permalink()); ?>&amp;send=false&amp;layout=button_count&amp;width=82&amp;show_faces=false&amp;font&amp;colorscheme=light&amp;action=like&amp;height=21&amp;locale=en_US" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:82px; height:21px;" allowTransparency="true"></iframe>
      </div>
      <div id="vk">
        <div id="vk_like"></div>
        <script type="text/javascript">
          VK.Widgets.Like('vk_like', {
            type: 'mini', 
            height: 20
          });
        </script>
      </div> 
    </div><?php
}
}

// Plusone JS
function ay_plusone_js() { 
if(is_front_page() || is_page('projects') || is_singular('portfolio'))  { ?>
<script>
  window.___gcfg = {lang: 'ru'};
  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/plusone.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
</script><?php 
}
}

// Pixelated square
function ay_pixelated_square($id, $color, $link=null) { ?>
<div id="<?php echo $id; ?>" class="forty-forty" data-color="<?php echo $color; ?>">
        <table cellpadding="0">
          <tbody>
            <tr>
              <td class="square_td"><span>&nbsp;</span></td>
              <td class="square_td"><span>&nbsp;</span></td>
              <td class="square_td"><span>&nbsp;</span></td>
              <td class="square_td"><span>&nbsp;</span></td>           
            </tr>
            <tr>
              <td class="square_td"><span>&nbsp;</span></td>
              <td class="square_td"><span>&nbsp;</span></td>
              <td class="square_td"><span>&nbsp;</span></td>
              <td class="square_td"><span>&nbsp;</span></td>         
            </tr>
            <tr>
              <td class="square_td"><span>&nbsp;</span></td>
              <td class="square_td"><span>&nbsp;</span></td>
              <td class="square_td"><span>&nbsp;</span></td>
              <td class="square_td"><span>&nbsp;</span></td>           
            </tr>
            <tr>
              <td class="square_td"><span>&nbsp;</span></td>
              <td class="square_td"><span>&nbsp;</span></td>
              <td class="square_td"><span>&nbsp;</span></td>
              <td class="square_td"><span>&nbsp;</span></td>           
            </tr>
          </tbody>
        </table>
        <?php if($link) { ?><a class="image" href="<?php echo $link; ?>"></a><?php } else { ?><span class="image"></span><?php } ?>
      
      </div>
<?php	
}

/*-------------------------------------------------------------------------*/
/*	CUSTOM PAGE HEADER
/*-------------------------------------------------------------------------*/

function ay_page_header($postid) {		
	global $options;
	global $post;		
    $bg = get_post_meta($postid, '_ay_header_bg', true);
    $title = ppqtrans_use(ppqtrans_getLanguage(), get_post_meta($postid, '_ay_header_title', true), true);
    $subtitle = ppqtrans_use(ppqtrans_getLanguage(), get_post_meta($postid, '_ay_header_subtitle', true), true);
	$height = get_post_meta($postid, '_ay_header_bg_height', true);
	$link = get_permalink(get_post_meta($postid, '_ay_header_id', true));
	$page_template = get_post_meta($postid, '_wp_page_template', true); 		
	// In case no title is entered for portfolio, still show the filters
	if($page_template == 'template-portfolio.php' && empty($title)) $title = get_the_title($post->ID);
	echo "\n";
	if(!empty($bg)) { ?>    	
  <div id="page-header-bg" data-height="<?php echo (!empty($height)) ? $height : '250'; ?>" style="background-image: url(<?php echo $bg; ?>); height: <?php echo $height;?>px;">
    <div class="container">
	
      <div class="col<?php if($link) echo ' link'; ?>">
        <?php if($link) { ?><a href="<?php echo $link; ?>"><?php echo "\n          "; } ?><h1><?php echo $title ?></h1>
      <?php if($subtitle) { ?>  <span class="subheader"><?php echo $subtitle."\n"; ?></span><?php } ?><?php if($link) { ?>  </a><?php echo "\n      "; } ?></div>
      <?php if($page_template == 'template-portfolio.php') { ?> 
      <div id="portfolio-filters">
        <a href="#" id="sort-portfolio"><?php _e('Sort Portfolio', 'yenbekbay'); ?></a>
        <ul>
          <li><a href="#" data-filter="*"><?php echo __('All', 'yenbekbay'); ?></a></li>
<?php wp_list_categories(array('title_li' => '', 'taxonomy' => 'project-type', 'show_option_none'   => '', 'walker' => new Walker_Portfolio_Filter())); ?>
        </ul>
      </div><?php } ?>
	  
    </div>
  </div>	   	
	    <?php } else if(!empty($title)) { ?>
  <div class="row page-title page-header-no-bg container">

    <h1><?php echo $title; ?><?php if(!empty($subtitle)) echo '<span>'.$subtitle.'</span>'; ?></h1>		
    <?php if($page_template == 'template-portfolio.php') { ?>  
    <div id="portfolio-filters">
      <a href="#" id="sort-portfolio"><?php _e('Sort Portfolio', 'yenbekbay'); ?></a>
      <ul>
        <li><a href="#" data-filter="*"><?php echo __('All', 'yenbekbay'); ?></a></li>
<?php wp_list_categories(array('title_li' => '', 'taxonomy' => 'project-type', 'show_option_none'   => '', 'walker' => new Walker_Portfolio_Filter())); ?>
      </ul>
    </div>
  <?php } ?>

  </div>	
	<?php }		 
}

/*-------------------------------------------------------------------------*/
/*	DISABLE ATTACHMENT PAGES
/*-------------------------------------------------------------------------*/ 

// Disable attachment posts
function ay_attachment_fields_edit($form_fields,$post){ 
    $form_fields['url']['html'] = preg_replace('/<button(.*)<\/button>/', '', $form_fields['url']['html']);
    $form_fields['url']['helps'] ='';
    return $form_fields;
}
add_filter('attachment_fields_to_edit', 'ay_attachment_fields_edit', 10, 2);

// Redirect attachment pages
function sar_attachment_redirect() {  
	global $post;
	if( is_attachment() && isset($post->post_parent) && is_numeric($post->post_parent) && ($post->post_parent != 0) ) {
		wp_redirect(get_permalink($post->post_parent), 301); // permanent redirect to post/page where image or document was uploaded
		exit;
	} elseif( is_attachment() && isset($post->post_parent) && is_numeric($post->post_parent) && ($post->post_parent < 1) ) {   // for some reason it doesnt work checking for 0, so checking lower than 1 instead...
		wp_redirect(get_bloginfo('wpurl'), 302); // temp redirect to home for image or document not associated to any post/page
		exit;       
    }
}
add_action('template_redirect', 'sar_attachment_redirect',1);

/*-------------------------------------------------------------------------*/
/*	SEARCH
/*-------------------------------------------------------------------------*/

// Redirect search results from /?s=query to /search/query/, converts %20 to +
function ay_nice_search_redirect() {
	global $wp_rewrite;
	if(!isset($wp_rewrite) || !is_object($wp_rewrite) || !$wp_rewrite->using_permalinks()) {
		return;
	}
	$search_base = $wp_rewrite->search_base;
	if (is_search() && !is_admin() && strpos($_SERVER['REQUEST_URI'], "/{$search_base}/") === false) {
		wp_redirect(home_url("/{$search_base}/" . urlencode(get_query_var('s'))));
	exit();
	}
}
if(current_theme_supports('nice-search')) {
	add_action('template_redirect', 'ay_nice_search_redirect');
}

// Fix for empty search queries redirecting to home page
function ay_request_filter($query_vars) {
	if (isset($_GET['s']) && empty($_GET['s'])) {
		$query_vars['s'] = ' ';
	}
	return $query_vars;
}
add_filter('request', 'ay_request_filter');

// Change number of search results per page
function change_wp_search_size($query) {
	if($query->is_search ) 
		$query->query_vars['posts_per_page'] = 12; 

	return $query; 
}
add_filter('pre_get_posts', 'change_wp_search_size');

/*-------------------------------------------------------------------------*/
/*	IMAGES
/*-------------------------------------------------------------------------*/

// Remove image links
function attachment_image_link_remove_filter( $content ) {
	$content =	preg_replace(array('{<a(.*?)(wp-att|wp-content/uploads)[^>]*><img}', '{ wp-image-[0-9]*" /></a>}'), array('<img','" />'), $content);
	return $content;
}
add_filter( 'the_content', 'attachment_image_link_remove_filter' );

// Remove gallery settings
function ay_remove_gallery_settings() {
    echo '<style type="text/css">
    .media-modal .gallery-settings .setting { display: none; }
    .media-modal .gallery-settings .setting:last-child { display: block; }
</style>'."\n";
}
add_action('admin_head', 'ay_remove_gallery_settings');

/*-------------------------------------------------------------------------*/
/*	CLEAN URLS
/*-------------------------------------------------------------------------*/

function ay_flush_rewrites() {
  global $wp_rewrite;
  $wp_rewrite->flush_rules();
}
add_action('admin_init', 'ay_flush_rewrites');

function ay_add_rewrites($content) {
  $theme_name = next(explode('/themes/', get_stylesheet_directory()));
  global $wp_rewrite;
  $ay_new_non_wp_rules = array(
    'css/(.*)'      => 'wp-content/themes/'. $theme_name . '/css/$1',
    'js/(.*)'       => 'wp-content/themes/'. $theme_name . '/js/$1',
    'img/(.*)'      => 'wp-content/themes/'. $theme_name . '/img/$1',
    'plugins/(.*)'  => 'wp-content/plugins/$1'
  );
  $wp_rewrite->non_wp_rules += $ay_new_non_wp_rules;
}
add_action('generate_rewrite_rules', 'ay_add_rewrites');

if(!is_admin()) {
  add_filter('plugins_url', 'ay_clean_plugins');
  add_filter('bloginfo', 'ay_clean_assets');
  add_filter('stylesheet_directory_uri', 'ay_clean_assets');
  add_filter('template_directory_uri', 'ay_clean_assets');
  add_filter('script_loader_src', 'ay_clean_plugins');
  add_filter('style_loader_src', 'ay_clean_plugins');
}

function ay_clean_assets($content) {
    $theme_name = next(explode('/themes/', $content));
    $current_path = '/wp-content/themes/' . $theme_name;
    $new_path = '';
    $content = str_replace($current_path, $new_path, $content);
    return $content;
}

function ay_clean_plugins($content) {
    $current_path = '/wp-content/plugins';
    $new_path = '/plugins';
    $content = str_replace($current_path, $new_path, $content);
    return $content;
}

if(!is_admin() && !in_array($GLOBALS['pagenow'], array('wp-login.php', 'wp-register.php'))) {
  add_filter('bloginfo_url', 'ay_root_relative_url');
  add_filter('theme_root_uri', 'ay_root_relative_url');
  add_filter('stylesheet_directory_uri', 'ay_root_relative_url');
  add_filter('template_directory_uri', 'ay_root_relative_url');
  add_filter('script_loader_src', 'ay_fix_duplicate_subfolder_urls');
  add_filter('style_loader_src', 'ay_fix_duplicate_subfolder_urls');
  add_filter('plugins_url', 'ay_root_relative_url');
  add_filter('the_permalink', 'ay_root_relative_url');
  add_filter('wp_list_pages', 'ay_root_relative_url');
  add_filter('wp_list_categories', 'ay_root_relative_url');
  add_filter('wp_nav_menu', 'ay_root_relative_url');
  add_filter('the_content_more_link', 'ay_root_relative_url');
  add_filter('the_tags', 'ay_root_relative_url');
  add_filter('get_pagenum_link', 'ay_root_relative_url');
  add_filter('get_comment_link', 'ay_root_relative_url');
  add_filter('month_link', 'ay_root_relative_url');
  add_filter('day_link', 'ay_root_relative_url');
  add_filter('year_link', 'ay_root_relative_url');
  add_filter('tag_link', 'ay_root_relative_url');
  add_filter('the_author_posts_link', 'ay_root_relative_url');
}

function ay_root_relative_url($input) {
  $output = preg_replace_callback(
    '!(https?://[^/|"]+)([^"]+)?!',
    create_function(
      '$matches',
      // If full URL is site_url, return a slash for relative root
      'if (isset($matches[0]) && $matches[0] === site_url()) { return "/";' .
      // If domain is equal to site_url, then make URL relative
      '} elseif (isset($matches[0]) && strpos($matches[0], site_url()) !== false) { return $matches[2];' .
      // If domain is not equal to site_url, do not make external link relative
      '} else { return $matches[0]; };'
    ),
    $input
  );
  return $output;
}

// Remove the duplicate subfolder in the src of JS/CSS tags
function ay_fix_duplicate_subfolder_urls($input) {
  $output = ay_root_relative_url($input);
  preg_match_all('!([^/]+)/([^/]+)!', $output, $matches);
  if (isset($matches[1]) && isset($matches[2])) {
    if ($matches[1][0] === $matches[2][0]) {
      $output = substr($output, strlen($matches[1][0]) + 1);
    }
  }
  return $output;
}

// Remove root relative URLs on any attachments in the feed
add_action('pre_get_posts', 'ay_root_relative_attachment_urls');
function ay_root_relative_attachment_urls() {
  if ( !is_feed() ) {
    add_filter('wp_get_attachment_url', 'ay_root_relative_url');
    add_filter('wp_get_attachment_link', 'ay_root_relative_url');
  }
}

/*-------------------------------------------------------------------------*/
/*	MISC.
/*-------------------------------------------------------------------------*/

// Clean nav menu
class Cleaner_Walker_Nav_Menu extends Walker {
    var $tree_type = array('post_type', 'taxonomy', 'custom');
    var $db_fields = array('parent' => 'menu_item_parent', 'id' => 'db_id');
    function start_lvl(&$output, $depth) {
        $indent = str_repeat("\t", $depth);
        $output .= "\n$indent         <ul class=\"sub-menu\">\n";
    }
    function end_lvl(&$output, $depth) {
        $indent = str_repeat("\t", $depth);
        $output .= "$indent       </ul>\n";
    }
    function start_el(&$output, $item, $depth, $args) {
        global $wp_query;
        $indent = ($depth) ? str_repeat( "\t", $depth ) :'';
        $class_names = $value ='';
        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $classes = in_array('current-menu-item', $classes) ? array('current-menu-item') : array();
        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
        $class_names = strlen(trim($class_names)) > 0 ?' class="'.esc_attr($class_names ).'"':'';
        $id = apply_filters('nav_menu_item_id', '', $item, $args );
        $id = strlen($id ) ?' id="'.esc_attr($id ).'"':'';
        $output .= $indent . '     <li'. $id . $value . $class_names .'>';
        $attributes  = ! empty($item->attr_title ) ?'title="' . esc_attr($item->attr_title ).'"':'';
        $attributes .= ! empty($item->target )     ?'target="'. esc_attr($item->target     ).'"':'';
        $attributes .= ! empty($item->xfn )        ?'rel="'   . esc_attr($item->xfn        ).'"':'';
        $attributes .= ! empty($item->url )        ?'href="'  . esc_attr($item->url        ).'"':'';
        $item_output = $args->before;
        $item_output .='<a '.$attributes.'>';
        $item_output .= $args->link_before.apply_filters('the_title', $item->title, $item->ID).$args->link_after;
        $item_output .='</a>';
        $item_output .= $args->after;
        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }
    function end_el(&$output, $item, $depth) {
        $output .= "</li>\n  ";
	}
}

// Remove rel attribute from the category list
function remove_category_list_rel( $output ) {
    return str_replace(' rel="category tag"', '', $output);
} 
add_filter('wp_list_categories', 'remove_category_list_rel');
add_filter('the_category', 'remove_category_list_rel');

// Current page URL
function current_page_url() {
	$pageURL = 'http';
	if( isset($_SERVER['HTTPS']) ) {
		if ($_SERVER['HTTPS'] == 'on') {$pageURL .= 's';}
	}
	$pageURL .= '://';
	if ($_SERVER['SERVER_PORT'] != '80') {
		$pageURL .= $_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].$_SERVER['REQUEST_URI'];
	} else {
		$pageURL .= $_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
	}
	return $pageURL;
}

/* Convert cyrillic, european and georgian characters in post, term slugs and media file names to latin characters
 * Credit: Cyr to Lat enhanced Plugin */
function ctl_sanitize_title($title) {
	global $wpdb;
	$iso9_table = array(
		'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Ѓ' => 'G`',
		'Ґ' => 'G`', 'Д' => 'D', 'Е' => 'E', 'Ё' => 'YO', 'Є' => 'YE',
		'Ж' => 'ZH', 'З' => 'Z', 'Ѕ' => 'Z', 'И' => 'I', 'Й' => 'J',
		'Ј' => 'J', 'І' => 'I', 'Ї' => 'YI', 'К' => 'K', 'Ќ' => 'K`',
		'Л' => 'L', 'Љ' => 'L', 'М' => 'M', 'Н' => 'N', 'Њ' => 'N`',
		'О' => 'O', 'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T',
		'У' => 'U', 'Ў' => 'U`', 'Ф' => 'F', 'Х' => 'H', 'Ц' => 'TS',
		'Ч' => 'CH', 'Џ' => 'DH', 'Ш' => 'SH', 'Щ' => 'SHH', 'Ъ' => '``',
		'Ы' => 'Y`', 'Ь' => '`', 'Э' => 'E`', 'Ю' => 'YU', 'Я' => 'YA',
		'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'ѓ' => 'g',
		'ґ' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'yo', 'є' => 'ye',
		'ж' => 'zh', 'з' => 'z', 'ѕ' => 'z', 'и' => 'i', 'й' => 'j',
		'ј' => 'j', 'і' => 'i', 'ї' => 'yi', 'к' => 'k', 'ќ' => 'k`',
		'л' => 'l', 'љ' => 'l', 'м' => 'm', 'н' => 'n', 'њ' => 'n`',
		'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't',
		'у' => 'u', 'ў' => 'u`', 'ф' => 'f', 'х' => 'h', 'ц' => 'ts',
		'ч' => 'ch', 'џ' => 'dh', 'ш' => 'sh', 'щ' => 'shh', 'ъ' => '``',
		'ы' => 'y`', 'ь' => '`', 'э' => 'e`', 'ю' => 'yu', 'я' => 'ya'
	);
	$geo2lat = array(
		'ა' => 'a', 'ბ' => 'b', 'გ' => 'g', 'დ' => 'd', 'ე' => 'e', 'ვ' => 'v',
		'ზ' => 'z', 'თ' => 'th', 'ი' => 'i', 'კ' => 'k', 'ლ' => 'l', 'მ' => 'm',
		'ნ' => 'n', 'ო' => 'o', 'პ' => 'p','ჟ' => 'zh','რ' => 'r','ს' => 's',
		'ტ' => 't','უ' => 'u','ფ' => 'ph','ქ' => 'q','ღ' => 'gh','ყ' => 'qh',
		'შ' => 'sh','ჩ' => 'ch','ც' => 'ts','ძ' => 'dz','წ' => 'ts','ჭ' => 'tch',
		'ხ' => 'kh','ჯ' => 'j','ჰ' => 'h'
	);
	$iso9_table = array_merge($iso9_table, $geo2lat);
	$locale = get_locale();
	switch ( $locale ) {
		case 'bg_BG':
			$iso9_table['Щ'] = 'SHT';
			$iso9_table['щ'] = 'sht'; 
			$iso9_table['Ъ'] = 'A`';
			$iso9_table['ъ'] = 'a`';
			break;
		case 'uk':
			$iso9_table['И'] = 'Y`';
			$iso9_table['и'] = 'y`';
			break;
	}
	$is_term = false;
	$backtrace = debug_backtrace();
	foreach ( $backtrace as $backtrace_entry ) {
		if ( $backtrace_entry['function'] == 'wp_insert_term' ) {
			$is_term = true;
			break;
		}
	}
	$term = $is_term ? $wpdb->get_var("SELECT slug FROM {$wpdb->terms} WHERE name = '$title'") : '';
	if ( empty($term) ) {
		$title = strtr($title, apply_filters('ctl_table', $iso9_table));
		if(function_exists('iconv')){
			$title = iconv('UTF-8', 'UTF-8//TRANSLIT//IGNORE', $title);
		}
		$title = preg_replace("/[^A-Za-z0-9'_\-\.]/", '-', $title);
		$title = preg_replace('/\-+/', '-', $title);
		$title = preg_replace('/^-+/', '', $title);
		$title = preg_replace('/-+$/', '', $title);
	} else {
		$title = $term;
	}
	return $title;
}
add_filter('sanitize_title', 'ctl_sanitize_title', 9);
add_filter('sanitize_file_name', 'ctl_sanitize_title');

function ctl_convert_existing_slugs() {
	global $wpdb;
	$posts = $wpdb->get_results("SELECT ID, post_name FROM {$wpdb->posts} WHERE post_name REGEXP('[^A-Za-z0-9\-]+') AND post_status = 'publish'");
	foreach ( (array) $posts as $post ) {
		$sanitized_name = ctl_sanitize_title(urldecode($post->post_name));
		if ( $post->post_name != $sanitized_name ) {
			add_post_meta($post->ID, '_wp_old_slug', $post->post_name);
			$wpdb->update($wpdb->posts, array( 'post_name' => $sanitized_name ), array( 'ID' => $post->ID ));
		}
	}
	$terms = $wpdb->get_results("SELECT term_id, slug FROM {$wpdb->terms} WHERE slug REGEXP('[^A-Za-z0-9\-]+') ");
	foreach ((array) $terms as $term) {
		$sanitized_slug = ctl_sanitize_title(urldecode($term->slug));
		if ($term->slug != $sanitized_slug) {
			$wpdb->update($wpdb->terms, array('slug' => $sanitized_slug), array('term_id' => $term->term_id));
		}
	}
}

function ctl_schedule_conversion() {
	add_action('shutdown', 'ctl_convert_existing_slugs');
}
register_activation_hook(__FILE__, 'ctl_schedule_conversion');

// Russian dates
function the_russian_time($tdate = '') {
	if(substr_count($tdate , '---') > 0) return str_replace('---', '', $tdate);
	$treplace = array(
	"Январь" => "января",
	"Февраль" => "февраля",
	"Март" => "марта",
	"Апрель" => "апреля",
	"Май" => "мая",
	"Июнь" => "июня",
	"Июль" => "июля",
	"Август" => "августа",
	"Сентябрь" => "сентября",
	"Октябрь" => "октября",
	"Ноябрь" => "ноября",
	"Декабрь" => "декабря",
	);
   	return strtr($tdate, $treplace);
}
add_filter('the_date', 'the_russian_time');
add_filter('get_the_time', 'the_russian_time');
add_filter('get_comment_date', 'the_russian_time');
add_filter('the_modified_time', 'the_russian_time');

?>