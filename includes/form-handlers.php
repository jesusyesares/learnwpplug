<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Form handling functions.
 */

 /**
 * Save the form data to the database.
 * Check if the form is submitted and the nonce is valid.
 * Sanitize the form data and insert it into the subscriber custom post type.
 */
add_action('init', 'save_course_subscribe_form_data');
function save_course_subscribe_form_data() {
    if (!isset($_POST['subscribe_form_nonce']) || !wp_verify_nonce($_POST['subscribe_form_nonce'], 'subscribe_form')) {
        return;
    }

    if (isset($_POST['name']) && isset($_POST['address']) && isset($_POST['city']) && isset($_POST['email'])) {
        $name = sanitize_text_field($_POST['name']);
        $address = sanitize_text_field($_POST['address']);
        $city = sanitize_text_field($_POST['city']);
        $email = sanitize_email($_POST['email']);
        $course_id = intval($_POST['course_id']);
        // Save the form data as subscriber post type.
        $subscriber_id = wp_insert_post(array(
            'post_title' => 'New Subscriber ' . $name,
            'post_content' => '',
            'post_type' => 'subscriber',
            'post_status' => 'publish',
            // add meta data to the subscriber post
            'meta_input' => array(
                'course_id' => $course_id,
                'name' => $name,
                'address' => $address,
                'city' => $city,
                'email' => $email,
            )
        ));
    }

    // Redirect to the same page after form submission.
   wp_safe_redirect( esc_url( $_SERVER['REQUEST_URI'] ) );
}


/**
 * * Save the form data to the database.
 * Check if the form is submitted and the nonce is valid.
 * Sanitize the form data and insert it into the student custom post type.
 */

 add_action('init', 'save_student_form_data');
 function save_student_form_data() {
	 if (!isset($_POST['student_register_form_nonce']) || !wp_verify_nonce($_POST['student_register_form_nonce'], 'student_register_form')) {
		 return;
	 }
 
	 if (isset($_POST['student_name']) && isset($_POST['student_surname']) && isset($_POST['student_address']) && isset($_POST['student_city']) && isset($_POST['student_email'])) {
		 $student_name = sanitize_text_field($_POST['student_name']);
		 $student_surname = sanitize_text_field($_POST['student_surname']);
		 $student_address = sanitize_text_field($_POST['student_address']);
		 $student_city = sanitize_text_field($_POST['student_city']);
		 $student_email = sanitize_email($_POST['student_email']);
		 $student_id = intval($_POST['student_id']);
		 // Save the form data as student post type.
		 $student_id = wp_insert_post(array(
			 'post_title' => $student_name . ' ' . $student_surname,
			 'post_content' => '',
			 'post_type' => 'student',
			 'post_status' => 'publish',
			 // add meta data to the student post
			 'meta_input' => array(
				 'student_id' => $student_id,
				 'name' => $student_name,
				 'surname' => $student_surname,
				 'address' => $student_address,
				 'city' => $student_city,
				 'email' => $student_email,
			 )
		 ));  
	 }
 
	 // Redirect to the same page after form submission.
	 wp_safe_redirect( esc_url( $_SERVER['REQUEST_URI'] ) );
 }
 