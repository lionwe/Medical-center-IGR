<?php
add_action('wp_enqueue_scripts', 'igrmed_enqueue_assets');
add_action('after_setup_theme', 'igrmed_theme_setup');
add_filter('upload_mimes', 'svg_upload_allow');
add_filter('wp_check_filetype_and_ext', 'fix_svg_mime_type', 10, 5);
add_action('pre_get_posts', 'igrmed_blog_posts_per_page');
add_filter('script_loader_tag', 'igrmed_defer_scripts', 10, 2);
add_action('wp_head', 'add_font_preconnect', 1);

require get_template_directory() . '/includes/post-types.php';
require get_template_directory() . '/includes/ajax-handler.php';
require get_template_directory() . '/includes/helpers.php';
require get_template_directory() . '/includes/polylang-register-strings.php';

/**
 * Add font preconnect and preload for Google Fonts
 */
function add_font_preconnect() {
    echo '<link rel="preconnect" href="https://fonts.googleapis.com">';
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>';
    echo '<link rel="preload" href="https://fonts.googleapis.com/css2?family=Montserrat+Alternates:wght@500&family=Montserrat:wght@300;400;500;600;700&display=swap" as="style" onload="this.onload=null;this.rel=\'stylesheet\'">';
}

/**
 * Get SVG content from assets
 */
function igrmed_get_svg($name)
{
    $path = get_template_directory() . '/assets/img/svg/' . $name . '.svg';
    if (file_exists($path)) {
        return file_get_contents($path);
    }
    return '';
}

/**
 * Enqueue Main Assets & Vendors with cache busting
 */
function igrmed_enqueue_assets(): void
{
    $dist_path = get_template_directory() . '/dist';
    $dist_uri = get_template_directory_uri() . '/dist';

    // File paths for versioning
    $css_bundle = $dist_path . '/css/main.bundle.css';
    $js_bundle = $dist_path . '/js/main.bundle.js';
    $js_vendors = $dist_path . '/js/vendors-core.bundle.js';

    // Versioning based on file modified time
    $css_ver = file_exists($css_bundle) ? filemtime($css_bundle) : '1.0.0';
    $js_ver = file_exists($js_bundle) ? filemtime($js_bundle) : '1.0.0';
    $js_vendors_ver = file_exists($js_vendors) ? filemtime($js_vendors) : '1.0.0';

    wp_enqueue_style(
        'igrmed-google-fonts',
        'https://fonts.googleapis.com/css2?family=Montserrat+Alternates:wght@500&family=Montserrat:wght@300;400;500;600;700&display=swap',
        [],
        null
    );

    wp_enqueue_style(
        'igrmed-main-style',
        $dist_uri . '/css/main.bundle.css',
        ['igrmed-google-fonts'],
        $css_ver
    );

    // Enqueue core vendors first if they exist
    if (file_exists($js_vendors)) {
        wp_enqueue_script(
            'igrmed-vendors-core',
            $dist_uri . '/js/vendors-core.bundle.js',
            [],
            $js_vendors_ver,
            true
        );
    }

    wp_enqueue_script(
        'igrmed-main-js',
        $dist_uri . '/js/main.bundle.js',
        file_exists($js_vendors) ? ['igrmed-vendors-core'] : [],
        $js_ver,
        true
    );

    wp_localize_script('igrmed-main-js', 'params', [
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('ajax-nonce'),
        'template_directory_url' => get_template_directory_uri(),
        'current_lang' => function_exists('pll_current_language') ? pll_current_language() : '',
        'i18n' => [
            'loading' => igrmed__('loading'),
            'error_connection' => igrmed__('error_connection'),
            'error_loading' => igrmed__('error_loading'),
            'error_validation' => igrmed__('error_validation'),
            'error_required' => igrmed__('error_required'),
            'error_email' => igrmed__('error_email'),
            'error_phone' => igrmed__('error_phone'),
            'success' => igrmed__('success'),
            'processing' => igrmed__('processing'),
            'btn_send' => igrmed__('btn_send'),
            'btn_close' => igrmed__('btn_close'),
            'btn_cancel' => igrmed__('btn_cancel'),
            'form_success_message' => igrmed__('form_success_message'),
            'form_error_message' => igrmed__('form_error_message'),
            'search_placeholder' => igrmed__('search_placeholder'),
            'search_no_results' => igrmed__('search_no_results'),
            'search_results' => igrmed__('search_results'),
            'pagination_prev' => igrmed__('pagination_prev'),
            'pagination_next' => igrmed__('pagination_next'),
            'blog_load_more' => igrmed__('blog_load_more'),
            'reviews_thank_you' => igrmed__('reviews_thank_you'),
            'appointment_success' => igrmed__('appointment_success'),
        ],
    ]);
}

/**
 * Add DEFER attribute to theme scripts for better performance
 */
function igrmed_defer_scripts($tag, $handle)
{
    $scripts_to_defer = ['igrmed-main-js', 'igrmed-vendors-core'];

    if (in_array($handle, $scripts_to_defer)) {
        return str_replace(' src', ' defer src', $tag);
    }

    return $tag;
}

function igrmed_theme_setup(): void
{
    show_admin_bar(false);
    register_nav_menus([
        'menu-header' => 'Header',
        'menu-footer' => 'Footer',
    ]);

    add_theme_support('custom-logo');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
}

/**
 * Header menu: mark the “Напрямки” item for dropdown chevron (::after in CSS).
 */
