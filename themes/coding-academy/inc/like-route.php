<?php
function academy_like_route() {
    register_rest_route('academy/v1', 'manageLike', array(
        'methods' => 'POST',
        'callback' => 'create_like'
    ));

    register_rest_route('academy/v1', 'manageLike', array(
        'methods' => 'DELETE',
        'callback' => 'delete_like'
    ));    
}

function create_like($data) {
    if (is_user_logged_in()) {
        $professor = sanitize_text_field($data['professor']); 
        $existQuery = new WP_Query(array(
            'author' => get_current_user_id(),
            'post_type' => 'like',
            'meta_query' => array(
              array(
                'key' => 'liked_professor_id',
                'compare' => '=',
                'value' => $professor
              ))
          ));        
        if ($existQuery->found_posts == 0 AND get_post_type($professor) == 'professor') {   
            return wp_insert_post(array(
                'post_type' => 'like',
                'post_title' => 'Like Professor: ' . $professor,
                'post_status' => 'publish',
                'meta_input' => array(
                    'liked_professor_id' => $professor
                )
            ));    
    
        } else {
            die("Invalid Professor ID");
        }
    } else {
        die("Only logged in users can create a like.");
    }
}

function delete_like($data) {
    $likeId = sanitize_text_field($data['like']);
    if (get_current_user_id() == get_post_field('post_author', $likeId) AND
        get_post_type($likeId) == 'like') {
            wp_delete_post($likeId, true);
            return "Succesfully deleted your like.";
        } else {
            die("You do not have permission to delete this like.");
        }
}
add_action('rest_api_init', 'academy_like_route');