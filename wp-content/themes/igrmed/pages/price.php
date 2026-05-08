<?php

/**
 * Template Name: Price Page
 * 
 * @package IGRMed
 */

get_header();
?>

<main id="primary" class="site-main price-page" style="--_section-gap: 0;">
    <?php
    get_template_part('sections/price/hero');
    get_template_part('sections/price/list');
    ?>
</main>

<?php
get_footer();
