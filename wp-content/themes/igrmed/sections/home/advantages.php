<?php

/**
 * Section: Advantages
 * Fields: advantages_content, advantages_list, advantages_numbers
 */

$content = get_field('advantages_content', get_the_ID());
$list    = get_field('advantages_list', get_the_ID());
$numbers = get_field('advantages_numbers', get_the_ID());

if (!is_array($list)) {
    $list = [];
}

if (!is_array($numbers)) {
    $numbers = [];
}

if (!$content && empty($list) && empty($numbers)) {
    return;
}
?>

<section class="advantages" id="advantages">

    <div class="advantages__bg" aria-hidden="true">
        <!-- Desktop background -->
        <div class="advantages__bg-desktop">
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

        <!-- Mobile background -->
        <div class="advantages__bg-mobile">
            <svg width="375" height="808" viewBox="0 0 375 808" fill="none" xmlns="http://www.w3.org/2000/svg">
                <g filter="url(#filter0_f_840_527)">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M216.736 235.929C263.653 239.089 315.912 224.036 355.573 241.099C395.206 258.151 400.521 294.757 420.113 323.428C437.864 349.405 454.906 374.322 465.515 401.989C479.021 437.21 524.503 479.322 490.841 507.87C454.999 538.267 375.221 508.828 321.865 523.816C278.339 536.043 261.854 575.576 216.736 584.868C167.766 594.954 113.787 589.125 66.1621 576.48C14.6923 562.814 -49.2358 546.108 -63.8436 510.371C-79.2566 472.665 -7.05746 441.059 -5.16395 401.989C-3.36858 364.944 -66.6661 333.598 -53.5313 297.59C-40.5458 261.991 8.92906 233.072 60.767 221.245C111.32 209.712 163.593 232.35 216.736 235.929Z" fill="url(#paint0_linear_840_527)" fill-opacity="0.4" />
                </g>
                <defs>
                    <filter id="filter0_f_840_527" x="-284" y="0" width="1005" height="808" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                        <feFlood flood-opacity="0" result="BackgroundImageFix" />
                        <feBlend mode="normal" in="SourceGraphic" in2="BackgroundImageFix" result="shape" />
                        <feGaussianBlur stdDeviation="109" result="effect1_foregroundBlur_840_527" />
                    </filter>
                    <linearGradient id="paint0_linear_840_527" x1="-27.4734" y1="457.531" x2="268.772" y2="548.194" gradientUnits="userSpaceOnUse">
                        <stop stop-color="#79B6E3" />
                        <stop offset="1" stop-color="#E6C0A3" />
                    </linearGradient>
                </defs>
            </svg>
        </div>
    </div>

    <div class="container">

        <?php if ($content) : ?>
            <div class="advantages__header">
                <?php echo wp_kses_post($content); ?>
            </div>
        <?php endif; ?>

        <?php if (! empty($list)) : ?>

            <!-- Desktop: regular grid -->
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
                                <?php
                                get_picture([
                                    'src' => $icon['sizes']['thumbnail'] ?? $icon['url'],
                                    'alt' => $icon['alt'],
                                    'class' => 'advantages__item-img',
                                    'lazy' => true,
                                ]);
                                ?>
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

            <!-- Mobile: swiper -->
            <div class="advantages__list--swiper swiper">
                <div class="swiper-wrapper">
                    <?php foreach ($list as $item) :
                        $icon = $item['icon'] ?? null;
                        $text = $item['text'] ?? '';

                        if (! $icon && ! $text) {
                            continue;
                        }
                    ?>
                        <div class="swiper-slide">
                            <div class="advantages__item">
                                <?php if ($icon) : ?>
                                    <div class="advantages__item-icon">
                                        <?php
                                        get_picture([
                                            'src' => $icon['sizes']['thumbnail'] ?? $icon['url'],
                                            'alt' => $icon['alt'],
                                            'class' => 'advantages__item-img',
                                            'lazy' => true,
                                        ]);
                                        ?>
                                    </div>
                                <?php endif; ?>

                                <?php if ($text) : ?>
                                    <div class="advantages__item-text">
                                        <?php echo wp_kses_post($text); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Swiper pagination (shown only on mobile via CSS) -->
            <div class="advantages__list-pagination swiper-pagination"></div>

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