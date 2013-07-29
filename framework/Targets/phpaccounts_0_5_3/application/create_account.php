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
include(INCLUDE_PATH .'/includes/includes.php');

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
$tmpl->readTemplatesFromFile('main.tmpl.html');

$tmpl->readTemplatesFromFile('create_account.tmpl.html');

if($_POST)
{
	if(!$_POST['Terms'])
	{
		$tmpl->AddVar('create_account','TERMS_CLASS','warning');
		$message = 'You must agree to Terms &amp; Conditions.';
		$warning['Terms'] = true;

	}
	//mandatory fields
	$check_fields = array('Email','Password','Company_Name','First_Name','Surname','Telephone');
	foreach($check_fields as $check)
	{
		if(!$_POST[$check])
		{
		$message = 'Please ensure the marked fields are completed. ';
			$warning[$check] = true;
		}
	}

	//check Email
	if(!checkEmail($_POST['Email']))
	{
		$message .= "The Email address {$_POST['Email']} is not valid/already registered";
		$warning['Email'] = true;
	}


	//problem
	if($warning)
	{
		foreach($_POST as $key => $value)
		{
			$tmpl->AddVar('create_account',$key,$value);
			if($warning[$key])
			{
				$tmpl->AddVar('create_account',$key .'_CLASS','warning');
			}
		}
		$tmpl->AddVar('message','MESSAGE',$message);
		$tmpl->SetAttribute('message','visibility','show');
		$tmpl->AddThisTemplate('create_account');
	}

	else
	{
		//INSERT
		$User_ID = createUser($_POST);
		sendLoginDetails($User_ID);
		$tmpl->AddThisTemplate('account_created');
	}
}
else
{
	$tmpl->AddThisTemplate('create_account');
}
$tmpl->DisplayContent();
?>
