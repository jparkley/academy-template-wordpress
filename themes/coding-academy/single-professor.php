<?php get_header(); 
    while(have_posts()) {
        the_post(); 
        page_banner();
        ?>

  <div class="container container--narrow page-section">    
    <div class="generic-content">
        <div class="row group">
            <div class="one-third"><?php the_post_thumbnail('professor-vertical'); ?></div>
            <div class="two-thirds"><?php the_content(); ?></div>            
        </div>        
    </div>
    <?php 
      $relatedPrograms = get_field('related_programs');
      if ($relatedPrograms) {     
        echo '<hr class="section-break"';     
        echo '<h2 class="headline headline--medium">Subject(s) Taught</h2>';
        echo '<ul class="link-list min-list">';
        foreach($relatedPrograms as $program) { ?>
          <li><a class="text-dark" href="<?php echo get_the_permalink($program); ?>"><?php echo get_the_title($program); ?></a></li>            
      <?php           
        }
      echo '</ul>';
      } ?>
  </div>
<?php } ?>

<?php get_footer(); ?>