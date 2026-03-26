<?php
/*
Template Name: Egg Donor
Template Post Type: services
*/

get_header();
?>

<main id="egg-donor">
    <?php get_template_part('sections/egg-donor/hero'); ?>
    <?php get_template_part('sections/egg-donor/content'); ?>
    <?php get_template_part('sections/services/faq'); ?>
    <?php get_template_part('sections/home/cta'); ?>
</main>

<?php get_footer('simple'); ?>
