<?php
/**
 * Plugin Name: CopyDiss Carousel
 * Plugin URI: https://github.com/matthewdcooper/copydiss-carousel
 * Description: Displays a minimalist carousel of auto-cycling images.
 * Version: 1.0.1
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

require_once('includes/copydiss-carousel-post-type.php');
require_once('includes/copydiss-carousel-meta-box.php');

// ACTIVATION
function copydiss_carousel_activate() {

}
register_activation_hook( __FILE__, 'copydiss_carousel_activate' );


// DEACTIVATION
function copydiss_carousel_deactivate() {

}
register_deactivation_hook( __FILE__, 'copydiss_carousel_deactivate' );


// The CopyDiss Carousel
function copydiss_carousel_shortcode($atts) {
	global $pagenow;
	if ($pagenow == "post.php") return; // don't display during edit

	// TODO: use id to pull corresponding images from database
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
	?>
	<div class="copydiss-carousel">
		<div class="copydiss-carousel-inner">
			<input type="hidden" id="inp_speed" value="<?php echo $atts['speed'] ?>" />
			<input type="hidden" id="inp_image_count" value="<?php echo sizeof($image_files) ?>" />

			<?php
			$carousel_id = 1;
			foreach ($image_files as $image) {
				$alt = get_post_meta($image, '_wp_attachment_image_alt', true);
				$attachment_url = wp_get_attachment_url($image, 'thumbnail');
				$attachment_meta = get_post($image);

			?>

				<input class="copydiss-carousel-open" type="radio" id="copydiss-carousel-<?php echo $carousel_id ?>" name="carousel" aria-hidden="true" hidden="" checked="checked" />
                <div class="copydiss-carousel-item">
                    <img src="<?php echo $attachment_url ?>" />
                </div>

				<?php
				$carousel_id += 1;
			}

			?>
		</div> <!-- copydiss-carousel-inner -->
	</div> <!-- copydiss-carousel -->
	<script>

	const copydissCarousel = {
		current: 1, // assume at least one image exists
		speed: parseInt(document.getElementById("inp_speed").value) * 1000, // seconds to milliseonds
		imageCount: parseInt(document.getElementById("inp_image_count").value),
		set: (i) => document.getElementById("copydiss-carousel-" + i).checked = true,
	};

	setInterval(() => {
		copydissCarousel.current += 1;
		if (copydissCarousel.current > copydissCarousel.imageCount) {
			copydissCarousel.current = 1;
		}
		copydissCarousel.set(copydissCarousel.current);
	}, copydissCarousel.speed);

	</script>
	<?php
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
	wp_enqueue_script('copydiss-carousel-admin-js', 
					  plugin_dir_url(__FILE__) . 'admin/copydiss-carousel-admin.js');
}



// HOOKS

// public scripts and styles
add_action('wp_enqueue_scripts', 'copydiss_carousel_enqueue_public_styles');

// admin scripts and styles
add_action('admin_enqueue_scripts', 'copydiss_carousel_enqueue_admin_scripts');
add_action('admin_enqueue_scripts', 'copydiss_carousel_enqueue_admin_styles');

// shortcode for displaying carousel
add_shortcode('copydiss_carousel', 'copydiss_carousel_shortcode');

// admin panel, carousel post type
add_action('edit_form_after_title', 'copydiss_carousel_shortcode_example');
add_action('add_meta_boxes', 'copydiss_carousel_create_meta_box');
add_action( 'admin_init', 'copydiss_carousel_admin_init' );
add_action( 'init', 'copydiss_carousel_post_type_init' );
add_action('save_post', 'copydiss_carousel_save');

flush_rewrite_rules();

?>