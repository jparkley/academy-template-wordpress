<?php 
    get_header(); 
    while(have_posts()) {
        the_post();
        page_banner();
?>
  <div class="container container--narrow page-section">
    <?php
        $parentId = wp_get_post_parent_id(get_the_ID());
        if($parentId) { ?>
        <div class="metabox metabox--position-up metabox--with-home-link">
        <p><a class="metabox__blog-home-link" href="<?php echo get_permalink($parentId); ?>"><i class="fa fa-home" aria-hidden="true"></i> Back to <?php echo get_the_title($parentId); ?></a> <span class="metabox__main"><?php the_title(); ?></span></p>
        </div>  
    <?php } 
    
    $isParent = get_pages(array(
        'child_of' => get_the_ID()
    ));
    // only if it's a child page(parentId is not 0) or it's a parent page(isParent is not null)
    if ($parentId or $isParent) {         
    ?>      
    <div class="page-links">
      <h2 class="page-links__title"><a href="<?php echo get_permalink($parentId); ?>"><?php echo get_the_title($parentId); ?></a></h2> <!-- if $parentId == 0, it returns this page's title and url -->
      <ul class="min-list">
        <?php 
            if ($parentId) {
                $findChildrenOf = $parentId;
            } else {
                $findChildrenOf = get_the_ID();
            }            
            wp_list_pages(array(
                'title_li'=> NULL,
                'child_of'=> $findChildrenOf,
                'sort_column' => 'menu_order' // Assign numeric value to each page from the admin interface
            ));
        ?>
      </ul>
    </div>
    <?php } ?> <!-- End of if -->
    
    <div class="generic-content">
        <!-- This is the traditional PHP search (just in case user disables javascript) -->
        <?php get_search_form(); ?>
    </div>
  </div>

  <?php 
    } // End of while
    get_footer(); ?>