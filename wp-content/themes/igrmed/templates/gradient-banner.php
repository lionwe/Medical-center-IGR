<?php
$title = (string) get_the_title();
$button_text = (string) __("Зв'язатись з нами", 'igrmed');
$button_link = '#contact';
?>

<section class="gradient-banner">
        <div class="gradient-banner__inner">
            <?php if ($title !== ''): ?>
                <h2 class="gradient-banner__title"><?php echo esc_html($title); ?></h2>
            <?php endif; ?>

            <div class="gradient-banner__cta">
                <?php
                get_template_part('templates/button', null, [
                    'text' => $button_text,
                    'link' => $button_link,
                    'type' => 'primary',
                    'primary_split' => true,
                    'icon_url' => get_template_directory_uri() . '/assets/img/svg/contact-arrow.svg',
                ]);
                ?>
            </div>

    </div>
</section>
