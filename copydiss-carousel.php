<?php
/**
 * Plugin Name: CopyDiss Carousel
 * Plugin URI: https://github.com/matthewdcooper/copydiss-carousel
 * Description: Displays a minimalist carousel of auto-cycling images.
 * Version: 1.0.0
 * Requires at least: 5.3
 * Requires PHP: 7.4
 * Author: Matthew Cooper
 * License: GPLv3
 * 
 * @package CopyDissCarousel
 **/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'COPYDISS_CAROUSEL_VERSION', '1.0.0' );


// ACTIVATION
function copydiss_carousel_activate() {
	// display settings
	if ( false === get_option('copydiss_carousel_rotation_speed') ) {
		add_option( 'copydiss_carousel_rotation_speed', '10' );
	}
}
register_activation_hook( __FILE__, 'copydiss_carousel_activate' );

// DEACTIVATION
function deactivate_copydiss_carousel() {
	remove_menu_page( 'copydiss_carousel_settings' );
}
register_deactivation_hook( __FILE__, 'deactivate_copydiss_carousel' );







// ADMIN

// enqueue styles
function copydiss_carousel_enqueue_admin_styles() {


}
// enqueue scripts

// menu
function copydiss_carousel_settings_html() {
    // check user capabilities
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
	}
    ?>
    <form method="POST" action="options.php">
		<?php settings_fields('copydiss_carousel_settings' );
		do_settings_sections( 'copydiss_carousel_settings' );
		submit_button();
		?>
	</form>
    <?php
}

// Settings
function copydiss_carousel_settings_api_init() {
	add_settings_section(
	   'copydiss_carousel_display',
	   'Display Settings',
	   'copydiss_carousel_display_section_html',
	   'copydiss_carousel_settings'
   );

	add_settings_section(
	   'copydiss_carousel_images',
	   'Images',
	   'copydiss_carousel_images_section_html',
	   'copydiss_carousel_settings'
	);

	add_settings_field(
	   'copydiss_carousel_rotation_speed',
	   'Rotation Speed (seconds)',
	   'copydiss_carousel_rotation_speed_html',
	   'copydiss_carousel_settings',
	   'copydiss_carousel_display'
   );
	
	register_setting( 'copydiss_carousel_settings', 'copydiss_carousel_rotation_speed' );
} 
add_action( 'admin_init', 'copydiss_carousel_settings_api_init' );

function copydiss_carousel_display_section_html() {
	?>
	<?php
}

function copydiss_carousel_images_section_html() {
	?>
	<?php
}

function copydiss_carousel_rotation_speed_html() {
	?>
	<input
		name="copydiss_carousel_rotation_speed"
		id="copydiss_carousel_rotation_speed"
		type="number"
		value="<?php echo get_option("copydiss_carousel_rotation_speed"); ?>"
		min="0"
		max="60"
		step="0.5"
		class="code" />

	<?php
}


function copydiss_carousel_settings_menu() {
	add_submenu_page('themes.php', 
					 'CopyDiss Carousel Settings',
					 'CopyDiss Carousel',
					 'manage_options',
					 'copydiss_carousel_settings',
					 'copydiss_carousel_settings_html' );

}
add_action('admin_menu', 'copydiss_carousel_settings_menu');





// PUBLIC

// enqueue styles
function copydiss_carousel_enqueue_public_styles() {
	wp_enqueue_style('copydiss-carousel-css', 
					  plugin_dir_url(__FILE__) . 'public/copydiss-carousel.css');

}

// enqueue scripts
function copydiss_carousel_enqueue_public_scripts() {
	wp_enqueue_script('copydiss-carousel-js', 
					  plugin_dir_url(__FILE__) . 'public/copydiss-carousel.js');


}

function copydiss_carousel_test_shortcode($atts) {

	// TODO: use id to pull corresponding images from database
	$atts = shortcode_atts(
		array(
			'id' => ''
		), $atts, 'cpdtest');
	$id = $atts['id'];
	if (empty($id)) {
		return '<h1>ID Missing</h1>';
	}
	?>

	<div class="copydiss-carousel">
		<div class="copydiss-carousel-inner">
	// TODO: iterate over carousel images and add each to output
			<input class="copydiss-carousel-open" type="radio" id="carousel-1" name="carousel" aria-hidden="true" hidden="" checked="checked" />
			<div class="copydiss-carousel-item">
				<img src="' . get_template_directory_uri() . '/assets/img/carousel-1.jpg" />
			</div>
		</div> <!-- copydiss-carousel-inner -->
	</div> <!-- copydiss-carousel -->
	<?php
}

copydiss_carousel_enqueue_public_styles();
copydiss_carousel_enqueue_public_scripts();
add_shortcode('cpdtest', 'copydiss_carousel_test_shortcode');