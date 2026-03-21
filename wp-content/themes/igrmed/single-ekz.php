<?php
/*
Template Name: EKZ
Template Post Type: services
*/

get_header();
?>

<main id="ekz">
    <?php get_template_part('sections/ekz/hero'); ?>
    <?php get_template_part('sections/ekz/content'); ?>
    <?php get_template_part('sections/services/faq'); ?>
    <?php get_template_part('sections/home/cta'); ?>
</main>

<?php get_footer('simple'); ?>
