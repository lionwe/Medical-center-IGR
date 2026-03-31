<?php
$title   = get_field('licenses_title');
$gallery = get_field('licenses_gallery');
$bg      = get_field('licenses_bg');

if (!$title && !$gallery) {
    return;
}

$bg_style = '';
if ($bg && isset($bg['url'])) {
    $bg_style = 'style="background-image: url(' . esc_url($bg['url']) . ');"';
}
?>

<section class="licenses-certificates" <?php echo $bg_style; ?>>
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
                                <img src="<?php echo esc_url($image['sizes']['large'] ?? $image['url']); ?>" alt="<?php echo esc_attr($image['alt'] ?: $title); ?>" class="licenses-certificates__img" loading="lazy">
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