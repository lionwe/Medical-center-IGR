<?php
/*
Template Name: Gynecology
Template Post Type: services
*/

get_header();
?>

<main id="gynecology">
    <?php get_template_part('sections/gynecology/hero'); ?>
    <?php get_template_part('sections/gynecology/content'); ?>
    <?php get_template_part('sections/services/faq'); ?>
    <?php get_template_part('sections/home/cta'); ?>
</main>

<?php get_footer('simple'); ?>
