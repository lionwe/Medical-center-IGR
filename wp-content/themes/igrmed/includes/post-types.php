<?php

/**
 * Custom Post Types Registration
 *
 * Project: IGR Medical Center
 * Registered CPTs:
 * 1. Services  (Послуги)  → taxonomy: service_category
 * 2. Doctors   (Лікарі)  → taxonomy: doctor_specialty
 * 3. Blog      (Блог)    → taxonomy: blog_category
 */

if (!defined('ABSPATH')) {
    exit;
}

// ============================================================
// Register all CPTs
// ============================================================

function igrmed_register_cpts(): void
{

    // --------------------------------------------------------
    // 1. Services (Послуги)
    // --------------------------------------------------------
    $labels_services = [
        'name' => _x('Послуги', 'Post Type General Name', 'igrmed'),
        'singular_name' => _x('Послуга', 'Post Type Singular Name', 'igrmed'),
        'menu_name' => __('Послуги', 'igrmed'),
        'name_admin_bar' => __('Послуга', 'igrmed'),
        'add_new' => __('Додати', 'igrmed'),
        'add_new_item' => __('Додати нову послугу', 'igrmed'),
        'new_item' => __('Нова послуга', 'igrmed'),
        'edit_item' => __('Редагувати послугу', 'igrmed'),
        'view_item' => __('Переглянути послугу', 'igrmed'),
        'all_items' => __('Всі послуги', 'igrmed'),
        'search_items' => __('Пошук послуг', 'igrmed'),
        'not_found' => __('Послуг не знайдено', 'igrmed'),
        'not_found_in_trash' => __('Послуг не знайдено у кошику', 'igrmed'),
        'featured_image' => __('Зображення послуги', 'igrmed'),
        'set_featured_image' => __('Встановити зображення', 'igrmed'),
        'remove_featured_image' => __('Видалити зображення', 'igrmed'),
        'archives' => __('Архів послуг', 'igrmed'),
    ];

    register_post_type('services', [
        'label' => __('Послуги', 'igrmed'),
        'description' => __('Медичні послуги клініки', 'igrmed'),
        'labels' => $labels_services,
        'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'revisions', 'custom-fields', 'page-attributes'],
        'taxonomies' => ['service_category'],
        'hierarchical' => false,
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => 4,
        'menu_icon' => 'dashicons-clipboard',
        'show_in_admin_bar' => true,
        'show_in_nav_menus' => true,
        'can_export' => true,
        'has_archive' => true,
        'exclude_from_search' => false,
        'publicly_queryable' => true,
        'capability_type' => 'post',
        'rewrite' => ['slug' => 'services', 'with_front' => false],
        'show_in_rest' => false,
    ]);

    // --------------------------------------------------------
    // 2. Doctors (Лікарі)
    // --------------------------------------------------------
    $labels_doctors = [
        'name' => _x('Лікарі', 'Post Type General Name', 'igrmed'),
        'singular_name' => _x('Лікар', 'Post Type Singular Name', 'igrmed'),
        'menu_name' => __('Лікарі', 'igrmed'),
        'name_admin_bar' => __('Лікар', 'igrmed'),
        'add_new' => __('Додати', 'igrmed'),
        'add_new_item' => __('Додати нового лікаря', 'igrmed'),
        'new_item' => __('Новий лікар', 'igrmed'),
        'edit_item' => __('Редагувати лікаря', 'igrmed'),
        'view_item' => __('Переглянути лікаря', 'igrmed'),
        'all_items' => __('Всі лікарі', 'igrmed'),
        'search_items' => __('Пошук лікарів', 'igrmed'),
        'not_found' => __('Лікарів не знайдено', 'igrmed'),
        'not_found_in_trash' => __('Лікарів не знайдено у кошику', 'igrmed'),
        'featured_image' => __('Фото лікаря', 'igrmed'),
        'set_featured_image' => __('Встановити фото', 'igrmed'),
        'remove_featured_image' => __('Видалити фото', 'igrmed'),
        'archives' => __('Архів лікарів', 'igrmed'),
    ];

    register_post_type('doctors', [
        'label' => __('Лікарі', 'igrmed'),
        'description' => __('Лікарі та спеціалісти клініки', 'igrmed'),
        'labels' => $labels_doctors,
        'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'revisions', 'custom-fields'],
        'taxonomies' => ['doctor_specialty'],
        'hierarchical' => false,
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => 5,
        'menu_icon' => 'dashicons-groups',
        'show_in_admin_bar' => true,
        'show_in_nav_menus' => true,
        'can_export' => true,
        'has_archive' => true,
        'exclude_from_search' => false,
        'publicly_queryable' => true,
        'capability_type' => 'post',
        'rewrite' => ['slug' => 'doctors', 'with_front' => false],
        'show_in_rest' => false,
    ]);

    // --------------------------------------------------------
    // 3. Blog (Блог)
    // --------------------------------------------------------
    $labels_blog = [
        'name' => _x('Блог', 'Post Type General Name', 'igrmed'),
        'singular_name' => _x('Стаття', 'Post Type Singular Name', 'igrmed'),
        'menu_name' => __('Блог', 'igrmed'),
        'name_admin_bar' => __('Стаття', 'igrmed'),
        'add_new' => __('Додати', 'igrmed'),
        'add_new_item' => __('Додати нову статтю', 'igrmed'),
        'new_item' => __('Нова стаття', 'igrmed'),
        'edit_item' => __('Редагувати статтю', 'igrmed'),
        'view_item' => __('Переглянути статтю', 'igrmed'),
        'all_items' => __('Всі статті', 'igrmed'),
        'search_items' => __('Пошук статей', 'igrmed'),
        'not_found' => __('Статей не знайдено', 'igrmed'),
        'not_found_in_trash' => __('Статей не знайдено у кошику', 'igrmed'),
        'featured_image' => __('Обкладинка статті', 'igrmed'),
        'set_featured_image' => __('Встановити обкладинку', 'igrmed'),
        'remove_featured_image' => __('Видалити обкладинку', 'igrmed'),
        'archives' => __('Архів блогу', 'igrmed'),
    ];

    register_post_type('blog', [
        'label' => __('Блог', 'igrmed'),
        'description' => __('Статті та новини клініки', 'igrmed'),
        'labels' => $labels_blog,
        'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'revisions', 'custom-fields', 'author'],
        'taxonomies' => ['blog_category'],
        'hierarchical' => false,
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => 6,
        'menu_icon' => 'dashicons-welcome-write-blog',
        'show_in_admin_bar' => true,
        'show_in_nav_menus' => true,
        'can_export' => true,
        'has_archive' => true,
        'exclude_from_search' => false,
        'publicly_queryable' => true,
        'capability_type' => 'post',
        'rewrite' => ['slug' => 'blog', 'with_front' => false],
        'show_in_rest' => false,
    ]);
}

