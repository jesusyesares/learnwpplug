<?php
/**
 * Plugin Name: Courses Custom Post Type
 * Plugin URI:
 * Description: A plugin to create a custom post type for students.
 * Version: 0.3.0
 * Text Domain: jyg-students
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

// Define plugin constants
define( 'MY_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'MY_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// Include required files
require_once MY_PLUGIN_DIR . 'includes/shortcodes.php';
require_once MY_PLUGIN_DIR . 'includes/form-handlers.php';
require_once MY_PLUGIN_DIR . 'includes/enqueue-scripts.php';
require_once MY_PLUGIN_DIR . 'includes/admin.php';
require_once MY_PLUGIN_DIR . 'includes/frontend.php';

/**
 * Let's create a custom post type for courses and a custom taxonomy for the subject.
 * We will also create a subscribe form on the single course page to allow users to subscribe to the course.
 * The form data will be saved as a custom post type for subscribers.
 * We will display the number of subscribers per course in the admin column.
 *
 *
 * Here are the WordPress action and filter hooks used in this example:
 *
 * ### Action Hooks
 * - init
 * callbacks:
 *      course_post_type
 *      course_taxonomy
 *      save_course_subscribe_form_data
 *      subscribers_cpt
 *      manage_course_posts_custom_column
 *      course_subscribe_column_content
 *      manage_course_posts_columns
 *      course_subscribe_column
 *
 * ### Filter Hooks
 * - manage_course_posts_columns
 * callback:
 *       course_subscribe_column
 */

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
