add_filter(
    'nav_menu_css_class',
    static function (array $classes, $item, $args, int $depth): array {
        if ($depth !== 0 || !isset($args->theme_location) || $args->theme_location !== 'menu-header') {
            return $classes;
        }
        $title = isset($item->title) ? trim(wp_strip_all_tags($item->title)) : '';
        if (strcasecmp($title, 'Напрямки') === 0) {
            $classes[] = 'menu-item--nav-directions';
        }
        return $classes;
    },
    10,
    4
);

// ============================================
// ACF Options Page
// ============================================
add_action('acf/init', function () {
    if (function_exists('acf_add_options_page')) {
        acf_add_options_page(array(
            'page_title' => 'Налаштування сайту',
            'menu_title' => 'Налаштування',
            'menu_slug' => 'site-options',
            'capability' => 'manage_options',
            'redirect' => false,
            'position' => 65,
            'icon_url' => 'dashicons-admin-generic',
            'update_button' => __('Зберегти налаштування', 'igrmed'),
            'updated_message' => __('Налаштування оновлені', 'igrmed')
        ));
    }
});

// ============================================
// Helpers & SVG
// ============================================

function reading_time($content)
{
    $words_per_minute = 120;
    $clean_content = strip_tags($content);
    $word_count = preg_match_all('/[\p{L}\p{N}]+/u', $clean_content);
    $minutes = (int) ceil($word_count / $words_per_minute);
    return max(1, $minutes);
}

/**
 * Set posts per page on blog archive: 12 on desktop, 7 on mobile.
 */
function igrmed_blog_posts_per_page(WP_Query $query): void
{
    if (is_admin() || !$query->is_main_query()) {
        return;
    }

    if ($query->is_post_type_archive('blog')) {
        $posts_per_page = wp_is_mobile() ? 7 : 12;
        $query->set('posts_per_page', $posts_per_page);
    }
}

function get_picture($args = [])
{
    $defaults = [
        'name' => '',
        'src' => '',
        'alt' => '',
        'class' => '',
        'lazy' => true,
    ];
    $args = wp_parse_args($args, $defaults);

    $img_src = $args['src'];
    $is_asset = false;

    if (!empty($args['name'])) {
        $img_src = get_template_directory_uri() . "/assets/img/" . $args['name'];
        $is_asset = true;
    }

    if (empty($img_src))
        return;

    $alt = 'alt="' . esc_attr($args['alt']) . '"';
    $class = $args['class'] ? 'class="' . esc_attr($args['class']) . '"' : '';
    $loading = $args['lazy'] ? 'loading="lazy"' : '';

    // Check if it's an SVG file - render directly without picture wrapper
    $is_svg = strtolower(pathinfo($img_src, PATHINFO_EXTENSION)) === 'svg';

    if ($is_asset && !$is_svg) {
        $path_parts = pathinfo($args['name']);
        $webp_name = $path_parts['filename'] . '.webp';
        $webp_src = get_template_directory_uri() . "/assets/img/" . $webp_name;

        echo '<picture>';
        echo '<source srcset="' . esc_url($webp_src) . '" type="image/webp">';
        echo '<img src="' . esc_url($img_src) . '" ' . $alt . ' ' . $class . ' ' . $loading . '>';
        echo '</picture>';
    } else {
        echo '<img src="' . esc_url($img_src) . '" ' . $alt . ' ' . $class . ' ' . $loading . '>';
    }
}

function svg_upload_allow($mimes)
{
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}

function fix_svg_mime_type($data, $file, $filename, $mimes, $real_mime = '')
{
    if (version_compare($GLOBALS['wp_version'], '5.1.0', '>=')) {
        $dosvg = in_array($real_mime, ['image/svg', 'image/svg+xml']);
    } else {
        $dosvg = ('.svg' === strtolower(substr($filename, -4)));
    }

    if ($dosvg) {
        if (current_user_can('manage_options')) {
            $data['ext'] = 'svg';
            $data['type'] = 'image/svg+xml';
        } else {
            $data['ext'] = false;
            $data['type'] = false;
        }
    }
    return $data;
}

function getHomePageID()
{
    $default_home_id = get_option('page_on_front');

    if (function_exists('pll_current_language') && function_exists('pll_get_post')) {
        $current_lang = pll_current_language();
        $translated_home_id = pll_get_post($default_home_id, $current_lang);
        return $translated_home_id ? $translated_home_id : $default_home_id;
    }

    return $default_home_id;
}

// ============================================
// GUTENBERG DISABLE (NUCLEAR OPTION)
// ============================================

add_filter('use_block_editor_for_post', '__return_false', 10);
add_filter('use_block_editor_for_post_type', '__return_false', 10);
add_filter('use_widgets_block_editor', '__return_false');

add_action('wp_enqueue_scripts', function () {
    wp_dequeue_style('wp-block-library');
    wp_dequeue_style('wp-block-library-theme');
    wp_dequeue_style('wc-blocks-style');
    wp_dequeue_style('global-styles');
    wp_dequeue_style('classic-theme-styles');
}, 100);

remove_action('wp_body_open', 'wp_global_styles_render_svg_filters');
remove_action('in_admin_header', 'wp_global_styles_render_svg_filters');
add_filter('use_default_gallery_style', '__return_false');

// ============================================
// FIX: Output Buffering Zlib Conflict
// ============================================
remove_action('shutdown', 'wp_ob_end_flush_all', 1);
add_action('shutdown', function () {
    while (@ob_end_flush())
        ;
});