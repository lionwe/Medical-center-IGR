<?php
/**
 * Section: Services FAQ (shared)
 * Location: Service pages
 */

$extract_image_url = static function ($image): string {
    if (is_array($image)) {
        return trim((string) ($image['url'] ?? ''));
    }

    if (is_numeric($image)) {
        return (string) wp_get_attachment_url((int) $image);
    }

    return trim((string) $image);
};

$title = trim((string) get_field('faq_title'));
$bg_image = $extract_image_url(get_field('faq_bg_image'));
$faq_rows = get_field('faq_global_list');
$faq_list = [];

if (is_array($faq_rows)) {
    foreach ($faq_rows as $row) {
        if (!is_array($row)) {
            continue;
        }

        $question = trim((string) ($row['question'] ?? ''));
        $answer = trim((string) ($row['answer'] ?? ''));

        if ($question === '' && $answer === '') {
            continue;
        }

        $faq_list[] = [
            'question' => $question,
            'answer' => $answer,
        ];
    }
}

if (empty($faq_list)) {
    return;
}

if ($bg_image === '') {
    $bg_image = get_template_directory_uri() . '/assets/img/svg/footer-bg.svg';
}

$icon_arrow_svg = '<svg width="19" height="9" viewBox="0 0 19 9" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><path d="M9.09375 8.25L18.187 0H0.000483513L9.09375 8.25Z" fill="black"/></svg>';
?>

<section class="faq-section" id="faq-section">
    <div class="container">
        <div class="faq-section__inner" style="background-image: url('<?php echo esc_url($bg_image); ?>');">

            <div class="faq-section__layout">
                <div class="faq-section__left-col">
                    <div class="faq-section__header-group">
                        <?php if ($title !== ''): ?>
                            <h2 class="faq-section__title">
                                <?php echo wp_kses_post($title); ?>
                            </h2>
                        <?php endif; ?>
                    </div>

                    <div class="faq-section__action">
                        <?php
                        get_template_part('templates/button', null, [
                            'text' => __("Зв'язатись з нами", 'igrmed'),
                            'link' => '#contact',
                            'type' => 'glass-primary',
                            'primary_split' => true,
                            'icon_url' => get_template_directory_uri() . '/assets/img/svg/contact-arrow.svg',
                        ]);
                        ?>
                    </div>
                </div>

                <div class="faq-section__right-col">
                    <?php if (!empty($faq_list)): ?>
                        <div class="faq-list">
                            <?php foreach ($faq_list as $item): ?>
                                <?php if ($item['question'] !== ''): ?>
                                    <div class="faq-item js-faq-item">
                                        <button class="faq-item__header js-faq-trigger" type="button" aria-expanded="false">
                                            <h4 class="faq-item__title">
                                                <?php echo esc_html($item['question']); ?>
                                            </h4>
                                            <div class="faq-item__icon">
                                                <?php echo $icon_arrow_svg; ?>
                                            </div>
                                        </button>
                                        <div class="accordeon">
                                            <div class="content">
                                                <div class="faq-item__body">
                                                    <?php echo wp_kses_post(wpautop($item['answer'])); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
