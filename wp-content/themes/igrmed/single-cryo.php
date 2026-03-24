<?php
/*
Template Name: Cryotechnology
Template Post Type: services
*/

get_header();
?>

<main id="cryotechnology">
    <?php get_template_part('sections/cryotechnology/hero'); ?>
    <?php get_template_part('sections/cryotechnology/content'); ?>
    <?php get_template_part('sections/services/faq'); ?>
    <?php get_template_part('sections/home/cta'); ?>
</main>

<?php get_footer('simple'); ?>