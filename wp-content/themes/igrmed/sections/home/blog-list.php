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
            <?php if ($title): ?>
                <h2 class="blog-list__title"><?php echo esc_html($title); ?></h2>
            <?php endif; ?>

            <?php get_template_part('templates/button', null, [
                'type' => 'secondary',
                'text' => __('Переглянути більше новин', 'igrmed'),
                'link' => get_post_type_archive_link('blog')
            ]); ?>
        </div>

        <?php if ($posts): ?>
            <div class="blog-list__grid">
                <?php
                global $post; // Preserve global post object

                foreach ($posts as $post) {
                    setup_postdata($post);
                    get_template_part('templates/blog-card');
                }

                wp_reset_postdata();
                ?>
            </div>
        <?php endif; ?>
    </div>
</section>