<?php
$hero_bg = get_field('home_hero_bg');
$hero_title = (string) get_field('home_hero_title');
$hero_text = (string) get_field('home_hero_text');
$hero_button = get_field('home_hero_button');
$hero_cards = get_field('hero_cards');

$hero_button_url = '';
$hero_button_label = '';
$hero_button_target = '_self';

if (is_array($hero_button)) {
	$hero_button_url = $hero_button['url'] ?? '';
	$hero_button_label = $hero_button['title'] ?? '';
	$hero_button_target = $hero_button['target'] ?? '_self';
}

$promo_title = '';
$promo_badge = '';
$promo_subtitle = '';
$promo_text = '';
$promo_link = '';

$article_post = null;
$article_read_time = '';
$article_title = '';
$article_url = '';

$socials_title = '';
$schedule_title = '';
$schedule_text = '';

if (is_array($hero_cards)) {
	$promo_title = (string) ($hero_cards['promo_title'] ?? '');
	$promo_badge = (string) ($hero_cards['promo_badge'] ?? '');
	$promo_subtitle = (string) ($hero_cards['promo_subtitle'] ?? '');
	$promo_text = (string) ($hero_cards['promo_text'] ?? '');
	$promo_link = (string) ($hero_cards['promo_link'] ?? '');

	$article_post = $hero_cards['article_post'] ?? null;
	$article_read_time = (string) ($hero_cards['article_read_time'] ?? '');

	$socials_title = (string) ($hero_cards['socials_title'] ?? '');
	$schedule_title = (string) ($hero_cards['schedule_title'] ?? '');
}

$schedule_text = '';
if (function_exists('get_field')) {
	$schedule_text = (string) get_field('schedule_2', 'option');
}

if ($article_post instanceof WP_Post) {
	$article_title = get_the_title($article_post);
	$article_url = get_permalink($article_post);
}

$social_items = [];
$social_fields = [
	'instagram' => ['link' => 'link_instagram', 'icon' => 'icon_instagram', 'label' => 'Instagram'],
	'facebook' => ['link' => 'link_facebook', 'icon' => 'icon_facebook', 'label' => 'Facebook'],
	'tiktok' => ['link' => 'link_tiktok', 'icon' => 'icon_tiktok', 'label' => 'TikTok'],
	'telegram' => ['link' => 'link_telegram', 'icon' => 'icon_telegram', 'label' => 'Telegram'],
];

foreach ($social_fields as $social => $config) {
	$icon_value = get_field($config['icon'], 'option');
	$icon_url = is_array($icon_value) && !empty($icon_value['url']) ? $icon_value['url'] : (is_numeric($icon_value) ? wp_get_attachment_url((int) $icon_value) : ($icon_value ?: ''));
	$link = (string) get_field($config['link'], 'option');

	if ($link === '' && $icon_url === '') {
		continue;
	}

	$social_items[] = [
		'link' => $link !== '' ? $link : '#',
		'icon_url' => $icon_url,
		'label' => $config['label'],
	];
}

$hero_bg_url = '';
if (is_string($hero_bg) && $hero_bg !== '') {
	$hero_bg_url = $hero_bg;
} elseif (is_array($hero_bg) && !empty($hero_bg['url'])) {
	$hero_bg_url = (string) $hero_bg['url'];
}

?>

