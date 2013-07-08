<?php
global $wpdb;
$_POST = stripslashes_deep($_POST);
$_GET = stripslashes_deep($_GET);
$xyz_em_attachId = intval($_GET['id']);
$xyz_em_campId = intval($_GET['campId']);
if($xyz_em_attachId=="" || !is_numeric($xyz_em_attachId)){
	header("Location:".admin_url('admin.php?page=newsletter-manager-emailcampaigns'));
	exit();

}
$attachDetails = $wpdb->get_results( 'SELECT * FROM xyz_em_attachment WHERE id="'.$xyz_em_attachId.'" ' ) ;

if(count($attachDetails)==0){
	header("Location:".admin_url('admin.php?page=newsletter-manager-emailcampaigns'));
	exit();
}else{
	$attachDetails = $attachDetails[0];
	$existingAttachmentName =  $xyz_em_attachId."_".$attachDetails->name;
	$dir = realpath(dirname(__FILE__) . '/../../../')."/uploads/xyz_em/attachments/";
	unlink ($dir.$existingAttachmentName);
	
	$wpdb->query( 'DELETE FROM  xyz_em_attachment  WHERE id="'.$xyz_em_attachId.'" ' ) ;
	header("Location:".admin_url('admin.php?page=newsletter-manager-emailcampaigns&action=edit_campaign&id='.$xyz_em_campId));
	exit();
}
?>
