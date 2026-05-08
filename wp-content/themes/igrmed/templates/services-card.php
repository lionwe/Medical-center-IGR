<?php

/**
 * Template: Service Card
 * Args: post (WP_Post)
 * ACF Field: service_icon (image array) — from CPT "services"
 */

$post = $args['post'] ?? null;

if (!$post) {
    return;
}

$icon       = get_field('service_icon', $post->ID);
$title      = get_the_title($post);
$permalink  = get_permalink($post);

$icon_url = '';
$icon_alt = '';

if (is_array($icon)) {
    $icon_url = $icon['url'] ?? '';
    $icon_alt = $icon['alt'] ?? '';
} elseif (is_numeric($icon)) {
    $icon_url = wp_get_attachment_url((int) $icon) ?: '';
    $icon_alt = get_post_meta((int) $icon, '_wp_attachment_image_alt', true) ?: '';
} elseif (is_string($icon)) {
    $icon_url = $icon;
}

if (!$title) {
    return;
}

// Wrap text in parentheses into a styled <span>
$title_html = preg_replace_callback(
    '/\(([^)]+)\)/',
    static function (array $matches): string {
        return '<span class="service-card__title-sub">(' . esc_html($matches[1]) . ')</span>';
    },
    esc_html($title)
);

$hover_bg = get_template_directory_uri() . '/assets/img/service-card-hover-bg.webp';

?>
<article class="service-card"
    data-href="<?php echo esc_url($permalink); ?>"
    style="--service-card-hover-bg: url('<?php echo esc_url($hover_bg); ?>')">

    <?php if ($icon_url !== '') : ?>
        <div class="service-card__icon">
            <?php
            get_picture([
                'src' => $icon_url,
                'alt' => $icon_alt,
                'class' => '',
                'lazy' => true,
            ]);
            ?>
        </div>
    <?php endif; ?>

    <div class="service-card__body">
        <h3 class="service-card__title"><?php echo $title_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
                                        ?></h3>

        <a href="<?php echo esc_url($permalink); ?>"
            class="service-card__link"
            aria-label="<?php echo esc_attr(sprintf(igrmed__('btn_read_more_about'), $title)); ?>">
            <span class="service-card__link-text"><?php igrmed_e('btn_read_more'); ?></span>
            <span class="service-card__link-arrow" aria-hidden="true">
                <?php
                $arrow_path = get_template_directory() . '/assets/img/svg/arrow-next.svg';
                if (file_exists($arrow_path)) {
                    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                    echo file_get_contents($arrow_path);
                }
                ?>
            </span>
        </a>
    </div>

</article>