<?php
if (isset($_POST["action"])) {
	$id = $_GET['id'];
	$title = $_POST['title'];
	$bundle_id = $_POST['bundle_id'];
	$bundle_version = $_POST['bundle_version'];
	$allowed_roles = $_POST['allowed_roles'];
	$max_download_times = $_POST['max_download_times'];
	
	global $wpdb;
	$wpom = array(
		'title' => $title,
		'bundle_id' => $bundle_id,
		'bundle_version' => $bundle_version,
		'allowed_roles' => json_encode($allowed_roles),
		'max_download_times' => $max_download_times
	);
	
	$wpdb->update('wpom_ota_files', $wpom, array ( 'id' => $id ));
	
	if (isset($_FILES["ipa_file"])) 
	{
		//$upload_dir =  wp_upload_dir();
		//$ota_upload_dir = $upload_dir['basedir'] . '/ota_uploads/';
		$ota_upload_dir = dirname(__FILE__) . '/uploads/';
		
		$ipa_file = $_FILES["ipa_file"];
		if ($ipa_file["error"] <= 0) {
			$extension = end(explode(".", $ipa_file["name"]));
			move_uploaded_file($ipa_file["tmp_name"], $ota_upload_dir . $id . '.' . $extension);
		}
	}
	
	echo "<script> location.href='admin.php?page=ota-manager';</script>";
}
?>

<?php 
	$id = $_GET['id'];
	
	global $wpdb;
	$sql = 'select * from wpom_ota_files where id = ' . $id;
	
	$results = $wpdb->get_results($sql, ARRAY_A);
	if (count($results) > 0) {
		$info = $results[0];
		
		//$upload_dir =  wp_upload_dir();
		//$ota_upload_dir = $upload_dir['basedir'] . '/ota_uploads/';
		$ota_upload_dir = dirname(__FILE__) . '/uploads/';
		
		$ipa_location = $ota_upload_dir . $id . '.ipa';
		$ipa_file_size = filesize($ipa_location);
		
		$allowed_roles = json_decode($info['allowed_roles']);
	}
    
    $sql = "SELECT * FROM wp_usermeta where meta_key = 'wpom_download_times' AND meta_value like '%\"" . $id ."\":%'";
    
    $results_user_meta = $wpdb->get_results($sql, ARRAY_A);
    	
?>
<div class="wrap">
	<div id="icon-edit" class="icon32 icon32-posts-post"><br></div>
	<h2>Update OTA</h2>
	<form method="post" enctype="multipart/form-data">
		<input type="hidden" name="action" value="update" />
		<div id="poststuff" class="metabox-holder has-right-sidebar">
			<div id="side-info-column" class="inner-sidebar">
				<div class="postbox " id="current">
					<div title="Click to toggle" class="handlediv"><br></div><h3 class="hndle"><span>Current File</span></h3>
					<div class="inside"> 
						<label>Size: <?php echo (isset($ipa_file_size)) ? number_format($ipa_file_size, 0, '.', ',') . ' KB(s)' : 'N/A' ?></label>
					</div>
				</div>

				
				<div class="postbox " id="upload">
					<div title="Click to toggle" class="handlediv"><br></div><h3 class="hndle"><span>Upload</span></h3>
					<div class="inside"> 
						<label>
							Select another *.ipa:
							<input type="file" name="ipa_file" id="ipa_file">
						</label>
					</div>
				</div>
				
				<div class="postbox " id="settings">
					<div title="Click to toggle" class="handlediv"><br></div><h3 class="hndle"><span>Settings</span></h3>
					<div class="inside">
						<label>
							Download limitation: <br />
							<input type="text" name="max_download_times" value="<?php if (isset($info)) { echo $info['max_download_times']; } ?>">
							time(s)
						</label>
					</div>
				</div>

				<div class="postbox " id="action">
					<div title="Click to toggle" class="handlediv"><br></div><h3 class="hndle"><span>Actions</span></h3>
					<div class="inside">
						<input type="submit" value="Update Package" id="publish" class="button-primary button button-large" name="publish">
					</div>
				</div>
			</div>
			
			<div id="post-body">
				<div id="post-body-content">
					<div id="titlediv">
						<div id="titlewrap">
							<input type="text" name="title" size="30" value="<?php if (isset($info)) { echo $info['title']; } ?>" id="title" autocomplete="off" placeholder="Title" style="font-size:1.3em">
						</div>
					</div>
					
					<div id="titlediv">
						<div id="titlewrap">
							<input type="text" name="bundle_id" size="30" value="<?php if (isset($info)) { echo $info['bundle_id']; } ?>" id="title" autocomplete="off" placeholder="Bundle Identifier" style="font-size:1.3em">
						</div>
					</div>
					
					<div id="titlediv">
						<div id="titlewrap">
							<input type="text" name="bundle_version" size="30" value="<?php if (isset($info)) { echo $info['bundle_version']; } ?>" id="title" autocomplete="off" placeholder="Bundle Version" style="font-size:1.3em">
						</div>
					</div>
					
					<div class="postbox " id="permissions">
						<div title="Click to toggle" class="handlediv"><br></div><h3 class="hndle"><span>Permissions</span></h3>
						<div class="inside" style="font-size:1.3em">
							<ul>
								<?php 
								global $wp_roles;
								$roles = $wp_roles->roles;
								foreach ($roles as $role_name => $value) { 
									$checked = '';
									$role_label = $value['name'];
									if (in_array($role_name, $allowed_roles)) {
										$checked = 'checked';
									}
								?>
								   <li><label><input type="checkbox" name="allowed_roles[]" value="<?php echo $role_name;?>" <?php echo $checked; ?>>  <?php echo $role_label;?></label></li>
								<?php } ?>
							</ul>
						</div>
					</div>
					
					<div class="postbox " id="permissions">
                        <div title="Click to toggle" class="handlediv"><br></div><h3 class="hndle"><span>Counter</span></h3>
                        <div class="inside" style="font-size:1.3em">
                            <table width="50%">
                                <tr>
                                    <th align="left">Username</th>
                                    <th align="left">NO. Download</th>
                                </tr>
<?php                           
                                
                                if($results_user_meta && count($results_user_meta) > 0) {
                                    
                                    foreach ($results_user_meta as $user_meta) {
                                        $user_id = $user_meta["user_id"];  
                                        $meta_value = $user_meta["meta_value"];
                                        $user = new WP_User( $user_id );
                                        $download_times = json_decode($meta_value, true);
                                        $number_download = $download_times[$id];
?>
                                <tr>
                                    <td><?php echo $user->user_login ?></td>
                                    <td><?php echo $number_download ?></td>
                                </tr>
<?php
                                        
                                    }
                                } 
                                
?>
                                
                            </table>
                        </div>
                    </div>
					
				</div><!-- /post-body-content -->
			</div><!-- /post-body -->
			
			
			
			
		</div><!-- /poststuff -->
	</form>
</div>