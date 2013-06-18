<?php
header('Content-type: text/html; charset=utf-8');
require_once( '../../../wp-config.php' );

$max = 20;
if($_GET['max']) $max = $_GET['max'];

sk_text_domain();
echo sk_feed($max);
?>
