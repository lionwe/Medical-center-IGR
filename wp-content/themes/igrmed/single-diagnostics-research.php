<?php
/*
Template Name: Diagnostics Research
Template Post Type: services
*/

get_header();
?>

<main id="diagnostics-research">
    <?php get_template_part('sections/diagnostics-research/hero'); ?>
    <?php get_template_part('sections/diagnostics-research/diagnostics'); ?>
    <?php get_template_part('sections/services/faq'); ?>
    <?php get_template_part('sections/home/cta'); ?>
</main>

<?php get_footer('simple'); ?>