<?php

require( dirname( __FILE__ ) . '/tinymce_filters.php' );

// Load the options
global $wpdb;
$_GET = stripslashes_deep($_GET);
//require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
if($_POST){
$_POST = stripslashes_deep($_POST);
$_POST = xyz_trim_deep($_POST);
	// 	echo '<pre>';
	// 	print_r($_POST);
	// 	die;
	
	$xyz_em_pagenum = abs(intval($_POST['pageno']));
	
	$xyz_em_campId = intval($_POST['campId']);

	$xyz_em_defaultEditor = $_POST['xyz_em_defaultEditor'];

	if($xyz_em_defaultEditor == 1){
		$xyz_em_bodyPlain = $_POST['xyz_em_bodyPlain'];
		$xyz_em_body = $xyz_em_bodyPlain;
	}
	if($xyz_em_defaultEditor == 2){
		$xyz_em_page = $_POST['xyz_em_body'];
		$xyz_em_body = preg_replace("/<script.+?>.+?<\/script>/im","",$xyz_em_page);

	}

	if (($_POST['xyz_em_campName']!= "")
			&& ($_POST['xyz_em_campSubject'] != "")
			&& ($xyz_em_body != "")
			&& ($_POST['xyz_em_batchSize'] != "")
			&& ($_POST['xyz_em_senderName'] != "")
			&& ($_POST['xyz_em_senderEmail'] != "")
			&& ($_POST['xyz_em_redirectAfterLink'] != "")){

		$xyz_em_campName = $_POST['xyz_em_campName'];



		$xyz_em_startTime = $_POST['xyz_em_startTime'];
		$xyz_em_hour = $_POST['xyz_em_hour'];
		$xyz_em_minute = $_POST['xyz_em_minute'];
		$xyz_em_second = 00;

		$xyz_em_campSubject = $_POST['xyz_em_campSubject'];
		$xyz_em_altBody = $_POST['xyz_em_altBody'];
		$xyz_em_batchSize = abs(intval($_POST['xyz_em_batchSize']));
		$xyz_em_senderName = $_POST['xyz_em_senderName'];
		$xyz_em_redirectAfterLink = strip_tags($_POST['xyz_em_redirectAfterLink']);
		$xyz_em_senderEmail = $_POST['xyz_em_senderEmail'];



		if($xyz_em_startTime != ""){
			$startDateArray = explode('/',$xyz_em_startTime);
			// 				echo '<pre>';
			// 				print_r($startDateArray);

			$month = $startDateArray[0];
			$day = $startDateArray[1];
			$year = $startDateArray[2];

			if(($xyz_em_hour >= 0) && ($xyz_em_minute >=0)){

				$xyz_em_currentDateTime = mktime($xyz_em_hour,$xyz_em_minute,$xyz_em_second,$month,$day,$year);

			}else{
				$xyz_em_currentDateTime = mktime(0,0,0,$month,$day,$year);
			}

		}else{

			$xyz_em_currentDateTime = time();
		}

		for($i = 1; $i <= 5; $i++){

			if($_FILES['xyz_em_uploadFile_'.$i]['name'] != ""){

				${
					$uploadFileName.$i} = $_FILES['xyz_em_uploadFile_'.$i]['name'];
					$extension = pathinfo(${
						$uploadFileName.$i});

			}

		}

		if ($xyz_em_batchSize > 0){

			if($xyz_em_defaultEditor == 2){
					
				if($xyz_em_altBody == ""){
					$xyz_em_altBody = "";
			?>
				<!-- 
					<br>
					<div class="messageFailure">Fill alternate body.</div>
				 -->
			<?php
				}
					
			}elseif($xyz_em_defaultEditor == 1){
				$xyz_em_altBody = "";
			}

			$xyz_em_campFlag = 0;
			if(is_email($xyz_em_senderEmail)){

				$xyz_em_campaign_count = $wpdb->query( 'SELECT * FROM xyz_em_email_campaign WHERE name="'.$xyz_em_campName.'" AND id!="'.$xyz_em_campId.'" LIMIT 0,1' ) ;
				if($xyz_em_campaign_count == 0){




					$wpdb->update('xyz_em_email_campaign',
							array('name'=>$xyz_em_campName,
									'type'=>$xyz_em_defaultEditor,
									'subject'=>$xyz_em_campSubject,
									'body'=>$xyz_em_body,
									'alt_body'=>$xyz_em_altBody,
									'batch_size'=>$xyz_em_batchSize,
									'sender_name'=>$xyz_em_senderName,
									'sender_email'=>$xyz_em_senderEmail,
									'unsubscription_link'=>$xyz_em_redirectAfterLink,
									'start_time'=>$xyz_em_currentDateTime),
							array('id'=>$xyz_em_campId));


					$xyz_em_campLastid = $xyz_em_campId;
					if($xyz_em_campLastid != 0){
						for($i = 1; $i <= 5; $i++){

							if($_FILES['xyz_em_uploadFile_'.$i]['name'] != ""){

								$wpdb->insert('xyz_em_attachment', array('campaigns_id' => $xyz_em_campLastid,'name' => $_FILES['xyz_em_uploadFile_'.$i]['name']),array('%d','%s'));

								$xyz_em_attachmentId = $wpdb->insert_id;

								
								$targetfolder = realpath(dirname(__FILE__) . '/../../../')."/uploads";
								if (!is_file($targetfolder) && !is_dir($targetfolder)) {
								
									mkdir($targetfolder) or die("Could not create directory " . $targetfolder);
									chmod($targetfolder, 0777); //make it writable
								}
								$targetfolder = realpath(dirname(__FILE__) . '/../../../')."/uploads/xyz_em";
								if (!is_file($targetfolder) && !is_dir($targetfolder)) {
								
									mkdir($targetfolder) or die("Could not create directory " . $targetfolder);
									chmod($targetfolder, 0777); //make it writable
								}
								
								
								$dir = "uploads/xyz_em/attachments";
								$targetfolder = realpath(dirname(__FILE__) . '/../../../')."/".$dir;
								if (!is_file($targetfolder) && !is_dir($targetfolder)) {

									mkdir($targetfolder) or die("Could not create directory " . $targetfolder);

									chmod($targetfolder, 0777); //make it writable
								}
								move_uploaded_file($_FILES['xyz_em_uploadFile_'.$i]["tmp_name"],$targetfolder."/".$xyz_em_attachmentId."_".$_FILES['xyz_em_uploadFile_'.$i]['name']);
							}

						}
							
					}
					$xyz_em_campFlag = 1;

					}
					if($xyz_em_campFlag == 1){
						
						header("Location:".admin_url('admin.php?page=newsletter-manager-emailcampaigns&campmsg=2&pagenum='.$xyz_em_pagenum));
						exit();

				}else{
					?>
<div class="system_notice_area_style0" id="system_notice_area">
	Campaign name already exists.&nbsp;&nbsp;&nbsp;<span
		id="system_notice_area_dismiss">Dismiss</span>
</div>
<?php
				}

			}else{
				?>
<div class="system_notice_area_style0" id="system_notice_area">
	Please enter a valid sender email.&nbsp;&nbsp;&nbsp;<span
		id="system_notice_area_dismiss">Dismiss</span>
</div>
<?php
			}


		}else{
			?>
<div class="system_notice_area_style0" id="system_notice_area">
	Batch size must be a positive integer.&nbsp;&nbsp;&nbsp;<span
		id="system_notice_area_dismiss">Dismiss</span>
</div>
<?php		
		}
	}else{
		?>
<div class="system_notice_area_style0" id="system_notice_area">
	Fill all fields.&nbsp;&nbsp;&nbsp;<span id="system_notice_area_dismiss">Dismiss</span>
</div>
<?php
	}
}
?>


