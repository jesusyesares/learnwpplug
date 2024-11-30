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
}