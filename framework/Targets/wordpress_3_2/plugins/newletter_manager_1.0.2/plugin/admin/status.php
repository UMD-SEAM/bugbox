<?php 

global $wpdb;

?>
<div>

	<h2>Statistics</h2>

	<fieldset
		style="width: 98%; border: 1px solid #F7F7F7; padding: 10px 0px 15px 10px;">
		<legend>Email Address Statistics</legend>
		<table class="widefat" style="width: 99%;">
			<tr valign="top">
				<td scope="row" class="settingInput" ><label
					for="">Pending Emails</label>
				</td>
				<td class="settingInput" ><div class="setInput"> <?php echo pendingEmailCount();?></div> </td>

			<td scope="row" class="settingInput">Active Emails<label
					for=""></label>
				</td>
				<td class="settingInput"><div class="setInput"> <?php echo activeEmailCount();?></div> </td>
			</tr>
			<tr valign="top">
							<td scope="row" class="settingInput" id="bottomBorderNone" ><label
					for="">Unsubscribed Emails</label>
				</td>
				<td class="settingInput" id="bottomBorderNone"><div class="setInput"><?php echo unsubscribedEmailCount();?> </div> </td>
				<td scope="row"class="settingInput" id="bottomBorderNone"><label
					for="">Total Email Addresses</label>
				</td>
				<td class="settingInput" id="bottomBorderNone"><div class="setInput"> <?php echo $wpdb->get_var( "SELECT COUNT(`id`) FROM xyz_em_email_address" );?> </div></td>
			</tr>
		</table>
	</fieldset>
	
	<fieldset
		style="width: 98%; border: 1px solid #F7F7F7; padding: 10px 0px 15px 10px;">
		<legend>Queue Statistics</legend>
		<table class="widefat" style="width: 99%;">
			<tr valign="top">
				<td scope="row" class=" settingInput" id="bottomBorderNone"><label
					for="">Email Fired In Current Hour</label>
				</td>
				<td class="settingInput" id="bottomBorderNone"><div class="setInput" style="margin-left:13px;"><?php echo get_option('xyz_em_hourly_email_sent_count');?></div></td>
				<td scope="row" class="settingInput" id="bottomBorderNone"><label
					for="">Hourly Email Sending Limit</label>
				</td>
				<td class="settingInput" id="bottomBorderNone"><div class="setInput" style="margin-left:7px;"><?php echo get_option('xyz_em_hesl');?></div></td>
			</tr>
		</table>
	</fieldset>
	
	<fieldset
		style="width: 98%; border: 1px solid #F7F7F7; padding: 10px 0px 15px 10px;">
		<legend>Campaign Statistics</legend>
		<table class="widefat" style="width: 99%;">
			<tr valign="top">
				<td scope="row" class="settingInput"><label
					for="">Pending</label>
				</td>
				<td class="settingInput"><div class="setInput" style="margin-left:9px;"> <?php echo pendingCampaignCount();?> </div></td>
				<td scope="row" class="settingInput"><label
					for="">Active</label>
				</td>
				<td class="settingInput" ><div class="setInput" style="margin-left:10px;"> <?php echo activeCampaignCount();?></div> </td>
			</tr>
			<tr valign="top">
				<td scope="row" class="settingInput" id="bottomBorderNone"><label
					for="">Paused</label>
				</td>
				<td class="settingInput" id="bottomBorderNone"><div class="setInput" style="margin-left:9px;"> <?php echo pausedCampaignCount();?></div> </td>
				<td scope="row" class="settingInput" id="bottomBorderNone"><label
					for="">Total</label>
				</td>
				<td class="settingInput" id="bottomBorderNone"><div class="setInput" style="margin-left:10px;"> <?php echo totalCampaignCount();?></div> </td>
			</tr>
		</table>
	</fieldset>
	<fieldset
		style="width: 98%; border: 1px solid #F7F7F7; padding: 10px 0px 15px 10px;">
		<legend>Cron Execution Info</legend>
		<table class="widefat" style="width: 99%;">
			<tr valign="top">
				<td scope="row" class=" settingInput"id="bottomBorderNone" ><label
					for="">Cron Start Time</label>
				</td>
				<td class="settingInput" id="bottomBorderNone"><div><?php
				
				if(get_option('xyz_em_cronStartTime') != 0){
				
					echo date("d-m-Y H:i:s",get_option('xyz_em_cronStartTime'));
				
				}else{
				
					echo "NA";
				
				}
				
				?></div></td>
				<td scope="row" class=" settingInput" id="bottomBorderNone"><label
					for="">Cron End Time</label>
				</td>
				<td class="settingInput" id="bottomBorderNone"><div><?php
				if(get_option('xyz_em_CronEndTime') != 0){
				
				echo date("d-m-Y H:i:s",get_option('xyz_em_CronEndTime'));
				
				}else{
					
					echo "NA";
					
				}
				
				?></div></td>
			</tr>
		</table>
	</fieldset>

</div>
<?php 

function pendingEmailCount(){
	global $wpdb;
	$xyz_em_pendingCount = $wpdb->get_results("SELECT ea.id,ea.email,em.status FROM xyz_em_email_address ea INNER JOIN xyz_em_address_list_mapping em ON ea.id=em.ea_id WHERE em.status='-1'");
	return count($xyz_em_pendingCount);
}

function activeEmailCount(){
	global $wpdb;
	$xyz_em_activeCount = $wpdb->get_results("SELECT ea.id,ea.email,em.status FROM xyz_em_email_address ea INNER JOIN xyz_em_address_list_mapping em ON ea.id=em.ea_id WHERE em.status='1'");
	return count($xyz_em_activeCount);
}

function unsubscribedEmailCount(){
	global $wpdb;
	$xyz_em_unsubscribedCount = $wpdb->get_results("SELECT ea.id,ea.email,em.status FROM xyz_em_email_address ea INNER JOIN xyz_em_address_list_mapping em ON ea.id=em.ea_id WHERE em.status='0'");
	return count($xyz_em_unsubscribedCount);
}

function pendingCampaignCount(){
	global $wpdb;
	$xyz_em_pendingCampaignCount = $wpdb->get_results("SELECT * FROM xyz_em_email_campaign  WHERE status='-1'");
	return count($xyz_em_pendingCampaignCount);
}

function activeCampaignCount(){
	global $wpdb;
	$xyz_em_activeCampaignCount = $wpdb->get_results("SELECT * FROM xyz_em_email_campaign  WHERE status='1'");
	return count($xyz_em_activeCampaignCount);
}

function pausedCampaignCount(){
	global $wpdb;
	$xyz_em_pausedCampaignCount = $wpdb->get_results("SELECT * FROM xyz_em_email_campaign  WHERE status='0'");
	return count($xyz_em_pausedCampaignCount);
}

function totalCampaignCount(){
	global $wpdb;
	$xyz_em_totalCampaignCount = $wpdb->get_results("SELECT * FROM xyz_em_email_campaign");
	return count($xyz_em_totalCampaignCount);
}





?>
