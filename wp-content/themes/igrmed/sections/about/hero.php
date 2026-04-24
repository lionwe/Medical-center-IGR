<?php
$hero_about_left_text = trim((string) get_field('hero_about_left_text'));
$hero_about_img = get_field('hero_about_img');
$hero_about_bg = get_field('hero_about_bg');

$hero_about_img_url = '';
$hero_about_img_alt = '';
$hero_about_bg_url = '';

if (is_array($hero_about_img)) {
    $hero_about_img_url = (string) ($hero_about_img['url'] ?? '');
    $hero_about_img_alt = $hero_about_img['alt'] ?? '';
} elseif (is_numeric($hero_about_img)) {
    $hero_about_img_url = (string) wp_get_attachment_image_url((int) $hero_about_img, 'full');
    $hero_about_img_alt = get_post_meta((int) $hero_about_img, '_wp_attachment_image_alt', true);
} elseif (is_string($hero_about_img)) {
    $hero_about_img_url = $hero_about_img;
    $hero_about_img_alt = '';
}

if (is_array($hero_about_bg)) {
    $hero_about_bg_url = (string) ($hero_about_bg['url'] ?? '');
    $hero_about_bg_alt = $hero_about_bg['alt'] ?? '';
} elseif (is_numeric($hero_about_bg)) {
    $hero_about_bg_url = (string) wp_get_attachment_image_url((int) $hero_about_bg, 'full');
    $hero_about_bg_alt = get_post_meta((int) $hero_about_bg, '_wp_attachment_image_alt', true);
} elseif (is_string($hero_about_bg)) {
    $hero_about_bg_url = $hero_about_bg;
    $hero_about_bg_alt = '';
}

// Empty Fields Rule: treat left text as required primary content.
if ($hero_about_left_text === '') {
    return;
}
?>

<section id="about-hero" class="about-hero">
    <?php if ($hero_about_bg_url !== ''): ?>
        <?php
        get_picture([
            'src' => $hero_about_bg_url,
            'alt' => $hero_about_bg_alt,
            'class' => 'about-hero__bg',
            'lazy' => true,
        ]);
        ?>
    <?php endif; ?>
    <div class="container">
        <?php get_template_part('templates/breadcrumbs'); ?>

        <div class="about-hero__wrapper">
            <div class="about-hero__left">
                <?php echo wp_kses_post($hero_about_left_text); ?>
            </div>
            <?php
            if ($hero_about_img_url !== '') {
                get_picture([
                    'src' => $hero_about_img_url,
                    'alt' => $hero_about_img_alt,
                    'class' => 'about-hero__img',
                ]);
            }
            ?>

        </div>
    </div>
</section>