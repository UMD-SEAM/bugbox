<?php
define('INCLUDE_PATH',getcwd());
ini_set('display_errors','on');

//instantiate  the extention of the template class
require_once( INCLUDE_PATH .'/includes/display.php');

//need some bacic functions
require_once( INCLUDE_PATH .'/includes/main_fns.php');
$action = getVariable('action');

//instantiate our display class, based on patTemplate
$tmpl = new tmpl();
//$tmpl = new patTemplate();
//this is where the templates live
$tmpl->setBasedir(INCLUDE_PATH .'/templates');

// Read main Templates
$tmpl->readTemplatesFromFile("main.tmpl.html");

//read installer templates
$tmpl->readTemplatesFromFile('installer.tmpl.html');

//check for config file
if(file_exists(INCLUDE_PATH .'/config.php'))
{
	require_once(INCLUDE_PATH .'/config.php');
	if(defined('DB_USER'))
	{
		require_once(INCLUDE_PATH .'/includes/includes.php');
	}
	else
	{
		$run_install = true;
	}
}
else
{
	$run_install = true;
}

if($action != 'config' && $run_install)
{
	//throw back to config page
		unset($action);
}

if($action == 'config' && $_POST)
{
	//mandatory fields
	$check_fields = array('Username','Password','Host','Database','DB_Driver','DB_Prefix','Admin_Email');
	foreach($check_fields as $check)
	{
		if(!$_POST[$check])
		{
			$message = 'Please ensure the marked fields are completed. ';
			$warning[$check] = true;
		}

	}

	//check for imagepng
	if(!function_exists('imagepng'))
	{
		$warning['imagepng'] = true;
		$message .= "function imagepng could not be found";
	}


	//problem
	if($warning)
	{
		foreach($_POST as $key => $value)
		{
			$tmpl->AddVar('create_config',$key,$value);
			if($warning[$key])
			{
				$tmpl->AddVar('create_config',$key .'_CLASS','warning');
			}
		}
		$tmpl->AddVar('message','MESSAGE',$message);
		$tmpl->SetAttribute('message','visibility','show');
		$tmpl->AddThisTemplate('create_config');
	}

	else
	{ 
		foreach($_POST as $key => $value)
		{
			$tmpl->AddVar('config_file',$key,$value);
		}
		$config = $tmpl->GetParsedTemplate('config_file');
		$config_file = INCLUDE_PATH .'/config.php';

		//try and write file
		if(!writeFile($config_file,$config))
		{
			$message = "$config_file is not writeable. <br /><b>please create this file manually with the contents below, BEFORE YOU CONTINUE</b>";
		}
		else
		{
			$message ="SUCCESS! $config_file created, please continue";
		}

		$tmpl->AddVar('message','MESSAGE',$message);
		$tmpl->SetAttribute('message','visibility','show');
		$tmpl->AddVar('config_complete','config',htmlspecialchars($config));
		$tmpl->AddThisTemplate('config_complete');
		$action = 'config_complete';

	}

}

if($action == 'submit_create_account')
{
	if(!$_POST['Terms'])
	{
		$tmpl->AddVar('create_account','TERMS_CLASS','warning');
		$message = 'You must agree to Terms &amp; Conditions.';
		$warning['Terms'] = true;

	}
	//mandatory fields
	$check_fields = array('Email','Password','Company_Name','First_Name','Surname');
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
		if(!is_writable(INCLUDE_PATH ."/users") || !is_writable(INCLUDE_PATH ."/users"))
		{
			$tmpl->AddVar('user_directory_not_writable','USER_DIR',INCLUDE_PATH ."/users");
			$tmpl->AddVar('user_directory_not_writable','CACHE_DIR',INCLUDE_PATH ."/cache");
			$tmpl->SetAttribute('user_directory_not_writable','visibility','show');
		}
else
{
			$tmpl->SetAttribute('user_directory_not_writable','visibility','hidden');

}
		$tmpl->AddThisTemplate('account_created');
		$action = 'account_created';
	}
}

if($action == 'create_account')
{

	//create database and default data
	$sql = file_get_contents(INCLUDE_PATH .'/install/database.sql');

	//replace table names
	$const = get_defined_constants();
	foreach($const as $key => $value)
	{
		if(strstr($key,'PHPA_'))
		{
			$sql = str_replace($key,$value,$sql);
		}
	}

	$tmpfname = tempnam("/tmp", "tmp.sql");
	$handle = fopen($tmpfname, "w");
	fwrite($handle, $sql);
	fclose($handle);
	$command = "mysql -u". DB_USER ." -p". DB_PASS ." -D". DB_NAME ." -h". DB_WRITER_HOST ." < ". $tmpfname;
	//exec($command,$out);

	//quicl
	$queries = explode(';',$sql);
	foreach($queries as $query)
	{
		if(preg_match('/\w/',$query))
		{
			$db_writer->exec($query);
		}
	}
	$tmpl->AddVar('create_account','EMAIL',ADMIN_EMAIL);
	$tmpl->AddThisTemplate('create_account');
}

if(!$action)
{
	//default prefix
	$tmpl->AddVar('create_config','DB_PREFIX','PHPA_');
	$tmpl->AddThisTemplate('create_config');
}
$tmpl->DisplayContent();


?>
