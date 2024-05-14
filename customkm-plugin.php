<?php
/**
 * Plugin Name: Customkm Menu
* Plugin URI: https://custom-plugin.com
 * Description: A simple Plugin for storing and displaying custom data.
 * Version: 6.5.2
 * Author: krishna
 * Author URI: http://custom-plugin.com
 * Text Domain: customkm_menu
 *
 * Custom Menu is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * PARTICULAR PURPOSE.
 */

// Enqueue custom stylesheet
function your_plugin_enqueue_styles() {
	// Enqueue your stylesheet
	wp_enqueue_style( 'your-plugin-styles', plugin_dir_url( __FILE__ ) . 'style.css' );
}
add_action( 'wp_enqueue_scripts', 'your_plugin_enqueue_styles' );


// Add custom menu page
function customkm_menu_page() {
	add_menu_page(
		'Customkm Menu',              // Page title
		'Customkm Menu',              // Menu title
		'manage_options',           // Capability
		'customkm-page-slug',         // Menu slug
		'customkm_page_content',      // Callback function
		'dashicons-admin-generic',  // Icon
		25                          // Position
	);
}
add_action( 'admin_menu', 'customkm_menu_page' );

// Custom page content
function customkm_page_content() {
	?>
	<div class="logo-container">
		<img src="<?php echo esc_url( plugins_url( 'images/custom-logo.png', __FILE__ ) ); ?>" alt="Plugin Logo">
	</div>
	<div class="wrap">
		<form action="" method="post">
			<?php wp_nonce_field( 'update_plugin_options', 'plugin_options_nonce' ); ?>
			Name: <input type="text" name="name" value="<?php echo esc_attr( get_option( 'name' ) ); ?>"/>
			<input type="submit" name="submit" value="Submit"/>
		</form>
	</div>
	<?php
}

// Save data using Option API
add_action( 'init', 'data_save_table' );
function data_save_table() {
	if ( isset( $_POST['plugin_options_nonce'] ) && wp_verify_nonce( $_POST['plugin_options_nonce'], 'update_plugin_options' ) ) {
		$data_to_store = $_POST['name'];
		$key           = 'name';
		update_option( $key, $data_to_store );
	}
}

// Add shortcode to fetch data
add_shortcode( 'fetch_data', 'fetch_data_shortcode' );
function fetch_data_shortcode() {
	$key        = 'name';                  // Specify the key used to save the data
	$saved_data = get_option( $key ); // Retrieve the saved data
	return $saved_data;             // Return the data
}

// Add submenu page using Option API
function km_plugin_submenu_page() {
	add_submenu_page(
		'options-general.php',      // Parent menu slug
		'KM Plugin Settings',       // Page title
		'KM Plugin',                // Menu title
		'manage_options',           // Capability
		'km-plugin-submenu',        // Menu slug
		'km_plugin_submenu_settings_page' // Callback function
	);
}
add_action( 'admin_menu', 'km_plugin_submenu_page' );

// Submenu page content
function km_plugin_submenu_settings_page() {
	?>
	<div class="wrap">
		<h2>My Plugin Settings</h2>
		<form method="post" action="">
		<?php wp_nonce_field( 'update_plugin_options', 'plugin_options_nonce' ); ?>
			Add Data: <input type="text" name="my_data" value="<?php echo esc_attr( get_option( 'my_data' ) ); ?>"/>
			<input type="submit" name="submit" value="Submit">
		</form>
	</div>
	<?php
}

// Save data using Option API for submenu
add_action( 'init', 'data_save_table2' );
function data_save_table2() {
	if ( isset( $_POST['plugin_options_nonce'] ) && wp_verify_nonce( $_POST['plugin_options_nonce'], 'update_plugin_options' ) ) {
		$data_to_store1 = $_POST['my_data'];
		$keys           = 'my_data';
		update_option( $keys, $data_to_store1 );
	}
}

// Add shortcode to fetch data for submenu
add_shortcode( 'fetch_data_value', 'fetch_data_value_shortcode' );
function fetch_data_value_shortcode() {
	$key        = 'my_data';               // Specify the key used to save the data
	$saved_data = get_option( $key ); // Retrieve the saved data
	return $saved_data;             // Return the data
}

// Add submenu page using Settings API
function km_custom_submenu_page() {
	add_submenu_page(
		'options-general.php',      // Parent menu slug
		'KM Submenu Page',          // Page title
		'KM Submenu',               // Menu title
		'manage_options',           // Capability required to access
		'km-custom-submenu',        // Menu slug
		'km_custom_submenu_callback' // Callback function to display content
	);
}
add_action( 'admin_menu', 'km_custom_submenu_page' );

