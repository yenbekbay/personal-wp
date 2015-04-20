<?php get_header(); ?>

<?php ay_page_header($post->ID); ?>

<div class="container main-content">
	
	<div class="row">

		<?php if(have_posts()) : while(have_posts()) : the_post(); ?>
			
			<?php the_content(); ?>

		<?php endwhile; endif; ?>
			

	</div>
	
</div>

<?php get_footer(); ?>