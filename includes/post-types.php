<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Register custom post types.
 */

/**
 * Register the course custom post type.
 * show_in_rest is set to true to enable the Gutenberg editor for the custom post type.
 * supports custom-fields to enable custom fields in the Gutenberg editor.
 * rewrite is set to 'courses' to change the URL of the custom post type.
 * public is set to true to access the courses content from the front end.
 */
add_action('init', 'learnwpplug_register_course_cpt');
function learnwpplug_register_course_cpt() {
    register_post_type('course',
        array(
            'labels'      => array(
                'name'          => __('Courses', 'jyg-students'),
                'singular_name' => __('Course', 'jyg-students'),
                'add_new'       => __('Add New Course', 'jyg-students'),
                'add_new_item'       => __('Add New Course', 'jyg-students'),
                'new_item'       => __('New Course', 'jyg-students'),
                'edit_item'       => __('Edit Course', 'jyg-students'),
                'view_item'       => __('View Course', 'jyg-students'),
                'all_items'       => __('All Courses', 'jyg-students'),
            ),
            'public'      => true,
            'has_archive' => true,
            'show_in_rest' => true,
            'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'custom-fields' ),
            'rewrite'     => array( 'slug' => 'courses' ),

        )
    );
}

/**
 * Create a custom post type for subscribers.
 * show_in_rest is set to true to enable the Gutenberg editor for the custom post type.
 * supports custom-fields to enable custom fields in the Gutenberg editor.
 * rewrite is set to 'subscribers' to change the URL of the custom post type.
 * public is set to true to access the subscribers content from the front end.
 */

 add_action('init', 'learnwpplug_register_subscribers_cpt');
 // Create a custom post type for subscribers.
 function learnwpplug_register_subscribers_cpt(){
	 register_post_type('subscriber',
		 array(
			 'labels'      => array(
				 'name'          => __('Subscribers', 'jyg-students'),
				 'singular_name' => __('Subscriber', 'jyg-students'),
				 'add_new'       => __('Add New Subscriber', 'jyg-students'),
				 'add_new_item'       => __('Add New Subscriber', 'jyg-students'),
				 'new_item'       => __('New Subscriber', 'jyg-students'),
				 'edit_item'       => __('Edit Subscriber', 'jyg-students'),
				 'view_item'       => __('View Subscriber', 'jyg-students'),
				 'all_items'       => __('All Subscribers', 'jyg-students'),
			 ),
			 'public'      => true,
			 'has_archive' => true,
			 'show_in_rest' => true,
			 'supports' => array( 'title', 'custom-fields' ),
			 'rewrite'     => array( 'slug' => 'subscribers' ),
 
		 )
	 );
 }
 
 /**
 * * Register the student custom post type.
 * show_in_rest is set to true to enable the Gutenberg editor for the custom post type.
 * supports custom-fields to enable custom fields in the Gutenberg editor.
 * rewrite is set to 'students' to change the URL of the custom post type.
 * public is set to true to access the students content from the front end.
 */
add_action('init', 'learnwpplug_register_student_cpt');
function learnwpplug_register_student_cpt() {
    register_post_type('student',
        array(
            'labels'      => array(
                'name'          => __('Students', 'jyg-students'),
                'singular_name' => __('Student', 'jyg-students'),
                'add_new'       => __('Add New Student', 'jyg-students'),
                'add_new_item'       => __('Add New Student', 'jyg-students'),
                'new_item'       => __('New Student', 'jyg-students'),
                'edit_item'       => __('Edit Student', 'jyg-students'),
                'view_item'       => __('View Student', 'jyg-students'),
                'all_items'       => __('All Students', 'jyg-students'),
            ),
            'public'      => true,
            'has_archive' => true,
            'show_in_rest' => true,
            'supports' => array( 'title', 'custom-fields' ),
            'rewrite'     => array( 'slug' => 'students' ),
        )
    );
}