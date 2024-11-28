<?php
/**
 * Plugin Name: Courses Custom Post Type
 * Plugin URI:
 * Description: A plugin to create a custom post type for students.
 * Version: 0.1.0
 * Text Domain: jyg-students
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

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
 * Register the course custom post type.
 * show_in_rest is set to true to enable the Gutenberg editor for the custom post type.
 * supports custom-fields to enable custom fields in the Gutenberg editor.
 * rewrite is set to 'courses' to change the URL of the custom post type.
 * public is set to true to access the courses content from the front end.
 */
add_action('init', 'course_post_type');
function course_post_type() {
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
 * Register the subject taxonomy for the course custom post type.
 * show_in_rest is set to true to enable the Gutenberg editor for the custom taxonomy.
 * hierarchical is set to true to create a hierarchical taxonomy.
 * show_admin_column is set to true to display the taxonomy in the admin column.
 */

add_action('init', 'course_taxonomy');
function course_taxonomy() {
    register_taxonomy(
        'course_subject',
        'course',
        array(
            'label' => __('Subject'),
            'rewrite' => array('slug' => 'subject'),
            'hierarchical' => true,
            'show_admin_column' => true,
            'show_in_rest' => true,
        )
    );
}


/**
 * Add a subscribe form with name and email on the single course page to allow users to submit the form.
 * Use the shortcode [course_subscribe_form] to display the form on the single course page.
 * The form contains name, email, address, city, and a hidden field to store the course CPT ID.
 */
add_shortcode( 'course_subscribe_form', 'course_subscribe_form' );
function course_subscribe_form() {
    ob_start();
    ?>
    <form action="" method="post">
        <label for="name">Name:</label>
        <input type="text" name="name" id="name" required><br>
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required><br>
        <label for="address">Address:</label>
        <input type="text" name="address" id="address"><br>
        <label for="city">City:</label>
        <input type="text" name="city" id="city"><br>
        <input type="hidden" name="course_id" value="<?php echo get_the_ID(); ?>">
        <?php wp_nonce_field('subscribe_form', 'subscribe_form_nonce'); ?>
        <input type="submit" value="Subscribe">
    </form>
    <?php
    return ob_get_clean();
}

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
 * Create a custom post type for subscribers.
 * show_in_rest is set to true to enable the Gutenberg editor for the custom post type.
 * supports custom-fields to enable custom fields in the Gutenberg editor.
 * rewrite is set to 'subscribers' to change the URL of the custom post type.
 * public is set to true to access the subscribers content from the front end.
 */

add_action('init', 'subscribers_cpt');
// Create a custom post type for subscribers.
function subscribers_cpt(){
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
            )
        ));
        echo count($subscribers);
    }
}