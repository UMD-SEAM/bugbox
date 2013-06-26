<?php
	//Get query text
	$text="";
	if(isset($_GET['text'])) $text=$_GET['text'];
	
	//Asume page number
	$page=$paged=1;
	if(isset($_GET['paged'])) $paged=$_GET['paged'];
	//if there is a new text query, set 1st page and get text query from text label
	if(isset($_POST['but']) && $_POST['but']!="") {
		$text=$_POST['text'];
		$page=1;
	} else {
		//Else, use page send in URL
		$page=0+$paged;
		if($page==0) //If there is no page, asume 1st
			$page=1;
	}
	
	$options = get_option('sk_options');
	$days=0;
	if(is_array($options)) {
		$days=$options['bl_days'];
	}
	
	//Get query results
	$sql="SELECT $table_name.*, $table_list.id AS block_id, $table_list.pc, $table_list.date + INTERVAL $days DAY AS date_end, $table_list.forever FROM $table_list LEFT JOIN $table_name ON $table_list.pc=$table_name.user_id";
	$comments = $wpdb->get_results($sql);

	//Count results
	$total=count($comments);
	
	//Items by page
	$max=10;
	
	//Page must have a real group
	$page=min(ceil($total/$max)+1,$page);
	
	//Create groups to be shown in page selector
	$start=($page-1)*$max+1;
	$end=min( $page * $max, $total );
	$page_links = paginate_links( array(
		'base' => add_query_arg( array('paged'=>'%#%','text'=>$text) ),
		'format' => '',
		'prev_text' => '&laquo;',
		'next_text' => '&raquo;',
		'total' => ceil($total/$max),
		'current' => $page
		));
