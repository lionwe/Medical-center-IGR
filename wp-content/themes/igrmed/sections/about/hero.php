<?php
$hero_about_left_text = trim((string) get_field('hero_about_left_text'));
$hero_about_img = get_field('hero_about_img');
$hero_about_bg = get_field('hero_about_bg');

$hero_about_img_url = '';
$hero_about_bg_url = '';

if (is_array($hero_about_img)) {
    $hero_about_img_url = (string) ($hero_about_img['url'] ?? '');
} elseif (is_numeric($hero_about_img)) {
    $hero_about_img_url = (string) wp_get_attachment_image_url((int) $hero_about_img, 'full');
} elseif (is_string($hero_about_img)) {
    $hero_about_img_url = $hero_about_img;
}

if (is_array($hero_about_bg)) {
    $hero_about_bg_url = (string) ($hero_about_bg['url'] ?? '');
} elseif (is_numeric($hero_about_bg)) {
    $hero_about_bg_url = (string) wp_get_attachment_image_url((int) $hero_about_bg, 'full');
} elseif (is_string($hero_about_bg)) {
    $hero_about_bg_url = $hero_about_bg;
}

// Empty Fields Rule: treat left text as required primary content.
if ($hero_about_left_text === '') {
    return;
}

$about_hero_style = $hero_about_bg_url !== '' ? ' style="background-image: url(' . esc_url($hero_about_bg_url) . ');"' : '';
?>

<section id="about-hero" class="about-hero" <?php echo $about_hero_style; ?>>
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
                    'alt' => '',
                    'class' => 'about-hero__img',
                ]);
            }
            ?>

        </div>
    </div>
</section>