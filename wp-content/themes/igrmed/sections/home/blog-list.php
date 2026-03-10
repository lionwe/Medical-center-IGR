<?php
$title = get_field('blog_title');
$posts = get_field('blog_posts');

if (!$title && !$posts) {
    return;
}
?>

<section class="blog-list">
    <div class="container">
        <div class="blog-list__header">
            <div class="blog-list__header-content">
                <?php if ($title): ?>
                    <h2 class="blog-list__title"><?php echo esc_html($title); ?></h2>
                <?php endif; ?>

                <?php get_template_part('templates/button', null, [
                    'type' => 'secondary',
                    'text' => __('Переглянути більше новин', 'igrmed'),
                    'link' => get_post_type_archive_link('blog'),
                    'class' => 'blog-list__more-desktop'
                ]); ?>
            </div>

            <!-- Mobile Controls Floor -->
            <div class="blog-list__controls">
                <?php get_template_part('templates/button', null, [
                    'type' => 'secondary',
                    'text' => __('Переглянути більше новин', 'igrmed'),
                    'link' => get_post_type_archive_link('blog'),
                    'class' => 'blog-list__more-mobile'
                ]); ?>

                <div class="blog-list__nav">
                    <?php get_template_part('templates/button', null, [
                        'type' => 'carousel',
                        'icon_url' => get_template_directory_uri() . '/assets/img/svg/arrow-prev.svg',
                        'class' => 'blog-list__prev js-blog-prev',
                        'attributes' => ['aria-label' => __('Попередній запис', 'igrmed')]
                    ]); ?>
                    <?php get_template_part('templates/button', null, [
                        'type' => 'carousel',
                        'icon_url' => get_template_directory_uri() . '/assets/img/svg/arrow-next.svg',
                        'class' => 'blog-list__next js-blog-next',
                        'attributes' => ['aria-label' => __('Наступний запис', 'igrmed')]
                    ]); ?>
                </div>
            </div>
        </div>

        <?php if ($posts): ?>
            <!-- Desktop Grid -->
            <div class="blog-list__grid">
                <?php
                global $post;
                foreach ($posts as $post) {
                    setup_postdata($post);
                    get_template_part('templates/blog-card');
                }
                wp_reset_postdata();
                ?>
            </div>

            <!-- Mobile Swiper -->
            <div class="blog-list__slider swiper js-blog-slider">
                <div class="swiper-wrapper">
                    <?php
                    global $post;
                    foreach ($posts as $post) {
                        setup_postdata($post);
                        echo '<div class="swiper-slide">';
                        get_template_part('templates/blog-card');
                        echo '</div>';
                    }
                    wp_reset_postdata();
                    ?>
                </div>
                <!-- Pagination -->
                <div class="blog-list__pagination swiper-pagination"></div>
            </div>
        <?php endif; ?>
    </div>
</section>