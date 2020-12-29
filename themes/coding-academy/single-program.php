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
        <p><a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('event'); ?>"><i class="fa fa-home" aria-hidden="true"></i> Our Programs</a> <span class="metabox__main"><?php the_title(); ?></span></p>
    </div>  
    <h1><?php the_title(); ?></h1>
    <div class="generic-content"><p><?php the_content(); ?></p></div>

    <?php
        // Display related events for this program
        $today = date('Ymd'); 
        $events = new WP_Query(array(
            'posts_per_page' => 2,
            'post_type' => 'event',
            'meta_key' => 'event_date',
            'orderby' => 'meta_value_num',
            'order' => 'ASC',
            'meta_query' => array(array(
                'key' => 'event_date',
                'compare' => '>=',
                'value' => $today,
                'type' => 'numeric'
            ), array(
                'key' => 'related_programs',
                'compare' => 'LIKE',
                'value' => '"' . get_the_ID() . '"'
            ))
            // related_programs: array is saved as a string after serialization
            // ex: array(12,120,120) => 'a:3:{i:0;i:12;i:1;i:120;i:2;i:120;}
            // so the value should be in the quotation mark ""
        ));

        if ($events->have_posts()) {
            echo '<hr class="section-break">';     
            echo '<h2 class="mb-2 headline headline--small">Upcoming ' . get_the_title() . ' Programs</h2>';
            echo '<ul class="link-list">';
            while($events->have_posts()) {
            $events->the_post(); 
            ?>
            <div class="event-summary">
                <a class="event-summary__date t-center" href="#">
                <span class="event-summary__month"><?php 
                $eventDate = new DateTime(get_field('event_date'));
                echo $eventDate->format('M'); ?></span>
                <span class="event-summary__day"><?php echo $eventDate->format('d'); ?></span>
                </a>
                <div class="event-summary__content">
                <h5 class="event-summary__title headline headline--tiny"><a class="link-list__dark-gray" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
                <p><?php 
                if (has_excerpt()) { echo get_the_excerpt(); }  // the_excerpt(): adds its own format, not working for our site
                else { echo wp_trim_words(get_the_content(), 20); } ?> <a href="<?php the_permalink(); ?>" class="nu gray">Learn more</a></p>
                </div>
            </div>
            <?php
            }
        }
    ?>
  </div>
<?php } ?>

<?php get_footer(); ?>