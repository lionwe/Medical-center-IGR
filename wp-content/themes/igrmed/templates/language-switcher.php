<?php
/**
 * Custom language dropdown (Polylang). Replaces native &lt;select&gt;; keeps header visual style.
 *
 * @param array $args {
 *     @type string $class Root classes, e.g. 'header__lang header__lang--desktop'
 * }
 */

defined('ABSPATH') || exit;

$class = isset($args['class']) ? (string) $args['class'] : 'header__lang';

$languages = [];
if (function_exists('pll_the_languages')) {
    $raw = pll_the_languages(['raw' => 1, 'hide_if_empty' => false]);
    if (is_array($raw) && $raw !== []) {
        $languages = array_values($raw);
    }
}

if ($languages === []) {
    $languages = [
        ['slug' => 'uk', 'name' => 'UA', 'url' => home_url('/'), 'current_lang' => true],
        ['slug' => 'en', 'name' => 'EN', 'url' => home_url('/en/'), 'current_lang' => false],
    ];
}

$current_label = 'UA';
foreach ($languages as $lang) {
    if (!empty($lang['current_lang'])) {
        $current_label = strtoupper((string) $lang['slug']);
        break;
    }
}

$arrow_url = get_template_directory_uri() . '/assets/img/svg/lang-switcher-arrow.svg';
$aria_label = __('Language switcher', 'igr-theme');
?>
<div class="<?php echo esc_attr($class); ?>" data-lang-switcher>
    <button type="button"
        class="header__lang-trigger"
        aria-expanded="false"
        aria-haspopup="listbox"
        aria-label="<?php echo esc_attr($aria_label); ?>">
        <span class="header__lang-trigger-label"><?php echo esc_html($current_label); ?></span>
        <span class="header__lang-icon" aria-hidden="true">
            <?php
            get_picture([
                'name' => 'svg/lang-switcher-arrow.svg',
                'alt' => 'Language switcher arrow',
                'class' => '',
                'lazy' => false,
            ]);
            ?>
        </span>
    </button>
    <div class="header__lang-dropdown" aria-hidden="true">
        <ul class="header__lang-dropdown-list" role="listbox">
            <?php foreach ($languages as $lang) :
                $slug = isset($lang['slug']) ? (string) $lang['slug'] : '';
                $url = isset($lang['url']) ? (string) $lang['url'] : '#';
                $is_current = !empty($lang['current_lang']);
                $short = $slug !== '' ? strtoupper($slug) : '';
                ?>
                <li class="header__lang-dropdown-item" role="none">
                    <a role="option"
                        href="<?php echo esc_url($url); ?>"
                        class="header__lang-dropdown-link<?php echo $is_current ? ' is-current' : ''; ?>"
                        data-lang="<?php echo esc_attr($slug); ?>"
                        <?php echo $is_current ? 'aria-selected="true"' : ''; ?>>
                        <?php echo esc_html($short); ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
        <div class="header__lang-dropdown-circle" aria-hidden="true">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 120 120" focusable="false">
                <circle cx="60" cy="60" r="60" fill="var(--color-white, #fff)"/>
            </svg>
        </div>
    </div>
</div>
