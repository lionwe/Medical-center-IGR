<?php

/**
 * Section: Our Services (Наші послуги)
 * Location: Home page
 * ACF Fields: home_services_title, home_services_posts
 */

$title = get_field('home_services_title');
$posts = get_field('home_services_posts');

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
                ?>
            </div>
        <?php endif; ?>

    </div>
</section>

<script>
    (function() {
        var cards = document.querySelectorAll('.service-card[data-href]');
        cards.forEach(function(card) {
            card.addEventListener('click', function(e) {
                // Skip if the actual link or its children were clicked directly
                var link = card.querySelector('.service-card__link');
                if (link && (e.target === link || link.contains(e.target))) {
                    return;
                }
                window.location.href = card.dataset.href;
            });
        });
    }());
</script>