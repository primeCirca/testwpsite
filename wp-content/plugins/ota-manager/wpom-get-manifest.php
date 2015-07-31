<?php require( dirname(dirname(dirname(dirname(__FILE__)))) . '/wp-load.php' ); ?>
<?php
	if (isset($_GET['code'])) {
		$array = explode('_', $_GET['code']);
		$id = $array[0];
		$user_token = $array[1];
		
		global $wpdb;
		$sql = 'select * from wpom_ota_files where id = ' . $id;
		$results = $wpdb->get_results($sql, ARRAY_A);
		
		if (count($results) > 0) {
			$info = $results[0];
			
			$template_location = 'template/template.plist';
			
			$input = simplexml_load_file($template_location);
			$xml = new SimpleXMLElement($input->asXML());
			
			$sql = "SELECT * FROM wp_usermeta where meta_key = 'wpom_user_token' AND meta_value = '" . $user_token . "'";
			$wp_usermeta_results = $wpdb->get_results($sql, ARRAY_A);
			if (count($wp_usermeta_results) <= 0) return;
			$user_id = $wp_usermeta_results[0]['user_id'];
	
			//update ipa link
			//http://192.168.11.12:8080/wordpress/?wpom=download&amp;type=ipa&amp;id=4
			$xml->dict->array->dict->array->dict->string[1] = get_site_url() . '/?wpom=download&id=' . $id . '&token=' . $user_token;

            //update bundle id
			$xml->dict->array->dict->dict->string[0] = $info['bundle_id'];
			
			//update bundle version
			$xml->dict->array->dict->dict->string[1] = $info['bundle_version'];
			
			//update title
			$xml->dict->array->dict->dict->string[2] = $info['title'];
			
			$manifest_location = 'template/' . $id . '_' . $user_id . '.plist';
			$xml->asXML($manifest_location);
            
            header('HTTP/1.0 200 OK', true, 200);
            header('Content-Transfer-Encoding: Binary');
			header('Content-disposition: attachment; filename=template.plist');
			header('Content-type: application/x-plist');
			readfile($manifest_location);
			exit;
		}
	}
?> 