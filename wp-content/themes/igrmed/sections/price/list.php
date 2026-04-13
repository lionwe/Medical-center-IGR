<?php

/**
 * Price List Section
 *
 * @package IGRMed
 */

$full_price_data = igrmed_get_price_data();

// Empty Fields Rule: If no price data, the section is not rendered at all.
if (empty($full_price_data)) {
    return;
}

$categories = get_terms([
    'taxonomy'   => 'service_category',
    'hide_empty' => true,
]);

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

                <div class="price-list__category-select js-price-category" data-selected-category="all">
                    <div class="price-list__category-trigger js-price-category-trigger">
                        <span class="price-list__category-label"><?php echo esc_html(igrmed__('services_all')); ?></span>
                        <div class="price-list__category-arrow-wrap">
                            <?php echo igrmed_get_svg('chevron-white'); ?>
                        </div>
                    </div>

                    <?php if (!empty($categories) && !is_wp_error($categories)): ?>
                        <div class="price-list__dropdown js-price-dropdown">
                            <ul class="price-list__dropdown-list">
                                <li class="price-list__dropdown-item">
                                    <button type="button" class="price-list__dropdown-link" data-category-id="all">
                                        <?php echo esc_html(igrmed__('services_all')); ?>
                                    </button>
                                    <div class="price-list__dropdown-separator"></div>
                                </li>
                                <?php foreach ($categories as $index => $cat): ?>
                                    <?php
                                    // Rule 3: Use Polylang-compatible function if available, though get_terms result usually contains correct translation already if Polylang is active and set up.
                                    $cat_id = $cat->term_id;
                                    ?>
                                    <li class="price-list__dropdown-item">
                                        <button type="button" class="price-list__dropdown-link" data-category-id="<?php echo esc_attr($cat_id); ?>">
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