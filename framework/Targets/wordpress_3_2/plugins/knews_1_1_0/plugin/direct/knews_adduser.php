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

	function different_locale_for_ajax( $locale ) {
	   return $_POST['lang_locale_user'];
	}
	
	if (KNEWS_MULTILANGUAGE) add_filter('locale', 'different_locale_for_ajax'); 
	
	$Knews_plugin->add_user_self();
}

?>