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
 * Form handling functions.
 */

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
 