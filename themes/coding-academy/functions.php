<?php
function academy_files() {
    
    // NULL-> no dependencies, true->put it in footer, not in header
    wp_enqueue_style('custom-google-fonts', 'https://fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
    wp_enqueue_style('font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');

    // *** Using automatic workflow for local development
    if (strstr($_SERVER['SERVER_NAME'], '127.0.0.1')) {
        wp_enqueue_script('main-academy-js', 'http://localhost:3000/bundled.js', NULL, '1.0', true);    
    } else {
        wp_enqueue_script('our-vendors-js', get_theme_file_uri('/bundled-assets/vendors~scripts.8c97d901916ad616a264.js'), NULL, '1.0', true);
        wp_enqueue_script('main-academy-js', get_theme_file_uri('/bundled-assets/scripts.bc49dbb23afb98cfc0f7.js'), NULL, '1.0', true);
        wp_enqueue_style('our-main-styles', get_stylesheet_uri('/bundled-assets/styles.bc49dbb23afb98cfc0f7.css'));
    }
}
add_action('wp_enqueue_scripts', 'academy_files');

function academy_features() {
    register_nav_menu('footerMenuOne', 'Footer Menu One');
    register_nav_menu('footerMenuTwo', 'Footer Menu Two');
    register_nav_menu('headerMenuLocation', 'Header Menu Location');
    add_theme_support('title-tag');
}

add_action('after_setup_theme', 'academy_features');