<?php
// Fields from Options page
$contacts_left_block = (string) get_field('contacts_left_block', 'option');
$contacts_form_block = (string) get_field('contacts_form_block', 'option');
$contacts_form_shortcode = (string) get_field('contacts_form_shortcode', 'option');
$contacts_form_bg = get_field('contacts_form_bg', 'option');

// Icons
$icon_phone = get_field('icon_phone', 'option');
$icon_address = get_field('icon_address', 'option');
$icon_mail = get_field('icon_mail', 'option');
$icon_clock_3 = get_field('icon_clock_3', 'option'); // Restored user's icon logic

// Data
$phones = [];
for ($i = 1; $i <= 5; $i++) {
    if ($phone = get_field('phone_' . $i, 'option')) {
        $phones[] = $phone;
    }
}
$address_main = (string) get_field('address_main', 'option');
$schedule = (string) get_field('contacts_schedule', 'option');

// Автоматичне вирівнювання: забираємо зайві пробіли/nbsp на початку рядків
$schedule = preg_replace('/(<p[^>]*>|<br\s*\/?>)(?:&nbsp;|\s)+/i', '$1', $schedule);
// Обертаємо "Вт-" у спеціальний клас, щоб "Пт" стало на одному рівні з "Пн"
$schedule = str_replace('Вт-Пт', '<span class="schedule-outdent">Вт-</span>Пт', $schedule);
$schedule = str_replace('Вт - Пт', '<span class="schedule-outdent">Вт - </span>Пт', $schedule);

$email = 'demo@gmail.com';

// Form Bg URL
$form_bg_url = '';
if (is_array($contacts_form_bg)) {
    $form_bg_url = (string) ($contacts_form_bg['url'] ?? '');
} elseif (is_numeric($contacts_form_bg)) {
    $form_bg_url = (string) wp_get_attachment_image_url((int) $contacts_form_bg, 'full');
} elseif (is_string($contacts_form_bg) && $contacts_form_bg !== '') {
    $form_bg_url = $contacts_form_bg;
}

$form_bg_style = $form_bg_url !== '' ? ' style="background-image: url(' . esc_url($form_bg_url) . ');"' : '';
?>

<section id="contacts-info" class="contacts-info">
    <div class="container">
        <div class="contacts-info__card">

            <div class="contacts-info__left">
                <?php if ($contacts_left_block): ?>
                    <div class="contacts-info__intro">
                        <?php echo wp_kses_post($contacts_left_block); ?>
                    </div>
                <?php endif; ?>

                <div class="contacts-info__wrapper">
                    <div class="contacts-info__data">
                        <ul class="contacts-info__list">
                            <?php if (!empty($phones) && $icon_phone): ?>
                                <li class="contacts-info__item contacts-info__item--phones">
                                    <div class="contacts-info__icon">
                                        <?php echo wp_get_attachment_image(is_array($icon_phone) ? ($icon_phone['ID'] ?? '') : $icon_phone, 'full'); ?>
                                    </div>
                                    <div class="contacts-info__tels">
                                        <?php foreach ($phones as $phone): ?>
                                            <a href="<?php echo esc_url($phone['url'] ?? ''); ?>" target="<?php echo esc_attr($phone['target'] ?? '_self'); ?>">
                                                <?php echo esc_html($phone['title'] ?? ''); ?>
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                                </li>
                            <?php endif; ?>

                            <?php if ($address_main && $icon_address): ?>
                                <li class="contacts-info__item">
                                    <div class="contacts-info__icon">
                                        <?php echo wp_get_attachment_image(is_array($icon_address) ? ($icon_address['ID'] ?? '') : $icon_address, 'full'); ?>
                                    </div>
                                    <span><?php echo esc_html($address_main); ?></span>
                                </li>
                            <?php endif; ?>

                            <?php if ($email && $icon_mail): ?>
                                <li class="contacts-info__item">
                                    <div class="contacts-info__icon">
                                        <?php echo wp_get_attachment_image(is_array($icon_mail) ? ($icon_mail['ID'] ?? '') : $icon_mail, 'full'); ?>
                                    </div>
                                    <a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>

                    <?php if ($schedule && $icon_clock_3): ?>
                        <div class="contacts-info__schedule">
                            <div class="contacts-info__schedule-icon">
                                <?php echo wp_get_attachment_image(is_array($icon_clock_3) ? ($icon_clock_3['ID'] ?? '') : $icon_clock_3, 'full'); ?>
                            </div>
                            <div class="contacts-info__schedule-text">
                                <?php echo wp_kses_post($schedule); ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="contacts-info__right" <?php echo $form_bg_style; ?>>
                <?php if ($contacts_form_block): ?>
                    <div class="contacts-info__form-intro">
                        <?php echo wp_kses_post($contacts_form_block); ?>
                    </div>
                <?php endif; ?>

                <?php if ($contacts_form_shortcode): ?>
                    <div class="contacts-info__form">
                        <?php echo do_shortcode($contacts_form_shortcode); ?>
                    </div>
                <?php endif; ?>
            </div>

        </div>

        <?php if ($address_main): ?>
            <div class="contacts-info__map-wrapper">
                <iframe
                    src="https://maps.google.com/maps?q=<?php echo urlencode(strip_tags($address_main)); ?>&t=&z=14&ie=UTF8&iwloc=&output=embed"
                    allowfullscreen=""
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"
                    class="contacts-info__map">
                </iframe>
            </div>
        <?php endif; ?>
    </div>
</section>