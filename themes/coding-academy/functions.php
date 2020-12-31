<?php
require get_theme_file_path('/inc/search-route.php');

function academy_custom_rest() {
    register_rest_field('post', 'authorName', array(
        'get_callback' => function() { return get_the_author(); }
    ));
}
add_action('rest_api_init', 'academy_custom_rest');

function academy_files() {
    
    // NULL-> no dependencies, true->put it in footer, not in header
    wp_enqueue_style('custom-google-fonts', 'https://fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
    wp_enqueue_style('font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');

    // *** Using automatic workflow for local development
    if (strstr($_SERVER['SERVER_NAME'], '127.0.0.1')) {
        wp_enqueue_script('main-academy-js', 'http://localhost:3000/bundled.js', NULL, '1.0', true);    
    } else {
        wp_enqueue_script('our-vendors-js', get_theme_file_uri('/bundled-assets/vendors~scripts.9678b4003190d41dd438.js'), NULL, '1.0', true);
        wp_enqueue_script('main-academy-js', get_theme_file_uri('/bundled-assets/scripts.02007734402f5cb7fa1e.js'), NULL, '1.0', true);
        wp_enqueue_style('our-main-styles', get_stylesheet_uri('/bundled-assets/styles.02007734402f5cb7fa1e.css'));
    }
    // args (handle/name of main js, name to use in  js, array of data that we want available in js )
    wp_localize_script('main-academy-js', 'academyData', array(
        'root_url' => get_site_url()
    )); 
}
add_action('wp_enqueue_scripts', 'academy_files');

function academy_features() {
    register_nav_menu('footerMenuOne', 'Footer Menu One');
    register_nav_menu('footerMenuTwo', 'Footer Menu Two');
    register_nav_menu('headerMenuLocation', 'Header Menu Location');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_image_size('professor-horizontal', 400, 260, true); // true: crop in the center
    add_image_size('professor-vertical', 480, 650, true);
    add_image_size('banner', 1500, 350, true);
}

add_action('after_setup_theme', 'academy_features');

function academy_adjust_queries($query) {
    $today = date('Ymd');
    if (!is_admin() AND is_post_type_archive('event') AND $query->is_main_query()) {
        $query->set('posts_per_page', '5');
        $query->set('meta_key', 'event_date');
        $query->set('orderby', 'meta_value_num');
        $query->set('order', 'ASC');
        $query->set('meta_query', array(array(
            'key'=>'event_date',
            'compare' => '>=',
            'value' => $today,
            'type' => 'numeric'
        )));
    }
    
    if (!is_admin() AND is_post_type_archive('program') AND $query->is_main_query()) {
        $query->set('posts_per_page', -1);
        $query->set('orderby', 'title');
        $query->set('order', 'ASC');
    }
}

add_action('pre_get_posts', 'academy_adjust_queries');


function page_banner($args = NULL) {
    if (!$args['title']) { $args['title'] = get_the_title(); }
    if (!$args['subtitle']) { $args['subtitle'] = get_field('page_banner_subtitle'); }
    if (!$args['image']) { 
        if (get_field('page_banner_background_image')) {
            $args['image'] = get_field('page_banner_background_image')['sizes']['banner']; 
        } else {
        $args['image'] = get_theme_file_uri('/images/library-hero.jpg'); 
        }
    }
    ?>
    <div class="page-banner">
    <div class="page-banner__bg-image" style="background-image: url(<?php echo $args['image'];?>);"></div>
    <div class="page-banner__content container container--narrow">
      <h1 class="page-banner__title"><?php echo $args['title'] ?></h1>
      <div class="page-banner__intro"><p><?php echo $args['subtitle'] ?></p></div>
    </div>  
  </div>
<?php    
}