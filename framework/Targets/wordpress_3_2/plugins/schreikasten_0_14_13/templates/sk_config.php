<div class="wrap">
	<div id="icon-options-general" class="icon32"><br /></div> <h2><?php _e('Schreikasten Settings', 'sk') ?></h2>
	<form name="form1" method="post" action="<?php echo remove_query_arg(array('mode', 'id')); ?>">
		<table class="form-table">
			<?php if(strlen(get_option('sk_api_key'))==0 || !sk_verify_key()) {
				echo "<tr><td colspan='2'><div class='updated'><p>".sprintf(__("You need an Akismet <a href='%s' target='_BLANK'>API</a> to enable the antispam filter.", 'sk' ), 'http://wordpress.com/api-keys/')."</p></div></td></tr>"; 
			}?>
			<tr>
				<td style="width: 300px;"><?php _e("Akismet API", 'sk' ); ?>:</td>
				<td><input type="text" name="sk_api_key" size="30" value="<?php echo $sk_api_key; ?>" /></td>
			</tr>
			<tr>
				<?php 
				if(strlen(get_option('sk_api_key'))==0) {
					update_option('sk_api_key_accepted',false);
					echo "<td colspan='2' style='padding-left: 190px;'><strong>".sprintf(__("Set the <a href='%s' target='_BLANK'>API Key</a> if you require the antispam filter.", 'sk'), 'http://wordpress.com/api-keys/')."</strong></td>";
				} else {
					if(sk_verify_key()) {
						update_option('sk_api_key_accepted',true);
						echo "<td colspan='2' style='background: #00FF00; padding-left: 190px;'><strong>".__("API Key is valid.", 'sk')."</strong></td>";
					} else {
						update_option('sk_api_key_accepted',false); 
						echo "<td colspan='2' style='background: #FF0000; padding-left: 190px;'><strong>".__("API Key is not valid.", 'sk')."<br/>".sprintf(__("Set the <a href='%s' target='_BLANK'>API Key</a> if you require the antispam filter.", 'sk'), 'http://wordpress.com/api-keys/')."</strong></td>";
					}
				}?>
			</tr>

<tr>
	<td><?php _e("Date format", 'sk' ); ?>: </td>
	<td><input type='text' name='date_format' value='<?php echo $options['date_format']; ?>'></td>
</tr>

<tr>
	<td colspan=2 style='padding-left: 190px;'><?php _e("<a href='http://codex.wordpress.org/Formatting_Date_and_Time' target='_BLANK'>Documentation about date format</a>.", 'sk'); ?></td>
</tr>

<tr>
	<td><?php _e('Show Avatar', 'sk'); ?>:
</td>
	<td>
		<input type="checkbox" class="checkbox" id="sk_avatar" name="sk_avatar"<?php
			if($status) echo " checked"; ?> />
	</td>
</tr>

<tr>
	<td>
		<?php 
		_e('Registered users only', 'sk'); ?>:
		</td><td>
		<select id="sk_registered" name="sk_registered">
			<option<?php echo $registered1; ?> value='1'><?php _e('Use general configuration', 'sk'); ?></option>
			<option<?php echo $registered2; ?> value='2'><?php _e('Yes', 'sk'); ?></option>
			<option<?php echo $registered3; ?> value='3'><?php _e('No', 'sk'); ?></option>
		</select>
</td>
</tr>

<tr>
	<td>
	<?php 
		_e('Comments must be moderated', 'sk'); ?>:
		</td><td>
		<select id="sk_moderation" name="sk_moderation">
			<option<?php echo $moderation0; ?> value='0'><?php _e('Use general configuration', 'sk'); ?></option>
			<option<?php echo $moderation1; ?> value='1'><?php _e('Yes', 'sk'); ?></option>
			<option<?php echo $moderation2; ?> value='2'><?php _e('No', 'sk'); ?></option>
		</select>
</td>
</tr>

<tr>
	<td>
	<?php
		_e('Requiere e-mail','sk'); ?>:
		</td><td>
		<select id="sk_requiremail" name="sk_requiremail">
			<option<?php echo $require1; ?> value='1'><?php _e('Use general configuration', 'sk'); ?></option>
			<option<?php echo $require2; ?> value='2'><?php _e('Yes', 'sk'); ?></option>
			<option<?php echo $require3; ?> value='3'><?php _e('No', 'sk'); ?></option>
		</select>
</td>
</tr>

<tr>
	<td>
	<?php
		_e('Announce comments (send e-mail)','sk'); ?>:
		</td><td>
		<select id="sk_announce" name="sk_announce">
			<option<?php echo $announce1; ?> value='1'><?php _e('Use general configuration', 'sk'); ?></option>
			<option<?php echo $announce2; ?> value='2'><?php _e('Send e-mail', 'sk'); ?></option>
			<option<?php echo $announce3; ?> value='3'><?php _e('Don\'t send e-mail', 'sk'); ?></option>
		</select>
	</td>
</tr>

<tr>
	<td>
		<?php _e('Layout', 'sk'); ?>:
	</td>
	<td>
		<select id="sk_layout" name="sk_layout">
			<option<?php echo $layout1; ?> value='1'><?php _e('Guest Book', 'sk'); ?></option>
			<option<?php echo $layout2; ?> value='2'><?php _e('Black Board', 'sk'); ?></option>
			<option<?php echo $layout3; ?> value='3'><?php _e('Chat Box', 'sk'); ?></option>
			<option<?php echo $layout4; ?> value='4'><?php _e('Questions and Answers', 'sk'); ?></option>
		</select>
	</td>
