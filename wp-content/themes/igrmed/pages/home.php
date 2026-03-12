<?php
/*
Template Name: Home
*/
?>

<?php get_header(); ?>

<main id="home">
    <?php get_template_part('sections/home/hero'); ?>
    <?php get_template_part('sections/home/advantages'); ?>
    <?php get_template_part('sections/home/doctors'); ?>
    <?php get_template_part('sections/home/google-reviews'); ?>
    <?php get_template_part('sections/home/licenses-certificates'); ?>
    <?php get_template_part('sections/home/services'); ?>
    <?php get_template_part('sections/home/why-choose-us'); ?>
    <?php get_template_part('sections/home/blog-list'); ?>
</main>

<?php get_footer(); ?>