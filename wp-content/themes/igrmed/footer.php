<?php
$footer_contacts       = get_field('footer_contacts', 'option');
$footer_phones_title   = (string) get_field('footer_phones_title', 'option');
$icon_phone            = get_field('icon_phone', 'option');
$phone_1               = get_field('phone_1', 'option');
$phone_2               = get_field('phone_2', 'option');
$phone_3               = get_field('phone_3', 'option');
$phone_4               = get_field('phone_4', 'option');

$footer_form_title     = (string) get_field('footer_form_title', 'option');
$footer_form_shortcode = (string) get_field('footer_form_shortcode', 'option');

// Map
$address_text = wp_strip_all_tags(str_replace(['<br>', '<br/>', '<br />'], ' ', $footer_contacts ?: ''));
$map_src      = 'https://maps.google.com/maps?q=' . urlencode($address_text) . '&t=m&z=14&output=embed&iwloc=near';

// Icon
$icon_phone_url = '';
if (is_array($icon_phone) && !empty($icon_phone['url'])) {
    $icon_phone_url = $icon_phone['url'];
} elseif (is_numeric($icon_phone)) {
    $icon_phone_url = wp_get_attachment_url((int) $icon_phone);
} elseif (is_string($icon_phone)) {
    $icon_phone_url = $icon_phone;
}
?>

