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
                        <div class="cryotechnology-content__blob" aria-hidden="true">
                            <svg width="676" height="1030" viewBox="0 0 676 1030" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g filter="url(#filter0_f_806_1358_cryo)">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M615.023 246.628C680.905 251.674 754.287 227.637 809.98 254.884C865.633 282.111 873.097 340.564 900.609 386.345C925.535 427.824 949.465 467.611 964.363 511.789C983.329 568.03 1047.19 635.272 999.926 680.857C949.596 729.394 837.571 682.386 762.647 706.319C701.527 725.843 678.378 788.968 615.023 803.806C546.258 819.911 470.46 810.603 403.584 790.412C331.31 768.591 241.541 741.914 221.028 684.851C199.385 624.643 300.768 574.175 303.427 511.789C305.948 452.636 217.065 402.583 235.509 345.087C253.743 288.244 323.217 242.067 396.008 223.182C466.997 204.766 540.398 240.914 615.023 246.628Z" fill="url(#paint0_linear_806_1358_cryo)" fill-opacity="0.4"/>
                                </g>
                                <defs>
                                    <filter id="filter0_f_806_1358_cryo" x="0" y="0" width="1235" height="1030" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                                        <feFlood flood-opacity="0" result="BackgroundImageFix"/>
                                        <feBlend mode="normal" in="SourceGraphic" in2="BackgroundImageFix" result="shape"/>
                                        <feGaussianBlur stdDeviation="109" result="effect1_foregroundBlur_806_1358_cryo"/>
                                    </filter>
                                    <linearGradient id="paint0_linear_806_1358_cryo" x1="-139.558" y1="518.047" x2="402.317" y2="1246.93" gradientUnits="userSpaceOnUse">
                                        <stop stop-color="white"/>
                                        <stop offset="1" stop-color="#4776BA"/>
                                    </linearGradient>
                                </defs>
                            </svg>
                        </div>
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