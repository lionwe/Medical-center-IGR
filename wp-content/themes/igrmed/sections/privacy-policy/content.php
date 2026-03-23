<?php
/**
 * Privacy Policy Content Section
 *
 * @package IGRMed
 */

// If no content, don't render the section.
if (empty(get_the_content())) {
    return;
}
?>

<section class="privacy-content js-privacy-content">
    <div class="container">
        <div class="privacy-content__wrapper">
            <div class="privacy-content__text">
                <?php the_content(); ?>
            </div>
            
            <div class="privacy-content__more-wrapper">
                <div class="privacy-content__more-content">
                    <?php // Content moved here by JS ?>
                </div>
                
                <button type="button" class="privacy-content__read-more-btn js-read-more-btn" data-collapse-text="<?php echo esc_attr__('Згорнути', 'igrmed'); ?>">
                    <span><?php echo esc_html__('Читати більше', 'igrmed'); ?></span>
                    <?php echo igrmed_get_svg('read-more-arrow'); ?>
                </button>
            </div>
        </div>
    </div>
</section>
