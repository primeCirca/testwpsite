<?php
	if (!isset($_GET['token']) || !isset($_GET['id'])) return;
	
	$id = $_GET["id"];
    $user_token = $_GET['token'];
	
	global $wpdb;
	$sql = "SELECT * FROM wp_usermeta where meta_key = 'wpom_user_token' AND meta_value = '" . $user_token . "'";
	$results = $wpdb->get_results($sql, ARRAY_A);
	if (count($results) <= 0) {
		echo 'Invalid user!!';
		exit;
	}
	
	$user_id = $results[0]['user_id'];
	$user = new WP_User( $user_id );
	
	$download_times = json_decode($user->wpom_download_times, true);
	$number_download = $download_times[strval($id)];
	if (!isset($number_download) || trim($number_download)==='') {
		$number_download = 1;
		$download_times[strval($id)] = $number_download;
	}
	else {
		$number_download++;
		$download_times[strval($id)] = $number_download;
	}

	$limitation = 5;
	$sql = 'select * from wpom_ota_files where id = ' . $id;
	$wpom_otas = $wpdb->get_results($sql, ARRAY_A);	
	if (count($wpom_otas) > 0) {
		$wpom_ota =  $wpom_otas[0];
		$limitation = $wpom_ota['max_download_times'];
	}
	
	if ($number_download > $limitation) {
		echo 'Limitation';
		exit;
	}
	
	update_user_meta($user_id, 'wpom_download_times', json_encode($download_times));
	$ota_upload_dir = dirname(__FILE__) . '/uploads/';
	
	$ipa_location = $ota_upload_dir . $id . '.ipa';
    header('HTTP/1.0 200 OK', true, 200);
    header('Content-Transfer-Encoding: Binary');
	header('Content-type: application/octet-stream');
	header('Content-disposition: attachment; filename=' . $id . '.ipa');
	header('Content-Length: ' . filesize($ipa_location));
	ob_clean();
	flush();
	readfile($ipa_location);
    exit;
?> 