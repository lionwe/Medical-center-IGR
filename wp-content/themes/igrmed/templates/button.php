<?php
/**
 * Button Component
 *
 * Usage:
 * get_template_part('templates/button', null, [
 *   'text'        => 'Детальніше',
 *   'link'        => '#',
 *   'type'        => 'primary', // primary, primary-soft-hover, primary-calm, primary-calm-soft, secondary, tertiary, social, carousel, carousel-glass
 *   'icon_name'   => 'arrow',   // ACF field name without 'icon_' prefix
 *   'icon_url'    => '',        // direct URL override
 *   'target'      => '_self'
 * ]);
 */

$text = (string) ($args['text'] ?? '');
$href = (string) ($args['link'] ?? '#');
$type = (string) ($args['type'] ?? 'primary');
$icon_name = $args['icon_name'] ?? null;
$icon_url = $args['icon_url'] ?? null;
$target = (string) ($args['target'] ?? '_self');
$class_extra = trim((string) ($args['class'] ?? ''));
$modifier = $args['modifier'] ?? '';
$use_img_icon = !empty($args['icon_as_img']) && $type !== 'social';
$is_primary_split = !empty($args['primary_split']) && in_array($type, ['primary', 'primary-soft-hover', 'primary-calm', 'primary-calm-soft'], true) && $text !== '';
$carousel_group = $args['carousel_group'] ?? null;

// Get icon from ACF options or direct URL.
$icon_alt = '';
if (!$icon_url && $icon_name) {
    $field_name = 'icon_' . $icon_name;
    $icon_val = get_field($field_name, 'option');

    if (is_array($icon_val)) {
        $icon_url = $icon_val['url'] ?? null;
        $icon_alt = $icon_val['alt'] ?? '';
    } elseif (is_numeric($icon_val)) {
        $icon_url = wp_get_attachment_url((int) $icon_val);
        $icon_alt = get_post_meta((int) $icon_val, '_wp_attachment_image_alt', true) ?? '';
    } else {
        $icon_url = $icon_val ?: null;
    }
}

// If icon_url passed directly, check if it's from ACF
if ($icon_url && !$icon_alt && strpos($icon_url, get_template_directory_uri()) === false) {
    // Might be an ACF image URL, try to get alt
    $attachment_id = attachment_url_to_postid($icon_url);
    if ($attachment_id) {
        $icon_alt = get_post_meta($attachment_id, '_wp_attachment_image_alt', true) ?? '';
    }
}

// Fallback alt for theme asset icons
if ($icon_url && !$icon_alt && strpos($icon_url, get_template_directory_uri()) !== false) {
    $icon_alt = 'Button icon';
}

if ($type === 'carousel' && is_array($carousel_group) && !empty($carousel_group)) {
    $render_carousel_control = static function (array $control) use ($icon_url): void {
        $control_icon_url = (string) ($control['icon_url'] ?? $icon_url ?? '');
        $control_class = trim((string) ($control['class'] ?? ''));
        $control_label = trim((string) ($control['aria_label'] ?? ''));
        $attributes = $control['attributes'] ?? [];

        if (!is_array($attributes)) {
            $attributes = [];
        }

        if ($control_label !== '' && !isset($attributes['aria-label'])) {
            $attributes['aria-label'] = $control_label;
        }

        $attrs = 'type="button"';
        foreach ($attributes as $attr => $value) {
            $attrs .= ' ' . esc_attr((string) $attr) . '="' . esc_attr((string) $value) . '"';
        }
        ?>
        <button class="<?php echo esc_attr(trim('btn btn--carousel ' . $control_class)); ?>" <?php echo $attrs; ?>>
            <?php if ($control_icon_url !== ''): ?>
                <span class="btn__icon" style="-webkit-mask-image: url('<?php echo esc_url($control_icon_url); ?>'); mask-image: url('<?php echo esc_url($control_icon_url); ?>');"></span>
            <?php endif; ?>
        </button>
        <?php
    };

    foreach ($carousel_group as $control) {
        if (!is_array($control)) {
            continue;
        }
        $render_carousel_control($control);
    }
    return;
}

// Build classes for regular button.
$classes = 'btn btn--' . $type;
if ($modifier !== '') {
    $classes .= ' btn--' . $type . '--' . $modifier;
}
if ($class_extra !== '') {
    $classes .= ' ' . $class_extra;
}
if (!$icon_url) {
    $classes .= ' btn--no-icon';
}

