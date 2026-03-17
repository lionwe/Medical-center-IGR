<?php
$hero_bg_url = get_template_directory_uri() . '/assets/img/bread-crumbs.webp';
$hero_style = ' style="background-image: url(' . esc_url($hero_bg_url) . ');"';
$title = trim((string) get_field('diagnostics-research_title'));
$types_rows = get_field('diagnostics-research_types');
$image = get_field('diagnostics-research_img');

$types = [];
if (is_array($types_rows)) {
    foreach ($types_rows as $row) {
        if (!is_array($row)) {
            continue;
        }

        $item = trim((string) ($row['item'] ?? ''));
        if ($item !== '') {
            $types[] = $item;
        }
    }
}

$image_url = '';
if (is_array($image)) {
    $image_url = (string) ($image['url'] ?? '');
} elseif (is_numeric($image)) {
    $image_url = (string) wp_get_attachment_image_url((int) $image, 'full');
} elseif (is_string($image)) {
    $image_url = $image;
}
?>

<section id="diagnostics-research-hero" class="diagnostics-research-hero" <?php echo $hero_style; ?>>
    <div class="container">
        <?php get_template_part('templates/breadcrumbs'); ?>
        <?php get_template_part('templates/gradient-banner'); ?>

        <div class="diagnostics-research-hero__wrapper">
            <div class="diagnostics-research-hero__left">
                <?php if ($title !== ''): ?>
                    <h2 class="diagnostics-research-hero__title"><?php echo esc_html($title); ?></h2>
                <?php endif; ?>

                <div class="diagnostics-research-hero__action">
                    <?php
                    get_template_part('templates/button', null, [
                        'text' => 'Зв’язатись з нами',
                        'link' => '#cta',
                        'type' => 'tertiary',
                        'icon_url' => get_template_directory_uri() . '/assets/img/svg/contact-arrow-up-right.svg',
                    ]);
                    ?>
                </div>

                <?php if (!empty($types)): ?>
                    <ul class="diagnostics-research-hero__list">
                        <?php foreach ($types as $item): ?>
                            <li class="diagnostics-research-hero__item"><?php echo esc_html($item); ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
            <div class="diagnostics-research-hero__right">
                <?php
                if ($image_url !== '') {
                    get_picture([
                        'src' => $image_url,
                        'alt' => '',
                        'class' => 'diagnostics-research-hero__img',
                    ]);
                }
                ?>
            </div>
        </div>
    </div>
</section>
