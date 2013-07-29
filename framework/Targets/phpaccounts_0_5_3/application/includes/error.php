<?php

/*
 * Error Handler
 *
 */

// what messages to report
error_reporting (E_ALL ^ E_NOTICE);
// this function will handle all reported errors
 /*
function Accounts_error_handler ($errno, $errstr, $errfile, $errline)
{
	global $db_writer;
	global $_SERVER;
	global $page;
	global $action;
	global $User_ID;

	//don't bother with E_NOTICE (*)
	if($errno != 8)
	{
		$query = "INSERT INTO ". PHPA_ERROR_LOG_TABLE ."
			(Severity,Message,Filename,Line_Number,Request_URI,Page,Action,User_ID,Timestamp)
			VALUES ('". addslashes($errno) ."','". addslashes($errstr) ."','". addslashes($errfile) ."','". addslashes($errline) ."','". addslashes($_SERVER['REQUEST_URI']) ."','". addslashes($page) ."','". addslashes($action) ."','". addslashes($User_ID) ."',NOW())";
		$db_writer->exec($query);
		if(DEBUG_ENV)
		{
			echo "In $errfile, line: $errline\n<br>$errstr";
		}
	}


}

//manually called error function, for when user click on something that shouldn't work etc.
function generic_error()
{
	global $tmpl;
	$tmpl->DisplayParsedTemplate('generic_go_back');
	exit();
}
set_error_handler ('Accounts_error_handler');

  */
?>
