<?php
$hero_bg_url = get_template_directory_uri() . '/assets/img/bread-crumbs.webp';
$hero_style = ' style="background-image: url(' . esc_url($hero_bg_url) . ');"';
$title_pregnant = trim((string) get_field('title_pregnant'));
$right_text_pregnant = (string) get_field('right_text_pregnant');

// Empty Fields Rule: title is required for this section.
if ($title_pregnant === '') {
    return;
}
?>

<section id="infertility-women-hero" class="infertility-women-hero" <?php echo $hero_style; ?>>
    <div class="container">
        <?php get_template_part('templates/breadcrumbs'); ?>
        <?php get_template_part('templates/gradient-banner'); ?>

        <div class="infertility-women-hero__wrapper">
            <div class="infertility-women-hero__bg" aria-hidden="true">
                <div class="infertility-women-hero__bg-desktop" aria-hidden="true"></div>
            </div>
            <div class="infertility-women-hero__left">
                <div class="infertility-women-hero__left-title"><?php echo wp_kses_post($title_pregnant); ?>
                </div>
            </div>

            <div class="infertility-women-hero__right">
                <?php if ($right_text_pregnant !== ''): ?>
                    <div class="infertility-women-hero__text"><?php echo wp_kses_post($right_text_pregnant); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>