// Callback function to display submenu page content
function km_custom_submenu_callback() {
	?>
	<div class="wrap">
		<h2>My Submenu Page</h2>
		<form method="post" action="options.php">
			<?php
			// Display settings fields
			settings_fields( 'km-custom-settings-group' );
			do_settings_sections( 'km-custom-settings-group' );
			?>
			<input type="submit" class="button-primary" value="Save Changes">
		</form>
	</div>
	<?php
}

// Register settings and fields
function km_custom_settings_init() {
	register_setting(
		'km-custom-settings-group',  // Option group
		'km_option_name',             // Option name
		'km_sanitize_callback'       // Sanitization callback function
	);

	add_settings_section(
		'km-settings-section',       // Section ID
		'KM Settings Section',       // Section title
		'km_settings_section_callback', // Callback function to display section description (optional)
		'km-custom-settings-group'   // Parent page slug
	);

	add_settings_field(
		'km-setting-field',          // Field ID
		'KM Setting Field',          // Field title
		'km_setting_field_callback', // Callback function to display field input
		'km-custom-settings-group',  // Parent page slug
		'km-settings-section'        // Section ID
	);
}
add_action( 'admin_init', 'km_custom_settings_init' );

// Callback function to display section description (optional)
function km_settings_section_callback() {
	echo '<p>This is a description of my settings section.</p>';
}

// Callback function to display field input
function km_setting_field_callback() {
	$option_value = get_option( 'my_option_name' );
	?>
	<input type="text" name="my_option_name" value="<?php echo esc_attr( $option_value ); ?>">
	<?php
}

