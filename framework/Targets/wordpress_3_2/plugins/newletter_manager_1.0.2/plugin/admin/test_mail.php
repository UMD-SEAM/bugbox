<?php
require_once ABSPATH . WPINC . '/class-phpmailer.php';
$phpmailer = new PHPMailer();
$phpmailer->CharSet=get_option('blog_charset');




global $wpdb;
$_POST = stripslashes_deep($_POST);
$_GET = stripslashes_deep($_GET);

$xyz_em_pageno = absint($_GET['pageno']);


if(isset($_POST['xyz_em_testMailId'])){
	if ($_POST['xyz_em_testMailId']!= ""){
$xyz_em_testEmail = $_POST['xyz_em_testMailId'];
$xyz_em_campId = absint($_POST['campId']);
if($xyz_em_campId=="" || !is_numeric($xyz_em_campId)){
	header("Location:".admin_url('admin.php?page=newsletter-manager-emailcampaigns'));
	exit();
}
$xyz_em_campDetails = $wpdb->get_results( 'SELECT * FROM xyz_em_email_campaign WHERE id="'.$xyz_em_campId.'" ' ) ;
if(count($xyz_em_campDetails)==0){
	header("Location:".admin_url('admin.php?page=newsletter-manager-emailcampaigns'));
	exit();
}else{
	if(is_email($xyz_em_testEmail)){
		$xyz_em_campDetails = $xyz_em_campDetails[0];
		
		$phpmailer->IsMail();
		
		//$xyz_em_attachments = array();
		$xyz_em_attachmentDetails = $wpdb->get_results( 'SELECT * FROM xyz_em_attachment WHERE campaigns_id="'.$xyz_em_campId.'" ' ) ;
		if($xyz_em_attachmentDetails){
		
			$xyz_em_dir = "uploads/xyz_em/attachments/";
			$xyz_em_targetfolder = realpath(dirname(__FILE__) . '/../../../')."/".$xyz_em_dir;
		
			foreach ($xyz_em_attachmentDetails as $xyz_em_attchDetail){
				//$xyz_em_attachments[] = $xyz_em_targetfolder.$xyz_em_attchDetail->id."_".$xyz_em_attchDetail->name;
				$phpmailer->AddAttachment($xyz_em_targetfolder.$xyz_em_attchDetail->id."_".$xyz_em_attchDetail->name);
			}
		}
		
		$xyz_em_senderName = $xyz_em_campDetails->sender_name;
		$xyz_em_senderEmail = $xyz_em_campDetails->sender_email;
		
		$phpmailer->SetFrom($xyz_em_senderEmail,$xyz_em_senderName);
		$phpmailer->AddReplyTo($xyz_em_senderEmail,$xyz_em_senderName);
		
		$phpmailer->Subject = $xyz_em_campDetails->subject;
		
		
		$xyz_em_body 	 = $xyz_em_campDetails->body;
		$type = $xyz_em_campDetails->type;
		
		$xyz_em_body =  str_replace("{field1}","Name",$xyz_em_body);

		$unsubscriptionLink = plugins_url("newsletter-manager/demo_unsubscription.php");
		
			$xyz_em_body = str_replace("{unsubscribe-url}",$unsubscriptionLink,$xyz_em_body);
		if($xyz_em_campDetails->type == 2){
// 			$link =  str_replace("{unsubscribe-url}",$unsubscriptionLink,'<a href="{unsubscribe-url}">Click here</a> to unsubscribe.');
// 			$xyz_em_body = str_replace("{unsubscribe-option}",$link,$xyz_em_body);
			
			$phpmailer->MsgHTML($xyz_em_body);
		
		}elseif($xyz_em_campDetails->type == 1){
		
		
// 			$link =  str_replace("{unsubscribe-url}",$unsubscriptionLink,"Use the following link to unsubscribe {unsubscribe-url}.");
// 			$phpmailer->Body = str_replace("{unsubscribe-option}",$link,$xyz_em_body);
			$phpmailer->Body = $xyz_em_body;
			
		}
		
		$phpmailer->AddAddress($xyz_em_testEmail);
		
		$sent = $phpmailer->Send();
		
		if($sent == FALSE) {
			
			echo  "Mailer Error: " .$phpmailer->ErrorInfo;
		
		} elseif($sent == TRUE) {
			
			header("Location:".admin_url('admin.php?page=newsletter-manager-emailcampaigns&campmsg=4&pagenum='.$xyz_em_pageno));
			exit();
			
		}
		
	}else{
		?>
		<div class="system_notice_area_style0" id="system_notice_area">
	Please enter a valid email.&nbsp;&nbsp;&nbsp;<span id="system_notice_area_dismiss">Dismiss</span>
</div>
		
		
		<?php
		
	}
}

	}else{
		?>
		<div class="system_notice_area_style0" id="system_notice_area">
	Please fill fields.&nbsp;&nbsp;&nbsp;<span id="system_notice_area_dismiss">Dismiss</span>
</div>
		
		
		<?php
		
	}
}

?>

<div>

	<h2>Send Test Mail</h2>
	<form method="post">
		<table class="widefat" style=" width:500px;" >

			<tr valign="top">
				<td scope="row"><label for="xyz_em_testMailId">Enter email address</label>
				</td>
				<td>
					<input type="text" name="xyz_em_testMailId" value="<?php if(isset($_POST['xyz_em_testMailId'])) echo esc_html($_POST['xyz_em_testMailId']); ?>">
				</td>
			</tr>

			<tr>
				<td scope="row"></td>
				<td><br> <input id="button" type="submit" value="Submit" /></td>
			</tr>
			<input type="hidden" name="campId"
				value="<?php echo $_GET['id']; ?>">
		</table>
	</form>

</div>
