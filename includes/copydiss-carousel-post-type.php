<?php 

function copydiss_carousel_shortcode_example($name) {
    global $post;
    if ($post->post_type != "copydiss_carousel") return;
    echo '<p>Paste this shortcode into the page where you\'d like the carousel to display: <b>[copydiss_carousel id="' . intval($post->ID) .'" speed="10"]</b></p>';
}


function copydiss_carousel_post_type_init() {
	$labels = array(
		'name' => 'CopyDiss Carousels',
        'singular name' => 'CopyDiss Carousel',
        'menu_name' => 'CopyDiss Carousel',
        'all_items' => 'All Carousels',
        'add_new' => 'Add New',
        'add_new_item' => 'Add New Carousel',
        'edit' => 'Edit',
        'edit_item' => 'Edit Carousel',
        'new_item' => 'New Carousel',
        'view' => 'View',
        'view_item' => 'View Carousel',
        'search_items' => 'Search Carousels',
        'not_found' => 'No Carousel found',
        'not_found_in_trash' => 'No Carousels found in trash',
        'parent' => 'Parent Carousel'
	);

	$args = array(
		'label' => 'CopyDiss Carousels',
        'lables' => $labels,
        'description' => 'This is where you can create and manage CopyDiss Carousels',
        'public' => TRUE,
        'show_ui' => TRUE,
        'capability_type' => 'post',
        'map_meta_cap' => TRUE,
        'publicly_queryable' => TRUE,
        'exclude_from_search' => TRUE,
        'hierarchical' => FALSE,
        'rewrite' => array(
            'slug' => 'copydiss_carousel',
            'hierarchical' => TRUE,
            'with_front' => FALSE
        ),
        'query_var' => TRUE,
        'can_export' => TRUE,
        'supports' => array('title'),
        'has_archive' => TRUE,
        'show_in_nav_menus' => TRUE,
	);

	register_post_type('copydiss_carousel', $args);

}

function copydiss_carousel_admin_init() {

};