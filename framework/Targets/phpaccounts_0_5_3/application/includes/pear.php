<?php
/*
* Set a adebug function for pear
*
*/



// This function will handle all errors
function handle_pear_error ($error_obj) 
{
	// Be verbose while developing the application
	if (DEBUG_ENV)
	{
		$message = $error_obj->getMessage()."\n".$error_obj->getDebugInfo();
		// Dump a silly message if the site is in production
	} 
	else 
	{
		die ('Sorry you request can not be processed now. Try again later');
	}

	//log message into error table
	//Accounts_error_handler (0,$message,false,false );
	die($message);
}

// On error, call the "handle_pear_error" function back
// You can also use an object as pear error handler so:
// setErrorHandling(PEAR_ERROR_CALLBACK, array($object,'method_name');
PEAR::setErrorHandling(PEAR_ERROR_CALLBACK, 'handle_pear_error');
?>
