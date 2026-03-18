<?php

/**
 * Section: Our Services (Наші послуги)
 * Location: Home page
 * ACF Fields: home_services_title, home_services_posts
 */

$home_page_id = getHomePageID();
$title = get_field('home_services_title', $home_page_id);
$posts = get_field('home_services_posts', $home_page_id);

if (!$title && !$posts) {
    return;
}

?>
<section class="services-section">
    <div class="container">

        <?php if ($title) : ?>
            <div class="services-section__header">
                <h2><?php echo esc_html($title); ?></h2>
            </div>
        <?php endif; ?>

        <?php if ($posts) : ?>
            <div class="services-section__grid">
                <?php foreach ($posts as $post) :
                    setup_postdata($post);
                    get_template_part('templates/services-card', null, ['post' => $post]);
                endforeach;
                wp_reset_postdata();

                // Show CTA card only if posts count is 10 or less
                if (count($posts) <= 10) :
                    $cta_text = get_field('home_services_cta_text', $home_page_id);
                    $cta_button = get_field('home_services_cta_button', $home_page_id);

                    if ($cta_text || ($cta_button && !empty($cta_button['url']))) :
                        $cta_button_url = $cta_button['url'] ?? '';
                        $cta_button_title = $cta_button['title'] ?? __("Зв'язатись з нами", 'igrmed');
                        $cta_button_target = $cta_button['target'] ?? '_self';
                ?>
                        <div class="service-card service-card--cta" <?php echo $cta_button_url ? 'data-href="' . esc_url($cta_button_url) . '"' : ''; ?>>
                            <div class="service-card--cta__content">
                                <?php if ($cta_text) : ?>
                                    <h3 class="service-card--cta__title"><?php echo wp_kses_post($cta_text); ?></h3>
                                <?php endif; ?>

                                <?php if ($cta_button_url !== '') : ?>
                                    <div class="service-card--cta__actions">
                                        <?php
                                        get_template_part('templates/button', null, [
                                            'text' => $cta_button_title,
                                            'link' => $cta_button_url,
                                            'type' => 'primary',
                                            'primary_split' => true,
                                            'icon_url' => get_template_directory_uri() . '/assets/img/svg/contact-arrow.svg',
                                            'target' => $cta_button_target,
                                            'class' => 'btn-split--dark'
                                        ]);
                                        ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                <?php endif;
                endif; ?>
            </div>
        <?php endif; ?>

    </div>
</section>
<script>
    (function() {
        var cards = document.querySelectorAll('.service-card[data-href]');

        function getAnchorTarget(href) {
            if (!href) return null;
            var hashIndex = href.indexOf('#');
            if (hashIndex === -1) return null;
            
            var hash = href.substring(hashIndex);
            if (hash === '#') return null;

            // Check if it's the same page
            var path = href.substring(0, hashIndex);
            var currentPath = window.location.origin + window.location.pathname;
            var currentRelPath = window.location.pathname;
            
            if (path === '' || path === currentPath || path === currentRelPath || href.startsWith('#')) {
                return document.querySelector(hash);
            }
            return null;
        }

        function smoothScrollTo(target, hash, e) {
            if (!target) return false;
            if (e) e.preventDefault();

            var header = document.querySelector('.header');
            var headerOffset = header ? header.offsetHeight : 0;
            var elementPosition = target.getBoundingClientRect().top;
            var offsetPosition = elementPosition + window.pageYOffset - headerOffset;

            window.scrollTo({
                top: offsetPosition,
                behavior: 'smooth'
            });

            if (hash) {
                history.pushState(null, null, hash);
            }
            return true;
        }

        cards.forEach(function(card) {
            card.addEventListener('click', function(e) {
                if (e.target.closest('a') || e.target.closest('button')) {
                    return;
                }

                var href = card.dataset.href;
                var target = getAnchorTarget(href);
                
                if (target) {
                    var hash = href.substring(href.indexOf('#'));
                    smoothScrollTo(target, hash, e);
                } else if (href) {
                    window.location.href = href;
                }
            });
        });

        // Specifically handle the CTA button
        var ctaButtons = document.querySelectorAll('.service-card--cta .btn-split');
        ctaButtons.forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                var href = btn.getAttribute('href');
                var target = getAnchorTarget(href);
                if (target) {
                    var hash = href.substring(href.indexOf('#'));
                    smoothScrollTo(target, hash, e);
                }
            });
        });
    }());
</script>