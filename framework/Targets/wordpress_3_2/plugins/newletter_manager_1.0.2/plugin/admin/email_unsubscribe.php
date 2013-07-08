<?php
global $wpdb;
$_POST = stripslashes_deep($_POST);
$_GET = stripslashes_deep($_GET);

$xyz_em_emailId = absint($_GET['id']);
$xyz_em_pageno = absint($_GET['pageno']);
$xyz_em_search = '';
if(isset($_GET['search']))
$xyz_em_search = trim($_GET['search']);
if($xyz_em_emailId=="" || !is_numeric($xyz_em_emailId)){
	header("Location:".admin_url('admin.php?page=newsletter-manager-emailaddresses'));
	exit();

}
$emailCount = $wpdb->query( 'SELECT * FROM xyz_em_email_address WHERE id="'.$xyz_em_emailId.'" ' ) ;

if($emailCount==0){
	header("Location:".admin_url('admin.php?page=newsletter-manager-emailaddresses'));
	exit();
}else{
	$xyz_em_status = 0;
	$time = time();
	
	$wpdb->update('xyz_em_address_list_mapping', array('status'=>$xyz_em_status,'last_update_time'=>$time), array('ea_id'=>$xyz_em_emailId));
	
	//$wpdb->query( 'UPDATE  xyz_em_address_list_mapping SET status="'.$xyz_em_status.'" WHERE ea_id="'.$xyz_em_emailId.'" ' ) ;
	if($xyz_em_search=='')
		header("Location:".admin_url('admin.php?page=newsletter-manager-emailaddresses&emailmsg=2&pagenum='.$xyz_em_pageno));
	else
		header("Location:".admin_url('admin.php?page=newsletter-manager-searchemails&search='.$xyz_em_search));
	
	exit();

}
?>
