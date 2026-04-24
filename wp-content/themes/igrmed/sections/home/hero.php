<?php
$hero_bg = get_field('home_hero_bg');
$hero_title = trim((string) get_field('home_hero_title'));
$hero_text = trim((string) get_field('home_hero_text'));
$hero_button = get_field('home_hero_button');
$hero_cards = get_field('hero_cards');

$hero_button_url = '';
$hero_button_label = '';
$hero_button_target = '_self';

if (is_array($hero_button)) {
    $hero_button_url = $hero_button['url'] ?? '';
$hero_button_label = igrmed__('btn_details_about_us');
    $hero_button_target = $hero_button['target'] ?? '_self';
}

$promo_title = '';
$promo_badge = '';
$promo_subtitle = '';
$promo_text = '';
$promo_link = '';

$article_post = null;
$article_read_time = '';
$article_title = '';
$article_url = '';

$socials_title = '';
$schedule_title = '';
$schedule_text = '';

if (is_array($hero_cards)) {
    $promo_title = (string) ($hero_cards['promo_title'] ?? '');
    $promo_badge = (string) ($hero_cards['promo_badge'] ?? '');
    $promo_subtitle = (string) ($hero_cards['promo_subtitle'] ?? '');
    $promo_text = (string) ($hero_cards['promo_text'] ?? '');
    $promo_link = (string) ($hero_cards['promo_link'] ?? '');

    $article_post = $hero_cards['article_post'] ?? null;
    $article_read_time = (string) ($hero_cards['article_read_time'] ?? '');

    $socials_title = (string) ($hero_cards['socials_title'] ?? '');
    $schedule_title = (string) ($hero_cards['schedule_title'] ?? '');
}

$schedule_text = '';
if (function_exists('get_field')) {
    $schedule_text = (string) get_field('schedule_2', 'option');
}

if ($article_post instanceof WP_Post) {
    $article_title = get_the_title($article_post);
    $article_url = get_permalink($article_post);
    $article_content = get_post_field('post_content', $article_post);
    $article_read_time = function_exists('reading_time') && $article_content !== ''
        ? (string) reading_time($article_content)
        : (string) ($hero_cards['article_read_time'] ?? '');
}

// Empty Fields Rule: do not render hero without title.
if ($hero_title === '') {
    return;
}

$social_items = [];
$social_fields = [
    'instagram' => ['link' => 'link_instagram', 'icon' => 'icon_instagram', 'label' => 'Instagram'],
    'facebook' => ['link' => 'link_facebook', 'icon' => 'icon_facebook', 'label' => 'Facebook'],
    'tiktok' => ['link' => 'link_tiktok', 'icon' => 'icon_tiktok', 'label' => 'TikTok'],
    'telegram' => ['link' => 'link_telegram', 'icon' => 'icon_telegram', 'label' => 'Telegram'],
];

foreach ($social_fields as $social => $config) {
    $icon_value = get_field($config['icon'], 'option');
    $icon_url = is_array($icon_value) && !empty($icon_value['url']) ? $icon_value['url'] : (is_numeric($icon_value) ? wp_get_attachment_url((int) $icon_value) : ($icon_value ?: ''));
    $link = (string) get_field($config['link'], 'option');

    if ($link === '' && $icon_url === '') {
        continue;
    }

    $social_items[] = [
        'link' => $link !== '' ? $link : '#',
        'icon_url' => $icon_url,
        'label' => $config['label'],
    ];
}

$hero_bg_url = '';
$hero_bg_alt = '';
if (is_string($hero_bg) && $hero_bg !== '') {
    $hero_bg_url = $hero_bg;
} elseif (is_array($hero_bg) && !empty($hero_bg['url'])) {
    $hero_bg_url = (string) $hero_bg['url'];
    $hero_bg_alt = $hero_bg['alt'] ?? '';
}

$icon_clock_2_val = get_field('icon_clock_2', 'option');
$icon_calendar_val = get_field('icon_calendar', 'option');
$hero_icon_clock_url = is_array($icon_clock_2_val) && !empty($icon_clock_2_val['url']) ? $icon_clock_2_val['url'] : (is_numeric($icon_clock_2_val) ? wp_get_attachment_url((int) $icon_clock_2_val) : ($icon_clock_2_val ?: ''));
$hero_icon_calendar_url = is_array($icon_calendar_val) && !empty($icon_calendar_val['url']) ? $icon_calendar_val['url'] : (is_numeric($icon_calendar_val) ? wp_get_attachment_url((int) $icon_calendar_val) : ($icon_calendar_val ?: ''));
$hero_icon_clock_alt = is_array($icon_clock_2_val) ? $icon_clock_2_val['alt'] : '';
$hero_icon_calendar_alt = is_array($icon_calendar_val) ? $icon_calendar_val['alt'] : '';
if (is_numeric($icon_clock_2_val)) {
    $hero_icon_clock_alt = get_post_meta((int) $icon_clock_2_val, '_wp_attachment_image_alt', true);
}
if (is_numeric($icon_calendar_val)) {
    $hero_icon_calendar_alt = get_post_meta((int) $icon_calendar_val, '_wp_attachment_image_alt', true);
}

