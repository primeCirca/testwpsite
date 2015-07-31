<?php
global $wpdb;
$limit = 10;

$cond = '';
$s = isset($_GET['s'])?explode(" ",$_GET['s']):array();
foreach($s as $p){
    $cond[] = "title like '%".mysql_escape_string($p)."%'";
    
}
if(isset($_GET['s'])) $cond = "where ".implode(" or ", $cond);
 
$start = isset($_GET['paged'])?(($_GET['paged']-1)*$limit):0;
$res = $wpdb->get_results("select * from wpom_ota_files $cond order by id desc limit $start, $limit",ARRAY_A);
 
$row = $wpdb->get_row("select count(*) as total from wpom_ota_files $cond",ARRAY_A);

?>
 

<div class="wrap">
    <div class="icon32" id="icon-upload"><br></div>
    
<h2>Manage OTAs <a href="admin.php?page=ota-manager/add-new-ota" class="add-new-h2">Add New</a></h2> 
           
<form method="get" id="posts-filter">
<div class="tablenav">

<div class="alignleft actions">
<select class="select-action" name="wpom-task">
<option selected="selected" value="-1">Bulk Actions</option>
<option value="delete_file">Delete Permanently</option>
</select>

<input type="submit" class="button-secondary action" id="doaction" name="doaction" value="Apply">
 

</div>
<br class="clear">
</div>

<div class="clear"></div>

<table cellspacing="0" class="wp-list-table widefat fixed posts">
    <thead>
    <tr>
    <th style="" class="manage-column column-cb check-column" id="cb" scope="col"><input type="checkbox"></th>
    <th style="" class="manage-column column-title" id="title" scope="col">Title</th>
	<th style="" class="manage-column column-author" id="author" scope="col">Shortcode</th>
	<th style="" class="manage-column column-author" id="author" scope="col">Bundle Id</th>
	<th style="" class="manage-column column-author" id="author" scope="col">Bundle Version</th>
    <th style="" class="manage-column column-parent" id="parent" scope="col">Limitation</th>
    </tr>
    </thead>

    <tfoot>
    <tr>
    <th style="" class="manage-column column-cb check-column" id="cb" scope="col"><input type="checkbox"></th>
    <th style="" class="manage-column column-title" id="title" scope="col">Title</th>
	<th style="" class="manage-column column-author" id="author" scope="col">Shortcode</th>
	<th style="" class="manage-column column-author" id="author" scope="col">Bundle Id</th>
	<th style="" class="manage-column column-author" id="author" scope="col">Bundle Version</th>
    <th style="" class="manage-column column-parent" id="parent" scope="col">Limitation</th>
    </tr>
    </tfoot>

    <tbody class="list:post" id="the-list">
    <?php foreach($res as $media) { ?>
    <tr valign="top" class="alternate author-self status-inherit" id="post-8">
                <th class="check-column" scope="row"><input type="checkbox" value="<?php echo $media['id'] ?>" name="id[]"></th>
                <td class="media column-title">
                    <strong><a title="Edit" href="admin.php?page=ota-manager&wpom-task=edit_file&id=<?php echo $media['id']?>"><?php echo stripslashes($media['title'])?></a></strong><br>
                    <div class="row-actions"><div class="button-group"><a class="button" href="admin.php?page=ota-manager&wpom-task=edit_file&id=<?php echo $media['id']?>">Edit</a><a href="admin.php?page=ota-manager&wpom-task=delete_file&id=<?php echo $media['id']?>" onclick="return showNotice.warn();" class="button submitdelete" style="color: #aa0000;">Delete Permanently</a></div></div>
                </td>
				<td class="author column-author"><input style="text-align:center" type="text" onclick="this.select()" size="20" title="Simply Copy and Paste in post contents" value="[ota_download id=<?php echo $media['id'];?>]" /></td>
				<td class="author column-author"><?php echo $media['bundle_id']; ?></td>
                <td class="author column-author"><?php echo $media['bundle_version']; ?></td>
                <td class="parent column-parent"><?php echo $media['max_download_times']; ?></td>
     
     </tr>
     <?php } ?>
    </tbody>
</table>

<?php
$paged = isset($_GET['paged']) ?$_GET['paged'] :1;

$page_links = paginate_links( array(
    'base' => add_query_arg( 'paged', '%#%' ),
    'format' => '',
    'prev_text' => __('&laquo;'),
    'next_text' => __('&raquo;'),
    'total' => ceil($row['total']/$limit),
    'current' => $paged
));


?>

<div id="ajax-response"></div>

<div class="tablenav">

<?php 
if ( $page_links ) { 
                
    ?>
<div class="tablenav-pages"><?php $page_links_text = sprintf( '<span class="displaying-num">' . __( 'Displaying %s&#8211;%s of %s' ) . '</span>%s',
    number_format_i18n( ( $paged - 1 ) * $limit + 1 ),
    number_format_i18n( min( $paged * $limit, $row['total'] ) ),
    number_format_i18n( $row['total'] ),
    $page_links
); echo $page_links_text; ?></div>
<?php } ?>

<br class="clear">
</div>
   
</form>
<br class="clear">

</div>

 