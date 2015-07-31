<?php require( dirname(dirname(dirname(dirname(__FILE__)))) . '/wp-load.php' ); ?>

<?php
	$id = $_GET["id"];
	$action = $_GET["action"];

	$user = new WP_User( $id );
			
	if ($action == 'check') {
		$download_times = json_decode($user->wpom_download_times, true);
		echo var_dump($download_times);
	}
	else if ($action == 'reset') {
		echo 'reseted';
		update_user_meta($id, 'wpom_download_times', 'asdasd');
	}
?> 