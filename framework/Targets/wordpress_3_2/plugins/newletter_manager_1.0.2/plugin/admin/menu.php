<?php


//*********************Hide pages from public side menu*************************************//
add_action('wp_list_pages_excludes', 'xyz_em_hide_pages');

function xyz_em_hide_pages($explicitExcludes) {
	global $wpdb;
	$xyz_em_Ids = array();

	$the_page_unsubscribe = $wpdb->get_results("SELECT * FROM wp_postmeta WHERE meta_key='xyz_em_unsubscribe_page' AND meta_value='1'");
	$the_page_unsubscribe = $the_page_unsubscribe[0];
	$xyz_em_Ids[] = $the_page_unsubscribe->post_id;

	$the_page_thanks = $wpdb->get_results("SELECT * FROM wp_postmeta WHERE meta_key='xyz_em_thanks_page' AND meta_value='2'");
	$the_page_thanks = $the_page_thanks[0];
	$xyz_em_Ids[] = $the_page_thanks->post_id;

	$the_page_confirm = $wpdb->get_results("SELECT * FROM wp_postmeta WHERE meta_key='xyz_em_confirm_page' AND meta_value='3'");
	$the_page_confirm = $the_page_confirm[0];
	$xyz_em_Ids[] = $the_page_confirm->post_id;

	$excludes = array();
	$excludes = $xyz_em_Ids;
	$excludes = array_merge($excludes, $explicitExcludes);
	sort($excludes);
	return $excludes;
}

//*********************Hide pages from public side menu*************************************//







if ( is_admin() ){

	add_action('admin_menu', 'em_menu');
}

function em_menu(){



	add_menu_page('Newsletter Manager - Manage settings', 'XYZ Newsletter', 'manage_options', 'newsletter-manager-settings', 'em_settings',plugins_url('newsletter-manager/images/plugin.png'));

	// Add a submenu to the Dashboard:
	$page=add_submenu_page('newsletter-manager-settings', 'Newsletter Manager - Manage settings', 'Settings', 'manage_options', 'newsletter-manager-settings' ,'em_settings');

	add_submenu_page('newsletter-manager-settings', 'Newsletter Manager - Opt-in Form', 'Opt-in Form', 'manage_options', 'newsletter-manager-subscription-code' ,'em_subscription_code');

	add_submenu_page('newsletter-manager-settings', 'Newsletter Manager - Add Emails', 'Add Emails', 'manage_options', 'newsletter-manager-addemails' ,'em_add_emails');

	add_submenu_page('newsletter-manager-settings', 'Newsletter Manager - Search Emails', 'Search Emails', 'manage_options', 'newsletter-manager-searchemails' ,'em_search_emails');

	add_submenu_page('newsletter-manager-settings', 'Newsletter Manager - Email Addresses', 'Email Addresses', 'manage_options', 'newsletter-manager-emailaddresses' ,'em_email_addresses');

	add_submenu_page('newsletter-manager-settings', 'Newsletter Manager - Create Campaign', 'Create Campaign', 'manage_options', 'newsletter-manager-createcampaign' ,'em_create_campaign');

	add_submenu_page('newsletter-manager-settings', 'Newsletter Manager - Email Campaigns', 'Email Campaigns', 'manage_options', 'newsletter-manager-emailcampaigns' ,'em_email_campaigns');

	add_submenu_page('newsletter-manager-settings', 'Newsletter Manager - Import Export', 'Import/Export', 'manage_options', 'newsletter-manager-importexport' ,'em_import_export');

	add_submenu_page('newsletter-manager-settings', 'Newsletter Manager - Statistics', 'Statistics', 'manage_options', 'newsletter-manager-status' ,'em_status');

	add_submenu_page('newsletter-manager-settings', 'Newsletter Manager - User Guide', 'User Guide', 'manage_options', 'newsletter-manager-readme' ,'em_readme');
	
	add_submenu_page('newsletter-manager-settings', 'Newsletter Manager - About', 'About', 'manage_options', 'newsletter-manager-about' ,'em_about');
	

}



function em_settings(){
	require( dirname( __FILE__ ) . '/newsletter_manager_settings.php' );
	require( dirname( __FILE__ ) . '/footer.php' );
}


function em_import_export(){
	$importexportflag=0;

	if(isset($_GET['action']) && $_GET['action']=='export' ){
		include(dirname( __FILE__ ) . '/export.php');
		$importexportflag=1;
	}

	if(isset($_GET['action']) && $_GET['action']=='import' ){
		include(dirname( __FILE__ ) . '/import.php');
		$importexportflag=1;
	}

	if($importexportflag == 0){
		require( dirname( __FILE__ ) . '/import_export.php' );
	}
	require( dirname( __FILE__ ) . '/footer.php' );
}



