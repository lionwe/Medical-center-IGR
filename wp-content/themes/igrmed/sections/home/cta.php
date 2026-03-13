<?php

$form_shortcode = (string) get_field('form_shortcode', 'option');
$home_page_id = getHomePageID();

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

$cta_bg_url = $resolve_image_url(get_field('cta_bg', $home_page_id));
$cta_bg_mobile_url = $resolve_image_url(get_field('cta_bg_mobile', $home_page_id));

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
                <?php
                get_picture([
                    'src' => $cta_bg_url,
                    'alt' => '',
                    'class' => 'cta__bg-image cta__bg-image--desktop',
                ]);
                ?>
            <?php endif; ?>

            <?php if ($cta_bg_mobile_url !== ''): ?>
                <?php
                get_picture([
                    'src' => $cta_bg_mobile_url,
                    'alt' => '',
                    'class' => 'cta__bg-image cta__bg-image--mobile',
                ]);
                ?>
            <?php endif; ?>

            <div class="cta__window">

                <?php if ($form_shortcode): ?>

                    <?php echo do_shortcode($form_shortcode); ?>

                <?php endif; ?>

            </div>

        </div>

    </div>

</section>