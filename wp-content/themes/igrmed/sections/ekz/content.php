<?php

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

$has_any_content =
    ($intro_title !== '' || $intro_content !== '') ||
    ($advantages_title !== '' || $advantages_content !== '') ||
    ($indications_title !== '' || $indications_subtitle !== '' || !empty($indications_list)) ||
    ($contra_title !== '' || $contra_intro !== '' || !empty($contra_list)) ||
    ($stages_title !== '' || $stages_intro !== '' || !empty($stages_list)) ||
    ($success_title !== '' || $success_intro !== '' || $success_content !== '') ||
    ($candidates_title !== '' || $candidates_subtitle !== '' || !empty($candidates_list)) ||
    ($programs_title !== '' || !empty($programs_list));

if (!$has_any_content) {
    return;
}

$sections = [];

if ($intro_title !== '' || $intro_content !== '') {
    $sections[] = [
        'id' => 'ekz-intro',
        'title' => $intro_title !== '' ? $intro_title : __('Що таке ЕКЗ', 'igrmed'),
    ];
}

if ($advantages_title !== '' || $advantages_content !== '') {
    $sections[] = [
        'id' => 'ekz-advantages',
        'title' => $advantages_title !== '' ? $advantages_title : __('Переваги клініки', 'igrmed'),
    ];
}

if ($indications_title !== '' || $indications_subtitle !== '' || $indications_list) {
    $sections[] = [
        'id' => 'ekz-indications',
        'title' => $indications_title !== '' ? $indications_title : __('Показання', 'igrmed'),
    ];
}

if ($contra_title !== '' || $contra_intro !== '' || $contra_list) {
    $sections[] = [
        'id' => 'ekz-contraindications',
        'title' => $contra_title !== '' ? $contra_title : __('Протипоказання', 'igrmed'),
    ];
}

if ($stages_title !== '' || $stages_intro !== '' || $stages_list) {
    $sections[] = [
        'id' => 'ekz-stages',
        'title' => $stages_title !== '' ? $stages_title : __('Етапи процедури', 'igrmed'),
    ];
}

if ($success_title !== '' || $success_intro !== '' || $success_content !== '') {
    $sections[] = [
        'id' => 'ekz-success',
        'title' => $success_title !== '' ? $success_title : __('Успішність', 'igrmed'),
    ];
}

if ($candidates_title !== '' || $candidates_subtitle !== '' || $candidates_list) {
    $sections[] = [
        'id' => 'ekz-candidates',
        'title' => $candidates_title !== '' ? $candidates_title : __('Кому підходить', 'igrmed'),
    ];
}

if ($programs_title !== '' || $programs_list) {
    $sections[] = [
        'id' => 'ekz-programs',
        'title' => $programs_title !== '' ? $programs_title : __('Програми ЕКЗ', 'igrmed'),
    ];
}
?>