// Sanitization callback function
function _sanitize_callback( $input ) {
	return sanitize_text_field( $input );
}
function custom_portfolio_post_type() {

	$labels = array(
		'name'                  => _x( 'Portfolio', 'Post Type General Name', 'text_domain' ),
		'singular_name'         => _x( 'Portfolio Item', 'Post Type Singular Name', 'text_domain' ),
		'menu_name'             => __( 'Portfolio', 'text_domain' ),
		'name_admin_bar'        => __( 'Portfolio', 'text_domain' ),
		'archives'              => __( 'Portfolio Archives', 'text_domain' ),
		'attributes'            => __( 'Portfolio Attributes', 'text_domain' ),
		'parent_item_colon'     => __( 'Parent Item:', 'text_domain' ),
		'all_items'             => __( 'All Items', 'text_domain' ),
		'add_new_item'          => __( 'Add New Item', 'text_domain' ),
		'add_new'               => __( 'Add New', 'text_domain' ),
		'new_item'              => __( 'New Item', 'text_domain' ),
		'edit_item'             => __( 'Edit Item', 'text_domain' ),
		'update_item'           => __( 'Update Item', 'text_domain' ),
		'view_item'             => __( 'View Item', 'text_domain' ),
		'view_items'            => __( 'View Items', 'text_domain' ),
		'search_items'          => __( 'Search Item', 'text_domain' ),
		'not_found'             => __( 'Not found', 'text_domain' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'text_domain' ),
		'featured_image'        => __( 'Featured Image', 'text_domain' ),
		'set_featured_image'    => __( 'Set featured image', 'text_domain' ),
		'remove_featured_image' => __( 'Remove featured image', 'text_domain' ),
		'use_featured_image'    => __( 'Use as featured image', 'text_domain' ),
		'insert_into_item'      => __( 'Insert into item', 'text_domain' ),
		'uploaded_to_this_item' => __( 'Uploaded to this item', 'text_domain' ),
		'items_list'            => __( 'Items list', 'text_domain' ),
		'items_list_navigation' => __( 'Items list navigation', 'text_domain' ),
		'filter_items_list'     => __( 'Filter items list', 'text_domain' ),
	);
	$args   = array(
		'label'               => __( 'Portfolio Item', 'text_domain' ),
		'description'         => __( 'Portfolio Item Description', 'text_domain' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor', 'thumbnail', 'custom-fields' ),
		'taxonomies'          => array( 'category', 'post_tag' ),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_position'       => 5,
		'menu_icon'           => 'dashicons-portfolio',
		'show_in_admin_bar'   => true,
		'show_in_nav_menus'   => true,
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'capability_type'     => 'page',
	);
	register_post_type( 'portfolio', $args );
}
add_action( 'init', 'custom_portfolio_post_type', 0 );

// Add Custom Fields
function custom_portfolio_custom_fields() {
	add_meta_box(
		'portfolio_fields',
		'Portfolio Fields',
		'render_portfolio_fields',
		'portfolio',
		'normal',
		'default'
	);
}
add_action( 'add_meta_boxes', 'custom_portfolio_custom_fields' );

function render_portfolio_fields( $post ) {
	// Retrieve existing values for fields
	$client_name = get_post_meta( $post->ID, 'client_name', true );
	$project_url = get_post_meta( $post->ID, 'project_url', true );

	// Render fields
	?>
	<p>
	<?php wp_nonce_field( 'update_plugin_options', 'plugin_options_nonce' ); ?>
		<label for="client_name">Client Name:</label>
		<input type="text" id="client_name" name="client_name" value="<?php echo esc_attr( $client_name ); ?>">
	</p>
	<p>
	<?php wp_nonce_field( 'update_plugin_options', 'plugin_options_nonce' ); ?>
		<label for="project_url">Project URL:</label>
		<input type="text" id="project_url" name="project_url" value="<?php echo esc_attr( $project_url ); ?>">
	</p>
	<?php
}

// Save Custom Fields
function save_portfolio_custom_fields( $post_id ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	// Save client name
	if ( isset( $_POST['plugin_options_nonce'] ) && wp_verify_nonce( $_POST['plugin_options_nonce'], 'update_plugin_options' ) ) {
		update_post_meta( $post_id, 'client_name', sanitize_text_field( $_POST['client_name'] ) );
	}

	// Save project URL
	if ( isset( $_POST['plugin_options_nonce'] ) && wp_verify_nonce( $_POST['plugin_options_nonce'], 'update_plugin_options' ) ) {
		update_post_meta( $post_id, 'project_url', esc_url( $_POST['project_url'] ) );
	}
}
add_action( 'save_post', 'save_portfolio_custom_fields' );


add_shortcode( 'recent_portfolio_posts', 'display_recent_portfolio_posts_shortcode' );

// Shortcode callback function to display recent portfolio posts
function display_recent_portfolio_posts_shortcode( $atts ) {
	$atts = shortcode_atts(
		array(
			'count' => 4,               // Default number of posts to display
		),
		$atts
	);

	$args = array(
		'post_type'      => 'portfolio', // Custom post type name
		'posts_per_page' => $atts['count'],
		'orderby'        => 'date',
		'order'          => 'DESC',
	);

	$recent_portfolio_posts = new WP_Query( $args );

	if ( $recent_portfolio_posts->have_posts() ) {
		$output = '<ul>';
		while ( $recent_portfolio_posts->have_posts() ) {
			$recent_portfolio_posts->the_post();
			$output .= '<li><a href="' . get_permalink() . '">' . get_the_title() . '</a></li>';
		}
		$output .= '</ul>';
		wp_reset_postdata(); // Reset post data query
	} else {
		$output = 'No recent portfolio posts found.';
	}

	return $output;
}

require_once plugin_dir_path( __FILE__ ) . 'includes/class-kmd-widget.php';


function custom_widget() {
	//echo 'ok';
	register_widget( 'Kmd_Widget' );
}
add_action( 'widgets_init', 'custom_widget' );


function widgets_init() {
	// Register the custom widget area
	register_sidebar(
		array(
			'name'          => __( 'Custom Widget Area', 'twentytwentyone' ),
			'id'            => 'custom-widget-area',
			'description'   => __( 'Add widgets here to appear in the custom widget area.', 'twentytwentyone' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);

	// Add recent posts widget to the custom widget area
	if ( is_active_sidebar( 'custom-widget-area' ) ) {
		// Instantiate the custom recent posts widget
		$recent_posts_widget = new Custom_Widget();

		// Add the widget to the custom widget area
		the_widget(
			'Custom_Widget', // Widget class name
			array(), // Widget arguments (empty for default settings)
			array(
				'before_widget' => '<section id="%1$s" class="widget %2$s">',
				'after_widget'  => '</section>',
				'before_title'  => '<h2 class="widget-title">',
				'after_title'   => '</h2>',
			)
		);
	}
}
add_action( 'widgets_init', 'widgets_init' );


function custom_ajax_plugin_menu() {
	add_menu_page(
		'Custom AJAX Plugin Settings',
		'Custom AJAX Plugin',
		'manage_options',
		'custom-ajax-plugin-settings',
		'custom_ajax_plugin_settings_page'
	);
}

function custom_ajax_plugin_settings_page() {
	?>
	<div class="wrap">
		<h2>Custom AJAX Plugin Settings</h2>
		<form id="store-name-form">
			<label for="store-name">Store Name:</label>
			<input type="text" id="store-name" name="store_name" value="<?php echo esc_attr( get_option( 'store_name' ) ); ?>">
			<input type="submit" value="Save">
			<?php wp_nonce_field( 'save_store_name', 'store_name_nonce' ); ?> <!-- Add nonce field -->
		</form>
		<div id="store-name-result"></div>
	</div>
	<?php
}

add_action( 'admin_menu', 'custom_ajax_plugin_menu' );

// Step 3: Implement AJAX for Dynamic Content


function custom_ajax_plugin_ajax_handler() {
	if ( isset( $_POST['store_name'] ) ) {
		// Verify nonce
		if ( ! wp_verify_nonce( $_POST['nonce'], 'custom_ajax_plugin_ajax_nonce' ) ) {
			echo 'ok';
		}
		$store_name = sanitize_text_field( $_POST['store_name'] );
		update_option( 'store_name', $store_name );
		echo 'Store name updated successfully!';
	}
	wp_die();
}

add_action( 'wp_ajax_custom_ajax_plugin_update_store_name', 'custom_ajax_plugin_ajax_handler' );

// Enqueue JavaScript for AJAX

function custom_ajax_plugin_enqueue_scripts( $hook ) {
	if ( 'toplevel_page_custom-ajax-plugin-settings' !== $hook ) {
		return;
	}
	wp_enqueue_script( 'custom-ajax-plugin-script', plugins_url( '/js/custom-ajax-plugin-script.js', __FILE__ ), array( 'jquery' ), '1.0', true );
	wp_localize_script(
		'custom-ajax-plugin-script',
		'custom_ajax_plugin_ajax_object',
		array( 'ajax_url' => admin_url( 'admin-ajax.php' ) )
	);
}

add_action( 'admin_enqueue_scripts', 'custom_ajax_plugin_enqueue_scripts' );

// Enqueue jQuery in WordPress
function enqueue_jquery() {
	wp_enqueue_script( 'jquery' );
}
add_action( 'wp_enqueue_scripts', 'enqueue_jquery' );
// Create shortcode for form
function portfolio_submission_form_shortcode() {
	ob_start();
	?>
	<form id="portfolio_submission_form">
		<input type="hidden" name="action" value="portfolio_submission">
		<?php wp_nonce_field( 'portfolio_submission_nonce', 'portfolio_submission_nonce_field' ); ?>
		<label for="name">Name:</label>
		<input type="text" id="name" name="name" required><br><br>
		<label for="company_name">Company Name:</label>
		<input type="text" id="company_name" name="company_name"><br><br>
		<label for="email">Email:</label>
		<input type="email" id="email" name="email" required><br><br>
		<label for="phone">Phone:</label>
		<input type="tel" id="phone" name="phone"><br><br>
		<label for="address">Address:</label>
		<textarea id="address" name="address"></textarea><br><br>
		<input type="button" id="submit_btn" value="Submit">
	</form>
	<div id="response_msg"></div>
	<script>
		jQuery(document).ready(function ($) {
	$('#submit_btn').on('click', function () {
		var formData = $('#portfolio_submission_form').serialize();
		$.ajax({
			type: 'POST',
			url: '<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>',
			data: formData,
			success: function (response) {
				$('#response_msg').html(response);
				$('#portfolio_submission_form')[0].reset(); // Reset the form
			}
		});
	});
});
	</script>
	<?php
	return ob_get_clean();
}
add_shortcode( 'portfolio_submission_form', 'portfolio_submission_form_shortcode' );
// Process form submission
function process_portfolio_submission() {
	if ( isset( $_POST['portfolio_submission_nonce_field'] ) && wp_verify_nonce( $_POST['portfolio_submission_nonce_field'], 'portfolio_submission_nonce' ) ) {
		if ( isset( $_POST['name'] ) && isset( $_POST['email'] ) ) {
			$name         = sanitize_text_field( $_POST['name'] );
			$company_name = sanitize_text_field( $_POST['company_name'] );
			$email        = sanitize_email( $_POST['email'] );
			$phone        = sanitize_text_field( $_POST['phone'] );
			$address      = sanitize_textarea_field( $_POST['address'] );
			// Create post object
			$portfolio_data = array(
				'post_title'  => $name,
				'post_type'   => 'portfolio',
				'post_status' => 'publish',
				'meta_input'  => array(
					'client_name'  => $name,
					'company_name' => $company_name,
					'email'        => $email,
					'phone'        => $phone,
					'address'      => $address,
				),
			);
			// Insert the post into the database
			$post_id = wp_insert_post( $portfolio_data );
			if ( is_wp_error( $post_id ) ) {
				echo 'Error: ' . esc_html( $post_id->get_error_message() );
			} else {
				echo 'Success! Your portfolio has been submitted.';
			}
		}
	}
	die();
}
add_action( 'wp_ajax_portfolio_submission', 'process_portfolio_submission' );
add_action( 'wp_ajax_nopriv_portfolio_submission', 'process_portfolio_submission' );