<?php

/*
 * This include uses pear db class and defines 2 functions to abstract reads and writes to the database
 *
 */

// The pear base directory must be in your include_path
require_once 'MDB2.php';

function safe_writer_connect()
{

	if($db =& MDB2::connect(DB_WRITER_DSN))
	{
		$db->setOption('portability', MDB2_PORTABILITY_ALL ^ MDB2_PORTABILITY_FIX_CASE);
			
		if(PEAR::isError($db))
		{
			die ($db->getMessage());
		}
		else return $db;
	}
	return false;
}

function safe_reader_connect()
{

	if($db =& MDB2::factory(DB_READER_DSN))
	{
		$db->setOption('portability', MDB2_PORTABILITY_ALL ^ MDB2_PORTABILITY_FIX_CASE);
		if(PEAR::isError($db)) 
		{
			die ($db->getMessage());
		}
		else return $db;
	}
	return false;
}

$db_reader = safe_reader_connect();
$db_writer = safe_writer_connect();


?>