<section class="ekz-content">
    <div class="container">
        <div class="ekz-content__layout catalog-wrap">
            <?php get_template_part('templates/content-sidebar', null, ['sections' => $sections]); ?>

            <div class="ekz-content__content">

                <?php if ($intro_title !== '' || $intro_content !== ''): ?>
                <section id="ekz-intro" class="ekz-content__section">
                    <div class="ekz-content__section-title-wrap">
                        <h2 class="ekz-content__section-title">
                            <?php echo esc_html($intro_title ?: __('Що таке ЕКЗ', 'igrmed')); ?>
                        </h2>
                    </div>
                    <div class="ekz-content__section-body">
                        <div class="ekz-content__section-content ekz-content__section-content--lead">
                            <?php echo wp_kses_post($intro_content); ?>
                        </div>
                    </div>
                </section>
                <?php endif; ?>

                <?php if ($advantages_title !== '' || $advantages_content !== ''): ?>
                <section id="ekz-advantages" class="ekz-content__section">
                    <div class="ekz-content__section-title-wrap">
                        <h2 class="ekz-content__section-title">
                            <?php echo esc_html($advantages_title ?: __('Переваги клініки', 'igrmed')); ?>
                        </h2>
                    </div>
                    <div class="ekz-content__section-body">
                        <div class="ekz-content__section-content ekz-content__section-content--lead">
                            <?php echo wp_kses_post($advantages_content); ?>
                        </div>
                    </div>
                </section>
                <?php endif; ?>

                <?php if ($indications_title !== '' || $indications_subtitle !== '' || $indications_list): ?>
                <section id="ekz-indications" class="ekz-content__section ekz-content__section--indications">
                    <div class="ekz-content__section-title-wrap">
                        <h2 class="ekz-content__section-title">
                            <?php echo esc_html($indications_title ?: __('Показання', 'igrmed')); ?>
                        </h2>
                    </div>
                    <div class="ekz-content__section-body">
                        <?php if ($indications_subtitle !== ''): ?>
                        <p class="ekz-content__section-subtitle">
                            <?php echo esc_html($indications_subtitle); ?>
                        </p>
                        <?php endif; ?>
                        <?php if ($indications_list): ?>
                        <div class="ekz-content__indications-grid">
                            <?php foreach ($indications_list as $item): ?>
                            <article class="ekz-content__indication-card">
                                <span class="ekz-content__indication-card__accent" aria-hidden="true"></span>
                                <?php echo wp_kses_post($item); ?>
                            </article>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </section>
                <?php endif; ?>

                <?php if ($contra_title !== '' || $contra_intro !== '' || $contra_list): ?>
                <section id="ekz-contraindications"
                    class="ekz-content__section ekz-content__section--contraindications">
                    <div class="ekz-content__section-title-wrap">
                        <h3 class="ekz-content__section-title">
                            <?php echo esc_html($contra_title ?: __('Протипоказання', 'igrmed')); ?>
                        </h3>
                    </div>
                    <div class="ekz-content__section-body">
                        <?php if ($contra_intro !== ''): ?>
                        <p class="ekz-content__section-subtitle">
                            <?php echo esc_html($contra_intro); ?>
                        </p>
                        <?php endif; ?>
                        <?php if ($contra_list): ?>
                        <div class="ekz-content__list">
                            <?php foreach ($contra_list as $item): ?>
                            <article class="ekz-content__list-item">
                                <?php echo wp_kses_post($item); ?>
                            </article>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </section>
                <?php endif; ?>

                <?php if ($stages_title !== '' || $stages_intro !== '' || $stages_list): ?>
                <section id="ekz-stages" class="ekz-content__section ekz-content__section--stages">
                    <div class="ekz-content__section-title-wrap">
                        <h2 class="ekz-content__section-title">
                            <?php echo esc_html($stages_title ?: __('Етапи процедури', 'igrmed')); ?>
                        </h2>
                    </div>
                    <div class="ekz-content__section-body">
                        <?php if ($stages_intro !== ''): ?>
                        <strong class="ekz-content__section-subtitle">
                            <?php echo wp_kses_post($stages_intro); ?>
                        </strong>
                        <?php endif; ?>
                        <?php if ($stages_list): ?>
                        <div class="ekz-content__stages-accordion">
                            <?php foreach ($stages_list as $stage): ?>
                            <details class="ekz-content__stage-item">
                                <summary class="ekz-content__stage-trigger">
                                    <span class="ekz-content__stage-label">
                                        <?php echo esc_html($stage['label']); ?>
                                    </span>
                                </summary>
                                <div class="ekz-content__stage-desc">
                                    <?php echo wp_kses_post($stage['description']); ?>
                                </div>
                            </details>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </section>
                <?php endif; ?>

                <?php if ($success_title !== '' || $success_intro !== '' || $success_content !== ''): ?>
                <section id="ekz-success" class="ekz-content__section ekz-content__section--success">
                    <div class="ekz-content__section-title-wrap">
                        <h2 class="ekz-content__section-title">
                            <?php echo esc_html($success_title ?: __('Успішність', 'igrmed')); ?>
                        </h2>
                    </div>
                    <div class="ekz-content__section-body">
                        <?php if ($success_intro !== ''): ?>
                        <p class="ekz-content__section-subtitle">
                            <?php echo esc_html($success_intro); ?>
                        </p>
                        <?php endif; ?>
                        <?php if ($success_content !== ''): ?>
                        <div class="ekz-content__section-content ekz-content__section-content--lead">
                            <?php echo wp_kses_post($success_content); ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </section>
                <?php endif; ?>

                <?php if ($candidates_title !== '' || $candidates_subtitle !== '' || $candidates_list): ?>
                <section id="ekz-candidates" class="ekz-content__section ekz-content__section--candidates">
                    <div class="ekz-content__section-title-wrap">
                        <h2 class="ekz-content__section-title">
                            <?php echo esc_html($candidates_title ?: __('Кому підходить', 'igrmed')); ?>
                        </h2>
                    </div>
                    <div class="ekz-content__section-body">
                        <?php if ($candidates_subtitle !== ''): ?>
                        <p class="ekz-content__section-subtitle">
                            <?php echo esc_html($candidates_subtitle); ?>
                        </p>
                        <?php endif; ?>
                        <?php if ($candidates_list): ?>
                        <div class="ekz-content__list">
                            <?php foreach ($candidates_list as $item): ?>
                            <article class="ekz-content__list-item">
                                <?php echo esc_html($item); ?>
                            </article>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </section>
                <?php endif; ?>

                <?php if ($programs_title !== '' || $programs_list): ?>
                <section id="ekz-programs" class="ekz-content__section ekz-content__section--programs">
                    <div class="ekz-content__section-title-wrap">
                        <h2 class="ekz-content__section-title">
                            <?php echo esc_html($programs_title ?: __('Програми ЕКЗ', 'igrmed')); ?>
                        </h2>
                    </div>
                    <div class="ekz-content__section-body">
                        <?php if ($programs_list): ?>
                        <div class="ekz-content__programs">
                            <?php foreach ($programs_list as $program): ?>
                            <article class="ekz-content__program-card">
                                <div class="ekz-content__program-content">
                                    <?php if ($program['title'] !== ''): ?>
                                    <h3 class="ekz-content__program-title"><?php echo esc_html($program['title']); ?>
                                    </h3>
                                    <?php endif; ?>

                                    <?php if ($program['text'] !== ''): ?>
                                    <div class="ekz-content__program-text">
                                        <?php echo wp_kses_post(wpautop($program['text'])); ?>
                                    </div>
                                    <?php endif; ?>

                                    <?php if (!empty($program['items'])): ?>
                                    <ol class="ekz-content__program-list">
                                        <?php foreach ($program['items'] as $item): ?>
                                        <li class="ekz-content__program-list-item"><?php echo esc_html($item); ?></li>
                                        <?php endforeach; ?>
                                    </ol>
                                    <?php endif; ?>

                                    <?php if ($program['price'] !== ''): ?>
                                    <div class="ekz-content__program-price">
                                        <?php echo wp_kses_post($program['price']); ?>
                                    </div>
                                    <?php endif; ?>
                                </div>

                                <?php if ($program['image_url'] !== ''): ?>
                                <div class="ekz-content__program-image-wrap">
                                    <img src="<?php echo esc_url($program['image_url']); ?>"
                                        alt="<?php echo esc_attr($program['image_alt']); ?>" loading="lazy">
                                </div>
                                <?php endif; ?>
                            </article>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </section>
                <?php endif; ?>

            </div>
        </div>
    </div>
</section>