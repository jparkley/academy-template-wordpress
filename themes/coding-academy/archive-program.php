<?php get_header(); ?>
    <div class="page-banner">
      <div class="page-banner__bg-image" style="background-image: url(<?php echo get_theme_file_uri('/images/library-hero.jpg'); ?>);"></div>
      <div class="page-banner__content container container--narrow">
        <h2 class="page-banner__title">Our Programs</h2>
        <div class="page-banner__intro"><p>Workshops and Events</p></div>        
      </div>
    </div>
    <div class="container container--narrow page-section">    
    <ul>
        <?php
        while(have_posts()) {
            the_post();
        ?>
        <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
        <hr class="section-break">
        <?php   } 
        echo paginate_links();
        ?>
    </ul>
    </div>
<?php get_footer(); ?>