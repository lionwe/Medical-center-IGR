<?php
/**
 * Ведення вагітності — контент сторінки послуги.
 */

$intro_title   = trim((string) get_field('intro_title'));
$intro_content = get_field('intro_content');
$intro_content = is_string($intro_content) ? trim($intro_content) : '';

$advantages_title = trim((string) get_field('advantages_title'));
$advantages_list  = get_field('advantages_list');
$advantages_list  = is_array($advantages_list) ? array_filter($advantages_list) : [];

$procedures_title = trim((string) get_field('procedures_title'));
$procedures_list  = get_field('procedures_list');
$procedures_list  = is_array($procedures_list) ? array_filter($procedures_list) : [];

$doctors_title = trim((string) get_field('doctors_title'));
$doctors_intro = trim((string) get_field('doctors_intro'));
$doctors_list  = get_field('doctors_list');
$doctors_list  = is_array($doctors_list) ? array_filter($doctors_list) : [];

$post_content  = trim((string) get_post_field('post_content', get_the_ID()));

// Блок показується лише якщо є реальний контент
$has_intro      = $intro_title !== '' || $intro_content !== '';
$has_advantages = !empty($advantages_list);
$has_procedures = !empty($procedures_list);
$has_doctors    = !empty($doctors_list);
$has_content    = $post_content !== '';

$sections = [];

if ($has_intro) {
    $sections[] = [
        'id'    => 'preg-intro',
        'title' => $intro_title !== '' ? $intro_title : __('Ведення вагітності', 'igrmed'),
    ];
}
if ($has_advantages) {
    $sections[] = [
        'id'    => 'preg-advantages',
        'title' => $advantages_title !== '' ? $advantages_title : __('Переваги ведення вагітності', 'igrmed'),
    ];
}
if ($has_procedures) {
    $sections[] = [
        'id'    => 'preg-procedures',
        'title' => $procedures_title !== '' ? $procedures_title : __('Процедури діагностики при веденні вагітності', 'igrmed'),
    ];
}
if ($has_doctors) {
    $sections[] = [
        'id'    => 'preg-doctors',
        'title' => $doctors_title !== '' ? $doctors_title : __('Лікарі, які ведуть лікування', 'igrmed'),
    ];
}

if (empty($sections) && !$has_content) {
    return;
}
?>

