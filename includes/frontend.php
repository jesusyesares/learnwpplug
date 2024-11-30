<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Frontend functions.
 */

 /**
 * * Show the student fields in the frontend in each student post.
 * The student fields are retrieved from the student custom post type.
 * ! https://chatgpt.com/g/g-674a0939b10481918ae90db180ff77e4-profesor-desarrollo-wordpress/c/674a0c8d-d450-8001-9f09-b1798c672aaf
 */

add_filter('the_content', 'show_student_fields_with_map');
function show_student_fields_with_map($content) {
    if ( is_singular( 'student' ) ) { // Verifies if the current page is a student post.
        // Get the student fields from the student custom post type.
        $student_name = get_post_meta(get_the_ID(), 'name', true);
        $student_surname = get_post_meta(get_the_ID(), 'surname', true);
        $student_address = get_post_meta(get_the_ID(), 'address', true);
        $student_city = get_post_meta(get_the_ID(), 'city', true);
        $student_email = get_post_meta(get_the_ID(), 'email', true);
        // Display the student fields in the frontend.
        $content .= '<p><strong>Name: </strong>' . $student_name . '</p>';
        $content .= '<p><strong>Surname: </strong>' . $student_surname . '</p>';
        $content .= '<p><strong>Address: </strong>' . $student_address . '</p>';
        $content .= '<p><strong>City: </strong>' . esc_html($student_city) . '</p>';
        $content .= '<p><strong>Email: </strong>' . $student_email . '</p>';
        $content .= '<div id="student_map"></div>';

        // No inline JavaScript needed here since it's moved to an external file.
    }

    // Return the content with the student fields.
    return $content;
}