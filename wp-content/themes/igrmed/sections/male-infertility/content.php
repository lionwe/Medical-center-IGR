<?php
/**
 * Чоловіче безпліддя — контент сторінки.
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

$causes_title = trim((string) get_field('causes_title'));
$causes_subtitle = trim((string) get_field('causes_subtitle'));
$causes_list = $extract_text_rows(get_field('causes_list'), ['item', 'text', 'title', 'name']);

$when_title = trim((string) get_field('when_title'));
$when_list = $extract_text_rows(get_field('when_list'), ['item', 'text', 'title', 'name']);

$stages_title = trim((string) get_field('stages_title'));
$stages_bg_desc = trim((string) get_field('stages_bg_desc'));
$stages_bg_url = $extract_image_url(get_field('stages_bg'));
$stages_bg_mobile_url = $extract_image_url(get_field('stages_bg_mob'));

// Guard: if URL is accidentally entered into description field,
// do not render it as visible text above the timeline.
if ($stages_bg_desc !== '' && filter_var($stages_bg_desc, FILTER_VALIDATE_URL)) {
    $stages_bg_desc = '';
}

if ($stages_bg_url === '') {
    $stages_bg_url = 'http://igr-medical.local/wp-content/uploads/2026/03/group-1000001824-1-scaled.webp';
}

if ($stages_bg_mobile_url === '') {
    $stages_bg_mobile_url = $stages_bg_url;
}
$stages_rows = get_field('stages_list');
$stages_list = [];

if (is_array($stages_rows)) {
    foreach ($stages_rows as $row) {
        if (!is_array($row)) {
            continue;
        }

        $stages_list[] = [
            'icon' => trim((string) ($row['icon'] ?? '')),
            'title' => trim((string) ($row['title'] ?? '')),
            'text' => trim((string) ($row['text'] ?? '')),
        ];
    }

    $stages_list = array_values(array_filter($stages_list, static function ($item) {
        return $item['icon'] !== '' || $item['title'] !== '' || $item['text'] !== '';
    }));
}

$methods_title = trim((string) get_field('methods_title'));
$methods_content = get_field('methods_list');
$methods_content = is_string($methods_content) ? trim($methods_content) : '';

$has_intro = $intro_content !== '';
$has_causes = $causes_subtitle !== '' || !empty($causes_list);
$has_when = !empty($when_list);
$has_stages = $stages_bg_desc !== '' || !empty($stages_list);
$has_methods = $methods_content !== '';

$sections = [];

if ($has_intro) {
    $sections[] = ['id' => 'mi-intro', 'title' => $intro_title];
}
if ($has_causes) {
    $sections[] = ['id' => 'mi-causes', 'title' => $causes_title];
}
if ($has_when) {
    $sections[] = ['id' => 'mi-when', 'title' => $when_title];
}
if ($has_stages) {
    $sections[] = ['id' => 'mi-stages', 'title' => $stages_title];
}
if ($has_methods) {
    $sections[] = ['id' => 'mi-methods', 'title' => $methods_title];
}

if (empty($sections)) {
    return;
}
?>

<section class="male-infertility-content">
    <div class="container">
        <div class="male-infertility-content__layout catalog-wrap">
            <?php get_template_part('templates/content-sidebar', null, ['sections' => $sections]); ?>

            <div class="male-infertility-content__content">
                <?php if ($has_intro): ?>
                    <div id="mi-intro" class="male-infertility-content__section male-infertility-content__section--intro">
                        <div class="male-infertility-content__title-wrap">
                            <h2 class="male-infertility-content__title">
                                <?php echo esc_html($intro_title !== '' ? $intro_title : __('Що таке чоловіче безпліддя?', 'igrmed')); ?>
                            </h2>
                        </div>
                        <div class="male-infertility-content__body">
                            <?php if ($intro_content !== ''): ?>
                                <?php echo wp_kses_post($intro_content); ?>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($has_causes): ?>
                    <div id="mi-causes" class="male-infertility-content__section">
                        <div class="male-infertility-content__title-wrap">
                            <h2 class="male-infertility-content__title">
                                <?php echo esc_html($causes_title !== '' ? $causes_title : __('Причини безпліддя у чоловіків', 'igrmed')); ?>
                            </h2>
                        </div>
                        <div class="male-infertility-content__body">
                            <?php if ($causes_subtitle !== ''): ?>
                                <p class="male-infertility-content__subtitle"><?php echo esc_html($causes_subtitle); ?></p>
                            <?php endif; ?>
                            <?php if (!empty($causes_list)): ?>
                                <ul class="male-infertility-content__reasons-list">
                                    <?php foreach ($causes_list as $item): ?>
                                        <li><?php echo esc_html($item); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($has_when): ?>
                    <div id="mi-when" class="male-infertility-content__section male-infertility-content__section--when">
                        <div class="male-infertility-content__title-wrap male-infertility-content__title-wrap--when">
                            <h2 class="male-infertility-content__title">
                                <?php echo esc_html($when_title !== '' ? $when_title : __('Коли варто звернутися за лікуванням?', 'igrmed')); ?>
                            </h2>
                        </div>
                        <div class="male-infertility-content__body">
                            <?php if (!empty($when_list)): ?>
                                <div class="male-infertility-content__symptoms-grid">
                                    <?php foreach ($when_list as $item): ?>
                                        <article class="male-infertility-content__symptoms-card">
                                            <span class="male-infertility-content__symptoms-accent" aria-hidden="true"></span>
                                            <?php echo esc_html($item); ?>
                                        </article>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($has_stages): ?>
                    <div id="mi-stages" class="male-infertility-content__section male-infertility-content__section--stages">
                        <div class="male-infertility-content__title-wrap">
                            <h2 class="male-infertility-content__title">
                                <?php echo esc_html($stages_title !== '' ? $stages_title : __('Етапи лікування безпліддя', 'igrmed')); ?>
                            </h2>
                        </div>
                        <div class="male-infertility-content__body male-infertility-content__body--stages">
                            <?php if ($stages_bg_desc !== ''): ?>
                                <strong class="male-infertility-content__subtitle">
                                    <?php echo esc_html($stages_bg_desc); ?>
                                </strong>
                            <?php endif; ?>
                            <?php if (!empty($stages_list) || $stages_bg_url !== '' || $stages_bg_mobile_url !== ''): ?>
                                <div
                                    class="male-infertility-content__stages-bg"
                                    <?php
                                    $stages_bg_styles = [];
                                    if ($stages_bg_url !== '') {
                                        $stages_bg_styles[] = '--mi-stages-bg: url(' . esc_url($stages_bg_url) . ')';
                                    }
                                    if ($stages_bg_mobile_url !== '') {
                                        $stages_bg_styles[] = '--mi-stages-bg-mobile: url(' . esc_url($stages_bg_mobile_url) . ')';
                                    }
                                    echo !empty($stages_bg_styles) ? 'style="' . esc_attr(implode('; ', $stages_bg_styles)) . '"' : '';
                                    ?>
                                >
                                    <div class="male-infertility-content__stages-layout">
                                        <?php foreach ($stages_list as $stage): ?>
                                            <article class="male-infertility-content__stage-item">
                                                <?php if ($stage['icon'] !== ''): ?>
                                                    <span class="male-infertility-content__stage-icon-wrap" aria-hidden="true">
                                                        <?php
                                                        $icon_url = is_array($stage['icon']) ? $stage['icon']['url'] : $stage['icon'];
                                                        $icon_alt = is_array($stage['icon']) ? $stage['icon']['alt'] : '';
                                                        if (is_numeric($stage['icon'])) {
                                                            $icon_alt = get_post_meta((int) $stage['icon'], '_wp_attachment_image_alt', true);
                                                        }
                                                        get_picture([
                                                            'src' => $icon_url,
                                                            'alt' => $icon_alt,
                                                            'class' => 'male-infertility-content__stage-icon',
                                                            'lazy' => true,
                                                        ]);
                                                        ?>
                                                    </span>
                                                <?php endif; ?>
                                                <div class="male-infertility-content__stage-content">
                                                    <?php if ($stage['title'] !== ''): ?>
                                                        <h3><?php echo esc_html($stage['title']); ?></h3>
                                                    <?php endif; ?>
                                                    <?php if ($stage['text'] !== ''): ?>
                                                        <p><?php echo esc_html($stage['text']); ?></p>
                                                    <?php endif; ?>
                                                </div>
                                            </article>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($has_methods): ?>
                    <div id="mi-methods" class="male-infertility-content__section male-infertility-content__section--methods">
                        <div class="male-infertility-content__title-wrap">
                            <h2 class="male-infertility-content__title">
                                <?php echo esc_html($methods_title !== '' ? $methods_title : __('Методи лікування безпліддя', 'igrmed')); ?>
                            </h2>
                        </div>
                        <div class="male-infertility-content__body">
                            <?php if ($methods_content !== ''): ?>
                                <div class="male-infertility-content__methods">
                                    <?php echo wp_kses_post($methods_content); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</section>
