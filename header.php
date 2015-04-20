<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>">
<meta name="robots" content="index, follow">
<meta name="description" content="<?php echo ay_description(); ?>"/>
<meta name="author" content="Аян Енбекбай">
<?php ay_opengraph_tags(); ?>
<?php ay_twitter_card(); ?>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="google-site-verification" content="PHADAVGUPZVSZGNvyTeIHM0ljzIhBSuD_b01ENqfOmQ">
<meta name='yandex-verification' content='5bf1e3005ba5d968'>

<title><?php ay_title(); ?></title>
<link rel='shortcut icon' href='<?php echo get_template_directory_uri(); ?>/img/favicon.ico'>
<link rel='apple-touch-icon-precomposed' href='<?php echo get_template_directory_uri(); ?>/img/apple-touch-icon.png'>
<link rel='image_src' href='<?php echo ay_image(); ?>'>
<?php if(!is_page()) { ?>
<link rel='alternate' type='application/rss+xml' title='<?php bloginfo('name'); ?> - <?php _e('Feed', 'yenbekbay'); ?>' href='<?php bloginfo('rss2_url'); ?>'>
<?php } ?>

<?php wp_head(); ?>
</head>

<body <?php body_class();?>>

<section id="header">	
	
  <div id="header-wrapper" class="container clearfix">
  
    <?php if(!is_front_page()) { ay_pixelated_square('logo', '#fa113d', home_url()); } else { ay_pixelated_square('logo', '#fa113d'); } ?>
	
    <div id="primary-nav">
      <?php if(has_nav_menu('primary-menu')) {
        wp_nav_menu( array('theme_location' => 'primary-menu', 'container' => '', 'menu_id' => 'primary-menu', 'items_wrap' => '<ul id="%1$s" class="%2$s">'."\n".'  %3$s    </ul>'."\n", 'walker' => new Cleaner_Walker_Nav_Menu() ));
      } ?>
    </div>  
    
  </div>
  
</section>
