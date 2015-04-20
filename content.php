<article id="post-<?php the_ID(); ?>" <?php post_class('text'); ?>>		
    <?php if(has_post_thumbnail()) {
      if(!is_single()) {  echo '<a class="post-featured-img" href="' . get_permalink() . '">'; } 
      echo get_the_post_thumbnail($post->ID, 'full', array('title' => ''));
      if(!is_single()) {  echo'</a>'; }
    }
    if(!is_single() ) { ?>  <div class="post-header">
        <h2 class="post-title">
          <?php if(!is_single()) { ?><a href="<?php the_permalink(); ?>"><?php } ?><?php the_title(); ?><?php if( !is_single() ) {?> </a> <?php } ?>
			
        </h2>
        <?php ay_date($post->ID, 'post', false); ?>
      </div>
      <?php } 
      the_content('<span>'.__("Read more &raquo;", 'yenbekbay').'</span>');
    if(is_single() && has_tag()) {					
      echo '<div class="post-tags"><h4>Tags: </h4>'; 
      the_tags('','','');
      echo '<div class="clear"></div></div> ';
    } ?>    </article>

    