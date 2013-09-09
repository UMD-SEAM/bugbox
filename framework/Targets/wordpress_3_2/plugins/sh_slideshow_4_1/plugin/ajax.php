<?php
	include_once('../../../wp-config.php');
	include_once('../../../wp-load.php');
	include_once('../../../wp-includes/wp-db.php');
	global $wpdb;
	$result = $wpdb->query('delete from sh_slides where id = '.$_POST['id']);
	if($result):
		$return['msg'] = 'Success';
	else:
		$return['msg'] = 'Database Error.';
	endif;
	echo json_encode($return);
?>