<!-- below for date picker -->


<script type="text/javascript">
var dp_cal;      
window.onload = function ()
{
dp_cal  = new Epoch('epoch_popup','popup',document.getElementById('startTime'));	
};
</script>

<link rel="stylesheet" href="<?php echo plugins_url("newsletter-manager/css/datepicker.css");?>" type="text/css"	media="screen" />

<!-- above for date picker -->





<script type="text/javascript">

	jQuery(document).ready(function() {
		jQuery('#collapseimg').hide();
		jQuery('#expandimg').show();
		jQuery('#attachments').hide();
		  // Handler for .ready() called.
		jQuery('#collapseimg').click(function() {
			jQuery('#attachments').hide(200);
			jQuery('#collapseimg').hide();
			jQuery('#expandimg').show();
			});
		jQuery('#expandimg').click(function() {
			jQuery('#attachments').show(200);
			jQuery('#expandimg').hide();
			jQuery('#collapseimg').show();
			});
			  
		});
	

</script>



<?php 

$xyz_em_campId = intval($_GET['id']);

if($_GET['pageno'] != ""){
	$xyz_em_pageno = abs(intval($_GET['pageno']));
}else{
	$xyz_em_pageno= 1;
}

if($xyz_em_campId=="" || !is_numeric($xyz_em_campId)){
	header("Location:".admin_url('admin.php?page=newsletter-manager-campaigns'));
	exit();

}
$campDetails = $wpdb->get_results( 'SELECT * FROM xyz_em_email_campaign WHERE id="'.$xyz_em_campId.'" ' ) ;

