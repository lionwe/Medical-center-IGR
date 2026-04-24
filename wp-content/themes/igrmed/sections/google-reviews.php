<?php
$homepage_id = getHomePageID();
$shortcode = get_field('reviews_shortcode', $homepage_id);

$feed_id = 0;
if ($shortcode && preg_match('/id=["\']?(\d+)["\']?/', $shortcode, $matches)) {
    $feed_id = (int) $matches[1];
}

$grw_data = get_grw_data($feed_id);
$reviews = $grw_data ? $grw_data['reviews'] : [];

// Filter reviews: show only 4+ stars
$reviews = array_filter($reviews, function($review) {
    return isset($review->rating) && $review->rating >= 4;
});

if (empty($reviews)) {
    return;
}
?>

<section class="reviews" id="reviews">
    <div class="container">
        <div class="reviews__wrapper">
            <div class="reviews__header">
                <h2 class="reviews__title">
                    <?php the_field('reviews_title', $homepage_id); ?>
                </h2>
                <div class="reviews__navigation">
                    <?php
                    get_template_part('templates/button', null, [
                        'type'       => 'carousel',
                        'icon_url'   => get_template_directory_uri() . '/assets/img/svg/arrow-prev.svg',
                        'class'      => 'js-reviews-prev',
                        'attributes' => ['aria-label' => 'Попередній'],
                    ]);
                    ?>
                    <?php
                    get_template_part('templates/button', null, [
                        'type'       => 'carousel',
                        'icon_url'   => get_template_directory_uri() . '/assets/img/svg/arrow-next.svg',
                        'class'      => 'js-reviews-next',
                        'attributes' => ['aria-label' => 'Наступний'],
                    ]);
                    ?>
                </div>
            </div>

            <div class="reviews__slider swiper js-reviews-swiper">
                <div class="swiper-wrapper">
                    <?php foreach ($reviews as $review): ?>
                        <div class="swiper-slide">
                            <div class="reviews-card">
                                <div class="reviews-card__stars">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <span class="star <?php echo $i <= $review->rating ? 'star--filled' : ''; ?>">★</span>
                                    <?php endfor; ?>
                                </div>

                                <?php if ($review->text): ?>
                                    <?php
                                    $text = wp_strip_all_tags($review->text);
                                    $words = explode(' ', trim($text));
                                    if (count($words) > 15) {
                                        $limited_words = array_slice($words, 0, 15);
                                        $limited_text = implode(' ', $limited_words) . '...';
                                    } else {
                                        $limited_text = $text;
                                    }
                                    ?>
                                    <p class="reviews-card__text wp-google-text">
                                        <?php echo esc_html($limited_text); ?>
                                    </p>
                                <?php endif; ?>

                                <?php if ($review->author_url): ?>
                                    <a href="<?php echo esc_url($review->author_url); ?>" target="_blank"
                                        rel="nofollow noopener" class="reviews-card__read-more">
                                        Читати більше
                                    </a>
                                <?php endif; ?>

                                <div class="reviews-card__name wp-google-name">
                                    <?php echo esc_html($review->author_name); ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>