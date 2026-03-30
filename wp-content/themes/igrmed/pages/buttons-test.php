<?php
/*
Template Name: Buttons Test
*/

get_header();
?>

<main id="buttons-test" class="buttons-test">
    <div class="container">
        <div style="padding: 140px 0 60px;">
            <h1 style="margin-bottom: 24px;">Buttons Test</h1>
            <p style="margin-bottom: 32px; max-width: 900px;">
                Тестова сторінка для візуальної перевірки всіх варіантів `templates/button.php`.
            </p>

            <?php
            $iconArrow = get_template_directory_uri() . '/assets/img/svg/contact-arrow.svg';
            $iconInstagram = get_template_directory_uri() . '/assets/img/svg/instagram.svg';

            $sections = [
                'Primary / Dark / Secondary' => [
                    ['type' => 'primary', 'text' => 'Primary', 'link' => '#', 'icon_url' => $iconArrow],
                    ['type' => 'primary', 'text' => 'Primary (no icon)', 'link' => '#'],
                    ['type' => 'primary-soft-hover', 'text' => 'Primary soft hover', 'link' => '#', 'icon_url' => $iconArrow],
                    ['type' => 'primary-calm', 'text' => 'Primary calm (default like banner)', 'link' => '#', 'icon_url' => $iconArrow],
                    ['type' => 'primary-calm-soft', 'text' => 'Primary calm soft (bg 0.05)', 'link' => '#', 'icon_url' => $iconArrow],
                    ['type' => 'secondary', 'text' => 'Secondary', 'link' => '#', 'icon_url' => $iconArrow],
                    ['type' => 'tertiary', 'text' => 'Tertiary', 'link' => '#', 'icon_url' => $iconArrow],
                ],
                'Primary split' => [
                    ['type' => 'primary', 'text' => 'Primary split', 'link' => '#', 'primary_split' => true, 'icon_url' => $iconArrow],
                    ['type' => 'primary-soft-hover', 'text' => 'Primary soft hover split', 'link' => '#', 'primary_split' => true, 'icon_url' => $iconArrow],
                    ['type' => 'primary-calm', 'text' => 'Primary calm split', 'link' => '#', 'primary_split' => true, 'icon_url' => $iconArrow],
                    ['type' => 'primary-calm-soft', 'text' => 'Primary calm soft split', 'link' => '#', 'primary_split' => true, 'icon_url' => $iconArrow],
                ],
                'Removed legacy primary variants' => [
                    ['type' => 'primary', 'text' => 'Primary (legacy variants removed)', 'link' => '#', 'icon_url' => $iconArrow],
                ],
                'Carousel controls' => [
                    [
                        'type' => 'carousel',
                        'carousel_group' => [
                            [
                                'class' => 'is-prev',
                                'aria_label' => 'Prev',
                                'icon_url' => $iconArrow,
                                'attributes' => ['data-test' => 'carousel-prev'],
                            ],
                            [
                                'class' => 'is-next',
                                'aria_label' => 'Next',
                                'icon_url' => $iconArrow,
                                'attributes' => ['data-test' => 'carousel-next'],
                            ],
                        ],
                    ],
                    [
                        'type' => 'carousel-glass',
                        'carousel_group' => [
                            [
                                'class' => 'is-prev',
                                'aria_label' => 'Prev (glass)',
                                'icon_url' => $iconArrow,
                                'attributes' => ['data-test' => 'carousel-glass-prev'],
                            ],
                            [
                                'class' => 'is-next',
                                'aria_label' => 'Next (glass)',
                                'icon_url' => $iconArrow,
                                'attributes' => ['data-test' => 'carousel-glass-next'],
                            ],
                        ],
                    ],
                ],
                'Social buttons' => [
                    ['type' => 'social', 'link' => '#', 'icon_url' => $iconInstagram, 'attributes' => ['aria-label' => 'Instagram']],
                    ['type' => 'social', 'link' => '#', 'icon_url' => $iconArrow, 'attributes' => ['aria-label' => 'Arrow']],
                ],
                'Buttons / submit' => [
                    ['type' => 'button', 'text' => 'Button element', 'attributes' => ['aria-label' => 'Button']],
                    ['type' => 'submit', 'text' => 'Submit element', 'attributes' => ['aria-label' => 'Submit']],
                ],
                'Readmore' => [
                    ['type' => 'readmore-v1', 'text' => 'Readmore v1', 'link' => '#', 'icon_url' => $iconArrow],
                ],
                'Targets & attributes' => [
                    ['type' => 'primary', 'text' => 'Open in new tab', 'link' => '#', 'target' => '_blank', 'attributes' => ['rel' => 'noopener noreferrer'], 'icon_url' => $iconArrow],
                    ['type' => 'primary', 'text' => 'Disabled (aria)', 'link' => '#', 'attributes' => ['aria-disabled' => 'true', 'tabindex' => '-1'], 'class' => 'is-disabled', 'icon_url' => $iconArrow],
                ],
            ];
            ?>

            <?php
            $render_raw_btn = static function (array $config) use ($iconArrow): void {
                $tag = (string) ($config['tag'] ?? 'a');
                $classes = trim((string) ($config['class'] ?? 'btn'));
                $text = (string) ($config['text'] ?? '');
                $href = (string) ($config['href'] ?? '#');
                $attrs = (array) ($config['attrs'] ?? []);
                $iconUrl = (string) ($config['icon_url'] ?? $iconArrow);

                $attrStr = '';
                foreach ($attrs as $k => $v) {
                    $attrStr .= ' ' . esc_attr((string) $k) . '="' . esc_attr((string) $v) . '"';
                }

                if ($tag === 'button') {
                    ?>
                    <button class="<?php echo esc_attr($classes); ?>" type="button" <?php echo $attrStr; ?>>
                        <?php if ($text !== ''): ?><span class="btn__text"><?php echo esc_html($text); ?></span><?php endif; ?>
                        <span class="btn__icon"
                            style="-webkit-mask-image:url('<?php echo esc_url($iconUrl); ?>');mask-image:url('<?php echo esc_url($iconUrl); ?>');"></span>
                    </button>
                    <?php
                    return;
                }

                ?>
                <a class="<?php echo esc_attr($classes); ?>" href="<?php echo esc_url($href); ?>" <?php echo $attrStr; ?>>
                    <?php if ($text !== ''): ?><span class="btn__text"><?php echo esc_html($text); ?></span><?php endif; ?>
                    <span class="btn__icon"
                        style="-webkit-mask-image:url('<?php echo esc_url($iconUrl); ?>');mask-image:url('<?php echo esc_url($iconUrl); ?>');"></span>
                </a>
                <?php
            };

            $render_raw_split = static function (array $config) use ($iconArrow): void {
                $classes = trim((string) ($config['class'] ?? 'btn-split'));
                $text = (string) ($config['text'] ?? 'Split');
                $href = (string) ($config['href'] ?? '#');
                $attrs = (array) ($config['attrs'] ?? []);
                $iconUrl = (string) ($config['icon_url'] ?? $iconArrow);

                $attrStr = '';
                foreach ($attrs as $k => $v) {
                    $attrStr .= ' ' . esc_attr((string) $k) . '="' . esc_attr((string) $v) . '"';
                }
                ?>
                <a class="<?php echo esc_attr($classes); ?>" href="<?php echo esc_url($href); ?>" <?php echo $attrStr; ?>>
                    <span class="btn-split__text"><?php echo esc_html($text); ?></span>
                    <span class="btn-split__icon" aria-hidden="true">
                        <span class="btn__icon"
                            style="-webkit-mask-image:url('<?php echo esc_url($iconUrl); ?>');mask-image:url('<?php echo esc_url($iconUrl); ?>');"></span>
                    </span>
                </a>
                <?php
            };
            ?>

            <?php foreach ($sections as $title => $buttons): ?>
                <section style="margin-top: 44px;">
                    <h2 style="margin-bottom: 18px;"><?php echo esc_html($title); ?></h2>
                    <div style="display:flex; flex-wrap:wrap; gap:12px; align-items:center;">
                        <?php foreach ($buttons as $btnArgs): ?>
                            <?php
                            get_template_part('templates/button', null, array_merge([
                                'text' => '',
                                'link' => '#',
                                'target' => '_self',
                            ], $btnArgs));
                            ?>
                        <?php endforeach; ?>
                    </div>
                </section>
            <?php endforeach; ?>

            <section style="margin-top: 64px;">
                <h2 style="margin-bottom: 18px;">CSS-only button variants (not via template)</h2>
                <div style="display:flex; flex-wrap:wrap; gap:12px; align-items:center;">
                    <?php
                    // Variants defined in assets/css/base/_button.scss that may not be used via templates/button.php.
                    $render_raw_btn([
                        'class' => 'btn btn--simple-outline',
                        'text' => 'Simple outline',
                    ]);
                    $render_raw_btn([
                        'class' => 'btn btn--back-home',
                        'text' => 'Back home',
                    ]);

                    // Carousel states.
                    $render_raw_btn([
                        'tag' => 'button',
                        'class' => 'btn btn--carousel',
                        'text' => '',
                        'attrs' => ['aria-label' => 'Carousel enabled'],
                    ]);
                    $render_raw_btn([
                        'tag' => 'button',
                        'class' => 'btn btn--carousel swiper-button-disabled',
                        'text' => '',
                        'attrs' => ['aria-label' => 'Carousel disabled'],
                    ]);

                    // Carousel glass (prev/next + disabled).
                    $render_raw_btn([
                        'tag' => 'button',
                        'class' => 'btn btn--carousel-glass is-prev',
                        'text' => '',
                        'attrs' => ['aria-label' => 'Carousel glass prev'],
                    ]);
                    $render_raw_btn([
                        'tag' => 'button',
                        'class' => 'btn btn--carousel-glass is-next',
                        'text' => '',
                        'attrs' => ['aria-label' => 'Carousel glass next'],
                    ]);
                    $render_raw_btn([
                        'tag' => 'button',
                        'class' => 'btn btn--carousel-glass is-prev swiper-button-disabled',
                        'text' => '',
                        'attrs' => ['aria-label' => 'Carousel glass prev disabled'],
                    ]);

                    // Social special shapes used in header/hero.
                    $render_raw_btn([
                        'class' => 'btn btn--social header__socials-link',
                        'text' => '',
                        'attrs' => ['aria-label' => 'Header social icon'],
                    ]);
                    $render_raw_btn([
                        'class' => 'btn btn--social hero__socials-link',
                        'text' => 'Hero social',
                        'attrs' => ['aria-label' => 'Hero social'],
                    ]);
                    ?>
                </div>
            </section>

            <section style="margin-top: 44px;">
                <h2 style="margin-bottom: 18px;">Split button CSS variants (btn-split--*)</h2>
                <div style="display:flex; flex-wrap:wrap; gap:12px; align-items:center;">
                    <?php
                    $render_raw_split(['class' => 'btn-split btn-split--primary', 'text' => 'Split primary']);
                    $render_raw_split(['class' => 'btn-split btn-split--white', 'text' => 'Split white']);
                    $render_raw_split(['class' => 'btn-split btn-split--dark', 'text' => 'Split dark']);
                    ?>
                </div>
            </section>

            <section style="margin-top: 44px;">
                <h2 style="margin-bottom: 18px;">Icon-only / no-icon modifiers</h2>
                <div style="display:flex; flex-wrap:wrap; gap:12px; align-items:center;">
                    <?php
                    $render_raw_btn([
                        'class' => 'btn btn--primary btn--icon-only',
                        'text' => '',
                        'attrs' => ['aria-label' => 'Primary icon-only'],
                    ]);
                    $render_raw_btn([
                        'class' => 'btn btn--primary btn--no-icon',
                        'text' => 'Primary no-icon class',
                        'icon_url' => '',
                        'attrs' => ['aria-label' => 'Primary no icon'],
                    ]);
                    ?>
                </div>
            </section>
        </div>
    </div>
</main>

<?php get_footer('simple'); ?>