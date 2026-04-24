<?php
/*
Template Name: About
*/
?>

<?php get_header(); ?>

<main id="about">
    <?php get_template_part('sections/about/hero'); ?>
    <?php get_template_part('sections/about/facts'); ?>
        <?php get_template_part('sections/home/why-choose-us'); ?>

    <?php get_template_part('sections/about/gallery'); ?>
    <?php get_template_part('sections/google-reviews'); ?>
    <?php get_template_part('sections/home/cta'); ?>
</main>

<?php get_footer('simple'); ?>