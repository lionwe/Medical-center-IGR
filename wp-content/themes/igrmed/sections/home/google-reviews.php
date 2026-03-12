<?php
$title = trim((string) get_field('reviews_title'));
$trustindex_shortcode = trim((string) get_field('reviews_shortcode'));
$has_trustindex_shortcode = function_exists('shortcode_exists') && shortcode_exists('trustindex');

if ($trustindex_shortcode === '' || !$has_trustindex_shortcode) {
    return;
}

if ($title === '') {
    $title = (string) __('Відгуки з Google', 'igrmed');
}
?>

<section class="google-reviews" id="google-reviews">
    <div class="container">
        <div class="google-reviews__wrapper">
            <div class="google-reviews__header">
                <h2 class="google-reviews__title"><?php echo esc_html($title); ?></h2>

                <div class="google-reviews__header-right">
                    <div class="google-reviews__nav">
                        <?php
                        get_template_part('templates/button', null, [
                            'type' => 'carousel',
                            'carousel_group' => [
                                [
                                    'icon_url' => get_template_directory_uri() . '/assets/img/svg/arrow-prev.svg',
                                    'class' => 'google-reviews__prev js-google-reviews-prev',
                                    'aria_label' => __('Попередній відгук', 'igrmed'),
                                ],
                                [
                                    'icon_url' => get_template_directory_uri() . '/assets/img/svg/arrow-next.svg',
                                    'class' => 'google-reviews__next js-google-reviews-next',
                                    'aria_label' => __('Наступний відгук', 'igrmed'),
                                ],
                            ],
                        ]);
                        ?>
                    </div>

                </div>
            </div>

            <div class="google-reviews__embed">
                <?php echo do_shortcode($trustindex_shortcode); ?>
            </div>
        </div>
    </div>
</section>