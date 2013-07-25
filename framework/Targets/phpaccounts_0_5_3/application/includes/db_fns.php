<?
//echo "<h1>SOLAII Down for maintenance till 8pm GMT Sunday<h1>";
//exit();

function cleanHTML($string)
{
	//remove charactars that will mess with html or javascript
	$chars = array("'","\"","<",">","#");
	foreach($chars as $char) $string = str_replace($char,"",$string);
	$string = htmlspecialchars($string);
	return $string;
}

// get last ID in a table
function getLastID($table)
{
	global $db_reader;
	if(!$db_reader) $db_reader = safe_reader_connect();

	if(DB_DRIVER == 'mysql')
	{
		$query = "SELECT LAST_INSERT_ID() FROM $table";
		if(list($id) = $db_reader->queryRow($query))
		{
			if($id)
			{
				return intval($id);
			}
		}
		else return false;
	}
	else
	{
		echo 'please check db_driver can handle LAST INSERT_ID()';
		exit();
	}
}

// get last ID in a table
function getNextID($table)
{
	global $db_reader;
	if(!$db_reader) $db_reader = safe_reader_connect();

	if(DB_DRIVER == 'mysql')
	{
		$query ="SELECT ID FROM $table ORDER BY ID DESC LIMIT 1";
		if($result = $db_reader->query($query))
		{
			list($id) = $result->FetchRow();
			if($id)
			{
				$value = intval($id + 1);
				global $db_writer;
				//we reset the auto increment incase of deleted rows etc.
				$query = "ALTER TABLE $table AUTO_INCREMENT=$value";
				$db_writer->exec($query);
				return intval($value);
			}
			else
			{
				return 1;
			}
		}
		else return false;
	}
	else
	{
		echo 'please check db_driver can handle LAST INSERT_ID()';
		exit();
	}
}

//return array of a Collum with a Key

function tableNames($table,$column,$key,$where=false)
{
	global $db_reader;
	if(!$db_reader) $db_reader = safe_reader_connect();

	$query = "SELECT `$column`, `$key` FROM $table $where ORDER BY $column";
	$result = $db_reader->query($query);
	while($row = $result->fetchRow(MDB2_FETCHMODE_ASSOC))
	{
		$results[$row[$key]] = $row[$column];
	}
	if($results) asort($results);
	return $results;
}


function mysql_list_fields_good($db, $table,$dsn) 
{
	global $db_reader;

	return $db_reader->queryRow("SHOW COLUMNS FROM $table");
} 

function fieldlengths($table,$fields)
{
	if(DB_DRIVER == 'mysql')
	{
		$link = mysql_connect(DB_READER_HOST,DB_USER,DB_PASS);
		$fieldlist = mysql_list_fields(DB_NAME,$table,$link);
		if($fields == "all")
		{
			for($i=0;$i<mysql_num_fields($fieldlist);$i++)
			{
				while ($key = @mysql_fieldname($fieldlist, $i))
				{
					$fieldlengths[$key] = intval(mysql_fieldlen($fieldlist,$i));
				}
			}
		}
		else
		{
			foreach($fields as $field)
			{
				$i=0;
				while ($key = @mysql_fieldname($fieldlist, $i))
				{
					if($key == $field)
					{
						$fieldlengths[$field] = intval(mysql_fieldlen($fieldlist,$i));
						break;
					}
					$i++;
				}

			}
		}
		return $fieldlengths;
	}
	else
	{
		echo "fieldlengths is not implemented for DB_DRIVER !!!";
		exit();
	}
}

function getEnumOptions($table, $field)
{
	global $db_reader;
	if(!$db_reader) $db_reader = safe_reader_connect();

	$finalResult = array();

	if (strlen(trim($table)) < 1) return false;
	$query  = "show columns from $table";
	$result = $db_reader->query($query);
	while($row = $result->fetchRow(MDB2_FETCHMODE_ASSOC))
	{
		if ($field != $row["Field"]) continue;
		//check if enum type
		if (ereg('enum.(.*).', $row['Type'], $match))
		{
			$opts = explode(',', $match[1]);
			foreach ($opts as $item) $finalResult[] = substr($item, 1, strlen($item)-2);
		}
		else  return false;
	}
	return $finalResult;
}


