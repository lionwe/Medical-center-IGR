<?php
/**
 * In-page section navigation (sidebar).
 *
 * @var array $args {
 *     @type array[] $sections Pre-filtered list of [ 'id' => string, 'title' => string ].
 * }
 */

$sections = $args['sections'] ?? [];

if (empty($sections)) {
    return;
}
?>

<aside class="content-sidebar" aria-label="<?php esc_attr_e('Навігація по сторінці', 'igrmed'); ?>">
    <div class="content-sidebar__panel js-content-sidebar">
        <nav>
            <ul>
                <?php foreach ($sections as $index => $section) : ?>
                    <li>
                        <a
                            class="content-sidebar__link js-content-sidebar-link"
                            href="#<?php echo esc_attr($section['id']); ?>"
                            data-target="<?php echo esc_attr($section['id']); ?>"
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
                            <span class="content-sidebar__label"><?php echo esc_html($section['title']); ?></span>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </nav>
    </div>
</aside>
