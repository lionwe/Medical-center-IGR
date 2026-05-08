<?php

add_action('wp_ajax_igrmed_load_surrogate_tabs', 'igrmed_load_surrogate_tabs');
add_action('wp_ajax_nopriv_igrmed_load_surrogate_tabs', 'igrmed_load_surrogate_tabs');

function igrmed_load_surrogate_tabs(): void
{
    error_log('Surrogate tabs AJAX called');

    check_ajax_referer('ajax-nonce', 'nonce');

    $post_id = isset($_POST['post_id']) ? (int) $_POST['post_id'] : 0;
    $tab_type = isset($_POST['tab_type']) ? sanitize_text_field($_POST['tab_type']) : '';

    error_log('Post ID: ' . $post_id);
    error_log('Tab type: ' . $tab_type);

    if ($post_id <= 0 || $tab_type === '') {
        error_log('Invalid parameters');
        wp_send_json_error(['message' => 'Invalid parameters'], 400);
    }

    $post = get_post($post_id);
    if (!$post) {
        error_log('Post not found');
        wp_send_json_error(['message' => 'Post not found'], 404);
    }

    // Handle generic tab types (tab_0, tab_1, etc.)
    if (strpos($tab_type, 'tab_') === 0) {
        $tab_index = (int) str_replace('tab_', '', $tab_type);
        error_log('Generic tab index: ' . $tab_index);

        // Get the actual tabs data for this post (keep in sync with template field names).
        // Primary field in `sections/surrogate-motherhood/content.php` is `central_tabs_list`.
        $central_tabs = get_field('central_tabs_list', $post_id);

        error_log('Central tabs field exists: ' . ($central_tabs !== null ? 'yes' : 'no'));
        error_log('Central tabs is array: ' . (is_array($central_tabs) ? 'yes' : 'no'));
        error_log('Central tabs count: ' . (is_array($central_tabs) ? count($central_tabs) : 0));

        // Try alternative field names (backward compatibility)
        if ($central_tabs === null) {
            $alternative_fields = ['surrogate_central_tabs', 'central_tabs', 'tabs', 'surrogate_tabs'];
            foreach ($alternative_fields as $field_name) {
                $central_tabs = get_field($field_name, $post_id);
                if ($central_tabs !== null) {
                    error_log('Found tabs in field: ' . $field_name);
                    break;
                }
            }
        }

        if (!empty($central_tabs) && isset($central_tabs[$tab_index])) {
            $tab = $central_tabs[$tab_index];
            error_log('Found tab data for index ' . $tab_index);
            error_log('Tab data keys: ' . implode(', ', array_keys($tab)));

            ob_start();
?>
            <?php if (!empty($tab['tab_content_title'])): ?>
                <h3 class="surrogate-motherhood-content__tab-title">
                    <?php echo esc_html($tab['tab_content_title']); ?>
                </h3>
            <?php endif; ?>
            <?php if (!empty($tab['tab_content_text'])): ?>
                <div class="surrogate-motherhood-content__tab-text">
                    <?php echo wp_kses_post($tab['tab_content_text']); ?>
                </div>
            <?php endif; ?>
        <?php
            $html = ob_get_clean();
            error_log('Generated HTML length: ' . strlen($html));

            wp_send_json_success(['html' => $html]);
        } else {
            error_log('No tab data found for index ' . $tab_index);

            // Provide demo content for testing
            ob_start();
        ?>
            <div class="surrogate-motherhood-content__tab-text">
                <?php if ($tab_index === 0): ?>
                    <h3 class="surrogate-motherhood-content__tab-title">Програма сурогатного материнства</h3>
                    <p>Це демонстраційний контент для першого табу. Тут буде інформація про програму сурогатного материнства, кроки, умови та переваги.</p>
                    <ul>
                        <li>Повний юридичний супровід</li>
                        <li>Медичний контроль на всіх етапах</li>
                        <li>Підбір сурогатної матері</li>
                        <li>Підтримка після народження дитини</li>
                    </ul>
                <?php else: ?>
                    <h3 class="surrogate-motherhood-content__tab-title">Показання до сурогатного материнства</h3>
                    <p>Це демонстраційний контент для другого табу. Тут буде інформація про медичні показання до сурогатного материнства.</p>
                    <ul>
                        <li>Відсутність матки</li>
                        <li>Серйозні захворювання матки</li>
                        <li>Неуспішні спроби ЕКІ</li>
                        <li>Інші медичні показання</li>
                    </ul>
                <?php endif; ?>
            </div>
        <?php
            $html = ob_get_clean();
            wp_send_json_success(['html' => $html]);
        }
        return;
    }

    // Handle specific tab types (programs, indications, etc.)
    $cache_key = 'igrmed_surrogate_' . $tab_type . '_' . $post_id;
    $cached_html = get_transient($cache_key);
    if (is_string($cached_html) && $cached_html !== '') {
        error_log('Returning cached content');
        wp_send_json_success([
            'html' => $cached_html,
        ]);
    }

    ob_start();

    // Load content based on tab type
    if ($tab_type === 'programs') {
        error_log('Loading programs tab');
        $program_steps = get_field('surrogate_program_steps', $post_id);
        $price_label = get_field('surrogate_price_label', $post_id);
        $program_image = get_field('surrogate_program_image', $post_id);
        $program_image_url = is_array($program_image) ? ($program_image['url'] ?? '') : $program_image;

        error_log('Program steps found: ' . (is_array($program_steps) ? count($program_steps) : 0));
        error_log('Price label: ' . ($price_label ? 'exists' : 'missing'));

        if (!empty($program_steps) || !empty($price_label)):
        ?>
            <div class="surrogate-motherhood-content__program-main">
                <?php if (!empty($program_steps)): ?>
                    <div class="surrogate-motherhood-content__steps">
                        <?php foreach ($program_steps as $step_index => $step): ?>
                            <div class="surrogate-motherhood-content__step">
                                <span class="surrogate-motherhood-content__step-number">
                                    <?php echo esc_html(str_pad($step_index + 1, 2, '0', STR_PAD_LEFT)); ?>
                                </span>
                                <?php if (!empty($step['step_text'])): ?>
                                    <p class="surrogate-motherhood-content__step-text">
                                        <?php echo esc_html($step['step_text']); ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($price_label)): ?>
                    <div class="surrogate-motherhood-content__price">
                        <span class="surrogate-motherhood-content__price-label">
                            <?php echo $price_label; ?>
                        </span>
                    </div>
                <?php endif; ?>
            </div>

            <?php if (!empty($program_image_url)): ?>
                <div class="surrogate-motherhood-content__program-image">
                    <?php
                    if (function_exists('get_picture')) {
                        get_picture([
                            'src'   => $program_image_url,
                            'alt'   => is_array($program_image) ? ($program_image['alt'] ?? '') : '',
                            'class' => 'surrogate-motherhood-content__program-img',
                        ]);
                    }
                    ?>
                </div>
            <?php endif; ?>
        <?php
        endif;
    } elseif ($tab_type === 'indications') {
        error_log('Loading indications tab');
        $indications = get_field('surrogate_indications', $post_id);
        error_log('Indications found: ' . (is_array($indications) ? count($indications) : 0));

        if (!empty($indications)):
        ?>
            <div class="surrogate-motherhood-content__list">
                <?php foreach ($indications as $item): ?>
                    <div class="surrogate-motherhood-content__item">
                        <?php if (!empty($item['title'])): ?>
                            <h3><?php echo esc_html($item['title']); ?></h3>
                        <?php endif; ?>
                        <?php if (!empty($item['content'])): ?>
                            <div class="surrogate-motherhood-content__item-content">
                                <?php echo wp_kses_post($item['content']); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
    <?php
        endif;
    } else {
        error_log('Unknown tab type: ' . $tab_type);
    }

    $html = ob_get_clean();
    error_log('Generated HTML length: ' . strlen($html));

    set_transient($cache_key, $html, HOUR_IN_SECONDS);

    wp_send_json_success([
        'html' => $html,
    ]);
}

