<?php 
ob_start();
if (!function_exists('add_action')) {
	$path='./';
	for ($x=1; $x<6; $x++) {
		$path .= '../';
		if (@file_exists($path . 'wp-config.php')) {
		    require_once($path . "wp-config.php");
			break;
		}
	}
}
ob_end_clean();

if ($Knews_plugin) {

	if (! $Knews_plugin->initialized) $Knews_plugin->init();

	$id_newsletter = intval($Knews_plugin->get_safe('id'));
	$email = $Knews_plugin->get_safe('e');
	$user_id=0;
	
	if ($email != '') {
		$query = "SELECT * FROM ".KNEWS_USERS." WHERE email='" . $email . "'";
		$results = $wpdb->get_results( $query );

		if ($results) {
			$user_id=$results[0]->id;
			$mysqldate = $Knews_plugin->get_mysql_date();
			
			$query = "INSERT INTO " . KNEWS_STATS . " (what, user_id, submit_id, date) VALUES (2, " . $user_id . ", " . $id_newsletter . ", '" . $mysqldate . "')";
			$result=$wpdb->query( $query );

		}
	}
	
	

	require( KNEWS_DIR . "/includes/knews_compose_email.php");
	$theHtml = str_replace('%unsubscribe_href%', '#', $theHtml);
	$theHtml = str_replace('%cant_read_href%', '#' , $theHtml);
	
	if ($Knews_plugin->get_safe('preview',0)!=1) $theHtml = extract_code('<!--cant_read_block_start-->','<!--cant_read_block_end-->',$theHtml,true);
?>
<?php echo $theHtml; ?>
<?php
} else {
	echo 'Knews is not active';
}
?>