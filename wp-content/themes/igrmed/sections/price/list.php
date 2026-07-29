<?php

/**
 * Price List Section
 *
 * @package IGRMed
 */

// Use Advanced Custom Fields to get price list data
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
        $category_items = [];
        
        foreach ($category['items'] as $item) {
            if (empty($item['service_name']) || empty($item['price'])) {
                continue;
            }

            $category_items[] = [
                'title' => $item['service_name'],
                'price' => $item['price'],
            ];
        }
        
        // Create a single direction for the category with all items
        if (!empty($category_items)) {
            $directions[] = [
                'name'  => $category['title'], // Use category title as direction name
                'items' => $category_items,
            ];
        }

        if (!empty($directions)) {
            $category_data['directions'] = $directions;
            $full_price_data[] = $category_data;
        }
    }
}


// Empty Fields Rule: If no price data, show a message instead of not rendering
if (empty($full_price_data)) {
    ?>
    <section id="price-list" class="price-list">
        <div class="container">
            <div class="price-list__wrapper">
                <div class="price-list__content">
                    <div class="price-list__empty">
                        <?php echo esc_html(igrmed__('no_price_data_available') ?: 'На даний момент прайс-лист недоступний. Будь ласка, зверніться до адміністратора.'); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php
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

                                <div class="accordeon js-price-accordion-accordeon" <?php if ($is_first) echo 'open style="display: block;"'; else echo 'style="display: none;"'; ?>>
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