<footer class="footer">
    <div class="footer__bg" aria-hidden="true">
        <?php get_picture([
            'src' => get_template_directory_uri() . '/assets/img/svg/footer-bg.svg',
            'alt' => '',
            'class' => '',
            'lazy' => true,
        ]); ?>
    </div>

    <div class="container footer__container">
        <div class="footer__top">

            <div class="footer__map" aria-hidden="true">
                <iframe src="<?php echo esc_url($map_src); ?>" width="100%" height="100%" style="border:0;" loading="lazy"></iframe>
            </div>

            <div class="footer__top-container">

                <!-- PHONES -->
                <div class="footer__contacts-block">
                    <?php if ($footer_phones_title): ?>
                        <h3 class="footer__phones-title"><?php igrmed_e('footer_contacts_title'); ?></h3>
                    <?php endif; ?>

                    <?php $phones = [$phone_1, $phone_2, $phone_3, $phone_4]; ?>

                    <div class="footer__phones">
                        <?php foreach ($phones as $phone): ?>
                            <?php if (!empty($phone)): ?>
                                <div class="footer__phone">
                                    <?php if ($icon_phone_url): ?>
                                        <span aria-hidden="true">
                                            <?php get_picture([
                                                'src' => $icon_phone_url,
                                                'alt' => '',
                                                'class' => 'footer__phone-icon',
                                                'lazy' => true,
                                            ]); ?>
                                        </span>
                                    <?php endif; ?>

                                    <a href="<?php echo esc_attr($phone['url'] ?? '#'); ?>">
                                        <?php echo esc_html($phone['title'] ?? ''); ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- FORM -->
                <div class="footer__form-block">
                    <?php if ($footer_form_title): ?>
                        <h2 class="footer__form-title"><?php echo esc_html($footer_form_title); ?></h2>
                    <?php endif; ?>

                    <?php if ($footer_form_shortcode): ?>
                        <div class="footer__form-wrapper">
                            <?php echo do_shortcode($footer_form_shortcode); ?>
                        </div>
                    <?php endif; ?>
                </div>

            </div>
        </div>

        <div class="footer__bottom">
            <div class="footer__bottom-row">

                <!-- MENU -->
                <div class="footer__col footer__col--menu">
                    <?php wp_nav_menu([
                        'theme_location' => 'menu-footer',
                        'container'      => 'nav',
                        'menu_class'     => 'footer__menu-list',
                        'fallback_cb'    => false,
                        'depth'          => 1,
                    ]); ?>
                </div>

                <!-- SCHEDULE -->
                <div class="footer__col footer__col--schedule">
                    <div class="footer__schedule-text footer__text">
                        <?php echo wp_kses_post(get_field('schedule_2', 'option')); ?>
                    </div>
                </div>

                <!-- CONTACTS -->
                <div class="footer__col footer__col--contacts">
                    <?php if (get_field('footer_contacts_title', 'option')): ?>
                        <h3 class="footer__col-title"><?php igrmed_e('footer_contacts_title'); ?></h3>
                    <?php endif; ?>

                    <div class="footer__contacts-text footer__text">
                        <?php
                        $contacts_text = get_field('footer_contacts', 'option');
                        $contacts_text = preg_replace('/(\([^)]+\))/', '<span class="footer__contacts-note">$1</span>', $contacts_text);
                        echo wp_kses_post($contacts_text);
                        ?>
                    </div>
                </div>

                <!-- SOCIALS -->
                <div class="footer__col footer__col--socials">
                    <?php if (get_field('footer_socials_title', 'option')): ?>
                        <h3 class="footer__col-title"><?php igrmed_e('footer_social_title'); ?></h3>
                    <?php endif; ?>

                    <div class="footer__socials footer__text">
                        <?php if ($link = get_field('link_instagram', 'option')): ?>
                            <a href="<?php echo esc_url($link); ?>" target="_blank" rel="noopener noreferrer">Instagram</a>
                        <?php endif; ?>

                        <?php if ($link = get_field('link_facebook', 'option')): ?>
                            <a href="<?php echo esc_url($link); ?>" target="_blank" rel="noopener noreferrer">Facebook</a>
                        <?php endif; ?>

                        <?php if ($link = get_field('link_tiktok', 'option')): ?>
                            <a href="<?php echo esc_url($link); ?>" target="_blank" rel="noopener noreferrer">TikTok</a>
                        <?php endif; ?>
                    </div>
                </div>

            </div>

            <!-- TITLE + LOGO -->
            <?php
            $footer_main_title = get_field('footer_main_title', 'option');
            $footer_logo       = get_field('footer_logo', 'option');
            ?>

            <?php if ($footer_main_title || $footer_logo): ?>
                <div class="footer__bottom-title-row">

                    <?php if ($footer_logo): ?>
                        <?php get_picture([
                            'src' => $footer_logo['url'],
                            'alt' => $footer_logo['alt'],
                            'class' => 'footer__logo-bg',
                            'lazy' => true,
                        ]); ?>
                    <?php endif; ?>

                    <?php if ($footer_main_title): ?>
                        <div class="footer__main-title">
                            <?php echo esc_html($footer_main_title); ?>
                        </div>
                    <?php endif; ?>

                </div>
            <?php endif; ?>

            <!-- COPYRIGHT -->
            <?php
            $footer_copyright = get_field('footer_copyright', 'option');
            $privacy_policy   = get_field('privacy_policy_page', 'option');
            $developer_credit = get_field('developer_credit', 'option');
            $developer_link   = get_field('developer_link', 'option');
            ?>

            <?php if ($footer_copyright || $privacy_policy || $developer_credit): ?>
                <div class="footer__copyright-row">

                    <div class="footer__copyright-text">
                        <?php echo wp_kses_post($footer_copyright); ?>
                    </div>

                    <div class="footer__copyright-privacy">
                        <?php if ($privacy_policy): ?>
                            <a href="<?php echo esc_url($privacy_policy); ?>">
                                <?php igrmed_e('footer_privacy_policy'); ?>
                            </a>
                        <?php endif; ?>
                    </div>

                    <div class="footer__copyright-dev">
                        <?php if ($developer_credit): ?>
                            Made by
                            <?php if ($developer_link): ?>
                                <a href="<?php echo esc_url($developer_link); ?>" target="_blank" rel="noopener noreferrer">
                                    <?php echo esc_html($developer_credit); ?>
                                </a>
                            <?php else: ?>
                                <span><?php echo esc_html($developer_credit); ?></span>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>

                </div>
            <?php endif; ?>

        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>