<?php

$intro_title = trim((string) get_field('intro_title'));
$intro_content = get_field('intro_content');
$intro_content = is_string($intro_content) ? trim($intro_content) : '';

$advantages_title = trim((string) get_field('advantages_title'));
$advantages_content = get_field('advantages_content');
$advantages_content = is_string($advantages_content) ? trim($advantages_content) : '';

$when_title = trim((string) get_field('when_title'));
$when_content = get_field('when_content');
$when_content = is_string($when_content) ? trim($when_content) : '';

$procedures_title = trim((string) get_field('procedures_title'));
$procedures_rows = get_field('procedures_list');
$procedures_items = [];
if (is_array($procedures_rows)) {
    foreach ($procedures_rows as $row) {
        $item = trim((string) ($row['title'] ?? ''));
        if ($item !== '') {
            $procedures_items[] = $item;
        }
    }
}
$procedures_visible = 11;

$has_intro = $intro_content !== '';
$has_advantages = $advantages_content !== '';
$has_when = $when_content !== '';
$has_procedures = !empty($procedures_items);

$sections = [];

if ($has_intro) {
    $sections[] = ['id' => 'gyn-intro', 'title' => $intro_title];
}
if ($has_advantages) {
    $sections[] = ['id' => 'gyn-advantages', 'title' => $advantages_title];
}
if ($has_when) {
    $sections[] = ['id' => 'gyn-when', 'title' => $when_title];
}
if ($has_procedures) {
    $sections[] = ['id' => 'gyn-procedures', 'title' => $procedures_title];
}

if (empty($sections)) {
    return;
}
?>

<section class="gyn-content">
    <div class="container">
        <div class="gyn-content__layout catalog-wrap">
            <?php get_template_part('templates/content-sidebar', null, ['sections' => $sections]); ?>

            <div class="gyn-content__content">
                <?php if ($intro_title !== ''): ?>
                    <div id="gyn-intro" class="gyn-content__section gyn-content__section--intro">
                        <div class="gyn-content__section-title-wrap">
                            <h2 class="gyn-content__section-title">
                                <?php echo esc_html($intro_title); ?>
                            </h2>
                        </div>
                        <div class="gyn-content__section-body">
                            <?php if ($intro_content !== ''): ?>
                                <div class="gyn-content__lead">
                                    <?php echo wp_kses_post($intro_content); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($advantages_title !== ''): ?>
                    <div id="gyn-advantages" class="gyn-content__section gyn-content__section--advantages">
                        <div class="gyn-content__section-title-wrap">
                            <h2 class="gyn-content__section-title">
                                <?php echo esc_html($advantages_title); ?>
                            </h2>
                        </div>
                        <div class="gyn-content__section-body">
                            <?php if ($advantages_content !== ''): ?>
                                <div class="gyn-content__lead">
                                    <?php echo wp_kses_post($advantages_content); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($when_title !== ''): ?>
                    <div id="gyn-when" class="gyn-content__section gyn-content__section--when">
                        <div class="gyn-content__section-title-wrap">
                            <h2 class="gyn-content__section-title">
                                <?php echo esc_html($when_title); ?>
                            </h2>
                        </div>
                        <div class="gyn-content__section-body">
                            <?php if ($when_content !== ''): ?>
                                <div class="gyn-content__reasons-content">
                                    <?php echo wp_kses_post($when_content); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($has_procedures): ?>
                    <div id="gyn-procedures" class="gyn-content__section gyn-content__section--procedures">
                        <div class="gyn-content__section-title-wrap">
                            <h2 class="gyn-content__section-title">
                                <?php echo esc_html($procedures_title); ?>
                            </h2>
                        </div>
                        <div class="gyn-content__section-body">
                            <?php $has_more_proc = count($procedures_items) > $procedures_visible; ?>
                            <ul class="gyn-content__list-blocks">
                                <?php foreach ($procedures_items as $index => $item): ?>
                                    <li<?php if ($index >= $procedures_visible): ?> class="is-collapsible-service is-hidden-service"<?php endif; ?>><?php echo esc_html($item); ?></li>
                                <?php endforeach; ?>
                                <?php if ($has_more_proc): ?>
                                    <li class="gyn-content__list-blocks-more-item">
                                        <button
                                            class="gyn-content__list-more js-gyn-procedures-more"
                                            type="button"
                                            aria-expanded="false"
                                            data-more-label="<?php echo esc_attr(igrmed__('diagnostics_all_procedures')); ?>"
                                            data-less-label="<?php echo esc_attr(igrmed__('btn_close')); ?>"
                                        >
                                            <span class="gyn-content__list-more-text"><?php igrmed_e('diagnostics_all_procedures'); ?></span>
                                            <span class="gyn-content__list-more-arrow" aria-hidden="true">
                                                <svg viewBox="0 0 21 8" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M0 4h19.5M16.5 1l3 3-3 3" stroke="currentColor" stroke-width="1.2"/></svg>
                                            </span>
                                        </button>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>