<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Enqueue scripts and styles.
 */

 // Enqueuing Leaflet JS and CSS
add_action( 'wp_enqueue_scripts', 'enqueue_leaflet_js_and_css' );
function enqueue_leaflet_js_and_css() {
    wp_enqueue_style('leaflet-css', 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css');
    wp_enqueue_script('leaflet-js', 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js', [], null, true);

	//Enqueue custom map styles
	wp_enqueue_style('my-plugin-style-map', MY_PLUGIN_URL . 'assets/css/style-map.css', [], '1.0.0');

	// Conditionally enqueue the student map script
	if ( is_singular( 'student' ) ) {
		global $post;
		$post_id = $post->ID;

		// Get the student city from the custom field
		$student_city = get_post_meta( $post_id, 'city', true );

		// Enqueue the student map JavaScript file with correct path
		wp_enqueue_script( 'my-plugin-student-map', MY_PLUGIN_URL . 'assets/js/student-map.js', ['leaflet-js'], null, true );

		// Localize the script with the city name and User-Agent
		wp_localize_script( 'my-plugin-student-map', 'studentMapData', array(
			'cityName' => $student_city,
			'userAgent' => 'learnWPPlug/0.3 (web@jesusyesares.com)',
		));
	}

}