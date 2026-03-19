<?php
/**
 * Template: Content sidebar for infertility treatment women page.
 *
 * @var array $args {
 *   @type array $sections Available content sections.
 * }
 */

$sections = is_array($args['sections'] ?? null) ? $args['sections'] : [];

if (empty($sections)) {
    return;
}

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

if (empty($sidebar_items)) {
    return;
}
?>

<aside class="content-sidebar" aria-label="<?php esc_attr_e('Навігація по сторінці', 'igrmed'); ?>">
    <div class="content-sidebar__panel js-content-sidebar">
        <?php foreach ($sidebar_items as $item): ?>
            <a
                class="content-sidebar__link js-content-sidebar-link"
                href="#<?php echo esc_attr($item['id']); ?>"
                data-target="<?php echo esc_attr($item['id']); ?>"
            >
                <span class="content-sidebar__label"><?php echo esc_html($item['title']); ?></span>
            </a>
        <?php endforeach; ?>
    </div>
</aside>
