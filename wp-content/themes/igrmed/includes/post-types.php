<?php

/**
 * Custom Post Types Registration
 *
 * Project: IGR Medical Center
 * Registered CPTs:
 * 1. Price Items (Послуги - slug: price_items)
 * 2. Doctors    (Лікарі)                    → taxonomy: doctor_specialty
 * 3. Blog       (Блог)                      → taxonomy: blog_category
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register all taxonomies first
 */
function igrmed_register_taxonomies(): void
{

    // doctor_specialty → Doctors
    register_taxonomy('doctor_specialty', 'doctors', [
        'labels' => [
            'name' => __('Спеціальності', 'igrmed'),
        ],
        'hierarchical' => true,
        'public' => true,
        'show_ui' => true,
        'show_in_nav_menus' => true,
        'show_admin_column' => true,
    ]);

    // blog_category → Blog
    register_taxonomy('blog_category', 'blog', [
        'labels' => [
            'name' => __('Рубрики блогу', 'igrmed'),
        ],
        'hierarchical' => true,
        'public' => true,
        'show_ui' => true,
        'show_in_nav_menus' => true,
        'show_admin_column' => true,
    ]);
}
add_action('init', 'igrmed_register_taxonomies', 5);

/**
 * Register all post types
 */
function igrmed_register_cpts(): void
{
    // --------------------------------------------------------
    // 1. Price Items (Послуги для прайсу)
    // --------------------------------------------------------
    $labels_price_items = [
        'name' => _x('Послуги (Прайс)', 'Post Type General Name', 'igrmed'),
        'singular_name' => _x('Послуга', 'Post Type Singular Name', 'igrmed'),
        'menu_name' => __('Послуги (Прайс)', 'igrmed'),
        'all_items' => __('Всі послуги', 'igrmed'),
        'add_new' => __('Додати послугу', 'igrmed'),
        'add_new_item' => __('Додати нову послугу', 'igrmed'),
        'edit_item' => __('Редагувати послугу', 'igrmed'),
    ];

    register_post_type('price_items', [
        'labels' => $labels_price_items,
        'supports' => ['title', 'page-attributes'],
        'public' => true,
        'show_in_nav_menus' => true,
        'menu_position' => 5,
        'menu_icon' => 'dashicons-money-alt',
        'has_archive' => false,
        'rewrite' => ['slug' => 'price-item', 'with_front' => false],
        'show_in_rest' => false,
    ]);

    // --------------------------------------------------------
    // 2. Doctors (Лікарі)
    // --------------------------------------------------------
    $labels_doctors = [
        'name' => _x('Лікарі', 'Post Type General Name', 'igrmed'),
        'singular_name' => _x('Лікар', 'Post Type Singular Name', 'igrmed'),
        'menu_name' => __('Лікарі', 'igrmed'),
    ];

    register_post_type('doctors', [
        'labels' => $labels_doctors,
        'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'revisions'],
        'taxonomies' => ['doctor_specialty'],
        'public' => true,
        'show_in_nav_menus' => true,
        'menu_position' => 6,
        'menu_icon' => 'dashicons-groups',
        'has_archive' => true,
        'rewrite' => ['slug' => 'doctors', 'with_front' => false],
    ]);

    // --------------------------------------------------------
    // 3. Blog (Блог)
    // --------------------------------------------------------
    $labels_blog = [
        'name' => _x('Блог', 'Post Type General Name', 'igrmed'),
        'singular_name' => _x('Стаття', 'Post Type Singular Name', 'igrmed'),
        'menu_name' => __('Блог', 'igrmed'),
    ];

    register_post_type('blog', [
        'labels' => $labels_blog,
        'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'revisions', 'author'],
        'taxonomies' => ['blog_category'],
        'public' => true,
        'show_in_nav_menus' => true,
        'menu_position' => 7,
        'menu_icon' => 'dashicons-welcome-write-blog',
        'has_archive' => true,
        'rewrite' => ['slug' => 'blog', 'with_front' => false],
    ]);
}
add_action('init', 'igrmed_register_cpts', 10);
