<?php
/**
 * Сурогатне материнство — контент сторінки послуги.
 */

// 1. Вступна частина
$intro_title   = trim((string) get_field('intro_title'));
$intro_content = get_field('intro_content');
$intro_content = is_string($intro_content) ? trim($intro_content) : '';

// 2. Центральна секція (Таби)
$central_tabs = get_field('central_tabs_list');
$central_tabs = is_array($central_tabs) ? array_filter($central_tabs, function($tab) {
    return !empty($tab['tab_nav_title']) || !empty($tab['tab_content_text']);
}) : [];

// 3. Програма та вартість
$program_title  = trim((string) get_field('program_title'));
$program_steps  = get_field('program_steps');
$program_steps  = is_array($program_steps) ? array_filter($program_steps, function($step) {
    return !empty($step['step_number']) || !empty($step['step_text']);
}) : [];
$program_image  = get_field('program_image');
$price_label    = trim((string) get_field('price_label'));
$price_value    = trim((string) get_field('price_value'));
$price_subtext  = trim((string) get_field('price_subtext'));

$program_image_url = '';
if (is_array($program_image)) {
    $program_image_url = $program_image['url'] ?? '';
} elseif (is_string($program_image)) {
    $program_image_url = $program_image;
}

$has_intro   = $intro_content !== '';
$has_tabs    = !empty($central_tabs);
$has_program = !empty($program_steps) || $program_image_url !== '' || $price_value !== '';

// Empty Fields Rule: if there is no content for any section, do not render the block at all.
if (!$has_intro && !$has_tabs && !$has_program) {
    return;
}

$sections = [];

if ($has_intro) {
    $sections[] = [
        'id'    => 'surrogate-intro',
        'title' => $intro_title !== '' ? $intro_title : __('Сурогатне материнство', 'igrmed'),
    ];
}
if ($has_tabs) {
    $sections[] = [
        'id'    => 'surrogate-tabs',
        'title' => __('Супровід', 'igrmed'),
    ];
}
if ($has_program) {
    $sections[] = [
        'id'    => 'surrogate-program',
        'title' => $program_title !== '' ? $program_title : __('Програма та вартість', 'igrmed'),
    ];
}
?>