add_action('wp_ajax_igrmed_load_diagnostics_men', 'igrmed_load_diagnostics_men');
add_action('wp_ajax_nopriv_igrmed_load_diagnostics_men', 'igrmed_load_diagnostics_men');

function igrmed_get_diagnostics_men_cache_key(int $post_id): string
{
    return 'igrmed_diag_men_' . $post_id;
}

function igrmed_get_diagnostics_svg_markup(string $relative_svg_path): string
{
    $base_path = (string) get_template_directory();
    if ($base_path === '') {
        return '';
    }

    $svg_path = trailingslashit($base_path) . ltrim($relative_svg_path, '/');
    if ($svg_path === '' || !file_exists($svg_path) || !is_readable($svg_path)) {
        return '';
    }

    $svg_markup = file_get_contents($svg_path);
    if (!is_string($svg_markup) || $svg_markup === '') {
        return '';
    }

    return wp_kses_post($svg_markup);
}

function igrmed_normalize_diagnostics_list_style($raw_style): string
{
    $value = trim((string) $raw_style);
    if ($value === '') {
        return 'list-dots';
    }

    $value = function_exists('mb_strtolower') ? mb_strtolower($value) : strtolower($value);
    $value = str_replace(['_', '—', '–'], ['-', '-', '-'], $value);

    $map = [
        'list-lines' => 'list-lines',
        'lines' => 'list-lines',
        'line' => 'list-lines',
        'список з лініями' => 'list-lines',
        'лінії' => 'list-lines',
        'лініями' => 'list-lines',
        'list-dots' => 'list-dots',
        'dots' => 'list-dots',
        'dot' => 'list-dots',
        'список з крапками' => 'list-dots',
        'крапки' => 'list-dots',
        'крапками' => 'list-dots',
        'list-cards' => 'list-cards',
        'cards' => 'list-cards',
        'card' => 'list-cards',
        'список-картки' => 'list-cards',
        'список картки' => 'list-cards',
        'картки' => 'list-cards',
        'list-blocks' => 'list-blocks',
        'blocks' => 'list-blocks',
        'block' => 'list-blocks',
        'список-блоки' => 'list-blocks',
        'список блоки' => 'list-blocks',
        'блоки' => 'list-blocks',
    ];

    return $map[$value] ?? 'list-dots';
}

