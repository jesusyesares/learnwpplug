<?php
/**
 * Plugin Name: Books Custom Post Type
 * Plugin URI:
 * Description: A plugin to create a custom post type for books.
 * Version: 1.0
 * Text Domain: lwp-books
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Let's create a custom post type for books and a custom taxonomy for the book genre.
 * We will also create a subscribe form on the single book page to allow users to subscribe to the book.
 * The form data will be saved as a custom post type for subscribers.
 * We will display the number of subscribers per book in the admin column.
 *
 *
 * Here are the WordPress action and filter hooks used in this example:
 *
 * ### Action Hooks
 * - init
 * callbacks:
 *      book_post_type
 *      book_taxonomy
 *      save_book_subscribe_form_data
 *      subscribers_cpt
 *      manage_book_posts_custom_column
 *      book_subscribe_column_content
 *      manage_book_posts_columns
 *      book_subscribe_column
 *
 * ### Filter Hooks
 * - manage_book_posts_columns
 * callback:
 *       book_subscribe_column
 */

/**
 * Register the book custom post type.
 * show_in_rest is set to true to enable the Gutenberg editor for the custom post type.
 * supports custom-fields to enable custom fields in the Gutenberg editor.
 * rewrite is set to 'books' to change the URL of the custom post type.
 * public is set to true to access the books content from the front end.
 */
add_action('init', 'book_post_type');
function book_post_type() {
    register_post_type('book',
        array(
            'labels'      => array(
                'name'          => __('Books', 'lwp-books'),
                'singular_name' => __('Book', 'lwp-books'),
                'add_new'       => __('Add New Book', 'lwp-books'),
                'add_new_item'       => __('Add New Book', 'lwp-books'),
                'new_item'       => __('New Book', 'lwp-books'),
                'edit_item'       => __('Edit Book', 'lwp-books'),
                'view_item'       => __('View Book', 'lwp-books'),
                'all_items'       => __('All Books', 'lwp-books'),
            ),
            'public'      => true,
            'has_archive' => true,
            'show_in_rest' => true,
            'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'custom-fields' ),
            'rewrite'     => array( 'slug' => 'books' ),

        )
    );
}

/**
 * Register the book_genre taxonomy for the book custom post type.
 * show_in_rest is set to true to enable the Gutenberg editor for the custom taxonomy.
 * hierarchical is set to true to create a hierarchical taxonomy.
 * show_admin_column is set to true to display the taxonomy in the admin column.
 */

add_action('init', 'book_taxonomy');
function book_taxonomy() {
    register_taxonomy(
        'book_genre',
        'book',
        array(
            'label' => __('Genre'),
            'rewrite' => array('slug' => 'genre'),
            'hierarchical' => true,
            'show_admin_column' => true,
            'show_in_rest' => true,
        )
    );
}


/**
 * Add a subscribe form with name and email on the single book page to allow users to submit the form.
 * Use the shortcode [book_subscribe_form] to display the form on the single book page.
 * The form contains name, email, and a hidden field to store the book CPT ID.
 */
add_shortcode( 'book_subscribe_form', 'book_subscribe_form' );
function book_subscribe_form() {
    ob_start();
    ?>
    <form action="" method="post">
        <label for="name">Name:</label>
        <input type="text" name="name" id="name" required>
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required>
        <input type="hidden" name="book_id" value="<?php echo get_the_ID(); ?>">
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
add_action('init', 'save_book_subscribe_form_data');
function save_book_subscribe_form_data() {
    if (!isset($_POST['subscribe_form_nonce']) || !wp_verify_nonce($_POST['subscribe_form_nonce'], 'subscribe_form')) {
        return;
    }

    if (isset($_POST['name']) && isset($_POST['email'])) {
        $name = sanitize_text_field($_POST['name']);
        $email = sanitize_email($_POST['email']);
        $book_id = intval($_POST['book_id']);
        // Save the form data as subscriber post type.
        $subscriber_id = wp_insert_post(array(
            'post_title' => 'New Subscriber ' . $name,
            'post_content' => '',
            'post_type' => 'subscriber',
            'post_status' => 'publish',
            // add meta data to the subscriber post
            'meta_input' => array(
                'book_id' => $book_id,
                'name' => $name,
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
                'name'          => __('Subscribers', 'lwp-books'),
                'singular_name' => __('Subscriber', 'lwp-books'),
                'add_new'       => __('Add New Subscriber', 'lwp-books'),
                'add_new_item'       => __('Add New Subscriber', 'lwp-books'),
                'new_item'       => __('New Subscriber', 'lwp-books'),
                'edit_item'       => __('Edit Subscriber', 'lwp-books'),
                'view_item'       => __('View Subscriber', 'lwp-books'),
                'all_items'       => __('All Subscribers', 'lwp-books'),
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
 * Show the subscriber entries per book in the admin column.
 * Get the number of subscriber entries per book and display it in the admin column.
 */
add_filter('manage_book_posts_columns', 'book_subscribe_column');
function book_subscribe_column($columns) {
    $columns['subscribers'] = 'Subscribers';
    return $columns;
}

/**
 * Display the subscriber entries per book in the admin column.
 * Get the number of subscriber entries per book and display it in the admin column.
 */
add_action('manage_book_posts_custom_column', 'book_subscribe_column_content', 10, 2);
function book_subscribe_column_content($column, $post_id) {
    if ($column === 'subscribers') {
        $subscribers = get_posts(array(
            'post_type' => 'subscriber',
            'meta_query' => array(
                array(
                    'key' => 'book_id',
                    'value' => $post_id,
                    'compare' => '=',
                )
            )
        ));
        echo count($subscribers);
    }
}