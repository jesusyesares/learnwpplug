<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Admin functions.
 */

 /**
 * Show the subscriber entries per course in the admin column.
 * Get the number of subscriber entries per course and display it in the admin column.
 */
add_filter('manage_course_posts_columns', 'course_subscribe_column');
function course_subscribe_column($columns) {
    $columns['subscribers'] = 'Subscribers';
    return $columns;
}

/**
 * Display the subscriber entries per course in the admin column.
 * Get the number of subscriber entries per course and display it in the admin column.
 */
add_action('manage_course_posts_custom_column', 'course_subscribe_column_content', 10, 2);
function course_subscribe_column_content($column, $post_id) {
    if ($column === 'subscribers') {
        $subscribers = get_posts(array(
            'post_type' => 'subscriber',
            'meta_query' => array(
                array(
                    'key' => 'course_id',
                    'value' => $post_id,
                    'compare' => '=',
                )
				),
				'fields' => 'ids',
            	'posts_per_page' => -1,
        ));
        echo count($subscribers);
    }
}
