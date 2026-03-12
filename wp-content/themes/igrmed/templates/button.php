<?php

/**
 * Button Component
 *
 * Usage:
 * get_template_part('templates/button', null, [
 *   'text'      => 'Детальніше',
 *   'link'      => '#',
 *   'type'      => 'primary', // primary, primary-dark, social, carousel, carousel-glass
 *   'icon_name'   => 'arrow',   // ACF field name without 'icon_' prefix
 *   'icon_url'    => '',        // direct URL override
 *   'target'      => '_self'
 * ]);
 */

$text        = $args['text']      ?? '';
$href        = $args['link']      ?? '#';
$type        = $args['type']      ?? 'primary';
$icon_name   = $args['icon_name'] ?? null;
$icon_url    = $args['icon_url']  ?? null;
$target      = $args['target']    ?? '_self';
$class_extra = $args['class']     ?? '';
$use_img_icon = !empty($args['icon_as_img']) && $type !== 'social';
$is_primary_split = !empty($args['primary_split']) && $type === 'primary' && $text !== '';
$carousel_group = $args['carousel_group'] ?? null;

// Get icon from ACF options or direct URL
if (!$icon_url && $icon_name) {
    $field_name = 'icon_' . $icon_name;
    $icon_val   = get_field($field_name, 'option');

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

// Build CSS classes
$classes = 'btn btn--' . $type;
if (!empty($class_extra)) {
    $classes .= ' ' . $class_extra;
}
if (!$icon_url) {
    $classes .= ' btn--no-icon';
}

// Determine tag and attributes
$tag   = ($type === 'button' || $type === 'submit') ? 'button' : 'a';
$attrs = ($tag === 'a') ? 'href="' . esc_url($href) . '" target="' . esc_attr($target) . '"' : 'type="button"';

// Add custom attributes
if (!empty($args['attributes']) && is_array($args['attributes'])) {
    foreach ($args['attributes'] as $attr => $value) {
        $attrs .= ' ' . esc_attr($attr) . '="' . esc_attr($value) . '"';
    }
}

?>

<?php if ($is_primary_split): ?>
    <?php
    $split_button_classes = $classes . ' btn--icon-only';
    ?>
    <div class="btn-split btn-split--primary">
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
    <?php return; ?>
<?php endif; ?>

<<?php echo $tag; ?> class="<?php echo esc_attr($classes); ?>" <?php echo $attrs; ?>>

    <?php if ($text && $type !== 'social'): ?>
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