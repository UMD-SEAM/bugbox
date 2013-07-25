<?
function timeSelector($name,$time,$start = "0:0",$end = "23:55")
{
	//get hours minutes seconds
	$time=explode(":",$time);
	$start=explode(":",$start);
	$end=explode(":",$end);
	if($start[0]>=$end[0])
	{
		$start[0]=1;
		$end[0]=23;
	}

	//hours
	$html = "<select name='". $name ."_hour'>\n";
	for($i=$start[0];$i<=$end[0];$i++)
	{
		if($time[0]==$i) $html .= "<option value=$i selected>$i</option>\n";
		else $html .= "<option value=$i>$i</option>\n";
	}
	$html .= "</select>:";

	//minutes
	$html .= "<select name='". $name ."_minute'>\n";
	for($i=0;$i<=55;$i=$i+5)
	{
		if($time[1]<=$i && $time[1] > $i-5) $html .= "<option value=$i selected>$i</option>\n";
		else $html .= "<option value=$i>$i</option>\n";
	}
	$html .= "</select>";
	return $html;
}

function dateSelector($name,$date,$days=true)
{
	//check for timestamp
	if(!is_int($date))
	{
		$date = strtotime($date);
	}

	//get date parts
	$day = date("j",$date);
	$month = date("m",$date);
	$year = date("Y",$date);

	//days
	if($days)
	{
		$html = "<select name='". $name ."_day'>\n";
		for($i=1;$i<=31;$i++)
		{
			if($day==$i) $html .= "<option value=$i selected>$i</option>\n";
			else $html .= "<option value=$i>$i</option>\n";
		}
		$html .= "</select>:";
	}

	//months
	$html .= "<select name='". $name ."_month'>\n";
	for($i=1;$i<=12;$i++)
	{
		if($month==$i) $html .= "<option value=$i selected>". date('M',mktime(0,0,0,$i,1,$year)) ."</option>\n";
		else $html .= "<option value=$i>". date('M',mktime(0,0,0,$i,1,$year)) ."</option>\n";
	}
	$html .= "</select>";

	//year
	$html .= "<select name='". $name ."_year'>\n";
	for($i=2000;$i<=2018;$i++)
	{
		if($year==$i) $html .= "<option value=$i selected>$i</option>\n";
		else $html .= "<option value=$i>$i</option>\n";
	}
	$html .= "</select>";

	return $html;
}

function textInput($name,$value,$length)
{
	$maxlength = $length;
	if($length > 40) $length = 40;
	return "<b>$name:</b> <input type='text' name='$name' value='$value' size='$length' maxlength='$maxlength'>";
}
function textareaInput($name,$value)
{
	return "<b>$name:</b> <textarea name='$name' rows='5' cols='50'>$value</textarea>";
}

function hiddenInput($name,$value,$length)
{
	return "<b>$name:</b> <input type='hidden' name='$name' value='$value'><input type='text' value='$value' readonly size='$length'>";
}

// create form to add new records to a given table
function addForm($table,$action,$ID)
{
	global $db_reader;
	if(!$db_reader) $db_reader = safe_reader_connect();
	if(DB_DRIVER == 'mysql')
	{
		$fieldlist = mysql_list_fields(DB_NAME,$table);
		$html = "<div class='center'>\n<form name='$table' action='$action' method='POST'>\n<table class='edit'>\n<tr>\n<td>";

		//Skip first field Cause it's the Key
		$i=0;
		$m=1;
		while ($key = mysql_fieldname($fieldlist, $m))
		{
			$length = intval(mysql_fieldlen($fieldlist, $m));
			if(strstr(mysql_field_flags($fieldlist,$m),"enum"))
			{
				unset($thearray);
				$array = getEnumOptions($table, $key);
				foreach($array as $array_key) $thearray[$array_key] = $array_key;
				$html .= "<b>$key:</b> ";
				$html .= selectlist2($key,$thearray,$value);
			}
			elseif("$key = \"$value\"" == $ID[$i])
			{
				$html .= hiddenInput($key,$value,$length);
				$i++;
			}
			elseif(ereg("ID",$key))
			{
				$fieldname_array = ereg_replace("ID1|ID2","ID",$key);
				$fieldname_array = substr($fieldname_array, 0, -3) . "_array";
				global $$fieldname_array;
				$html .= "<b>$key:</b>";
				$html .= selectlist2($key,$$fieldname_array,$value);
			}
			else
			{
				$value = "";
				$html .= textInput($key,$value,$length);
			}

			if($m % 2 == 0)
			{
				$html .= "</td>\n</tr>\n<tr>\n<td>";
			}
			else
			{
				$html .= "</td>\n<td>";
			}
			$m++; 
		}
		$html .= "<div align='center'><input type='submit' name='Submit' value='Submit' class='medium'></div></td></tr></table></form></div>";
		return $html;
	}
	else 	{
		$html .= "updateForm is not implemented for DB_DRIVER !!!";
		exit();
	}
}




