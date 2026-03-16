<?php
$hero_bg_url = get_template_directory_uri() . '/assets/img/bread-crumbs.webp';
$hero_style = ' style="background-image: url(' . esc_url($hero_bg_url) . ');"';
$title_pregnant = (string) get_field('title_pregnant');
$right_text_pregnant = (string) get_field('right_text_pregnant');
?>

<section id="infertility-women-hero" class="infertility-women-hero" <?php echo $hero_style; ?>>
    <div class="container">
        <?php get_template_part('templates/breadcrumbs'); ?>

        <div class="infertility-women-hero__wrapper">
            <div class="infertility-women-hero__left">
                <?php if ($title_pregnant !== ''): ?>
                    <div class="infertility-women-hero__left-title"><?php echo wp_kses_post($title_pregnant); ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="infertility-women-hero__right">
                <?php if ($right_text_pregnant !== ''): ?>
                    <div class="infertility-women-hero__text"><?php echo wp_kses_post($right_text_pregnant); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>