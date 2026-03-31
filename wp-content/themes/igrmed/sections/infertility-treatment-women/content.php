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

    if (is_numeric($image)) {
        $image_url = wp_get_attachment_url((int) $image);
        return $image_url ? (string) $image_url : '';
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

        $title = trim((string) ($row['title'] ?? $row['stage_title'] ?? $row['label'] ?? $row['name'] ?? ''));
        $content = trim((string) ($row['content'] ?? $row['description'] ?? $row['text'] ?? ''));
        $icon = $extract_image_url($row['icon'] ?? $row['stage_icon'] ?? '');

        if ($title === '' && $content === '' && $icon === '') {
            continue;
        }

        $stages[] = [
            'icon' => $icon,
            'title' => $title,
            'content' => $content,
        ];
    }
}

$stages_bg_url = $extract_image_url(get_field('treatment_bg'));
$stages_bg_mobile_url = $extract_image_url(get_field('treatment_bg_mob'));

$methods_title = trim((string) get_field('methods_title'));
$methods_rows = get_field('methods_list');
$methods = [];

$advantages_title = trim((string) get_field('advantages_title'));
$advantages_rows = get_field('advantages_list');
$advantages = [];

if (is_array($advantages_rows)) {
    foreach ($advantages_rows as $row) {
        if (!is_array($row)) {
            continue;
        }

        $title = trim((string) ($row['title'] ?? $row['item_title'] ?? $row['name'] ?? ''));
        $content = trim((string) ($row['content'] ?? $row['item_content'] ?? $row['text'] ?? ''));

        if ($title === '' && $content === '') {
            continue;
        }

        $advantages[] = [
            'title' => $title,
            'content' => $content,
        ];
    }
}

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

$has_intro = $intro_content !== '';
$has_reasons = $reasons_subtitle !== '' || !empty($reasons_items);
$has_symptoms = !empty($symptoms_items);
$has_stages = !empty($stages);
$has_methods = !empty($methods);
$has_advantages = !empty($advantages);

$sections = [];

if ($has_intro) {
    $sections[] = ['id' => 'itw-intro', 'title' => $intro_title];
}
if ($has_reasons) {
    $sections[] = ['id' => 'itw-reasons', 'title' => $reasons_title];
}
if ($has_symptoms) {
    $sections[] = ['id' => 'itw-symptoms', 'title' => $symptoms_title];
}
if ($has_stages) {
    $sections[] = ['id' => 'itw-stages', 'title' => $stages_title];
}
if ($has_methods) {
    $sections[] = ['id' => 'itw-methods', 'title' => $methods_title];
}
if ($has_advantages) {
    $sections[] = ['id' => 'itw-advantages', 'title' => $advantages_title];
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
                <?php if ($intro_title !== ''): ?>
                    <div id="itw-intro" class="infertility-treatment-women__section js-itw-section donor-section">
                        <div class="infertility-treatment-women__section-title-wrap">
                            <h2 class="infertility-treatment-women__section-title">
                                <?php echo esc_html($intro_title); ?>
                            </h2>
                        </div>
                        <div class="infertility-treatment-women__section-body">
                            <?php if ($intro_content !== ''): ?>
                                <?php echo wp_kses_post($intro_content); ?>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($reasons_title !== ''): ?>
                    <div id="itw-reasons" class="infertility-treatment-women__section js-itw-section donor-section">
                        <div class="infertility-treatment-women__section-title-wrap">
                            <h2 class="infertility-treatment-women__section-title">
                                <?php echo esc_html($reasons_title); ?>
                            </h2>
                        </div>
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

                <?php if ($symptoms_title !== ''): ?>
                    <div id="itw-symptoms" class="infertility-treatment-women__section infertility-treatment-women__section--symptoms js-itw-section donor-section">
                        <div class="infertility-treatment-women__section-title-wrap infertility-treatment-women__section-title-wrap--symptoms">
                            <h2 class="infertility-treatment-women__section-title">
                                <?php echo esc_html($symptoms_title); ?>
                            </h2>
                        </div>
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

                <?php if ($stages_title !== ''): ?>
                    <div id="itw-stages" class="infertility-treatment-women__section js-itw-section donor-section">
                        <div class="infertility-treatment-women__section-title-wrap">
                            <h2 class="infertility-treatment-women__section-title">
                                <?php echo esc_html($stages_title); ?>
                            </h2>
                        </div>
                        <div
                            class="infertility-treatment-women__section-body infertility-treatment-women__section-body--stages">
                            <div
                                class="infertility-treatment-women__stages-bg"
                                <?php
                                $stages_bg_styles = [];
                                if ($stages_bg_url !== '') {
                                    $stages_bg_styles[] = '--itw-stages-bg: url(' . esc_url($stages_bg_url) . ')';
                                }
                                if ($stages_bg_mobile_url !== '') {
                                    $stages_bg_styles[] = '--itw-stages-bg-mobile: url(' . esc_url($stages_bg_mobile_url) . ')';
                                }
                                echo !empty($stages_bg_styles) ? 'style="' . esc_attr(implode('; ', $stages_bg_styles)) . '"' : '';
                                ?>
                            >
                                <div class="infertility-treatment-women__stages-layout">
                                    <?php if (!empty($stages)): ?>
                                        <?php foreach ($stages as $stage): ?>
                                            <article class="infertility-treatment-women__stage-item">
                                                <?php if ($stage['icon'] !== ''): ?>
                                                    <span class="infertility-treatment-women__stage-icon-wrap" aria-hidden="true">
                                                        <img src="<?php echo esc_url($stage['icon']); ?>" alt="" class="infertility-treatment-women__stage-icon" loading="lazy">
                                                    </span>
                                                <?php endif; ?>
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
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($methods_title !== ''): ?>
                    <div id="itw-methods" class="infertility-treatment-women__section js-itw-section donor-section">
                        <div class="infertility-treatment-women__section-title-wrap">
                            <h2 class="infertility-treatment-women__section-title">
                                <?php echo esc_html($methods_title); ?>
                            </h2>
                        </div>
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

                <?php if ($advantages_title !== ''): ?>
                    <div id="itw-advantages" class="infertility-treatment-women__section infertility-treatment-women__section--advantages js-itw-section donor-section">
                        <div class="infertility-treatment-women__section-title-wrap">
                            <h2 class="infertility-treatment-women__section-title">
                                <?php echo esc_html($advantages_title); ?>
                            </h2>
                        </div>
                        <div class="infertility-treatment-women__section-body infertility-treatment-women__section-body--advantages">
                            <?php if (!empty($advantages)): ?>
                                <div class="infertility-treatment-women__list">
                                    <?php foreach ($advantages as $item): ?>
                                        <article class="infertility-treatment-women__item">
                                            <?php if ($item['title'] !== ''): ?>
                                                <h3><?php echo esc_html($item['title']); ?></h3>
                                            <?php endif; ?>
                                            <?php if ($item['content'] !== ''): ?>
                                                <?php echo wp_kses_post(wpautop($item['content'])); ?>
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