<?php
/**
 * Ведення вагітності — слайдер лікарів.
 */

$doctors_title = trim((string) get_field('doctors_title'));
$doctors_intro = trim((string) get_field('doctors_intro'));
$doctors_bg    = get_field('doctors_bg');
$doctors_list  = get_field('doctors_list');
$doctors_list  = is_array($doctors_list) ? array_filter($doctors_list, function($doctor) {
    if (!is_object($doctor) && !is_array($doctor)) return false;
    $doctor_id = is_object($doctor) ? $doctor->ID : ($doctor['ID'] ?? 0);
    return !empty($doctor_id);
}) : [];

// Отримуємо URL та alt фону
$bg_url = '';
$bg_alt = '';
if ($doctors_bg) {
    if (is_array($doctors_bg) && !empty($doctors_bg['url'])) {
        $bg_url = $doctors_bg['url'];
        $bg_alt = $doctors_bg['alt'] ?? '';
    } elseif (is_numeric($doctors_bg)) {
        $bg_url = wp_get_attachment_image_url((int) $doctors_bg, 'full');
        $bg_alt = get_post_meta((int) $doctors_bg, '_wp_attachment_image_alt', true);
    } elseif (is_string($doctors_bg) && $doctors_bg !== '') {
        $bg_url = $doctors_bg;
    }
}

if (empty($doctors_list)) {
    return;
}
?>

<div class="pregnancy-doctors" id="preg-doctors">
    <?php if ($bg_url !== ''): ?>
        <?php
        get_picture([
            'src' => $bg_url,
            'alt' => $bg_alt,
            'class' => 'pregnancy-doctors__bg',
            'lazy' => true,
        ]);
        ?>
    <?php endif; ?>
    <div class="container">
        <div class="pregnancy-doctors__wrapper">
            <?php if ($doctors_title !== ''): ?>
                <h2 class="pregnancy-doctors__title">
                    <?php echo esc_html($doctors_title); ?>
                </h2>
            <?php endif; ?>

            <?php if ($doctors_intro !== '') : ?>
                <p class="pregnancy-doctors__intro"><?php echo esc_html($doctors_intro); ?></p>
            <?php endif; ?>

            <div class="pregnancy-doctors__slider-clip">
                <div class="pregnancy-doctors__slider swiper js-pregnancy-doctors-swiper">
                    <div class="swiper-wrapper">
                        <?php foreach ($doctors_list as $doctor): ?>
                            <?php
                            $doctor_id        = $doctor->ID;
                            $doctor_spec      = trim((string) get_field('doctor_spec', $doctor_id));
                            $doctor_name      = get_the_title($doctor_id);
                            $doctor_excerpt   = trim(wp_strip_all_tags((string) get_the_excerpt($doctor_id)));
                            $doctor_link      = (string) get_permalink($doctor_id);
                            $doctor_photo     = get_field('doctor_photo', $doctor_id);
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

                            <article class="pregnancy-doctors__slide swiper-slide">

                                <?php /* ТЕКСТ НАД ЗОБРАЖЕННЯМ */ ?>
                                <?php if ($doctor_name !== ''): ?>
                                    <h3 class="pregnancy-doctors__slide-title"><?php echo esc_html($doctor_name); ?></h3>
                                <?php endif; ?>

                                <?php if ($doctor_spec !== ''): ?>
                                    <p class="pregnancy-doctors__slide-spec"><?php echo esc_html($doctor_spec); ?></p>
                                <?php endif; ?>

                                <?php if ($doctor_excerpt !== ''): ?>
                                    <p class="pregnancy-doctors__slide-excerpt"><?php echo esc_html($doctor_excerpt); ?></p>
                                <?php endif; ?>

                                <?php /* ЗОБРАЖЕННЯ ПІСЛЯ ТЕКСТУ */ ?>
                                <?php if ($doctor_photo_url !== ''): ?>
                                    <div class="pregnancy-doctors__slide-image-wrap">
                                        <?php
                                        get_picture([
                                            'src'   => $doctor_photo_url,
                                            'alt'   => $doctor_name,
                                            'class' => 'pregnancy-doctors__slide-image',
                                        ]);
                                        ?>

                                        <?php if ($doctor_link !== ''): ?>
                                            <a class="pregnancy-doctors__slide-more" href="<?php echo esc_url($doctor_link); ?>">
                                                <span class="pregnancy-doctors__slide-more-text"><?php igrmed_e('doctors_details'); ?></span>
                                                <svg class="pregnancy-doctors__slide-more-icon" width="20" height="20" viewBox="0 0 20 20" aria-hidden="true" focusable="false">
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

                <div class="pregnancy-doctors__slider-nav btn-group--glass-circle">
                    <?php
                    get_template_part('templates/button', null, [
                        'type'       => 'carousel-glass',
                        'icon_url'   => get_template_directory_uri() . '/assets/img/svg/arrow-carousel-active.svg',
                        'class'      => 'is-prev js-pregnancy-doctors-prev',
                        'attributes' => ['aria-label' => igrmed__('pagination_prev')],
                    ]);
                    ?>
                    <?php
                    get_template_part('templates/button', null, [
                        'type'       => 'carousel-glass',
                        'icon_url'   => get_template_directory_uri() . '/assets/img/svg/arrow-carousel-active.svg',
                        'class'      => 'pregnancy-doctors__next js-pregnancy-doctors-next',
                        'attributes' => ['aria-label' => igrmed__('pagination_next')],
                    ]);
                    ?>
                </div>
            </div>
        </div>
    </div>
</section>