function updateForm($table,$action,$ID,$title=edit)
{
	global $db_reader;
	if(!$db_reader) $db_reader = safe_reader_connect();
	if(DB_DRIVER == 'mysql')
	{
		global $db_reader_dsn;
		$fieldlist = mysql_list_fields(DB_NAME,$table);

		$heading = str_replace("_"," ",$title);
		$html = "<div class='centered'><form name='$table' action='$action' method='POST' enctype=\"multipart/form-data\">
			<fieldset>
			<legend>$heading form</legend>
			<table><tr><td> ";

		$query = "SELECT * FROM $table WHERE $ID";
		$result = $db_reader->query($query);
		$mode = MDB2_FETCHMODE_ASSOC;

		$Table = str_replace("_tbl","",$table);

		//get the ID from the Where bit in $ID
		ereg("[0-9]+",$ID,$ids);
		$id = $ids[0];

		//Skip first field Cause it's the Key
		$i=0;
		$m=0;
		while($row = $result->fetchRow($mode))
		{
			foreach($row as $key => $value)
			{
				$length = intval(mysql_field_len($fieldlist, $m));
				if("$key = \"$value\"" == $ID)
				{
					$html .= hiddenInput($key,$value,$length);
					$i++;
				}
				elseif(ereg("_ID",$key))
				{
					$fieldname = substr($key, 0, -3);
					$fieldname_array = $fieldname . "_array";
					global $$fieldname_array;
					$html .= "<b>$fieldname:</b> ";
					//check array exists
					if($$fieldname_array)
					{
						$html .= selectlist2($key,$$fieldname_array,$value);
					}
					//attempt to look up value
					else
					{
						//check for field
						$query = "DESCRIBE ". DB_PREFIX . $fieldname ."_tbl";
						$check_result = $db_reader->query($query);
						while($check_row = $check_result->FetchRow(MDB2_FETCHMODE_ASSOC))
						{
							if(strstr($check_row['Field'],'Name'))
							{
								$query = "SELECT {$check_row['Field']} FROM ". DB_PREFIX . $fieldname ."_tbl
									WHERE ID = $value";
								list($Name) = $db_reader->queryRow($query);
								$html .= $Name;
								break;
							}
						}
					}
				}
				elseif($key == "Open" || $key == "Close" || $key == "Time")
				{
					$html .= "<b>$key:</b> ";
					$html .= timeSelector($key,$value);
					$html .= "";
				}					
				elseif($length > 101)
				{
					$html .= textareaInput($key,$value);
				}
				else
				{
					$html .= textInput($key,$value,$length);
				}
				if($m % 2 == 1)
				{
					$html .= "</td></tr><tr><td>";
					$align = "right";
				}
				else
				{
					$html .= "</td><td nowrap='nowrap'>";
					$align = "left";
				}
				$m++;
			}
		}
		$html .= "<div align='$align'><input type='submit' name='Submit' value='Submit' class='medium'></div></td></tr></table></fieldset></div></form>";
		return $html;
	}
	else 	{
		$html .= "updateForm is not implemented for DB_DRIVER !!!";
		exit();
	}
}


function multipleselectlist($name,$array,$selected,$size=false)
{
	if(!$size) $size = 10;
	$html = "<select name='". $name ."[]' MULTIPLE size='$size'>\n";
	while(list($key,$value)=each($array))
	{
		$html = $html . "<option value='" . $key . "' ". $selected[$key] ." >" . $value ."</option>\n";
	}
	$html = $html . "</select>\n";
	return $html;
}

