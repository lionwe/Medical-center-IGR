<?php

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
                ? ' class="is-hidden-service"'
                : '';

            echo '<li' . $item_class . '>' . esc_html($item_text) . '</li>';
        }
        if ($should_collapse_blocks) {
            echo '<li class="diagnostics-research-diagnostics__list-more-item">';
            echo '<button type="button" class="diagnostics-research-diagnostics__list-more" aria-expanded="false">';
            echo '<span class="diagnostics-research-diagnostics__list-more-text">' . esc_html__('Всі процедури', 'igrmed') . '</span>';
            echo '<span class="diagnostics-research-diagnostics__list-more-arrow" aria-hidden="true">';
            echo igrmed_get_diagnostics_svg_markup('assets/img/svg/arrow-next.svg');
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
        return '<div class="diagnostics-research-diagnostics__status">' . esc_html__('Дані відсутні.', 'igrmed') . '</div>';
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
                <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/svg/diagnostics-polygon.svg'); ?>"
                    alt="" width="19" height="9">
            </span>
        </button>

        <div class="diagnostics-research-diagnostics__accordion-content">
            <?php echo igrmed_render_diagnostics_content($row); ?>
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