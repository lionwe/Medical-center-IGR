<?php
$doctors_title = (string) get_field('doctors_title');
$doctors_bg = get_field('doctors_bg');
$doctors_button = get_field('doctors_button');
$doctors_posts = get_field('doctors_posts');

$doctors_button_url = '';
$doctors_button_label = '';
$doctors_button_target = '_self';

if (is_array($doctors_button)) {
    $doctors_button_url = (string) ($doctors_button['url'] ?? '');
    $doctors_button_label = igrmed__('btn_contact_us');
    $doctors_button_target = (string) ($doctors_button['target'] ?? '_self');
}

$doctors_bg_url = '';
$doctors_bg_alt = '';

if (is_string($doctors_bg) && $doctors_bg !== '') {
    $doctors_bg_url = $doctors_bg;
} elseif (is_array($doctors_bg)) {
    $doctors_bg_url = (string) ($doctors_bg['url'] ?? '');
    $doctors_bg_alt = (string) ($doctors_bg['alt'] ?? '');
} elseif (is_numeric($doctors_bg)) {
    $doctors_bg_url = (string) wp_get_attachment_image_url((int) $doctors_bg, 'full');
}

if ($doctors_bg_alt === '') {
    $doctors_bg_alt = $doctors_title;
}

if (!is_array($doctors_posts) || empty($doctors_posts)) {
    return;
}

$doctors_posts = array_values(array_filter($doctors_posts, static fn($post) => $post instanceof WP_Post));

if (empty($doctors_posts)) {
    return;
}
?>

<section class="doctors" id="doctors">
    <?php if ($doctors_bg_url !== ''): ?>
        <div class="doctors__bg" aria-hidden="true">
            <?php
            get_picture([
                'src' => $doctors_bg_url,
                'alt' => '',
                'class' => 'doctors__bg-image',
                'lazy' => true,
            ]);
            ?>
        </div>
    <?php endif; ?>
    <div class="container">

        <div class="doctors__wrapper">
            <?php if ($doctors_title !== ''): ?>
                <h2 class="doctors__title">
                    <?php echo esc_html($doctors_title); ?>
                </h2>
            <?php endif; ?>

            <?php if ($doctors_button_url !== '' && $doctors_button_label !== ''): ?>
                <div class="doctors__actions">
                    <?php
                    get_template_part('templates/button', null, [
                        'text' => $doctors_button_label,
                        'link' => $doctors_button_url,
                        'type' => 'primary',
                        'primary_split' => true,
                        'icon_url' => get_template_directory_uri() . '/assets/img/svg/contact-arrow.svg',
                        'target' => $doctors_button_target,
                    ]);
                    ?>
                </div>
            <?php endif; ?>

            <div class="doctors__slider-clip">
                <div class="doctors__slider swiper js-doctors-swiper">
                    <div class="swiper-wrapper">
                        <?php foreach ($doctors_posts as $doctor_post): ?>
                            <?php
                            $doctor_id = $doctor_post->ID;
                            $doctor_spec = trim((string) get_field('doctor_spec', $doctor_id));
                            $doctor_name = get_the_title($doctor_id);
                            $doctor_excerpt = trim(wp_strip_all_tags((string) get_the_excerpt($doctor_id)));
                            $doctor_link = (string) get_permalink($doctor_id);
                            $doctor_photo = get_field('doctor_photo', $doctor_id);
                            $doctor_photo_url = '';

                            if (is_array($doctor_photo) && !empty($doctor_photo['url'])) {
                                $doctor_photo_url = (string) $doctor_photo['url'];
                            } elseif (is_numeric($doctor_photo)) {
                                $doctor_photo_url = (string) wp_get_attachment_image_url((int) $doctor_photo, 'medium_large');
                            } elseif (is_string($doctor_photo) && $doctor_photo !== '') {
                                $doctor_photo_url = $doctor_photo;
                            } else {
                                $doctor_photo_url = get_the_post_thumbnail_url($doctor_id, 'medium_large') ?: '';
                            }
                            ?>

                            <article class="doctors__slide swiper-slide">
                                <?php if ($doctor_spec !== ''): ?>
                                    <p class="doctors__slide-spec"><?php echo esc_html($doctor_spec); ?></p>
                                <?php endif; ?>

                                <?php if ($doctor_name !== ''): ?>
                                    <h3 class="doctors__slide-title"><?php echo esc_html($doctor_name); ?></h3>
                                <?php endif; ?>

                                <?php if ($doctor_excerpt !== ''): ?>
                                    <p class="doctors__slide-excerpt"><?php echo esc_html($doctor_excerpt); ?></p>
                                <?php endif; ?>

                                <?php if ($doctor_photo_url !== ''): ?>
                                    <div class="doctors__slide-image-wrap">
                                        <?php
                                        get_picture([
                                            'src' => $doctor_photo_url,
                                            'alt' => $doctor_name,
                                            'class' => 'doctors__slide-image',
                                            'lazy' => true,
                                        ]);
                                        ?>
                                        <?php if ($doctor_link !== ''): ?>
                                            <a class="doctors__slide-more" href="<?php echo esc_url($doctor_link); ?>">
                                                <span class="doctors__slide-more-text"><?php igrmed_e('doctors_details'); ?></span>
                                                <svg class="doctors__slide-more-icon" width="20" height="20" viewBox="0 0 20 20" aria-hidden="true" focusable="false">
                                                    <path d="M9.36002 19.2004V0.000390053H10.32V19.2004H9.36002ZM2.45571e-05 10.0804V9.16839H19.68V10.0804H2.45571e-05Z" fill="currentColor"/>
                                                </svg>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </article>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="doctors__slider-nav btn-group--glass-circle">
                    <?php
                    get_template_part('templates/button', null, [
                        'type' => 'carousel-glass',
                        'icon_url' => get_template_directory_uri() . '/assets/img/svg/arrow-carousel-active.svg',
                        'class' => 'is-prev js-doctors-prev',
                        'attributes' => ['aria-label' => __('Попередній лікар', 'igrmed')],
                    ]);
                    ?>
                    <?php
                    get_template_part('templates/button', null, [
                        'type' => 'carousel-glass',
                        'icon_url' => get_template_directory_uri() . '/assets/img/svg/arrow-carousel-active.svg',
                        'class' => 'doctors__next js-doctors-next',
                        'attributes' => ['aria-label' => __('Наступний лікар', 'igrmed')],
                    ]);
                    ?>
                </div>
            </div>
        </div>
    </div>
</section>