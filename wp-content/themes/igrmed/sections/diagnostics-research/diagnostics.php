<?php
/**
 * Section: Diagnostics
 * Location: Diagnostics Research page
 */

$women_rows = get_field('diagnostics-research_women');

$prepare_rows = static function ($rows): array {
    if (function_exists('igrmed_prepare_diagnostics_rows')) {
        return igrmed_prepare_diagnostics_rows($rows);
    }

    return [];
};

$women_items = $prepare_rows($women_rows);
$post_id = get_queried_object_id();
?>

<section class="diagnostics-research-diagnostics js-diagnostics-research"
    data-post-id="<?php echo esc_attr((string) $post_id); ?>"
    data-loading-label="<?php echo esc_attr__('Завантаження...', 'igrmed'); ?>"
    data-error-label="<?php echo esc_attr__('Не вдалося завантажити дані. Спробуйте ще раз.', 'igrmed'); ?>">
    <div class="container">
        <div class="diagnostics-research-diagnostics__actions-wrap">
            <div class="diagnostics-research-diagnostics__actions js-diagnostics-toggle">
                <label class="diagnostics-research-diagnostics__label">
                    <input class="diagnostics-research-diagnostics__radio" type="radio"
                        name="diagnostics-type-<?php echo esc_attr((string) $post_id); ?>" value="women"
                        data-toggle="women" checked>
                    <span class="diagnostics-research-diagnostics__bg" aria-hidden="true"></span>
                    <span class="diagnostics-research-diagnostics__button-text">Діагностика жінок</span>
                </label>

                <label class="diagnostics-research-diagnostics__label">
                    <input class="diagnostics-research-diagnostics__radio" type="radio"
                        name="diagnostics-type-<?php echo esc_attr((string) $post_id); ?>" value="men"
                        data-toggle="men">
                    <span class="diagnostics-research-diagnostics__bg" aria-hidden="true"></span>
                    <span class="diagnostics-research-diagnostics__button-text">Діагностика чоловіків</span>
                </label>
            </div>
        </div>

        <div class="diagnostics-research-diagnostics__content">
            <div class="diagnostics-research-diagnostics__panel is-active" data-panel="women">
                <?php if (!empty($women_items)): ?>
                <div class="diagnostics-research-diagnostics__accordion js-diagnostics-accordion">
                    <?php foreach ($women_items as $row): ?>
                    <article class="diagnostics-research-diagnostics__accordion-item">
                        <button class="diagnostics-research-diagnostics__accordion-trigger" type="button"
                            aria-expanded="false">
                            <span class="diagnostics-research-diagnostics__accordion-title">
                                <?php echo esc_html($row['title']); ?>
                            </span>
                            <span class="diagnostics-research-diagnostics__accordion-icon" aria-hidden="true">
                                <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/svg/diagnostics-polygon.svg'); ?>"
                                    alt="" width="19" height="9">
                            </span>
                        </button>

                        <div class="diagnostics-research-diagnostics__accordion-content">

                            <?php
                                    if (function_exists('igrmed_render_diagnostics_content')) {
                                        echo igrmed_render_diagnostics_content($row);
                                    }
                                    ?>

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