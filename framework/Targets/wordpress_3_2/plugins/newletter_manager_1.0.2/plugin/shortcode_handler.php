<?php 

add_shortcode('xyz_em_subscription_html_code','display_content');


function display_content(){
	include(dirname( __FILE__ ).'/shortcodes/htmlcode.php');
}

add_shortcode('xyz_em_thanks','display_thanks');


function display_thanks(){
	include(dirname( __FILE__ ).'/shortcodes/thanks.php');
}

add_shortcode('xyz_em_confirm','display_confirm');


function display_confirm(){
	include(dirname( __FILE__ ).'/shortcodes/confirm.php');
}

add_shortcode('xyz_em_unsubscribe','display_unsubscribe');


function display_unsubscribe(){
	include(dirname( __FILE__ ).'/shortcodes/unsubscribe.php');
}

?>