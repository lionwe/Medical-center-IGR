<?php

/**
 * Content Section for Single Blog
 *
 * @package IGRMed
 */

if (!get_the_content()) return;
?>
<section class="blog-content">
    <div class="container">
        <div class="blog-content__inner">
            <?php the_content(); ?>

            <div class="blog-share">
                <div class="blog-share__header">
                    <span class="blog-share__title"><?php echo esc_html__('Поширити', 'igrmed'); ?></span>
                </div>
                <div class="blog-share__list">
                    <?php
                    $share_url   = urlencode(get_permalink());
                    $share_title = urlencode(get_the_title());
                    $share_links = [
                        [
                            'name' => 'facebook',
                            'url'  => "https://www.facebook.com/sharer/sharer.php?u={$share_url}",
                            'icon' => 'facebook'
                        ],
                        [
                            'name' => 'instagram',
                            'url'  => "https://www.instagram.com/", // Instagram doesn't support direct URL sharing via link
                            'icon' => 'instagram'
                        ],
                        [
                            'name' => 'telegram',
                            'url'  => "https://t.me/share/url?url={$share_url}&text={$share_title}",
                            'icon' => 'telegram'
                        ],
                        [
                            'name' => 'x',
                            'url'  => "https://twitter.com/intent/tweet?url={$share_url}&text={$share_title}",
                            'icon' => 'x'
                        ],
                    ];
                    ?>
                    <?php foreach ($share_links as $link): ?>
                        <a href="<?php echo esc_url($link['url']); ?>"
                            class="blog-share__link blog-share__link--<?php echo esc_attr($link['name']); ?>"
                            target="_blank"
                            rel="noopener noreferrer"
                            aria-label="<?php echo sprintf(esc_attr__('Поширити у %s', 'igrmed'), ucfirst($link['name'])); ?>">
                            <?php echo igrmed_get_svg($link['icon']); ?>
                        </a>
                    <?php endforeach; ?>

                    <button type="button"
                        class="blog-share__link blog-share__link--copy js-copy-link"
                        data-url="<?php echo esc_url(get_permalink()); ?>"
                        aria-label="<?php esc_attr_e('Копіювати посилання', 'igrmed'); ?>">
                        <?php echo igrmed_get_svg('copy-link-icon'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>