function em_subscription_code(){

	require( dirname( __FILE__ ) . '/subscription_code.php' );
	require( dirname( __FILE__ ) . '/footer.php' );
}


function em_status(){
	require( dirname( __FILE__ ) . '/status.php' );
	require( dirname( __FILE__ ) . '/footer.php' );
}

function em_about(){
	require( dirname( __FILE__ ) . '/about.php' );
	require( dirname( __FILE__ ) . '/footer.php' );
}

function em_readme(){
	require( dirname( __FILE__ ) . '/readme.php' );
}




function em_add_emails(){
	require( dirname( __FILE__ ) . '/add_emails.php' );
	require( dirname( __FILE__ ) . '/footer.php' );
}

function em_search_emails(){
	require( dirname( __FILE__ ) . '/search_emails.php' );
	require( dirname( __FILE__ ) . '/footer.php' );
	
}
function em_email_addresses(){


	$emailflag=0;
	if(isset($_GET['action']) && $_GET['action']=='email_unsubscribe' )
	{
		include(dirname( __FILE__ ) . '/email_unsubscribe.php');
		$emailflag=1;
	}
	if(isset($_GET['action']) && $_GET['action']=='email_activate' )
	{
		include(dirname( __FILE__ ) . '/email_activate.php');
		$emailflag=1;
	}
	if(isset($_GET['action']) && $_GET['action']=='edit_email' )
	{
		include(dirname( __FILE__ ) . '/edit_email.php');
		$emailflag=1;
	}
	if(isset($_GET['action']) && $_GET['action']=='email_delete' )
	{
		include(dirname( __FILE__ ) . '/email_delete.php');
		$emailflag=1;
	}

	if(isset($_GET['action']) && $_GET['action']=='group' )
	{
		include(dirname( __FILE__ ) . '/group.php');
		$emailflag=1;
	}


	if($emailflag==0)
	{

		require( dirname( __FILE__ ) . '/email_addresses.php' );

	}
	require( dirname( __FILE__ ) . '/footer.php' );
}

function em_create_campaign(){
	require( dirname( __FILE__ ) . '/create_campaign.php' );
	require( dirname( __FILE__ ) . '/footer.php' );

}

function em_email_campaigns(){

	$campflag=0;
	if(isset($_GET['action']) && $_GET['action']=='campaign_status' )
	{
		include(dirname( __FILE__ ) . '/campaign_status.php');
		$campflag=1;
	}
	if(isset($_GET['action']) && $_GET['action']=='restart' )
	{
		include(dirname( __FILE__ ) . '/restart.php');
		$campflag=1;
	}
	if(isset($_GET['action']) && $_GET['action']=='preview' )
	{
		include(dirname( __FILE__ ) . '/preview.php');
		$campflag=1;
	}
	if(isset($_GET['action']) && $_GET['action']=='edit_campaign' )
	{
		include(dirname( __FILE__ ) . '/edit_campaign.php');
		$campflag=1;
	}
	if(isset($_GET['action']) && $_GET['action']=='attachment_delete' )
	{
		include(dirname( __FILE__ ) . '/attachment_delete.php');
		$campflag=1;
	}
	if(isset($_GET['action']) && $_GET['action']=='campaign_delete' )
	{
		include(dirname( __FILE__ ) . '/campaign_delete.php');
		$campflag=1;
	}
	if(isset($_GET['action']) && $_GET['action']=='test_mail' )
	{
		include(dirname( __FILE__ ) . '/test_mail.php');
		$campflag=1;
	}
	if(isset($_GET['action']) && $_GET['action']=='send_mail' )
	{
		include(dirname( __FILE__ ) . '/send_mail.php');
		$campflag=1;
	}

	if(isset($_GET['action']) && $_GET['action']=='cron' )
	{
		include(ABSPATH . 'wp-content/plugins/newsletter-manager/cron.php');
		$campflag=1;
	}

	if($campflag==0)
	{

		require( dirname( __FILE__ ) . '/email_campaigns.php' );

	}


	require( dirname( __FILE__ ) . '/footer.php' );

}
if(is_admin()){
	wp_enqueue_script('jquery');

	wp_register_script( 'calender_script', plugins_url('newsletter-manager/js/epoch_classes.js') );
	wp_enqueue_script( 'calender_script' );

	wp_register_script( 'notice_script', plugins_url('newsletter-manager/js/notice.js') );
	wp_enqueue_script( 'notice_script' );

	wp_register_style( 'xyz_style', plugins_url('newsletter-manager/css/xyz_em_styles.css'));
	wp_enqueue_style( 'xyz_style');

}





function xyz_em_admin_style()
{
	require( dirname( __FILE__ ) . '/style.php' );

}
add_action('admin_print_styles', 'xyz_em_admin_style');


?>