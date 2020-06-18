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