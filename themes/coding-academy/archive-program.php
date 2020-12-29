<?php get_header(); 
      page_banner(array(
        'title' => 'Our Programs',
        'subtitle' => 'There is something for everyone.  Have a look around.'
      ));
?>
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