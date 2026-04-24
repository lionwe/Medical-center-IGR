<?php

/**
 * Hero Section for Blog Archive
 *
 * @package IGRMed
 */

$hero_bg_url = get_template_directory_uri() . '/assets/img/bread-crumbs.webp';
?>

<section class="blog-archive-hero">
    <?php
    get_picture([
        'name' => 'bread-crumbs.webp',
        'alt' => '',
        'class' => 'blog-archive-hero__bg',
        'lazy' => false,
    ]);
    ?>
    <div class="container">
        <?php get_template_part('templates/breadcrumbs'); ?>
        <?php
        get_template_part('templates/photo-banner', null, [
            'title' => igrmed__('blog_title')
        ]);
        ?>
    </div>
</section>