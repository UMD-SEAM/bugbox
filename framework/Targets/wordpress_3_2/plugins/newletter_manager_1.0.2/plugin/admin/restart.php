<?php
global $wpdb;

$_POST = stripslashes_deep($_POST);
$_GET = stripslashes_deep($_GET);

$xyz_em_campId = absint($_GET['id']);
$xyz_em_pageno = absint($_GET['pageno']);

if($xyz_em_campId=="" || !is_numeric($xyz_em_campId)){
	header("Location:".admin_url('admin.php?page=newsletter-manager-emailcampaigns'));
	exit();

}
$emailCount = $wpdb->query( 'SELECT * FROM xyz_em_email_campaign WHERE id="'.$xyz_em_campId.'" ' ) ;

if($emailCount==0){
	header("Location:".admin_url('admin.php?page=newsletter-manager-emailcampaigns'));
	exit();
}else{
	
	$startTime = time();
	$last_send_mapping_id = 0;
	$send_count = 0;
	$last_fired_time = 0;
	
	$wpdb->update('xyz_em_email_campaign', array('last_send_mapping_id'=>$last_send_mapping_id, 'send_count'=>$send_count, 'start_time'=>$startTime,'last_fired_time'=>$last_fired_time), array('id'=>$xyz_em_campId));
	
	header("Location:".admin_url('admin.php?page=newsletter-manager-emailcampaigns&campmsg=1&pagenum='.$xyz_em_pageno));
	exit();
}
?>