<section class="surrogate-motherhood-content">
    <div class="container">
        <div class="surrogate-motherhood-content__layout catalog-wrap">
            <?php get_template_part('templates/content-sidebar', null, ['sections' => $sections]); ?>

            <div class="surrogate-motherhood-content__content">

                <?php if ($has_intro) : ?>
                <div id="surrogate-intro"
                    class="surrogate-motherhood-content__section surrogate-motherhood-content__section--intro">
                    <div class="surrogate-motherhood-content__title-wrap">
                        <h2 class="surrogate-motherhood-content__title">
                            <?php echo esc_html($intro_title !== '' ? $intro_title : __('Сурогатне материнство', 'igrmed')); ?>
                        </h2>
                    </div>
                    <?php if ($intro_content !== '') : ?>
                    <div class="surrogate-motherhood-content__body">
                        <?php echo wp_kses_post($intro_content); ?>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <?php if ($has_tabs) : ?>
                <div id="surrogate-tabs"
                    class="surrogate-motherhood-content__section surrogate-motherhood-content__section--tabs"
                    data-post-id="<?php echo esc_attr(get_queried_object_id()); ?>"
                    data-loading-label="<?php echo esc_attr__('Завантаження...', 'igrmed'); ?>"
                    data-error-label="<?php echo esc_attr__('Не вдалося завантажити дані. Спробуйте ще раз.', 'igrmed'); ?>">

                    <?php /* Desktop: flat nav + content area */ ?>
                    <div class="surrogate-motherhood-content__tabs">
                        <div class="surrogate-motherhood-content__tabs-nav">
                            <?php foreach ($central_tabs as $index => $tab) : ?>
                            <button type="button"
                                class="surrogate-motherhood-content__tab-btn <?php echo $index === 0 ? 'is-active' : ''; ?>"
                                data-tab="<?php echo esc_attr($tab['tab_type'] ?? 'tab_' . $index); ?>">
                                <?php if (!empty($tab['tab_icon'])) : ?>
                                <span class="surrogate-motherhood-content__tab-icon-wrap">
                                    <img src="<?php echo esc_url($tab['tab_icon']); ?>" alt=""
                                        class="surrogate-motherhood-content__tab-icon">
                                </span>
                                <?php endif; ?>
                                <span><?php echo esc_html($tab['tab_nav_title'] ?? ''); ?></span>
                            </button>
                            <?php endforeach; ?>
                        </div>
                        <div class="surrogate-motherhood-content__tabs-content">
                            <?php foreach ($central_tabs as $index => $tab) : ?>
                            <div class="surrogate-motherhood-content__tab-panel <?php echo $index === 0 ? 'is-active' : ''; ?>"
                                data-panel="<?php echo esc_attr($tab['tab_type'] ?? 'tab_' . $index); ?>"
                                data-loaded="<?php echo $index === 0 ? 'true' : 'false'; ?>"
                                <?php echo $index !== 0 ? 'hidden' : ''; ?>>
                                <?php if ($index === 0) : ?>
                                <?php if (!empty($tab['tab_content_title'])) : ?>
                                <h3 class="surrogate-motherhood-content__tab-title">
                                    <?php echo esc_html($tab['tab_content_title']); ?>
                                </h3>
                                <?php endif; ?>
                                <?php if (!empty($tab['tab_content_text'])) : ?>
                                <div class="surrogate-motherhood-content__tab-text">
                                    <?php echo wp_kses_post($tab['tab_content_text']); ?>
                                </div>
                                <?php endif; ?>
                                <?php else : ?>
                                <div class="surrogate-motherhood-content__tab-status"></div>
                                <?php endif; ?>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <?php /* Mobile: accordion — each item wraps its own btn + panel */ ?>
                    <div class="surrogate-motherhood-content__accordion">
                        <?php foreach ($central_tabs as $index => $tab) : ?>
                        <div class="surrogate-motherhood-content__accordion-item">
                            <button type="button" class="surrogate-motherhood-content__tab-btn"
                                data-tab="<?php echo esc_attr($tab['tab_type'] ?? 'tab_' . $index); ?>">
                                <?php if (!empty($tab['tab_icon'])) : ?>
                                <span class="surrogate-motherhood-content__tab-icon-wrap">
                                    <img src="<?php echo esc_url($tab['tab_icon']); ?>" alt=""
                                        class="surrogate-motherhood-content__tab-icon">
                                </span>
                                <?php endif; ?>
                                <span><?php echo esc_html($tab['tab_nav_title'] ?? ''); ?></span>
                            </button>
                            <div class="surrogate-motherhood-content__tab-panel"
                                data-panel="<?php echo esc_attr($tab['tab_type'] ?? 'tab_' . $index); ?>"
                                data-loaded="<?php echo $index === 0 ? 'true' : 'false'; ?>" hidden>
                                <?php if ($index === 0) : ?>
                                <?php if (!empty($tab['tab_content_title'])) : ?>
                                <h3 class="surrogate-motherhood-content__tab-title">
                                    <?php echo esc_html($tab['tab_content_title']); ?>
                                </h3>
                                <?php endif; ?>
                                <?php if (!empty($tab['tab_content_text'])) : ?>
                                <div class="surrogate-motherhood-content__tab-text">
                                    <?php echo wp_kses_post($tab['tab_content_text']); ?>
                                </div>
                                <?php endif; ?>
                                <?php else : ?>
                                <div class="surrogate-motherhood-content__tab-status"></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>

                </div>
                <?php endif; ?>

                <?php if ($has_program) : ?>
                <div id="surrogate-program"
                    class="surrogate-motherhood-content__section surrogate-motherhood-content__section--program">
                    <div class="surrogate-motherhood-content__program">
                        <div class="surrogate-motherhood-content__program-main">
                            <?php if ($program_title !== '') : ?>
                            <h2 class="surrogate-motherhood-content__program-title">
                                <?php echo esc_html($program_title); ?>
                            </h2>
                            <?php endif; ?>

                            <?php if (!empty($program_steps)) : ?>
                            <div class="surrogate-motherhood-content__steps">
                                <?php foreach ($program_steps as $step_index => $step) : ?>
                                <div class="surrogate-motherhood-content__step">
                                    <span class="surrogate-motherhood-content__step-number">
                                        <?php echo esc_html(str_pad($step_index + 1, 2, '0', STR_PAD_LEFT)); ?>
                                    </span>
                                    <?php if (!empty($step['step_text'])) : ?>
                                    <p class="surrogate-motherhood-content__step-text">
                                        <?php echo esc_html($step['step_text']); ?>
                                    </p>
                                    <?php endif; ?>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <?php endif; ?>

                            <?php if ($price_value !== '') : ?>
                            <div class="surrogate-motherhood-content__price">
                                <?php if ($price_label !== '') : ?>
                                <span class="surrogate-motherhood-content__price-label">
                                    <?php echo wp_kses_post($price_label); ?>
                                </span>
                                <?php endif; ?>
                            </div>
                            <?php endif; ?>
                        </div>

                        <?php if ($program_image_url !== '') : ?>
                        <div class="surrogate-motherhood-content__program-image">
                            <?php get_picture([
                                        'src'   => $program_image_url,
                                        'alt'   => is_array($program_image) ? ($program_image['alt'] ?? '') : '',
                                        'class' => 'surrogate-motherhood-content__program-img',
                                    ]); ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>

                <?php get_template_part('templates/overflow-banner'); ?>

            </div>
        </div>
    </div>
</section>