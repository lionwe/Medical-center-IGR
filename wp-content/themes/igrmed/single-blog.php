<?php

/**
 * Single Blog Template
 * 
 * @package IGRMed
 */

get_header();

?>

<main id="single-blog">
    <?php
    // Hero section
    get_template_part('sections/single-blog/hero');

    // Content section
    get_template_part('sections/single-blog/content');

    // FAQ section
    get_template_part('sections/services/faq');
    ?>
</main>

<?php get_footer('simple'); ?>