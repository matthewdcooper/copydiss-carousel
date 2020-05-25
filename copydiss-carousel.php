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

define( 'PLUGIN_NAME_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/copydiss-carousel-activator.php
 */
function activate_plugin_name() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-copydiss-carousel-activator.php';
	Plugin_Name_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/copydiss-carousel-deactivator.php
 */
function deactivate_plugin_name() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-copydiss-carousel-deactivator.php';
	Plugin_Name_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_plugin_name' );
register_deactivation_hook( __FILE__, 'deactivate_plugin_name' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-copydiss-carousel.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_copydiss_carousel() {

	$plugin = new Copydiss_Carousel();
	$plugin->run();

}
run_copydiss_carousel();