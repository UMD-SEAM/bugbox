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
		$run_install = true;
	}
	else
	{
		$run_install = false;
	}
}
else
{
	$run_install = false;
}

if($run_install)
{

	//create database and default data
	$sql = file_get_contents(INCLUDE_PATH .'/install/0.5.2-from-0.5.1.sql');

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

	$queries = explode(';',$sql);
	foreach($queries as $query)
	{
		if(preg_match('/\w/',$query))
		{
			$affected =& $db_writer->exec($query);

			// Always check that result is not an error
			if (PEAR::isError($affected)) {
				die($affected->getMessage());
			}
		}
	}
	$tmpl->AddThisTemplate('upgrade-complete');
}
else
{
	$tmpl->AddThisTemplate('upgrade-instructions');
}

$tmpl->DisplayContent();

?>
