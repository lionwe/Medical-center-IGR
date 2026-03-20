<?php

/**
 * Custom Post Types Registration
 *
 * Project: IGR Medical Center
 * Registered CPTs:
 * 1. Directions (Напрямки - slug: services)  → taxonomy: service_category
 * 2. Price Items (Послуги - slug: price_items)
 * 3. Doctors    (Лікарі)                    → taxonomy: doctor_specialty
 * 4. Blog       (Блог)                      → taxonomy: blog_category
 */

if (!defined('ABSPATH')) {
    exit;
}

function igrmed_register_cpts(): void
{
    // --------------------------------------------------------
    // 1. Directions (Напрямки - колишні Services)
    // --------------------------------------------------------
    $labels_directions = [
        'name' => _x('Напрямки', 'Post Type General Name', 'igrmed'),
        'singular_name' => _x('Напрямок', 'Post Type Singular Name', 'igrmed'),
        'menu_name' => __('Напрямки', 'igrmed'),
        'all_items' => __('Всі напрямки', 'igrmed'),
        'add_new' => __('Додати напрямок', 'igrmed'),
        'add_new_item' => __('Додати новий напрямок', 'igrmed'),
        'edit_item' => __('Редагувати напрямок', 'igrmed'),
        'view_item' => __('Переглянути напрямок', 'igrmed'),
    ];

    register_post_type('services', [
        'labels' => $labels_directions,
        'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'revisions', 'page-attributes'],
        'taxonomies' => ['service_category'],
        'public' => true,
        'menu_position' => 4,
        'menu_icon' => 'dashicons-category',
        'has_archive' => true,
        'rewrite' => ['slug' => 'directions', 'with_front' => false],
        'show_in_rest' => false,
    ]);

    // --------------------------------------------------------
    // 2. Price Items (Послуги для прайсу)
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
        'menu_position' => 4,
        'menu_icon' => 'dashicons-money-alt',
        'has_archive' => false,
        'rewrite' => ['slug' => 'price-item', 'with_front' => false],
        'show_in_rest' => false,
    ]);

    // --------------------------------------------------------
    // 3. Doctors (Лікарі)
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
        'menu_position' => 5,
        'menu_icon' => 'dashicons-groups',
        'has_archive' => true,
        'rewrite' => ['slug' => 'doctors', 'with_front' => false],
    ]);

    // --------------------------------------------------------
    // 4. Blog (Блог)
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
        'menu_position' => 6,
        'menu_icon' => 'dashicons-welcome-write-blog',
        'has_archive' => true,
        'rewrite' => ['slug' => 'blog', 'with_front' => false],
    ]);
}

add_action('init', 'igrmed_register_cpts');

function igrmed_register_taxonomies(): void
{
    // service_category → Directions (services)
    register_taxonomy('service_category', 'services', [
        'labels' => [
            'name' => __('Категорії напрямків', 'igrmed'),
            'singular_name' => __('Категорія напрямку', 'igrmed'),
        ],
        'hierarchical' => true,
        'public' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'rewrite' => ['slug' => 'service-category', 'with_front' => false],
    ]);

    // doctor_specialty → Doctors
    register_taxonomy('doctor_specialty', 'doctors', [
        'labels' => [
            'name' => __('Спеціальності', 'igrmed'),
        ],
        'hierarchical' => true,
        'public' => true,
        'show_ui' => true,
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
        'show_admin_column' => true,
    ]);
}

add_action('init', 'igrmed_register_taxonomies');
