<?php

/**
 * Simple Banner Template
 *
 * @package IGRMed
 */

$title = (string) ($args['title'] ?? get_the_title());
$button_text = (string) __("Зв'язатись з нами", 'igrmed');
$button_link = '#cta';

$title = trim($title);

// Empty Fields Rule: title is required for this block.
if ($title === '') {
    return;
}
?>

<div class="simple-banner">
    <div class="simple-banner__inner">
        <div class="simple-banner__content">
            <h2 class="simple-banner__title"><?php echo esc_html($title); ?></h2>

            <div class="simple-banner__cta">
                <?php
                get_template_part('templates/button', null, [
                    'text' => $button_text,
                    'link' => $button_link,
                    'type' => 'glass-primary',
                    'primary_split' => true,
                    'icon_url' => get_template_directory_uri() . '/assets/img/svg/contact-arrow.svg',
                ]);
                ?>
            </div>
        </div>
    </div>
</div>