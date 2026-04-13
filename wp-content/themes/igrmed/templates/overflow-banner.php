<?php
$title = trim((string) ($args['title'] ?? 'Стати донором яйцеклітин'));
$button_text = igrmed__('btn_contact_us');
$button_link = '#cta';
$image_url = get_template_directory_uri() . '/assets/img/ba5c6331bea41bf283dc73f4555c3659-removebg-preview 1.webp';

// Empty Fields Rule: title is required for this block.
if ($title === '') {
    return;
}
?>

<section class="overflow-banner">
    <div class="overflow-banner__inner">
        <div class="overflow-banner__content">
            <div class="overflow-banner__text-content">
                <h2 class="overflow-banner__title"><?php echo esc_html($title); ?></h2>

                <div class="overflow-banner__cta">
                    <?php
                    get_template_part('templates/button', null, [
                        'text' => $button_text,
                        'link' => $button_link,
                        'type' => 'primary-calm-soft',
                        'primary_split' => true,
                        'icon_url' => get_template_directory_uri() . '/assets/img/svg/contact-arrow.svg',
                    ]);
                    ?>
                </div>
            </div>

            <div class="overflow-banner__image-wrap mobile-only" aria-hidden="true">
                <img class="overflow-banner__image" src="<?php echo esc_url($image_url); ?>" alt="" loading="lazy">
            </div>
        </div>

        <div class="overflow-banner__image-wrap desktop-only" aria-hidden="true">
            <img class="overflow-banner__image" src="<?php echo esc_url($image_url); ?>" alt="" loading="lazy">
        </div>
    </div>
</section>
