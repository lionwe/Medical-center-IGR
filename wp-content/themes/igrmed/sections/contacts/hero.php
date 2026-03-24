<?php
$contacts_hero_img = get_field('contacts_hero_img');

$hero_bg_url = '';
if (is_array($contacts_hero_img)) {
    $hero_bg_url = (string) ($contacts_hero_img['url'] ?? '');
} elseif (is_numeric($contacts_hero_img)) {
    $hero_bg_url = (string) wp_get_attachment_image_url((int) $contacts_hero_img, 'full');
} elseif (is_string($contacts_hero_img) && $contacts_hero_img !== '') {
    $hero_bg_url = $contacts_hero_img;
} else {
    // Default background fallback
    $hero_bg_desktop = get_template_directory_uri() . '/assets/img/bread-crumbs.webp';
    $hero_bg_mobile = get_template_directory_uri() . '/assets/img/bread-crumbs-mobile.webp';
}

// If user uploaded a custom image in ACF, use it for both for simplicity, or define variables
// But Contacts hero might not even have ACF image set, so we rely on fallback
$bg_d = isset($hero_bg_desktop) ? $hero_bg_desktop : $hero_bg_url;
$bg_m = isset($hero_bg_mobile) ? $hero_bg_mobile : $hero_bg_url;

$hero_style = ' style="--bg-desktop: url(' . esc_url($bg_d) . '); --bg-mobile: url(' . esc_url($bg_m) . ');"';
?>

<section id="contacts-hero" class="contacts-hero" <?php echo $hero_style; ?>>
    <div class="container">

    </div>
</section>