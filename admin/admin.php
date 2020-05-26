<?php
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
