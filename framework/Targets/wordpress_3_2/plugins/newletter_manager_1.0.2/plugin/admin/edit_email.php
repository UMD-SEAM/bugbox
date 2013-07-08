<?php
$xyz_em_search='';
global $wpdb;
$_GET = stripslashes_deep($_GET);
if($_POST){

$_POST = stripslashes_deep($_POST);
$_POST = xyz_trim_deep($_POST);

		$xyz_em_emailId = abs(intval($_POST['emailId']));
		$xyz_em_email = trim($_POST['xyz_em_email']);
		$xyz_em_name = trim($_POST['xyz_em_name']);
		$xyz_em_search = trim($_POST['search']);

		
		$xyz_em_pagenum = abs(intval($_POST['pageno']));

		if(is_email($xyz_em_email)){
			$email_count = $wpdb->query( 'SELECT * FROM xyz_em_email_address WHERE id!="'.$xyz_em_emailId.'" AND email="'.$xyz_em_email.'" LIMIT 0,1' ) ;
			// 				echo '<pre>';
			// 				print_r($email_count);
			// 		die;
			if($email_count == 0){

				$nameCount = $wpdb->query( 'SELECT * FROM xyz_em_additional_field_value WHERE ea_id="'.$xyz_em_emailId.'" ' ) ;

				if($nameCount>0){
						
					$wpdb->update('xyz_em_additional_field_value',array('field1'=>$xyz_em_name),array('ea_id'=>$xyz_em_emailId));
						
				}else{
						
					$wpdb->insert('xyz_em_additional_field_value', array('ea_id' => $xyz_em_emailId,'field1' => $xyz_em_name),array('%d','%s'));
				}
						
					if($xyz_em_name=='')
						$wpdb->query( 'delete  FROM xyz_em_additional_field_value WHERE ea_id="'.$xyz_em_emailId.'" ' ) ;
					
					$wpdb->update('xyz_em_email_address',array('email'=>$xyz_em_email),array('id'=>$xyz_em_emailId));
				
					if($xyz_em_search=='')
				header("Location:".admin_url('admin.php?page=newsletter-manager-emailaddresses&emailmsg=1&pagenum='.$xyz_em_pagenum));
					else
				header("Location:".admin_url('admin.php?page=newsletter-manager-searchemails&search='.$xyz_em_search));
				exit();

			}else{
				?>
<div class="system_notice_area_style0" id="system_notice_area">
	Email already exists.&nbsp;&nbsp;&nbsp;<span
		id="system_notice_area_dismiss">Dismiss</span>
</div>

<?php
			}
		}else{
			?>
<div class="system_notice_area_style0" id="system_notice_area">
	Please enter a valid email.&nbsp;&nbsp;&nbsp;<span
		id="system_notice_area_dismiss">Dismiss</span>
</div>

<?php		
		}

	
}


$xyz_em_emailId = abs(intval($_GET['id']));

if($_GET['pageno'] != ""){
$xyz_em_pageno = abs(intval($_GET['pageno']));
}else{
	$xyz_em_pageno= 1;
}

if($_GET['search'] != "")
$xyz_em_search = trim($_GET['search']);
	

if($xyz_em_emailId==0){
	header("Location:".admin_url('admin.php?page=newsletter-manager-emailaddresses'));
	exit();

}
$emailres = $wpdb->get_results( 'SELECT * FROM xyz_em_email_address WHERE id="'.$xyz_em_emailId.'" LIMIT 0,1' ) ;

if(count($emailres)==0){
	header("Location:".admin_url('admin.php?page=newsletter-manager-emailaddresses'));
	exit();
}else{

	$nameres = $wpdb->get_results( 'SELECT * FROM xyz_em_additional_field_value WHERE ea_id="'.$xyz_em_emailId.'" ' ) ;

		$emailDetails = $emailres[0];
		?>
<div>

	<h2>Update Email</h2>
	<form method="post">
		<table class="widefat" style="width:99%;">

			<tr valign="top">
				<td scope="row"><label for="xyz_em_email">Email address</label>
				</td>
				<td><input name="xyz_em_email" type="text" id="xyz_em_email"
					value="<?php if(isset($_POST['xyz_em_email']) ){echo esc_html($_POST['xyz_em_email']);}else{ echo esc_html($emailDetails->email); }?>" />
				</td>
			</tr>
			<tr valign="top">
				<td scope="row"><label for="xyz_em_name">Name</label>
				</td>
				<td><input name="xyz_em_name" type="text" id="xyz_em_name"
					value="<?php
								if(isset($_POST['xyz_em_name']) ){
									echo esc_html($_POST['xyz_em_name']);
								}else{								
									foreach ($nameres as $detailsName){
										echo esc_html($detailsName->field1);
									}
								}
					?>" />
				</td>
			</tr>

			<tr>
				<td scope="row"></td>
				<td>
				<div style="height:50px;"><input style="margin:10px 0 20px 0;" id="submit" class="button-primary bottonWidth" type="submit" value="Update Email" /></div>
				</td>
			</tr>
			<tr>
				<td id="bottomBorderNone" scope="row"colspan="2" ><a
					href='javascript:history.back(-1);'>Go
						back </a>
				</td>
			</tr>
		</table>
		<input type="hidden" name="emailId"
			value="<?php echo $xyz_em_emailId; ?>">
		<input type="hidden" name="pageno"
			value="<?php echo $xyz_em_pageno; ?>">
		<input type="hidden" name="search"
			value="<?php echo ($xyz_em_search); ?>">
	</form>

</div>
<?php 

}

?>