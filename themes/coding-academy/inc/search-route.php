<?php

function academy_register_search() {
    // Args(namespace in URL, routing info in URL, what should happen with API call)
    register_rest_route('academy/v1', 'search', array(   
        'methods' => WP_REST_SERVER::READABLE, //-> 'GET'
        'callback' => 'academy_search_results'
    )); 
}
function academy_search_results($data) {
    $mainQuery = new WP_Query(array(
        'post_type' => array('post', 'page', 'professor', 'event', 'program'),
        's' => sanitize_text_field($data['term'])
    ));
    $results = array(
        'generalInfo' => array(),
        'programs' => array(),
        'professors' => array(),
        'events' => array()
    );
    while($mainQuery->have_posts()) {
        $mainQuery->the_post();

        if (get_post_type() == 'post' OR get_post_type() == 'page') {
            array_push($results['generalInfo'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink()
            ));    
        }
        if (get_post_type() == 'program') {
            array_push($results['programs'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink()
            ));    
        }
        if (get_post_type() == 'event') {
            $eventDate = new DateTime(get_field('event_date'));
            $desc = NULL;
            if (has_excerpt()) { $desc =  get_the_excerpt(); }  
            else { $desc = wp_trim_words(get_the_content(), 20); } 

            array_push($results['events'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'month' => $eventDate->format('M'),
                'day' => $eventDate->format('d'),
                'desc' => $desc
            ));    
        }
        if (get_post_type() == 'professor') {
            array_push($results['professors'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'image' => get_the_post_thumbnail_url(0, 'professor-horizontal')
            ));    
        }
    }
    return $results;
}
add_action("rest_api_init", "academy_register_search");