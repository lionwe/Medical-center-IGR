<?php

/**
 * Section: Advantages
 * Fields: advantages_content, advantages_list, advantages_numbers
 */

$content = get_field('advantages_content', get_the_ID());
$list    = get_field('advantages_list', get_the_ID());
$numbers = get_field('advantages_numbers', get_the_ID());

if (! $content && empty($list) && empty($numbers)) {
    return;
}
?>

<section class="advantages" id="advantages">
    <div class="advantages__bg" aria-hidden="true">
        <svg width="1429" height="1084" viewBox="0 0 1429 1084" fill="none" xmlns="http://www.w3.org/2000/svg">
            <g filter="url(#filter0_f_860_403)">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M711.422 249.231C793.3 254.735 884.499 228.514 953.715 258.237C1022.88 287.94 1032.16 351.706 1066.35 401.65C1097.33 446.899 1127.07 490.303 1145.58 538.497C1169.15 599.85 1248.53 673.206 1189.78 722.935C1127.23 775.885 988.005 724.603 894.889 750.712C818.928 772.01 790.16 840.874 711.422 857.061C625.961 874.63 531.758 864.476 448.645 842.449C358.822 818.644 247.256 789.542 221.763 727.292C194.865 661.611 320.865 606.554 324.169 538.497C327.302 473.967 216.838 419.364 239.76 356.641C262.422 294.63 348.764 244.255 439.23 223.653C527.454 203.562 618.678 242.997 711.422 249.231Z" fill="url(#paint0_linear_860_403)" fill-opacity="0.4" />
            </g>
            <defs>
                <filter id="filter0_f_860_403" x="0" y="0" width="1429" height="1084" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                    <feFlood flood-opacity="0" result="BackgroundImageFix" />
                    <feBlend mode="normal" in="SourceGraphic" in2="BackgroundImageFix" result="shape" />
                    <feGaussianBlur stdDeviation="109" result="effect1_foregroundBlur_860_403" />
                </filter>
                <linearGradient id="paint0_linear_860_403" x1="-226.374" y1="545.324" x2="338.674" y2="1411.21" gradientUnits="userSpaceOnUse">
                    <stop stop-color="#79B6E3" />
                    <stop offset="1" stop-color="#E6C0A3" />
                </linearGradient>
            </defs>
        </svg>
    </div>

    <div class="container">

        <?php if ($content) : ?>
            <div class="advantages__header">
                <?php echo wp_kses_post($content); ?>
            </div>
        <?php endif; ?>

        <?php if (! empty($list)) : ?>
            <div class="advantages__list">
                <?php foreach ($list as $item) :
                    $icon = $item['icon'] ?? null;
                    $text = $item['text'] ?? '';

                    if (! $icon && ! $text) {
                        continue;
                    }
                ?>
                    <div class="advantages__item">
                        <?php if ($icon) : ?>
                            <div class="advantages__item-icon">
                                <?php echo wp_get_attachment_image(
                                    absint($icon['ID']),
                                    'thumbnail',
                                    false,
                                    [
                                        'alt'   => esc_attr($icon['alt'] ?? ''),
                                        'class' => 'advantages__item-img',
                                    ]
                                ); ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($text) : ?>
                            <div class="advantages__item-text">
                                <?php echo wp_kses_post($text); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if (! empty($numbers)) : ?>
            <div class="advantages__numbers js-advantages-numbers">
                <?php
                // Обмежуємо максимум 3 картками
                $numbers = array_slice($numbers, 0, 3);
                foreach ($numbers as $stat) :
                    $number = $stat['number'] ?? '';
                    $label  = $stat['text'] ?? '';

                    if (! $number) {
                        continue;
                    }
                ?>
                    <div class="advantages__stat">
                        <h3 class="advantages__stat-value js-count-up" data-value="<?php echo esc_attr($number); ?>">
                            <?php echo esc_html($number); ?>
                        </h3>

                        <?php if ($label) : ?>
                            <p class="advantages__stat-text">
                                <?php echo esc_html($label); ?>
                            </p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div>
</section>