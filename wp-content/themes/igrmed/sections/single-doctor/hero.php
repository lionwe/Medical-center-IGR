<?php

/**
 * Single Doctor Hero Section
 */

$doctor_specialization = get_field('doctor_specialization');
$doctor_experience = get_field('doctor_experience');
$doctor_schedule = get_field('doctor_schedule');
$doctor_fb = get_field('doctor_facebook');
$doctor_insta = get_field('doctor_instagram');

// Tabs content
$doctor_about = get_field('doctor_about');
$doctor_education = get_field('doctor_education');
$doctor_certificates = get_field('doctor_certificates');

// Get social icons from options
$icon_fb = get_field('icon_facebook', 'option');
$icon_insta = get_field('icon_instagram', 'option');

$icon_fb_url = is_array($icon_fb) ? $icon_fb['url'] : '';
$icon_insta_url = is_array($icon_insta) ? $icon_insta['url'] : '';

$doctor_name = get_the_title();
$doctor_content = get_the_content();
$doctor_photo = get_post_thumbnail_id();

// Contacts button data
$contact_link = get_field('header_btn_link', 'option');
$contact_label = get_field('header_btn_label', 'option') ?: __('Зв’язатись з нами', 'igrmed');
?>

<section class="doctor-hero js-doctor-tabs">
    <div class="container">
        <div class="doctor-hero__container">
            <div class="doctor-hero__top">
                <div class="doctor-hero__info">
                    <div class="doctor-hero__breadcrumbs">
                        <?php get_template_part('templates/breadcrumbs'); ?>
                    </div>

                    <div class="doctor-hero__meta">
                        <div class="doctor-hero__socials">
                            <?php if ($doctor_insta): ?>
                                <a href="<?php echo esc_url($doctor_insta); ?>" class="doctor-hero__social-item" target="_blank" rel="noopener noreferrer">
                                    <?php if ($icon_insta_url): ?>
                                        <img src="<?php echo esc_url($icon_insta_url); ?>" alt="Instagram">
                                    <?php else: ?>
                                        <svg viewBox="0 0 24 24">
                                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.353 2.609 6.782 6.98 6.981 1.281.058 1.688.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.981-6.98.058-1.28.072-1.689.072-4.948 0-3.259-.014-3.669-.072-4.949-.2-4.338-2.621-6.77-6.981-6.98-1.281-.059-1.689-.073-4.948-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                                        </svg>
                                    <?php endif; ?>
                                </a>
                            <?php endif; ?>

                            <?php if ($doctor_fb): ?>
                                <a href="<?php echo esc_url($doctor_fb); ?>" class="doctor-hero__social-item" target="_blank" rel="noopener noreferrer">
                                    <?php if ($icon_fb_url): ?>
                                        <img src="<?php echo esc_url($icon_fb_url); ?>" alt="Facebook">
                                    <?php else: ?>
                                        <svg viewBox="0 0 24 24">
                                            <path d="M22.675 0h-21.35c-.732 0-1.325.593-1.325 1.325v21.351c0 .731.593 1.324 1.325 1.324h11.495v-9.294h-3.128v-3.622h3.128v-2.671c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463.099 2.795.143v3.24l-1.918.001c-1.504 0-1.795.715-1.795 1.763v2.313h3.587l-.467 3.622h-3.12v9.293h6.116c.73 0 1.323-.593 1.323-1.324v-21.35c0-.732-.593-1.325-1.325-1.325z" />
                                        </svg>
                                    <?php endif; ?>
                                </a>
                            <?php endif; ?>
                        </div>

                        <div class="doctor-hero__text-group">
                            <h1 class="doctor-hero__name"><?php echo esc_html($doctor_name); ?></h1>
                            <?php if ($doctor_content): ?>
                                <div class="doctor-hero__excerpt">
                                    <?php echo wp_kses_post($doctor_content); ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="doctor-hero__badges">
                            <?php if ($doctor_specialization): ?>
                                <div class="doctor-hero__badge doctor-hero__badge--specialty">
                                    <div class="doctor-hero__badge-value"><?php echo esc_html($doctor_specialization); ?></div>
                                    <div class="doctor-hero__badge-label"><?php esc_html_e('Спеціальність', 'igrmed'); ?></div>
                                </div>
                            <?php endif; ?>

                            <?php if ($doctor_experience): ?>
                                <div class="doctor-hero__badge doctor-hero__badge--experience">
                                    <div class="doctor-hero__badge-value"><?php echo esc_html($doctor_experience); ?></div>
                                    <div class="doctor-hero__badge-label"><?php esc_html_e('Років стажу', 'igrmed'); ?></div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="doctor-hero__image-wrapper">
                    <?php if ($doctor_photo): ?>
                        <?php echo wp_get_attachment_image($doctor_photo, 'full', false, ['class' => 'doctor-hero__image', 'alt' => $doctor_name]); ?>
                    <?php endif; ?>
                </div>
            </div>

            <hr class="doctor-hero__divider">

            <div class="doctor-hero__bottom">
                <div class="doctor-hero__left">
                    <nav class="doctor-hero__nav">
                        <button class="doctor-hero__tab is-active" data-tab="about">
                            <span class="doctor-hero__tab-text"><?php esc_html_e('Про лікаря', 'igrmed'); ?></span>
                            <svg class="doctor-hero__tab-arrow" width="31" height="8" viewBox="0 0 31 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M30.3536 4.03556C30.5488 3.8403 30.5488 3.52372 30.3536 3.32845L27.1716 0.146473C26.9763 -0.0487893 26.6597 -0.0487893 26.4645 0.146473C26.2692 0.341735 26.2692 0.658318 26.4645 0.85358L29.2929 3.68201L26.4645 6.51043C26.2692 6.7057 26.2692 7.02228 26.4645 7.21754C26.6597 7.4128 26.9763 7.4128 27.1716 7.21754L30.3536 4.03556ZM0 3.68201V4.18201H30V3.68201V3.18201H0V3.68201Z" fill="black" />
                            </svg>
                        </button>
                        <?php if ($doctor_education): ?>
                            <button class="doctor-hero__tab" data-tab="education">
                                <span class="doctor-hero__tab-text"><?php esc_html_e('Освіта', 'igrmed'); ?></span>
                                <svg class="doctor-hero__tab-arrow" width="31" height="8" viewBox="0 0 31 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M30.3536 4.03556C30.5488 3.8403 30.5488 3.52372 30.3536 3.32845L27.1716 0.146473C26.9763 -0.0487893 26.6597 -0.0487893 26.4645 0.146473C26.2692 0.341735 26.2692 0.658318 26.4645 0.85358L29.2929 3.68201L26.4645 6.51043C26.2692 6.7057 26.2692 7.02228 26.4645 7.21754C26.6597 7.4128 26.9763 7.4128 27.1716 7.21754L30.3536 4.03556ZM0 3.68201V4.18201H30V3.68201V3.18201H0V3.68201Z" fill="black" />
                                </svg>
                            </button>
                        <?php endif; ?>
                        <?php if ($doctor_certificates): ?>
                            <button class="doctor-hero__tab" data-tab="certificates">
                                <span class="doctor-hero__tab-text"><?php esc_html_e('Сертифікати', 'igrmed'); ?></span>
                                <svg class="doctor-hero__tab-arrow" width="31" height="8" viewBox="0 0 31 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M30.3536 4.03556C30.5488 3.8403 30.5488 3.52372 30.3536 3.32845L27.1716 0.146473C26.9763 -0.0487893 26.6597 -0.0487893 26.4645 0.146473C26.2692 0.341735 26.2692 0.658318 26.4645 0.85358L29.2929 3.68201L26.4645 6.51043C26.2692 6.7057 26.2692 7.02228 26.4645 7.21754C26.6597 7.4128 26.9763 7.4128 27.1716 7.21754L30.3536 4.03556ZM0 3.68201V4.18201H30V3.68201V3.18201H0V3.68201Z" fill="black" />
                                </svg>
                            </button>
                        <?php endif; ?>
                    </nav>

                    <a class="doctor-hero__btn btn-split btn-split--primary" href="#cta">
                        <span class="btn-split__text"><?php esc_html_e('Зв’язатись з нами', 'igrmed'); ?></span>
                        <span class="btn btn--primary btn--icon-only" aria-hidden="true">
                            <span class="btn__icon" style="-webkit-mask-image: url('<?php echo get_template_directory_uri(); ?>/assets/img/svg/contact-arrow.svg'); mask-image: url('<?php echo get_template_directory_uri(); ?>/assets/img/svg/contact-arrow.svg');"></span>
                        </span>
                    </a>
                </div>

                <div class="doctor-hero__content-wrapper">
                    <div class="doctor-hero__tab-content is-active" data-content="about">
                        <?php if ($doctor_about): ?>
                            <div class="doctor-hero__wysiwyg">
                                <?php echo wp_kses_post($doctor_about); ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <?php if ($doctor_education): ?>
                        <div class="doctor-hero__tab-content" data-content="education">
                            <div class="doctor-hero__wysiwyg">
                                <?php echo wp_kses_post($doctor_education); ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if ($doctor_certificates): ?>
                        <div class="doctor-hero__tab-content" data-content="certificates">
                            <div class="doctor-hero__certificates-grid">
                                <?php foreach ($doctor_certificates as $cert): ?>
                                    <div class="doctor-hero__certificate-item">
                                        <?php echo wp_get_attachment_image($cert['ID'], 'medium', false, ['class' => 'doctor-hero__certificate-img']); ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>