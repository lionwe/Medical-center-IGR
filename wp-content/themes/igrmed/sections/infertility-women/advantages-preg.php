<?php
/**
 * Section: Advantages Preg
 * Location: Infertility Women page
 */

$title = trim((string) get_field('advantages-preg_title'));
$text = get_field('advantages-preg_text');
$text = is_string($text) ? trim($text) : '';
$items_rows = get_field('advantages-preg_items');
$items = [];

if (is_array($items_rows)) {
    foreach ($items_rows as $row) {
        if (!is_array($row)) {
            continue;
        }

        $item = trim((string) ($row['item'] ?? ''));
        if ($item !== '') {
            $items[] = $item;
        }
    }
}
?>

<section class="advantages-preg">
    <div class="container">
        <div class="advantages-preg__wrapper">
            <?php if ($title !== ''): ?>
            <h3 class="advantages-preg__title"><?php echo esc_html($title); ?></h3>
            <?php endif; ?>

            <div class="advantages-preg__row">
                <div class="advantages-preg__left">
                    <?php if ($text !== ''): ?>
                    <div class="advantages-preg__text"><?php echo wp_kses_post($text); ?></div>
                    <?php endif; ?>

                    <div class="advantages-preg__action">
                        <?php
                        get_template_part('templates/button', null, [
                            'text' => 'Зв’язатись з нами',
                            'link' => '#сta',
                            'type' => 'tertiary',
                            'icon_url' => get_template_directory_uri() . '/assets/img/svg/contact-arrow-up-right.svg',
                        ]);
                        ?>
                    </div>
                </div>

                <div class="advantages-preg__right js-advantages-preg-swiper">
                    <div class="swiper-wrapper">
                        <?php if (!empty($items)): ?>
                        <?php foreach ($items as $item): ?>
                        <div class="swiper-slide">
                            <article class="advantages-preg__card">
                                <span class="advantages-preg__card-line" aria-hidden="true"></span>
                                <p class="advantages-preg__card-text"><?php echo esc_html($item); ?></p>
                            </article>
                        </div>
                        <?php endforeach; ?>
                        <?php else: ?>
                        <?php for ($i = 0; $i < 5; $i++): ?>
                        <div class="swiper-slide">
                            <article class="advantages-preg__card">
                                <span class="advantages-preg__card-line" aria-hidden="true"></span>
                            </article>
                        </div>
                        <?php endfor; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>