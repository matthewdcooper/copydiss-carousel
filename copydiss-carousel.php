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
if ( ! defined( 'WPINC' ) ) die;

require_once('includes/copydiss-carousel-post-type.php');
require_once('includes/copydiss-carousel-meta-box.php');


// The CopyDiss Carousel
function copydiss_carousel_shortcode($atts) {
	$atts = shortcode_atts(
		array(
			'id' => '',
			'speed' => '1', // seconds
		), $atts, 'copydiss_carousel');
	$id = $atts['id'];
	if (empty($id)) {
		return 'CopyDiss Carousel error: missing id';
	}
	// get carousel images
	$image_files = get_post_meta( intval( $id ), '_copydiss_carousel_images', TRUE);
	$image_files = array_filter( explode(',', $image_files) );
	ob_start();
	include 'includes/carousel.php';
	return ob_get_clean();
}

// ENQUEUE STYLES
function copydiss_carousel_enqueue_public_styles() {
	wp_enqueue_style('copydiss-carousel-css', 
					  plugin_dir_url(__FILE__) . 'public/copydiss-carousel.css');
}

function copydiss_carousel_enqueue_admin_styles() {
	wp_enqueue_style('copydiss-carousel-admin-css', 
					  plugin_dir_url(__FILE__) . 'admin/copydiss-carousel-admin.css');
}


// ENQUEUE SCRIPTS
function copydiss_carousel_enqueue_admin_scripts() {
	wp_enqueue_media(); // for uploading images to carousels
	wp_enqueue_script('copydiss-carousel-admin-js', 
					  plugin_dir_url(__FILE__) . 'admin/copydiss-carousel-admin.js');
}



// HOOKS

// public scripts and styles
add_action('wp_enqueue_scripts', 'copydiss_carousel_enqueue_public_styles');
// shortcode for displaying carousel
add_shortcode('copydiss_carousel', 'copydiss_carousel_shortcode');


// admin panel, carousel post type
add_action('edit_form_after_title', 'copydiss_carousel_shortcode_example');
add_action('add_meta_boxes', 'copydiss_carousel_create_meta_box');
add_action( 'admin_init', 'copydiss_carousel_admin_init' );
add_action( 'init', 'copydiss_carousel_post_type_init' );
add_action('save_post', 'copydiss_carousel_save');

// admin scripts and styles
add_action('admin_enqueue_scripts', 'copydiss_carousel_enqueue_admin_scripts');
add_action('admin_enqueue_scripts', 'copydiss_carousel_enqueue_admin_styles');

?>