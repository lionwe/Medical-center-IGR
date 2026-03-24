<?php
/*
Template Name: Pregnancy Management
Template Post Type: services
*/

get_header();
?>

<main id="pregnancy-management">
    <?php get_template_part('sections/pregnancy-management/hero'); ?>
    <?php get_template_part('sections/pregnancy-management/content'); ?>
    <?php get_template_part('sections/services/faq'); ?>
    <?php get_template_part('sections/home/cta'); ?>
</main>

<?php get_footer('simple'); ?>
