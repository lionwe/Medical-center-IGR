<?php
$title = (string) ($args['title'] ?? 'Стати донором яйцеклітин');
$button_text = (string) __("Зв'язатись з нами", 'igrmed');
$button_link = '#cta';
$image_url = get_template_directory_uri() . '/assets/img/ba5c6331bea41bf283dc73f4555c3659-removebg-preview 1.webp';
?>

<section class="overflow-banner">
    <div class="overflow-banner__inner">
        <div class="overflow-banner__content">
            <?php if ($title !== ''): ?>
                <h2 class="overflow-banner__title"><?php echo esc_html($title); ?></h2>
            <?php endif; ?>

            <div class="overflow-banner__cta">
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

        <div class="overflow-banner__image-wrap" aria-hidden="true">
            <img class="overflow-banner__image" src="<?php echo esc_url($image_url); ?>" alt="" loading="lazy">
        </div>
    </div>
</section>
