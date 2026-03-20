<?php

/**
 * Blog List Section for Archive
 *
 * @package IGRMed
 */

if (!have_posts()) : ?>
    <section class="blog-archive-empty">
        <div class="container">
            <p><?php esc_html_e('Статей поки що немає.', 'igrmed'); ?></p>
        </div>
    </section>
<?php return;
endif; ?>

<section class="blog-archive-list">
    <div class="container">
        <div class="blog-archive-list__grid js-blog-grid">
            <?php
            $count = 0;
            global $wp_query;
            $posts_per_page = (int) $wp_query->get('posts_per_page');
            while (have_posts()) : the_post();
                $count++;
            ?>
                <div class="blog-archive-list__item <?php echo $count > $posts_per_page ? 'js-ajax-item' : ''; ?>" <?php echo $count > $posts_per_page ? 'style="display: none;"' : ''; ?>>
                    <?php get_template_part('templates/blog-card'); ?>
                </div>
            <?php endwhile; ?>
        </div>

        <div class="blog-archive-list__load-more">
            <?php
            if ($wp_query->max_num_pages > 1) :
                $nonce = wp_create_nonce('blog_archive_nonce');
            ?>
                <button type="button"
                    class="btn btn--simple-outline js-load-more-blog"
                    data-paged="1"
                    data-max="<?php echo (int) $wp_query->max_num_pages; ?>"
                    data-nonce="<?php echo esc_attr($nonce); ?>"
                    data-text-load="<?php esc_attr_e('Читати більше', 'igrmed'); ?>"
                    data-text-collapse="<?php esc_attr_e('Згорнути', 'igrmed'); ?>">
                    <span class="btn__text"><?php esc_html_e('Читати більше', 'igrmed'); ?></span>
                </button>
            <?php endif; ?>
        </div>
    </div>
</section>