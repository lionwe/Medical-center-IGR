<?php
/*
Template Name: Pregnancy Planning
Template Post Type: services
*/

get_header();
?>

<main id="pregnancy-planning">

    <?php get_template_part('sections/pregnancy-planning/hero'); ?>
    <?php get_template_part('sections/pregnancy-planning/what-examinations'); ?>
    <?php get_template_part('sections/pregnancy-planning/advantages-preg'); ?>
    <?php get_template_part('sections/services/faq'); ?>
    <?php get_template_part('sections/home/cta'); ?>


</main>

<?php get_footer('simple'); ?>