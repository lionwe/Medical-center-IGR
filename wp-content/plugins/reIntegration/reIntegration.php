<?php
/* 
	 plugin Name: reIntegration
	 Description: Цей плагін призначений для того щоб додати на сайт форму яка буде підвязана інтеграцією з різними сервісами та виконувати всю логіку на сервері.
	 Version: 0.1.0 beta
	 Author: Tor4in
	 Author URI: https://github.com/Tor4in
 */



if (
	!defined('ABSPATH') ||
	!defined('WPINC')
) {
	die;
}

define('REINTEGRATION_PLUGIN_ACTIVE', true);

defined('REINTEGRATION_PLUGIN_DIR') || define('REINTEGRATION_PLUGIN_DIR', plugin_dir_url(__FILE__));

require_once plugin_dir_path(__FILE__) . 'pages/index.php';
require_once plugin_dir_path(__FILE__) . 'includes/ajax/index.php';

require_once plugin_dir_path(__FILE__) . 'includes/index.php';

add_action('wp_enqueue_scripts', function () {
	wp_enqueue_script(
		'reintegration-script',
		plugin_dir_url(__FILE__) . 'dist/script.js',
		[],
		'1.0',
		true
	);

	wp_script_add_data('reintegration-script', 'type', 'module');
	wp_add_inline_script('reintegration-script', 'const reintegration_ajax = ' . json_encode([
		'ajax_url' => admin_url('admin-ajax.php'),
		'nonce' => wp_create_nonce('reintegration_form'),
	]) . ';', 'before');
});

register_activation_hook(__FILE__, function () {
	REIntegration_Database::install();
});

// TODO
// register_deactivation_hook(__FILE__, function () {
// 	// Деактивувати reIntegrationTelegram та reIntegrationSheets
// 	deactivate_plugins([
// 		'reIntegrationTelegram/reIntegrationTelegram.php',
// 		'reIntegrationSheets/reIntegrationSheets.php'
// 	]);
// });