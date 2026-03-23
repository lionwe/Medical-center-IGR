<?php
/**
 * Privacy Policy Hero Section
 *
 * @package IGRMed
 */

$hero_bg_url = get_template_directory_uri() . '/assets/img/bread-crumbs.webp';
$hero_style = ' style="background-image: url(' . esc_url($hero_bg_url) . ');"';
?>

<section class="privacy-hero" <?php echo $hero_style; ?>>
    <div class="container">
        <?php get_template_part('templates/breadcrumbs'); ?>
        <div class="simple-banner">
            <div class="simple-banner__inner">
                <div class="simple-banner__content">
                    <h2 class="simple-banner__title"><?php the_title(); ?></h2>
                </div>
            </div>
        </div>
    </div>
</section>
