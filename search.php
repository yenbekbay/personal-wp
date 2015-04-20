<?php get_header(); ?>

<script>
jQuery(document).ready(function($){	
  var container = $('#search-results')
  $(window).load(function(){
    container.isotope({
      itemSelector: '.result',
      masonry: { columnWidth: $('#search-results').width() / 3 }
    });	
    container.css('visibility','visible');			
    });
	
    $(window).smartresize(function(){
      container.isotope({
        masonry: { columnWidth: $('#search-results').width() / 3 }
      });
    });
});
</script>

<section id="content" class="container">
	
  <div class="row page-title page-header-no-bg">
    <h1><?php echo __('Results for', 'yenbekbay'); ?><span> "<?php the_search_query(); ?>"</span></h1>
  </div>
	
  <div id="search-results" class="row">					
    <?php if(have_posts()) { while(have_posts()) { the_post(); ?>
    <article class="result">
	
    <?php if( get_post_type($post->ID) == 'post') { ?>
      <h2 class="title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a><span><?php echo __('Blog Post', 'yenbekbay'); ?></span></h2>	
    <?php } ?>
						
    <?php if( get_post_type($post->ID) == 'page') { ?>
      <h2 class="title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a><span><?php echo __('Page', 'yenbekbay'); ?></span></h2>	
      <?php if(has_excerpt()) the_excerpt(); ?>
    <?php } ?>
						
    <?php if( get_post_type($post->ID) == 'portfolio') { ?>
      <h2 class="title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a><span><?php echo __('Project', 'yenbekbay'); ?></span></h2>	
    <?php } ?>
						
    </article><?php }
	} else { echo "<p>".__('No results found', 'yenbekbay')."</p>"; } ?>
			
  </div>
			
  <?php if( get_next_posts_link() || get_previous_posts_link() ) { ?>
    <div id="pagination">
      <div class="prev"><?php previous_posts_link('&laquo; Previous Entries') ?></div>
      <div class="next"><?php next_posts_link('Next Entries &raquo;','') ?></div>
    </div>	
  <?php }?>
	
</section>

<?php get_footer(); ?>