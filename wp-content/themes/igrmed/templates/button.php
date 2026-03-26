<?php
/**
 * Button Component
 *
 * Usage:
 * get_template_part('templates/button', null, [
 *   'text'        => 'Детальніше',
 *   'link'        => '#',
 *   'type'        => 'primary', // primary, primary-white-border, primary-white-border--black-border, primary-dark, secondary, tertiary, social, carousel, carousel-glass
*   'modifier'    => 'black-border', // Use with type: primary-white-border for black border variant
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
$is_primary_split = !empty($args['primary_split']) && in_array($type, ['primary', 'glass-primary', 'primary-white-border'], true) && $text !== '';
$carousel_group = $args['carousel_group'] ?? null;

// Get icon from ACF options or direct URL.
if (!$icon_url && $icon_name) {
    $field_name = 'icon_' . $icon_name;
    $icon_val = get_field($field_name, 'option');

    if (is_array($icon_val)) {
        $icon_url = $icon_val['url'] ?? null;
    } elseif (is_numeric($icon_val)) {
        $icon_url = wp_get_attachment_url((int) $icon_val);
    } else {
        $icon_url = $icon_val ?: null;
    }
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

// Add inline hover styles for primary-dark buttons (not for split buttons)
if ($type === 'primary-dark' && !$is_primary_split) {
    $attrs .= ' style="transition: background 0.3s ease, color 0.3s ease;" onmouseover="this.style.background=\'var(--color-white)\'; this.style.color=\'var(--color-primary-dark)\'; this.style.borderColor=\'var(--color-white)\'; const icon = this.querySelector(\'.btn__icon\'); if(icon) icon.style.backgroundColor=\'var(--color-primary-dark)\';" onmouseout="this.style.background=\'var(--color-primary-dark)\'; this.style.color=\'var(--color-white)\'; this.style.borderColor=\'var(--color-primary-dark)\'; const icon = this.querySelector(\'.btn__icon\'); if(icon) icon.style.backgroundColor=\'var(--color-white)\';"';
}

// Add inline hover styles for primary-white-border buttons (not for split buttons)
if ($type === 'primary-white-border' && !$is_primary_split) {
    $attrs .= ' style="transition: background 0.3s ease, color 0.3s ease;" onmouseover="this.style.background=\'transparent\'; this.style.color=\'var(--color-primary-dark)\'; this.style.borderColor=\'var(--color-white)\'; const icon = this.querySelector(\'.btn__icon\'); if(icon) icon.style.backgroundColor=\'var(--color-primary-dark)\';" onmouseout="this.style.background=\'var(--color-primary-dark)\'; this.style.color=\'var(--color-white)\'; this.style.borderColor=\'var(--color-primary-dark)\'; const icon = this.querySelector(\'.btn__icon\'); if(icon) icon.style.backgroundColor=\'var(--color-white)\';"';
}

if (!empty($args['attributes']) && is_array($args['attributes'])) {
    foreach ($args['attributes'] as $attr => $value) {
        $attrs .= ' ' . esc_attr((string) $attr) . '="' . esc_attr((string) $value) . '"';
    }
}

if ($is_primary_split) {
    $split_button_classes = trim($classes . ' btn--icon-only');

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
            <span class="<?php echo esc_attr($split_button_classes); ?>" aria-hidden="true">
                <?php if ($icon_url): ?>
                    <?php if ($use_img_icon): ?>
                        <span class="btn__icon btn__icon--img">
                            <img class="btn__icon-image" src="<?php echo esc_url($icon_url); ?>" alt="" aria-hidden="true">
                        </span>
                    <?php else: ?>
                        <span class="btn__icon" style="-webkit-mask-image: url('<?php echo esc_url($icon_url); ?>'); mask-image: url('<?php echo esc_url($icon_url); ?>');"></span>
                    <?php endif; ?>
                <?php endif; ?>
            </span>
        </a>
    <?php else: ?>
        <div class="<?php echo esc_attr($btn_split_class); ?>">
            <span class="btn-split__text"><?php echo esc_html($text); ?></span>
            <<?php echo $tag; ?> class="<?php echo esc_attr($split_button_classes); ?>" <?php echo $attrs; ?>>
                <?php if ($icon_url): ?>
                    <?php if ($use_img_icon): ?>
                        <span class="btn__icon btn__icon--img">
                            <img class="btn__icon-image" src="<?php echo esc_url($icon_url); ?>" alt="" aria-hidden="true">
                        </span>
                    <?php else: ?>
                        <span class="btn__icon" style="-webkit-mask-image: url('<?php echo esc_url($icon_url); ?>'); mask-image: url('<?php echo esc_url($icon_url); ?>');"></span>
                    <?php endif; ?>
                <?php endif; ?>
            </<?php echo $tag; ?>>
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
                <img class="btn__icon-image" src="<?php echo esc_url($icon_url); ?>" alt="" aria-hidden="true">
            </span>
        <?php else: ?>
            <span class="btn__icon" style="-webkit-mask-image: url('<?php echo esc_url($icon_url); ?>'); mask-image: url('<?php echo esc_url($icon_url); ?>');"></span>
        <?php endif; ?>

        <?php if ($type === 'readmore-v1'): ?>
            </span>
        <?php endif; ?>
    <?php endif; ?>
</<?php echo $tag; ?>>
