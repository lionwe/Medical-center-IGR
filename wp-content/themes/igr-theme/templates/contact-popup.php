<?php
/**
 * Contact Popup Template
 * Popup для форми зворотного зв'язку
 */
?>
<div class="backdrop contact-popup" id="contact-popup" aria-hidden="true">
    <div class="contact-popup__modal">
        <button type="button" class="contact-popup__close close-button" aria-label="<?php esc_attr_e('Закрити', 'igr-theme'); ?>">
            <span aria-hidden="true">&times;</span>
        </button>
        <div class="contact-popup__content">
            <h2 class="contact-popup__title"><?php esc_html_e('Зв\'язатись з нами', 'igr-theme'); ?></h2>
            <?php if (function_exists('wpcf7_contact_form') && shortcode_exists('contact-form-7')): ?>
                <?php echo do_shortcode('[contact-form-7 id="1" title="Contact form 1"]'); ?>
            <?php else: ?>
                <p class="contact-popup__placeholder"><?php esc_html_e('Додайте форму Contact Form 7 через shortcode [contact-form-7]', 'igr-theme'); ?></p>
            <?php endif; ?>
        </div>
    </div>
</div>
