<?php
/**
 * Hero Section for Single Blog
 *
 * @package IGRMed
 */

$post_id = get_the_ID();
$date = get_the_date('j F, Y');
$post_obj = get_post($post_id);
$minutes = (function_exists('reading_time') && $post_obj) ? reading_time($post_obj->post_content) : 3;
$read_time = sprintf(igrmed__('blog_reading_time'), $minutes);

$icon_calendar = get_field('icon_calendar', 'option');
$icon_clock = get_field('icon_clock_2', 'option');
$icon_calendar_alt = is_array($icon_calendar) ? ($icon_calendar['alt'] ?? '') : '';
$icon_clock_alt = is_array($icon_clock) ? ($icon_clock['alt'] ?? '') : '';
if (is_numeric($icon_calendar)) {
    $icon_calendar_alt = get_post_meta((int) $icon_calendar, '_wp_attachment_image_alt', true) ?? '';
}
if (is_numeric($icon_clock)) {
    $icon_clock_alt = get_post_meta((int) $icon_clock, '_wp_attachment_image_alt', true) ?? '';
}
?>

<section class="blog-hero">
    <div class="container">
        <?php get_template_part('templates/breadcrumbs'); ?>

        <div class="blog-hero__bottom-container">
            <div class="blog-hero__meta">
                <div class="blog-hero__meta-item">
                    <span class="blog-hero__meta-icon-wrapper">
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
                    <span class="blog-hero__meta-text"><?php echo esc_html($date); ?></span>
                </div>

                <div class="blog-hero__meta-separator"></div>

                <div class="blog-hero__meta-item">
                    <span class="blog-hero__meta-icon-wrapper">
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
                    <span class="blog-hero__meta-text"><?php echo esc_html($read_time); ?></span>
                </div>
            </div>

            <h1 class="blog-hero__title">
                <?php the_title(); ?>
            </h1>
        </div>
    </div>
</section>