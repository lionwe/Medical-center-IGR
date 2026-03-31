<?php
?>
<footer class="footer footer--simple">
    <div class="container footer__container">
        <div class="footer__bottom">
            <div class="footer__bottom-row">
                <!-- Меню -->
                <div class="footer__col footer__col--menu">
                    <?php
                    wp_nav_menu([
                        'theme_location' => 'menu-footer',
                        'container' => 'nav',
                        'menu_class' => 'footer__menu-list',
                        'fallback_cb' => false,
                        'depth' => 1,
                    ]);
                    ?>
                </div>

                <!-- Графік роботи -->
                <div class="footer__col footer__col--schedule">
                    <div class="footer__schedule-text footer__text">
                        <?php echo wp_kses_post(get_field('schedule_2', 'option')); ?>
                    </div>
                </div>

                <!-- Контакти -->
                <div class="footer__col footer__col--contacts">
                    <?php $footer_contacts_title = get_field('footer_contacts_title', 'option'); ?>
                    <?php if ($footer_contacts_title): ?>
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

                <!-- Соціальні мережі -->
                <div class="footer__col footer__col--socials">
                    <?php $footer_socials_title = get_field('footer_socials_title', 'option'); ?>
                    <?php if ($footer_socials_title): ?>
                        <h3 class="footer__col-title"><?php igrmed_e('footer_social_title'); ?></h3>
                    <?php endif; ?>
                    <div class="footer__socials footer__text">
                        <?php if ($link_instagram = get_field('link_instagram', 'option')): ?>
                            <a href="<?php echo esc_url($link_instagram); ?>" target="_blank"
                                rel="noopener noreferrer">Instagram</a>
                        <?php endif; ?>

                        <?php if ($link_facebook = get_field('link_facebook', 'option')): ?>
                            <a href="<?php echo esc_url($link_facebook); ?>" target="_blank"
                                rel="noopener noreferrer">Facebook</a>
                        <?php endif; ?>

                        <?php if ($link_tiktok = get_field('link_tiktok', 'option')): ?>
                            <a href="<?php echo esc_url($link_tiktok); ?>" target="_blank"
                                rel="noopener noreferrer">TikTok</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Головна назва та логотип -->
            <?php
            $footer_main_title = get_field('footer_main_title', 'option');
            $footer_logo = get_field('footer_logo', 'option');
            ?>
            <?php if ($footer_main_title || $footer_logo): ?>
                <div class="footer__bottom-title-row">
                    <?php if ($footer_logo): ?>
                        <img src="<?php echo esc_url($footer_logo['url']); ?>" alt="" class="footer__logo-bg" width="553"
                            height="308" aria-hidden="true" loading="lazy">
                    <?php endif; ?>

                    <?php if ($footer_main_title): ?>
                        <div class="footer__main-title"><?php igrmed_e('footer_about_title'); ?></div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <!-- Копірайт та інше -->
            <?php
            $footer_copyright = get_field('footer_copyright', 'option');
            $privacy_policy = get_field('privacy_policy_page', 'option');
            $developer_credit = get_field('developer_credit', 'option');
            $developer_link = get_field('developer_link', 'option');
            ?>
            <?php if ($footer_copyright || $privacy_policy || $developer_credit): ?>
                <div class="footer__copyright-row">
                    <div class="footer__copyright-text">
                        <?php echo wp_kses_post($footer_copyright); ?>
                    </div>

                    <div class="footer__copyright-privacy">
                        <?php if ($privacy_policy): ?>
                            <a
                                href="<?php echo esc_url($privacy_policy); ?>"><?php igrmed_e('footer_privacy_policy'); ?></a>
                        <?php endif; ?>
                    </div>

                    <div class="footer__copyright-dev">
                        <?php if ($developer_credit): ?>
                            Made by
                            <?php if ($developer_link): ?>
                                <a href="<?php echo esc_url($developer_link); ?>" target="_blank"
                                    rel="noopener noreferrer"><?php echo esc_html($developer_credit); ?></a>
                            <?php else: ?>
                                <span class="footer__developer-name"><?php echo esc_html($developer_credit); ?></span>
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