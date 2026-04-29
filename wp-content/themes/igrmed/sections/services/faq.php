<?php
/**
 * Section: Services FAQ (shared)
 * Location: Service pages
 */

$title = trim((string) get_field('faq_title'));
$bg_image = get_field('faq_bg_image');
$faq_rows = get_field('faq_global_list');
$faq_list = [];

if (is_array($faq_rows)) {
    foreach ($faq_rows as $row) {
        if (!is_array($row)) {
            continue;
        }

        $question = trim((string) ($row['question'] ?? ''));
        $answer = trim((string) ($row['answer'] ?? ''));

        // Empty Fields Rule: FAQ item must have a question to render.
        if ($question === '') {
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

$icon_arrow_svg = '<svg width="19" height="9" viewBox="0 0 19 9" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><path d="M9.09375 8.25L18.187 0H0.000483513L9.09375 8.25Z" fill="black"/></svg>';
?>

<section class="faq-section" id="faq-section">
    <div class="container">
        <div class="faq-section__inner" <?php if ($bg_image !== ''): ?>style="background-image: url('<?php echo esc_url($bg_image); ?>');"<?php endif; ?>>
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
                            'text' => igrmed__('btn_contact_us'),
                            'link' => '#contact',
                            'type' => 'primary',
                            'icon_url' => get_template_directory_uri() . '/assets/img/svg/contact-arrow.svg',
                        ]);
                        ?>
                    </div>
                </div>

                <div class="faq-section__right-col">
                    <?php if (!empty($faq_list)): ?>
                        <div class="faq-list">
                            <?php foreach ($faq_list as $item): ?>
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
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>