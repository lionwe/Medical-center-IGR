<?php
/**
 * Price List Section
 *
 * @package IGRMed
 */

$search_icon = get_field('icon_search', 'option');

// Fetch real categories from WordPress taxonomy
$categories = get_terms([
    'taxonomy'   => 'service_category',
    'hide_empty' => true,
]);

/**
 * Preparing the full price data for initial load
 * (Later this can be used for the AJAX logic too)
 */
$full_price_data = [];

if (!empty($categories) && !is_wp_error($categories)) {
    foreach ($categories as $cat) {
        $services_query = new WP_Query([
            'post_type'      => 'services',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
            'tax_query'      => [
                [
                    'taxonomy' => 'service_category',
                    'field'    => 'term_id',
                    'terms'    => $cat->term_id,
                ],
            ],
            'meta_query'     => [
                [
                    'key'     => 'service_show_in_price',
                    'value'   => '1',
                    'compare' => '=',
                ],
            ],
            'orderby'        => 'menu_order',
            'order'          => 'ASC',
        ]);

        if ($services_query->have_posts()) {
            $cat_services = [];
            while ($services_query->have_posts()) {
                $services_query->the_post();
                $price = get_field('service_price');
                if ($price) {
                    $cat_services[] = [
                        'title' => get_the_title(),
                        'price' => $price,
                    ];
                }
            }
            if (!empty($cat_services)) {
                $full_price_data[] = [
                    'category_id'   => $cat->term_id,
                    'category_name' => $cat->name,
                    'items'         => $cat_services,
                ];
            }
        }
        wp_reset_postdata();
    }
}
?>

<section class="price-list">
    <div class="container">
        <div class="price-list__wrapper">
            <header class="price-list__header">
                <div class="price-list__search">
                    <input type="text" class="price-list__search-input" placeholder="<?php esc_attr_e('Пошук', 'igrmed'); ?>">
                    <?php if ($search_icon && isset($search_icon['url'])): ?>
                        <span class="price-list__search-icon">
                            <img src="<?php echo esc_url($search_icon['url']); ?>" alt="Search" width="11" height="11">
                        </span>
                    <?php endif; ?>
                </div>

                <div class="price-list__category-select js-price-category">
                    <div class="price-list__category-trigger js-price-category-trigger">
                        <span class="price-list__category-label"><?php esc_html_e('Категорія послуг', 'igrmed'); ?></span>
                        <div class="price-list__category-arrow-wrap">
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/img/svg/chevron-white.svg" alt="Arrow" width="10" height="7">
                        </div>
                    </div>

                    <?php if (!empty($categories) && !is_wp_error($categories)): ?>
                        <div class="price-list__dropdown js-price-dropdown">
                            <ul class="price-list__dropdown-list">
                                <?php foreach ($categories as $index => $cat): ?>
                                    <li class="price-list__dropdown-item">
                                        <button type="button" class="price-list__dropdown-link" data-category-id="<?php echo esc_attr($cat->term_id); ?>">
                                            <?php echo esc_html($cat->name); ?>
                                        </button>
                                        <?php if ($index < count($categories) - 1): ?>
                                            <div class="price-list__dropdown-separator"></div>
                                        <?php endif; ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                </div>
            </header>

            <div class="price-list__content js-price-list-content">
                <?php if (!empty($full_price_data)): ?>
                    <div class="price-list__categories">
                        <?php foreach ($full_price_data as $category): ?>
                            <div class="price-list__category-block" data-category-id="<?php echo esc_attr($category['category_id']); ?>">
                                <h2 class="price-list__category-title"><?php echo esc_html($category['category_name']); ?></h2>
                                <div class="price-list__items">
                                    <?php foreach ($category['items'] as $item): ?>
                                        <div class="price-list__item">
                                            <span class="price-list__item-name"><?php echo esc_html($item['title']); ?></span>
                                            <div class="price-list__item-dots"></div>
                                            <span class="price-list__item-value"><?php echo esc_html($item['price']); ?> <?php _e('грн', 'igrmed'); ?></span>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="price-list__empty"><?php esc_html_e('На жаль, за вашим запитом послуг не знайдено.', 'igrmed'); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
