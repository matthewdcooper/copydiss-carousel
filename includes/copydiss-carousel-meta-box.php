<?php

/* The CopyDiss Carousel Meta Box is displayed when editing Carousel posts.
 * It displays the images added to the current carousel, as well as providing
 * functionality for adding/removing images.
*/



function copydiss_carousel_create_meta_box() {
    add_meta_box(
        'copydiss_carousel', // id
        'Images', // title
        'copydiss_carousel_meta_box_html', // callback
        'copydiss_carousel', // screen
        'normal', // context
        'high' // priority
    );
}

function copydiss_carousel_meta_box_html($post) {
    /* Echoes the html for displaying the metabox on the admin panel. */
    wp_nonce_field('copydiss_nonce', 'nonce');
    ?>

    <div id="copydiss-carousel-container">
        <ul class="copydiss-carousel-images">
            <?php
            if (metadata_exists('post', $post->ID, '_copydiss_carousel_images')) {
                $copydiss_carousel_images = get_post_meta($post->ID, '_copydiss_carousel_images', TRUE);
            } else {
                $attachment_ids = get_posts(
                        'post_parent=' . $post->ID . '&'
                        . 'numberposts=-1&'
                        . 'post_type=attachment&'
                        . 'orderby=menu_order&'
                        . 'order=ASC&'
                        . 'post_mime_type=image&'
                        . 'fields=ids&'
                );
                $attachment_ids = array_diff($attachment_ids, array(get_post_thumbnail_id()));
                $copydiss_carousel_images = implode(',', $attachment_ids);
            }

            $attachments = array_filter(explode(',', $copydiss_carousel_images));
            $update_meta = FALSE;
            $updated_gallery_ids = array();

            if (!empty($attachments)) {
                foreach ($attachments as $attachment_id) {
                    $attachment = wp_get_attachment_image($attachment_id, 'thumbnail');
                    $alt = get_post_meta($attachment_id, '_wp_attachment_image_alt', true);
                    $attachment_meta = get_post($attachment_id);
                    
                   // Skip Empty Attachment
                    if (empty($attachment)) {
                        $update_meta = TRUE;
                        continue;
                    }

                    echo '<li class="image" data-attachment_id="' . esc_attr($attachment_id) . '">
                            ' . $attachment . '<br>
                            <input type="text" name="caption_' . $attachment_id . '"  value="' . $attachment_meta->post_excerpt . '"  placeholder="Caption"> 
                            <a href="#" class="delete tips" data-tip="Delete Slide"><i class="fa fa-times" aria-hidden="true"></i>
                          </a>
                    </li>';

                    // Rebuild IDs to be Saved
                    $updated_gallery_ids[] = $attachment_id;
                }

                // Update Soc Slider Meta to Set New Slide's IDs
                if ($update_meta) {
                    update_post_meta($post->ID, '_copydiss_carousel_images', implode(',', $updated_gallery_ids));
                }
            }


            ?>
        </ul>
        <input type="hidden" id="copydiss_carousel_images" name="copydiss_carousel_images" value="<?php echo esc_attr($copydiss_carousel_images); ?>" />
    </div>
    <p class="add_image hide-if-no-js">
        <a href="#" data-choose="Add Image to Carousel" data-update="Add to Carousel" data-delete="Delete Image" data-text="Delete">Add Image to Carousel</a>
    </p>


    <?php
}


function copydiss_carousel_save() {
    /* Saves POST data in order to update a carousel. */
    global $post;

    // Check Nonce Field
    if (!isset($_POST['nonce'])) {
        return;
    }

    // Verify that the nonce is valid.
    if (!wp_verify_nonce($_POST['nonce'], 'copydiss_nonce')) {
        return;
    }

    // If this is an autosave, our form has not been submitted, so we don't want to do anything.
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Check the user's permissions.
    if (isset($_POST['post_type']) && 'page' == $_POST['post_type']) {
        if (!current_user_can('edit_page', $post->ID)) {
            return;
        }
    } else {
        if (!current_user_can('edit_post', $post->ID)) {
            return;
        }
    }

    // Get Attachment's/Slide's IDs
    $attachment_ids = isset($_POST['copydiss_carousel_images']) ? array_filter(explode(',', $_POST['copydiss_carousel_images'])) : array();
    update_post_meta($post->ID, '_copydiss_carousel_images', implode(',', $attachment_ids));

    foreach ($attachment_ids as $attachment_id) {
      
        $post_type_attachment_ = array(
            'ID' => $attachment_id,
            'post_excerpt' => $_POST['caption_'. $attachment_id],
        );

        // Update Excerpt of Post Type Attachment
        wp_update_post($post_type_attachment_);
    }
      
}