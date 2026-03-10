<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, minimum-scale=1.0">
	<meta name="description" content="Side maded on Wordpress by Recipe team">

	<?php wp_head(); ?>

	<title><?php wp_title(); ?></title>

</head>

<body <?php body_class(); ?>>
	<?php wp_body_open(); ?>
	<?php
	$header_address = (string) get_field('address_main', 'option');
	$header_phone_1 = get_field('phone_1', 'option');
	$header_phone_2 = get_field('phone_2', 'option');
	$header_schedule = (string) get_field('schedule', 'option');
	$header_address_icon = get_field('icon_address', 'option');
	$header_phone_icon = get_field('icon_phone', 'option');
	$header_clock_icon = get_field('icon_clock', 'option');
	$header_search_icon = get_field('icon_search', 'option');

	$header_search_icon_url = '';
	if (is_array($header_search_icon) && !empty($header_search_icon['url'])) {
		$header_search_icon_url = (string) $header_search_icon['url'];
	} elseif (is_numeric($header_search_icon)) {
		$header_search_icon_url = (string) wp_get_attachment_url((int) $header_search_icon);
	} elseif (is_string($header_search_icon) && $header_search_icon !== '') {
		$header_search_icon_url = $header_search_icon;
	}

	$header_phone_1_url = '';
	$header_phone_1_label = '';
	$header_phone_1_target = '_self';

	if (is_array($header_phone_1)) {
		$header_phone_1_url = $header_phone_1['url'] ?? '';
		$header_phone_1_label = $header_phone_1['title'] ?? '';
		$header_phone_1_target = $header_phone_1['target'] ?? '_self';
	}

	$header_phone_2_url = '';
	$header_phone_2_label = '';
	$header_phone_2_target = '_self';

	if (is_array($header_phone_2)) {
		$header_phone_2_url = $header_phone_2['url'] ?? '';
		$header_phone_2_label = $header_phone_2['title'] ?? '';
		$header_phone_2_target = $header_phone_2['target'] ?? '_self';
	}

	$header_resolve_media_url = static function ($value): string {
		if (is_array($value) && !empty($value['url'])) {
			return (string) $value['url'];
		}
		if (is_numeric($value)) {
			$url = wp_get_attachment_url((int) $value);
			return $url ? (string) $url : '';
		}
		if (is_string($value)) {
			return $value;
		}
		return '';
	};

	$header_social_items = [];
	$header_social_fields = [
		'instagram' => ['link' => 'link_instagram', 'icon' => 'icon_instagram', 'label' => 'Instagram'],
		'facebook' => ['link' => 'link_facebook', 'icon' => 'icon_facebook', 'label' => 'Facebook'],
		'tiktok' => ['link' => 'link_tiktok', 'icon' => 'icon_tiktok', 'label' => 'TikTok'],
	];
	foreach ($header_social_fields as $config) {
		$link = (string) get_field($config['link'], 'option');
		if ($link === '') {
			continue;
		}
		$icon_value = get_field($config['icon'], 'option');
		$icon_url = $header_resolve_media_url($icon_value);
		$header_social_items[] = [
			'link' => $link,
			'icon_url' => $icon_url,
			'label' => $config['label'],
		];
	}
	?>

	<header class="header">
		<div class="container">
			<div class="header__wrapper">
				<nav class="header__nav" aria-label="<?php esc_attr_e('Main navigation', 'igr-theme'); ?>">
					<div class="header__logo">
						<?php if (has_custom_logo()): ?>
							<?php the_custom_logo(); ?>
						<?php else: ?>
							<a href="<?php echo esc_url(home_url('/')); ?>"
								aria-label="<?php esc_attr_e('Homepage', 'igr-theme'); ?>">
								<span class="header__logo-text"><?php bloginfo('name'); ?></span>
							</a>
						<?php endif; ?>
					</div>

					<div class="header__menu">
						<div class="header__content">
							<div class="header__top">
								<div class="header__top-container">
									<div class="header__top-info">
										<?php if ($header_address !== ''): ?>
											<div class="header__address-block">
												<?php if (!empty($header_address_icon['url'])): ?>
													<span class="header__info-icon">
														<?php
														get_picture([
															'src' => $header_address_icon['url'],
															'alt' => $header_address_icon['alt'] ?? 'Address icon',
															'class' => 'header__info-icon-image',
															'lazy' => false,
														]);
														?>
													</span>
												<?php endif; ?>

												<span
													class="header__address"><?php echo esc_html($header_address); ?></span>
											</div>
										<?php endif; ?>

										<?php if ($header_phone_1_url !== '' || $header_phone_2_url !== ''): ?>
											<div class="header__phones-block">
												<?php if (!empty($header_phone_icon['url'])): ?>
													<span class="header__info-icon">
														<?php
														get_picture([
															'src' => $header_phone_icon['url'],
															'alt' => $header_phone_icon['alt'] ?? 'Phone icon',
															'class' => 'header__info-icon-image',
															'lazy' => false,
														]);
														?>
													</span>
												<?php endif; ?>

												<ul class="header__phones-list">
													<?php if ($header_phone_1_url !== ''): ?>
														<li class="header__phones-item">
															<a class="header__phone"
																href="<?php echo esc_url($header_phone_1_url); ?>"
																target="<?php echo esc_attr($header_phone_1_target); ?>">
																<?php echo esc_html($header_phone_1_label !== '' ? $header_phone_1_label : $header_phone_1_url); ?>
															</a>
														</li>
													<?php endif; ?>

													<?php if ($header_phone_2_url !== ''): ?>
														<li class="header__phones-item">
															<a class="header__phone"
																href="<?php echo esc_url($header_phone_2_url); ?>"
																target="<?php echo esc_attr($header_phone_2_target); ?>">
																<?php echo esc_html($header_phone_2_label !== '' ? $header_phone_2_label : $header_phone_2_url); ?>
															</a>
														</li>
													<?php endif; ?>
												</ul>
											</div>
										<?php endif; ?>

										<?php if ($header_schedule !== ''): ?>
											<div class="header__schedule-block">
												<?php if (!empty($header_clock_icon['url'])): ?>
													<span class="header__info-icon">
														<?php
														get_picture([
															'src' => $header_clock_icon['url'],
															'alt' => $header_clock_icon['alt'] ?? 'Schedule icon',
															'class' => 'header__info-icon-image',
															'lazy' => false,
														]);
														?>
													</span>
												<?php endif; ?>

												<span
													class="header__schedule"><?php echo wp_kses_post($header_schedule); ?></span>
											</div>
										<?php endif; ?>
									</div>

									<?php if (!empty($header_social_items)): ?>
										<ul class="header__socials"
											aria-label="<?php esc_attr_e('Social links', 'igr-theme'); ?>">
											<?php foreach ($header_social_items as $item): ?>
												<li class="header__socials-item">
													<?php
													get_template_part('templates/button', null, [
														'link' => $item['link'],
														'type' => 'social',
														'class' => 'header__socials-link',
														'icon_url' => $item['icon_url'] !== '' ? $item['icon_url'] : null,
														'target' => '_blank',
														'attributes' => [
															'rel' => 'noopener noreferrer',
															'aria-label' => $item['label'],
														],
													]);
													?>
												</li>
											<?php endforeach; ?>
										</ul>
									<?php endif; ?>
								</div>
							</div>
							<div class="header__bottom">
								<div class="header__controls">
									<div class="header__search">
										<input class="header__search-input" type="search"
											placeholder="<?php esc_attr_e('Пошук', 'igr-theme'); ?>">
										<span class="header__search-btn" aria-hidden="true">
											<?php if ($header_search_icon_url !== ''): ?>
												<?php
												get_picture([
													'src' => $header_search_icon_url,
													'alt' => '',
													'class' => 'header__search-icon',
													'lazy' => false,
												]);
												?>
											<?php endif; ?>
										</span>
									</div>
									<div class="header__lang" data-lang-switcher>
										<select class="header__lang-select"
											aria-label="<?php esc_attr_e('Language switcher', 'igr-theme'); ?>">
											<option value="uk">UA</option>
											<option value="en">EN</option>
										</select>
										<span class="header__lang-icon" aria-hidden="true">
											<img src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/svg/lang-switcher-arrow.svg'); ?>"
												alt="">
										</span>
									</div>

								</div>
								<?php get_template_part('templates/navigation', null, array('location' => 'menu-header')); ?>
								<a href="#contact" class="header__cta">
									<span class="header__cta-text"><?php esc_html_e("Зв'язатись з нами", 'igr-theme'); ?></span>
									<span class="header__cta-icon">
										<img src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/svg/contact-arrow-circle.svg'); ?>" alt="">
									</span>
								</a>
							</div>


						</div>

					</div>


				</nav>
			</div>
		</div>
	</header>