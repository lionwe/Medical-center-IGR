<?php

add_action('wp_ajax_igrmed_load_diagnostics_men', 'igrmed_load_diagnostics_men');
add_action('wp_ajax_nopriv_igrmed_load_diagnostics_men', 'igrmed_load_diagnostics_men');

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
                <button
                    class="diagnostics-research-diagnostics__accordion-trigger"
                    type="button"
                    aria-expanded="false">
                    <span class="diagnostics-research-diagnostics__accordion-title">
                        <?php echo esc_html($row['title']); ?>
                    </span>
                    <span class="diagnostics-research-diagnostics__accordion-icon" aria-hidden="true">
                        <img
                            src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/svg/diagnostics-polygon.svg'); ?>"
                            alt=""
                            width="19"
                            height="9">
                    </span>
                </button>

                <div class="diagnostics-research-diagnostics__accordion-content">
                    <?php echo wp_kses_post($row['item']); ?>
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

    $rows = get_field('diagnostics-research_man', $post_id);
    $items = [];

    if (is_array($rows)) {
        foreach ($rows as $row) {
            if (!is_array($row)) {
                continue;
            }

            $title = trim((string) ($row['title'] ?? ''));
            $item = $row['item'] ?? '';
            $item = is_string($item) ? trim($item) : '';

            if ($title === '' && $item === '') {
                continue;
            }

            $items[] = [
                'title' => $title,
                'item' => $item,
            ];
        }
    }

    wp_send_json_success([
        'html' => igrmed_render_diagnostics_accordion($items),
    ]);
}
