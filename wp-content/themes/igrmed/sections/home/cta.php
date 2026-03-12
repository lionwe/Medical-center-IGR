<?php

$form_shortcode = (string) get_field('form_shortcode', 'option');

$resolve_image_url = static function ($value): string {
    if (is_array($value)) {
        return (string) ($value['url'] ?? '');
    }

    return (string) $value;
};

$cta_bg_url = $resolve_image_url(get_field('cta_bg', getHomePageID()));
$cta_bg_mobile_url = $resolve_image_url(get_field('cta_bg_mobile', getHomePageID()));

if ($cta_bg_mobile_url === '') {
    $cta_bg_mobile_url = $resolve_image_url(get_field('cta_bg_moibile', getHomePageID()));
}

$style_parts = [];

if ($cta_bg_url !== '') {
    $style_parts[] = '--cta-bg:url(' . esc_url($cta_bg_url) . ')';
}

if ($cta_bg_mobile_url !== '') {
    $style_parts[] = '--cta-bg-mobile:url(' . esc_url($cta_bg_mobile_url) . ')';
}

$style = '';

if (!empty($style_parts)) {
    $style = 'style="' . esc_attr(implode(';', $style_parts)) . '"';
}

?>

<section id="cta" class="cta">

    <div class="container cta__bg" <?php echo $style; ?>>

        <div class="cta__wrapper">

            <div class="cta__window">

                <?php if ($form_shortcode): ?>

                    <?php echo do_shortcode($form_shortcode); ?>

                <?php endif; ?>

            </div>

        </div>

    </div>

</section>