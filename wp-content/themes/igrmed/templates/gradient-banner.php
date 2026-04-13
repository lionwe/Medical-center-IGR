<?php
$title = trim((string) get_the_title());
$button_text = igrmed__('btn_contact_us');
$button_link = '#contact';

// Empty Fields Rule: title is required for this block.
if ($title === '') {
    return;
}
?>

<section class="gradient-banner">
        <div class="gradient-banner__inner">
            <h2 class="gradient-banner__title"><?php echo esc_html($title); ?></h2>

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
