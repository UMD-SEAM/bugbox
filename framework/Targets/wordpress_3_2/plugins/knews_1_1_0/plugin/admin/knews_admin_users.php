<?php
	global $wpdb;
	global $Knews_plugin;
	
	$languages = $Knews_plugin->getLangs(true);

	$query = "SELECT id, name FROM " . KNEWS_LISTS ;
	$lists_name = $wpdb->get_results( $query );
	$lists_indexed=array();
	foreach ($lists_name as $ln) {
		$lists_indexed[$ln->id] = $ln->name;
	}
	
	if ($Knews_plugin->get_safe('da')=='activate') {
		$query = "UPDATE ".KNEWS_USERS." SET state='2' WHERE id=" . intval($Knews_plugin->get_safe('uid'));
		$result=$wpdb->query( $query );
		echo '<div class="updated"><p>' . __('User data updated','knews') . '</p></div>';
	}

	if ($Knews_plugin->get_safe('da')=='block') {
		$query = "UPDATE ".KNEWS_USERS." SET state='3' WHERE id=" . intval($Knews_plugin->get_safe('uid'));
		$result=$wpdb->query( $query );
		echo '<div class="updated"><p>' . __('User data updated','knews') . '</p></div>';
	}

	if ($Knews_plugin->get_safe('da')=='delete') {
		$query="DELETE FROM " . KNEWS_USERS . " WHERE id=" . intval($Knews_plugin->get_safe('uid'));
		$results = $wpdb->query( $query );
		echo '<div class="updated"><p>' . __('User deleted','knews') . '</p></div>';
	}

	if (isset($_POST['action'])) {
		if ($_POST['action']=='update_user') {
			
			$email = mysql_real_escape_string($_POST['email']);
			$state = mysql_real_escape_string($_POST['state']);
			$lang = mysql_real_escape_string($_POST['lang']);
			$id=intval($_POST['id_user']);

			$query = "UPDATE ".KNEWS_USERS." SET email='" . $email . "', state='" . $state . "', lang='" . $lang . "' WHERE id=" . $id;
			$result=$wpdb->query( $query );
			
			$query="DELETE FROM " . KNEWS_USERS_PER_LISTS . " WHERE id_user=" . $id;
			$results = $wpdb->query( $query );

			foreach ($lists_name as $ln) {
				if (isset($_POST['list_'.$ln->id])) {
					if ($_POST['list_'.$ln->id]=='1') {

						$query="INSERT INTO " . KNEWS_USERS_PER_LISTS . " (id_user, id_list) VALUES (" . $id . ", " . $ln->id . ")";
						$results = $wpdb->query( $query );
						
					}
				}
			}
			
			echo '<div class="updated"><p>' . __('User data updated','knews') . '</p></div>';
			
		} else if ($_POST['action']=='delete_users') {
			
			$query = 'SELECT id FROM ' . KNEWS_USERS;
			$result=$wpdb->get_results( $query );
			
			foreach ($result as $look_user) {

				if (intval($Knews_plugin->post_safe('batch_' . $look_user->id)) == 1) {

					$query= 'DELETE FROM ' . KNEWS_USERS . ' WHERE id=' . $look_user->id;
					$delete=$wpdb->query( $query );
				}
			}
			
			echo '<div class="updated"><p>' . __('User data updated','knews') . '</p></div>';
			
		} else if ($_POST['action']=='add_user') {
		
			$lang = mysql_real_escape_string($_POST['lang']);
			$email = mysql_real_escape_string($_POST['email']);
			$date = $Knews_plugin->get_mysql_date();
			$confkey = $Knews_plugin->get_unique_id();
			$id_list_news = intval($_POST['id_list_news']);
			$submit_confirm = intval($_POST['submit_confirm']);
			
			if ($submit_confirm==1) {
				$state='1';
			} else {
				$state='2';
			}	

			if ($Knews_plugin->validEmail($email)) {
				
				$query = "SELECT * FROM " . KNEWS_USERS . " WHERE email='" . $email . "'";
				$user_found = $wpdb->get_results( $query );
	
	
				if (count($user_found)==0) {
					$query = "INSERT INTO " . KNEWS_USERS . " (email, lang, state, joined, confkey) VALUES ('" . $email . "','" . $lang . "', $state, '" . $date . "','" . $confkey . "');";
					$results = $wpdb->query( $query );
	
					if ($results) {
						$query = "INSERT INTO " . KNEWS_USERS_PER_LISTS . " (id_user, id_list) VALUES (LAST_INSERT_ID(), " . $id_list_news . ");";
						$results = $wpdb->query( $query );

						if ($submit_confirm) {
							
							$lang_locale = $Knews_plugin->localize_lang($languages, $lang);
												
							if ($Knews_plugin->submit_confirmation ($email, $confkey, $lang_locale)) {
								echo '<div class="updated"><p>' . __('The user has been added and an e-mail confirmation has been sent','knews') . '</p></div>';
							} else {
								echo '<div class="error"><p><strong>' . __('Error','knews') . ':</strong> ' . __('The user has been added but an error occurred in sending e-mail confirmation','knews') . '</p></div>';
							}
						} else {
							echo '<div class="updated"><p>' . __('The user has been added and from now on he will receive the newsletters','knews') . '</p></div>';
						}
					} else {
						echo '<div class="error"><p><strong>' . __('Error','knews') . ':</strong> ' . __('The user has not been added','knews') . '</p></div>';
					}
				} else {
					echo '<div class="error"><p><strong>' . __('Error','knews') . ':</strong> ' . __('The user was already introduced','knews') . '</p></div>';
				}
			} else {
				echo '<div class="error"><p><strong>' . __('Error','knews') . ':</strong> ' . __('Wrong e-mail','knews') . '.</p></div>';
			}
		}
	}
