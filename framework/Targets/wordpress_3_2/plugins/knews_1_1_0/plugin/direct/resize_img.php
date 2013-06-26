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

	$url_img= $Knews_plugin->get_safe('urlimg');
	$width= intval($Knews_plugin->get_safe('width'));
	$height= intval($Knews_plugin->get_safe('height'));

	$wp_dirs = wp_upload_dir();
	$absolute_dir = substr($_SERVER['SCRIPT_FILENAME'], 0, strpos($_SERVER['SCRIPT_FILENAME'], 'wp-content'));

	$wp_dirs['basedir'] = substr($wp_dirs['basedir'], strpos($wp_dirs['basedir'], $absolute_dir));

	//echo '*' . $wp_dirs['baseurl'] . '*<br>';
	//echo '*' . substr($url_img, 0, strlen($wp_dirs['baseurl'])) . '*<br>';
	if (substr($url_img, 0, strlen($wp_dirs['baseurl'])) != $wp_dirs['baseurl']) {
		//echo 'no comencen igual<br>';
		$wp_dirs['baseurl']=substr($url_img, 0, strpos($url_img, 'wp-content'));
		$wp_dirs['basedir']=substr($_SERVER['SCRIPT_FILENAME'], 0, strpos($_SERVER['SCRIPT_FILENAME'], 'wp-content'));
	}
	//echo '*' . $wp_dirs['baseurl'] . '*<br>';
	//echo '*' . $wp_dirs['basedir'] . '*<br>';

	//$url_start = substr($url_img, 0, strpos($url_img, $_SERVER['SERVER_NAME']) + strlen($_SERVER['SERVER_NAME']));

	$pos = strrpos($url_img, "-");
	if ($pos !== false) { 
		$pos2 = strrpos($url_img, ".");
		
		if ($pos2 !== false) { 
			$try_original = substr($url_img, 0, $pos) . substr($url_img, $pos2);
			$try_original2 = substr($try_original, strlen($wp_dirs['baseurl']));

			if (is_file($wp_dirs['basedir'] . $try_original2)) $url_img = $try_original;
		}
	}
	knews_get_url_img($url_img, $width, $height);
}

function knews_get_url_img($img_url, $width, $height, $cut = true) {
	
	global $wp_dirs, $absolute_dir;
	
    if ($img_url != '' && $img_url != 'undefined') {

		// cut the url
		//$url_imatge = substr($img_url, strpos($img_url, 'wp-content'));

		$url_imatge = substr($img_url, strlen($wp_dirs['baseurl']));
		$url=$url_imatge;

		$url_imatge = str_replace('.jpg', '-' . $width . 'x' . $height .'.jpg', $url_imatge);
		$url_imatge = str_replace('.jpeg', '-' . $width . 'x' . $height .'.jpeg', $url_imatge);
		$url_imatge = str_replace('.gif', '-' . $width . 'x' . $height .'.gif', $url_imatge);
		$url_imatge = str_replace('.png', '-' . $width . 'x' . $height .'.png', $url_imatge);

		$url_imatge = str_replace('.JPG', '-' . $width . 'x' . $height .'.JPG', $url_imatge);
		$url_imatge = str_replace('.JPEG', '-' . $width . 'x' . $height .'.JPEG', $url_imatge);
		$url_imatge = str_replace('.GIF', '-' . $width . 'x' . $height .'.GIF', $url_imatge);
		$url_imatge = str_replace('.PNG', '-' . $width . 'x' . $height .'.PNG', $url_imatge);

		if (is_file($wp_dirs['basedir'] . $url_imatge)) {

			$jsondata['result'] = 'ok';
			$jsondata['url'] = $wp_dirs['baseurl'] . $url_imatge;
 			echo json_encode($jsondata);

			return;
	
		} else {
	
			// resize the image
			$thumb = image_resize($wp_dirs['basedir'] . $url, $width, $height, $cut, $width.'x'.$height);
			if (is_string($thumb)) {

				//$thumb = substr($thumb, strpos($thumb, 'wp-content'));
				$thumb = substr($thumb, strlen($wp_dirs['basedir']));
				
				$jsondata['result'] = 'ok';
				$jsondata['url'] = $wp_dirs['baseurl'] . $thumb;
				echo json_encode($jsondata);
				
				return;
	
			} else {
				if (is_file($absolute_dir . $url)) {

					$jsondata['result'] = 'ok';
					$jsondata['url'] = $wp_dirs['baseurl'] . $url;
					echo json_encode($jsondata);
	
					return;
					
				} else {

					$jsondata['result'] = 'error';
					$jsondata['url'] = '';
					$jsondata['message'] = __('Error','knews') . ': ' . __('Check the directory permissions for','knews') . ' ' . $wp_dirs['basedir'] . dirname($url);
					echo json_encode($jsondata);
	
					return;
				}
			}
		}

	} else {

		$jsondata['result'] = 'error';
		$jsondata['url'] = '';
		$jsondata['message'] = __('Error: there is no image selected','knews');
		echo json_encode($jsondata);
	}
}

?>