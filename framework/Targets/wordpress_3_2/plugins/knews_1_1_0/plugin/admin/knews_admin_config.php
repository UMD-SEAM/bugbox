<?php

global $Knews_plugin, $knewsOptions;

if ($Knews_plugin->get_safe('tab')=='custom') {
	
	$languages = $Knews_plugin->getLangs(true);	
	$KnewsDefaultMessages=$Knews_plugin->get_default_messages();
	
	if (isset($_POST['update_KnewsAdminCustom'])) {
		
		foreach($KnewsDefaultMessages as $CustomMessage) {
			foreach($languages as $l) {

				if ($Knews_plugin->post_safe('reset_all_messages') == '1') {
	
					$reset = $Knews_plugin->get_custom_text($CustomMessage['name'],$l['localized_code'], true);
				
				} else {

					if (isset($_POST['custom_lang_' . $CustomMessage['name'] . '_' . $l['localized_code']])) {
						
						$message = $_POST['custom_lang_' . $CustomMessage['name'] . '_' . $l['localized_code']];
						$message = str_replace('\"','"',$message);
						$message = str_replace("\'","'",$message);
						
						$compat_lang = str_replace('-','_',$l['localized_code']);
						update_option('knews_custom_' . $CustomMessage['name'] . '_' . $compat_lang, $message);
					}
				}
			}
		}

		if ($Knews_plugin->post_safe('reset_all_messages') == '1') {
			echo '<div class="updated"><p><strong>Messages reseted to defaults.</strong></p></div>';
		} else {
			echo '<div class="updated"><p><strong>Guardat.</strong></p></div>';
		}
	}
	
	?>
	<link href="<?php echo KNEWS_URL; ?>/admin/styles.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript">
	function view_lang(n_custom, n_lang) {
		jQuery('div.pestanyes_'+n_custom+' a').removeClass('on');
		jQuery('a.link_'+n_custom+'_'+n_lang).addClass('on');
	
		target='div.pregunta_'+n_custom+' textarea.on';
		save_height=jQuery(target).innerHeight() + parseInt(jQuery(target).css('marginTop'), 10) + parseInt(jQuery(target).css('marginBottom'), 10);
		
		save_width=jQuery(target).innerWidth() + parseInt(jQuery(target).css('marginLeft'), 10) + parseInt(jQuery(target).css('marginRight'), 10);
			
		jQuery('div.pregunta_'+n_custom+' textarea').css('display','none').removeClass('on');
		jQuery('textarea.custom_lang_'+n_custom+'_'+n_lang).css({display:'block', height:save_height, width:save_width}).addClass('on');
	}
	</script>
	
<div class=wrap>
			<form method="post" action="admin.php?page=knews_config&tab=custom">
				<div class="icon32" style="background:url(<?php echo KNEWS_URL; ?>/images/icon32.png) no-repeat 0 0;"><br></div><h2 class="nav-tab-wrapper">
					<a class="nav-tab" href="admin.php?page=knews_config"><?php _e('Main options','knews'); ?></a>
					<a class="nav-tab nav-tab-active" href="admin.php?page=knews_config&tab=custom"><?php _e('Customised messages','knews'); ?></a>
				</h2>
				<?php
				$n_custom=0;
				foreach($KnewsDefaultMessages as $CustomMessage) {
					$n_custom++;
					echo '<h3>' . $CustomMessage['label'] .'</h3>';
					
					echo '<div class="pestanyes pestanyes_' . $n_custom . '">';
					$n_lang=0;
					foreach($languages as $l){
						$n_lang++;
						echo '<a href="#" class="link_' . $n_custom . '_' . $n_lang . ' ' . (($n_lang==1)? ' on' : '') . '" onclick="view_lang(' . $n_custom . ',' . $n_lang . '); return false;">' . $l['native_name'] . '</a>';
					}
					echo '</div>';
					
					echo '<div class="pregunta pregunta_' . $n_custom . '">';
					$n_lang=0;
					foreach($languages as $l){
						$n_lang++;
						echo '<textarea class="custom_lang_' . $n_custom . '_' . $n_lang . (($n_lang==1)? ' on' : '') . '" name="custom_lang_' . $CustomMessage['name'] . '_' . $l['localized_code'] . '" style="width:100%;';
						if ($Knews_plugin->get_custom_text('text_direction',$l['localized_code'])=='rtl' &&
							($CustomMessage['name'] != 'text_direction' && $CustomMessage['name'] != 'default_alignment' && $CustomMessage['name'] != 'inverse_alignment')) echo ' unicode-bidi:bidi-override; direction:rtl;';
						echo '">';
						echo $Knews_plugin->get_custom_text($CustomMessage['name'],$l['localized_code']);
						echo '</textarea>';
					}
	
					echo '</div><hr />';
				}
				?>
				<p><input type="checkbox" name="reset_all_messages" id="reset_all_messages" value="1" /> <?php _e('Reset all messages to default values (all languages at once)','knews'); ?></p>
				<div class="submit">
					<input type="submit" name="update_KnewsAdminCustom" id="update_KnewsAdminCustom" value="<?php _e('Save','knews'); ?>" class="button-primary" />
				</div>
			</form>
		</div>
<?php
} else {
	//$knewsOptions = $this->getAdminOptions();
	
	if (isset($_POST['update_KnewsAdminSettings'])) {
		if (isset($_POST['multilanguage_knews'])) $knewsOptions['multilanguage_knews'] = $_POST['multilanguage_knews'];
		if (isset($_POST['from_mail_knews'])) $knewsOptions['from_mail_knews'] = $_POST['from_mail_knews'];
		if (isset($_POST['from_name_knews'])) $knewsOptions['from_name_knews'] = $_POST['from_name_knews'];
		if (isset($_POST['smtp_knews'])) $knewsOptions['smtp_knews'] = $_POST['smtp_knews'];
		if (isset($_POST['smtp_host_knews'])) $knewsOptions['smtp_host_knews'] = $_POST['smtp_host_knews'];
		if (isset($_POST['smtp_port_knews'])) $knewsOptions['smtp_port_knews'] = $_POST['smtp_port_knews'];
		if (isset($_POST['smtp_user_knews'])) $knewsOptions['smtp_user_knews'] = $_POST['smtp_user_knews'];
		if (isset($_POST['smtp_pass_knews'])) $knewsOptions['smtp_pass_knews'] = $_POST['smtp_pass_knews'];
		if (isset($_POST['smtp_secure_knews'])) $knewsOptions['smtp_secure_knews'] = $_POST['smtp_secure_knews'];
		if (isset($_POST['knews_cron'])) $knewsOptions['knews_cron'] = $_POST['knews_cron'];
		if (isset($_POST['write_logs_knews'])) {
			$knewsOptions['write_logs'] = $_POST['write_logs_knews'];
		} else {
			$knewsOptions['write_logs'] = 'no';
		}

		$knewsOptions['config_knews'] = 'yes';

		if ($Knews_plugin->post_safe('reset_alerts_knews')=='1') {

			$knewsOptions['no_warn_cron_knews'] = 'no';
			$knewsOptions['no_warn_ml_knews'] = 'no';
			$knewsOptions['config_knews'] = 'no';
		}

		update_option($Knews_plugin->adminOptionsName, $knewsOptions);
	
		echo '<div class="updated"><p><strong>' . __('Saved.','knews') . '</strong></p></div>';

		if (!wp_next_scheduled('knews_wpcron_function_hook')) {
			if ($knewsOptions['knews_cron']=='cronwp') {
				wp_schedule_event( time(), 'knewstime', 'knews_wpcron_function_hook' );
			}
		} else {
			if ($knewsOptions['knews_cron']!='cronwp') {
				wp_clear_scheduled_hook('knews_wpcron_function_hook');
			}
		}

	}

?>
	<div class=wrap>
		<form method="post" action="admin.php?page=knews_config">
			<div class="icon32" style="background:url(<?php echo KNEWS_URL; ?>/images/icon32.png) no-repeat 0 0;"><br></div><h2 class="nav-tab-wrapper">
				<a class="nav-tab nav-tab-active" href="admin.php?page=knews_config"><?php _e('Main options','knews'); ?></a>
				<a class="nav-tab" href="admin.php?page=knews_config&tab=custom"><?php _e('Customised messages','knews'); ?></a>
			</h2>
			<h3><?php _e('Multilingual','knews'); ?></h3>
			<?php 
				if (!$Knews_plugin->check_multilanguage_plugin() && $knewsOptions['multilanguage_knews'] != 'off') {
			 ?>
				<div class="error">
					<p><?php _e('The multilanguage plugin has stopped working.','knews'); ?></p>
				</div>
			<?php
				} else {
					if (!$Knews_plugin->have_wpml() && !$Knews_plugin->have_qtranslate()) {
			?>				
				<div class="updated">
					<p><?php _e('No mulilanguage plugins detected. Knews works with qTranslate and WPML plugins','knews'); ?></p>
					<p><a href="http://www.qianqin.de/qtranslate/" target="_blank"><?php _e('qTranslate: free plugin','knews'); ?></a></p>
					<p><a href="http://wpml.org/" target="_blank"><?php _e('WPML: commercial plugin','knews'); ?></a></p>
					<p><?php _e('Note: Knews authors have no relationship with qTranslate or WMPL authors.','knews'); ?></p>
				</div>
			<?php 
					}
				}
				if ($Knews_plugin->have_wpml()) {
			?>
					<p><input type="radio" name="multilanguage_knews" value="wpml" id="multilanguage_knews_wpml"<?php if ($knewsOptions['multilanguage_knews']=='wpml') echo ' checked="checked"'; ?> /> <?php _e('Use the WPML defined languages to operate Knews in multilanguage mode','knews'); ?></p>
			<?php
				}
				if ($Knews_plugin->have_qtranslate()) {
			?>
					<p><input type="radio" name="multilanguage_knews" value="qt" id="multilanguage_knews_qt"<?php if ($knewsOptions['multilanguage_knews']=='qt') echo ' checked="checked"'; ?> /> <?php _e('Use the qTranslate defined languages to operate Knews in multilanguage mode','knews'); ?></p>
			<?php
				}
			?>
				<p><input type="radio" name="multilanguage_knews" value="off" id="multilanguage_knews_off"<?php if ($knewsOptions['multilanguage_knews']=='off') echo ' checked="checked"'; ?> /> <?php _e('Operate Knews as monolingual','knews'); ?></p>

			<hr />
			<h3><?php _e('CRON','knews'); ?></h3>
			<h3><input type="radio" name="knews_cron" value="cronjob"<?php if ($knewsOptions['knews_cron']=='cronjob') echo ' checked="checked"'; ?> /> <?php _e('Use the CRON server (recommended)','knews'); ?> <a href="<?php _e('tutorial_cron_url','knews'); ?>" style="background:url(<?php echo KNEWS_URL; ?>/images/help.png) no-repeat 5px 0; padding:3px 0 3px 30px; color:#0646ff; font-size:15px; font-weight:normal;" target="_blank"><?php _e('Configure CRON tutorial','knews'); ?></a></h3>
			<?php
				$last_cron_time=$Knews_plugin->get_last_cron_time();
				$now_time = time();
				if ($now_time - $last_cron_time < 800) {
			?>
				<div class="updated">
					<p><strong><?php _e('CRON is properly configured','knews'); ?></strong></p>
					<p><?php _e('The last execution was done','knews'); ?> <?php echo round(($now_time - $last_cron_time) / 60); ?> <?php _e('minutes ago','knews');?></p>
				</div>
			<?php
				} else {
			?>
				<div class="<?php if ($knewsOptions['knews_cron']=='cronjob') { echo ' error';} else {echo ' updated';} ?>">
					<p><strong>
					<?php if ($last_cron_time == 0) {
						echo __('CRON has not yet been configured','knews') . '</strong></p>';
					} else {
						echo __('CRON has stopped working.','knews') . '</strong></p>';
						echo '<p>' . __('The last execution was done','knews') . ' ' . round(($now_time - $last_cron_time) / 60) . ' ' . __('minutes ago','knews') . '</p>';
					}
					//if (ini_get('safe_mode')) echo '<p><strong>' . __('Please note: PHP Safe Mode enabled. Without cron, this directive will fail the bulk of mails.','knews') . '</strong></p>';
					?>
					<p><?php _e('Instructions for setting up CRON','knews');?>:</p>
					<?php
					if( is_multisite() ) {
						echo '<p><strong>' . __('Multisite detected. Only one instance of knews_cron.php must be called for all websites.','knews') . '</strong></p>';
						//switch_to_blog($Knews_plugin->KNEWS_MAIN_BLOG_ID);
						$cron_main_url = $Knews_plugin->get_main_plugin_url() . '/knews/direct/knews_cron.php';
						//restore_current_blog();
					} else {
						$cron_main_url = KNEWS_URL . '/direct/knews_cron.php';
					}
					?>
					<p><?php _e('You must add this line in your webserver CRONTAB:','knews'); ?></p>
					<p><strong>*/10 * * * * wget -q -O /dev/null <?php echo $cron_main_url; ?></strong></p>
					<p><?php printf( __('The file location is: %s','knews'),  KNEWS_DIR . '/direct/knews_cron.php'); ?></p>
				</div>
			<?php
				}
			?>
			<h3 style="margin-bottom:0"><input type="radio" name="knews_cron" value="cronwp"<?php if ($knewsOptions['knews_cron']=='cronwp') echo ' checked="checked"'; ?> /> <?php echo __("Use WordPress's built-in CRON framework.",'knews') . '</h3><p style="margin-top:0;">' .  __('This option no requires configuration, but in less traffic sites can be slow to submit','knews'); ?></h3>
			<h3 style="margin-bottom:0"><input type="radio" name="knews_cron" value="cronjs"<?php if ($knewsOptions['knews_cron']=='cronjs') echo ' checked="checked"'; ?> /> <?php echo __('Use the JavaScript CRON emulation.','knews') . '</h3><p style="margin-top:0;">' .   __("This option requires you to keep a window open during submission and does not allow deferred submits",'knews'); ?></p>
			<hr />
			<h3><?php _e('Sender','knews');?></h3>
			<table cellpadding="0" cellspacing="0" border="0">
			<tr><td><?php _e('Sender e-mail','knews');?>:</td><td><input type="text" name="from_mail_knews" id="from_mail_knews" class="regular-text" value="<?php echo $knewsOptions['from_mail_knews']; ?>" /></td></tr>
			<tr><td><?php _e('Sender name','knews');?>:</td><td><input type="text" name="from_name_knews" id="from_name_knews" class="regular-text" value="<?php echo $knewsOptions['from_name_knews']; ?>" /></td></tr></table>
			<hr />
			<script type="text/javascript">
			jQuery(document).ready( function () {
				jQuery('input#test_smtp').click(function() {
					jQuery('div.resultats_test').html('<p><blink><?php _e('Sending','knews');?>...</blink></p>');
					jQuery.ajax({
						data: {
							email_test: jQuery('input#email_test').val(),
							from_mail_knews: jQuery('input#from_mail_knews').val(),
							from_name_knews: jQuery('input#from_name_knews').val(),
							smtp_host_knews: jQuery('input#smtp_host_knews').val(),
							smtp_port_knews: jQuery('input#smtp_port_knews').val(),
							smtp_user_knews: jQuery('input#smtp_user_knews').val(),
							smtp_pass_knews: jQuery('input#smtp_pass_knews').val(),
							smtp_secure_knews: jQuery('select#smtp_secure_knews').val()
						},
						type: "POST",
						cache: false,
						url: "<?php echo KNEWS_URL ?>/direct/test_smtp.php",
						success: function(data) {
							jQuery('div.resultats_test').html(data);
						}
					});
					return false;
				});
			});
			</script>
			<h3><?php _e('Submit method','knews');?></h3>
			<p><input type="radio" name="smtp_knews" value="0"<?php if ($knewsOptions['smtp_knews']!='1') echo ' checked="checked"'; ?> /> <?php _e('E-mails sent internally using WordPress (wp_mail() function)','knews');?></p>
			<p><input type="radio" name="smtp_knews" value="1"<?php if ($knewsOptions['smtp_knews']=='1') echo ' checked="checked"'; ?> /> <?php _e('Send e-mails using SMTP (recommended)','knews');?> <a href="<?php _e('tutorial_smtp_url','knews');?>" style="background:url(<?php echo KNEWS_URL; ?>/images/help.png) no-repeat 5px 0; padding:3px 0 3px 30px; color:#0646ff; font-size:15px;" target="_blank"><?php _e('Configure SMTP tutorial','knews');?></a></p>
			<div style="width:420px; float:left; padding-left:30px;">
				<table cellpadding="0" cellspacing="0" border="0">
				<tr><td><?php _e('Host SMTP','knews');?>:</td><td><input type="text" name="smtp_host_knews" id="smtp_host_knews" class="regular-text" autocomplete="off" value="<?php echo $knewsOptions['smtp_host_knews']; ?>" /></td></tr>
				<tr><td><?php _e('Port SMTP','knews');?>:</td><td><input type="text" name="smtp_port_knews" id="smtp_port_knews" style="width:100px" autocomplete="off" value="<?php echo $knewsOptions['smtp_port_knews']; ?>" /></td></tr>
				<tr><td><?php _e('SMTP User','knews');?>: *</td><td><input type="text" name="smtp_user_knews" id="smtp_user_knews" class="regular-text" autocomplete="off" value="<?php echo $knewsOptions['smtp_user_knews']; ?>" /></td></tr>
				<tr><td><?php _e('SMTP Password','knews');?>: *</td><td><input type="password" name="smtp_pass_knews" id="smtp_pass_knews" class="regular-text" autocomplete="off" value="<?php echo $knewsOptions['smtp_pass_knews']; ?>" /></td></tr>
				<tr><td><?php _e('SMTP Secure','knews');?>: </td><td><select name="smtp_secure_knews" id="smtp_secure_knews" autocomplete="off" >
					<option value=""<?php if ($knewsOptions['smtp_secure_knews']=='') echo ' selected="selected"'; ?>>none</option>
					<option value="tls"<?php if ($knewsOptions['smtp_secure_knews']=='tls') echo ' selected="selected"'; ?>>tls</option>
					<option value="ssl"<?php if ($knewsOptions['smtp_secure_knews']=='ssl') echo ' selected="selected"'; ?>>ssl</option></select></td></tr></table>
				<p>* <?php _e('Pay attention: If your SMTP server is anonymous leave SMTP User and SMTP Password fields blank','knews');?></p>
			</div>
			<div style="width:300px; float:left; padding-left:20px;">
				<p><?php _e('Before enabling the sending SMTP, enter the values and your e-mail and then click on TEST button','knews');?>:</p>
				<p><?php _e('Recipient','knews'); ?>: <input type="text" name="email_test" id="email_test" class="regular-text" /></p>
				<div class="submit">
					<div class="resultats_test"></div>
					<input type="button" name="test_smtp" id="test_smtp" value="<?php _e('Test SMTP config','knews');?>" />
				</div>
			</div>
			<div style="clear:both"></div>
			<div class="updated"><p><?php printf(__('The e-mails submited to any e-mail terminated with @knewstest.com (like testing001@knewstest.com or xxx@knewstest.com) will be submited to: %s for your testing purposes','knews'), get_option('admin_email')); ?></p></div>
			<hr />

			<h3><?php _e('Alerts and logs','knews'); ?></h3>
			<p><input type="checkbox" name="reset_alerts_knews" value="1" id="reset_alerts_knews" /> <?php _e('Reset all alerts','knews'); ?></p>
			<p><input type="checkbox" name="write_logs_knews" value="yes" id="write_logs_knews"<?php if ($knewsOptions['write_logs']=='yes') echo ' checked="checked"'; ?> /> <?php _e('Write logs (in /wp-content/plugins/knews/tmp directory) in submits','knews'); ?></p>

			<div class="submit">
				<input type="submit" name="update_KnewsAdminSettings" id="update_KnewsAdminSettings" value="<?php _e('Save','knews');?>" class="button-primary" />
			</div>
		</form>
	</div>
<?php
}
?>