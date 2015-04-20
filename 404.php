<?php
header("HTTP/1.1 404 Not Found");
header("Status: 404 Not Found");
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset=<?php bloginfo('charset'); ?>" />

<title><?php _e('Page not found', 'yenbekbay');  echo " | "; bloginfo('name');?></title>
<link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/img/favicon.ico"/>
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/404.css" type="text/css" media="screen" />
</head>

<body>

  <div id="content" class="container"> 
  
    <h1 class="entry-title"><?php _e('404! Oh no!', 'yenbekbay'); ?></h1>
	
    <div class="entry-content">
      <p><?php _e('Sorry, but nothing exists here.', 'yenbekbay'); ?></p>
      <p><a href="<?php echo home_url(); ?>"><?php _e('Shall we return to home?', 'yenbekbay'); ?></a></p>
    </div>
	
  </div>
  
</body>

</html>
