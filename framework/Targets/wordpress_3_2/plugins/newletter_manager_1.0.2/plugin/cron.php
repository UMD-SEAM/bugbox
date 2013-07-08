<?php
require( dirname( __FILE__ ) . '../../../../wp-load.php' );
require_once ABSPATH . WPINC . '/class-phpmailer.php';

// update_option('xyz_em_hourly_reset_time',$currentHour);
// update_option('xyz_em_hourly_email_sent_count',0);die;

global $wpdb;

$currentDateTime = time();

$xyz_em_campDetails = $wpdb->get_results( 'SELECT * FROM xyz_em_email_campaign WHERE status="1" AND start_time<="'.$currentDateTime.'"' ) ;
if(count($xyz_em_campDetails)==0){
	echo "No active campaigns scheduled to execute now";
}
else
{	
			update_option('xyz_em_cronStartTime',time());

		foreach( $xyz_em_campDetails as $entry ) {
			
			$counter=1;
			$xyz_em_campId = $entry->id;

			$xyz_em_campDetails = $wpdb->get_results( 'SELECT * FROM xyz_em_email_campaign WHERE id="'.$xyz_em_campId.'"') ;
				
			$xyz_em_campDetails = $xyz_em_campDetails[0];
			$xyz_em_fieldInfoDetails = $wpdb->get_results( 'SELECT default_value FROM xyz_em_additional_field_info WHERE field_name="Name"' ) ;
			$xyz_em_fieldInfoDetails = $xyz_em_fieldInfoDetails[0];

			
			$time = time();
			
			
			$day = date('d', $time);
			$month = date('m', $time);
			$year = date('Y', $time);
			$hour = date('H', $time);
			$currentHour = mktime($hour,0,0,$month,$day,$year);
			
			if(($currentHour - get_option('xyz_em_hourly_reset_time'))>=3600){
			
				update_option('xyz_em_hourly_reset_time',$currentHour);
				update_option('xyz_em_hourly_email_sent_count',0);
			
			}
			
			
				
			$xyz_em_mappingDetails = $wpdb->get_results( 'SELECT ea_id,id FROM xyz_em_address_list_mapping WHERE el_id="'.$xyz_em_campDetails->list_id.'" AND status="1"
					AND id>"'.$xyz_em_campDetails->last_send_mapping_id.'" ORDER BY id LIMIT 0,'.$xyz_em_campDetails->batch_size ) ;

			$xyz_em_sendMailFlag = 0;
			
			
			echo "<br/><br/>Campaign : ".$xyz_em_campDetails->name."<br/><br/>";
			
			
			$noEmailFlag = 0;
			
			if(count($xyz_em_mappingDetails)>0){
					
				foreach ($xyz_em_mappingDetails as $mappingdetail){
						
$phpmailer = new PHPMailer();
$phpmailer->CharSet=get_option('blog_charset');

					$xyz_em_emailDetails = $wpdb->get_results( 'SELECT email FROM xyz_em_email_address WHERE id="'.$mappingdetail->ea_id.'" ') ;
					$xyz_em_emailDetails = $xyz_em_emailDetails[0];
						
						
					$xyz_em_fieldValueDetails = $wpdb->get_results( 'SELECT field1 FROM xyz_em_additional_field_value WHERE ea_id="'.$mappingdetail->ea_id.'"' ) ;
					$xyz_em_fieldValueDetails = $xyz_em_fieldValueDetails[0];
						
						
					$phpmailer->IsMail();
						
					$xyz_em_attachmentDetails = $wpdb->get_results( 'SELECT * FROM xyz_em_attachment WHERE campaigns_id="'.$xyz_em_campId.'" ' ) ;
					if($xyz_em_attachmentDetails){
							
						$xyz_em_dir = "uploads/xyz_em/attachments/";
						$xyz_em_targetfolder = realpath(dirname(__FILE__) . '/../../')."/".$xyz_em_dir;
						
						foreach ($xyz_em_attachmentDetails as $xyz_em_attchDetail){
							$phpmailer->AddAttachment($xyz_em_targetfolder.$xyz_em_attchDetail->id."_".$xyz_em_attchDetail->name);
						}
					}
						
					$xyz_em_senderName = $xyz_em_campDetails->sender_name;
					$xyz_em_senderEmail = $xyz_em_campDetails->sender_email;
						
					$phpmailer->SetFrom($xyz_em_senderEmail,$xyz_em_senderName);
					$phpmailer->AddReplyTo($xyz_em_senderEmail,$xyz_em_senderName);
						
					$phpmailer->Subject = $xyz_em_campDetails->subject;
						
					$xyz_em_body 	 = $xyz_em_campDetails->body;
						
					if($xyz_em_fieldValueDetails->field1 != ""){
							
						$xyz_em_body =  str_replace("{field1}",$xyz_em_fieldValueDetails->field1,$xyz_em_body);
							
					}else{
						$xyz_em_body =  str_replace("{field1}",$xyz_em_fieldInfoDetails->default_value,$xyz_em_body);
					}
						
					$type = $xyz_em_campDetails->type;
						
					$combineValues =  $mappingdetail->id.$xyz_em_campDetails->list_id.$xyz_em_emailDetails->email;
					$both = md5($combineValues);
						
					$unsubscriptionLink = plugins_url("newsletter-manager/unsubscription.php?eId=".$mappingdetail->id."&lId=".$xyz_em_campDetails->list_id."&both=".$both."&campId=".$xyz_em_campDetails->id);
						
						$xyz_em_body = str_replace("{unsubscribe-url}",$unsubscriptionLink,$xyz_em_body);
					if($xyz_em_campDetails->type == 2){
							
// 						$link =  str_replace("{unsubscribe-url}",$unsubscriptionLink,'<a href="{unsubscribe-url}">Click here</a> to unsubscribe.');
// 						$xyz_em_body = str_replace("{unsubscribe-option}",$link,$xyz_em_body);
						$phpmailer->MsgHTML($xyz_em_body);
							
					}elseif($xyz_em_campDetails->type == 1){
							
// 						$link =  str_replace("{unsubscribe-url}",$unsubscriptionLink,"Use the following link to unsubscribe {unsubscribe-url}.");
// 						$phpmailer->Body = str_replace("{unsubscribe-option}",$link,$xyz_em_body);
						$phpmailer->Body = $xyz_em_body;
							
					}
						
					$phpmailer->AddAddress($xyz_em_emailDetails->email);
						
					$xyz_em_mappingId = $mappingdetail->id;
						
					if(get_option('xyz_em_hesl') > get_option('xyz_em_hourly_email_sent_count')){
							
						echo $counter++.". ".$xyz_em_emailDetails->email." : ";
						
						$sent = $phpmailer->Send();
						//$sent = TRUE; for testing

						if($sent == FALSE) {
								
							echo  "Mailer Error: " .$phpmailer->ErrorInfo;
								
						} elseif($sent == TRUE) {
								
							echo "Sent.";
							$xyz_em_sendMailFlag=1;
								
								
							$xyz_em_campSentCount = $wpdb->get_results( 'SELECT send_count FROM xyz_em_email_campaign WHERE id="'.$xyz_em_campId.'"') ;
							$xyz_em_campSentCount = $xyz_em_campSentCount[0];
								
							$time = time();
							$wpdb->update('xyz_em_email_campaign',
									array('send_count'=>$xyz_em_campSentCount->send_count+$xyz_em_sendMailFlag,'last_fired_time'=>$time,'last_send_mapping_id'=>$xyz_em_mappingId),
									array('id'=>$xyz_em_campId));
								
							$xyz_em_hourlySentEmailCount = get_option('xyz_em_hourly_email_sent_count');
							$xyz_em_currentSentCount = $xyz_em_hourlySentEmailCount + $xyz_em_sendMailFlag;
							update_option('xyz_em_hourly_email_sent_count',$xyz_em_currentSentCount);
							
								
						}
						echo "<br>";
							
					}else{
						echo "Hourly email sending limit reached.";
						break;
					}
				}
			}else{
				
				$noEmailFlag ++;
				echo "No more email to send.";
					
			}
			
				
		}

	
							update_option('xyz_em_CronEndTime',time());

}
?>