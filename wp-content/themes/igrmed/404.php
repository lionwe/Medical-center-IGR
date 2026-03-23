<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, minimum-scale=1.0">
    <meta name="description" content="404 - Page Not Found">

    <?php wp_head(); ?>

    <title><?php wp_title(); ?></title>
</head>

<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>

    <main id="primary" class="site-main">
        <?php get_template_part('sections/404/content'); ?>
    </main>

    <?php wp_footer(); ?>
</body>

</html>