add_action('init', 'igrmed_register_cpts');

// ============================================================
// Register custom taxonomies
// ============================================================

function igrmed_register_taxonomies(): void
{

    // --------------------------------------------------------
    // service_category → Services
    // --------------------------------------------------------
    register_taxonomy('service_category', 'services', [
        'labels' => [
            'name' => _x('Категорії послуг', 'Taxonomy General Name', 'igrmed'),
            'singular_name' => _x('Категорія послуги', 'Taxonomy Singular Name', 'igrmed'),
            'menu_name' => __('Категорії', 'igrmed'),
            'all_items' => __('Всі категорії', 'igrmed'),
            'edit_item' => __('Редагувати категорію', 'igrmed'),
            'add_new_item' => __('Додати категорію', 'igrmed'),
            'new_item_name' => __('Нова категорія', 'igrmed'),
            'search_items' => __('Пошук категорій', 'igrmed'),
            'not_found' => __('Категорій не знайдено', 'igrmed'),
        ],
        'hierarchical' => true,
        'public' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'show_in_nav_menus' => true,
        'rewrite' => ['slug' => 'service-category', 'with_front' => false],
        'show_in_rest' => false,
    ]);

    // --------------------------------------------------------
    // doctor_specialty → Doctors
    // --------------------------------------------------------
    register_taxonomy('doctor_specialty', 'doctors', [
        'labels' => [
            'name' => _x('Спеціальності', 'Taxonomy General Name', 'igrmed'),
            'singular_name' => _x('Спеціальність', 'Taxonomy Singular Name', 'igrmed'),
            'menu_name' => __('Спеціальності', 'igrmed'),
            'all_items' => __('Всі спеціальності', 'igrmed'),
            'edit_item' => __('Редагувати спеціальність', 'igrmed'),
            'add_new_item' => __('Додати спеціальність', 'igrmed'),
            'new_item_name' => __('Нова спеціальність', 'igrmed'),
            'search_items' => __('Пошук спеціальностей', 'igrmed'),
            'not_found' => __('Спеціальностей не знайдено', 'igrmed'),
        ],
        'hierarchical' => true,
        'public' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'show_in_nav_menus' => true,
        'rewrite' => ['slug' => 'doctor-specialty', 'with_front' => false],
        'show_in_rest' => false,
    ]);

    // --------------------------------------------------------
    // blog_category → Blog
    // --------------------------------------------------------
    register_taxonomy('blog_category', 'blog', [
        'labels' => [
            'name' => _x('Рубрики блогу', 'Taxonomy General Name', 'igrmed'),
            'singular_name' => _x('Рубрика', 'Taxonomy Singular Name', 'igrmed'),
            'menu_name' => __('Рубрики', 'igrmed'),
            'all_items' => __('Всі рубрики', 'igrmed'),
            'edit_item' => __('Редагувати рубрику', 'igrmed'),
            'add_new_item' => __('Додати рубрику', 'igrmed'),
            'new_item_name' => __('Нова рубрика', 'igrmed'),
            'search_items' => __('Пошук рубрик', 'igrmed'),
            'not_found' => __('Рубрик не знайдено', 'igrmed'),
        ],
        'hierarchical' => true,
        'public' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'show_in_nav_menus' => true,
        'rewrite' => ['slug' => 'blog-category', 'with_front' => false],
        'show_in_rest' => false,
    ]);
}

add_action('init', 'igrmed_register_taxonomies');

// ============================================================
// Flush rewrite rules on theme activation
// ============================================================

add_action('after_switch_theme', static function (): void {
    igrmed_register_cpts();
    igrmed_register_taxonomies();
    flush_rewrite_rules();
});