<section id="hero" class="hero">
	<?php if ($hero_bg_url !== ''): ?>
		<div class="hero__bg" aria-hidden="true">
			<?php
			get_picture([
				'src' => $hero_bg_url,
				'alt' => '',
				'class' => 'hero__bg-image',
				'lazy' => false,
			]);
			?>
		</div>
	<?php endif; ?>

	<div class="container">
		<div class="hero__content">
			<?php if ($hero_title !== '' || ($hero_button_url !== '' && $hero_button_label !== '')): ?>
				<div class="hero__top">
					<?php if ($hero_title !== ''): ?>
						<h1 class="hero__title"><?php echo wp_kses_post($hero_title); ?></h1>
					<?php endif; ?>
					<div class="hero__top-actions">
						<p class="text-xs semibold">Детальніше про нас</p>


						<?php
						get_template_part('templates/button', null, [
							'link' => $promo_link !== '' ? $promo_link : '#',
							'type' => 'primary',
							'icon_url' => get_template_directory_uri() . '/assets/img/svg/contact-arrow.svg',
							'target' => '_self',
						]);
						?>

					</div>
				</div>




			<?php endif; ?>

			<?php if ($promo_title !== '' || $article_title !== '' || $socials_title !== '' || $schedule_title !== '' || $schedule_text !== ''): ?>
				<div class="hero__middle">
					<article class="hero__card hero__card--promo">
						<div class="hero__card-header">
							<?php if ($promo_title !== ''): ?>
								<h3 class="hero__card-title"><?php echo wp_kses_post($promo_title); ?></h3>
							<?php endif; ?>

							<?php if ($promo_badge !== ''): ?>
								<p class="hero__card-badge"><?php echo wp_kses_post($promo_badge); ?></p>
							<?php endif; ?>
						</div>

						<div class="hero__card-body">
							<?php if ($promo_subtitle !== ''): ?>
								<p class="hero__card-subtitle"><?php echo wp_kses_post($promo_subtitle); ?></p>
							<?php endif; ?>

							<?php if ($promo_text !== ''): ?>
								<p class="hero__card-text"><?php echo wp_kses_post(nl2br($promo_text)); ?></p>
							<?php endif; ?>
						</div>

						<div class="hero__card-actions">
							<?php
							get_template_part('templates/button', null, [
								'link' => $promo_link !== '' ? $promo_link : '#',
								'type' => 'primary-dark',
								'icon_url' => get_template_directory_uri() . '/assets/img/svg/contact-arrow.svg',
								'target' => '_self',
							]);
							?>
						</div>
					</article>

					<article class="hero__card hero__card--article">
						<?php if ($article_title !== ''): ?>
							<h3 class="hero__card-title"><?php echo wp_kses_post($article_title); ?></h3>
						<?php endif; ?>

						<?php if ($article_read_time !== ''): ?>
							<p class="hero__card-meta"><?php echo wp_kses_post($article_read_time); ?></p>
						<?php endif; ?>

						<?php if ($article_url !== ''): ?>
							<a class="hero__card-link" href="<?php echo esc_url($article_url); ?>">
								<?php esc_html_e('Читати статтю', 'igr-theme'); ?>
							</a>
						<?php endif; ?>
					</article>

					<article class="hero__card hero__card--info">
						<div class="hero__card-inner">
							<?php if ($socials_title !== ''): ?>
								<h3 class="hero__card-title"><?php echo wp_kses_post($socials_title); ?></h3>
							<?php endif; ?>

							<?php if (!empty($social_items)): ?>
								<ul class="hero__socials" aria-label="<?php esc_attr_e('Social links', 'igr-theme'); ?>">
									<?php foreach ($social_items as $social_item): ?>
										<li class="hero__socials-item">
											<span class="hero__socials-label"><?php echo esc_html($social_item['label']); ?></span>
											<?php
											get_template_part('templates/button', null, [
												'link' => $social_item['link'],
												'type' => 'social',
												'class' => 'hero__socials-link',
												'text' => $social_item['icon_url'] === '' ? $social_item['label'] : '',
												'icon_url' => $social_item['icon_url'] !== '' ? $social_item['icon_url'] : null,
												'target' => '_blank',
												'attributes' => ['rel' => 'noopener noreferrer'],
											]);
											?>
										</li>
									<?php endforeach; ?>
								</ul>
							<?php endif; ?>

							<?php if ($schedule_text !== ''): ?>
								<div class="hero__schedule">
									<?php echo wp_kses_post($schedule_text); ?>
								</div>
							<?php endif; ?>
						</div>
					</article>
				</div>
			<?php endif; ?>

			<?php if ($hero_text !== ''): ?>
				<div class="hero__bottom">
					<p class="hero__bottom-text"><?php echo wp_kses_post(nl2br($hero_text)); ?></p>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>