</tr>
<tr>
	<td colspan=2 style='padding-left: 190px;'>
		<strong><?php _e('Guest Book', 'sk'); ?></strong>: <?php _e('Just to leave messages. No replies.', 'sk'); ?>
		<br/><strong><?php _e('Black Board', 'sk'); ?></strong>: <?php _e('Anyone can leave replies to any comment, but there wouldn\'t be threads. A reply is listed in the order they come like another comment.', 'sk'); ?>
		<br/><strong><?php _e('Chat Box', 'sk'); ?></strong>: <?php _e('The space to write comments comes at the bottom, and the messages list goes like in a chat room. Anyone can leave replies to any comment, but there wouldn\'t be threads. A reply is listed in the order they come like another comment.', 'sk'); ?>
		<br/><strong><?php _e('Questions and Answers', 'sk'); ?></strong>: <?php _e('Only the administrator can leave a reply, and it will be shown right after the parent message.', 'sk'); ?>
	</td>
</tr>

<tr>
	<td>
		<?php _e('Alert users about posting e-mails in their comments', 'sk'); ?>:
</td>
	<td>
		<input type="checkbox" class="checkbox" id="sk_alert_about_emails" name="sk_alert_about_emails"<?php
		if($alert_about_emails) echo " checked"; ?> />
	</td>
</tr>

<tr>
	<td><?php
		_e('Number of characters allowed per comment','sk'); ?>: 
		</td><td>
		<input type="text" name="sk_maxchars" style="width: 50px;" value="<?php echo $maxchars; ?>">
</td>
</tr>

<tr>
	<td>
	<?php
		_e('Refresh rate','sk'); ?><a title="<?php _e('This option is in an early stage, take caution.', 'sk'); ?>" style="cursor: pointer;">(!)</a>:
	</td><td>
	<select id="sk_refresh" name="sk_refresh">
		<option<?php echo $selectedrefresh0; ?> value="0"><?php _e('Never','sk'); ?></option>
		<option<?php echo $selectedrefresh5; ?> value="5">5 <?php _e('seconds','sk'); ?></option>
		<option<?php echo $selectedrefresh10; ?> value="10">10 <?php _e('seconds','sk'); ?></option>
		<option<?php echo $selectedrefresh60; ?> value="60">60 <?php _e('seconds','sk'); ?></option>
	</select>
</td>
</tr>

<tr>
	<td><?php
		_e('Delete any comment older than','sk'); ?>:</td><td style='vertical-align: top;'><input type="text" name="sk_delete_num" style="width: 50px;" value="<?php echo $options['delete_num']; ?>"> <select id="sk_delete_type" name="sk_delete_type">
		<option<?php echo $deletetype1; ?> value="1"><?php _e('days','sk'); ?></option>
		<option<?php echo $deletetype2; ?> value="2"><?php _e('weeks','sk'); ?></option>
		<option<?php echo $deletetype3; ?> value="3"><?php _e('months','sk'); ?></option>
	</select>
	<br><?php _e('Void or 0 to not delete.', 'sk'); ?>
</td>
</tr>

<tr>
	<td></td>
	<td></td>
</tr>

<tr>
	<td>
	<?php
		_e('Max days to hold a PC blacklisted','sk'); ?>:
	</td><td>
		<select id="sk_bl_days" name="sk_bl_days">
			<option<?php echo $selecteddays1; ?>>1</option>
			<option<?php echo $selecteddays2; ?>>2</option>
			<option<?php echo $selecteddays5; ?>>5</option>
			<option<?php echo $selecteddays7; ?>>7</option>
			<option<?php echo $selecteddays14; ?>>14</option>
			<option<?php echo $selecteddays0; ?> value="0"><?php _e('Forever','sk'); ?></option>
		</select>
</td>
</tr>

<tr>
	<td>
	<?php
			_e('Max pending messages from blacklisted PC','sk'); ?>:
		</td><td>
		<select id="sk_bl_maxpending" name="sk_bl_maxpending">
			<option<?php echo $selectedmaxpending0; ?> value="0"><?php _e('None', 'sk');?></option>
			<option<?php echo $selectedmaxpending1; ?>>1</option>
			<option<?php echo $selectedmaxpending2; ?>>2</option>
			<option<?php echo $selectedmaxpending5; ?>>5</option>
			<option<?php echo $selectedmaxpending10; ?>>10</option>
		</select>
</td>
</tr>

<tr>
	<td>
	<?php
			_e('Max number of messages a user can send each day','sk'); ?>:
		</td><td>
		<select id="sk_maxperday" name="sk_maxperday">
			<option<?php echo $selectedmaxperday0; ?> value="0"><?php _e('Unlimited', 'sk');?></option>
			<option<?php echo $selectedmaxperday1; ?>>1</option>
			<option<?php echo $selectedmaxperday2; ?>>2</option>
			<option<?php echo $selectedmaxperday5; ?>>5</option>
			<option<?php echo $selectedmaxperday10; ?>>10</option>
		</select>
</td>
</tr>

<tr>
	<td colspan='2'><input type="hidden" class="checkbox" id="sk_rss" name="sk_rss" value="<?php
			echo $rss; ?>" /><input id="sk_title" name="sk_title" type="hidden" size="30" value="<?php echo $title; ?>" /><input type="hidden" name="sk_items" style="width: 30px;" value="<?php echo $items; ?>"><input type="hidden" name="sk-submit" value="true"><input type="submit" name="Submit" class="button-primary" value="<?php _e('Save Changes') ?>" /></td>
</tr>
</table>
	</form>
</div>