// Determine tag and attributes.
$tag = ($type === 'button' || $type === 'submit') ? 'button' : 'a';

if ($tag === 'a') {
    $attrs = 'href="' . esc_url($href) . '" target="' . esc_attr($target) . '"';
} else {
    $button_type = $type === 'submit' ? 'submit' : 'button';
    $attrs = 'type="' . esc_attr($button_type) . '"';
}

if (!empty($args['attributes']) && is_array($args['attributes'])) {
    foreach ($args['attributes'] as $attr => $value) {
        $attrs .= ' ' . esc_attr((string) $attr) . '="' . esc_attr((string) $value) . '"';
    }
}

if ($is_primary_split) {
    $btn_split_class = 'btn-split';
    if ($class_extra !== '') {
        $btn_split_class .= ' ' . $class_extra;
    }
    if (strpos($btn_split_class, 'btn-split--') === false) {
        $btn_split_class .= ' btn-split--' . $type;
    }
    ?>
    <?php if ($tag === 'a'): ?>
        <a class="<?php echo esc_attr($btn_split_class); ?>" <?php echo $attrs; ?>>
            <span class="btn-split__text"><?php echo esc_html($text); ?></span>
            <span class="btn-split__icon" aria-hidden="true">
                <?php if ($icon_url): ?>
                    <?php if ($use_img_icon): ?>
                        <span class="btn__icon btn__icon--img">
                            <?php
                            get_picture([
                                'src' => $icon_url,
                                'alt' => $icon_alt,
                                'class' => 'btn__icon-image',
                                'lazy' => false,
                            ]);
                            ?>
                        </span>
                    <?php else: ?>
                        <span class="btn__icon" style="-webkit-mask-image: url('<?php echo esc_url($icon_url); ?>'); mask-image: url('<?php echo esc_url($icon_url); ?>');" role="img" aria-label="<?php echo esc_attr($icon_alt ?: 'Button icon'); ?>"></span>
                    <?php endif; ?>
                <?php endif; ?>
            </span>
        </a>
    <?php else: ?>
        <div class="<?php echo esc_attr($btn_split_class); ?>">
            <span class="btn-split__text"><?php echo esc_html($text); ?></span>
            <span class="btn-split__icon" aria-hidden="true">
                <?php if ($icon_url): ?>
                    <?php if ($use_img_icon): ?>
                        <span class="btn__icon btn__icon--img">
                            <?php
                            get_picture([
                                'src' => $icon_url,
                                'alt' => $icon_alt,
                                'class' => 'btn__icon-image',
                                'lazy' => false,
                            ]);
                            ?>
                        </span>
                    <?php else: ?>
                        <span class="btn__icon" style="-webkit-mask-image: url('<?php echo esc_url($icon_url); ?>'); mask-image: url('<?php echo esc_url($icon_url); ?>');" role="img" aria-label="<?php echo esc_attr($icon_alt ?: 'Button icon'); ?>"></span>
                    <?php endif; ?>
                <?php endif; ?>
            </span>
        </div>
    <?php endif; ?>
    <?php
    return;
}
?>

<<?php echo $tag; ?> class="<?php echo esc_attr($classes); ?>" <?php echo $attrs; ?>>
    <?php if ($text !== '' && $type !== 'social'): ?>
        <span class="btn__text"><?php echo esc_html($text); ?></span>
    <?php endif; ?>

    <?php if ($icon_url): ?>
        <?php if ($type === 'readmore-v1'): ?>
            <span class="btn__icon-container">
        <?php endif; ?>

        <?php if ($use_img_icon): ?>
            <span class="btn__icon btn__icon--img">
                <?php
                get_picture([
                    'src' => $icon_url,
                    'alt' => $icon_alt ?: 'Button icon',
                    'class' => 'btn__icon-image',
                    'lazy' => false,
                ]);
                ?>
            </span>
        <?php else: ?>
            <span class="btn__icon" style="-webkit-mask-image: url('<?php echo esc_url($icon_url); ?>'); mask-image: url('<?php echo esc_url($icon_url); ?>');" role="img" aria-label="<?php echo esc_attr($icon_alt ?: 'Button icon'); ?>"></span>
        <?php endif; ?>

        <?php if ($type === 'readmore-v1'): ?>
            </span>
        <?php endif; ?>
    <?php endif; ?>
</<?php echo $tag; ?>>