// makes an array of specified coloumn in table

function selectlistSQL($title,$table,$column,$key,$multiple)
{
	$result = tableNames($table,$column,$key,$where);
	for($i=0;$i<sizeof($result);$i++)
	{

		$results[$i] = $result[$i][1];
		$keys[$i] = $result[$i][0];
	}
	$html = selectlist($title,$key,$results,$keys,$multiple);
	return $html;
}



// Makes a drop down selectlist from arrays
function javaselectlist($title,$name,$array,$multiple,$java)
{
	if ($array)
	{
		if($multiple>1)
		{
			$html = "<select $java class='medium' name='" . $name. "[]' MULTIPLE size='$multiple'>\n";
		}
		else
		{
			$html = "<select $java class='medium' name='$name'>\n<option  value='(NULL)' selected>$title</option>\n";
		}

		while(list($key,$value)=each($array))
		{
			$html = $html . "<option value='" . $key . "'>" . $value ."</option>\n";
		}
		$html = $html . "</select>\n";
		return $html;
	}
	return false;
}

// Makes a drop down selectlist from arrays
function selectlist($title,$name,$array,$selected)
{
	$html = "<select name='$name'>\n<option value='' selected>$title</option>\n";
	if($array)
	{
		while(list($key,$value)=each($array))
		{
			if($selected == $key)
			{
				$html = $html . "<option value='" . $key . "' selected>" . $value ."</option>\n";
				continue;
			}
			$html = $html . "<option value='" . $key . "' >" . $value ."</option>\n";
		}
	}
	$html = $html . "</select>\n";
	return $html;
}

// Makes a drop down selectlist from arrays
function selectlist2($name,$array,$selected)
{
	$html = "<select class='medium' name='$name'>\n";
	if(is_array($array))
	{
		while(list($key,$value)=each($array))
		{
			if($selected == $key)
			{
				$html = $html . "<option value='" . $key . "' selected>" . $value ."</option>\n";
				continue;
			}
			$html = $html . "<option value='" . $key . "' >" . $value ."</option>\n";
		}
	}
	$html = $html . "</select>\n";
	return $html;
}

// Makes a drop down selectlist for number select
function numericSelectlist($name,$selected,$max=10,$min=1,$increment=1)
{
	$i=0;
	$html = "<select class='medium' name='$name'>\n";
	for($i=$min;$i<=$max;$i+=$increment)
	{
		if($i == $selected) $html .= "<option value='" . $i . "' selected>" . $i ."</option>\n";
		else $html .= "<option value='" . $i . "' >" . $i ."</option>\n";
	}
	$html .=  "</select>\n";
	return $html;
}

function urlJump($title,$name,$array,$url,$selected=0)
{
	if ($array)
	{
		$html = "<form style='margin-bottom:0;' action='$url' method='post'><select class='medium' name='$name' onChange='window.location=\"" . $url . $name . "=\" + this.options[selectedIndex].value'>\n<option  value='0' selected>$title</option>\n";


		while(list($key,$value)=each($array))
		{
			if($selected == $key)
			{
				$html = $html . "<option value='" . $key . "' selected>" . $value ."</option>\n";
				continue;
			}
			$html = $html . "<option value='$key'>" . $value ."</option>\n";
		}
		$html = $html . "</select></form>\n";
		return $html;
	}
	return false;
}

function checkboxArray($Title,$Key,$array)
{

	$html .= "<fieldset><legend>$Title</legend>";
	$html .= "<table>";
	foreach($array as $key => $value)
	{
		if($i == 0)
		{
			$html .= '<tr>';
		}
		$html .= "<td><input type=\"checkbox\" name=\"". $Key ."[$key]\" value=\"$key\"></td><th>$value</th>\n";
		if($i == 3)
		{
			$html .= '</tr>';
			$i=0;
		}
		else
		{
			$i++;
		}
	}

	if($i)
	{
		$colspan = (3-$i) * 2;
		$html .="<td colspan=\"$colspan\"></td></tr>";
	}
	$html .= '</table></fieldset>';
	return $html;
}
?>
