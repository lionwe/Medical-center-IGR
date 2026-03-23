<?php
/**
 * Price Page Hero Section
 *
 * @package IGRMed
 */
?>

<section class="price-hero simple-banner-section">
    <div class="container">
        <?php get_template_part('templates/breadcrumbs'); ?>
        <?php
        get_template_part('templates/simple-banner', null, [
            'title' => get_the_title()
        ]);
        ?>
    </div>
</section>
