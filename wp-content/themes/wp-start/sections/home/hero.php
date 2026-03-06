<?php
$hero_bg = get_field('home_hero_bg');
$hero_title = (string) get_field('home_hero_title');
$hero_text = (string) get_field('home_hero_text');
$hero_button = get_field('home_hero_button');

$hero_button_url = '';
$hero_button_label = '';
$hero_button_target = '_self';

if (is_array($hero_button)) {
	$hero_button_url = $hero_button['url'] ?? '';
	$hero_button_label = $hero_button['title'] ?? '';
	$hero_button_target = $hero_button['target'] ?? '_self';
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
			<?php if ($hero_title !== ''): ?>
				<h1 class="hero__title"><?php echo esc_html($hero_title); ?></h1>
			<?php endif; ?>

			<?php if ($hero_text !== ''): ?>
				<p class="hero__text"><?php echo nl2br(esc_html($hero_text)); ?></p>
			<?php endif; ?>

			<?php if ($hero_button_url !== '' && $hero_button_label !== ''): ?>
				<a class="hero__button" href="<?php echo esc_url($hero_button_url); ?>"
					target="<?php echo esc_attr($hero_button_target); ?>">
					<?php echo esc_html($hero_button_label); ?>
				</a>
			<?php endif; ?>
		</div>
	</div>
</section>