?>

<section id="hero" class="hero">
    <?php if ($hero_bg_url !== ''): ?>
        <div class="hero__bg" aria-hidden="true">
            <?php
            get_picture([
                'src' => $hero_bg_url,
                'alt' => $hero_bg_alt,
                'class' => 'hero__bg-image',
                'lazy' => false,
            ]);
            ?>
        </div>
    <?php endif; ?>

    <div class="container">
        <div class="hero__content">
            <?php if ($hero_title !== '' || ($hero_button_url !== '' && $hero_button_label !== '')): ?>
                <div class="hero__top">
                    <h1 class="hero__title"><?php echo wp_kses_post($hero_title); ?></h1>
                    <?php if ($hero_button_url !== '' && $hero_button_label !== ''): ?>
                        <?php
                        get_template_part('templates/button', null, [
                            'text' => $hero_button_label,
                            'link' => $hero_button_url,
                            'type' => 'primary-soft-hover',
                            'primary_split' => true,
                            'icon_url' => get_template_directory_uri() . '/assets/img/svg/contact-arrow.svg',
                            'target' => $hero_button_target,
                        ]);
                        ?>
                    <?php endif; ?>
                </div>




            <?php endif; ?>

            <?php if ($promo_title !== '' || $article_title !== '' || $socials_title !== '' || $schedule_title !== '' || $schedule_text !== ''): ?>
                <div class="hero__middle hero__cards-swiper js-hero-swiper">
                    <div class="hero__cards-row swiper-wrapper">
                        <article class="hero__card hero__card--promo swiper-slide">
                            <div class="hero__card-header">
                                <?php if ($promo_title !== ''): ?>
                                    <h3 class="hero__card-title hero__card-title--promo">
                                        <?php echo wp_kses_post($promo_title); ?>
                                    </h3>
                                <?php endif; ?>

                                <?php if ($promo_badge !== ''): ?>
                                    <p class="hero__card-badge"><?php echo wp_kses_post($promo_badge); ?></p>
                                <?php endif; ?>
                            </div>

                            <div class="hero__card-content">
                                <div class="hero__card-body">
                                    <?php if ($promo_subtitle !== ''): ?>
                                        <p class="hero__card-subtitle"><?php echo wp_kses_post($promo_subtitle); ?></p>
                                    <?php endif; ?>

                                    <?php if ($promo_text !== ''): ?>
                                        <div class="hero__card-text"><?php echo wp_kses_post($promo_text); ?></div>
                                    <?php endif; ?>
                                </div>

                                <div class="hero__card-actions">
                                    <?php
                                    $hero_cta_href = $promo_link !== '' ? $promo_link : '#';
                                    $hero_cta_icon = get_template_directory_uri() . '/assets/img/svg/contact-arrow.svg';
                                    ?>
                                    <a class="btn btn--primary btn--icon-only btn--hero-card-cta"
                                        href="<?php echo esc_url($hero_cta_href); ?>"
                                        target="_self"
                                        aria-label="<?php echo esc_attr__('Детальніше', 'igr-theme'); ?>">
                                        <span class="btn__icon" style="-webkit-mask-image:url('<?php echo esc_url($hero_cta_icon); ?>');mask-image:url('<?php echo esc_url($hero_cta_icon); ?>');"></span>
                                    </a>
                                </div>
                            </div>
                        </article>

                        <article class="hero__card hero__card--article swiper-slide">
                            <?php if ($article_post instanceof WP_Post && ($article_read_time !== '' || get_the_date('', $article_post) !== '')): ?>
                                <div class="hero__card-meta-wrap">
                                    <div class="hero__card-meta-row">
                                            <?php $article_date = get_the_date('j F, Y', $article_post); ?>
                                            <?php if ($article_date !== ''): ?>
                                                <span class="hero__card-meta hero__card-meta--date">
                                                    <span class="hero__card-meta-icon hero__card-meta-icon--calendar"
                                                        aria-hidden="true">
                                                        <?php if ($hero_icon_calendar_url !== ''): ?>
                                                            <?php
                                                            get_picture([
                                                                'src' => $hero_icon_calendar_url,
                                                                'alt' => $hero_icon_calendar_alt,
                                                                'class' => '',
                                                                'lazy' => false,
                                                            ]);
                                                            ?>
                                                        <?php else: ?>
                                                            <svg width="19" height="15" viewBox="0 0 19 15" fill="none"
                                                                xmlns="http://www.w3.org/2000/svg">
                                                                <path
                                                                    d="M1 5.5H18M4 1V3M15 1V3M1.5 1H17.5C18.0523 1 18.5 1.44772 18.5 2V13C18.5 13.5523 18.0523 14 17.5 14H1.5C0.947716 14 0.5 13.5523 0.5 13V2C0.5 1.44772 0.947716 1 1.5 1Z"
                                                                    stroke="currentColor" stroke-width="1" stroke-linecap="round"
                                                                    stroke-linejoin="round" />
                                                            </svg>
                                                        <?php endif; ?>
                                                    </span>
                                                    <?php echo esc_html($article_date); ?>
                                                </span>
                                            <?php endif; ?>
                                            <?php if ($article_date !== '' && $article_read_time !== ''): ?>
                                                <span class="hero__card-meta-sep" aria-hidden="true"></span>
                                            <?php endif; ?>
                                            <?php if ($article_read_time !== ''): ?>
                                                <?php $read_time_display = is_numeric($article_read_time) ? $article_read_time . ' ' . __('хв', 'igr-theme') . ' ' . __('на прочитання', 'igr-theme') : $article_read_time; ?>
                                                <span class="hero__card-meta hero__card-meta--read-time">
                                                    <span class="hero__card-meta-icon hero__card-meta-icon--clock" aria-hidden="true">
                                                        <?php if ($hero_icon_clock_url !== ''): ?>
                                                            <?php
                                                            get_picture([
                                                                'src' => $hero_icon_clock_url,
                                                                'alt' => $hero_icon_clock_alt,
                                                                'class' => '',
                                                                'lazy' => false,
                                                            ]);
                                                            ?>
                                                        <?php else: ?>
                                                            <svg width="22" height="22" viewBox="0 0 22 22" fill="none"
                                                                xmlns="http://www.w3.org/2000/svg">
                                                                <circle cx="11" cy="11" r="9.5" stroke="currentColor" stroke-width="1" />
                                                                <path d="M11 6V11L15 13" stroke="currentColor" stroke-width="1"
                                                                    stroke-linecap="round" />
                                                            </svg>
                                                        <?php endif; ?>
                                                    </span>
                                                    <?php echo esc_html($read_time_display); ?>
                                                </span>
                                            <?php endif; ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <div class="hero__card-content">
                                <div class="hero__card-body">
                                    <?php if ($article_title !== ''): ?>
                                        <h4 class="hero__card-title hero__card-title--article">
                                            <?php echo wp_kses_post($article_title); ?>
                                        </h4>
                                    <?php endif; ?>

                                    <?php if ($article_post instanceof WP_Post): ?>
                                        <?php $article_excerpt = get_the_excerpt($article_post); ?>
                                        <?php if ($article_excerpt !== ''): ?>
                                            <p class="hero__card-excerpt"><?php echo wp_kses_post($article_excerpt); ?></p>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>

                                <?php if ($article_url !== ''): ?>
                                    <div class="hero__card-actions">
                                        <?php $hero_cta_icon = get_template_directory_uri() . '/assets/img/svg/contact-arrow.svg'; ?>
                                        <a class="btn btn--primary btn--icon-only btn--hero-card-cta"
                                            href="<?php echo esc_url($article_url); ?>"
                                            target="_self"
                                            aria-label="<?php echo esc_attr__('Детальніше', 'igr-theme'); ?>">
                                            <span class="btn__icon" style="-webkit-mask-image:url('<?php echo esc_url($hero_cta_icon); ?>');mask-image:url('<?php echo esc_url($hero_cta_icon); ?>');"></span>
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </article>

                        <article class="hero__card hero__card--info swiper-slide">
                            <?php if ($socials_title !== ''): ?>
                                <h4 class="hero__card-title hero__card-title--info">
                                    <?php echo wp_kses_post($socials_title); ?>
                                </h4>
                            <?php endif; ?>

                            <div class="hero__card-content">
                                <?php if (!empty($social_items)): ?>
                                    <ul class="hero__socials" aria-label="<?php esc_attr_e('Social links', 'igr-theme'); ?>">
                                        <?php foreach ($social_items as $social_item): ?>
                                            <li class="hero__socials-item">
                                                <span class="hero__socials-label"><?php echo esc_html($social_item['label']); ?></span>
                                                <?php
                                                get_template_part('templates/button', null, [
                                                    'link' => $social_item['link'],
                                                    'type' => 'social',
                                                    'class' => 'hero__socials-link',
                                                    'text' => $social_item['icon_url'] === '' ? $social_item['label'] : '',
                                                    'icon_url' => $social_item['icon_url'] !== '' ? $social_item['icon_url'] : null,
                                                    'target' => '_blank',
                                                    'attributes' => ['rel' => 'noopener noreferrer'],
                                                ]);
                                                ?>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>

                                <?php if ($schedule_text !== ''): ?>
                                    <div class="hero__schedule">
                                        <?php echo wp_kses_post($schedule_text); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </article>
                    </div>
                    <div class="hero__cards-pagination swiper-pagination" aria-hidden="true"></div>
                </div>
            <?php endif; ?>

            <?php if ($hero_text !== ''): ?>
                <div class="hero__bottom">
                    <p class="hero__bottom-text"><?php echo wp_kses_post(nl2br($hero_text)); ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>