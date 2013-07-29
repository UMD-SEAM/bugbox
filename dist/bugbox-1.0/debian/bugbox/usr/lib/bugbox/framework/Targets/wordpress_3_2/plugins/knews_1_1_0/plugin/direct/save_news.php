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

	$Knews_plugin->security_for_direct_pages();

	if (! $Knews_plugin->initialized) $Knews_plugin->init();

	require_once( KNEWS_DIR . '/includes/knews_util.php');

	$id=	$Knews_plugin->post_safe('idnews');
	$title=	$Knews_plugin->post_safe('title');
	$code=	$Knews_plugin->post_safe('code');
	
	$date=	$Knews_plugin->get_mysql_date();
	
	//WYSIWYG editor issues
	$code=rgb2hex($code);
	if (!is_utf8($code)) $codeModule=utf8_encode($code);
	$code=$Knews_plugin->htmlentities_corrected($code);
	
	if (strlen($Knews_plugin->post_safe('testslash'))==5) {
	
		$query = "UPDATE " . KNEWS_NEWSLETTERS . " SET html_mailing='" . mysql_real_escape_string($code) . "', modified='" . $date . "', subject='" . mysql_real_escape_string($title) . "' WHERE id=" . $id;
	} else {
		
		$query = "UPDATE " . KNEWS_NEWSLETTERS . " SET html_mailing='" . $code . "', modified='" . $date . "', subject='" . $title . "' WHERE id=" . $id;
	}
	
	if ($wpdb->query($query)) {
		echo 'ok';
	} else {
		echo $wpdb->last_error;
	}
}
?>