<?php 
function em_deactivate(){
global $wpdb;

$the_page_unsubscribe = $wpdb->get_results("SELECT * FROM wp_postmeta WHERE meta_key='xyz_em_unsubscribe_page' AND meta_value='1'");
$the_page_unsubscribe = $the_page_unsubscribe[0];
$pageIdUnsubscribe = $the_page_unsubscribe->post_id;

$wpdb->update('wp_posts', array('post_status'=>'draft'), array('id'=>$pageIdUnsubscribe));


$the_page_thanks = $wpdb->get_results("SELECT * FROM wp_postmeta WHERE meta_key='xyz_em_thanks_page' AND meta_value='2'");
$the_page_thanks = $the_page_thanks[0];
$pageIdThanks = $the_page_thanks->post_id;

$wpdb->update('wp_posts', array('post_status'=>'draft'), array('id'=>$pageIdThanks));


$the_page_confirm = $wpdb->get_results("SELECT * FROM wp_postmeta WHERE meta_key='xyz_em_confirm_page' AND meta_value='3'");
$the_page_confirm = $the_page_confirm[0];
$pageIdConfirm = $the_page_confirm->post_id;

$wpdb->update('wp_posts', array('post_status'=>'draft'), array('id'=>$pageIdConfirm));

}

register_deactivation_hook( XYZ_EM_PLUGIN_FILE, 'em_deactivate' );

?>