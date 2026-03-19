<?php
/**
 * Content Section for Single Blog
 *
 * @package IGRMed
 */

if (!get_the_content()) return;
?>
<section class="blog-content">
    <div class="container">
        <div class="blog-content__inner">
            <?php the_content(); ?>
        </div>
    </div>
</section>