<section class="pregnancy-management-content">
    <div class="container">
        <div class="pregnancy-management-content__layout catalog-wrap">
            <?php get_template_part('templates/content-sidebar', null, ['sections' => $sections]); ?>

            <div class="pregnancy-management-content__content">

                <?php if ($has_intro) : ?>
                    <div id="preg-intro" class="pregnancy-management-content__section pregnancy-management-content__section--intro">
                        <div class="pregnancy-management-content__title-wrap pregnancy-management-content__section--intro__title-wrap">
                            <h2 class="pregnancy-management-content__title">
                                <?php echo esc_html($intro_title !== '' ? $intro_title : __('Ведення вагітності', 'igrmed')); ?>
                            </h2>
                        </div>
                        <?php if ($intro_content !== '') : ?>
                            <div class="pregnancy-management-content__body pregnancy-management-content__section--intro__body">
                                <?php echo wp_kses_post($intro_content); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <?php if ($has_advantages) : ?>
                    <div id="preg-advantages" class="pregnancy-management-content__section pregnancy-management-content__section--advantages">
                        <div class="pregnancy-management-content__title-wrap">
                            <h2 class="pregnancy-management-content__title">
                                <?php echo esc_html($advantages_title !== '' ? $advantages_title : __('Переваги ведення вагітності', 'igrmed')); ?>
                            </h2>
                        </div>
                        <div class="pregnancy-management-content__body pregnancy-management-content__section--advantages__body">
                            <div class="pregnancy-management-content__list">
                                <?php foreach ($advantages_list as $item) : ?>
                                    <article class="pregnancy-management-content__item">
                                        <?php if (!empty($item['title'])) : ?>
                                            <h3><?php echo esc_html($item['title']); ?></h3>
                                        <?php endif; ?>
                                        <?php if (!empty($item['text'])) : ?>
                                            <?php echo wp_kses_post($item['text']); ?>
                                        <?php endif; ?>
                                    </article>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($has_procedures) : ?>
                    <div id="preg-procedures" class="pregnancy-management-content__section pregnancy-management-content__section--procedures">
                        <div class="pregnancy-management-content__title-wrap">
                            <h2 class="pregnancy-management-content__title">
                                <?php echo esc_html($procedures_title !== '' ? $procedures_title : __('Процедури діагностики при веденні вагітності', 'igrmed')); ?>
                            </h2>
                        </div>
                        <div class="pregnancy-management-content__body pregnancy-management-content__section--procedures__body">
                            <div class="pregnancy-management-content__grid">
                                <?php foreach ($procedures_list as $item) : ?>
                                    <article class="pregnancy-management-content__card pregnancy-management-content__section--procedures__card">
                                        <span class="pregnancy-management-content__accent" aria-hidden="true"></span>
                                        <?php if (!empty($item['title'])) : ?>
                                            <strong><?php echo esc_html($item['title']); ?></strong>
                                        <?php endif; ?>
                                        <?php if (!empty($item['link']['url'])) : ?>
                                            <a href="<?php echo esc_url($item['link']['url']); ?>"
                                               class="pregnancy-management-content__link"
                                               <?php echo !empty($item['link']['target']) ? 'target="' . esc_attr($item['link']['target']) . '"' : ''; ?>>
                                                <?php echo !empty($item['link']['title']) ? esc_html($item['link']['title']) : __('Докладніше', 'igrmed'); ?>
                                            </a>
                                        <?php endif; ?>
                                    </article>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($has_doctors) : ?>
                    <div id="preg-doctors" class="pregnancy-management-content__section pregnancy-management-content__section--doctors">
                        <div class="pregnancy-management-content__title-wrap">
                            <h2 class="pregnancy-management-content__title">
                                <?php echo esc_html($doctors_title !== '' ? $doctors_title : __('Лікарі, які ведуть лікування', 'igrmed')); ?>
                            </h2>
                        </div>
                        <div class="pregnancy-management-content__body pregnancy-management-content__section--doctors__body">
                            <?php if ($doctors_intro !== '') : ?>
                                <p class="pregnancy-management-content__intro"><?php echo esc_html($doctors_intro); ?></p>
                            <?php endif; ?>

                            <div class="pregnancy-management-content__slider">
                                <?php foreach ($doctors_list as $doctor) : ?>
                                    <?php
                                    $doctor_id        = $doctor->ID;
                                    $doctor_name      = get_the_title($doctor_id);
                                    $doctor_permalink = get_permalink($doctor_id);
                                    $doctor_specialty = get_field('specialty', $doctor_id);
                                    $doctor_photo     = get_the_post_thumbnail_url($doctor_id, 'medium');
                                    ?>
                                    <div class="pregnancy-management-content__card pregnancy-management-content__section--doctors__card">
                                        <?php if ($doctor_photo) : ?>
                                            <div class="pregnancy-management-content__photo">
                                                <img src="<?php echo esc_url($doctor_photo); ?>"
                                                     alt="<?php echo esc_attr($doctor_name); ?>"
                                                     loading="lazy">
                                            </div>
                                        <?php endif; ?>
                                        <div class="pregnancy-management-content__info">
                                            <h3 class="pregnancy-management-content__name">
                                                <a href="<?php echo esc_url($doctor_permalink); ?>">
                                                    <?php echo esc_html($doctor_name); ?>
                                                </a>
                                            </h3>
                                            <?php if ($doctor_specialty) : ?>
                                                <p class="pregnancy-management-content__specialty">
                                                    <?php echo esc_html($doctor_specialty); ?>
                                                </p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($has_content) : ?>
                    <div class="pregnancy-management-content__editor entry-content">
                        <?php the_content(); ?>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</section>