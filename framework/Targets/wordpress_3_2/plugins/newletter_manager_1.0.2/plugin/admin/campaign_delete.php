<?php
global $wpdb;
$_POST = stripslashes_deep($_POST);
$_GET = stripslashes_deep($_GET);
$xyz_em_campId = intval($_GET['id']);
$xyz_em_pageno = intval($_GET['pageno']);

if($xyz_em_campId=="" || !is_numeric($xyz_em_campId)){
	header("Location:".admin_url('admin.php?page=newsletter-manager-emailcampaigns'));
	exit();

}
$campCount = $wpdb->get_results( 'SELECT * FROM xyz_em_email_campaign WHERE id="'.$xyz_em_campId.'" ' ) ;

if(count($campCount)==0){
	header("Location:".admin_url('admin.php?page=newsletter-manager-emailcampaigns'));
	exit();
}else{
	
	$attachDetails = $wpdb->get_results( 'SELECT * FROM xyz_em_attachment WHERE campaigns_id="'.$xyz_em_campId.'" ' ) ;
	
	if($attachDetails){
		foreach ($attachDetails as $details){

			$existingAttachmentName =  $details->id."_".$details->name;
			$dir = realpath(dirname(__FILE__) . '/../../../')."/uploads/xyz_em/attachments/";
			unlink ($dir.$existingAttachmentName);

		}
	}
	
	$wpdb->query( 'DELETE FROM  xyz_em_attachment  WHERE campaigns_id="'.$xyz_em_campId.'" ' ) ;
	
	$wpdb->query( 'DELETE FROM  xyz_em_email_campaign  WHERE id="'.$xyz_em_campId.'" ' ) ;
	
	
	header("Location:".admin_url('admin.php?page=newsletter-manager-emailcampaigns&campmsg=3&pagenum='.$xyz_em_pageno));
	exit();
}
?>