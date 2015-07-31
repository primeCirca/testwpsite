<?php
    /*
    Plugin Name: OTA Manager
    Plugin URI: 
    Description: Plugin to manage OTA for iOS application
    Author: Tuan Phung
    Version: 1.0
    Author URI: 
    */
?>
<?php
	register_activation_hook( __FILE__, 'wpom_install');
	
     add_action('wp_login', 'wpom_generate_token', 10, 2);
	add_action( 'admin_menu', 'init_ota_menu' );
	add_action('init','wpom_process');
	add_shortcode('ota_download','wpom_generate_ota_link');
	
	function wpom_install(){
		global $wpdb;
	 
		$sql = "CREATE TABLE IF NOT EXISTS `wpom_ota_files` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `title` varchar(255) NOT NULL,
				  `bundle_id` text NOT NULL,
				  `bundle_version` text NOT NULL,
				  `allowed_roles` text NOT NULL,
				  `max_download_times` int(11) NOT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=MyISAM  DEFAULT CHARSET=utf8";
		  
		$wpdb->query($sql);
		
		update_option('wpom_access_level', 'administrator');
		
        /*
		$users = get_users();
		foreach ($users as $user) {
			update_user_meta($user->id, 'wpom_user_token', randString());
		}
		*/
        
		wpom_create_dir();		  
	}

	function randString() {
		$length = 40;
		$characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ123456789';
		
		$randString = '';
		for ($i = 0; $i < $length; $i++) {
			$index = rand() % $length;
			$randString .= substr($characters, $index, 1);
		}
		
		return $randString;
	}
	
	function wpom_create_dir() {
		// Install files and folders for uploading files and prevent hotlinking
		$upload_dir =  wp_upload_dir();

		$files = array(
			array(
				'base' 		=> dirname(__FILE__) . '/uploads/',
				'file' 		=> '.htaccess',
				'content' 	=> 'deny from all'
			)
		);
		foreach ( $files as $file ) {
			if ( wp_mkdir_p( $file['base'] ) && ! file_exists( trailingslashit( $file['base'] ) . $file['file'] ) ) {
				if ( $file_handle = @fopen( trailingslashit( $file['base'] ) . $file['file'], 'w' ) ) {
					fwrite( $file_handle, $file['content'] );
					fclose( $file_handle );
				}
			}
		}
	}
	
	function init_ota_menu() {
		add_menu_page("OTA Manager","OTA Manager",get_option('wpom_access_level'),'ota-manager','wpom_manage_ota','');
		$access = get_option('wpom_access_level')?get_option('wpom_access_level'):'administrator';
		add_submenu_page( 'ota-manager', 'OTA Manager', 'Manage OTAs', $access, 'ota-manager', 'wpom_manage_ota');    
		add_submenu_page( 'ota-manager', 'Add OTA File &lsaquo; OTA Manager', 'Add New', $access, 'ota-manager/add-new-ota', 'wpom_add_new_ota');    
	}
	
	function wpom_manage_ota() {
		if (isset($_GET['wpom-task'])) {
			$task = $_GET['wpom-task'];
			$id = $_GET['id'];
			if ($task == 'edit_file') {
				include('wpom-edit-ota.php');
				return;
			}
		}
		include('wpom-list-otas.php');
	}
	
	function wpom_add_new_ota() {
		include('wpom-add-new-ota.php');
	}
	
     function wpom_generate_token($user_login, $user) {
         $token = randString();
         update_user_meta($user->id, 'wpom_user_token', $token);
     }
     
	function wpom_generate_ota_link($atts) {
		$user = wp_get_current_user();
		if (!is_user_logged_in()) {
			return '<i>**Please log in to see install link**</i>';
		}
		
		$user_token = $user->wpom_user_token;
		
		if (!isset($user_token) || trim($user_token)==='') {
			$user_token = randString();
			update_user_meta($user->id, 'wpom_user_token', $token);
		}
		 
		$id = $atts["id"];
		$apple_prefix = 'itms-services://?action=download-manifest&url=';
		$plist_url = get_site_url() . '/wp-content/plugins/ota-manager/wpom-get-manifest.php%3Fcode%3D' . $id . '_' . $user_token;
		$download_url = $apple_prefix . $plist_url;
		
		global $wpdb;
		$sql = 'select * from wpom_ota_files where id = ' . $id;
		
		$results = $wpdb->get_results($sql, ARRAY_A);
		if (count($results) > 0) {
			$info = $results[0];
			
			$hasPermission = false;
			$allowed_roles = json_decode($info['allowed_roles']);
			foreach ($user->roles as $role) {
				if (in_array($role, $allowed_roles)) {
					$hasPermission = true;
				}
			}
		
			if ($hasPermission) {
				return '<a href="' . $download_url . '"> Install ' . $info['title'] . ' - Version: ' . $info['bundle_version'] . '</a>';
			}
			else {
				return '**You don\'t have permission to see install link**';
			}
		}
	}
	
	function wpom_process() {
		if(isset($_GET['wpom'])) {
			if($_GET['wpom']=='download')
			include('download.php');
		}
		
		if (isset($_GET['wpom-task'])) {
			$task = $_GET['wpom-task'];
			$id = $_GET['id'];
			if ($task == 'delete_file') {
				wpom_delete_file();
			}
			else if ($task == '-1') {
				echo "<script> location.href='admin.php?page=ota-manager';</script>";
			}
		}
	}
	
	function wpom_delete_file(){
		global $wpdb;
		if(is_array($_GET['id'])){
			foreach($_GET['id'] as $id){
				$qry[] = "id='".(int)$id."'";
			}
			$cond = implode(" or ", $qry);
		} else
		$cond = "id='".(int)$_GET['id']."'";
		$wpdb->query("delete from wpom_ota_files where ". $cond);
		echo "<script> location.href='admin.php?page=ota-manager';</script>";
		die();
	}
?>