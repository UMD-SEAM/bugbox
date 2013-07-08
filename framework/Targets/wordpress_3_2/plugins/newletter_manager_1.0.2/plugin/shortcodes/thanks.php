<div style="color: #757575;">
	<?php 
	if($_REQUEST['result'] == "success") //subscription ok
	{
		?>
		Thank you for subscribing to our list.
		<?php 		
		if(isset($_REQUEST['confirm'])) // double opt-in
		{
			if($_REQUEST['confirm'] == "true") // user need  to confirm
			{ 
				?>
				Your subscription is pending now. Please follow the confirmation link 	in your mailbox.
				<?php
			}
			else // user already confirmed.
			{ 
				?>
				Your subscription is already active.
				<?php 
			}
		}
		else // single opt-in
		{ 
			?>
			Your subscription is active now.
			<?php 
		}
	}
	
	if($_GET['result'] == "failure") //subscription error
	{ 
		?>
		There was an error during subscribing.
		<?php
	}

	?>

</div>
