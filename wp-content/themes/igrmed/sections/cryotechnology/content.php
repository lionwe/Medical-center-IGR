<?php
/**
 * Кріотехнологія — контент сторінки послуги.
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
$advantages_items = $extract_text_rows(get_field('advantages_grid'));

$services_title = trim((string) get_field('services_title'));
$services_items = $extract_text_rows(get_field('services_grid'));

$sections = [];

if ($intro_title !== '' || $intro_content !== '') {
    $sections[] = ['id' => 'cryo-intro', 'title' => $intro_title !== '' ? $intro_title : __('Кріотехнології', 'igrmed')];
}
if ($advantages_title !== '' || !empty($advantages_items)) {
    $sections[] = ['id' => 'cryo-advantages', 'title' => $advantages_title !== '' ? $advantages_title : __('Чому варто обрати нас?', 'igrmed')];
}
if ($services_title !== '' || !empty($services_items)) {
    $sections[] = ['id' => 'cryo-services', 'title' => $services_title !== '' ? $services_title : __('Послуги кріоконсервації в клініці ІГР', 'igrmed')];
}

if (empty($sections)) {
    return;
}
?>

<section class="cryotechnology-content">
    <div class="container">
        <div class="cryotechnology-content__layout catalog-wrap">
            <?php get_template_part('templates/content-sidebar', null, ['sections' => $sections]); ?>

            <div class="cryotechnology-content__content">
                <?php if ($intro_title !== '' || $intro_content !== ''): ?>
                <div id="cryo-intro" class="cryotechnology-content__section cryotechnology-content__section--intro">
                    <div class="cryotechnology-content__title-wrap">
                        <h2 class="cryotechnology-content__title">
                            <?php echo esc_html($intro_title !== '' ? $intro_title : __('Кріотехнології', 'igrmed')); ?>
                        </h2>
                    </div>
                    <div class="cryotechnology-content__body">
                        <?php if ($intro_content !== ''): ?>
                        <?php echo wp_kses_post($intro_content); ?>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>

                <?php if ($advantages_title !== '' || !empty($advantages_items)): ?>
                <div id="cryo-advantages"
                    class="cryotechnology-content__section cryotechnology-content__section--advantages">
                    <div class="cryotechnology-content__title-wrap">
                        <h2 class="cryotechnology-content__title">
                            <?php echo esc_html($advantages_title !== '' ? $advantages_title : __('Чому варто обрати нас?', 'igrmed')); ?>
                        </h2>
                    </div>
                    <div class="cryotechnology-content__body">
                        <?php if (!empty($advantages_items)): ?>
                        <div class="cryotechnology-content__grid">
                            <?php foreach ($advantages_items as $item): ?>
                            <article class="cryotechnology-content__card">
                                <span class="cryotechnology-content__accent" aria-hidden="true"></span>
                                <?php echo wp_kses_post($item); ?>
                            </article>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>

                <?php if ($services_title !== '' || !empty($services_items)): ?>
                <div id="cryo-services"
                    class="cryotechnology-content__section cryotechnology-content__section--services">
                    <div class="cryotechnology-content__title-wrap">
                        <h2 class="cryotechnology-content__title">
                            <?php echo esc_html($services_title !== '' ? $services_title : __('Послуги кріоконсервації в клініці ІГР', 'igrmed')); ?>
                        </h2>
                    </div>
                    <div class="cryotechnology-content__body">
                        <?php if (!empty($services_items)): ?>
                        <ol class="cryotechnology-content__grid">
                            <?php foreach ($services_items as $index => $item): ?>
                            <li>
                                <article class="cryotechnology-content__card">
                                    <div class="cryotechnology-content__num-strip">
                                        <span class="cryotechnology-content__num-badge">
                                            <?php echo esc_html(sprintf('%02d', $index + 1)); ?>
                                        </span>
                                    </div>
                                    <div class="cryotechnology-content__card-body">
                                        <p class="cryotechnology-content__label">
                                            <?php echo esc_html($item); ?>
                                        </p>
                                    </div>
                                </article>
                            </li>
                            <?php endforeach; ?>
                        </ol>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>

                <?php if (trim((string) get_post_field('post_content', get_the_ID())) !== ''): ?>
                <div class="cryotechnology-content__editor entry-content">
                    <?php the_content(); ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>