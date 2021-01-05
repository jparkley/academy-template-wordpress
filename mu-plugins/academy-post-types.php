<?php

function academy_post_types() {
    // Custom post type registration: Event
    register_post_type('event', array(
        'show_in_rest' => true, // This post type is available within REST API
        'supports' => array('title','editor', 'excerpt'),
        'capability_type' => 'event', // To grant event permissions. (default is 'post')
        'map_meta_cap' => true, // To clarify which capabilities it has and when it needs (automatic map of capabilities)
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

    // Custom post type registration: Program
    register_post_type('program', array(
        'show_in_rest' => true,
        'supports' => array('title','editor', 'excerpt'),
        'capability_type' => 'program',
        'map_meta_cap' => true,
        'has_archive' => true,
        'rewrite' => array(
            'slug' => 'programs'
        ),
        'public' => true,
        'labels' => array(
            'name' => 'Programs',
            'add_new_item' => 'Add New Program',
            'edit_item' => 'Edit Program',
            'all_items' => 'All Programs',
            'singular_name' => 'Program'
        ),
        'menu_icon' => 'dashicons-awards'
    ));

    // Custom post type registration: Professor
    register_post_type('professor', array(
        'show_in_rest' => true,
        'supports' => array('title','editor', 'excerpt','thumbnail'),
        'public' => true,
        'labels' => array(
            'name' => 'Professors',
            'add_new_item' => 'Add New Professor',
            'edit_item' => 'Edit Professor',
            'all_items' => 'All Professors',
            'singular_name' => 'Professor'
        ),
        'menu_icon' => 'dashicons-welcome-learn-more'
    ));

    // Custom post type registration: My Notes
    register_post_type('note', array(
        'show_in_rest' => true,
        'supports' => array('title','editor'),
        // Brand new capability type -> note.  See above comments for details
        'capability_type' => 'note', 
        'map_meta_cap' => true,
        'public' => false, // Notes are available only to the user who created, not to public
        'show_ui' => true, // Show this in the admin dashboard
        'labels' => array(
            'name' => 'Notes',
            'add_new_item' => 'Add New Note',
            'edit_item' => 'Edit Note',
            'all_items' => 'All Notess',
            'singular_name' => 'Note'
        ),
        'menu_icon' => 'dashicons-welcome-write-blog'
    ));    

    // Custom post type registration: Like (heart) for professors
    register_post_type('like', array(        
        'supports' => array('title'),
        'public' => false, // Notes are available only to the user who created, not to public
        'show_ui' => true, // Show this in the admin dashboard
        'labels' => array(
            'name' => 'Likes',
            'add_new_item' => 'Add New Like',
            'edit_item' => 'Edit Like',
            'all_items' => 'All Likes',
            'singular_name' => 'Like'
        ),
        'menu_icon' => 'dashicons-heart'
    ));      
}

add_action('init', 'academy_post_types');