?>
	<div class=wrap>
<?php 
	if ($Knews_plugin->im_pro()) {
?>
		<div class="icon32" style="background:url(<?php echo KNEWS_URL; ?>/images/icon32.png) no-repeat 0 0;"><br></div><h2 class="nav-tab-wrapper"><a href="admin.php?page=knews_users" class="nav-tab nav-tab-active"><?php _e('Subscribers','knews'); ?></a><a href="admin.php?page=knews_users&tab=extra_fields" class="nav-tab"><?php _e('Extra fields','knews'); ?></a></h2>
<?php 
	} else {
?>
		<div class="icon32" style="background:url(<?php echo KNEWS_URL; ?>/images/icon32.png) no-repeat 0 0;"><br></div><h2><?php _e('Subscribers','knews'); ?></h2>
<?php
	}
			$edit_user=0;
			if (isset ($_GET['edit_user'])) $edit_user=intval($_GET['edit_user']);
			
			if ($edit_user!=0) {
				//Edit user
				$query = "SELECT id, email, state, lang FROM " . KNEWS_USERS . ' WHERE id=' . $edit_user;
				$users = $wpdb->get_results( $query );
	
				if (count($users) != 0) {
					?>
					<form action="admin.php?page=knews_users" method="post">
					<input type="hidden" name="action" id="action" value="update_user" />
					<input type="hidden" name="id_user" id="id_user" value="<?php echo $edit_user; ?>" />
					<p>&nbsp;</p>
					<table class="wp-list-table widefat" style="width:400px;">
						<thead><th><?php _e('Field','knews');?></th><th><?php _e('Value','knews');?></th></thead>
						<tr><td>E-mail:</td><td><input type="text" name="email" id="email" value="<?php echo $users[0]->email; ?>" class="regular-text" /></td>
						<?php
						$lang_listed = false;
						foreach ($languages as $l) {
							if ($l['language_code'] == $users[0]->lang) $lang_listed = true;
						}
						
						if (!$lang_listed) $languages[$users[0]->lang] = array ('translated_name'=>  __('Inactive language','knews') . ' (' . $users[0]->lang . ')', 'language_code'=>$users[0]->lang);
						
						if (count($languages) > 1) {
							
							echo '<tr><td>' . __('Language','knews') . ':</td><td><select name="lang" id="lang">';
							foreach($languages as $l){
								echo '<option value="' . $l['language_code'] . '"' . ( ($users[0]->lang==$l['language_code']) ? ' selected="selected"' : '' ) . '>' . $l['translated_name'] . '</option>';
							}
							echo '</select></td></tr>';
			
						} else if (count($languages) == 1) {
							foreach ($languages as $l) {
								echo '<input type="hidden" name="lang" id="lang" value="' . $l['language_code'] . '" />';
							}
						} else {
							echo "<p>" . __('Error','knews') . ": " . __('Language not detected!','knews') . "</p>";
						}
						?>
						<tr><td><?php _e('State','knews');?>:</td><td><select name="state" id="state">
							<option value="1"<?php if ($users[0]->state=='1') echo ' selected="selected"'; ?>><?php _e('not confirmed','knews');?></option>
							<option value="2"<?php if ($users[0]->state=='2') echo ' selected="selected"'; ?>><?php _e('confirmed','knews');?></option>
							<option value="3"<?php if ($users[0]->state=='3') echo ' selected="selected"'; ?>><?php _e('blocked','knews');?></option>
						</select></td></tr>
						<?php
						?>
						<tr><td colspan="2"><?php _e('Subscriptions','knews');?>:</td></tr>
						<?php
						$query = "SELECT id_list FROM " . KNEWS_USERS_PER_LISTS . " WHERE id_user=" . $edit_user;
						$lists = $wpdb->get_results( $query );
						foreach ($lists_name as $ln) {
							$active=false;
							foreach ($lists as $list_user) {
								if ($list_user->id_list==$ln->id) $active=true;
							}
							echo '<tr><td>&nbsp;</td><td><input type="checkbox" value="1" name="list_' . $ln->id . '" id="list_' . $ln->id . '"' . (($active) ? ' checked="checked"' : '') . '>' . $ln->name . '</td></tr>';
						}
						?>
						<tfoot><th><?php _e('Field','knews');?></th><th><?php _e('Value','knews');?></th></tfoot>
					</table>
					<div class="submit">
						<input type="submit" value="<?php _e('Update user','knews');?>" class="button-primary" />&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="<?php _e('Go back','knews');?>" onclick="window.history.go(-1)" />
					</div>
					</form>
					<?php	
				} else {
					echo '<p>' . __('User not found','knews') . '</p>';
				}
				
			} else {
				//List users
				
				$filter_list = intval($Knews_plugin->get_safe('filter_list', 0));
				$filter_state = intval($Knews_plugin->get_safe('filter_state', 0));
				$search_user = mysql_real_escape_string($Knews_plugin->get_safe('search_user', ''));
				$paged = intval($Knews_plugin->get_safe('paged', 1));
								
				$results_per_page=20;
				
				$link_params='admin.php?page=knews_users&filter_list='.$filter_list.'&filter_state='.$filter_state.'&search_user='.$search_user.'&paged=';
				
				$query = "SELECT " . KNEWS_USERS . ".id, " . KNEWS_USERS . ".email, " . KNEWS_USERS . ".state, " . KNEWS_USERS . ".lang FROM " . KNEWS_USERS;

				if ($filter_list != 0) {
					$query .= ", " . KNEWS_USERS_PER_LISTS . " WHERE " . KNEWS_USERS . ".id = " . KNEWS_USERS_PER_LISTS . ".id_user AND " . KNEWS_USERS_PER_LISTS . ".id_list=" . $filter_list;
				
				} else if ($search_user != '') {
					$query .= " WHERE email LIKE '%" . $search_user . "%'";
				}
				
				if ($filter_state != 0) {
					if ($filter_list != 0 || $search_user != '') {
						$query .= " AND ";
					} else {
						$query .= " WHERE ";
					}
					$query .= KNEWS_USERS . ".state=" . $filter_state;
				}
				//echo '*' . $query . '*';
				$users = $wpdb->get_results( $query );
	
				?>
				<div class="top">
					<div class="alignleft actions">
						<form action="admin.php" method="get">
							<input type="hidden" name="page" id="page" value="knews_users" />
							<p><?php _e('Filter by mailing list','knews'); ?>: <select name="filter_list" id="filter_list">
							<option value="0"<?php if ($filter_list==0) echo ' selected="selected"'; ?>><?php _e('All','knews');?></option>
							<?php
								foreach ($lists_name as $ln) {
									echo '<option value="' . $ln->id . '"' . (($filter_list == $ln->id) ? ' selected="selected"' : '') . '>' . $ln->name . '</option>';
								}
							?>
							</select>&nbsp;&nbsp;&nbsp;
							<?php _e('Filter by state','knews'); ?>: <select name="filter_state" id="filter_state">
								<option value="0"<?php if ($filter_state==0) echo ' selected="selected"'; ?>><?php _e('All','knews'); ?></option>
								<option value="1"<?php if ($filter_state==1) echo ' selected="selected"'; ?>><?php _e('Not confirmed','knews'); ?></option>
								<option value="2"<?php if ($filter_state==2) echo ' selected="selected"'; ?>><?php _e('Confirmed','knews'); ?></option>
								<option value="3"<?php if ($filter_state==3) echo ' selected="selected"'; ?>><?php _e('Blocked','knews'); ?></option>
							</select>
							<input type="submit" value="<?php _e('Filter','knews'); ?>" class="button-secondary" /></p>
						</form>
					</div>
					<div class="alignright actions">
						<form action="admin.php" method="get">
							<input type="hidden" name="page" id="page" value="knews_users" />
							<p><?php _e('Find','knews');?>: <input type="text" name="search_user" id="search_user" value="<?php echo $search_user; ?>" /><input type="submit" value="<?php _e('Find','knews');?>" class="button-secondary " /></p>
						</form>
					</div>
					<br class="clear">
				</div>
				<?php
				if (count($users) != 0) {
				?>
				<form action="<?php echo $link_params . $paged; ?>" method="post">
				<?php
					$alt=false;
					echo '<table class="widefat"><thead><tr><th class="manage-column column-cb check-column"><input type="checkbox" /></th><th>E-mail</th>';

					echo '<th>' . __('Language','knews') . '</th><th>' . __('State','knews') . '</th><th>' . __('Subscriptions','knews') . '</th></tr></thead><tbody>';
	
					$results_counter=0;
					foreach ($users as $user) {
						$results_counter++;
						if ($results_per_page * ($paged-1)<$results_counter) {

							$query = "SELECT id_list FROM " . KNEWS_USERS_PER_LISTS . " WHERE id_user=" . $user->id;
							$lists = $wpdb->get_results( $query );
							
							echo '<tr class="' . $alt . '"><th class="check-column"><input type="checkbox" name="batch_' . $user->id . '" value="1"></th>' . 
							'<td><strong><a href="admin.php?page=knews_users&edit_user=' . $user->id . '">' . $user->email . '</a></strong>';
							
							echo '<div class="row-actions"><span><a title="' . __('Edit this user', 'knews') . '" href="admin.php?page=knews_users&edit_user=' . $user->id . '">' . __('Edit', 'knews') . '</a> | </span>';
							
							if ($user->state!=2) echo '<span><a href="' . $link_params . $paged . '&da=activate&uid=' . $user->id . '" title="' . __('Activate this user', 'knews') . '">' . __('Activate', 'knews') . '</a> | </span>';							
							if ($user->state!=3) echo '<span><a href="' . $link_params . $paged . '&da=block&uid=' . $user->id . '" title="' . __('Block this user', 'knews') . '">' . __('Block', 'knews') . '</a> | </span>';
							
							echo '<span class="trash"><a href="' . $link_params . $paged . '&da=delete&uid=' . $user->id . '" title="' . __('Delete definitely this user', 'knews') . '" class="submitdelete">' . __('Delete', 'knews') . '</a></span></div></td>';

							echo '<td>' . (($user->lang!='') ? $user->lang : '/') . '</td><td>';
							if ($user->state==1) echo '<img src="' . KNEWS_URL . '/images/yellow_led.gif" width="20" height="20" alt="No confirmat" /></td>';
							if ($user->state==2) echo '<img src="' . KNEWS_URL . '/images/green_led.gif" width="20" height="20" alt="Confirmat" /></td>';
							if ($user->state==3) echo '<img src="' . KNEWS_URL . '/images/red_led.gif" width="20" height="20" alt="Blocat" /></td>';
							echo '</td><td>';
							
							if (count($lists) != 0) {
								$first_comma=true;
								foreach ($lists as $list) {
									if (!$first_comma) echo ', ';
									if (isset($lists_indexed[$list->id_list])) {
										echo $lists_indexed[$list->id_list];
									} else {
										echo '<i>';
										_e('deleted list','knews');
										echo '</i>';
									}
									$first_comma=false;
								}
							}
							//echo '<td><input type="checkbox" value="1" name="user_delete_' . $user->id . '" id="user_delete_' . $user->id . '"></td>';
							echo '</tr>';
		
							$alt=!$alt;
							if ($results_counter == $results_per_page * $paged) break;
						}
					}
					echo '</tbody><tfoot><tr><th class="manage-column column-cb check-column"><input type="checkbox" /></th><th>E-mail</th>';

					echo '<th>' . __('Language','knews') . '</th><th>' . __('State','knews') . '</th><th>' . __('Subscriptions','knews') . '</th></tr></tfoot>';
					echo '</table>';
				?>
					<div class="submit">
						<select name="action">
							<option selected="selected" value=""><?php _e('Batch actions','knews'); ?></option>
							<option value="delete_users"><?php _e('Delete','knews'); ?></option>
						</select>
						<input type="submit" value="<?php _e('Apply','knews'); ?>">
					</div>
				</form>
				<?php
					//Pagination
					$maxPage=ceil(count($users) / $results_per_page);
					
					if ($maxPage > 1) {
			?>		
					<div class="tablenav bottom">

						<div class="tablenav-pages">
							<span class="displaying-num"><?php echo count($users); ?> <?php _e('users','knews'); ?></span>
							<?php if ($paged > 1) { ?>
							<a href="<?php echo $link_params; ?>1" title="<?php _e('Go to first page','knews'); ?>" class="first-page">&laquo;</a>
							<a href="<?php echo $link_params . ($paged-1); ?>" title="<?php _e('Go to previous page','knews'); ?>" class="prev-page">&lsaquo;</a>
							<?php } else { ?>
							<a href="<?php echo $link_params; ?>" title="<?php _e('Go to first page','knews'); ?>" class="first-page disabled">&laquo;</a>
							<a href="<?php echo $link_params; ?>" title="<?php _e('Go to previous page','knews'); ?>" class="prev-page disabled">&lsaquo;</a>
							<?php } ?>
							<span class="paging-input"><?php echo $paged; ?> de <span class="total-pages"><?php echo $maxPage; ?></span></span>
							<?php if ($maxPage > $paged) { ?>
							<a href="<?php echo $link_params . ($paged+1); ?>" title="<?php _e('Go to next page','knews'); ?>" class="next-page">&rsaquo;</a>
							<a href="<?php echo $link_params . $maxPage; ?>" title="<?php _e('Go to last page','knews'); ?>" class="last-page">&raquo;</a>
							<?php } else { ?>
							<a href="<?php echo $link_params . $maxPage; ?>" title="<?php _e('Go to next page','knews'); ?>" class="next-page disabled">&rsaquo;</a>
							<a href="<?php echo $link_params . $maxPage; ?>" title="<?php _e('Go to last page','knews'); ?>" class="last-page disabled">&raquo;</a>					
							<?php } ?>
						</div>
					<br class="clear">
					</div>
		<?php
					}
				} else {
					echo '<p>&nbsp;</p>';
					if ($filter_list != 0 || $search_user != '' || $filter_state != 0) {
						
						echo '<p>' . __('No users match the search criteria','knews') . '</p>';

					} else {
						echo '<p>' . __('There are not yet users','knews') . '</p>';
					}
					echo '<p>&nbsp;</p>';
				}
			?>
		<br />
		<hr />
		<h2><?php _e('Create a subscriber manually','knews'); ?></h2>
		<form action="admin.php?page=knews_users" method="post">
		<input type="hidden" name="action" id="action" value="add_user" />
		<p>E-mail: <input type="text" name="email" id="email" /></p>
		<?php
			if (count($languages) > 1) {
				
				echo '<p>' . __('Language','knews') . ': <select name="lang" id="lang">';
				foreach($languages as $l){
					echo '<option value="' . $l['language_code'] . '">' . $l['translated_name'] . '</option>';
				}
				echo '</select></p>';

			} else if (count($languages) == 1) {
				foreach ($languages as $l) {
					echo '<input type="hidden" name="lang" id="lang" value="' . $l['language_code'] . '" />';
				}
			} else {
				echo "<p>" . __('Error','knews') . ": " . __('Language not detected!','knews') . "</p>";
			}

			$query = "SELECT * FROM " . KNEWS_LISTS;
			$results = $wpdb->get_results( $query );

			if (count($results) > 1) {
				echo '<p>' . __('Mailing list','knews') . ': <select name="id_list_news" id="id_list_news">';
				foreach ($results as $list) {
					echo '<option value="' . $list->id . '">' . $list->name . '</option>';
				}
				echo '</select></p>';
			} else if (count($results) == 1) {
				echo '<input type="hidden" name="id_list_news" id="id_list_news" value="' . $results[0]->id . '">';
			}
		?>
		<p><input type="radio" name="submit_confirm" id="submit_confirm_yes" value="1" checked="checked" /><?php _e('Send e-mail confirmation','knews');?> | <input type="radio" name="submit_confirm" id="submit_confirm_no" value="0" /><?php _e("Activate user directly (don't send e-mail confirmation)",'knews');?></p>
		<div class="submit">
			<input type="submit" value="<?php _e('Create a user','knews'); ?>" class="button-primary" />
		</div>
		</form>
		<?php
			}
		?>
	</div>
<?php
?>