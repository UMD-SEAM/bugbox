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

	if ($Knews_plugin->security_for_direct_pages(false)) {
	
		$filename = $Knews_plugin->get_safe('file');
		
		if ($filename != '') {
			$filename = str_replace('/','*', $filename);
			$filename = str_replace('\\','*', $filename);
			$filename = str_replace('..','*', $filename);
			if ($file = @file_get_contents(KNEWS_DIR . '/tmp/' . $filename)) {

				if (strpos($filename,'.csv') !== false) {
					header('Content-type: text/csv');
					header('Content-disposition: attachment;filename=' . $filename);
				}

				echo $file;
				exit;
			}
		}
	}
}
header('HTTP/1.0 404 Not Found');
echo '<html><head><title>404 Not Found</title></head>';
echo "<body><h1>404 Not Found</h1>";
echo "The page that you have requested could not be found.</body>";
?>
