<div style="color: #757575;">

	<?php 
		
		
	if($_REQUEST['result'] == "success")
	{
		if(isset($_REQUEST['confirm']) && $_REQUEST['confirm'] == "true") // unsubscribed
		{
				?>
		Your email was unsubscribed successfully.
		<?php
		}
		else // user already unsubscribed.
		{
		?>
		Your email is already unsubscribed.
		<?php
		}
		
			
	}
	if($_REQUEST['result'] == "failure")
	{
		echo "Unsubscription unsuccessful, try again!!!";
			
	}
		
	?>

</div>
