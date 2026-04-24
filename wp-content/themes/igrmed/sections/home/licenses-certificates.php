<?php
$title   = get_field('licenses_title');
$gallery = get_field('licenses_gallery');
$bg      = get_field('licenses_bg');

if (!$title && !$gallery) {
    return;
}

$bg_url = '';
$bg_alt = '';
if ($bg && isset($bg['url'])) {
    $bg_url = $bg['url'];
    $bg_alt = $bg['alt'] ?? '';
}
?>

<section class="licenses-certificates">
    <?php if ($bg_url !== ''): ?>
        <?php
        get_picture([
            'src' => $bg_url,
            'alt' => $bg_alt,
            'class' => 'licenses-certificates__bg',
            'lazy' => true,
        ]);
        ?>
    <?php endif; ?>
    <div class="container">
        <?php if ($title): ?>
            <div class="licenses-certificates__header">
                <h2 class="licenses-certificates__title"><?php echo esc_html($title); ?></h2>
            </div>
        <?php endif; ?>

        <?php if ($gallery): ?>
            <div class="licenses-certificates__slider-wrapper">
                <div class="swiper js-licenses-slider licenses-certificates__slider">
                    <div class="swiper-wrapper">
                        <?php foreach ($gallery as $image): ?>
                            <div class="swiper-slide licenses-certificates__slide">
                                <?php
                                get_picture([
                                    'src' => $image['sizes']['large'] ?? $image['url'],
                                    'alt' => $image['alt'],
                                    'class' => 'licenses-certificates__img',
                                    'lazy' => true,
                                ]);
                                ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="licenses-certificates__nav">
                    <?php get_template_part('templates/button', null, [
                        'type' => 'carousel',
                        'icon_url' => get_template_directory_uri() . '/assets/img/svg/arrow-prev.svg',
                        'class' => 'licenses-certificates__prev js-licenses-prev',
                        'attributes' => ['aria-label' => igrmed__('pagination_prev')]
                    ]); ?>
                    <?php get_template_part('templates/button', null, [
                        'type' => 'carousel',
                        'icon_url' => get_template_directory_uri() . '/assets/img/svg/arrow-next.svg',
                        'class' => 'licenses-certificates__next js-licenses-next',
                        'attributes' => ['aria-label' => igrmed__('pagination_next')]
                    ]); ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>