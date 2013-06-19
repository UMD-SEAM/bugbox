<div class="narrow">
<p><?php 

$act_message="";
$button_message="";

switch($mode) {
	case 'set_spam':
		$button_message="Spam";
		break;
	case 'set_ham':
		$act_message=__('approve', 'sk');
		$button_message=__('Approve','sk');
		break;
	case 'set_black':
		$act_message=__('reject', 'sk');
		$button_message=__('Reject', 'sk');
		break;
	case 'delete':
		$act_message=__('delete', 'sk');
		$button_message=__('Delete', 'sk');
		break;
}

if($mode=='set_spam') {
	_e('<strong>Caution:</strong> You are about to mark the following comment as spam:</p>', 'sk'); 
} else {
	printf( __('<strong>Caution:</strong> You are about to %s the following comment:</p>', 'sk') , $act_message ); 
}?>

<p><?php _e('Are you sure you want to do that?', 'sk'); ?></p>

		<form action='<?php echo "edit-comments.php?page=skmanage&mode_x=".$mode."_x&id=$id"; ?>' method='post'>

<table width="100%">
<tr>
		<td><input type='button' class="button" value='No' onclick="self.location='<?php echo admin_url("edit-comments.php?page=skmanage"); ?>'" /></td>
<td class="textright"><input type='submit' class="button" value='<?php echo $button_message." ".__('Comment', 'sk'); ?>' /></td>
</tr>
</table>

</form>

<table class="form-table" cellpadding="5">
<tr class="alt">
<th scope="row"><?php _e('Author');?></th>
<td><?php echo $alias; ?></td>
</tr>
<tr>
<th scope="row"><?php _e('E-mail'); ?></th>
<td><?php echo $email; ?></td>
</tr>
<tr>
		<th scope="row" valign="top"><?php _e('Comment', 'sk'); ?></th>
<td><?php echo $comment; ?></td>
</tr>
</table>

</div>
