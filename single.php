<?php get_header(); ?>

<section id="content" class="container">

  <div class="row post-title">
    <h1><?php the_title(); ?></h1>
	<?php ay_date($post->ID, 'post', false); ?>
  </div>
	
  <?php while(have_posts()) { the_post();
  get_template_part('content');
  } ?>
			
  <div class="comments-section">
    
  </div>   
	
</section>
	
<?php get_footer(); ?>