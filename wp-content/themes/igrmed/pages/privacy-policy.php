<?php
/**
 * Template Name: Privacy Policy Page
 * 
 * @package IGRMed
 */

get_header();
?>

<main id="primary" class="site-main privacy-policy-page" style="--_section-gap: 1.875rem;">
    <?php
    get_template_part('sections/privacy-policy/hero');
    get_template_part('sections/privacy-policy/content');
    ?>
</main>

<?php
get_footer();
