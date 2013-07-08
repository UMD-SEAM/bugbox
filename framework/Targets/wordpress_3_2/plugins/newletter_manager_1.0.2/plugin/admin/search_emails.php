<?php
$_REQUEST = stripslashes_deep($_REQUEST);
$email_res=array();
$email='';

if(isset($_REQUEST['search'])){
	
	$_REQUEST=xyz_trim_deep($_REQUEST);
	
	global $wpdb;

	$email = strip_tags($_REQUEST['search']);

	if($email!='')	
		$email_res = $wpdb->get_results( 'SELECT * FROM xyz_em_email_address ea  INNER JOIN xyz_em_address_list_mapping em
ON ea.id=em.ea_id  WHERE email LIKE "%'.$email.'%"  ORDER BY email  LIMIT 0,'.get_option('xyz_em_limit') ) ;

	

}
?>

<div>

	<h2>Search Emails</h2>
	<form method="post">
		<table class="widefat" style="width: 98%;">

			<tr valign="top" class="alternate">
				<td scope="row"><label for="xyz_em_emails">Enter search term</label>
				</td>
				
				<td><input type="text" name="search" value="<?php echo ($email); ?>">
				</td>
			</tr>

			<tr>
				<td scope="row"></td>
				<td>
					<div style="height: 50px;">
						<input style="margin: 10px 0 20px 0;" id="submit"
							class="button-primary bottonWidth" type="submit"
							value=" Search Emails " />
					</div>
				</td>
			</tr>

		</table>
	</form>

</div>
<?php 

if(isset($_REQUEST['search']))
{
	if (count($email_res)>0) {
?>
<p></p>
<div>Best matched results (maximum <?php echo get_option('xyz_em_limit'); ?>) are shown below.</div>
<table class="widefat" style="width:98%;">
<thead>
<tr>
<th scope="col" class="manage-column column-name" style="">Email</th>
<th scope="col" class="manage-column column-name" style="">Name</th>
<th scope="col"  colspan="3" class="manage-column column-name" style="text-align:center;">Action</th>

</tr>
</thead>
<tbody>

<?php 

$count = 1;
//$email=urlencode($email);
		foreach ($email_res as $k => $entry)
		{

			$class = ( $count % 2 == 0 ) ? ' class="alternate"' : '';
			
?>
            <tr<?php echo $class; ?>>
                <td>
                <!-- <input type="checkbox" value="<?php echo $entry->id;?>" name="chk" id="chk<?php echo $entry->id;?>"> &nbsp; -->
                <?php 

					if($entry->status == 0){?>
					<font color=#DD2929><?php echo $entry->email;?> </font>
					<?php }
					if($entry->status == 1){
						echo $entry->email;
					}
					if($entry->status == -1){
					?>
						<font color=#D7DE10><?php echo $entry->email;?> </font>
					<?php 
					}
					?>
                
                
                
                </td>
                 <td >
                 <?php 
                 
                 echo $wpdb->get_var( 'SELECT field1 FROM xyz_em_additional_field_value WHERE ea_id="'.$entry->id.'" ' ) ;
                 
                 ?>
                 </td>
                 <td id="tdCenter">
                 <?php 
			if($entry->status != -1){			
			?>
                 	<a href='<?php echo admin_url('admin.php?page=newsletter-manager-emailaddresses&action=edit_email&id='.$entry->id.'&search='.$email); ?>'><img id="img" title="Edit Email" src="<?php echo plugins_url('newsletter-manager/images/edit.png')?>"></a>
                 <?php 
			}			
                 ?>
                 </td>
                 <td id="tdCenter">
			
			<?php 
			if(($entry->status != 0) && ($entry->status != -1)){			
			?>
			<a
				href="<?php echo admin_url('admin.php?page=newsletter-manager-emailaddresses&action=email_unsubscribe&id='.$entry->id.'&search='.$email); ?>"
				onclick="javascript: return confirm('Please click \'OK\' to confirm ');"><img id="img" title="Unsubscribe Email" src="<?php echo plugins_url('newsletter-manager/images/unsubscribe.png')?>">
			</a>
			<?php 
			}elseif($entry->status == 0){
			?>
			<a
				href="<?php echo admin_url('admin.php?page=newsletter-manager-emailaddresses&action=email_activate&id='.$entry->id.'&search='.$email); ?>"
				onclick="javascript: return confirm('Please click \'OK\' to confirm ');"><img id="img" title="Activate Email" src="<?php echo plugins_url('newsletter-manager/images/active.png')?>">
			</a>
			<?php 
			}
			?>
			</td>
			<td id="tdCenter" ><a
				href='<?php echo admin_url('admin.php?page=newsletter-manager-emailaddresses&action=email_delete&id='.$entry->id.'&search='.$email); ?>'
				onclick="javascript: return confirm('Please click \'OK\' to confirm ');"><img id="img" title="Delete Email" src="<?php echo plugins_url('newsletter-manager/images/delete.png')?>">
			</a></td>
            </tr>
            
 
            <?php
            
                $count++;


		}
?>
</tbody>
</table>
<?php 		
	}else{

		?>
<div class="system_notice_area_style0" id="system_notice_area">
	No emails found. &nbsp;&nbsp;&nbsp;<span
		id="system_notice_area_dismiss">Dismiss</span>
</div>

<?php


	}
}
?>