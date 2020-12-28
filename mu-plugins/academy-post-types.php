<?php

function academy_post_types() {
    register_post_type('event', array(
        'show_in_rest' => true, // This post type is available within REST API -> supports modern editor in admin.
        'supports' => array('title','editor', 'excerpt'),
        'has_archive' => true,
        'rewrite' => array(
            'slug' => 'events'
        ),
        'public' => true,
        'labels' => array(
            'name' => 'Events',
            'add_new_item' => 'Add New Event',
            'edit_item' => 'Edit Event',
            'all_items' => 'All Events',
            'singular_name' => 'Event'
        ),
        'menu_icon' => 'dashicons-calendar'
    ));
}

add_action('init', 'academy_post_types');