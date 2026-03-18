<?php
/**
 * Section: Diagnostics
 * Location: Diagnostics Research page
 * ACF:
 * - diagnostics-research_women (repeater: title[text], item[editor])
 * - diagnostics-research_man (repeater: title[text], item[editor]) [optional]
 */

$women_rows = get_field('diagnostics-research_women');

$prepare_rows = static function ($rows): array {
    $result = [];

    if (!is_array($rows)) {
        return $result;
    }

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

        $result[] = [
            'title' => $title,
            'item' => $item,
        ];
    }

    return $result;
};

$women_items = $prepare_rows($women_rows);
$post_id = get_queried_object_id();
?>

<section
    class="diagnostics-research-diagnostics js-diagnostics-research"
    data-post-id="<?php echo esc_attr((string) $post_id); ?>"
    data-loading-label="<?php echo esc_attr__('Завантаження...', 'igrmed'); ?>"
    data-error-label="<?php echo esc_attr__('Не вдалося завантажити дані. Спробуйте ще раз.', 'igrmed'); ?>">
    <div class="container">
        <div class="diagnostics-research-diagnostics__actions-wrap">
            <div class="diagnostics-research-diagnostics__actions js-diagnostics-toggle">
                <span class="diagnostics-research-diagnostics__slider" aria-hidden="true"></span>

                <button class="diagnostics-research-diagnostics__button is-active" type="button" data-toggle="women">
                    Діагностика жінок
                </button>
                <button class="diagnostics-research-diagnostics__button" type="button" data-toggle="men">
                    Діагностика чоловіків
                </button>
            </div>
        </div>

        <div class="diagnostics-research-diagnostics__content">
            <div class="diagnostics-research-diagnostics__panel is-active" data-panel="women">
                <?php if (!empty($women_items)): ?>
                    <div class="diagnostics-research-diagnostics__accordion js-diagnostics-accordion">
                        <?php foreach ($women_items as $row): ?>
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

                                <div
                                    class="diagnostics-research-diagnostics__accordion-content">
                                    <?php echo wp_kses_post($row['item']); ?>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="diagnostics-research-diagnostics__panel" data-panel="men" data-loaded="false" hidden>
                <div class="diagnostics-research-diagnostics__status" aria-live="polite"></div>
            </div>
        </div>
    </div>
</section>
