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
            <?php
            $image_alt = get_post_meta(get_post_thumbnail_id(), '_wp_attachment_image_alt', true);
            get_picture([
                'src' => $image_url,
                'alt' => $image_alt,
                'class' => 'blog-card__img',
                'lazy' => true,
            ]);
            ?>
        </a>

        <div class="blog-card__meta">
            <?php
            $icon_calendar = get_field('icon_calendar', 'option');
            $icon_clock = get_field('icon_clock_2', 'option');
            $icon_calendar_alt = is_array($icon_calendar) ? $icon_calendar['alt'] : '';
            $icon_clock_alt = is_array($icon_clock) ? $icon_clock['alt'] : '';
            if (is_numeric($icon_calendar)) {
                $icon_calendar_alt = get_post_meta((int) $icon_calendar, '_wp_attachment_image_alt', true);
            }
            if (is_numeric($icon_clock)) {
                $icon_clock_alt = get_post_meta((int) $icon_clock, '_wp_attachment_image_alt', true);
            }
            ?>

            <div class="blog-card__meta-item">
                <span class="blog-card__meta-icon-wrapper">
                    <?php if ($icon_calendar && isset($icon_calendar['url'])): ?>
                        <?php
                        get_picture([
                            'src' => $icon_calendar['sizes']['thumbnail'] ?? $icon_calendar['url'],
                            'alt' => $icon_calendar_alt,
                            'class' => '',
                            'lazy' => true,
                        ]);
                        ?>
                    <?php endif; ?>
                </span>
                <span class="blog-card__meta-text"><?php echo esc_html($date); ?></span>
            </div>

            <div class="blog-card__meta-separator"></div>

            <div class="blog-card__meta-item">
                <span class="blog-card__meta-icon-wrapper">
                    <?php if ($icon_clock && isset($icon_clock['url'])): ?>
                        <?php
                        get_picture([
                            'src' => $icon_clock['sizes']['thumbnail'] ?? $icon_clock['url'],
                            'alt' => $icon_clock_alt,
                            'class' => '',
                            'lazy' => true,
                        ]);
                        ?>
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