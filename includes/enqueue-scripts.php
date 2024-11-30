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
	wp_enqueue_style('custom-map-style', MY_PLUGIN_URL . 'assets/style-map.css');

	// Conditionally enqueue the student map script
	if ( is_singular( 'student' ) ) {
		// Get the city name for the current student post
		$student_city = get_post_meta(get_the_ID(), 'city', true);

		// Enqueue the student map JavaScript file withaut jQuery as a dependency
		wp_enqueue_script(
			'student-map',
			MY_PLUGIN_URL . 'assets/student-map.js',
			['leaflet-js'],
			null,
			true
		);

		// Localize the script with the city name and User-Agent
		wp_localize_script( 'student-map', 'studentMapData', [
			'city' => $student_city,
			'userAgent' => 'learnWPPlug/0.3 (web@jesusyesares.com)',
		]);
	}
}