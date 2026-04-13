<?php

/**
 * Blog Card Template
 */

$post_id        = get_the_ID();
$custom_image   = get_field('blog_card_image', $post_id);
$fallback_image = get_the_post_thumbnail_url($post_id, 'large');
$image_url      = $custom_image && isset($custom_image['url'])
    ? ($custom_image['sizes']['large'] ?? $custom_image['url'])
    : $fallback_image;

if (!$image_url) {
    $image_url = get_template_directory_uri() . '/assets/img/placeholder.png';
}

$date      = get_the_date('j F, Y');
$post_obj  = get_post($post_id);
$minutes   = (function_exists('reading_time') && $post_obj) ? reading_time($post_obj->post_content) : 3;
$read_time = sprintf(igrmed__('blog_reading_time'), $minutes);
?>

<article <?php post_class('blog-card'); ?>>
    <div class="blog-card__media">
        <a href="<?php echo esc_url(get_permalink()); ?>">
            <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" class="blog-card__img" loading="lazy">
        </a>

        <div class="blog-card__meta">
            <?php
            $icon_calendar = get_field('icon_calendar', 'option');
            $icon_clock = get_field('icon_clock_2', 'option');
            ?>

            <div class="blog-card__meta-item">
                <span class="blog-card__meta-icon-wrapper">
                    <?php if ($icon_calendar && isset($icon_calendar['url'])): ?>
                        <img src="<?php echo esc_url($icon_calendar['sizes']['thumbnail'] ?? $icon_calendar['url']); ?>" alt="Calendar" loading="lazy">
                    <?php endif; ?>
                </span>
                <span class="blog-card__meta-text"><?php echo esc_html($date); ?></span>
            </div>

            <div class="blog-card__meta-separator"></div>

            <div class="blog-card__meta-item">
                <span class="blog-card__meta-icon-wrapper">
                    <?php if ($icon_clock && isset($icon_clock['url'])): ?>
                        <img src="<?php echo esc_url($icon_clock['sizes']['thumbnail'] ?? $icon_clock['url']); ?>" alt="Clock" loading="lazy">
                    <?php endif; ?>
                </span>
                <span class="blog-card__meta-text"><?php echo esc_html($read_time); ?></span>
            </div>
        </div>
    </div>

    <div class="blog-card__content">
        <?php /* Mobile-only meta pill — shown via CSS only on mobile in blog archive */ ?>
        <div class="blog-card__meta blog-card__meta--inline">
            <div class="blog-card__meta-item">
                <span class="blog-card__meta-text"><?php echo esc_html($date); ?></span>
            </div>
            <div class="blog-card__meta-separator"></div>
            <div class="blog-card__meta-item">
                <span class="blog-card__meta-text"><?php echo esc_html($read_time); ?></span>
            </div>
        </div>

        <h3 class="blog-card__title">
            <a href="<?php echo esc_url(get_permalink()); ?>">
                <?php echo esc_html(wp_trim_words(get_the_title(), 10, '...')); ?>
            </a>
        </h3>
        <p class="blog-card__excerpt">
            <?php
            $excerpt = get_the_excerpt() ?: 'It is a long established fact that a reader will be distracted by the readable content';
            echo esc_html(wp_trim_words($excerpt, 15, '...'));
            ?>
        </p>
    </div>

    <div class="blog-card__footer">
        <?php get_template_part('templates/button', null, [
            'type'     => 'readmore-v1',
            'text'     => igrmed__('blog_read_more'),
            'link'     => get_permalink(),
            'icon_url' => get_template_directory_uri() . '/assets/img/svg/arrow-next.svg'
        ]); ?>
    </div>


    

</article>