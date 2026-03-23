<?php

/**
 * Price Page Hero Section
 *
 * @package IGRMed
 */

$hero_bg_url = get_template_directory_uri() . '/assets/img/bread-crumbs.webp';
$hero_style = ' style="background-image: url(' . esc_url($hero_bg_url) . ');"';
?>

<section class="price-hero" <?php echo $hero_style; ?>>
    <div class="container">
        <?php get_template_part('templates/breadcrumbs'); ?>
        <?php
        get_template_part('templates/simple-banner', null, [
            'title' => get_the_title()
        ]);
        ?>
    </div>
</section>