<?php
/**
 * Button Component
 *
 * Usage:
 * get_template_part('templates/button', null, [
 *   'text'      => 'Детальніше',
 *   'link'      => '#',
 *   'type'      => 'primary', // primary, secondary, social, glass
 *   'icon_name' => 'arrow',   // ACF field name without 'icon_' prefix
 *   'icon_url'  => '',        // direct URL override
 *   'target'    => '_self'
 * ]);
 */

$text        = $args['text'] ?? '';
$href        = $args['link'] ?? '#';
$type        = $args['type'] ?? 'primary';
$icon_name   = $args['icon_name'] ?? null;
$target      = $args['target'] ?? '_self';
$class_extra = $args['class'] ?? '';
$icon_url    = $args['icon_url'] ?? null;

// Get icon from ACF options unless direct icon_url was provided
if (!$icon_url && $icon_name) {
    $field_name = 'icon_' . $icon_name;
    $icon_val   = get_field($field_name, 'option');

    if (is_array($icon_val)) {
        $icon_url = $icon_val['url'] ?? null;
    } elseif (is_numeric($icon_val)) {
        $icon_url = wp_get_attachment_url((int) $icon_val);
    } else {
        $icon_url = $icon_val;
    }
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

<<?php echo $tag; ?> class="<?php echo esc_attr($classes); ?>" <?php echo $attrs; ?>>
    <?php if ($text && $type !== 'social' && $type !== 'glass') : ?>
        <span class="btn__text"><?php echo esc_html($text); ?></span>
    <?php endif; ?>

    <?php if ($icon_url) : ?>
        <span class="btn__icon" style="--_icon-mask: url('<?php echo esc_url($icon_url); ?>');"></span>
    <?php endif; ?>
</<?php echo $tag; ?>>
