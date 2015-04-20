<?php get_header(); ?>

<section id="content" class="container">

    <div id="entries">

      <p>Скоро.</p>
    <?php while(have_posts()) { the_post();
      get_template_part('content');
    }

	if(get_next_posts_link() || get_previous_posts_link()) { ?><nav id="pagination">
      <div class="prev"><?php previous_posts_link(__('&laquo; Previous posts', '')); ?></div>
      <div class="next"><?php next_posts_link(__('Next posts &raquo;','')); ?></div>
    </nav><?php } ?>
	
 
  </div>
  
</section>
	
<?php get_footer(); ?>