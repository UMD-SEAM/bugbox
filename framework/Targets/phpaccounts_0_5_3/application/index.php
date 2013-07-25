<?php
/*
 * General Notes
 *
 * All requests are made through this script
 * The scripts in includes are functions and globals, always included.
 * The scripts in lib are the specific functions to each page and are included when required
 * The Content is stored in a variable Content and out
 * The User's type and Company ID (if applicable) are stored in the session, Admin Users can temporarily use a company ID for editing that Particluar Company
 *
 */
define('INCLUDE_PATH',getcwd());

//check for config file
if(file_exists(INCLUDE_PATH .'/config.php'))
{
	require_once(INCLUDE_PATH .'/config.php');
	if(!defined('DB_USER'))
	{

		$run_install = true;
	}
}
else
{
	$run_install = true;
}

if($run_install)
{
	echo "Looks like you haven't run the <a href=\"installer.php\">installer</a> yet.";
	exit;
}

//include some configuration info, constants etc, functions etc..
require_once(INCLUDE_PATH .'/includes/includes.php');

//auto detect ABSOULUTE_URI
if(!ABSOLUTE_URI)
{
	define('ABSOLUTE_URI',$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
}



//instantiate our display class, based on patTemplate
$tmpl = new tmpl();
//$tmpl = new patTemplate();
//this is where the templates live
$tmpl->setBasedir(INCLUDE_PATH .'/templates');

// Read main Templates
$tmpl->readTemplatesFromFile("main.tmpl.html");

//authentication
require_once(INCLUDE_PATH .'/includes/authentication.php');

$action = $_GET['action'];
$page = basename($_GET['page']);
$User_ID = $_SESSION['User_ID'];

//Normal processing requires user id (and login)
if($User_ID)
{
	//read preferences
	include(INCLUDE_PATH .'/includes/preferences.php');


	//make an array of clients
	$Client_array = tableNames(PHPA_CLIENT_TABLE,'Company_Name','ID'," WHERE User_ID = $User_ID ");
	$Outgoing_Type_array = tableNames(PHPA_OUTGOING_TYPE_TABLE,'Outgoing_Type','ID'," WHERE User_ID = $User_ID ");
	$Vendor_array = tableNames(PHPA_VENDOR_TABLE,'Company_Name','ID'," WHERE User_ID = $User_ID ");

	//load script for this request
	if($page)
	{
		include(INCLUDE_PATH .'/lib/'. $page .'.php');
	}
	//show main window
	elseif($_GET['frameset'])
	{
		$tmpl->SetDisplayType('frameset');
	}
	else
	{
		//show enter button
		$tmpl->AddThisTemplate('enter');
	}
}


if($_GET['csv'])
{
	$tmpl->SetDisplayType('csv');
}
$tmpl->DisplayContent();
?>
