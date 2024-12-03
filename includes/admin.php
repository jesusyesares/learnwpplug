<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Shortcode functions.
 */

/**
 * * Add a form for register each student with name, surname, address, city, and email.
 * Use the shortcode [student_register_form] to display the form on the single student page.
 * The form contains name, surname, address, city, and email.
 * The form data is stored in the student custom post type.
 */
add_shortcode('student_register_form', 'student_register_form');
function student_register_form() {
    ob_start();
    ?>
    <form action="" method="post">
        <label for="student_name">Name:</label>
        <input type="text" name="student_name" id="student_name" required><br>
        <label for="student_surname">Surname:</label>
        <input type="text" name="student_surname" id="student_surname" required><br>
        <label for="student_address">Address:</label>
        <input type="text" name="student_address" id="student_address"><br>
        <label for="student_city">City:</label>
        <input type="text" name="student_city" id="student_city"><br>
        <label for="student_email">Email:</label>
        <input type="email" name="student_email" id="student_email" required><br>
        <input type="hidden" name="student_id" value="<?php echo get_the_ID(); ?>">
        <?php wp_nonce_field('student_register_form', 'student_register_form_nonce'); ?>
        <input type="submit" value="Register">
    </form>
    <?php
    return ob_get_clean();
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
