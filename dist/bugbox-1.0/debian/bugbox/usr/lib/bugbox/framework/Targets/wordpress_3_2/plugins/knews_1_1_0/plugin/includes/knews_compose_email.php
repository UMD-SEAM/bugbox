<?php

if ($Knews_plugin) {
	if (! $Knews_plugin->initialized) $Knews_plugin->init();

	require_once( KNEWS_DIR . '/includes/knews_util.php');

	global $wpdb;
		
	$query = "SELECT * FROM ".KNEWS_NEWSLETTERS." WHERE id=" . $id_newsletter;
	$results = $wpdb->get_results( $query );

	$theSubject = $results[0]->subject;
	$theHtml = $results[0]->html_head . '<body>' . $results[0]->html_mailing . '</body></html>';

	//Remove some shit from WYSIWYG editor
	$theHtml = str_replace( $results[0]->html_container, '', $theHtml);
	$theHtml = str_replace( '<span class="handler"></span>', '', $theHtml);
	$theHtml = str_replace( "\r\n\r\n", "\r\n", $theHtml);
	$theHtml = preg_replace('/(?:(?:\r\n|\r|\n)\s*){2}/s', "\n\n", $theHtml);
	
	$used_tokens = array();
}
?>