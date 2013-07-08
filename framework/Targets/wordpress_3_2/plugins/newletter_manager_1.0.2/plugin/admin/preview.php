<?php
require( dirname( __FILE__ ) . '../../../../../wp-load.php' );
if ( !current_user_can('manage_options') )
	die;
global $wpdb;
$_POST = stripslashes_deep($_POST);
$_GET = stripslashes_deep($_GET);
$xyz_em_campId = absint($_GET['id']);

$campList = $wpdb->get_results( 'SELECT * FROM xyz_em_email_campaign WHERE id="'.$xyz_em_campId.'"') ;


if($campList){
	$campList = $campList[0];

		$details = $campList;
		$body 	 = $details->body;
		$subject = $details->subject;
		$altBody = $details->alt_body;
		$sendersEmailId = $details->sender_email_id;
		$sendersEmail = $details->sender_email;
		$type = $details->type;
		$senderName = $details->sender_name;
		
		$body =  str_replace("{field1}","Name",$body);
		//$subject =  str_replace("{field1}","Name",$subject);
		if($details->alt_body != ""){
			$altBody =  str_replace("{field1}","Name",$altBody);
		}
		
		$unsubscriptionLink = plugins_url("newsletter-manager/demo_unsubscription.php");
			$body = str_replace("{unsubscribe-url}",$unsubscriptionLink,$body);
			$altBody    =  str_replace("{unsubscribe-url}",$unsubscriptionLink,$altBody);// optional, comment out and test
		
// 		if($details->type == 2){
// 			$linkAltBody =  str_replace("{unsubscribe-url}",$unsubscriptionLink,"Use the following link to unsubscribe.");
// 			$link =  str_replace("{unsubscribe-url}",$unsubscriptionLink,'<a href="{unsubscribe-url}">Click here</a> to unsubscribe.');
// 			$altBody    =  str_replace("{unsubscribe-option}",$linkAltBody,$body);// optional, comment out and test
// 			$body = str_replace("{unsubscribe-option}",$link,$body);
		
// 		}elseif($details->type == 1){
			
			
// 			$link =  str_replace("{unsubscribe-url}",$unsubscriptionLink,"Use the following link to unsubscribe {unsubscribe-url}.");
// 			$body = str_replace("{unsubscribe-option}",$link,$body);
		
// 		}
		
		$code = "";
		
		if($details->type==1){
			
			$code = $code.nl2br(htmlspecialchars($body));
		}
		if($details->type==2){
			
			$code = $code.$body;
		}
		echo $code;
		die;
		
		
	
}

?>