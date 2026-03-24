<?php
/*
Template Name: Andrology
Template Post Type: services
*/

get_header();
?>

<main id="male-infertility">
    <?php get_template_part('sections/male-infertility/hero'); ?>
    <?php get_template_part('sections/male-infertility/content'); ?>
    <?php get_template_part('templates/overflow-banner'); ?>
    <?php get_template_part('sections/services/faq'); ?>
    <?php get_template_part('sections/home/cta'); ?>
</main>

<?php get_footer('simple'); ?>