<?php get_header(); 
    while(have_posts()) {
        the_post();
        page_banner();
?>
  <div class="container container--narrow page-section">
    <div class="metabox metabox--position-up metabox--with-home-link">
        <p><a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('event'); ?>"><i class="fa fa-home" aria-hidden="true"></i> Our Programs</a> <span class="metabox__main"><?php the_title(); ?></span></p>
    </div>  
    <h1><?php the_title(); ?></h1>
    <div class="generic-content"><p><?php the_content(); ?></p></div>

    <?php
        // Display related professors for this program
        $professors = new WP_Query(array(
            'posts_per_page' => -1,
            'post_type' => 'professor',
            'orderby' => 'meta_value',
            'order' => 'ASC',
            'meta_query' => array(array(
                'key' => 'related_programs',
                'compare' => 'LIKE',
                'value' => '"' . get_the_ID() . '"'
            ))
            // related_programs: array is saved as a string after serialization
            // ex: array(12,120,120) => 'a:3:{i:0;i:12;i:1;i:120;i:2;i:120;}
            // so the value should be in the quotation mark ""
        ));
        if ($professors->have_posts()) {
            echo '<hr class="section-break">';     
            echo '<h2 class="mb-2 headline headline--small">' . get_the_title() . ' Professors</h2>';
            echo '<ul class="professor-cards">';
            while($professors->have_posts()) {
                $professors->the_post(); 
            ?>
            <li class="professor-card__list-item"><a class="professor-card" href="<?php the_permalink(); ?>">
                <img class="professor-card__image" src="<?php the_post_thumbnail_url('professor-horizontal'); ?>" /><span class="professor-card__name"><?php the_title(); ?></span></a>
            </li>
            <?php
            }
        }
        echo '</ul>';
        wp_reset_postdata(); // Resets the global post objects and functions like 'the_title()','get_the_ID()'.

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
            get_template_part('template-parts/content-event');
            ?>
            <?php
            }
            echo '</ul>';
        }
    ?>
  </div>
<?php } ?>

<?php get_footer(); ?>