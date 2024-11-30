<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/** 
 * Register custom taxonomies
 */

/**
 * Register the subject taxonomy for the course custom post type.
 * show_in_rest is set to true to enable the Gutenberg editor for the custom taxonomy.
 * hierarchical is set to true to create a hierarchical taxonomy.
 * show_admin_column is set to true to display the taxonomy in the admin column.
 */

 add_action('init', 'learnwpplug_register_course_taxonomy');
 function learnwpplug_register_course_taxonomy() {
	 register_taxonomy(
		 'course_subject',
		 'course',
		 array(
			 'label' => __('Subject', 'jyg-students'),
			 'rewrite' => array('slug' => 'subject'),
			 'hierarchical' => true,
			 'show_admin_column' => true,
			 'show_in_rest' => true,
		 )
	 );
 }