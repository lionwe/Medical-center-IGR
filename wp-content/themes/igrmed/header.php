<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, minimum-scale=1.0">
    <meta name="description" content="Side maded on Wordpress by Recipe team">

    <?php wp_head(); ?>

    <title><?php wp_title(); ?></title>

</head>

<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>
    <?php
    $header_address = (string) get_field('address_main', 'option');
    $header_phone_1 = get_field('phone_1', 'option');
    $header_phone_2 = get_field('phone_2', 'option');
    $header_schedule = (string) get_field('schedule', 'option');
    $header_address_icon = get_field('icon_address', 'option');
    $header_phone_icon = get_field('icon_phone', 'option');
    $header_clock_icon = get_field('icon_clock', 'option');
    $header_search_icon = get_field('icon_search', 'option');

    $header_address_icon_url = is_array($header_address_icon) && !empty($header_address_icon['url']) ? $header_address_icon['url'] : (is_numeric($header_address_icon) ? wp_get_attachment_url((int) $header_address_icon) : ($header_address_icon ?: ''));
    $header_phone_icon_url = is_array($header_phone_icon) && !empty($header_phone_icon['url']) ? $header_phone_icon['url'] : (is_numeric($header_phone_icon) ? wp_get_attachment_url((int) $header_phone_icon) : ($header_phone_icon ?: ''));
    $header_clock_icon_url = is_array($header_clock_icon) && !empty($header_clock_icon['url']) ? $header_clock_icon['url'] : (is_numeric($header_clock_icon) ? wp_get_attachment_url((int) $header_clock_icon) : ($header_clock_icon ?: ''));
    $header_search_icon_url = is_array($header_search_icon) && !empty($header_search_icon['url']) ? $header_search_icon['url'] : (is_numeric($header_search_icon) ? wp_get_attachment_url((int) $header_search_icon) : ($header_search_icon ?: ''));

    $header_address_icon_alt = is_array($header_address_icon) && !empty($header_address_icon['alt'])
        ? (string) $header_address_icon['alt']
        : 'Address icon';
    $header_phone_icon_alt = is_array($header_phone_icon) && !empty($header_phone_icon['alt'])
        ? (string) $header_phone_icon['alt']
        : 'Phone icon';
    $header_clock_icon_alt = is_array($header_clock_icon) && !empty($header_clock_icon['alt'])
        ? (string) $header_clock_icon['alt']
        : 'Schedule icon';

    $header_phone_1_url = '';
    $header_phone_1_label = '';
    $header_phone_1_target = '_self';

    if (is_array($header_phone_1)) {
        $header_phone_1_url = $header_phone_1['url'] ?? '';
        $header_phone_1_label = $header_phone_1['title'] ?? '';
        $header_phone_1_target = $header_phone_1['target'] ?? '_self';
    }

    $header_phone_2_url = '';
    $header_phone_2_label = '';
    $header_phone_2_target = '_self';

    if (is_array($header_phone_2)) {
        $header_phone_2_url = $header_phone_2['url'] ?? '';
        $header_phone_2_label = $header_phone_2['title'] ?? '';
        $header_phone_2_target = $header_phone_2['target'] ?? '_self';
    }

    $header_social_items = [];
    $header_social_fields = [
        'instagram' => ['link' => 'link_instagram', 'icon' => 'icon_instagram', 'label' => 'Instagram'],
        'facebook' => ['link' => 'link_facebook', 'icon' => 'icon_facebook', 'label' => 'Facebook'],
        'tiktok' => ['link' => 'link_tiktok', 'icon' => 'icon_tiktok', 'label' => 'TikTok'],
        'telegram' => ['link' => 'link_telegram', 'icon' => 'icon_telegram', 'label' => 'Telegram'],
    ];
    foreach ($header_social_fields as $config) {
        $icon_value = get_field($config['icon'], 'option');
        $icon_url = is_array($icon_value) && !empty($icon_value['url']) ? $icon_value['url'] : (is_numeric($icon_value) ? wp_get_attachment_url((int) $icon_value) : ($icon_value ?: ''));
        $link = (string) get_field($config['link'], 'option');

        if ($link === '' && $icon_url === '') {
            continue;
        }

        $header_social_items[] = [
            'link' => $link !== '' ? $link : '#',
            'icon_url' => $icon_url,
            'label' => $config['label'],
        ];
    }
    ?>

    <header class="header">
        <div class="container">
            <div class="header__wrapper">
                <nav class="header__nav" aria-label="<?php esc_attr_e('Main navigation', 'igr-theme'); ?>">
                    <div class="header__logo">
                        <?php if (has_custom_logo()): ?>
                            <?php the_custom_logo(); ?>
                        <?php else: ?>
                            <a href="<?php echo esc_url(home_url('/')); ?>"
                                aria-label="<?php esc_attr_e('Homepage', 'igr-theme'); ?>">
                                <span class="header__logo-text"><?php bloginfo('name'); ?></span>
                            </a>
                        <?php endif; ?>
                    </div>
                    <div class="header__mobile-bar">
                        <div class="header__menu">
                            <div class="header__content">
                                <div class="header__top">
                                    <div class="header__top-container">
                                        <div class="header__top-info">
                                            <?php if ($header_address !== ''): ?>
                                                <div class="header__address-block">
                                                    <?php if ($header_address_icon_url !== ''): ?>
                                                        <span class="header__info-icon">
                                                            <?php
                                                            get_picture([
                                                                'src' => $header_address_icon_url,
                                                                'alt' => $header_address_icon_alt,
                                                                'class' => 'header__info-icon-image',
                                                                'lazy' => false,
                                                            ]);
                                                            ?>
                                                        </span>
                                                    <?php endif; ?>

                                                    <span
                                                        class="header__address"><?php echo esc_html($header_address); ?></span>
                                                </div>
                                            <?php endif; ?>

                                            <?php if ($header_phone_1_url !== '' || $header_phone_2_url !== ''): ?>
                                                <div class="header__phones-block">
                                                    <?php if ($header_phone_icon_url !== ''): ?>
                                                        <span class="header__info-icon">
                                                            <?php
                                                            get_picture([
                                                                'src' => $header_phone_icon_url,
                                                                'alt' => $header_phone_icon_alt,
                                                                'class' => 'header__info-icon-image',
                                                                'lazy' => false,
                                                            ]);
                                                            ?>
                                                        </span>
                                                    <?php endif; ?>

                                                    <ul class="header__phones-list">
                                                        <?php if ($header_phone_1_url !== ''): ?>
                                                            <li class="header__phones-item">
                                                                <a class="header__phone"
                                                                    href="<?php echo esc_url($header_phone_1_url); ?>"
                                                                    target="<?php echo esc_attr($header_phone_1_target); ?>">
                                                                    <?php echo esc_html($header_phone_1_label !== '' ? $header_phone_1_label : $header_phone_1_url); ?>
                                                                </a>
                                                            </li>
                                                        <?php endif; ?>

                                                        <?php if ($header_phone_2_url !== ''): ?>
                                                            <li class="header__phones-item">
                                                                <a class="header__phone"
                                                                    href="<?php echo esc_url($header_phone_2_url); ?>"
                                                                    target="<?php echo esc_attr($header_phone_2_target); ?>">
                                                                    <?php echo esc_html($header_phone_2_label !== '' ? $header_phone_2_label : $header_phone_2_url); ?>
                                                                </a>
                                                            </li>
                                                        <?php endif; ?>
                                                    </ul>
                                                </div>
                                            <?php endif; ?>

                                            <?php if ($header_schedule !== ''): ?>
                                                <div class="header__schedule-block">
                                                    <?php if ($header_clock_icon_url !== ''): ?>
                                                        <span class="header__info-icon">
                                                            <?php
                                                            get_picture([
                                                                'src' => $header_clock_icon_url,
                                                                'alt' => $header_clock_icon_alt,
                                                                'class' => 'header__info-icon-image',
                                                                'lazy' => false,
                                                            ]);
                                                            ?>
                                                        </span>
                                                    <?php endif; ?>

                                                    <span
                                                        class="header__schedule"><?php echo wp_kses_post($header_schedule); ?></span>
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                        <?php if (!empty($header_social_items)): ?>
                                            <ul class="header__socials"
                                                aria-label="<?php esc_attr_e('Social links', 'igr-theme'); ?>">
                                                <?php foreach ($header_social_items as $item): ?>
                                                    <li class="header__socials-item">
                                                        <?php
                                                        get_template_part('templates/button', null, [
                                                            'link' => $item['link'],
                                                            'type' => 'social',
                                                            'class' => 'header__socials-link btn--social--header',
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
                                </div>
                                <div class="header__bottom">
                                    <div class="header__controls">
                                        <div class="header__search" id="header-search">
                                            <div class="header__search-field">
                                                <input class="header__search-input" type="search"
                                                    placeholder="<?php esc_attr_e(igrmed__('search_placeholder')); ?>">
                                                <div class="header__search-icons">
                                                    <span class="header__search-icon-wrapper header__search-icon-wrapper--search" aria-hidden="true">
                                                        <?php if ($header_search_icon_url !== ''): ?>
                                                            <?php
                                                            get_picture([
                                                                'src' => $header_search_icon_url,
                                                                'alt' => '',
                                                                'class' => 'header__search-icon',
                                                                'lazy' => false,
                                                            ]);
                                                            ?>
                                                        <?php endif; ?>
                                                    </span>
                                                    <span class="header__search-icon-wrapper header__search-icon-wrapper--loader" aria-hidden="true">
                                                        <svg class="header__search-loader" width="20" height="20" viewBox="0 0 38 38" xmlns="http://www.w3.org/2000/svg" stroke="currentColor">
                                                            <g fill="none" fill-rule="evenodd">
                                                                <g transform="translate(1 1)" stroke-width="2">
                                                                    <circle stroke-opacity=".5" cx="18" cy="18" r="18" />
                                                                    <path d="M36 18c0-9.94-8.06-18-18-18">
                                                                        <animateTransform attributeName="transform" type="rotate" from="0 18 18" to="360 18 18" dur="1s" repeatCount="indefinite" />
                                                                    </path>
                                                                </g>
                                                            </g>
                                                        </svg>
                                                    </span>
                                                    <button type="button" class="header__search-icon-wrapper header__search-icon-wrapper--close" aria-label="<?php esc_attr_e(igrmed__('btn_close')); ?>">
                                                        <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M1 1L13 13M1 13L13 1" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="header__search-results"></div>
                                        </div>
                                        <?php get_template_part('templates/language-switcher', null, [
                                            'class' => 'header__lang header__lang--desktop',
                                        ]); ?>
                                    </div>
                                    <div class="header__menu-lang-block">
                                        <?php get_template_part('templates/navigation', null, array('location' => 'menu-header')); ?>
                                    </div>
                                    <a href="#contact" class="header__cta">
                                        <span
                                            class="header__cta-text"><?php igrmed_e('btn_contact_us'); ?></span>
                                        <span class="header__cta-icon">
                                            <span class="header__cta-icon-circle" aria-hidden="true"></span>
                                            <?php
                                            get_picture([
                                                'name' => 'svg/contact-arrow.svg',
                                                'alt' => 'Contact arrow icon',
                                                'class' => 'header__cta-icon-arrow',
                                                'lazy' => false,
                                            ]);
                                            ?>
                                        </span>
                                    </a>
                                </div>
                            </div>

                        </div>
                        <?php get_template_part('templates/language-switcher', null, [
                            'class' => 'header__lang header__lang--bar',
                        ]); ?>
                        <span class="header__mobile-spacer" aria-hidden="true"></span>
                        <button class="header__search-btn-bar" type="button"
                            aria-label="<?php esc_attr_e('Search', 'igr-theme'); ?>">
                            <svg class="header__search-btn-icon" width="18" height="18" viewBox="0 0 18 18" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <circle cx="8" cy="8" r="5.5" stroke="currentColor" stroke-width="1.5" />
                                <path d="M12.5 12.5L16 16" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round" />
                            </svg>
                        </button>
                        <span class="header__mobile-spacer" aria-hidden="true"></span>
                        <button class="header__burger" type="button"
                            aria-label="<?php esc_attr_e('Open menu', 'igr-theme'); ?>" aria-expanded="false"
                            data-mobile-menu-toggle>
                            <span class="header__burger-icon" aria-hidden="true">
                                <svg class="header__burger-svg" width="25" height="13" viewBox="0 0 25 13" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <line class="header__burger-line header__burger-line--1" x1="0.5" y1="0.5" x2="24.5"
                                        y2="0.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                    <line class="header__burger-line header__burger-line--2" x1="0.5" y1="4.5" x2="24.5"
                                        y2="4.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                    <line class="header__burger-line header__burger-line--3" x1="3" y1="8.5" x2="22"
                                        y2="8.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                    <line class="header__burger-line header__burger-line--4" x1="0.5" y1="12.5"
                                        x2="24.5" y2="12.5" stroke="currentColor" stroke-width="1.5"
                                        stroke-linecap="round" />
                                </svg>
                            </span>
                        </button>
                    </div>
                </nav>
            </div>
        </div>
        <div class="header__mobile-menu" data-mobile-menu aria-hidden="true">
            <div class="header__mobile-menu-content">
                <div class="header__mobile-menu-inner">
                    <div class="header__mobile-menu-nav">
                        <?php get_template_part('templates/navigation', null, array('location' => 'menu-header')); ?>
                    </div>
                    <a href="#contact" class="header__cta header__mobile-menu-cta">
                        <span class="header__cta-text"><?php igrmed_e('btn_contact_us'); ?></span>
                        <span class="header__cta-icon">
                            <span class="header__cta-icon-circle" aria-hidden="true"></span>
                            <?php
                            get_picture([
                                'name' => 'svg/contact-arrow.svg',
                                'alt' => 'Contact arrow icon',
                                'class' => 'header__cta-icon-arrow',
                                'lazy' => false,
                            ]);
                            ?>
                        </span>
                    </a>
                    <div class="header__mobile-menu-info">
                        <?php if ($header_address !== ''): ?>
                            <div class="header__mobile-menu-info-row">
                                <?php if ($header_address_icon_url !== ''): ?>
                                    <span class="header__mobile-menu-info-icon">
                                        <?php
                                        get_picture([
                                            'src' => $header_address_icon_url,
                                            'alt' => $header_address_icon_alt,
                                            'class' => 'header__mobile-menu-info-icon-image',
                                            'lazy' => false,
                                        ]);
                                        ?>
                                    </span>
                                <?php endif; ?>
                                <span class="header__mobile-menu-info-text"><?php echo esc_html($header_address); ?></span>
                            </div>
                        <?php endif; ?>

                        <?php if ($header_phone_1_url !== '' || $header_phone_2_url !== ''): ?>
                            <div class="header__mobile-menu-info-row">
                                <?php if ($header_phone_icon_url !== ''): ?>
                                    <span class="header__mobile-menu-info-icon">
                                        <?php
                                        get_picture([
                                            'src' => $header_phone_icon_url,
                                            'alt' => $header_phone_icon_alt,
                                            'class' => 'header__mobile-menu-info-icon-image',
                                            'lazy' => false,
                                        ]);
                                        ?>
                                    </span>
                                <?php endif; ?>
                                <div class="header__mobile-menu-info-phones">
                                    <?php if ($header_phone_1_url !== ''): ?>
                                        <span class="header__mobile-menu-info-dot" aria-hidden="true">·</span>
                                        <a class="header__mobile-menu-info-text header__mobile-menu-info-link"
                                            href="<?php echo esc_url($header_phone_1_url); ?>"
                                            target="<?php echo esc_attr($header_phone_1_target); ?>">
                                            <?php echo esc_html($header_phone_1_label !== '' ? $header_phone_1_label : $header_phone_1_url); ?>
                                        </a>
                                    <?php endif; ?>
                                    <?php if ($header_phone_2_url !== ''): ?>
                                        <span class="header__mobile-menu-info-dot" aria-hidden="true">·</span>
                                        <a class="header__mobile-menu-info-text header__mobile-menu-info-link"
                                            href="<?php echo esc_url($header_phone_2_url); ?>"
                                            target="<?php echo esc_attr($header_phone_2_target); ?>">
                                            <?php echo esc_html($header_phone_2_label !== '' ? $header_phone_2_label : $header_phone_2_url); ?>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if ($header_schedule !== ''): ?>
                            <div class="header__mobile-menu-info-row">
                                <?php if ($header_clock_icon_url !== ''): ?>
                                    <span class="header__mobile-menu-info-icon">
                                        <?php
                                        get_picture([
                                            'src' => $header_clock_icon_url,
                                            'alt' => $header_clock_icon_alt,
                                            'class' => 'header__mobile-menu-info-icon-image',
                                            'lazy' => false,
                                        ]);
                                        ?>
                                    </span>
                                <?php endif; ?>
                                <span class="header__mobile-menu-info-text"><?php echo wp_kses_post($header_schedule); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php if (!empty($header_social_items)): ?>
                    <ul class="header__mobile-menu-socials" aria-label="<?php esc_attr_e('Social links', 'igr-theme'); ?>">
                        <?php foreach ($header_social_items as $item): ?>
                            <li class="header__mobile-menu-socials-item">
                                <?php
                                get_template_part('templates/button', null, [
                                    'link' => $item['link'],
                                    'type' => 'social',
                                    'class' => 'header__socials-link btn--social--header',
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
        </div>
    </header>

    <!-- Mobile Search Popup -->
    <div class="header__search-popup" id="mobile-search-popup" aria-hidden="true">
        <div class="header__search-popup-overlay"></div>
        <div class="header__search-popup-container">
            <div class="header__search-popup-header">
                <span class="header__search-popup-title"><?php igrmed_e('search_placeholder'); ?></span>
                <button class="header__search-popup-close" type="button"
                    aria-label="<?php esc_attr_e(igrmed__('btn_close')); ?>">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M1 1L15 15M1 15L15 1" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" />
                    </svg>
                </button>
            </div>
            <div class="header__search-popup-field">
                <input class="header__search-popup-input" type="search"
                    placeholder="<?php esc_attr_e('Введіть запит...', 'igr-theme'); ?>" autocomplete="off">
                <span class="header__search-popup-loader" aria-hidden="true">
                    <svg width="20" height="20" viewBox="0 0 38 38" xmlns="http://www.w3.org/2000/svg"
                        stroke="currentColor">
                        <g fill="none" fill-rule="evenodd">
                            <g transform="translate(1 1)" stroke-width="2">
                                <circle stroke-opacity=".5" cx="18" cy="18" r="18" />
                                <path d="M36 18c0-9.94-8.06-18-18-18">
                                    <animateTransform attributeName="transform" type="rotate" from="0 18 18"
                                        to="360 18 18" dur="1s" repeatCount="indefinite" />
                                </path>
                            </g>
                        </g>
                    </svg>
                </span>
            </div>
            <div class="header__search-popup-results"></div>
        </div>
    </div>