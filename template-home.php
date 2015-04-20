<?php 
/**
 * Template Name: [:en]Homepage[:ru]Главная
 */
get_header(); 
ay_page_header($post->ID);
?>

<section id="content" class="container">

  <?php ay_portfolio('4', false); ?>

  <?php ay_like(); ?>
  
</section>
	
<?php get_footer(); ?>