<?php
require( dirname( __FILE__ ) . '../../../../wp-load.php' );
require_once ABSPATH . WPINC . '/class-phpmailer.php';
$phpmailer = new PHPMailer();
$phpmailer->CharSet=get_option('blog_charset');

global $wpdb;
$_POST = stripslashes_deep($_POST);
$_GET = stripslashes_deep($_GET);
$xyz_em_emailId = absint($_GET['eId']);
$xyz_em_listId = absint($_GET['lId']);
$xyz_em_both = $_GET['both'];
$xyz_em_campId = absint($_GET['campId']);

$xyz_em_emailDetails = $wpdb->get_results( 'SELECT email FROM xyz_em_email_address WHERE id="'.$xyz_em_emailId.'" ') ;
$xyz_em_emailDetails = $xyz_em_emailDetails[0];

$combine = $xyz_em_emailId.$xyz_em_listId.$xyz_em_emailDetails->email;
$combineValue = md5($combine);
$time = time();



$xyz_em_campDetails = $wpdb->get_results( 'SELECT unsubscription_link FROM xyz_em_email_campaign WHERE id="'.$xyz_em_campId.'" LIMIT 0,1') ;
$xyz_em_campDetails = $xyz_em_campDetails[0];

if($combineValue == $xyz_em_both){
	
	$xyz_em_unsubscriptionFlag = 0;
	$xyz_em_status = $wpdb->get_results('SELECT status FROM xyz_em_address_list_mapping WHERE ea_id="'.$xyz_em_emailId.'" AND el_id="'.$xyz_em_listId.'"');
	$xyz_em_status = $xyz_em_status[0];
	if($xyz_em_status->status == 1){
	
		$xyz_em_unsubscriptionFlag = 1;
	
	}
	
	$wpdb->update('xyz_em_address_list_mapping',array('status'=>0,'last_update_time'=>$time),array('ea_id'=>$xyz_em_emailId,'el_id'=>$xyz_em_listId));
	
	if(get_option('xyz_em_enableUnsubNotification') == "True"){
		$phpmailer->IsMail();
		
		$xyz_em_emailTempalteDetails = $wpdb->get_results( 'SELECT * FROM xyz_em_email_template WHERE id=2') ;
		$xyz_em_emailTempalteDetails = $xyz_em_emailTempalteDetails[0];
		
		$xyz_em_emailTempalteMessage = $xyz_em_emailTempalteDetails->message;
		
		
		$xyz_em_fieldInfoDetails = $wpdb->get_results( 'SELECT default_value FROM xyz_em_additional_field_info WHERE field_name="Name"' ) ;
		$xyz_em_fieldInfoDetails = $xyz_em_fieldInfoDetails[0];
		
		$xyz_em_fieldValueDetails = $wpdb->get_results( 'SELECT field1 FROM xyz_em_additional_field_value WHERE ea_id="'.$xyz_em_emailId.'"' ) ;
		$xyz_em_fieldValueDetails = $xyz_em_fieldValueDetails[0];
		
		if($xyz_em_fieldValueDetails->field1 != ""){
		
			$xyz_em_emailTempalteMessage =  str_replace("{field1}",$xyz_em_fieldValueDetails->field1,$xyz_em_emailTempalteMessage);
		
		}else{
			$xyz_em_emailTempalteMessage =  str_replace("{field1}",$xyz_em_fieldInfoDetails->default_value,$xyz_em_emailTempalteMessage);
		}
		
		
		if($xyz_em_unsubscriptionFlag == 1){
			
			
			$xyz_em_senderName = get_option('xyz_em_dsn');
			$xyz_em_senderEmail = get_option('xyz_em_dse');
			
			$phpmailer->SetFrom($xyz_em_senderEmail,$xyz_em_senderName);
			$phpmailer->AddReplyTo($xyz_em_senderEmail,$xyz_em_senderName);
			
			$phpmailer->Subject = $xyz_em_emailTempalteDetails->subject;
			
			$phpmailer->MsgHTML(nl2br($xyz_em_emailTempalteMessage));
			
			$phpmailer->AddAddress($xyz_em_emailDetails->email);
			
			$sent = $phpmailer->Send();
			
			if($sent == FALSE) {
			
// 				echo  "Mailer Error: " .$phpmailer->ErrorInfo;
			
			} elseif($sent == TRUE) {}			
			
		}	
		
	}
	
	$unsub_url=$xyz_em_campDetails->unsubscription_link;
	if(strpos($unsub_url,'?') > 0)
		$unsub_url=$unsub_url."&result=success";
	else
		$unsub_url=$unsub_url."?result=success";
	
	if($xyz_em_unsubscriptionFlag == 1)
	{
		$unsub_url=$unsub_url."&confirm=true";
	}
	else
	{
		$unsub_url=$unsub_url."&confirm=false";
	}
		
	header("Location:".$unsub_url);
		exit();
	
	
}else{
	
	$unsub_url=$xyz_em_campDetails->unsubscription_link;
if($unsub_url=='')
	$unsub_url=get_option('xyz_em_redirectAfterLink');
	
	if(strpos($unsub_url,'?') > 0)
		header("Location:".$unsub_url."&result=failure");
	else
		header("Location:".$unsub_url."?result=failure");
	exit();
}

