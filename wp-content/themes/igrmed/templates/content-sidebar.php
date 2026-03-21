<?php
/**
 * Template: Content sidebar for infertility treatment women page.
 *
 * @var array $args {
 *   @type array $sections Available content sections.
 * }
 */

$sections = is_array($args['sections'] ?? null) ? $args['sections'] : [];

$sidebar_items = [];
foreach ($sections as $section) {
    if (!is_array($section)) {
        continue;
    }

    $id = trim((string) ($section['id'] ?? ''));
    $title = trim((string) ($section['title'] ?? ''));

    if ($id === '' || $title === '') {
        continue;
    }

    $sidebar_items[] = [
        'id' => $id,
        'title' => $title,
    ];
}

$is_auto_mode = empty($sidebar_items);
?>

<aside class="content-sidebar" aria-label="<?php esc_attr_e('Навігація по сторінці', 'igrmed'); ?>">
    <div class="content-sidebar__panel js-content-sidebar" <?php echo $is_auto_mode ? 'data-auto-build="1"' : ''; ?>>
        <?php if (!$is_auto_mode): ?>
            <?php foreach ($sidebar_items as $index => $item): ?>
                <a
                    class="content-sidebar__link js-content-sidebar-link"
                    href="#<?php echo esc_attr($item['id']); ?>"
                    data-target="<?php echo esc_attr($item['id']); ?>"
                >
                    <span class="content-sidebar__marker" aria-hidden="true">
                        <span class="content-sidebar__marker-number">
                            <?php echo esc_html(sprintf('(%02d)', $index + 1)); ?>
                        </span>
                        <span class="content-sidebar__marker-arrow">
                            (<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12.172 6.77766H0V8.77766H12.172L6.808 14.1417L8.222 15.5557L16 7.77766L8.222 -0.000335693L6.808 1.41366L12.172 6.77766Z" fill="white"/>
                            </svg>)
                        </span>
                    </span>
                    <span class="content-sidebar__label"><?php echo esc_html($item['title']); ?></span>
                </a>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</aside>
