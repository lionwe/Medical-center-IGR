<?php

/**
 * Section: Facts
 * Location: About page
 * ACF Fields: facts_title, facts_cards (repeater), facts_cards.text (editor)
 */

$title = (string) get_field('facts_title');
$facts_cards = get_field('facts_cards');
$cards = [];

if (is_array($facts_cards)) {
    foreach ($facts_cards as $card) {
        $text = '';
        if (is_array($card)) {
            $text = (string) ($card['text'] ?? '');
        }

        if ($text !== '') {
            $cards[] = $text;
        }
    }
}

if (!$title && empty($cards)) {
    return;
}

$cards_count = count($cards);
$left_count = (int) ceil($cards_count / 2);
$left_cards = array_slice($cards, 0, $left_count);
$right_cards = array_slice($cards, $left_count);
?>

<section class="about-facts">
    <div class="container">
        <div class="about-facts__wrapper">
            <div class="about-facts__left">
                <div class="about-facts__title"><?php echo wp_kses_post($title); ?></div>
                <?php
                get_template_part('templates/button', null, [
                    'text' => esc_html__("Зв'язатись з нами", 'igrmed'),
                    'link' => '#contact',
                    'type' => 'primary',
                    'primary_split' => true,
                    'icon_url' => get_template_directory_uri() . '/assets/img/svg/contact-arrow.svg',
                ]);
                ?>
            </div>

            <div class="about-facts__right">
                <div class="about-facts__bg" aria-hidden="true">
                    <div class="about-facts__bg-desktop">
                        <svg width="1187" height="1250" viewBox="0 0 1187 1250" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g filter="url(#filter0_f_766_2614)">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M761.609 257.232C851.815 264.145 952.29 231.207 1028.55 268.545C1104.75 305.856 1114.97 385.958 1152.64 448.696C1186.76 505.537 1219.53 560.06 1239.93 620.599C1265.9 697.67 1353.34 789.817 1288.62 852.285C1219.71 918.8 1066.32 854.381 963.736 887.178C880.05 913.932 848.355 1000.44 761.609 1020.77C667.455 1042.84 563.671 1030.09 472.104 1002.42C373.145 972.513 250.232 935.956 222.146 857.759C192.512 775.251 331.327 706.091 334.968 620.599C338.42 539.538 216.719 470.948 241.973 392.156C266.94 314.26 362.064 250.98 461.731 225.102C558.929 199.864 659.432 249.4 761.609 257.232Z" fill="url(#paint0_linear_766_2614)" fill-opacity="0.4" />
                            </g>
                            <defs>
                                <filter id="filter0_f_766_2614" x="0" y="0" width="1530" height="1250" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                                    <feFlood flood-opacity="0" result="BackgroundImageFix" />
                                    <feBlend mode="normal" in="SourceGraphic" in2="BackgroundImageFix" result="shape" />
                                    <feGaussianBlur stdDeviation="109" result="effect1_foregroundBlur_766_2614" />
                                </filter>
                                <linearGradient id="paint0_linear_766_2614" x1="-271.573" y1="629.176" x2="471.178" y2="1627.42" gradientUnits="userSpaceOnUse">
                                    <stop stop-color="#E6C0A3" />
                                    <stop offset="1" stop-color="#E6C0A3" />
                                </linearGradient>
                            </defs>
                        </svg>
                    </div>
                </div>

                <?php if ($cards_count > 0) : ?>
                    <div class="about-facts__features" id="about-facts-features">
                        <ol class="about-facts__column">
                            <?php foreach ($left_cards as $index => $card_text) : ?>
                                <li class="about-facts__card<?php if ($index >= 5) echo ' is-hidden-fact'; ?>">
                                    <span class="about-facts__card-number"><?php echo esc_html((string) ($index + 1)); ?></span>
                                    <div class="about-facts__card-text"><?php echo wp_kses_post($card_text); ?></div>
                                </li>
                            <?php endforeach; ?>
                        </ol>

                        <?php if (!empty($right_cards)) : ?>
                            <ol class="about-facts__column">
                                <?php foreach ($right_cards as $index => $card_text) : ?>
                                    <li class="about-facts__card<?php if (($left_count + $index) >= 5) echo ' is-hidden-fact'; ?>">
                                        <span class="about-facts__card-number"><?php echo esc_html((string) ($left_count + $index + 1)); ?></span>
                                        <div class="about-facts__card-text"><?php echo wp_kses_post($card_text); ?></div>
                                    </li>
                                <?php endforeach; ?>
                            </ol>
                        <?php endif; ?>
                        
                        <?php if ($cards_count > 5) : ?>
                            <div class="about-facts__read-more-item">
                                <button type="button" class="about-facts__read-more" aria-expanded="false"
                                    data-more-text="<?php echo esc_html__('ЧИТАТИ БІЛЬШЕ', 'igrmed'); ?>"
                                    data-less-text="<?php echo esc_html__('ЗГОРНУТИ', 'igrmed'); ?>">
                                    <span class="about-facts__read-more-text"><?php echo esc_html__('ЧИТАТИ БІЛЬШЕ', 'igrmed'); ?></span>
                                    <span class="about-facts__read-more-arrow" aria-hidden="true">
                                        <?php echo igrmed_get_svg('read-more-arrow'); ?>
                                    </span>
                                </button>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>


    </div>
</section>

<script>
    (function () {
        const root = document.querySelector('#about-facts-features');
        if (!root) return;

        const button = root.querySelector('.about-facts__read-more');
        if (!button) return;

        const moreText = button.dataset.moreText;
        const lessText = button.dataset.lessText;
        const textElement = button.querySelector('.about-facts__read-more-text');
        const hiddenItems = root.querySelectorAll('.is-hidden-fact');

        button.addEventListener('click', () => {
            const isExpanded = button.getAttribute('aria-expanded') === 'true';
            
            if (isExpanded) {
                // Collapse
                hiddenItems.forEach((item) => item.classList.add('is-hidden-fact'));
                button.setAttribute('aria-expanded', 'false');
                textElement.textContent = moreText;
            } else {
                // Expand
                hiddenItems.forEach((item) => item.classList.remove('is-hidden-fact'));
                button.setAttribute('aria-expanded', 'true');
                textElement.textContent = lessText;
            }
        });
    })();
</script>