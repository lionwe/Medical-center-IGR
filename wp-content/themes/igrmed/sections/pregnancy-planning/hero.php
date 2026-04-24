<?php
$hero_bg_url = get_template_directory_uri() . '/assets/img/bread-crumbs.webp';
$title_pregnant = trim((string) get_field('title_pregnant'));
$right_text_pregnant = (string) get_field('right_text_pregnant');

// Empty Fields Rule: title is required for this section.
if ($title_pregnant === '') {
    return;
}
?>

<section id="pregnancy-planning-hero" class="pregnancy-planning-hero">
    <?php
    get_picture([
        'name' => 'bread-crumbs.webp',
        'alt' => '',
        'class' => 'pregnancy-planning-hero__bg',
        'lazy' => false,
    ]);
    ?>
    <div class="container">
        <?php get_template_part('templates/breadcrumbs'); ?>
        <?php get_template_part('templates/gradient-banner'); ?>

        <div class="pregnancy-planning-hero__wrapper">
            <div class="pregnancy-planning-hero__bg" aria-hidden="true">
                <div class="pregnancy-planning-hero__bg-desktop" aria-hidden="true"></div>
            </div>
            <div class="pregnancy-planning-hero__left">
                <div class="pregnancy-planning-hero__left-title"><?php echo wp_kses_post($title_pregnant); ?>
                </div>
            </div>

            <div class="pregnancy-planning-hero__right">
                <?php if ($right_text_pregnant !== ''): ?>
                    <div class="pregnancy-planning-hero__text"><?php echo wp_kses_post($right_text_pregnant); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>