function igrmed_apply_class_to_editor_lists(string $html, string $list_style): string
{
    if ($html === '') {
        return '';
    }

    $style = esc_attr(igrmed_normalize_diagnostics_list_style($list_style));

    $inject = static function (array $matches) use ($style): string {
        $attrs = trim((string) ($matches[1] ?? ''));
        return $attrs === ''
            ? '<ul class="' . $style . '">'
            : '<ul ' . $attrs . ' class="' . $style . '">';
    };

    $processed = preg_replace_callback('/<ul(?![^>]*\bclass=)([^>]*)>/i', $inject, $html) ?? $html;
    $processed = preg_replace_callback(
        '/<ol(?![^>]*\bclass=)([^>]*)>/i',
        static function (array $matches) use ($style): string {
            $attrs = trim((string) ($matches[1] ?? ''));
            return $attrs === ''
                ? '<ol class="' . $style . '">'
                : '<ol ' . $attrs . ' class="' . $style . '">';
        },
        $processed
    ) ?? $processed;

    return $processed;
}

function igrmed_prepare_diagnostics_rows($rows): array
{
    $result = [];

    if (!is_array($rows)) {
        return $result;
    }

    foreach ($rows as $row) {
        if (!is_array($row)) {
            continue;
        }

        $title = trim((string) ($row['title'] ?? ''));
        $description = $row['description'] ?? '';
        if (!is_string($description) || trim($description) === '') {
            // Backward compatibility: old single WYSIWYG field.
            $description = is_string($row['item'] ?? null) ? $row['item'] : '';
        }
        $description = trim((string) $description);

        $style = igrmed_normalize_diagnostics_list_style($row['list_style'] ?? 'list-dots');

        $items = [];
        $raw_items = $row['list_items'] ?? [];
        if (is_array($raw_items)) {
            foreach ($raw_items as $raw_item) {
                if (is_array($raw_item)) {
                    $text = trim((string) ($raw_item['text'] ?? $raw_item['item'] ?? $raw_item['label'] ?? $raw_item['value'] ?? ''));
                } else {
                    $text = is_string($raw_item) ? trim($raw_item) : '';
                }

                if ($text === '') {
                    continue;
                }

                $items[] = $text;
            }
        }

        if ($title === '' && $description === '' && empty($items)) {
            continue;
        }

        $result[] = [
            'title' => $title,
            'description' => $description,
            'style' => $style,
            'items' => $items,
        ];
    }

    return $result;
}

