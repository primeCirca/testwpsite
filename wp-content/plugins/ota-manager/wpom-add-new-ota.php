<?php
if (isset($_POST["action"])) {
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
	
	$wpdb->insert('wpom_ota_files', $wpom);
	$last_id = $wpdb->insert_id;
	
	//$upload_dir =  wp_upload_dir();
	//$ota_upload_dir = $upload_dir['basedir'] . '/ota_uploads/';
	$ota_upload_dir = dirname(__FILE__) . '/uploads/';
	
	if (isset($_FILES["ipa_file"])) 
	{
		$ipa_file = $_FILES["ipa_file"];
		if ($ipa_file["error"] > 0)
		{
			echo "An error occured on ipa file <br />";
		}
		else
		{
			$extension = end(explode(".", $ipa_file["name"]));
			move_uploaded_file($ipa_file["tmp_name"], $ota_upload_dir . $last_id . '.' . $extension);
		}
	}
	
	echo "<script> location.href='admin.php?page=ota-manager';</script>";
}

?>


<div class="wrap">
	<div id="icon-edit" class="icon32 icon32-posts-post"><br></div>
	<h2>Add New OTA</h2>
	<form method="post" enctype="multipart/form-data">
		<input type="hidden" name="action" value="addNew" />
		<div id="poststuff" class="metabox-holder has-right-sidebar">
			<div id="side-info-column" class="inner-sidebar">
				<div class="postbox " id="upload">
					<div title="Click to toggle" class="handlediv"><br></div><h3 class="hndle"><span>Upload</span></h3>
					<div class="inside">
						<label>
							Select a *.ipa:
							<input type="file" name="ipa_file" id="ipa_file">
						</label>
						<div id="progress">A
						    <div class="bar" style="width: 0%;"></div>B
						</div>

					</div>
				</div>
				
				<div class="postbox " id="settings">
					<div title="Click to toggle" class="handlediv"><br></div><h3 class="hndle"><span>Settings</span></h3>
					<div class="inside">
						<label>
							Download limitation: <br />
							<input type="text" name="max_download_times" value="5">
							time(s)
						</label>
					</div>
				</div>

				<div class="postbox " id="action">
					<div title="Click to toggle" class="handlediv"><br></div><h3 class="hndle"><span>Actions</span></h3>
					<div class="inside">
						<input type="submit" value="Create Package" id="publish" class="button-primary button button-large" name="publish">
					</div>
				</div>
			</div>
			
			<div id="post-body">
				<div id="post-body-content">
					<div id="titlediv">
						<div id="titlewrap">
							<input type="text" name="title" size="20" value="" id="title" autocomplete="on" placeholder="Title" style="font-size:1.3em">
						</div>
					</div>
					
					<div id="titlediv">
						<div id="titlewrap">
							<input type="text" name="bundle_id" size="30" value="" id="title" autocomplete="on" placeholder="Bundle Identifier" style="font-size:1.3em">
						</div>
					</div>
					
					<div id="titlediv">
						<div id="titlewrap">
							<input type="text" name="bundle_version" size="30" value="" id="title" autocomplete="on" placeholder="Bundle Version" style="font-size:1.3em">
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
									$role_label = $value['name'];
								?>
								   <li><label><input type="checkbox" name="allowed_roles[]" value="<?php echo $role_name;?>">  <?php echo $role_label;?></label></li>
								<?php } ?>
							</ul>
						</div>
					</div>
				</div><!-- /post-body-content -->
			</div><!-- /post-body -->
			
			<br class="clear">
		</div><!-- /poststuff -->
	</form>
</div>
<style>
.bar {
    height: 18px;
    background: green;
}
</style>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="<?php echo plugin_dir_url( __FILE__ ); ?>/js/vendor/jquery.ui.widget.js"></script>
<script src="<?php echo plugin_dir_url( __FILE__ ); ?>/js/jquery.iframe-transport.js"></script>
<script src="<?php echo plugin_dir_url( __FILE__ ); ?>/js/jquery.fileupload.js"></script>
<script>
$(function () {
    $('#ipa_file').fileupload({
        dataType: 'json',
        add: function (e, data) {
            data.context = $('<button/>').text('Upload')
                .appendTo(document.body)
                .click(function () {
                    data.context = $('<p/>').text('Uploading...').replaceAll($(this));
                    data.submit();
                });
        },
        done: function (e, data) {
            $.each(data.result.files, function (index, file) {
                $('<p/>').text(file.name).appendTo(document.body);
            });
            data.context.text('Upload finished.');
        },
        progressall: function (e, data) {
        	var progress = parseInt(data.loaded / data.total * 100, 10);
 	       	$('#progress .bar').css(
    	        'width',
        	    progress + '%'
        	);
    	}
    });
});
</script>