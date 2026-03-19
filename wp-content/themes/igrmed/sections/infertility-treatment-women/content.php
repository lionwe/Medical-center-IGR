<?php
/**
 * Section: Infertility Treatment Women Content
 * Location: Infertility Treatment Women page
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

$extract_image_url = static function ($image): string {
    if (is_array($image)) {
        return trim((string) ($image['url'] ?? ''));
    }

    return trim((string) $image);
};

$intro_title = trim((string) get_field('intro_title'));
$intro_content = get_field('intro_content');
$intro_content = is_string($intro_content) ? trim($intro_content) : '';

$reasons_title = trim((string) get_field('reasons_title'));
$reasons_subtitle = trim((string) get_field('subtitle'));
$reasons_items = $extract_text_rows(get_field('reasons_list'));

$symptoms_title = trim((string) get_field('symptoms_title'));
$symptoms_items = $extract_text_rows(get_field('symptoms_grid'));

$stages_title = trim((string) get_field('treatment_title'));
$stages_rows = get_field('treatment_stages');
$stages = [];

if (is_array($stages_rows)) {
    foreach ($stages_rows as $index => $row) {
        if (!is_array($row)) {
            continue;
        }

        $title = trim((string) ($row['title'] ?? $row['stage_title'] ?? $row['name'] ?? ''));
        $content = trim((string) ($row['content'] ?? $row['description'] ?? $row['text'] ?? ''));

        if ($title === '' && $content === '') {
            continue;
        }

        $stages[] = [
            'num' => str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT),
            'title' => $title,
            'content' => $content,
        ];
    }
}

$stages_bg_url = $extract_image_url(get_field('treatment_bg'));

$methods_title = trim((string) get_field('methods_title'));
$methods_rows = get_field('methods_list');
$methods = [];

if (is_array($methods_rows)) {
    foreach ($methods_rows as $row) {
        if (is_string($row)) {
            $text = trim($row);
            if ($text !== '') {
                $methods[] = [
                    'title' => '',
                    'content' => $text,
                ];
            }
            continue;
        }

        if (!is_array($row)) {
            continue;
        }

        $title = trim((string) ($row['title'] ?? $row['item_title'] ?? $row['name'] ?? ''));
        $content = trim((string) ($row['content'] ?? $row['item_content'] ?? $row['text'] ?? ''));

        if ($title === '' && $content === '') {
            continue;
        }

        $methods[] = [
            'title' => $title,
            'content' => $content,
        ];
    }
}

$sections = [];

if ($intro_title !== '' || $intro_content !== '') {
    $sections[] = ['id' => 'itw-intro', 'title' => $intro_title !== '' ? $intro_title : __('Вступ', 'igrmed')];
}
if ($reasons_title !== '' || $reasons_subtitle !== '' || !empty($reasons_items)) {
    $sections[] = ['id' => 'itw-reasons', 'title' => $reasons_title !== '' ? $reasons_title : __('Причини', 'igrmed')];
}
if ($symptoms_title !== '' || !empty($symptoms_items)) {
    $sections[] = ['id' => 'itw-symptoms', 'title' => $symptoms_title !== '' ? $symptoms_title : __('Симптоми', 'igrmed')];
}
if ($stages_title !== '' || !empty($stages) || $stages_bg_url !== '') {
    $sections[] = ['id' => 'itw-stages', 'title' => $stages_title !== '' ? $stages_title : __('Етапи лікування', 'igrmed')];
}
if ($methods_title !== '' || !empty($methods)) {
    $sections[] = ['id' => 'itw-methods', 'title' => $methods_title !== '' ? $methods_title : __('Методи лікування', 'igrmed')];
}

if (empty($sections)) {
    return;
}
?>

<section class="infertility-treatment-women-content">
    <div class="container">
        <div class="infertility-treatment-women__layout catalog-wrap">
                <?php get_template_part('templates/content-sidebar', null, ['sections' => $sections]); ?>

                <div class="infertility-treatment-women__content">
                    <?php if ($intro_title !== '' || $intro_content !== ''): ?>
                        <div id="itw-intro" class="infertility-treatment-women__section js-itw-section donor-section">
                            <h2 class="infertility-treatment-women__section-title"><?php echo esc_html($intro_title !== '' ? $intro_title : __('Вступ', 'igrmed')); ?></h2>
                            <div class="infertility-treatment-women__section-body">
                                <?php if ($intro_content !== ''): ?>
                                    <?php echo wp_kses_post($intro_content); ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if ($reasons_title !== '' || $reasons_subtitle !== '' || !empty($reasons_items)): ?>
                        <div id="itw-reasons" class="infertility-treatment-women__section js-itw-section donor-section">
                            <h2 class="infertility-treatment-women__section-title"><?php echo esc_html($reasons_title !== '' ? $reasons_title : __('Причини', 'igrmed')); ?></h2>
                            <div class="infertility-treatment-women__section-body">
                                <?php if ($reasons_subtitle !== ''): ?>
                                    <p class="infertility-treatment-women__subtitle"><?php echo esc_html($reasons_subtitle); ?></p>
                                <?php endif; ?>

                                <?php if (!empty($reasons_items)): ?>
                                    <ul class="infertility-treatment-women__reasons-list">
                                        <?php foreach ($reasons_items as $item): ?>
                                            <li><?php echo esc_html($item); ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if ($symptoms_title !== '' || !empty($symptoms_items)): ?>
                        <div id="itw-symptoms" class="infertility-treatment-women__section js-itw-section donor-section">
                            <h2 class="infertility-treatment-women__section-title"><?php echo esc_html($symptoms_title !== '' ? $symptoms_title : __('Симптоми', 'igrmed')); ?></h2>
                            <div class="infertility-treatment-women__section-body">
                                <?php if (!empty($symptoms_items)): ?>
                                    <div class="infertility-treatment-women__symptoms-grid">
                                        <?php foreach ($symptoms_items as $item): ?>
                                            <article class="infertility-treatment-women__symptoms-card">
                                                <span class="infertility-treatment-women__symptoms-accent" aria-hidden="true"></span>
                                                <?php echo esc_html($item); ?>
                                            </article>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if ($stages_title !== '' || !empty($stages) || $stages_bg_url !== ''): ?>
                        <div id="itw-stages" class="infertility-treatment-women__section js-itw-section donor-section">
                            <h2 class="infertility-treatment-women__section-title"><?php echo esc_html($stages_title !== '' ? $stages_title : __('Етапи лікування', 'igrmed')); ?></h2>
                            <div class="infertility-treatment-women__section-body">
                                <div class="infertility-treatment-women__stages-layout">
                                    <?php if (!empty($stages)): ?>
                                        <div class="infertility-treatment-women__stages-list">
                                            <?php foreach ($stages as $stage): ?>
                                                <article class="infertility-treatment-women__stage-item">
                                                    <span class="infertility-treatment-women__stage-num"><?php echo esc_html($stage['num']); ?></span>
                                                    <div class="infertility-treatment-women__stage-content">
                                                        <?php if ($stage['title'] !== ''): ?>
                                                            <h3><?php echo esc_html($stage['title']); ?></h3>
                                                        <?php endif; ?>
                                                        <?php if ($stage['content'] !== ''): ?>
                                                            <p><?php echo esc_html($stage['content']); ?></p>
                                                        <?php endif; ?>
                                                    </div>
                                                </article>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>

                                    <?php if ($stages_bg_url !== ''): ?>
                                        <div class="infertility-treatment-women__stages-image-wrap">
                                            <img src="<?php echo esc_url($stages_bg_url); ?>" alt="" class="infertility-treatment-women__stages-image" loading="lazy">
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if ($methods_title !== '' || !empty($methods)): ?>
                        <div id="itw-methods" class="infertility-treatment-women__section js-itw-section donor-section">
                            <h2 class="infertility-treatment-women__section-title"><?php echo esc_html($methods_title !== '' ? $methods_title : __('Методи лікування', 'igrmed')); ?></h2>
                            <div class="infertility-treatment-women__section-body">
                                <?php if (!empty($methods)): ?>
                                    <div class="infertility-treatment-women__methods-list">
                                        <?php foreach ($methods as $method): ?>
                                            <article class="infertility-treatment-women__method-item">
                                                <?php if ($method['title'] !== ''): ?>
                                                    <h3><?php echo esc_html($method['title']); ?></h3>
                                                <?php endif; ?>
                                                <?php if ($method['content'] !== ''): ?>
                                                    <?php echo wp_kses_post(wpautop($method['content'])); ?>
                                                <?php endif; ?>
                                            </article>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
        </div>
    </div>
</section>