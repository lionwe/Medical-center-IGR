<?php

/**
 * Block Name: Why Choose Us
 * Description: Section "Why choose us" for Home page
 */

/** Empty Fields Rule check */
$bg_image   = get_field('why_choose_us_bg');
$title      = get_field('why_choose_us_title');
$list       = get_field('why_choose_us_list');
$media_type = get_field('why_choose_us_media_type'); // 'video' or 'image'
$video      = get_field('why_choose_us_video');
$image      = get_field('why_choose_us_image');

// Render only if we have minimum required content
if (!$title && !$list) {
    return;
}
?>

<section class="why-choose-us" <?php if ($bg_image): ?>style="background-image: url('<?php echo esc_url($bg_image['url']); ?>');" <?php endif; ?>>
    <div class="container">

        <?php if ($title): ?>
            <header class="why-choose-us__header">
                <h2 class="why-choose-us__title"><?php echo esc_html($title); ?></h2>
            </header>
        <?php endif; ?>

        <div class="why-choose-us__content">

            <?php if ($list): ?>
                <div class="why-choose-us__list-col">
                    <ul class="why-choose-us__list">
                        <?php foreach ($list as $index => $item):
                            // Add leading zero for numbers < 10
                            $num = sprintf("%02d", $index + 1);
                        ?>
                            <li class="why-choose-us__item">
                                <div class="why-choose-us__number">
                                    <?php echo esc_html($num); ?>
                                </div>
                                <div class="why-choose-us__text">
                                    <?php echo wp_kses_post($item['text']); ?>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <div class="why-choose-us__media-col">
                <?php if ($media_type === 'video' && $video): ?>
                    <div class="why-choose-us__video-wrapper js-video-container">
                        <video class="why-choose-us__video js-video" loop muted playsinline>
                            <source src="<?php echo esc_url($video['url']); ?>" type="video/mp4">
                        </video>

                        <div class="why-choose-us__overlay js-video-overlay">
                            <?php $video_text = get_field('why_choose_us_video_text'); ?>
                            <?php if ($video_text): ?>
                                <div class="why-choose-us__overlay-text">
                                    <?php echo wp_kses_post($video_text); ?>
                                </div>
                            <?php endif; ?>

                            <button type="button" class="why-choose-us__play-btn js-video-play" aria-label="<?php esc_attr_e('Play video', 'igrmed'); ?>">
                                <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/svg/play.svg'); ?>" alt="Play" width="31" height="33">
                            </button>
                        </div>
                    </div>
                <?php elseif ($media_type === 'image' && $image): ?>
                    <div class="why-choose-us__image-wrapper">
                        <?php echo wp_get_attachment_image($image['ID'], 'large', false, ['class' => 'why-choose-us__image']); ?>

                        <?php $video_text = get_field('why_choose_us_video_text'); ?>
                        <?php if ($video_text): ?>
                            <div class="why-choose-us__overlay js-video-overlay why-choose-us__overlay--static">
                                <div class="why-choose-us__overlay-text">
                                    <?php echo wp_kses_post($video_text); ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </div>
</section>