function igrmed_render_diagnostics_content(array $row): string
{
    $description = trim((string) ($row['description'] ?? ''));
    $style = igrmed_normalize_diagnostics_list_style($row['style'] ?? 'list-dots');
    $items = is_array($row['items'] ?? null) ? $row['items'] : [];
    $list_blocks_visible_limit = wp_is_mobile() ? 5 : 11;

    ob_start();
    if ($description !== '') {
        $description_html = igrmed_apply_class_to_editor_lists($description, $style);
        echo wp_kses_post($description_html);
    }

    if (!empty($items)) {
        $should_collapse_blocks = $style === 'list-blocks' && count($items) > $list_blocks_visible_limit;
        echo '<ul class="' . esc_attr($style) . '">';
        foreach ($items as $index => $item_text) {
            if (!is_string($item_text) || trim($item_text) === '') {
                continue;
            }

            $item_class = ($should_collapse_blocks && $index >= $list_blocks_visible_limit)
                ? ' class="is-collapsible-service is-hidden-service"'
                : '';

            echo '<li' . $item_class . '>' . esc_html($item_text) . '</li>';
        }
        if ($should_collapse_blocks) {
            echo '<li class="diagnostics-research-diagnostics__list-more-item">';
            echo '<button type="button" class="diagnostics-research-diagnostics__list-more" aria-expanded="false"';
            echo ' data-more-label="' . esc_attr(igrmed__('diagnostics_all_procedures')) . '"';
            echo ' data-less-label="' . esc_attr(igrmed__('btn_close')) . '">';
            echo '<span class="diagnostics-research-diagnostics__list-more-text">' . esc_html(igrmed__('diagnostics_all_procedures')) . '</span>';
            echo '<span class="diagnostics-research-diagnostics__list-more-arrow" aria-hidden="true">';
            echo igrmed_get_svg('read-more-arrow');
            echo '</span>';
            echo '</button>';
            echo '</li>';
        }
        echo '</ul>';
    }

    return (string) ob_get_clean();
}

function igrmed_render_diagnostics_accordion(array $items): string
{
    if (empty($items)) {
        return '<div class="diagnostics-research-diagnostics__status">' . esc_html(igrmed__('error_no_data')) . '</div>';
    }

    ob_start();
    ?>
    <div class="diagnostics-research-diagnostics__accordion js-diagnostics-accordion">
        <?php foreach ($items as $row): ?>
            <article class="diagnostics-research-diagnostics__accordion-item">
                <button class="diagnostics-research-diagnostics__accordion-trigger" type="button" aria-expanded="false">
                    <span class="diagnostics-research-diagnostics__accordion-title">
                        <?php echo esc_html($row['title']); ?>
                    </span>
                    <span class="diagnostics-research-diagnostics__accordion-icon" aria-hidden="true">
                        <?php
                        get_picture([
                            'name' => 'svg/diagnostics-polygon.svg',
                            'alt' => '',
                            'class' => '',
                            'lazy' => false,
                        ]);
                        ?>
                    </span>
                </button>

                <div class="diagnostics-research-diagnostics__accordion-content">
                    <div class="diagnostics-research-diagnostics__accordion-content-inner">
                        <?php echo igrmed_render_diagnostics_content($row); ?>
                    </div>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
    <?php

    return (string) ob_get_clean();
}

