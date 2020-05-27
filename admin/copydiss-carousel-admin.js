/* 
 *  Carousel Uploading via Media Frame
 *  v1.0.0
 */


(function ($) {
    'use strict';

    $(document).ready(function () {
        
        let cdc_frame;
        const cdc_images_id = $('#copydiss_carousel_images');
        const cdc_images_container = $('#copydiss-carousel-container');
        const cdc_images = cdc_images_container.find('ul.copydiss-carousel-images');

        $('.add_image').on('click', 'a', function (event) {
            event.preventDefault();
            const el = $(this);

            // if media frame exists, open it
            if (cdc_frame) {
                cdc_frame.open();
                return;
            }

            // create media frames
            cdc_frame = wp.media.frames.cdc_frame = wp.media({
                
                // Set the title of the modal.
                title: el.data('choose'),
                button: {
                    text: el.data('update')
                },
                states: [
                    new wp.media.controller.Library({
                        title: el.data('choose'),
                        filterable: 'all',
                        multiple: true
                    })
                ]
            });

            // When a slide is selected, run a callback.
            cdc_frame.on('select', function () {
                const selection = cdc_frame.state().get('selection');
                let attachment_ids = cdc_images_id.val();
                
                selection.map(function (attachment) {
                    attachment = attachment.toJSON();

                    if (attachment.id) {
                        attachment_ids = attachment_ids ? attachment_ids + ',' + attachment.id : attachment.id;

                        let attachment_image = attachment.sizes && attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url;

                        cdc_images.append('<li class="image" data-attachment_id="' + attachment.id + '"><img src="' + attachment_image + '" /><input type="text" name="caption_' + attachment.id + '"  value="'+attachment.caption+'" placeholder="Caption"><a href="#" class="delete" title="' + el.data('delete') + '"><i class="fa fa-times" aria-hidden="true"></i> </a>  </li>');


                    }
                });

                cdc_images_id.val(attachment_ids);
            });

            // open the modal
            cdc_frame.open();


        });

        // Image Ordering
        cdc_images.sortable({
            items: 'li.slide',
            cursor: 'move',
            scrollSensitivity: 40,
            forcePlaceholderSize: true,
            forceHelperSize: false,
            helper: 'clone',
            opacity: 0.65,
            placeholder: 'cdc-meta-box-sortable-placeholder',
            start: function (event, ui) {
                ui.item.css('background-color', '#f6f6f6');
            },
            stop: function (event, ui) {
                ui.item.removeAttr('style');
            },
            update: function () {
                var attachment_ids = '';

                cdc_images_container.find( 'li.slide' ).css('cursor', 'default').each(function () {
                    var attachment_id = jQuery(this).attr('data-attachment_id');
                    attachment_ids = attachment_ids + attachment_id + ',';
                });

                cdc_images_id.val(attachment_ids);
            }
        });

        // Remove Slides
        cdc_images_container.on('click', 'a.delete', function () {
            $(this).closest('li.image').remove();

            var attachment_ids = '';

            cdc_images_container.find( 'li.image' ).css('cursor', 'default').each(function () {
                var attachment_id = jQuery(this).attr('data-attachment_id');
                attachment_ids = attachment_ids + attachment_id + ',';
            });

            cdc_images_id.val(attachment_ids);

            return false;
        });

    });

})(jQuery);