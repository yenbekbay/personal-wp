<?php get_header(); ?>

<section id="content" class="container">
	
  <div class="row project-title">
	
      <h1><?php the_title(); ?></h1>
      <?php $portfolio_link = get_portfolio_page_link(get_the_ID()); ?>	
      <nav id="portfolio-nav">
        <div class="prev"><?php next_post_link('%link'); ?></div>
        <div class="grid"><a href="<?php echo $portfolio_link; ?>">Back to All Portfolio Items</a></div> 
        <div class="next"><?php previous_post_link('%link'); ?></div> 
      </nav>
	  
  </div>
	
  <div class="row project-content">		
    <?php if(have_posts()) { while(have_posts()) { the_post(); ?>				
    <div id="project-description" class="col text">
      <?php the_content();?> 
    </div>
			
    <div id="sidebar" class="col">
							
      <div id="sidebar-wrapper">
					
        <?php ay_date($post->ID, 'project'); ?>
        <?php ay_project_link($post->ID); ?>
					
        <?php $project_attrs = get_the_terms($post->ID, 'project-attributes');
        if(!empty($project_attrs)){ ?>
<ul class="project-attrs checks"><?php
          foreach($project_attrs as $attr) {
            echo "\n".'          <li>'.$attr->name.'</li>';
          } ?>

        </ul>
      <?php } ?>
				
      </div>
				
    </div>			
    <?php } } ?>
		
  </div>

  <?php ay_like(); ?>
    
	
</section>
	
<?php get_footer(); ?>