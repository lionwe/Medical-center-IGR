<?php

$title_fallback = [
    'intro' => __('Що таке ЕКЗ', 'igrmed'),
    'advantages' => __('Переваги клініки', 'igrmed'),
    'indications' => __('Показання', 'igrmed'),
    'contraindications' => __('Протипоказання', 'igrmed'),
    'stages' => __('Етапи процедури', 'igrmed'),
    'success' => __('Успішність', 'igrmed'),
    'candidates' => __('Кому підходить', 'igrmed'),
    'programs' => __('Програми ЕКЗ', 'igrmed'),
];

$extract_text_rows = static function ($rows, array $keys = ['text', 'item', 'title', 'name']): array {
    $items = [];
    if (!is_array($rows)) {
        return $items;
    }
    foreach ($rows as $row) {
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
$intro_content = trim((string) get_field('intro_content'));

$advantages_title = trim((string) get_field('advantages_title'));
$advantages_content = trim((string) get_field('advantages_content'));

$indications_title = trim((string) get_field('indications_title'));
$indications_subtitle = trim((string) get_field('indications_subtitle'));
$indications_list = [];
foreach ((array) get_field('indications_grid') as $row) {
    $text = trim((string) ($row['text'] ?? ''));
    if ($text !== '') {
        $indications_list[] = $text;
    }
}

$contra_title = trim((string) get_field('contraindications_title'));
$contra_intro = trim((string) get_field('contraindications_intro'));
$contra_list = $extract_text_rows(get_field('contraindications_list'), ['text']);

$stages_title = trim((string) get_field('stages_title'));
$stages_intro = trim((string) get_field('stages_intro'));
$stages_list = [];
foreach ((array) get_field('stages_accordion') as $row) {
    $label = trim((string) ($row['label'] ?? ''));
    $description = trim((string) ($row['description'] ?? ''));
    if ($label !== '' || $description !== '') {
        $stages_list[] = compact('label', 'description');
    }
}

$success_title = trim((string) get_field('success_title'));
$success_intro = trim((string) get_field('success_intro'));
$success_content = trim((string) get_field('success_content'));

$candidates_title = trim((string) get_field('candidates_title'));
$candidates_subtitle = trim((string) get_field('candidates_subtitle'));
$candidates_list = $extract_text_rows(get_field('candidates_list'), ['text']);

$programs_title = trim((string) get_field('programs_title'));
$programs_rows = get_field('programa');
$programs_list = [];
if (is_array($programs_rows)) {
    foreach ($programs_rows as $row) {
        if (!is_array($row)) {
            continue;
        }

        $program_title = trim((string) ($row['title'] ?? $row['name'] ?? ''));
        $program_text = trim((string) ($row['text'] ?? $row['description'] ?? ''));
        $program_price = trim((string) ($row['price'] ?? $row['price_editor'] ?? ''));
        $program_image = $row['image'] ?? $row['img'] ?? $row['photo'] ?? null;
        $program_image_url = '';
        $program_image_alt = '';

        if (is_array($program_image)) {
            $program_image_url = trim((string) ($program_image['url'] ?? ''));
            $program_image_alt = trim((string) ($program_image['alt'] ?? ''));
        } elseif (is_numeric($program_image)) {
            $image_id = (int) $program_image;
            if ($image_id > 0) {
                $program_image_url = trim((string) wp_get_attachment_image_url($image_id, 'full'));
                $program_image_alt = trim((string) get_post_meta($image_id, '_wp_attachment_image_alt', true));
            }
        } elseif (is_string($program_image)) {
            $program_image_url = trim($program_image);
        }
        $program_items = [];

        $items_source = $row['list'] ?? $row['items'] ?? $row['ol'] ?? [];
        if (is_array($items_source)) {
            $program_items = $extract_text_rows($items_source, ['text', 'item', 'title', 'name']);
        }

        if (
            $program_title === '' &&
            $program_text === '' &&
            $program_price === '' &&
            empty($program_items) &&
            $program_image_url === ''
        ) {
            continue;
        }

        $programs_list[] = [
            'title' => $program_title,
            'text' => $program_text,
            'price' => $program_price,
            'items' => $program_items,
            'image_url' => $program_image_url,
            'image_alt' => $program_image_alt,
        ];
    }
}

$content_sections = [];

if ($intro_title !== '' || $intro_content !== '') {
    $content_sections['intro'] = [
        'heading' => $intro_title !== '' ? $intro_title : $title_fallback['intro'],
        'intro_content' => $intro_content,
    ];
}

if ($advantages_title !== '' || $advantages_content !== '') {
    $content_sections['advantages'] = [
        'heading' => $advantages_title !== '' ? $advantages_title : $title_fallback['advantages'],
        'advantages_content' => $advantages_content,
    ];
}

if ($indications_title !== '' || $indications_subtitle !== '' || $indications_list) {
    $content_sections['indications'] = [
        'heading' => $indications_title !== '' ? $indications_title : $title_fallback['indications'],
        'indications_subtitle' => $indications_subtitle,
        'indications_list' => $indications_list,
    ];
}

if ($contra_title !== '' || $contra_intro !== '' || $contra_list) {
    $content_sections['contraindications'] = [
        'heading' => $contra_title !== '' ? $contra_title : $title_fallback['contraindications'],
        'contra_intro' => $contra_intro,
        'contra_list' => $contra_list,
    ];
}

if ($stages_title !== '' || $stages_intro !== '' || $stages_list) {
    $content_sections['stages'] = [
        'heading' => $stages_title !== '' ? $stages_title : $title_fallback['stages'],
        'stages_intro' => $stages_intro,
        'stages_list' => $stages_list,
    ];
}

if ($success_title !== '' || $success_intro !== '' || $success_content !== '') {
    $content_sections['success'] = [
        'heading' => $success_title !== '' ? $success_title : $title_fallback['success'],
        'success_intro' => $success_intro,
        'success_content' => $success_content,
    ];
}

if ($candidates_title !== '' || $candidates_subtitle !== '' || $candidates_list) {
    $content_sections['candidates'] = [
        'heading' => $candidates_title !== '' ? $candidates_title : $title_fallback['candidates'],
        'candidates_subtitle' => $candidates_subtitle,
        'candidates_list' => $candidates_list,
    ];
}

if ($programs_title !== '' || $programs_list) {
    $content_sections['programs'] = [
        'heading' => $programs_title !== '' ? $programs_title : $title_fallback['programs'],
        'programs_list' => $programs_list,
    ];
}

if (empty($content_sections)) {
    return;
}

$sections = [];
foreach ($content_sections as $type => $data) {
    $sections[] = [
        'id' => 'ekz-' . $type,
        'title' => $data['heading'],
    ];
}
?>

<section class="ekz-content">
    <div class="container">
        <div class="ekz-content__layout catalog-wrap">
            <?php get_template_part('templates/content-sidebar', null, ['sections' => $sections]); ?>

            <div class="ekz-content__content">

                <?php foreach ($content_sections as $type => $data) : ?>
                    <section id="ekz-<?php echo esc_attr($type); ?>" class="ekz-content__section ekz-content__section--<?php echo esc_attr($type); ?>">
                        <div class="ekz-content__section-title-wrap">
                            <h2 class="ekz-content__section-title">
                                <?php echo esc_html($data['heading']); ?>
                            </h2>
                        </div>
                        <div class="ekz-content__section-body">
                            <?php
                            switch ($type) {
                                case 'intro':
                                    ?>
                                    <div class="ekz-content__lead">
                                        <?php echo wp_kses_post($data['intro_content']); ?>
                                    </div>
                                    <?php
                                    break;

                                case 'advantages':
                                    ?>
                                    <div class="ekz-content__lead">
                                        <?php echo wp_kses_post($data['advantages_content']); ?>
                                    </div>
                                    <?php
                                    break;

                                case 'indications':
                                    if ($data['indications_subtitle'] !== '') :
                                        ?>
                                        <p class="ekz-content__section-subtitle">
                                            <?php echo esc_html($data['indications_subtitle']); ?>
                                        </p>
                                    <?php
                                    endif;
                                    if ($data['indications_list']) :
                                        ?>
                                        <div class="ekz-content__indications-grid">
                                            <?php foreach ($data['indications_list'] as $item) : ?>
                                                <article class="ekz-content__indication-card">
                                                    <span class="ekz-content__indication-accent" aria-hidden="true"></span>
                                                    <?php echo wp_kses_post($item); ?>
                                                </article>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php
                                    endif;
                                    break;

                                case 'contraindications':
                                    if ($data['contra_intro'] !== '') :
                                        ?>
                                        <p class="ekz-content__section-subtitle">
                                            <?php echo esc_html($data['contra_intro']); ?>
                                        </p>
                                    <?php
                                    endif;
                                    if ($data['contra_list']) :
                                        ?>
                                        <div class="ekz-content__list">
                                            <?php foreach ($data['contra_list'] as $item) : ?>
                                                <article class="ekz-content__list-item">
                                                    <?php echo wp_kses_post($item); ?>
                                                </article>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php
                                    endif;
                                    break;

                                case 'stages':
                                    if ($data['stages_intro'] !== '') :
                                        ?>
                                        <strong class="ekz-content__section-subtitle">
                                            <?php echo wp_kses_post($data['stages_intro']); ?>
                                        </strong>
                                    <?php
                                    endif;
                                    if ($data['stages_list']) :
                                        ?>
                                        <div class="ekz-content__stages-accordion">
                                            <?php foreach ($data['stages_list'] as $stage) : ?>
                                                <details class="ekz-content__stage-item">
                                                    <summary class="ekz-content__stage-trigger">
                                                        <span class="ekz-content__stage-label">
                                                            <?php echo esc_html($stage['label']); ?>
                                                        </span>
                                                    </summary>
                                                    <div class="ekz-content__stage-desc">
                                                        <div class="ekz-content__stage-desc-inner">
                                                            <?php echo wp_kses_post($stage['description']); ?>
                                                        </div>
                                                    </div>
                                                </details>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php
                                    endif;
                                    break;

                                case 'success':
                                    if ($data['success_intro'] !== '') :
                                        ?>
                                        <p class="ekz-content__section-subtitle">
                                            <?php echo esc_html($data['success_intro']); ?>
                                        </p>
                                    <?php
                                    endif;
                                    if ($data['success_content'] !== '') :
                                        ?>
                                        <div class="ekz-content__lead">
                                            <?php echo wp_kses_post($data['success_content']); ?>
                                        </div>
                                    <?php
                                    endif;
                                    break;

                                case 'candidates':
                                    if ($data['candidates_subtitle'] !== '') :
                                        ?>
                                        <p class="ekz-content__section-subtitle">
                                            <?php echo esc_html($data['candidates_subtitle']); ?>
                                        </p>
                                    <?php
                                    endif;
                                    if ($data['candidates_list']) :
                                        ?>
                                        <div class="ekz-content__list">
                                            <?php foreach ($data['candidates_list'] as $item) : ?>
                                                <article class="ekz-content__list-item">
                                                    <?php echo esc_html($item); ?>
                                                </article>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php
                                    endif;
                                    break;

                                case 'programs':
                                    if ($data['programs_list']) :
                                        ?>
                                        <div class="ekz-content__programs">
                                            <?php foreach ($data['programs_list'] as $program) : ?>
                                                <article class="ekz-content__program-card">
                                                    <div class="ekz-content__program-content">
                                                        <?php if ($program['title'] !== '') : ?>
                                                            <h3 class="ekz-content__program-title"><?php echo esc_html($program['title']); ?>
                                                            </h3>
                                                        <?php endif; ?>

                                                        <?php if ($program['text'] !== '') : ?>
                                                            <div class="ekz-content__program-text">
                                                                <?php echo wp_kses_post($program['text']); ?>
                                                            </div>
                                                        <?php endif; ?>

                                                        <?php if (!empty($program['items'])) : ?>
                                                            <ol class="ekz-content__program-list">
                                                                <?php foreach ($program['items'] as $item) : ?>
                                                                    <li><?php echo esc_html($item); ?></li>
                                                                <?php endforeach; ?>
                                                            </ol>
                                                        <?php endif; ?>

                                                        <?php if ($program['price'] !== '') : ?>
                                                            <div class="ekz-content__program-price">
                                                                <?php echo wp_kses_post($program['price']); ?>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>

                                                    <?php if ($program['image_url'] !== '') : ?>
                                                        <div class="ekz-content__program-image-wrap">
                                                            <img src="<?php echo esc_url($program['image_url']); ?>"
                                                                alt="<?php echo esc_attr($program['image_alt']); ?>" loading="lazy">
                                                        </div>
                                                    <?php endif; ?>
                                                </article>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php
                                    endif;
                                    break;
                            }
                            ?>
                        </div>
                    </section>
                <?php endforeach; ?>

            </div>
        </div>
    </div>
</section>