if(count($campDetails)==0){
	header("Location:".admin_url('admin.php?page=newsletter-manager-campaigns'));
	exit();
}else{

	if($campDetails){
		$details = $campDetails[0];

		?>


<div>

	<h2>Edit Campaign</h2>
	<form method="post" enctype="multipart/form-data">
		<table class="widefat" style="width:98%;">

			<tr valign="top">
				<td scope="row" class="td"><label for="xyz_em_campName">Campaign
						Name</label>
				</td>
				<td><input id="input" name="xyz_em_campName" type="text"
					id="xyz_em_campName"
					value="<?php if(isset($_POST['xyz_em_campName'])){ echo esc_html($_POST['xyz_em_campName']);}else{ echo esc_html($details->name); }?>" />
				</td>
			</tr>
			<tr valign="top">
				<td scope="row"><label for="xyz_em_defaultEditor">Email Type</label>
				</td>
				<td><select class="select" name="xyz_em_defaultEditor"
					id="xyz_em_defaultEditor">
						<option value="1" <?php if(isset($_POST['xyz_em_defaultEditor']) && $_POST['xyz_em_defaultEditor']==1){echo 'selected';}elseif($details->type==1) echo 'selected'; ?>>Plain
							Text</option>
						<option value="2" <?php if(isset($_POST['xyz_em_defaultEditor']) && $_POST['xyz_em_defaultEditor']==2){echo 'selected';}elseif($details->type==2) echo 'selected';  ?>>HTML</option>

				</select>
				</td>
			</tr>

			<tr>
				<td>&nbsp;</td>
				<td>

					Date&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Time
				</td>

			</tr>
			<tr>
				<td>Start Time</td>
				<td><input readonly="readonly" name="xyz_em_startTime"
					id="startTime" type="text"
					value="<?php if(isset($_POST['xyz_em_startTime']) && $_POST['xyz_em_startTime'] != ""){echo $_POST['xyz_em_startTime'];}else{ echo date("m/d/Y",$details->start_time);}?>" />
					 <select
					id="hour" name="xyz_em_hour" id="select">

						<?php 

						for($i = 0;$i<=23;$i++){

							?>
						<option value="<?php echo $i;?>"
						<?php if(isset($_POST['xyz_em_hour']) && $_POST['xyz_em_hour'] == $i){echo 'selected';}elseif(date("H", $details->start_time)==$i){ echo 'selected';}?>>
							<?php echo $i;?>
						</option>
						<?php 

						}

						?>
				</select>H <select id="minute" name="xyz_em_minute" id="select">

						<?php 

						for($i = 0;$i<=59;$i++){

							?>
						<option value="<?php echo $i;?>"
						<?php if(isset($_POST['xyz_em_minute']) && $_POST['xyz_em_minute'] == $i){echo 'selected';}elseif(date("i", $details->start_time)==$i){ echo 'selected';}?>>
							<?php echo $i;?>
						</option>
						<?php 

						}

						?>
				</select>M</td>
			</tr>
			<tr valign="top">
				<td scope="row"><label for="xyz_em_campSubject">Mail Subject</label>
				</td>
				<td><input id="input" name="xyz_em_campSubject" type="text"
					id="xyz_em_campSubject" value="<?php if(isset($_POST['xyz_em_campSubject'])){echo esc_html($_POST['xyz_em_campSubject']);}else{ echo esc_html($details->subject);} ?>" />
				</td>
			</tr>

			<tr>
				<td scope="row"><label for="xyz_em_body">Body Content</label></td>
				<td >
					<div id="htmlcamp">
						<?php 

						if(get_option('xyz_em_defaultEditor') == "Text Editor"){


							?>
						<textarea class="areaSize"  name="xyz_em_body"><?php

							if(isset($_POST['xyz_em_body'])){
								echo esc_textarea($_POST['xyz_em_body']);
							}else{
							echo esc_textarea($details->body); 
							}
							?></textarea>
						<?php 
							
						}else if(get_option('xyz_em_defaultEditor') == "HTML Editor"){

							if(isset($_POST['xyz_em_body'])){
								the_editor($_POST['xyz_em_body'],'xyz_em_body');
							}else{
								the_editor($details->body,'xyz_em_body');
							}
						}

						?>

					</div>
					<div id="plainText">

						<textarea class="areaSize" name="xyz_em_bodyPlain"><?php 
							if(isset($_POST['xyz_em_bodyPlain'])){
								echo esc_textarea($_POST['xyz_em_bodyPlain']);
							}else{
								echo esc_textarea($details->body);
							}
							 ?></textarea>

					</div>
				</td>
			</tr>
			<tr>
				<td></td>
				<td scope="row">
					<div class="campCreateDiv5">
						<b>{field1}</b>&nbsp;-&nbsp;Name.
					</div>
				</td>
			</tr>
			<tr>
				<td></td>
				<td scope="row">
				<b>{unsubscribe-url}</b> - Will be replaced with
					unsubscription link.</td>
			</tr>
<!-- 
			<tr class="campCreatTr1" id="altHtml">
				<td scope="row"><label for="xyz_em_altBody">Alternate Body Content</label></td>
				<td ><textarea id="textarea" name="xyz_em_altBody">
					<?php

// 					if(isset($_POST['xyz_em_altBody']){
// 						echo esc_html($_POST['xyz_em_altBody']);
// 					}else{
// 						echo esc_html($details->alt_body);
// 					}
					 ?>
					
									</textarea> <br /> <br />
					<div class="campCreateDiv5">
						<b>{field1}</b>&nbsp;-&nbsp;Name.
					</div>
					<div class="campCreateDiv9">
						<b>{unsubscribe-option}</b> - Will be replaced with Unsubscription
						link.
					</div>
				</td>
			</tr>
 -->
			<tr valign="top">
				<td scope="row"><label for="xyz_em_batchSize">Batch Size</label>
				</td>
				<td><input id="input" name="xyz_em_batchSize" type="text"
					id="xyz_em_batchSize"
					value="<?php if(isset($_POST['xyz_em_batchSize']) ) echo abs(intval($_POST['xyz_em_batchSize']));else echo $details->batch_size; ?>" />
				</td>
			</tr>
			<tr valign="top">
				<td scope="row"><label for="xyz_em_senderName">Sender Name</label>
				</td>
				<td><input id="input" name="xyz_em_senderName" type="text"
					id="xyz_em_senderName"
					value="<?php if(isset($_POST['xyz_em_senderName']) ) echo esc_html($_POST['xyz_em_senderName']);else echo esc_html($details->sender_name); ?>" />
				</td>
			</tr>
			<tr valign="top">
				<td scope="row"><label for="xyz_em_redirectAfterLink">Redirection
						link after unsubscription</label>
				</td>
				<td><input id="input" name="xyz_em_redirectAfterLink" type="text"
					id="xyz_em_redirectAfterLink"
					value="<?php if(isset($_POST['xyz_em_redirectAfterLink']) ) echo striptags($_POST['xyz_em_redirectAfterLink']);else echo ($details->unsubscription_link); ?>" />
				</td>
			</tr>
			<tr valign="top">
				<td scope="row"><label for="xyz_em_senderEmail">Sender Email</label>
				</td>
				<td><input id="input" name="xyz_em_senderEmail" type="text"
					id="xyz_em_senderEmail"
					value="<?php if(isset($_POST['xyz_em_senderEmail'])) echo esc_html($_POST['xyz_em_senderEmail']);else echo esc_html($details->sender_email); ?>" />
				</td>
			</tr>
			<?php 

			$attachDetails = $wpdb->get_results( 'SELECT * FROM xyz_em_attachment WHERE campaigns_id="'.$xyz_em_campId.'" ' ) ;
			if(count($attachDetails)>0){
				$i = 1;
				foreach ($attachDetails as $key => $attachmentDetails){
					?>
			<tr>
				<td scope="row">
						Attachment&nbsp;
						<?php echo $i;?>
				</td>
				<td>
							<?php echo $attachmentDetails->name."  ";?>
							<a
								href='<?php echo admin_url('admin.php?page=newsletter-manager-emailcampaigns&action=attachment_delete&id='.$attachmentDetails->id.'&campId='.$details->id); ?>'>Delete</a>
						
					</td>
			</tr>
			<?php 		
			$i++;
				}


			}


			?>

			<tr>
				<td>Add Attachments</td>

				<td>
					<p id="collapseimg">
						<button class="button-primary" type="button" id="buttonDesign">

							<div>Collapse</div>
						</button>
					</p>
					<p id="expandimg">
						<button class="button-primary" type="button" id="buttonDesign">

							<div>Expand</div>
						</button>
					</p>

				</td>
			</tr>
			<tr>
				<td colspan="2">
					<div id="attachments">

						<table class="widefat">
							<?php 

							for($i = 1; $i <= 5; $i++){
									
								?>
							<tr>
								<td scope="row" style="width:350px;"><label >Attachment&nbsp;<?php echo $i;?>
								</label>
								</td>
								<td><input  type="file"
									name="xyz_em_uploadFile_<?php echo $i;?>" />
								</td>
							</tr>
							<?php }?>
						</table>
					</div>
				</td>
			</tr>


			<tr>
				<td scope="row"></td>
				<td>
				<div style="height:50px;"><input style="margin:10px 0 20px 0;" id="submit" class="button-primary bottonWidth" type="submit" value="Update Campaign" /></div>
				</td>
			</tr>
			<tr>
				<td id="bottomBorderNone" scope="row"><a
					href='javascript:history.back(-1);'>Go back </a>
			<input type="hidden" name="campId"				value="<?php echo $details->id; ?>">
				<input type="hidden" name="pageno"			value="<?php echo $xyz_em_pageno; ?>">
				</td>
			</tr>
		</table>
	</form>

</div>
<?php 

	}
}

?>
<script type="text/javascript">


function editor_change()
{
    if (jQuery("#xyz_em_defaultEditor").val() == 1) {
        jQuery("#plainText").show();
        jQuery("#htmlcamp").hide();
        jQuery("#altHtml").hide();  
   }
   if (jQuery("#xyz_em_defaultEditor").val() == 2) {
        jQuery("#plainText").hide();
        jQuery("#htmlcamp").show();
        jQuery("#altHtml").show();
  }

}

						
jQuery(document).ready(function() {

	jQuery("#xyz_em_defaultEditor").change(function(){
		editor_change();
	});
	
	editor_change();
		  
	});	

</script>