function igrmed_load_diagnostics_men(): void
{
    check_ajax_referer('ajax-nonce', 'nonce');

    $post_id = isset($_POST['post_id']) ? (int) $_POST['post_id'] : 0;
    if ($post_id <= 0) {
        wp_send_json_error(['message' => 'Invalid post id'], 400);
    }

    $post = get_post($post_id);
    if (!$post) {
        wp_send_json_error(['message' => 'Post not found'], 404);
    }

    $cache_key = igrmed_get_diagnostics_men_cache_key($post_id);
    $cached_html = get_transient($cache_key);
    if (is_string($cached_html) && $cached_html !== '') {
        wp_send_json_success([
            'html' => $cached_html,
        ]);
    }

    $rows = get_field('diagnostics-research_man', $post_id);
    $items = igrmed_prepare_diagnostics_rows($rows);
    $html = igrmed_render_diagnostics_accordion($items);

    set_transient($cache_key, $html, HOUR_IN_SECONDS);

    wp_send_json_success([
        'html' => $html,
    ]);
}

/**
 * Load more blog posts via AJAX (style from user sample)
 */
add_action('wp_ajax_load_blog_posts', 'igrmed_load_blog_posts_ajax');
add_action('wp_ajax_nopriv_load_blog_posts', 'igrmed_load_blog_posts_ajax');

function igrmed_load_blog_posts_ajax()
{
    // Verification of the nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'blog_archive_nonce')) {
        wp_send_json_error(['message' => 'Security check failed'], 403);
    }

    $paged = isset($_POST['paged']) ? intval($_POST['paged']) : 1;
    $is_mobile = wp_is_mobile();
    $posts_per_page = $is_mobile ? 7 : 12;

    // Use cache for better performance
    $cache_key = 'igrmed_blog_page_' . $paged . ($is_mobile ? '_mobile' : '_desktop');
    $cached_data = get_transient($cache_key);

    if ($cached_data !== false) {
        wp_send_json_success($cached_data);
    }

    $args = [
        'post_type'           => 'blog',
        'post_status'         => 'publish',
        'posts_per_page'      => $posts_per_page,
        'paged'               => $paged,
        'orderby'             => 'date',
        'order'               => 'DESC',
        'ignore_sticky_posts' => 1,
        'no_found_rows'       => false, // Need for max_num_pages
        'update_post_term_cache' => false,
        'update_post_meta_cache' => false,
    ];

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        ob_start();
        while ($query->have_posts()) {
            $query->the_post();
            get_template_part('templates/blog-card');
        }
        $content = ob_get_clean();

        $response_data = [
            'html'      => $content,
            'max_pages' => (int) $query->max_num_pages,
        ];

        // Cache for 1 hour
        set_transient($cache_key, $response_data, HOUR_IN_SECONDS);

        wp_send_json_success($response_data);
    } else {
        wp_send_json_error(['message' => 'No more posts']);
    }

    wp_reset_postdata();
    wp_die();
}

/**
 * Live search AJAX handler.
 *
 * Uses direct $wpdb query instead of WP_Query because WordPress 6.x
 * $wpdb->prepare() replaces LIKE wildcards (%) with hash-placeholder tokens,
 * making title LIKE searches impossible through WP_Query filters.
 */
add_action('wp_ajax_igrmed_live_search', 'igrmed_live_search');
add_action('wp_ajax_nopriv_igrmed_live_search', 'igrmed_live_search');

