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

<body>

		<header class="header">
			<div class="container">
				<nav class="main-nav">

					<?php the_custom_logo(); ?>
					<!-- 
								<?php get_template_part('templates/navigation', null, array('location' => 'menu-header')); ?> -->

				</nav>
			</div>
		</header>