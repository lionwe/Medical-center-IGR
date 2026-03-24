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

$sections = [];
if ($intro_title !== '' || $intro_content !== '') {
    $sections[] = ['id' => 'gy-intro', 'title' => $intro_title !== '' ? $intro_title : __('Гінекологія', 'igrmed')];
}
if ($advantages_title !== '' || $advantages_content !== '') {
    $sections[] = ['id' => 'gy-advantages', 'title' => $advantages_title !== '' ? $advantages_title : __('Переваги', 'igrmed')];
}
if ($when_title !== '' || !empty($when_list)) {
    $sections[] = ['id' => 'gy-when', 'title' => $when_title !== '' ? $when_title : __('Коли варто звернутися', 'igrmed')];
}
if ($procedures_title !== '' || !empty($procedures_list)) {
    $sections[] = ['id' => 'gy-procedures', 'title' => $procedures_title !== '' ? $procedures_title : __('Процедури та захворювання', 'igrmed')];
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
                <?php if ($intro_title !== '' || $intro_content !== ''): ?>
                    <div id="gy-intro" class="gynecology-content__section gynecology-content__section--intro">
                        <div class="gynecology-content__title-wrap">
                            <h2 class="gynecology-content__title">
                                <?php echo esc_html($intro_title !== '' ? $intro_title : __('Гінекологія', 'igrmed')); ?>
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

                <?php if ($advantages_title !== '' || $advantages_content !== ''): ?>
                    <div id="gy-advantages" class="gynecology-content__section gynecology-content__section--advantages">
                        <div class="gynecology-content__title-wrap">
                            <h2 class="gynecology-content__title">
                                <?php echo esc_html($advantages_title !== '' ? $advantages_title : __('Переваги', 'igrmed')); ?>
                            </h2>
                        </div>
                        <div class="gynecology-content__body">
                            <?php if ($advantages_content !== ''): ?>
                                <?php echo wp_kses_post($advantages_content); ?>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($when_title !== '' || !empty($when_list)): ?>
                    <div id="gy-when" class="gynecology-content__section">
                        <div class="gynecology-content__title-wrap">
                            <h2 class="gynecology-content__title">
                                <?php echo esc_html($when_title !== '' ? $when_title : __('Коли варто звернутися', 'igrmed')); ?>
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

                <?php if ($procedures_title !== '' || !empty($procedures_list)): ?>
                    <div id="gy-procedures" class="gynecology-content__section gynecology-content__section--procedures">
                        <div class="gynecology-content__title-wrap">
                            <h2 class="gynecology-content__title">
                                <?php echo esc_html($procedures_title !== '' ? $procedures_title : __('Процедури та захворювання', 'igrmed')); ?>
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
                                        <li class="gynecology-content__procedures-item gynecology-content__list-more-item">
                                            <button type="button" class="gynecology-content__list-more" aria-expanded="false">
                                                <span
                                                    class="gynecology-content__list-more-text"><?php echo esc_html__('Всі процедури', 'igrmed'); ?></span>
                                                <span class="gynecology-content__list-more-arrow" aria-hidden="true">
                                                    <svg width="21" height="8" viewBox="0 0 21 8" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M20.3536 4.03544C20.5488 3.84018 20.5488 3.5236 20.3536 3.32833L17.1716 0.146352C16.9763 -0.0489098 16.6597 -0.0489099 16.4645 0.146352C16.2692 0.341614 16.2692 0.658197 16.4645 0.853459L19.2929 3.68189L16.4645 6.51031C16.2692 6.70558 16.2692 7.02216 16.4645 7.21742C16.6597 7.41268 16.9763 7.41268 17.1716 7.21742L20.3536 4.03544ZM0 3.68188L-4.37114e-08 4.18188L20 4.18189L20 3.68189L20 3.18189L4.37114e-08 3.18188L0 3.68188Z"
                                                            fill="black" />
                                                    </svg>
                                                </span>
                                            </button>
                                        </li>
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

<script>
    (function () {
        const root = document.querySelector('#gy-procedures');
        if (!root) return;

        const button = root.querySelector('.gynecology-content__list-more');
        if (!button) return;

        button.addEventListener('click', () => {
            const hiddenItems = root.querySelectorAll('.is-hidden-service');
            hiddenItems.forEach((item) => item.classList.remove('is-hidden-service'));
            button.setAttribute('aria-expanded', 'true');
            button.closest('.gynecology-content__list-more-item')?.remove();
        });
    })();
</script>