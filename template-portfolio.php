<?php 
/**
 * Template Name:  [:en]Portfolio[:ru]Портфолио
 */
get_header(); 
ay_page_header($post->ID);
?>

<section id="content" class="container">
  
  <?php ay_portfolio();
	
	if(get_next_posts_link() || get_previous_posts_link()) { ?>
		<nav id="pagination">
			<div class="prev"><?php previous_posts_link(__('&laquo; Previous Entries', '')); ?></div>
			<div class="next"><?php next_posts_link(__('Next Entries &raquo;','')); ?></div>
		</nav>
	<?php } ?>
	
  <?php ay_like(); ?>
  

</section>

<?php get_footer(); ?>