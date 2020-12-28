<?php
function academy_files() {
    wp_enqueue_script('main-academy-js', get_theme_file_uri('/js/scripts-bundled.js'), NULL, '1.0', true);    
    // NULL-> no dependencies, true->put it in footer, not in header
    wp_enqueue_style('custom-google-fonts', 'https://fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
    wp_enqueue_style('font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
    wp_enqueue_style('academy_main_styles', get_stylesheet_uri());
}
add_action('wp_enqueue_scripts', 'academy_files');

function academy_features() {
    register_nav_menu('footerMenuOne', 'Footer Menu One');
    register_nav_menu('footerMenuTwo', 'Footer Menu Two');
    register_nav_menu('headerMenuLocation', 'Header Menu Location');
    add_theme_support('title-tag');
}

add_action('after_setup_theme', 'academy_features');