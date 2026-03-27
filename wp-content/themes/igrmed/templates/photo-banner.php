<?php
$title = trim((string) ($args['title'] ?? get_the_title()));
$button_text = (string) __("Зв'язатись з нами", 'igrmed');
$button_link = '#cta';
$image_url = get_template_directory_uri() . '/assets/img/baby.webp';

// Empty Fields Rule: title is required for this block.
if ($title === '') {
    return;
}
?>

<section class="photo-banner">
    <div class="photo-banner__inner">
        <div class="photo-banner__content">
            <h2 class="photo-banner__title"><?php echo esc_html($title); ?></h2>

            <div class="photo-banner__cta">
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

        <div class="photo-banner__image-wrap" aria-hidden="true">
            <img class="photo-banner__image" src="<?php echo esc_url($image_url); ?>" alt="" loading="lazy">
        </div>
    </div>
</section>