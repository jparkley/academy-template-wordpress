<?php get_header(); 
    while(have_posts()) {
        the_post(); ?>

  <div class="page-banner">
    <div class="page-banner__bg-image" style="background-image: url(<?php echo get_theme_file_uri('/images/ocean.jpg'); ?>);"></div>
    <div class="page-banner__content container container--narrow">
      <h1 class="page-banner__title"><?php the_title(); ?></h1>
      <div class="page-banner__intro"><p>TODO: DON'T FORGET TO REPLACE ME LATER </p></div>
    </div>  
  </div>

  <div class="container container--narrow page-section">
    <div class="metabox metabox--position-up metabox--with-home-link">
        <p><a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('event'); ?>"><i class="fa fa-home" aria-hidden="true"></i> Event Home</a> <span class="metabox__main"><?php the_title(); ?></span></p>
    </div>  

    <h1><?php the_title(); ?></h1>
        <div class="generic-content">
        <p><?php the_content(); ?></p>
        </div>
  </div>
<?php } ?>


<?php get_footer(); ?>