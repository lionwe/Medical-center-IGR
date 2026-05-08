<?php
$hero_bg_url = get_template_directory_uri() . '/assets/img/bread-crumbs.webp';
?>

<section id="gyn-hero" class="gyn-hero">
    <?php
    get_picture([
        'name' => 'bread-crumbs.webp',
        'alt' => '',
        'class' => 'gyn-hero__bg',
        'lazy' => false,
    ]);
    ?>
    <div class="container">
        <?php get_template_part('templates/breadcrumbs'); ?>
        <?php get_template_part('templates/photo-banner'); ?>
    </div>
</section>