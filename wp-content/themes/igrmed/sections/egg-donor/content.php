<?php
/**
 * Стати донором яйцеклітин — контент сторінки послуги.
 */

$intro_title = trim((string) get_field('intro_title'));
$intro_content = get_field('intro_content');
$intro_content = is_string($intro_content) ? trim($intro_content) : '';

$stages_title = trim((string) get_field('stages_title'));
$stages_image = get_field('stages_image');
$stages_list = get_field('stages_list');
$stages_cards = [];

if (is_array($stages_list)) {
    foreach ($stages_list as $stage) {
        if (!is_array($stage)) {
            continue;
        }
        $stage_title = trim((string) ($stage['title'] ?? ''));
        $stage_text = trim((string) ($stage['text'] ?? ''));
        if ($stage_title === '' && $stage_text === '') {
            continue;
        }
        $stages_cards[] = [
            'title' => $stage_title,
            'text' => $stage_text,
        ];
    }
}

$requirements_title = trim((string) get_field('requirements_title'));
$requirements_list = get_field('requirements_list');
$requirements_cards = [];

if (is_array($requirements_list)) {
    foreach ($requirements_list as $req) {
        if (!is_array($req)) {
            continue;
        }

        $req_title = trim((string) ($req['title'] ?? ''));
        $req_text = trim((string) ($req['text'] ?? ''));

        if ($req_title === '' && $req_text === '') {
            continue;
        }

        $requirements_cards[] = [
            'title' => $req_title,
            'text' => $req_text,
        ];
    }
}

$compensation_title = trim((string) get_field('compensation_title'));
$compensation_text = get_field('compensation_text');
$compensation_highlight = get_field('compensation_highlight');
$compensation_text = is_string($compensation_text) ? trim($compensation_text) : '';
$compensation_highlight = is_string($compensation_highlight) ? trim($compensation_highlight) : '';

$has_intro = $intro_content !== '';
$has_stages = !empty($stages_cards) || !empty($stages_image);
$has_requirements = !empty($requirements_cards);
$has_compensation = $compensation_text !== '' || $compensation_highlight !== '';

$sections = [];

if ($has_intro) {
    $sections[] = ['id' => 'donor-intro', 'title' => $intro_title !== '' ? $intro_title : __('Вступ', 'igrmed')];
}
if ($has_stages) {
    $sections[] = ['id' => 'donor-stages', 'title' => $stages_title !== '' ? $stages_title : __('Етапи участі', 'igrmed')];
}
if ($has_requirements) {
    $sections[] = ['id' => 'donor-requirements', 'title' => $requirements_title !== '' ? $requirements_title : __('Вимоги до кандидатів', 'igrmed')];
}
if ($has_compensation) {
    $sections[] = ['id' => 'donor-compensation', 'title' => $compensation_title !== '' ? $compensation_title : __('Компенсація', 'igrmed')];
}

if (empty($sections)) {
    return;
}
?>

<section class="egg-donor-content">
    <div class="container">
        <div class="egg-donor-content__layout catalog-wrap">
            <?php get_template_part('templates/content-sidebar', null, ['sections' => $sections]); ?>

            <div class="egg-donor-content__content">
                <?php if ($has_intro): ?>
                    <div id="donor-intro" class="egg-donor-content__section egg-donor-content__section--intro">
                        <div class="egg-donor-content__title-wrap">
                            <h2 class="egg-donor-content__title">
                                <?php echo esc_html($intro_title !== '' ? $intro_title : __('Вступ', 'igrmed')); ?>
                            </h2>
                        </div>
                        <div class="egg-donor-content__body">
                            <?php if ($intro_content !== ''): ?>
                                <?php echo wp_kses_post($intro_content); ?>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($has_stages): ?>
                    <div id="donor-stages" class="egg-donor-content__section egg-donor-content__section--stages">
                        <div class="egg-donor-content__title-wrap">
                            <h2 class="egg-donor-content__title">
                                <?php echo esc_html($stages_title !== '' ? $stages_title : __('Етапи участі', 'igrmed')); ?>
                            </h2>
                        </div>
                        <div class="egg-donor-content__body">
                            <div class="egg-donor-content__stages-layout">
                                <?php if (!empty($stages_cards)): ?>
                                    <div class="egg-donor-content__stages-list">
                                        <?php foreach ($stages_cards as $index => $stage): ?>
                                                <div class="egg-donor-content__stage-item">
                                                    <div class="egg-donor-content__stage-number"><?php echo esc_html(sprintf('%02d', $index + 1)); ?></div>
                                                    <div class="egg-donor-content__stage-content">
                                                        <?php if ($stage['title'] !== ''): ?>
                                                            <h3 class="egg-donor-content__stage-title"><?php echo esc_html($stage['title']); ?></h3>
                                                        <?php endif; ?>
                                                        <?php if ($stage['text'] !== ''): ?>
                                                            <div class="egg-donor-content__stage-text-wrap">
                                                                <span class="egg-donor-content__stage-line" aria-hidden="true"></span>
                                                                <p class="egg-donor-content__stage-text"><?php echo esc_html($stage['text']); ?></p>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                                <?php if ($stages_image): ?>
                                    <div class="egg-donor-content__stages-image">
                                        <img src="<?php echo esc_url($stages_image); ?>" alt="">
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($has_requirements): ?>
                    <div id="donor-requirements" class="egg-donor-content__section egg-donor-content__section--requirements">
                        <div class="egg-donor-content__title-wrap">
                            <h2 class="egg-donor-content__title">
                                <?php echo esc_html($requirements_title !== '' ? $requirements_title : __('Вимоги до кандидатів', 'igrmed')); ?>
                            </h2>
                        </div>
                        <div class="egg-donor-content__body">
                            <?php if (!empty($requirements_cards)): ?>
                                <div class="egg-donor-content__requirements-grid">
                                    <?php foreach ($requirements_cards as $req): ?>
                                        <div class="egg-donor-content__requirement-card">
                                            <?php if ($req['title'] !== ''): ?>
                                                <h3 class="egg-donor-content__requirement-title"><?php echo esc_html($req['title']); ?></h3>
                                            <?php endif; ?>
                                            <?php if ($req['text'] !== ''): ?>
                                                <p class="egg-donor-content__requirement-text"><?php echo esc_html($req['text']); ?></p>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($has_compensation): ?>
                    <div id="donor-compensation" class="egg-donor-content__section egg-donor-content__section--compensation">
                        <div class="egg-donor-content__title-wrap">
                            <h2 class="egg-donor-content__title">
                                <?php echo esc_html($compensation_title !== '' ? $compensation_title : __('Компенсація', 'igrmed')); ?>
                            </h2>
                        </div>
                        <div class="egg-donor-content__body">
                            <?php if ($compensation_text !== ''): ?>
                                <div class="egg-donor-content__compensation-text">
                                    <?php echo wp_kses_post($compensation_text); ?>
                                </div>
                            <?php endif; ?>
                            <?php if ($compensation_highlight !== ''): ?>
                                <div class="egg-donor-content__compensation-highlight-wrap">
                                    <span class="egg-donor-content__compensation-highlight-line" aria-hidden="true"></span>
                                    <div class="egg-donor-content__compensation-highlight">
                                        <?php echo wp_kses_post($compensation_highlight); ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (trim((string) get_post_field('post_content', get_the_ID())) !== ''): ?>
                    <div class="egg-donor-content__editor entry-content">
                        <?php the_content(); ?>
                    </div>
                <?php endif; ?>

                <?php get_template_part('templates/overflow-banner'); ?>
            </div>
        </div>
    </div>
</section>
