<?php
/**
 * Breadcrumbs template (IGRMED).
 * Only project entities:
 * - pages
 * - CPT: blog, services, doctors
 * - taxonomies: blog_category, service_category, doctor_specialty
 */

if (is_front_page()) {
    return;
}

global $post;
$crumbs = [];

$add_crumb = static function (string $title, ?string $url = null) use (&$crumbs): void {
    $title = trim($title);
    if ($title === '') {
        return;
    }

    $last = end($crumbs);
    if (is_array($last) && ($last['title'] ?? '') === $title && ($last['url'] ?? null) === $url) {
        return;
    }

    $crumbs[] = [
        'title' => $title,
        'url' => $url,
    ];
};

$get_cpt_archive = static function (string $post_type): array {
    $obj = get_post_type_object($post_type);
    if (!$obj || empty($obj->has_archive)) {
        return ['', null];
    }

    $title = (string) ($obj->labels->name ?? ucfirst($post_type));
    $url = get_post_type_archive_link($post_type);

    return [$title, $url ?: null];
};

$home_url = function_exists('pll_home_url') ? pll_home_url() : home_url('/');
$add_crumb((string) __('Головна', 'igrmed'), $home_url);

if (is_tax(['service_category', 'doctor_specialty', 'blog_category'])) {
    $term = get_queried_object();
    if ($term instanceof WP_Term) {
        $taxonomy_to_pt = [
            'service_category' => 'services',
            'doctor_specialty' => 'doctors',
            'blog_category' => 'blog',
        ];

        $post_type = $taxonomy_to_pt[$term->taxonomy] ?? '';
        if ($post_type !== '') {
            [$archive_title, $archive_url] = $get_cpt_archive($post_type);
            $add_crumb($archive_title, $archive_url);
        }

        $ancestors = array_reverse(get_ancestors($term->term_id, $term->taxonomy, 'taxonomy'));
        foreach ($ancestors as $ancestor_id) {
            $ancestor = get_term($ancestor_id, $term->taxonomy);
            if ($ancestor instanceof WP_Term) {
                $add_crumb($ancestor->name, (string) get_term_link($ancestor));
            }
        }

        $add_crumb($term->name);
    }
} elseif (is_post_type_archive(['blog', 'services', 'doctors'])) {
    $post_type = get_query_var('post_type');
    if (is_array($post_type)) {
        $post_type = reset($post_type);
    }

    $post_type = (string) $post_type;
    [$archive_title] = $get_cpt_archive($post_type);
    $add_crumb($archive_title);
} elseif (is_category()) {
    $category = get_queried_object();
    if ($category instanceof WP_Term) {
        $add_crumb($category->name);
    }
} elseif (is_page()) {
    if ($post instanceof WP_Post && $post->post_parent) {
        $ancestors = array_reverse(get_post_ancestors($post->ID));
        foreach ($ancestors as $ancestor_id) {
            $add_crumb((string) get_the_title($ancestor_id), get_permalink($ancestor_id) ?: null);
        }
    }

    $add_crumb((string) get_the_title());
} elseif (is_single() && !is_attachment()) {
    $post_type = (string) get_post_type();

    if (in_array($post_type, ['blog', 'services', 'doctors'], true)) {
        [$archive_title, $archive_url] = $get_cpt_archive($post_type);
        $add_crumb($archive_title, $archive_url);
    } elseif ($post_type === 'post') {
        $add_crumb((string) __('Блог', 'igrmed'));
    }

    $add_crumb((string) get_the_title());
} elseif (is_search()) {
    $add_crumb((string) __('Пошук', 'igrmed') . ': ' . get_search_query());
} elseif (is_404()) {
    $add_crumb((string) __('Сторінку не знайдено', 'igrmed'));
} elseif (is_archive()) {
    $title = preg_replace('/^[\w\s]+:\s/', '', (string) get_the_archive_title());
    $add_crumb((string) $title);
}

if (count($crumbs) < 2) {
    return;
}

$current_url = home_url($_SERVER['REQUEST_URI'] ?? '/');
?>

<nav class="breadcrumbs" aria-label="<?php echo esc_attr__('Breadcrumb', 'igrmed'); ?>">
    <ol class="breadcrumbs__list" itemscope itemtype="https://schema.org/BreadcrumbList">
        <?php foreach ($crumbs as $index => $crumb): ?>
            <?php $is_last = $index === count($crumbs) - 1; ?>
            <li class="breadcrumbs__item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                <?php if (!$is_last && !empty($crumb['url'])): ?>
                    <a href="<?php echo esc_url((string) $crumb['url']); ?>" class="breadcrumbs__link" itemprop="item">
                        <span itemprop="name"><?php echo esc_html((string) $crumb['title']); ?></span>
                    </a>
                    <span class="breadcrumbs__separator">/</span>
                <?php else: ?>
                    <span class="breadcrumbs__current" itemprop="name"><?php echo esc_html((string) $crumb['title']); ?></span>
                    <meta itemprop="item" content="<?php echo esc_url($is_last ? $current_url : ((string) ($crumb['url'] ?? $current_url))); ?>">
                <?php endif; ?>
                <meta itemprop="position" content="<?php echo esc_attr((string) ($index + 1)); ?>">
            </li>
        <?php endforeach; ?>
    </ol>
</nav>