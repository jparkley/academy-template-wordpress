<?php
require get_theme_file_path('/inc/search-route.php');

function academy_custom_rest() {
    register_rest_field('post', 'authorName', array(
        'get_callback' => function() { return get_the_author(); }
    ));

    register_rest_field('note', 'userNoteCount', array(
        'get_callback' => function() { return count_user_posts(get_current_user_id(), 'note'); }
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
        wp_enqueue_script('main-academy-js', get_theme_file_uri('/bundled-assets/scripts.fe5a666d93a214e8cc9a.js'), NULL, '1.0', true);
        wp_enqueue_style('our-main-styles', get_stylesheet_uri('/bundled-assets/styles.fe5a666d93a214e8cc9a.css'));
    }
    // args (handle/name of main js, name to use in  js, array of data that we want available in js )
    wp_localize_script('main-academy-js', 'academyData', array(
        'root_url' => get_site_url(),
        'nonce' => wp_create_nonce('wp_rest')
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

// Redirect subscribers out of admin and onto homepage
function redirectSubsToFrontend() {
    $ourCurrentUser = wp_get_current_user();
    if (count($ourCurrentUser->roles)==1 && $ourCurrentUser->roles[0] == 'subscriber') {
        wp_redirect(site_url('/'));
        exit;
    }
}
add_action('admin_init', 'redirectSubsToFrontend');

// Do not show admin bar to subscribers
function noSubsAdminBar() {
    $ourCurrentUser = wp_get_current_user();
    if (count($ourCurrentUser->roles)==1 && $ourCurrentUser->roles[0] == 'subscriber') {
        show_admin_bar(false);        
    }
}
add_action('wp_loaded', 'noSubsAdminBar');

function getOurHeaderUrl() {
    return esc_url(site_url('/'));
}
add_filter('login_headerurl', 'getOurHeaderUrl');

function getOurLoginCSS() {
    wp_enqueue_style('custom-google-fonts', 'https://fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
    wp_enqueue_style('our-main-styles', get_stylesheet_uri('/bundled-assets/styles.fe5a666d93a214e8cc9a.css'));    
}
add_action('login_enqueue_scripts', 'getOurLoginCSS');

function getOurLoginTitle() {
    return get_bloginfo('name');
}
add_filter('login_headertitle', 'getOurLoginTitle');


// Force note posts to be private
function makeNotesPrivate($data, $postarr) {
    
    if ($data['post_type'] == 'note') {
        // Limit note count that a subscriber can create
        // !$postarr['ID']: only when there is no ID, meaning only for creating, not for updating
        if (count_user_posts(get_current_user_id(), 'note') > 3 AND !$postarr['ID']) {
            die("You have reached your note limit.");
        }        
        $data['post_content'] = sanitize_textarea_field($data['post_content']);
        $data['post_title'] = sanitize_text_field($data['post_title']);
    }
    if ($data['post_type'] == 'note' AND $data['post_status'] != 'trash') {
        $data['post_status'] = 'private';
    }
    return $data;
}
// 'wp_insert_post_data' hook applies both for inserting and updating
// 10: priority (not applied in this case), 2: passing two parameters
add_filter('wp_insert_post_data', 'makeNotesPrivate', 10, 2); 