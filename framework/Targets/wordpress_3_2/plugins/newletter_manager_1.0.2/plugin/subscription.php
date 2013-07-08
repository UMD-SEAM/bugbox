<?php 
require( dirname( __FILE__ ) . '../../../../wp-load.php' );
require_once ABSPATH . WPINC . '/class-phpmailer.php';
$phpmailer = new PHPMailer();
$phpmailer->CharSet=get_option('blog_charset');

global $wpdb;

$_POST = stripslashes_deep($_POST);
$_GET = stripslashes_deep($_GET);
$_REQUEST = stripslashes_deep($_REQUEST);
$_POST = xyz_trim_deep($_POST);
$_GET = xyz_trim_deep($_GET);
$_REQUEST = xyz_trim_deep($_REQUEST);


$xyz_em_email = $_REQUEST['xyz_em_email'];
$xyz_em_name = $_REQUEST['xyz_em_name'];
$xyz_em_emailConfirmation = get_option('xyz_em_emailConfirmation');
$xyz_em_afterSubscription = get_option('xyz_em_afterSubscription');

if(!is_email($xyz_em_email)){

	if(strpos($xyz_em_afterSubscription,'?') > 0)
	{
		$xyz_em_afterSubscription = $xyz_em_afterSubscription."&result=failure";

	}else{
		$xyz_em_afterSubscription = $xyz_em_afterSubscription."?result=failure";
	}
	header("location:".$xyz_em_afterSubscription);
	exit();
}else{

	$phpmailer->IsMail();

	$xyz_em_senderName = get_option('xyz_em_dsn');
	$xyz_em_senderEmail = get_option('xyz_em_dse');

	$phpmailer->SetFrom($xyz_em_senderEmail,$xyz_em_senderName);
	$phpmailer->AddReplyTo($xyz_em_senderEmail,$xyz_em_senderName);

	$time = time();
	$xyz_em_statusFlag=0;
	$emailDetails = $wpdb->get_results( 'SELECT id FROM xyz_em_email_address WHERE email="'.$xyz_em_email.'"') ;
	if(count($emailDetails) == 1){
		$emailDetails = $emailDetails[0];
		$xyz_em_emailLastId=$emailDetails->id;
		$emailExist = $wpdb->get_results( 'SELECT ea.id FROM xyz_em_email_address ea INNER JOIN xyz_em_address_list_mapping lm ON lm.ea_id=ea.id WHERE ea.email="'.$xyz_em_email.'" AND lm.status="1"') ;
		
		if(count($emailExist)>0){
		//	echo "Activation link already sent to your email address.";
		}else{		
		
		//	$xyz_em_emailLastId = $emailDetails->id;
			if(get_option('xyz_em_dss') == "Active")
				$wpdb->update('xyz_em_address_list_mapping', array('last_update_time' => $time,'status' => 1),array('ea_id'=>$xyz_em_emailLastId));
			
			$xyz_em_statusFlag = 1;
			
		}
	}else{
			
			
		$wpdb->insert('xyz_em_email_address', array('email' => $xyz_em_email,'create_time' => $time,'last_update_time' => $time ),array('%s','%d','%d'));
		$xyz_em_emailLastId = $wpdb->insert_id;

		if($xyz_em_name != ""){
			$wpdb->insert('xyz_em_additional_field_value', array('ea_id' => $xyz_em_emailLastId,'field1' => $xyz_em_name),array('%d','%s'));
		}

		if(get_option('xyz_em_dss') == "Pending"){

				$xyz_em_status = -1;
				
		}elseif(get_option('xyz_em_dss') == "Active"){
			$xyz_em_status = 1;
		}

		$wpdb->insert('xyz_em_address_list_mapping', array('ea_id' => $xyz_em_emailLastId,'el_id' => 1, 'create_time' => $time,'last_update_time' => $time,'status' => $xyz_em_status),array('%d','%d','%d','%d','%d'));
		$xyz_em_statusFlag = 1;
	}

	if(get_option('xyz_em_dss') == "Pending"){
		

		$xyz_em_emailTempalteDetails = $wpdb->get_results( 'SELECT * FROM xyz_em_email_template WHERE id=3') ;
		$xyz_em_emailTempalteDetails = $xyz_em_emailTempalteDetails[0];
		$xyz_em_emailTempalteMessage = $xyz_em_emailTempalteDetails->message;

		$xyz_em_fieldInfoDetails = $wpdb->get_results( 'SELECT default_value FROM xyz_em_additional_field_info WHERE field_name="Name"' ) ;
		$xyz_em_fieldInfoDetails = $xyz_em_fieldInfoDetails[0];

		$xyz_em_fieldValueDetails = $wpdb->get_results( 'SELECT field1 FROM xyz_em_additional_field_value WHERE ea_id="'.$xyz_em_emailLastId.'"' ) ;
		$xyz_em_fieldValueDetails = $xyz_em_fieldValueDetails[0];

		if($xyz_em_fieldValueDetails->field1 != ""){

			$xyz_em_emailTempalteMessage =  str_replace("{field1}",$xyz_em_fieldValueDetails->field1,$xyz_em_emailTempalteMessage);

		}else{
			$xyz_em_emailTempalteMessage =  str_replace("{field1}",$xyz_em_fieldInfoDetails->default_value,$xyz_em_emailTempalteMessage);
		}

		$xyz_em_conf_link = '{confirmation_link}';

		$xyz_em_activeDir = "";

		if(strpos($xyz_em_afterSubscription,'?') > 0)
		{
			$xyz_em_activeDir = $xyz_em_afterSubscription."&result=success";

		}else{
			$xyz_em_activeDir = $xyz_em_afterSubscription."?result=success";
		}
// 		if($xyz_em_statusFlag == 1){
// 		}
		$xyz_em_appendUrl = base64_encode($xyz_em_emailConfirmation);
		$listId = 1;
		
		$combine = $xyz_em_emailLastId.$listId.$xyz_em_email;
		$combineValue = md5($combine);
		
		$xyz_em_confLink = plugins_url("newsletter-manager/confirmation.php?eId=".$xyz_em_emailLastId."&lId=".$listId."&both=".$combineValue."&appurl=".$xyz_em_appendUrl);

		$xyz_em_messageToSend = nl2br(str_replace($xyz_em_conf_link,$xyz_em_confLink,$xyz_em_emailTempalteMessage));
		
		if($xyz_em_statusFlag == 1){
			$xyz_em_activeDir = $xyz_em_activeDir."&confirm=true"; // need to confirm

			$phpmailer->Subject = $xyz_em_emailTempalteDetails->subject;
				
			$phpmailer->MsgHTML($xyz_em_messageToSend);

			$phpmailer->AddAddress($xyz_em_email);

			$sent = $phpmailer->Send();

			if($sent == FALSE) {
				echo  "Mailer Error: " .$phpmailer->ErrorInfo;

			} 
// 			die;
// 			elseif($sent == TRUE) {

// 			}
		}
		else {
			
			$xyz_em_activeDir = $xyz_em_activeDir."&confirm=false"; // already confirmed.
		}
				header("location: ".$xyz_em_activeDir);
				exit();


	}else{
		$xyz_em_emailTempalteDetails = $wpdb->get_results( 'SELECT * FROM xyz_em_email_template WHERE id=1') ;
		$xyz_em_emailTempalteDetails = $xyz_em_emailTempalteDetails[0];

		$xyz_em_emailTempalteMessage = $xyz_em_emailTempalteDetails->message;

		$xyz_em_fieldInfoDetails = $wpdb->get_results( 'SELECT default_value FROM xyz_em_additional_field_info WHERE field_name="Name"' ) ;
		$xyz_em_fieldInfoDetails = $xyz_em_fieldInfoDetails[0];

		$xyz_em_fieldValueDetails = $wpdb->get_results( 'SELECT field1 FROM xyz_em_additional_field_value WHERE ea_id="'.$xyz_em_emailLastId.'"' ) ;
		$xyz_em_fieldValueDetails = $xyz_em_fieldValueDetails[0];

		if($xyz_em_fieldValueDetails->field1 != ""){

			$xyz_em_emailTempalteMessage =  str_replace("{field1}",$xyz_em_fieldValueDetails->field1,$xyz_em_emailTempalteMessage);

		}else{
			$xyz_em_emailTempalteMessage =  str_replace("{field1}",$xyz_em_fieldInfoDetails->default_value,$xyz_em_emailTempalteMessage);
		}

		$xyz_em_messageToSend = nl2br($xyz_em_emailTempalteMessage);
		//die("456");

		if($xyz_em_statusFlag == 1 && get_option('xyz_em_enableWelcomeEmail') == "True"){


			$phpmailer->Subject = $xyz_em_emailTempalteDetails->subject;

			$phpmailer->MsgHTML($xyz_em_messageToSend);

			$phpmailer->AddAddress($xyz_em_email);

			$sent = $phpmailer->Send();

			if($sent == FALSE) {

// 				echo  "Mailer Error: " .$phpmailer->ErrorInfo;

			} elseif($sent == TRUE) {
			}

		}

		if(strpos($xyz_em_afterSubscription,'?') > 0)
		{
			header("location:".$xyz_em_afterSubscription."&result=success");
			exit();
		}else{
			header("location:".$xyz_em_afterSubscription."?result=success");
			exit();
		}

	}

}
?>