<?php

/**
 * 404 Page Content Section
 *
 * @package IGRMed
 */
?>

<section class="error-404">
    <div class="error-404__background">
        <?php echo igrmed_get_svg('404-bg'); ?>
    </div>
    <div class="error-404__logo">
        <?php get_picture([
            'name' => 'logo-without-text.webp',
            'alt'  => esc_attr__('IGRMed Logo', 'igrmed'),
            'class' => 'error-404__logo-img',
            'lazy' => false,
        ]); ?>
    </div>
    <div class="container">
        <div class="error-404__content">
            <div class="error-404__num">404</div>
            <h1 class="error-404__title">
                <?php igrmed_e('error_404_title'); ?>
            </h1>
            <p class="error-404__text">
                <?php igrmed_e('error_404_text'); ?>
            </p>
        </div>
        <div class="error-404__cta">
            <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn--back-home">
                <span class="btn__text"><?php igrmed_e('breadcrumbs_home'); ?></span>
            </a>
        </div>
    </div>
</section>