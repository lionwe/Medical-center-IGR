<?php
/**
 * Section: Gallery
 * Location: About page
 * ACF Fields: gallery_items (repeater), gallery_items.img, gallery_items.text (editor)
 */

$gallery_items = get_field('gallery_items');
$slides = [];

if (is_array($gallery_items)) {
    foreach ($gallery_items as $item) {
        if (!is_array($item)) {
            continue;
        }

        $image = $item['img'] ?? null;
        $text = (string) ($item['text'] ?? '');
        $image_id = 0;
        $image_url = '';
        $image_alt = '';

        if (is_array($image)) {
            $image_id = (int) ($image['ID'] ?? 0);
            $image_url = (string) ($image['url'] ?? '');
            $image_alt = (string) ($image['alt'] ?? '');
        } elseif (is_numeric($image)) {
            $image_id = (int) $image;
            $image_url = (string) wp_get_attachment_image_url($image_id, 'large');
        } elseif (is_string($image)) {
            $image_url = $image;
        }

        if ($image_id === 0 && $image_url === '' && $text === '') {
            continue;
        }

        $slides[] = [
            'image_id' => $image_id,
            'image_url' => $image_url,
            'image_alt' => $image_alt,
            'text' => $text,
        ];
    }
}

if (empty($slides)) {
    return;
}
?>

<section class="about-gallery">
    <div class="container">
        <div class="about-gallery__slider swiper js-about-gallery-swiper">
            <div class="swiper-wrapper">
                <?php foreach ($slides as $slide): ?>
                    <article class="about-gallery__slide swiper-slide">
                        <?php if ($slide['image_id'] > 0 || $slide['image_url'] !== ''): ?>
                            <div class="about-gallery__image-wrap">
                                <?php
                                if ($slide['image_id'] > 0) {
                                    echo wp_get_attachment_image(
                                        $slide['image_id'],
                                        'large',
                                        false,
                                        [
                                            'class' => 'about-gallery__image',
                                            'alt' => esc_attr($slide['image_alt']),
                                            'loading' => 'lazy',
                                        ]
                                    );
                                } else {
                                    get_picture([
                                        'src' => $slide['image_url'],
                                        'alt' => $slide['image_alt'],
                                        'class' => 'about-gallery__image',
                                    ]);
                                }
                                ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($slide['text'] !== ''): ?>
                            <div class="about-gallery__text"><?php echo wp_kses_post($slide['text']); ?></div>
                        <?php endif; ?>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="about-gallery__slider-nav">
            <?php
            get_template_part('templates/button', null, [
                'type' => 'carousel',
                'carousel_group' => [
                    [
                        'icon_url' => get_template_directory_uri() . '/assets/img/svg/arrow-prev.svg',
                        'class' => 'is-prev js-about-gallery-prev',
                        'aria_label' => __('Попередній слайд', 'igrmed'),
                    ],
                    [
                        'icon_url' => get_template_directory_uri() . '/assets/img/svg/arrow-next.svg',
                        'class' => 'js-about-gallery-next',
                        'aria_label' => __('Наступний слайд', 'igrmed'),
                    ],
                ],
            ]);
            ?>
        </div>
    </div>
</section>