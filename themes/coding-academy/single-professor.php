<?php get_header(); 
    while(have_posts()) {
        the_post(); 
        page_banner();
        ?>

  <div class="container container--narrow page-section">    
    <div class="generic-content">
        <div class="row group">
            <div class="one-third"><?php the_post_thumbnail('professor-vertical'); ?></div>
            <div class="two-thirds">
              <?php 
                $likeCount = new WP_Query(array(
                  'post_type' => 'like',
                  'meta_query' => array(
                    array(
                      'key'=> 'liked_professor_id',
                      'compare' => '=',
                      'value' => get_the_ID()
                    ))
                ));

                // To see if the current user has already liked this professor
                $existStatus = 'no';
                // When the user is not logged-in,  get_current_user_id() returns 0
                // 'author' => 0: bring all the authors, so the query is not accurate, so put in if()
                if (is_user_logged_in()) {
                  $existQuery = new WP_Query(array(
                    'author' => get_current_user_id(),
                    'post_type' => 'like',
                    'meta_query' => array(
                      array(
                        'key' => 'liked_professor_id',
                        'compare' => '=',
                        'value' => get_the_ID()
                      ))
                  ));
                  if ($existQuery->found_posts) {
                    $existStatus = 'yes';
                  }  
                }
              ?>
              <span class="like-box" data-likeid="<?php echo $existQuery->posts[0]->ID; ?>" data-professor="<?php the_ID(); ?>" data-exists="<?php echo $existStatus; ?>">
                <i class="fa fa-heart-o" aria-hidden="true"></i>
                <i class="fa fa-heart" aria-hidden="true"></i>
                <span class="like-count"><?php echo $likeCount->found_posts; ?></span>
              </span>
              <?php the_content(); ?>
            </div>            
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