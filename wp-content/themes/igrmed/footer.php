<?php
$footer_copyright = (string) get_field('footer_copyright', 'option');
$footer_privacy_url = (string) get_field('privacy_policy_page', 'option');
$footer_developer = (string) get_field('developer_credit', 'option');

$footer_social_items = [];
$footer_social_fields = [
    'instagram' => ['link' => 'link_instagram', 'icon' => 'icon_instagram', 'label' => 'Instagram'],
    'facebook' => ['link' => 'link_facebook', 'icon' => 'icon_facebook', 'label' => 'Facebook'],
    'tiktok' => ['link' => 'link_tiktok', 'icon' => 'icon_tiktok', 'label' => 'TikTok'],
    'telegram' => ['link' => 'link_telegram', 'icon' => 'icon_telegram', 'label' => 'Telegram'],
];
foreach ($footer_social_fields as $config) {
    $icon_value = get_field($config['icon'], 'option');
    $icon_url = is_array($icon_value) && !empty($icon_value['url']) ? $icon_value['url'] : (is_numeric($icon_value) ? wp_get_attachment_url((int) $icon_value) : ($icon_value ?: ''));
    $link = (string) get_field($config['link'], 'option');

    if ($link === '' && $icon_url === '') {
        continue;
    }

    $footer_social_items[] = [
        'link' => $link !== '' ? $link : '#',
        'icon_url' => $icon_url,
        'label' => $config['label'],
    ];
}
?>
<footer class="footer">
    <div class="container">
        <?php if ($footer_copyright !== '' || $footer_privacy_url !== '' || $footer_developer !== '' || !empty($footer_social_items)): ?>
            <div class="footer__inner">
                <?php if ($footer_copyright !== ''): ?>
                    <div class="footer__copyright"><?php echo wp_kses_post(nl2br($footer_copyright)); ?></div>
                <?php endif; ?>
                <?php if ($footer_privacy_url !== ''): ?>
                    <a class="footer__privacy" href="<?php echo esc_url($footer_privacy_url); ?>"><?php esc_html_e('Політика конфіденційності', 'igr-theme'); ?></a>
                <?php endif; ?>
                <?php if ($footer_developer !== ''): ?>
                    <div class="footer__developer"><?php echo esc_html($footer_developer); ?></div>
                <?php endif; ?>
                <?php if (!empty($footer_social_items)): ?>
                    <ul class="footer__socials" aria-label="<?php esc_attr_e('Social links', 'igr-theme'); ?>">
                        <?php foreach ($footer_social_items as $item): ?>
                            <li class="footer__socials-item">
                                <?php
                                get_template_part('templates/button', null, [
                                    'link' => $item['link'],
                                    'type' => 'social',
                                    'class' => 'footer__socials-link',
                                    'icon_url' => $item['icon_url'] !== '' ? $item['icon_url'] : null,
                                    'target' => '_blank',
                                    'attributes' => [
                                        'rel' => 'noopener noreferrer',
                                        'aria-label' => $item['label'],
                                    ],
                                ]);
                                ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</footer>
<?php wp_footer(); ?>

</body>

</html>