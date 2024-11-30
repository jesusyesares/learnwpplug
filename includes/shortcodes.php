<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Shortcode functions.
 */

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
