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
?>

<section class="blog-hero">
    <div class="container">
        <?php get_template_part('templates/breadcrumbs'); ?>

        <div class="blog-hero__bottom-container">
            <div class="blog-hero__meta">
                <div class="blog-hero__meta-item">
                    <span class="blog-hero__meta-icon-wrapper">
                        <?php if ($icon_calendar && isset($icon_calendar['url'])): ?>
                            <img src="<?php echo esc_url($icon_calendar['sizes']['thumbnail'] ?? $icon_calendar['url']); ?>"
                                alt="Calendar" loading="lazy">
                        <?php endif; ?>
                    </span>
                    <span class="blog-hero__meta-text"><?php echo esc_html($date); ?></span>
                </div>

                <div class="blog-hero__meta-separator"></div>

                <div class="blog-hero__meta-item">
                    <span class="blog-hero__meta-icon-wrapper">
                        <?php if ($icon_clock && isset($icon_clock['url'])): ?>
                            <img src="<?php echo esc_url($icon_clock['sizes']['thumbnail'] ?? $icon_clock['url']); ?>"
                                alt="Clock" loading="lazy">
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