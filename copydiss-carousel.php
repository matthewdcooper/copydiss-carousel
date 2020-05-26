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
	$options = get_option('copydiss_carousel_options', array());

	$new_options['rotation_time'] = '10000'; // 10 seconds by default 

	$merged_options = wp_parse_args($options, $new_options);
	update_option('copydiss_carousel_options', $merged_options);
	return $merged_options;
}
register_activation_hook( __FILE__, 'copydiss_carousel_activate' );

// DEACTIVATION
function deactivate_copydiss_carousel() {

}
register_deactivation_hook( __FILE__, 'deactivate_copydiss_carousel' );

// ADMIN

// enqueue styles
function copydiss_carousel_enqueue_admin_styles() {


}
// enqueue scripts

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

	$output = '<div class="copydiss-carousel">';
	$output .= '<div class="copydiss-carousel-inner">';

	// TODO: iterate over carousel images and add each to output


	$output .= '<input class="copydiss-carousel-open" type="radio" id="carousel-1" name="carousel" aria-hidden="true" hidden="" checked="checked" />';
	$output .= '<div class="copydiss-carousel-item">';
	$output .= '<img src="' . get_template_directory_uri() . '/assets/img/carousel-1.jpg" />';
	$output .= '</div>';

	$output .= '</div> <!-- copydiss-carousel-inner -->'; 
	$output .= '</div> <!-- copydiss-carousel -->';

	return $output;
}

copydiss_carousel_enqueue_public_styles();
copydiss_carousel_enqueue_public_scripts();
add_shortcode('cpdtest', 'copydiss_carousel_test_shortcode');