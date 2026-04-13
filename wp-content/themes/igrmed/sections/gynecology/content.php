<?php
/**
 * Гінекологія — контент сторінки.
 */

$extract_text_rows = static function ($rows, array $keys = ['item', 'text', 'title', 'name']): array {
    $items = [];

    if (!is_array($rows)) {
        return $items;
    }

    foreach ($rows as $row) {
        if (is_string($row)) {
            $value = trim($row);
            if ($value !== '') {
                $items[] = $value;
            }
            continue;
        }

        if (!is_array($row)) {
            continue;
        }

        foreach ($keys as $key) {
            $value = trim((string) ($row[$key] ?? ''));
            if ($value !== '') {
                $items[] = $value;
                break;
            }
        }
    }

    return $items;
};

$intro_title = trim((string) get_field('intro_title'));
$intro_content = get_field('intro_content');
$intro_content = is_string($intro_content) ? trim($intro_content) : '';

$advantages_title = trim((string) get_field('advantages_title'));
$advantages_content = get_field('advantages_content');
$advantages_content = is_string($advantages_content) ? trim($advantages_content) : '';

$when_title = trim((string) get_field('when_title'));
$when_list = $extract_text_rows(get_field('when_list'), ['text', 'item', 'title', 'name']);

$procedures_title = trim((string) get_field('procedures_title'));
$procedures_list = $extract_text_rows(get_field('procedures_list'), ['name', 'text', 'item', 'title']);

$has_intro = $intro_content !== '';
$has_advantages = $advantages_content !== '';
$has_when = !empty($when_list);
$has_procedures = !empty($procedures_list);

$sections = [];
if ($has_intro) {
    $sections[] = ['id' => 'gy-intro', 'title' => $intro_title !== '' ? $intro_title : igrmed__('services_title')];
}
if ($has_advantages) {
    $sections[] = ['id' => 'gy-advantages', 'title' => $advantages_title !== '' ? $advantages_title : igrmed__('section_advantages')];
}
if ($has_when) {
    $sections[] = ['id' => 'gy-when', 'title' => $when_title !== '' ? $when_title : igrmed__('section_when_visit')];
}
if ($has_procedures) {
    $sections[] = ['id' => 'gy-procedures', 'title' => $procedures_title !== '' ? $procedures_title : igrmed__('section_procedures')];
}

if (empty($sections) && trim((string) get_post_field('post_content', get_the_ID())) === '') {
    return;
}
?>

<section class="gynecology-content">
    <div class="container">
        <div class="gynecology-content__layout catalog-wrap">
            <?php if (!empty($sections)): ?>
                <?php get_template_part('templates/content-sidebar', null, ['sections' => $sections]); ?>
            <?php endif; ?>

            <div class="gynecology-content__content">
                <?php if ($has_intro): ?>
                    <div id="gy-intro" class="gynecology-content__section gynecology-content__section--intro">
                        <div class="gynecology-content__title-wrap">
                            <h2 class="gynecology-content__title">
                                <?php echo esc_html($intro_title !== '' ? $intro_title : igrmed__('services_title')); ?>
                            </h2>
                        </div>
                        <div class="gynecology-content__body">
                            <?php if ($intro_content !== ''): ?>
                                <div class="gynecology-content__lead">
                                    <?php echo wp_kses_post($intro_content); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($has_advantages): ?>
                    <div id="gy-advantages" class="gynecology-content__section gynecology-content__section--advantages">
                        <div class="gynecology-content__title-wrap">
                            <h2 class="gynecology-content__title">
                                <?php echo esc_html($advantages_title !== '' ? $advantages_title : igrmed__('section_advantages')); ?>
                            </h2>
                        </div>
                        <div class="gynecology-content__body">
                            <?php if ($advantages_content !== ''): ?>
                                <?php echo wp_kses_post($advantages_content); ?>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($has_when): ?>
                    <div id="gy-when" class="gynecology-content__section">
                        <div class="gynecology-content__title-wrap">
                            <h2 class="gynecology-content__title">
                                <?php echo esc_html($when_title !== '' ? $when_title : igrmed__('section_when_visit')); ?>
                            </h2>
                        </div>
                        <div class="gynecology-content__body">
                            <?php if (!empty($when_list)): ?>
                                <ul class="gynecology-content__reasons-list">
                                    <?php foreach ($when_list as $item): ?>
                                        <li><?php echo esc_html($item); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($has_procedures): ?>
                    <div id="gy-procedures" class="gynecology-content__section gynecology-content__section--procedures">
                        <div class="gynecology-content__title-wrap">
                            <h2 class="gynecology-content__title">
                                <?php echo esc_html($procedures_title !== '' ? $procedures_title : igrmed__('section_procedures')); ?>
                            </h2>
                        </div>
                        <div class="gynecology-content__body">
                            <?php if (!empty($procedures_list)): ?>
                                <?php
                                $procedures_visible_limit = wp_is_mobile() ? 5 : 11;
                                $should_collapse_procedures = count($procedures_list) > $procedures_visible_limit;
                                ?>
                                <ul class="gynecology-content__procedures-list">
                                    <?php foreach ($procedures_list as $index => $item): ?>
                                        <?php
                                        $item_classes = ['gynecology-content__procedures-item'];
                                        if ($should_collapse_procedures && $index >= $procedures_visible_limit) {
                                            $item_classes[] = 'is-hidden-service';
                                        }
                                        ?>
                                        <li class="<?php echo esc_attr(implode(' ', $item_classes)); ?>">
                                            <?php echo esc_html($item); ?>
                                        </li>
                                    <?php endforeach; ?>
                                     <?php if ($should_collapse_procedures): ?>
                                    <div class="gynecology-content__procedures-item gynecology-content__list-more-item">
                                        <button type="button" class="gynecology-content__list-more" aria-expanded="false">
                                            <span class="gynecology-content__list-more-text"><?php echo esc_html(igrmed__('btn_read_more')); ?></span>
                                            <span class="gynecology-content__list-more-arrow" aria-hidden="true">
                                                <?php echo igrmed_get_svg('read-more-arrow'); ?>
                                            </span>
                                        </button>
                                </div>
                                <?php endif; ?>
                                </ul>
                               
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (trim((string) get_post_field('post_content', get_the_ID())) !== ''): ?>
                    <div class="gynecology-content__editor entry-content">
                        <?php the_content(); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>