<?php

$extract_text_rows = static function ($rows, array $keys = ['text', 'item', 'title', 'name']): array {
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

$advantages_title = trim((string) get_field('advantages_title'));
$advantages_content = get_field('advantages_content');
$advantages_content = is_string($advantages_content) ? trim($advantages_content) : '';

$indications_title = trim((string) get_field('indications_title'));
$indications_subtitle = trim((string) get_field('indications_subtitle'));
$indications_list = $extract_text_rows(get_field('indications_grid'));

$contra_title = trim((string) get_field('contraindications_title'));
$contra_intro = get_field('contraindications_intro');
$contra_intro = is_string($contra_intro) ? trim($contra_intro) : '';
$contra_list = $extract_text_rows(get_field('contraindications_list'), ['text']);

$stages_title = trim((string) get_field('stages_title'));
$stages_intro = get_field('stages_intro');
$stages_intro = is_string($stages_intro) ? trim($stages_intro) : '';
$stages_list = [];
foreach ((array) get_field('stages_accordion') as $row) {
    if (!is_array($row)) {
        continue;
    }
    $label = trim((string) ($row['label'] ?? ''));
    $description = trim((string) ($row['description'] ?? ''));
    if ($label !== '' || $description !== '') {
        $stages_list[] = compact('label', 'description');
    }
}

$success_title = trim((string) get_field('success_title'));
$success_intro = get_field('success_intro');
$success_intro = is_string($success_intro) ? trim($success_intro) : '';
$success_content = get_field('success_content');
$success_content = is_string($success_content) ? trim($success_content) : '';

$candidates_title = trim((string) get_field('candidates_title'));
$candidates_subtitle = get_field('candidates_subtitle');
$candidates_subtitle = is_string($candidates_subtitle) ? trim($candidates_subtitle) : '';
$candidates_content = get_field('candidates_list');
$candidates_content = is_string($candidates_content) ? trim($candidates_content) : '';

$programs_title = trim((string) get_field('programs_title'));
$programs_rows = get_field('programa');
$programs_list = [];
if (is_array($programs_rows)) {
    foreach ($programs_rows as $row) {
        if (!is_array($row)) {
            continue;
        }
        $program_title = trim((string) ($row['title'] ?? ''));
        $program_text = trim((string) ($row['text'] ?? ''));
        $program_price = trim((string) ($row['price'] ?? ''));
        $program_image_url = trim((string) ($row['image_url'] ?? ''));
        $program_image_alt = trim((string) ($row['image_alt'] ?? ''));
        
        if (empty($program_image_url)) {
            $program_images = $row['image'] ?? $row['images'] ?? $row['img'] ?? $row['photo'] ?? $row['photos'] ?? [];
            
            if (is_string($program_images) && !empty($program_images)) {
                $program_image_url = trim((string) $program_images);
            } elseif (is_array($program_images) && !empty($program_images)) {
                $first_image = reset($program_images);
                if (is_array($first_image)) {
                    $program_image_url = trim((string) ($first_image['url'] ?? ''));
                    $program_image_alt = trim((string) ($first_image['alt'] ?? ''));
                } elseif (is_numeric($first_image)) {
                    $program_image_url = wp_get_attachment_url((int) $first_image);
                    $program_image_alt = get_post_meta((int) $first_image, '_wp_attachment_image_alt', true) ?: '';
                } else {
                    $program_image_url = trim((string) $first_image);
                }
            }
        }
        
        $items_source = $row['list'] ?? $row['items'] ?? $row['ol'] ?? [];
        $program_items = [];
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

$has_intro = $intro_content !== '';
$has_advantages = $advantages_content !== '';
$has_indications = $indications_subtitle !== '' || !empty($indications_list);
$has_contra = $contra_intro !== '' || !empty($contra_list);
$has_stages = $stages_intro !== '' || !empty($stages_list);
$has_success = $success_intro !== '' || $success_content !== '';
$has_candidates = $candidates_subtitle !== '' || $candidates_content !== '';
$has_programs = !empty($programs_list);

$sections = [];

if ($has_intro) {
    $sections[] = ['id' => 'ekz-intro', 'title' => $intro_title];
}
if ($has_advantages) {
    $sections[] = ['id' => 'ekz-advantages', 'title' => $advantages_title];
}
if ($has_indications) {
    $sections[] = ['id' => 'ekz-indications', 'title' => $indications_title];
}
if ($has_contra) {
    $sections[] = ['id' => 'ekz-contraindications', 'title' => $contra_title];
}
if ($has_stages) {
    $sections[] = ['id' => 'ekz-stages', 'title' => $stages_title];
}
if ($has_success) {
    $sections[] = ['id' => 'ekz-success', 'title' => $success_title];
}
if ($has_candidates) {
    $sections[] = ['id' => 'ekz-candidates', 'title' => $candidates_title];
}
if ($has_programs) {
    $sections[] = ['id' => 'ekz-programs', 'title' => $programs_title];
}

if (empty($sections)) {
    return;
}
?>

<section class="ekz-content">
    <div class="container">
        <div class="ekz-content__layout catalog-wrap">
            <?php get_template_part('templates/content-sidebar', null, ['sections' => $sections]); ?>

            <div class="ekz-content__content">
                <?php if ($intro_title !== ''): ?>
                    <div id="ekz-intro" class="ekz-content__section ekz-content__section--intro">
                        <div class="ekz-content__section-title-wrap">
                            <h2 class="ekz-content__section-title">
                                <?php echo esc_html($intro_title); ?>
                            </h2>
                        </div>
                        <div class="ekz-content__section-body">
                            <?php if ($intro_content !== ''): ?>
                                <div class="ekz-content__lead">
                                    <?php echo wp_kses_post($intro_content); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($advantages_title !== ''): ?>
                    <div id="ekz-advantages" class="ekz-content__section ekz-content__section--advantages">
                        <div class="ekz-content__section-title-wrap">
                            <h2 class="ekz-content__section-title">
                                <?php echo esc_html($advantages_title); ?>
                            </h2>
                        </div>
                        <div class="ekz-content__section-body">
                            <?php if ($advantages_content !== ''): ?>
                                <div class="ekz-content__lead">
                                    <?php echo wp_kses_post($advantages_content); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($indications_title !== ''): ?>
                    <div id="ekz-indications" class="ekz-content__section ekz-content__section--indications">
                        <div class="ekz-content__section-title-wrap">
                            <h2 class="ekz-content__section-title">
                                <?php echo esc_html($indications_title); ?>
                            </h2>
                        </div>
                        <div class="ekz-content__section-body">
                            <?php if ($indications_subtitle !== ''): ?>
                                <p class="ekz-content__section-subtitle">
                                    <?php echo esc_html($indications_subtitle); ?>
                                </p>
                            <?php endif; ?>
                            <?php if (!empty($indications_list)): ?>
                                <div class="ekz-content__indications-grid">
                                    <?php foreach ($indications_list as $item): ?>
                                        <article class="ekz-content__indication-card">
                                            <span class="ekz-content__indication-accent" aria-hidden="true"></span>
                                            <?php echo wp_kses_post($item); ?>
                                        </article>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($contra_title !== ''): ?>
                    <div id="ekz-contraindications" class="ekz-content__section ekz-content__section--contraindications">
                        <div class="ekz-content__section-title-wrap">
                            <h2 class="ekz-content__section-title">
                                <?php echo esc_html($contra_title); ?>
                            </h2>
                        </div>
                        <div class="ekz-content__section-body">
                            <?php if ($contra_intro !== ''): ?>
                                <p class="ekz-content__section-subtitle">
                                    <?php echo esc_html($contra_intro); ?>
                                </p>
                            <?php endif; ?>
                            <?php if (!empty($contra_list)): ?>
                                <div class="ekz-content__list">
                                    <?php foreach ($contra_list as $item): ?>
                                        <article class="ekz-content__list-item">
                                            <?php echo wp_kses_post($item); ?>
                                        </article>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($stages_title !== ''): ?>
                    <div id="ekz-stages" class="ekz-content__section ekz-content__section--stages">
                        <div class="ekz-content__section-title-wrap">
                            <h2 class="ekz-content__section-title">
                                <?php echo esc_html($stages_title); ?>
                            </h2>
                        </div>
                        <div class="ekz-content__section-body">
                            <?php if ($stages_intro !== ''): ?>
                                <strong class="ekz-content__section-subtitle">
                                    <?php echo wp_kses_post($stages_intro); ?>
                                </strong>
                            <?php endif; ?>
                            <?php if (!empty($stages_list)): ?>
                                <div class="ekz-content__stages-accordion">
                                    <?php foreach ($stages_list as $stage): ?>
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
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($success_title !== ''): ?>
                    <div id="ekz-success" class="ekz-content__section ekz-content__section--success">
                        <div class="ekz-content__section-title-wrap">
                            <h2 class="ekz-content__section-title">
                                <?php echo esc_html($success_title); ?>
                            </h2>
                        </div>
                        <div class="ekz-content__section-body">
                            <?php if ($success_intro !== ''): ?>
                                <p class="ekz-content__section-subtitle">
                                    <?php echo wp_kses_post($success_intro); ?>
                                </p>
                            <?php endif; ?>
                            <?php if ($success_content !== ''): ?>
                                <div class="ekz-content__lead-text">
                                    <?php echo wp_kses_post($success_content); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($candidates_title !== ''): ?>
                    <div id="ekz-candidates" class="ekz-content__section ekz-content__section--candidates">
                        <div class="ekz-content__section-title-wrap">
                            <h2 class="ekz-content__section-title">
                                <?php echo esc_html($candidates_title); ?>
                            </h2>
                        </div>
                        <div class="ekz-content__section-body">
                            <?php if ($candidates_subtitle !== ''): ?>
                                <p class="ekz-content__section-subtitle">
                                    <?php echo esc_html($candidates_subtitle); ?>
                                </p>
                            <?php endif; ?>
                            <?php if ($candidates_content !== ''): ?>
                                <div class="ekz-content__lead-text">
                                    <?php echo wp_kses_post($candidates_content); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($programs_title !== ''): ?>
                    <div id="ekz-programs" class="ekz-content__section ekz-content__section--programs">
                        <div class="ekz-content__section-title-wrap">
                            <h2 class="ekz-content__section-title">
                                <?php echo esc_html($programs_title); ?>
                            </h2>
                        </div>
                        <div class="ekz-content__section-body">
                            <?php if (!empty($programs_list)): ?>
                                <div class="ekz-content__programs-slider-wrapper">
                                    <div class="swiper js-ekz-programs-slider ekz-content__programs">
                                        <div class="swiper-wrapper">
                                            <?php foreach ($programs_list as $program): ?>
                                                <div class="swiper-slide">
                                                    <article class="ekz-content__program-card">
                                            <div class="ekz-content__program-content">
                                                <?php if ($program['title'] !== ''): ?>
                                                    <h3 class="ekz-content__program-title">
                                                        <?php echo esc_html($program['title']); ?>
                                                    </h3>
                                                <?php endif; ?>
                                                <div class="ekz-content__program-text">
                                                    <?php if ($program['text'] !== ''): ?>
                                                        <p><?php echo wp_kses_post($program['text']); ?></p>
                                                    <?php endif; ?>
                                                    <?php if (!empty($program['items'])): ?>
                                                        <ul>
                                                            <?php foreach ($program['items'] as $item): ?>
                                                                <li><?php echo esc_html($item); ?></li>
                                                            <?php endforeach; ?>
                                                        </ul>
                                                    <?php endif; ?>
                                                </div>
                                                <?php if ($program['price'] !== ''): ?>
                                                    <div class="ekz-content__program-price">
                                                        <div><?php echo wp_kses_post($program['price']); ?></div>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <?php if ($program['image_url'] !== ''): ?>
                                                <div class="ekz-content__program-image-wrap">
                                                    <?php
                                                    get_picture([
                                                        'src' => $program['image_url'],
                                                        'alt' => $program['image_alt'],
                                                        'class' => 'ekz-content__program-image',
                                                        'lazy' => true,
                                                    ]);
                                                    ?>
                                                </div>
                                            <?php endif; ?>
                                                </article>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>

                                <div class="ekz-content__programs-nav">
                                    <?php
                                    get_template_part('templates/button', null, [
                                        'type' => 'carousel',
                                        'icon_url' => get_template_directory_uri() . '/assets/img/svg/arrow-prev.svg',
                                        'class' => 'ekz-content__programs-prev js-ekz-programs-prev',
                                        'attributes' => ['aria-label' => 'Попередній'],
                                    ]);
                                    ?>
                                    <?php
                                    get_template_part('templates/button', null, [
                                        'type' => 'carousel',
                                        'icon_url' => get_template_directory_uri() . '/assets/img/svg/arrow-next.svg',
                                        'class' => 'ekz-content__programs-next js-ekz-programs-next',
                                        'attributes' => ['aria-label' => 'Наступний'],
                                    ]);
                                    ?>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
