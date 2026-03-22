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
$program_image  = get_field('program_image'); // масив ACF (url, alt, ...) або просто URL
$price_label    = trim((string) get_field('price_label'));
$price_value    = trim((string) get_field('price_value'));
$price_subtext  = trim((string) get_field('price_subtext'));

// Визначаємо наявність зображення для перевірки $has_program
$program_image_url = '';
if (is_array($program_image)) {
    $program_image_url = $program_image['url'] ?? '';
} elseif (is_string($program_image)) {
    $program_image_url = $program_image;
}

// Блоки показуються лише якщо є реальний контент
$has_intro   = $intro_title !== '' || $intro_content !== '';
$has_tabs    = !empty($central_tabs);
$has_program = $program_title !== '' || !empty($program_steps) || $program_image_url !== '' || $price_value !== '';

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
                    <div id="surrogate-intro" class="surrogate-motherhood-content__section surrogate-motherhood-content__section--intro">
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
                    <div id="surrogate-tabs" class="surrogate-motherhood-content surrogate-motherhood-content--tabs"
                         data-post-id="<?php echo esc_attr(get_queried_object_id()); ?>"
                         data-loading-label="<?php echo esc_attr__('Завантаження...', 'igrmed'); ?>"
                         data-error-label="<?php echo esc_attr__('Не вдалося завантажити дані. Спробуйте ще раз.', 'igrmed'); ?>">
                        <div class="tabs">
                            <div class="tabs-nav">
                                <?php foreach ($central_tabs as $index => $tab) : ?>
                                    <button type="button"
                                            class="tab-btn <?php echo $index === 0 ? 'is-active' : ''; ?>"
                                            data-tab="<?php echo esc_attr($tab['tab_type'] ?? 'tab_' . $index); ?>">
                                        <?php if (!empty($tab['tab_icon'])) : ?>
                                            <span class="tab-icon-wrap">
                                                <img src="<?php echo esc_url($tab['tab_icon']); ?>" alt="" class="tab-icon">
                                            </span>
                                        <?php endif; ?>
                                        <span><?php echo esc_html($tab['tab_nav_title'] ?? ''); ?></span>
                                    </button>
                                <?php endforeach; ?>
                            </div>
                            <div class="tabs-content">
                                <?php foreach ($central_tabs as $index => $tab) : ?>
                                    <div class="tab-panel <?php echo $index === 0 ? 'is-active' : ''; ?>"
                                         data-panel="<?php echo esc_attr($tab['tab_type'] ?? 'tab_' . $index); ?>"
                                         data-loaded="<?php echo $index === 0 ? 'true' : 'false'; ?>"
                                         <?php echo $index !== 0 ? 'hidden' : ''; ?>>
                                        <?php if ($index === 0) : ?>
                                            <?php if (!empty($tab['tab_content_title'])) : ?>
                                                <h3 class="tab-title">
                                                    <?php echo esc_html($tab['tab_content_title']); ?>
                                                </h3>
                                            <?php endif; ?>
                                            <?php if (!empty($tab['tab_content_text'])) : ?>
                                                <div class="tab-text">
                                                    <?php echo wp_kses_post($tab['tab_content_text']); ?>
                                                </div>
                                            <?php endif; ?>
                                        <?php else : ?>
                                            <div class="tab-status"></div>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($has_program) : ?>
                    <div id="surrogate-program" class="surrogate-motherhood-content surrogate-motherhood-content--program">
                        <div class="program">
                            <?php if ($program_title !== '') : ?>
                                <h2 class="program-title">
                                    <?php echo esc_html($program_title); ?>
                                </h2>
                            <?php endif; ?>

                            <div class="program-main">
                                <?php if (!empty($program_steps)) : ?>
                                    <div class="steps">
                                        <?php foreach ($program_steps as $step_index => $step) : ?>
                                            <div class="step">
                                                <span class="step-number">
                                                    <?php echo esc_html(str_pad($step_index + 1, 2, '0', STR_PAD_LEFT)); ?>
                                                </span>
                                                <?php if (!empty($step['step_text'])) : ?>
                                                    <p class="step-text">
                                                        <?php echo esc_html($step['step_text']); ?>
                                                    </p>
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>

                                <?php if ($price_label !== '') : ?>
                                    <div class="price">
                                        <span class="price-label">
                                            <?php echo $price_label; ?>
                                        </span>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <?php if (!empty($program_image_url)) : ?>
                                <div class="program-image">
                                    <?php 
                                    if (function_exists('get_picture')) {
                                        get_picture([
                                            'src'   => $program_image_url,
                                            'alt'   => is_array($program_image) ? ($program_image['alt'] ?? '') : '',
                                            'class' => 'program-img',
                                        ]);
                                    }
                                    ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</section>