<?php

/**
 * Price List Section
 *
 * @package IGRMed
 */

$price_list = get_field('price_list');

$full_price_data = [];
if (!empty($price_list)) {
    foreach ($price_list as $category_index => $category) {
        if (empty($category['title']) || empty($category['items'])) {
            continue;
        }

        $category_data = [
            'category_id'   => $category_index + 1,
            'category_name' => $category['title'],
            'directions'    => []
        ];

        $directions = [];
        foreach ($category['items'] as $item) {
            if (empty($item['service_name']) || empty($item['price'])) {
                continue;
            }

            $directions[] = [
                'name'  => $item['service_name'],
                'items' => [
                    [
                        'title' => $item['service_name'],
                        'price' => $item['price'],
                    ]
                ],
            ];
        }

        if (!empty($directions)) {
            $category_data['directions'] = $directions;
            $full_price_data[] = $category_data;
        }
    }
}

// Empty Fields Rule: If no price data, the section is not rendered at all.
if (empty($full_price_data)) {
    return;
}

$search_icon = get_field('icon_search', 'option');
?>

<section id="price-list" class="price-list">
    <div class="container">
        <div class="price-list__wrapper">
            <header class="price-list__header">
                <div class="price-list__search">
                    <input type="text" class="price-list__search-input" placeholder="<?php echo esc_attr(igrmed__('search_placeholder')); ?>">
                    <?php if ($search_icon && isset($search_icon['url'])): ?>
                        <span class="price-list__search-icon">
                            <?php
                            get_picture([
                                'src' => $search_icon['url'],
                                'alt' => igrmed__('search_placeholder'),
                                'lazy' => true
                            ]);
                            ?>
                        </span>
                    <?php endif; ?>
                </div>
            </header>

            <div class="price-list__content js-price-list-content">
                <div class="price-list__empty js-price-empty" style="display: none;">
                    <?php echo esc_html(igrmed__('search_no_results')); ?>
                </div>
                <?php
                $accordion_count = 0;
                foreach ($full_price_data as $category):
                ?>
                    <div class="price-list__category-group" data-category-id="<?php echo esc_attr($category['category_id']); ?>">
                        <?php foreach ($category['directions'] as $direction): ?>
                            <?php
                            $is_first = ($accordion_count === 0);
                            $accordion_count++;
                            ?>
                            <div class="price-list__direction-accordion js-price-accordion <?php echo $is_first ? 'is-open' : ''; ?>">
                                <button type="button" class="price-list__direction-trigger js-price-accordion-trigger" aria-expanded="<?php echo $is_first ? 'true' : 'false'; ?>">
                                    <span class="price-list__direction-name"><?php echo esc_html($direction['name']); ?></span>
                                    <div class="price-list__direction-icon-wrap">
                                        <?php echo igrmed_get_svg('triangle'); ?>
                                    </div>
                                </button>

                                <div class="accordeon js-price-accordion-accordeon" <?php echo $is_first ? 'open style="display: block;"' : 'style="display: none;"'; ?>>
                                    <div class="content">
                                        <div class="price-list__direction-content-inner">
                                            <div class="price-list__items">
                                                <?php foreach ($direction['items'] as $item): ?>
                                                    <div class="price-list__item-container">
                                                        <span class="price-list__item-name"><?php echo esc_html($item['title']); ?></span>
                                                        <span class="price-list__item-value">
                                                            <?php echo esc_html($item['price']); ?>
                                                            <?php echo esc_html(igrmed__('pricing_currency')); ?>
                                                        </span>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>