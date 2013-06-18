<div class="wrap">
	<form name="form1" method="post" action="<?php echo remove_query_arg(array('mode', 'id')); ?>">
		<div id="icon-edit-comments" class="icon32"><br /></div>
		<h2><?php _e( 'Edit Comment', 'sk' );?></h2>
		<div id="poststuff" class="metabox-holder has-right-sidebar">
			<div id="side-info-column" class="inner-sidebar">
				<div id="submitdiv" class="stuffbox" >
					<h3><span class='hndle'><?php _e('Status'); ?>:</span></h3>
					<div class="inside">
						<div class="submitbox" id="submitcomment">
							<div id="minor-publishing">
								<div id="misc-publishing-actions">
									<div class="misc-pub-section curtime misc-pub-section-last">
										<div class="misc-pub-section" id="comment-status-radio">
											<label class="approved"><input type="radio" <?php if ($status==SK_HAM) echo "checked=\"checked\"";?> name="comment_status" value="<?php echo SK_HAM; ?>" /><?php echo _n('Approved', 'Approved', 1, 'sk');?></label><br />
											<label class="spam"><input type="radio" <?php if ($status==SK_SPAM) echo "checked=\"checked\"";?> name="comment_status" value="<?php echo SK_SPAM; ?>" /><?php _e('Spam', 'sk');?></label><br />
											<label class="blacklisted"><input type="radio" <?php if ($status==SK_BLACK) echo "checked=\"checked\"";?> name="comment_status" value="<?php echo SK_BLACK; ?>" /><?php echo _n('Rejected', 'Rejected', 1, 'sk');?></label>
										</div>
										<div class="misc-pub-section curtime misc-pub-section-last">
											<span id="timestamp">Enviado el: <b><?php echo $date; ?></b></span>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div id="major-publishing-actions">
						<div id="delete-action">
							<input type="submit" name="cancel" value="<?php _e( 'Cancel', 'sk' );?>" class="button-primary" />
						</div>
						<div id="publishing-action">
							<input type="submit" name="submit" value="<?php _e( 'Update', 'sk' );?>" class="button-primary" />
						</div>
						<div class="clear"></div>
					</div>
				</div>
			</div>
			<div id="post-body" class="has-sidebar">
				<div id="post-body-content" class="has-sidebar-content">
					<div id="namediv" class="stuffbox">
						<h3><label for="name"><?php _e('Comment', 'sk'); ?></label></h3>
						<div class="inside">		
							<input type="hidden" name="mode_x" value="<?php echo $mode; ?>_x"><input type="hidden" name="sk_id" value="<?php echo $id; ?>">
							<table>
								<tr>
									<td><?php _e("Author", 'sk' ); ?>:</td>
									<td><input type="text" name="sk_alias" value="<?php echo $alias; ?>" /></td>
								</tr>
								<tr>
									<td><?php _e("E-mail", 'sk' ); ?>:</td>
									<td><input type="text" name="sk_email" value="<?php echo $email; ?>" /></td>
								</tr>
								<tr>
									<td valign="top"><?php _e("Comment", 'sk' ); ?>:</td>
									<td><textarea name="sk_comment" rows="5" style="width: 98%;"><?php echo $comment; ?></textarea></td>
								</tr>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>
