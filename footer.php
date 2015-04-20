<section id="footer">

  <div class="container clearfix">
    <div class="alignleft">
      <p class="copyright">&copy; 2013-<?php echo date('Y'); ?> <?php bloginfo('name'); ?></p>
      <p class="email"><a href="mailto:<?php bloginfo('admin_email'); ?>"><?php bloginfo('admin_email'); ?></a></p>
    </div>
    <div class="alignright">
      <?php ay_pixelated_square('vk-button', '#41638f', '//vk.com/yenbekbay'); ?>
      <?php ay_pixelated_square('twitter-button', '#00acee', '//twitter.com/yenbekbay'); ?>
      <?php ay_pixelated_square('vimeo-button', '#a59d6d', '//vimeo.com/yenbekbay'); ?>
      <?php ay_pixelated_square('insta-button', '#517fa4', '//instagram.com/yenbekbay'); ?>
      <?php ay_pixelated_square('fb-button', '#3B5998', '//fb.com/yenbekbay'); ?>
    </div>
  </div>
</section>

<?php ay_plusone_js(); ?>

<?php wp_footer(); ?>	

<?php include_once('tracking.php') ?>

</body>
</html>