?>
<div class="wrap">
	<div id="icon-edit-comments" class="icon32"><br /></div>
	<h2><?php if($select==SK_SPAM) _e( 'Schreikasten Spam', 'sk' ); else _e( 'Schreikasten Comments', 'sk' );?></h2>
	<form name="form1" method="post" action="<?php echo add_query_arg(array('mode'=>'', 'text'=>$text)); ?>">	
		<ul class="subsubsub">
			<li class='all'><a href='edit-comments.php?page=skmanage'<?php if($select==SK_NOT_FILTERED) echo " class=\"current\""; ?>><?php $count = sk_count(); echo _n("Comment", "Comments", $count, 'sk'); ?> (<span class="spam-count"><?php echo $count; ?></span>)</a> |</li>
			<li class='pending'><a href='edit-comments.php?page=skmanage&filter=moot'<?php if($select==SK_MOOT) echo " class=\"current\""; ?>><?php $count = sk_count(SK_MOOT); echo _n("Pending", "Pending", $count, 'sk'); ?> (<span class="spam-count"><?php echo $count; ?></span>)</a><img src='../wp-content/plugins/schreikasten/img/moot.png'> |</li>
			<li class='approved'><a href='edit-comments.php?page=skmanage&filter=ham'<?php if($select==SK_HAM) echo " class=\"current\""; ?>><?php $count = sk_count(SK_HAM); echo _n("Approved", "Approved", $count, 'sk'); ?> (<span class="spam-count"><?php echo $count; ?></span>)</a><img src='../wp-content/plugins/schreikasten/img/ham.png'> |</li>
			<li class='spam'><a href='edit-comments.php?page=skmanage&filter=spam'<?php if($select==SK_SPAM) echo " class=\"current\""; ?>><?php $count = sk_count(SK_SPAM); echo _n("Spam", "Spam", $count, 'sk'); ?> (<span class="spam-count"><?php echo $count; ?></span>)</a> <img src='../wp-content/plugins/schreikasten/img/spam.png'> |</li>
			<li class='spam'><a href='edit-comments.php?page=skmanage&filter=black'<?php if($select==SK_BLACK) echo " class=\"current\""; ?>><?php $count = sk_count(SK_BLACK); echo _n("Rejected", "Rejected", $count, 'sk'); ?> (<span class="spam-count"><?php echo $count; ?></span>)</a> <img src='../wp-content/plugins/schreikasten/img/black.png'> | </li>
			<li class='spam'><a href='edit-comments.php?page=skmanage&filter=blocked'<?php if($select==SK_BLOCKED) echo " class=\"current\""; ?>><?php echo _n('Blocked PC', 'Blocked PC', 2, 'sk'); ?></a> <img src='../wp-content/plugins/schreikasten/img/blocked.png'></li>
		</ul>

		<div class="tablenav"><?php 
			if ( $page_links ) { ?>
			<div class="tablenav-pages"><?php 
				$page_links_text = sprintf( '<span class="displaying-num">' . __( 'Displaying %s&#8211;%s of %s', 'sk' ) . '</span>%s',
				number_format_i18n( $start ),
				number_format_i18n( $end ),
				number_format_i18n( $total ),
				$page_links
				); echo $page_links_text; ?>
			</div><?php 
			}
			
			//Consult group
			$sql.=" ORDER BY date DESC LIMIT ".($start - 1).", $max";
			$comments = $wpdb->get_results($sql);
			$count=count($comments);
			if($count==0) { ?> 
			<div class="clear"></div>
			<p><?php _e('No locked PCs found', 'sk'); ?></p><?php 
				} else { ?>
			<div class="alignleft actions">
				<select name="action">
					<option value="-1" selected="selected"><?php _e('Bulk Actions' , 'sk'); ?></option>
					<option value="unlock"><?php _e('Unlock PC' , 'sk'); ?></option>
					<option value="forever"><?php _e('Block PC forever' , 'sk'); ?></option>
				</select>
				<input type="submit" name="doaction" id="doaction" value="<?php _e('Apply'); ?>" class="button-secondary apply" />
			</div>
			<br class="clear" />
		</div>
		<div class="clear"></div>
		<table class="widefat comments fixed" cellspacing="0">
			<thead>
				<tr>
					<th scope="col" id="cb" class="manage-column column-cb check-column" style=""><input type="checkbox" /></th>
					<th scope="col" id="author" class="manage-column column-author" style="width: 150px;"><?php _e( 'PC' , 'sk'); ?></th>
					<th scope="col" id="comment" class="manage-column column-comment" style=""><?php _e( 'Comments' , 'sk'); ?></th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th scope="col" class="manage-column column-cb check-column" style=""><input type="checkbox" /></th>
					<th scope="col" class="manage-column column-author" style=""><?php _e( 'PC' , 'sk'); ?></th>
					<th scope="col" class="manage-column column-comment" style=""><?php _e( 'Comments' , 'sk'); ?></th>
				</tr>
			</tfoot>
			<tbody id="the-comment-list" class="list:comment"><?php
				$first="";
				foreach($comments as $comment) { ?>
				<tr id='pc-<?php echo $comment->id; ?>'>
					<?php if($comment->pc!=$first) { $first=$comment->pc; ?><th scope="row" class="check-column"><input type='checkbox' name='checked_pcs[]' value='<?php echo $comment->block_id; ?>' /></th>
						<td class="column-author"><strong> <?php 
						if(!$comment->forever)
							printf( __("To be unlocked<br>%s", 'sk'), $comment->date_end);
						else
							_e("To be never unlock", 'sk');
						if($comment->pc>0) printf(" (".__('registered user', 'sk').")");?></strong><br />							
							
						</td><?php } else { ?>
						<td colspan=2></td>
						<?php } ?>
						<td class="manage-column column-author"><?php if($comment->id!=NULL) { ?><div id="submitted-on"><?php
								echo sk_avatar($comment->id, 40);
								echo $comment->alias." | ". sprintf("<a href='http://ws.arin.net/cgi-bin/whois.pl?queryinput=%s' target='_BLANK'>%s</a>", $comment->ip, $comment->ip) . " <br> " . $comment->date . " <br> "; 
								if($comment->email!="")
									echo $comment->email;
								else
									_e('No e-mail registry', 'sk'); ?></div><div><p><img src='../wp-content/plugins/schreikasten/img/<?php $img='ham.png'; if($comment->status==SK_SPAM) $img='spam.png'; if($comment->status==SK_BLACK) $img='black.png'; if($comment->status==SK_MOOT) $img='moot.png'; echo $img; ?>'> <?php echo sk_format_text($comment->text); ?></p><?php
							$act_message="";
							$ham_message=strtolower(_n('Approved', 'Approved', 1, 'sk'));
							$black_message=strtolower(_n('Rejected','Rejected',1, 'sk'));
							$act_message=$black_message;
							$status_message=__('This comment is marked as %s.\nAre you sure you want to mark it as %s?', 'sk');
						?>
						<div class="row-actions">
							<span class='edit'><a href="<?php echo add_query_arg( array('paged'=>$page,'text'=>$text, 'mode' => 'edit', 'id' => $comment->id) );?>" class='edit'><?php _e('Edit comment', 'sk') ?></a></span>
							<?php if($comment->status!=SK_HAM) { ?><span> | <a href="<?php echo add_query_arg( array('paged'=>$page,'text'=>$text, 'mode_x' => 'set_ham_x', 'id' => $comment->id) );?>" class="edit" onclick="javascript:check=confirm( '<?php printf($status_message, $act_message, $ham_message); ?>');if(check==false) return false;"><?php _e('Accept comment', 'sk'); ?></a></span><?php } ?>
							<span class='delete'> | <a href="<?php echo add_query_arg( array('paged'=>$page,'text'=>$text, 'mode_x' => 'delete_x', 'id' => $comment->id) ); ?>" class="delete" onclick="javascript:check=confirm( '<?php _e("Delete this Comment?",'sk')?>');if(check==false) return false;"><?php _e('Delete Comment', 'sk') ?></a></span>
							<span> | <a href="<?php echo add_query_arg( array('paged'=>$page,'text'=>$text, 'mode_x' => 'unlock_x', 'id' => $comment->block_id) ); ?>" class="edit" onclick="javascript:check=confirm( '<?php _e("Are you sure you want to unlock this PC?",'sk')?>');if(check==false) return false;"><?php _e('Unlock PC', 'sk') ?></a></span>
							<?php if(!$comment->forever) { ?><span> | <a href="<?php echo add_query_arg( array('paged'=>$page,'text'=>$text, 'mode_x' => 'forever_lock_x', 'id' => $comment->block_id) );?>" class="delete" onclick="javascript:check=confirm( '<?php _e('Are you sure you want to block this PC forever?', 'sk'); ?>');if(check==false) return false;"><?php _e('Block PC forever', 'sk'); ?></a>
							</span><?php } ?>
						</div></div><?php } ?>
							
						</td></tr> <?php } ?>
			</tbody>
		</table>			
			
		<div class="tablenav"><?php 
			if ( $page_links ) { ?>
			<div class="tablenav-pages"><?php 
				$page_links_text = sprintf( '<span class="displaying-num">' . __( 'Displaying %s&#8211;%s of %s', 'sk' ) . '</span>%s',
					number_format_i18n( $start ),
					number_format_i18n( $end ),
					number_format_i18n( $total ),
					$page_links
					); echo $page_links_text; ?>
			</div><?php 
			} ?>
			<div class="alignleft actions">
				<select name="action2">
					<option value="-1" selected="selected"><?php _e('Bulk Actions' , 'sk'); ?></option>
					<option value="unlock"><?php _e('Unlock PC' , 'sk'); ?></option>
					<option value="forever"><?php _e('Block PC forever' , 'sk'); ?></option>
				</select>
				<input type="submit" name="doaction2" id="doaction2" value="<?php _e('Apply'); ?>" class="button-secondary apply" />
				<?php 
					if($select==SK_SPAM) { ?>
				<input type="submit" value="<?php _e( 'Delete all Spam', 'sk' ); ?>" class="button" name="deletespam" /><?php 
				} ?>
			</div>
			<br class="clear" />
		</div><?php 
			} ?>
	</form>
</div>
