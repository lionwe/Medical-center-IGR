<?php

$current_lang = function_exists('pll_current_language') ? pll_current_language() : 'uk';

$form_shortcode_field = ($current_lang === 'en') ? 'cta_form_en' : 'cta_form';
$form_shortcode = (string) get_field($form_shortcode_field, 'option');
$home_page_id = getHomePageID();

if (trim($form_shortcode) === '') {
    return;
}

$resolve_image_url = static function ($value): string {
    if (is_numeric($value)) {
        $url = wp_get_attachment_image_url((int) $value, 'full');
        return $url ? (string) $url : '';
    }

    if (is_array($value)) {
        if (!empty($value['url'])) {
            return (string) $value['url'];
        }
        if (!empty($value['ID'])) {
            $url = wp_get_attachment_image_url((int) $value['ID'], 'full');
            return $url ? (string) $url : '';
        }
    }

    return is_string($value) ? $value : '';
};

$cta_bg_image        = get_field('cta_bg', $home_page_id);
$cta_bg_mobile_image = get_field('cta_bg_mobile', $home_page_id);

$cta_bg_url        = $resolve_image_url($cta_bg_image);
$cta_bg_mobile_url = $resolve_image_url($cta_bg_mobile_image);

$cta_bg_alt        = is_array($cta_bg_image) ? $cta_bg_image['alt'] : '';
$cta_bg_mobile_alt = is_array($cta_bg_mobile_image) ? $cta_bg_mobile_image['alt'] : '';

if ($cta_bg_mobile_url === '') {
    $cta_bg_mobile_url = $resolve_image_url(get_field('cta_bg_moibile', $home_page_id));
}

if ($cta_bg_mobile_url === '') {
    $cta_bg_mobile_url = $cta_bg_url;
}

?>

<section id="cta" class="cta">
    <div class="container">
        <div class="cta__wrapper">
            <?php if ($cta_bg_url !== ''): ?>
                <?php get_picture([
                    'src'   => $cta_bg_url,
                    'alt'   => $cta_bg_alt,
                    'class' => 'cta__bg-image cta__bg-image--desktop',
                ]); ?>
            <?php endif; ?>

            <?php if ($cta_bg_mobile_url !== ''): ?>
                <?php get_picture([
                    'src'   => $cta_bg_mobile_url,
                    'alt'   => $cta_bg_mobile_alt,
                    'class' => 'cta__bg-image cta__bg-image--mobile',
                ]); ?>
            <?php endif; ?>

            <div class="cta__window">
                <?php echo do_shortcode($form_shortcode); ?>
            </div>
        </div>
    </div>
</section>