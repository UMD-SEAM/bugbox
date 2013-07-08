<?php 

////*****************************Sidebar Widget**********************************////

add_action("plugins_loaded", "newsletter_manager_init");

function newsletter_manager_init()
{
	register_sidebar_widget(__('Newsletter Manager'), 'widget_newsletter_manager');
}


function widget_newsletter_manager($args) {
	extract($args);
	echo $before_widget;
	echo $before_title;
	echo esc_html(get_option('xyz_em_widgetName'));
	echo $after_title;
?>	
<script>
	function verify_fields()
	{
	var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
	var address = document.subscription.xyz_em_email.value;
	if(reg.test(address) == false) {
	alert("Please check whether the email is correct.");
	return false;
}else{
document.subscription.submit();
}
}
</script>
<style>
.buttonWidget {

	width: 90px;

}

</style>

<form method="POST" name="subscription" action="<?php echo plugins_url("newsletter-manager/subscription.php");?>">
	
	<table border="0" style="border: 1px solid #FFFFFF; color: black;">
		<tr>

		</tr>
		<tr>
			<td width="200">Name</td>
		</tr>
		<tr>
			<td><input name="xyz_em_name" type="text" />
			</td>
		</tr>
		<tr>
			<td width="200">Email Address<span style="color: #FF0000">*</span>
			</td>
		</tr>
		<tr>
			<td><input name="xyz_em_email" type="text" />
			</td>
		</tr>
		
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>
				<div style="height: 20px;">
					<input id="submit" class="buttonWidget" type="submit"
						value="Subscribe"
						onclick="javascript: if(!verify_fields()) return false; " />
				</div>
			</td>
		</tr>
	</table>
</form>
	<?php 
echo $after_widget;
}







/////*****************************Dashboard Widget**********************************////



add_action( 'wp_dashboard_setup', 'xyz_em_add_dashboard_widget' );

function xyz_em_add_dashboard_widget() {

	wp_add_dashboard_widget( 'xyz-em-custom-widget', 'Newsletter Statistics', 'xyz_em_dashboard_widget' );
}


function xyz_em_dashboard_widget() {

	global $wpdb;

	?>
<div>
<fieldset
style="width: 98%; border: 1px solid #F7F7F7; padding: 10px 0px 15px 10px;">
<legend>Email Address Statistics</legend>
<table class="widefat" style="width: 99%;">
<tr valign="top">
<td scope="row" style="width:20%;" id="bottomBorderNone"><label
for="">Pending&nbsp;:</label>
</td>
<td class="settingInput" id="bottomBorderNone">
<?php
$xyz_em_pendingCount = $wpdb->get_results("SELECT ea.id,ea.email,em.status FROM xyz_em_email_address ea INNER JOIN xyz_em_address_list_mapping em ON ea.id=em.ea_id WHERE em.status='-1'");
echo count($xyz_em_pendingCount);
?></td>

<td scope="row" style="width:20%;" id="bottomBorderNone"><label
for="">Active&nbsp;:</label>
</td>
<td class="settingInput" id="bottomBorderNone">
<?php
$xyz_em_activeCount = $wpdb->get_results("SELECT ea.id,ea.email,em.status FROM xyz_em_email_address ea INNER JOIN xyz_em_address_list_mapping em ON ea.id=em.ea_id WHERE em.status='1'");
echo  count($xyz_em_activeCount);
?></td>


<td scope="row" style="width:20%;" id="bottomBorderNone" ><label
for="">Unsubscribed&nbsp;:</label>
</td>
<td class="settingInput" id="bottomBorderNone">
<?php
$xyz_em_unsubscribedCount = $wpdb->get_results("SELECT ea.id,ea.email,em.status FROM xyz_em_email_address ea INNER JOIN xyz_em_address_list_mapping em ON ea.id=em.ea_id WHERE em.status='0'");
echo count($xyz_em_unsubscribedCount);
?> </td>

</tr>
</table>
</fieldset>

<fieldset
style="width: 98%; border: 1px solid #F7F7F7; padding: 10px 0px 15px 10px;">
<legend>Queue Statistics</legend>
<table class="widefat" style="width: 99%;">
<tr valign="top">
<td scope="row" style="width:80%;" id="bottomBorderNone"><label
for="">Email Fired In Current Hour / Hourly Email Sending Limit &nbsp;:</label>
</td>
<td class="settingInput" id="bottomBorderNone"><div  style="margin-left:10px;"><?php echo get_option('xyz_em_hourly_email_sent_count');?> / <?php echo get_option('xyz_em_hesl');?></div></td>

</tr>
</table>
</fieldset>

<fieldset
style="width: 98%; border: 1px solid #F7F7F7; padding: 10px 0px 15px 10px;">
<legend>Campaign Statistics</legend>
<table class="widefat" style="width: 99%;">
<tr valign="top">
<td scope="row" style="width:20%;" id="bottomBorderNone"><label
for="">Pending&nbsp;:</label>
</td>
<td class="settingInput" id="bottomBorderNone">
<?php
$xyz_em_pendingCampaignCount = $wpdb->get_results("SELECT * FROM xyz_em_email_campaign  WHERE status='-1'");
echo  count($xyz_em_pendingCampaignCount);
?></td>
<td scope="row" style="width:20%;" id="bottomBorderNone"><label
for="">Active&nbsp;:</label>
</td>
<td class="settingInput" id="bottomBorderNone">
<?php
$xyz_em_activeCampaignCount = $wpdb->get_results("SELECT * FROM xyz_em_email_campaign  WHERE status='1'");
echo count($xyz_em_activeCampaignCount);
?></td>

<td scope="row" style="width:20%;" id="bottomBorderNone"><label
for="">Paused&nbsp;:</label>
</td>
<td class="settingInput" id="bottomBorderNone">
<?php
$xyz_em_pausedCampaignCount = $wpdb->get_results("SELECT * FROM xyz_em_email_campaign  WHERE status='0'");
echo count($xyz_em_pausedCampaignCount);
?></td>

</tr>
</table>
</fieldset>
<fieldset
style="width: 98%; border: 1px solid #F7F7F7; padding: 10px 0px 15px 10px;">
<legend>Cron Execution Info</legend>
<table class="widefat" style="width: 99%;">
<tr valign="top">
<td scope="row" style="width:16%;" id="bottomBorderNone" ><label
for="">Start Time:</label>
</td>
<td style="width:34%;" id="bottomBorderNone"><?php

if(get_option('xyz_em_cronStartTime') != 0){

echo date("d-m-Y H:i:s",get_option('xyz_em_cronStartTime'));

}else{

echo "NA";

}

?></td>
<td scope="row" style="width:16%;" id="bottomBorderNone"><label
for="">End Time:</label>
</td>
<td style="width:34%; " id="bottomBorderNone"><?php
if(get_option('xyz_em_CronEndTime') != 0){

echo date("d-m-Y H:i:s",get_option('xyz_em_CronEndTime'));

}else{

echo "NA";

}

?></td>
</tr>
</table>
</fieldset>

</div>
<?php
}
?>