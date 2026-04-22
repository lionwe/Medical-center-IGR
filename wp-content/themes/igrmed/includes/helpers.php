<?php

/**
 * Theme Helper Functions
 *
 * @package IGRMed
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get full price data structure: Category -> Direction -> Price Items
 *
 * @return array
 */
function igrmed_get_price_data(): array
{
    $categories = get_terms([
        'taxonomy'   => 'service_category',
        'hide_empty' => true,
    ]);

    $full_price_data = [];

    if (!empty($categories) && !is_wp_error($categories)) {
        foreach ($categories as $cat) {
            $cat_data = [
                'category_id'   => $cat->term_id,
                'category_name' => $cat->name,
                'directions'    => []
            ];

            // Get all Directions (Post Type: services) in this category
            $directions_query = new WP_Query([
                'post_type'      => 'services',
                'posts_per_page' => -1,
                'tax_query'      => [
                    [
                        'taxonomy' => 'service_category',
                        'field'    => 'term_id',
                        'terms'    => $cat->term_id,
                    ],
                ],
                'orderby'        => 'menu_order',
                'order'          => 'ASC',
                'no_found_rows'  => true,
                'update_post_meta_cache' => false,
                'update_post_term_cache' => false,
            ]);

            if ($directions_query->have_posts()) {
                while ($directions_query->have_posts()) {
                    $directions_query->the_post();
                    $direction_id = get_the_ID();
                    $direction_title = get_the_title();

                    // Get all Price Items (Post Type: price_items) for this direction
                    $price_items_query = new WP_Query([
                        'post_type'      => 'price_items',
                        'posts_per_page' => -1,
                        'meta_query'     => [
                            [
                                'key'     => 'price_item_direction',
                                'value'   => $direction_id,
                                'compare' => '=',
                            ],
                        ],
                        'orderby'        => 'menu_order',
                        'order'          => 'ASC',
                        'no_found_rows'  => true,
                    ]);

                    if ($price_items_query->have_posts()) {
                        $items = [];
                        while ($price_items_query->have_posts()) {
                            $price_items_query->the_post();
                            $price = get_field('price_item_value');
                            if ($price) {
                                $items[] = [
                                    'title' => get_the_title(),
                                    'price' => $price,
                                ];
                            }
                        }
                        if (!empty($items)) {
                            $cat_data['directions'][] = [
                                'name'  => $direction_title,
                                'items' => $items,
                            ];
                        }
                    }
                    wp_reset_postdata();
                }
            }
            wp_reset_postdata();

            if (!empty($cat_data['directions'])) {
                $full_price_data[] = $cat_data;
            }
        }
    }

    return $full_price_data;
}

/**
 * Get Google Reviews Widget data by feed ID
 *
 * @param int $feed_id
 * @return array|null
 */
function get_grw_data($feed_id)
{
    if (!class_exists('WP_Rplg_Google_Reviews\Includes\Core\Core')) {
        return null;
    }

    if (!class_exists('WP_Rplg_Google_Reviews\Includes\Feed_Deserializer')) {
        return null;
    }

    $feed_deserializer = new \WP_Rplg_Google_Reviews\Includes\Feed_Deserializer(new \WP_Query());
    $feed = $feed_deserializer->get_feed($feed_id);

    if (!$feed) {
        return null;
    }

    $core = new \WP_Rplg_Google_Reviews\Includes\Core\Core();
    $data = $core->get_reviews($feed);

    return $data;
}
