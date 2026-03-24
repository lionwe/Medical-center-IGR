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

$requirements_title = trim((string) get_field('requirements_title'));
$requirements_list = get_field('requirements_list');

$compensation_title = trim((string) get_field('compensation_title'));
$compensation_text = get_field('compensation_text');
$compensation_highlight = get_field('compensation_highlight');

$sections = [];

if ($intro_title !== '' || $intro_content !== '') {
    $sections[] = ['id' => 'donor-intro', 'title' => $intro_title !== '' ? $intro_title : __('Вступ', 'igrmed')];
}
if ($stages_title !== '' || !empty($stages_list)) {
    $sections[] = ['id' => 'donor-stages', 'title' => $stages_title !== '' ? $stages_title : __('Етапи участі', 'igrmed')];
}
if ($requirements_title !== '' || !empty($requirements_list)) {
    $sections[] = ['id' => 'donor-requirements', 'title' => $requirements_title !== '' ? $requirements_title : __('Вимоги до кандидатів', 'igrmed')];
}
if ($compensation_title !== '' || $compensation_text !== '' || $compensation_highlight !== '') {
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
                <?php if ($intro_title !== '' || $intro_content !== ''): ?>
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

                <?php if ($stages_title !== '' || !empty($stages_list) || $stages_image): ?>
                    <div id="donor-stages" class="egg-donor-content__section egg-donor-content__section--stages">
                        <div class="egg-donor-content__blob" aria-hidden="true">
                            <svg width="676" height="1030" viewBox="0 0 676 1030" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g filter="url(#filter0_f_806_1358_donor)">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M615.023 246.628C680.905 251.674 754.287 227.637 809.98 254.884C865.633 282.111 873.097 340.564 900.609 386.345C925.535 427.824 949.465 467.611 964.363 511.789C983.329 568.03 1047.19 635.272 999.926 680.857C949.596 729.394 837.571 682.386 762.647 706.319C701.527 725.843 678.378 788.968 615.023 803.806C546.258 819.911 470.46 810.603 403.584 790.412C331.31 768.591 241.541 741.914 221.028 684.851C199.385 624.643 300.768 574.175 303.427 511.789C305.948 452.636 217.065 402.583 235.509 345.087C253.743 288.244 323.217 242.067 396.008 223.182C466.997 204.766 540.398 240.914 615.023 246.628Z" fill="url(#paint0_linear_806_1358_donor)" fill-opacity="0.4"/>
                                </g>
                                <defs>
                                    <filter id="filter0_f_806_1358_donor" x="0" y="0" width="1235" height="1030" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                                        <feFlood flood-opacity="0" result="BackgroundImageFix"/>
                                        <feBlend mode="normal" in="SourceGraphic" in2="BackgroundImageFix" result="shape"/>
                                        <feGaussianBlur stdDeviation="109" result="effect1_foregroundBlur_806_1358_donor"/>
                                    </filter>
                                    <linearGradient id="paint0_linear_806_1358_donor" x1="-139.558" y1="518.047" x2="402.317" y2="1246.93" gradientUnits="userSpaceOnUse">
                                        <stop stop-color="white"/>
                                        <stop offset="1" stop-color="#4776BA"/>
                                    </linearGradient>
                                </defs>
                            </svg>
                        </div>
                        <div class="egg-donor-content__title-wrap">
                            <h2 class="egg-donor-content__title">
                                <?php echo esc_html($stages_title !== '' ? $stages_title : __('Етапи участі', 'igrmed')); ?>
                            </h2>
                        </div>
                        <div class="egg-donor-content__body">
                            <div class="egg-donor-content__stages-layout">
                                <?php if (!empty($stages_list)): ?>
                                    <div class="egg-donor-content__stages-list">
                                        <?php foreach ($stages_list as $index => $stage): ?>
                                            <?php
                                            $stage_title = trim((string) ($stage['title'] ?? ''));
                                            $stage_text = trim((string) ($stage['text'] ?? ''));
                                            ?>
                                            <?php if ($stage_title !== '' || $stage_text !== ''): ?>
                                                <div class="egg-donor-content__stage-item">
                                                    <div class="egg-donor-content__stage-number"><?php echo esc_html(sprintf('%02d', $index + 1)); ?></div>
                                                    <div class="egg-donor-content__stage-content">
                                                        <?php if ($stage_title !== ''): ?>
                                                            <h3 class="egg-donor-content__stage-title"><?php echo esc_html($stage_title); ?></h3>
                                                        <?php endif; ?>
                                                        <?php if ($stage_text !== ''): ?>
                                                            <div class="egg-donor-content__stage-text-wrap">
                                                                <span class="egg-donor-content__stage-line" aria-hidden="true"></span>
                                                                <p class="egg-donor-content__stage-text"><?php echo esc_html($stage_text); ?></p>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
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

                <?php if ($requirements_title !== '' || !empty($requirements_list)): ?>
                    <div id="donor-requirements" class="egg-donor-content__section egg-donor-content__section--requirements">
                        <div class="egg-donor-content__title-wrap">
                            <h2 class="egg-donor-content__title">
                                <?php echo esc_html($requirements_title !== '' ? $requirements_title : __('Вимоги до кандидатів', 'igrmed')); ?>
                            </h2>
                        </div>
                        <div class="egg-donor-content__body">
                            <?php if (!empty($requirements_list)): ?>
                                <div class="egg-donor-content__requirements-grid">
                                    <?php foreach ($requirements_list as $req): ?>
                                        <?php
                                        $req_title = trim((string) ($req['title'] ?? ''));
                                        $req_text = trim((string) ($req['text'] ?? ''));
                                        ?>
                                        <?php if ($req_title !== '' || $req_text !== ''): ?>
                                            <div class="egg-donor-content__requirement-card">
                                                <?php if ($req_title !== ''): ?>
                                                    <h3 class="egg-donor-content__requirement-title"><?php echo esc_html($req_title); ?></h3>
                                                <?php endif; ?>
                                                <?php if ($req_text !== ''): ?>
                                                    <p class="egg-donor-content__requirement-text"><?php echo esc_html($req_text); ?></p>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($compensation_title !== '' || $compensation_text !== '' || $compensation_highlight !== ''): ?>
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
            </div>
        </div>
    </div>
</section>
