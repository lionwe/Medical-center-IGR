<?php

add_action('wp_ajax_reintegration_send_form', 'reintegration_send_form_ajax');
add_action('wp_ajax_nopriv_reintegration_send_form', 'reintegration_send_form_ajax');

function reintegration_send_form_ajax()
{
	if (!isset($_POST['form_data'])) {
		wp_send_json_error(['message' => 'Недостатньо даних для відправки форми']);
		return;
	}
	$form_data = $_POST['form_data'];
	$referer = wp_get_referer();

	$form_data = json_decode(stripslashes($form_data), true);
	if (json_last_error() !== JSON_ERROR_NONE) {
		wp_send_json_error(['message' => 'Помилка декодування JSON: ' . json_last_error_msg()]);
		return;
	}
	$form_data = apply_filters('reintegration_before_send_form_data', $form_data);
	REIntegration_Integrations::send($form_data, $referer);

	wp_send_json_success(['message' => 'Форма успішно відправлена', 'data' => $form_data]);
}