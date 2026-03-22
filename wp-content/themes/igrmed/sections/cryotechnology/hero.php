<?php
$hero_bg_url = get_template_directory_uri() . '/assets/img/bread-crumbs.webp';
$hero_style = ' style="background-image: url(' . esc_url($hero_bg_url) . ');"';
?>

<section id="cryotechnology-hero" class="cryotechnology-hero infertility-treatment-women-hero" <?php echo $hero_style; ?>>
    <div class="container">
        <?php get_template_part('templates/breadcrumbs'); ?>
        <?php get_template_part('templates/photo-banner'); ?>
    </div>
</section>