function buildINSERT($HTTP_POST_VARS,$table)
{
	foreach($HTTP_POST_VARS as $thiskey => $thisvalue)
	{
		if(strstr($thiskey,'minute'))
		{
			$timefield = str_replace('_minute','',$thiskey);
			$Times[$timefield]['minute'] = $thisvalue;
		}
		if(strstr($thiskey,'hour'))
		{
			$timefield = str_replace('_hour','',$thiskey);
			$Times[$timefield]['hour'] = $thisvalue;
		}
		if(strstr($thiskey,'day'))
		{
			$datefield = str_replace('_day','',$thiskey);
			$Dates[$datefield]['day'] = $thisvalue;
		}
		if(strstr($thiskey,'month'))
		{
			$datefield = str_replace('_month','',$thiskey);
			$Dates[$datefield]['month'] = $thisvalue;
		}	
		if(strstr($thiskey,'year'))
		{
			$datefield = str_replace('_year','',$thiskey);
			$Dates[$datefield]['year'] = $thisvalue;
		}
	}
	reset($HTTP_POST_VARS);
	
	$fieldlist = mysql_list_fields(DB_NAME,$table);

	$i=0;
	foreach($HTTP_POST_VARS as $f => $v)
	{	
		//echo "$f = $v <br>";

		//check fields exits 
		for($j=0;$j<mysql_num_fields($fieldlist);$j++)
		{
			$key = @mysql_fieldname($fieldlist, $j);
			$matched="no";
			if($f == $key)
			{
				$matched = "yes";
				break;
			}
		}

		if($matched == "yes" && ereg("[a-zA-Z0-9]",$v))
		{
			$fieldlengths = fieldlengths($table,array($f));
			if($fieldlengths[$f] < strlen($v))
			{
				echo "field $f is too large<br>";
				echo "currently ". strlen($v) ." used<br>";
				echo "only ". $fieldlengths[$f] ." allowed<br>";
				echo "<h5>$f = $v</h5>";
				exit;
			}
			if($i>0)
			{
				$fields .= ",";
				$values .= ",";
			}
			$fields .= "`$f` ";
			if(is_int($v))
			{
				$values .= "$v";
				$i++;
				continue;
			}
			$values .= "'$v' ";
			$i++;
		}

	}
	//convert Hour Minute select box choices to one field
	if($Times)
	{
		foreach($Times as $timefield => $array)	
		{
			if($i>0 || $j>0)
			{
				$fields .= ", ";
				$values .= ", ";
			}
			$fields .= "`$timefield` ";
			$values .= "'". $array['hour'] .":". $array['minute'] .":00'";
			$j++;
		}
	}

	$j=0;
	//convert Date select box choices to one field
	if($Dates)
	{
		foreach($Dates as $datefield => $array)	
		{
			if($i>0 || $j>0)
			{
				$fields .= ", ";
				$values .= ", ";
			}
			if(!$array['day'])
			{
				$array['day'] = 1;
			}
			$fields .= "`$datefield` ";
			$values .= "'". $array['year'] ."-". $array['month'] ."-". $array['day'] ."'";
			$j++;
		}
	}


	if(ereg("[a-zA-Z0-9]",$values))
	{
		return "INSERT INTO `$table`\n (\n\t$fields\n)\n VALUES\n (\n\t$values\n)";
	}
	else return false;
}

function buildUPDATE($HTTP_POST_VARS,$table,$where)
{
	foreach($HTTP_POST_VARS as $thiskey => $thisvalue)
	{
		if(strstr($thiskey,'minute'))
		{
			$timefield = str_replace('_minute','',$thiskey);
			$Times[$timefield]['minute'] = $thisvalue;
		}
		if(strstr($thiskey,'hour'))
		{
			$timefield = str_replace('_hour','',$thiskey);
			$Times[$timefield]['hour'] = $thisvalue;
		}
		if(strstr($thiskey,'day'))
		{
			$datefield = str_replace('_day','',$thiskey);
			$Dates[$datefield]['day'] = $thisvalue;
		}
		if(strstr($thiskey,'month'))
		{
			$datefield = str_replace('_month','',$thiskey);
			$Dates[$datefield]['month'] = $thisvalue;
		}	
		if(strstr($thiskey,'year'))
		{
			$datefield = str_replace('_year','',$thiskey);
			$Dates[$datefield]['year'] = $thisvalue;
		}
	}
	reset($HTTP_POST_VARS);
	$fieldlist = mysql_list_fields(DB_NAME,$table);
	$i=0;
	foreach($HTTP_POST_VARS as $f => $v)
	{
		//check fields exits
		for($j=0;$j<mysql_num_fields($fieldlist);$j++)
		{
			$key = @mysql_fieldname($fieldlist, $j);
			$matched="no";
			if($f == $key)
			{
				$matched = "yes";
				break;
			}
		}

		if($matched == "yes" && ereg("[a-zA-Z0-9* ]",$v))
		{
			if($i>0)
			{
				$update .= ", ";
			}
			if($v == "NULL" OR $v == "NOW()") $update .= "$f = $v ";
			else $update .= "$f = '$v' ";
			$i++;
		}
	}

	//convert Hour Minute select box choices to one field
	if($Times)
	{
		foreach($Times as $timefield => $array)	
		{
			if($i>0 || $j>0)
			{
				$update .= ", ";
			}
			$update .= $timefield. " = '". $array['hour'] .":". $array['minute'] .":00'";
			$j++;
		}
	}

	$j=0;
	//convert Date select box choices to one field
	if($Dates)
	{
		foreach($Dates as $datefield => $array)	
		{
			if($i>0 || $j>0)
			{
				$update .= ", ";
			}
			if(!$array['day'])
			{
				$array['day'] = 1;
			}
			$update .= $datefield. " = '". $array['year'] ."-". $array['month'] ."-". $array['day'] ."'";
			$j++;
		}
	}

	if(ereg("[a-zA-Z0-9]",$update))
	{
		return "UPDATE $table SET $update WHERE $where";
	}
	else return false;
}

function decodeIP($string)
{
	//is this an integer
	if(preg_match('/^-?\d+$/', $string))
	{
		$HEX = base_convert($string, 10, 16);
		//echo "Back to Hex: $HEX<br>";
		//pad to 12 characters
		$HEX = str_pad($HEX, 12, "0", STR_PAD_LEFT);
		//echo "Padded Hex: $HEX<br>";
		for($i=0;$i<12;$i=$i+2) $IP_bits[] = strtoupper(substr($HEX,$i,2));
		return implode(':',$IP_bits);
	}
	//assume an IP IP address
	else
	{
		return base_convert($string, 16, 10);
	}
}
?>
