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
require_once MY_PLUGIN_DIR . 'includes/post-types.php';

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

// Enqueuing Leaflet JS and CSS
add_action( 'wp_enqueue_scripts', 'enqueue_leaflet_js_and_css' );
function enqueue_leaflet_js_and_css() {
    wp_enqueue_style('leaflet-css', 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css');
    wp_enqueue_script('leaflet-js', 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js', [], null, true);
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



/**
 * * Show the student fields in the frontend in each student post.
 * The student fields are retrieved from the student custom post type.
 * TODO: Review the code for fix the problems rendering the map.
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
        $content .= '<div id="student_map" style="width: 100%; height: 500px; margin-top: 20px;"></div>';

        // Add the student map script to the frontend.
        if ( !empty($student_city) ) {
            // Escape correctly the city name to avoid security issues.
            $city_for_js = json_encode($student_city);
            
            $content .= '<script>
                // Fuction for obtaining the city coordinates
                function getCoordinates(cityName) {
                    const url = `https://nominatim.openstreetmap.org/search?format=json&limit=1&q=${encodeURIComponent(cityName)}`;

                    return fetch(url, {
                        method: "GET",
                        headers: {
                            "Accept": "application/json",
                            "User-Agent": "MiMapa/1.0 (jesus@jesusyesares.com)"
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data && data.length > 0) {
                            return {
                                lat: parseFloat(data[0].lat),
                                lon: parseFloat(data[0].lon)
                            };
                        } else {
                            throw new Error("There is no results for the city specified.");
                        }
                    });
                }   


                // Name of the city
                const cityName = ' . $city_for_js . ';

                // Now we call to the function to obtain the coordinates
                getCoordinates(cityName)
                    .then(coords => {
                        // We inizialized the map centered in the obtained coordinates
                        const map = L.map("student_map").setView([coords.lat, coords.lon], 13);

                        // We add the tile layer to the map
                        L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
                            attribution: "&copy; <a href=\'https://www.openstreetmap.org/copyright\'>OpenStreetMap</a> contributors"
                        }).addTo(map);

                        // We add the marker to the map in the obtained coordinates
                        L.marker([coords.lat, coords.lon]).addTo(map)
                            .bindPopup(`City: ${cityName}`)
                            .openPopup();
                        })
                        .catch(error => {
                            console.error("Error obtaining ubication:", error);
                        });
            </script>';
        }
    }

    // Return the content with the student fields.
    return $content;
}