<div style="color: #757575;">
	
	<?php 
	
	if(isset($_REQUEST['result']) && $_REQUEST['result'] == 'failure')
	{
	
	?>
	
	There was an error while confirming your subscription. Try after some time.
	
	<?php 
	
	}
	else
	{

		if(isset($_REQUEST['confirm']) && $_REQUEST['confirm'] == "true") // user need  to confirm
		{
				?>
		Thank you for confirming your subscription.
		<?php
		}
		else // user already confirmed.
		{
		?>
		Your subscription is already active.
		<?php
		}
		
	}
	
	?>
</div>