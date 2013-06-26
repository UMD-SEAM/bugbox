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

	$id_news = intval($_GET['idnews']);

	$query = "SELECT * FROM ".KNEWS_NEWSLETTERS." WHERE id=" . $id_news;
	$results_news = $wpdb->get_results( $query );
	if (count($results_news) == 0) {
?>
			<h3><?php _e('Error: This newsletter does not exist','knews');?></h3>
<?php
	} else {

	$head_code=$results_news[0]->html_head;
	echo substr($head_code, 0, strlen($head_code)-7);
	?>	
	<script type="text/javascript" src="../wysiwyg/editor.js?ver=<?php echo KNEWS_VERSION; ?>"></script>
	<link href="../wysiwyg/editor.css?ver=<?php echo KNEWS_VERSION; ?>" rel="stylesheet" type="text/css" />
	</head>
	<body>
		<noscript>
		<h1><?php _e('Warning! You should activate JavaScript to edit newsletters!','knews');?></h1>
		</noscript>
		<div class="wysiwyg_editor">
			<?php echo $results_news[0]->html_mailing; ?>
		</div>
		<div id='modalDiv' style='display:none'></div>
	</body>
	</html>
<?php
	}
}
?>
