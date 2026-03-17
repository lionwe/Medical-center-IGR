<?php
/**
 * Section: What Examinations
 * Location: Infertility Women page
 */

$section_title = trim((string) get_field('title_what-examinations'));

$mom_group = get_field('for_mom_what-examinations');
$dad_group = get_field('for_dad_what-examinations');

$extract_group_data = static function ($group): array {
    $title = '';
    $bg = '';
    $items = [];

    if (is_array($group)) {
        $title = trim((string) ($group['title'] ?? ''));
        $bg = trim((string) ($group['bg'] ?? ''));
        $rows = $group['items'] ?? [];

        if (is_array($rows)) {
            foreach ($rows as $row) {
                $item = '';
                if (is_array($row)) {
                    $item = trim((string) ($row['item'] ?? ''));
                }

                if ($item !== '') {
                    $items[] = $item;
                }
            }
        }
    }

    return [
        'title' => $title,
        'bg' => $bg,
        'items' => $items,
    ];
};

$mom = $extract_group_data($mom_group);
$dad = $extract_group_data($dad_group);

if (
    $section_title === '' &&
    $mom['title'] === '' && empty($mom['items']) &&
    $dad['title'] === '' && empty($dad['items'])
) {
    return;
}
?>

<section class="what-examinations">
    <div class="container">
        <div class="what-examinations__wrapper">
            <?php if ($section_title !== ''): ?>
                <h3 class="what-examinations__title"><?php echo esc_html($section_title); ?></h3>
            <?php endif; ?>

            <div class="what-examinations__cards">
                <article class="what-examinations__card what-examinations__card--mom" <?php echo $mom['bg'] !== '' ? 'style="background-image: url(' . esc_url($mom['bg']) . ');"' : ''; ?>>
                    <?php if ($mom['title'] !== ''): ?>
                        <h4 class="what-examinations__card-title"><?php echo esc_html($mom['title']); ?></h4>
                    <?php endif; ?>

                    <?php if (!empty($mom['items'])): ?>
                        <ul class="what-examinations__list">
                            <?php foreach ($mom['items'] as $item): ?>
                                <li class="what-examinations__item"><?php echo esc_html($item); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </article>

                <article class="what-examinations__card what-examinations__card--dad" <?php echo $dad['bg'] !== '' ? 'style="background-image: url(' . esc_url($dad['bg']) . ');"' : ''; ?>>
                    <?php if ($dad['title'] !== ''): ?>
                        <h3 class="what-examinations__card-title"><?php echo esc_html($dad['title']); ?></h3>
                    <?php endif; ?>

                    <?php if (!empty($dad['items'])): ?>
                        <ul class="what-examinations__list">
                            <?php foreach ($dad['items'] as $item): ?>
                                <li class="what-examinations__item"><?php echo esc_html($item); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </article>
            </div>
        </div>
    </div>
</section>