function igrmed_live_search(): void
{
    check_ajax_referer('ajax-nonce', 'nonce');

    $s = isset($_POST['s']) ? sanitize_text_field(wp_unslash($_POST['s'])) : '';
    $lang = isset($_POST['lang']) ? sanitize_text_field(wp_unslash($_POST['lang'])) : '';

    if ($s === '' || mb_strlen($s) < 2) {
        wp_send_json_error(['message' => igrmed__('error_query_too_short')], 400);
    }

    global $wpdb;

    $escaped_like = esc_sql($wpdb->esc_like($s));
    $like_contains = "'%" . $escaped_like . "%'";
    $like_starts   = "'" . $escaped_like . "%'";
    $exact_val     = "'" . esc_sql($s) . "'";

    // Searchable post types
    $post_types = ['price_items', 'doctors', 'blog', 'page'];
    $types_in = implode(',', array_map(function ($t) {
        return "'" . esc_sql($t) . "'";
    }, $post_types));

    // Polylang language filter: LEFT JOIN so posts without language assignment are still found
    $lang_join  = '';
    $lang_where = '';

    if ($lang !== '' && function_exists('pll_current_language')) {
        $lang_term = get_term_by('slug', $lang, 'language');

        if ($lang_term && !is_wp_error($lang_term)) {
            $lang_tt_id = (int) $lang_term->term_taxonomy_id;
            $lang_join  = " LEFT JOIN {$wpdb->term_relationships} AS pll_tr
                ON ({$wpdb->posts}.ID = pll_tr.object_id
                    AND pll_tr.term_taxonomy_id = {$lang_tt_id})";
            $lang_where = " AND (pll_tr.term_taxonomy_id IS NOT NULL
                OR {$wpdb->posts}.ID NOT IN (
                    SELECT object_id FROM {$wpdb->term_relationships}
                    INNER JOIN {$wpdb->term_taxonomy} ON {$wpdb->term_relationships}.term_taxonomy_id = {$wpdb->term_taxonomy}.term_taxonomy_id
                    WHERE {$wpdb->term_taxonomy}.taxonomy = 'language'
                ))";
        }
    }

    // phpcs:disable WordPress.DB.PreparedSQL.InterpolatedNotPrepared
    $sql = "SELECT {$wpdb->posts}.ID, {$wpdb->posts}.post_title, {$wpdb->posts}.post_type
        FROM {$wpdb->posts}
        {$lang_join}
        WHERE {$wpdb->posts}.post_title LIKE {$like_contains}
            AND {$wpdb->posts}.post_type IN ({$types_in})
            AND {$wpdb->posts}.post_status = 'publish'
            {$lang_where}
        ORDER BY
            (CASE
                WHEN {$wpdb->posts}.post_title = {$exact_val} THEN 1
                WHEN {$wpdb->posts}.post_title LIKE {$like_starts} THEN 2
                ELSE 3
            END) ASC,
            {$wpdb->posts}.post_title ASC
        LIMIT 10";
    // phpcs:enable

    $results = $wpdb->get_results($sql);

    if (!empty($results)) {
        ob_start();
        echo '<ul class="header__search-results-list">';

        foreach ($results as $row) {
            $post_id   = (int) $row->ID;
            $post_type = $row->post_type;
            $post_type_obj = get_post_type_object($post_type);
            $label = $post_type_obj ? $post_type_obj->labels->singular_name : $post_type;

            $permalink = get_permalink($post_id);
            $title     = esc_html($row->post_title);
    ?>
            <li class="header__search-results-item">
                <a href="<?php echo esc_url($permalink); ?>" class="header__search-results-link">
                    <span class="header__search-results-title"><?php echo $title; ?></span>
                    <span class="header__search-results-type"><?php echo esc_html($label); ?></span>
                </a>
            </li>
<?php
        }

        echo '</ul>';
        $html = ob_get_clean();

        wp_send_json_success(['html' => $html]);
    } else {
        wp_send_json_success([
            'html' => '<div class="header__search-no-results">'
                . esc_html(igrmed__('search_no_results'))
                . '</div>',
        ]);
    }
}
