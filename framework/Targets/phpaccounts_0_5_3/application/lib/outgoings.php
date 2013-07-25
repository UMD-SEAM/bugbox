<?php
$tmpl->ReadTemplatesFromFile('outgoings.tmpl.html');
/*
if($action == 'import_data')
{
	$query = "SELECT * FROM tmp_". PHPA_OUTGOING_TABLE ."";
	$result = $db_reader->query($query);
	while($row = $result->fetchRow(MDB2_FETCHMODE_ASSOC))
	{
		reset($Vendor_array);
		foreach($Vendor_array as $key => $value)
		{
			if($value == $row['Vendor'])
			{
				$Vendor_ID = $key;
			}
		}

		if($row['Outgoing_Type'])
		{
			$Outgoing_Type_ID = 3;
		}
		else
		{
			$Outgoing_Type_ID = 0;
		}

		$query = "INSERT INTO ". PHPA_OUTGOING_TABLE ." (Date,Vendor_ID,Value,Outgoing_Type_ID,Description)
			VALUES
			('". $row['Date'] ."',$Vendor_ID,'". $row['Value'] ."',$Outgoing_Type_ID,'". addslashes($row['Description']) ."')";
		$db_writer->exec($query);
	}
	$action = 'view_outgoings';
}
*/



/*-----------------------------------------------------------------------------*\

Process Section

\*-----------------------------------------------------------------------------*/
if($action == 'submit_outgoing_types')
{
	//update
	foreach($_POST['Outgoing_Types'] as $key => $value)
	{
		$query = "UPDATE ". PHPA_OUTGOING_TYPE_TABLE ." SET Outgoing_Type = '". addslashes($value) ."' WHERE ID = $key";
		$db_writer->exec($query);
	}

	//new
	if($_POST['New_Outgoing_Type'])
	{
		$query = "INSERT INTO ". PHPA_OUTGOING_TYPE_TABLE ." (Outgoing_Type,User_ID) VALUES ('". addslashes($_POST['New_Outgoing_Type']) ."',$User_ID)";
		$db_writer->exec($query);
	}
	$previous_action = $action;
	$action = 'outgoing_types';
}

if($action == 'submit_outgoings')
{
	//set User ID
	$_POST['User_ID'] = $User_ID;
	//create new Vendor
	if($_POST['New_Vendor'])
	{
		//check for existing
		$query = "SELECT ID FROM ". PHPA_VENDOR_TABLE ." WHERE User_ID = $User_ID AND Company_Name = '". $_POST['New_Vendor'] ."'";
		if(!$_POST['Vendor_ID'] = $db_reader->queryOne($query))
		{
			$query = "INSERT INTO ". PHPA_VENDOR_TABLE ." (Company_Name,User_ID) VALUES ('". $_POST['New_Vendor'] ."',$User_ID)";
			$db_writer->exec($query);
			$_POST['Vendor_ID'] = getLastID(PHPA_OUTGOING_TABLE);
		}
	}
	if($_POST['ID'])
	{
		$query = buildUPDATE($_POST,PHPA_OUTGOING_TABLE,'ID = '. $_POST['ID']);
		$_GET['Outgoing_ID'] = $_POST['ID'];
	}
	else
	{
		$query = buildINSERT($_POST,PHPA_OUTGOING_TABLE);
	}
	$db_writer->exec($query);
	if(!$_GET['Outgoing_ID'])
	{
		$_GET['Outgoing_ID'] = getLastID(PHPA_OUTGOING_TABLE);
	}

	$previous_action = $action;
	$action = 'edit_outgoings';
}

if($action == 'delete_outgoings')
{
	$Outgoing_ID = getVariable('Outgoing_ID');
	if(!$Outgoing_ID)
	{
		generic_error();
	}
	//look for paymets

	$query = "SELECT COUNT(*) FROM ". PHPA_OUTGOING_PAYMENT_TABLE ." WHERE Outgoing_ID = $Outgoing_ID";
	list($payments) = $db_reader->queryOne($query);
	if($payments == 0)
	{
		$query = "DELETE FROM ". PHPA_OUTGOING_TABLE ." WHERE ID = $Outgoing_ID";
		$db_writer->exec($query);
	}
	else $tmpl->AddThisTemplate('cant_delete_outgoing');
	$action = 'view_outgoings';
}


if($action == 'new_payment')
{
	if($_POST['Date_day'])
	{
		$Timestamp = mysqlTimestamp(mktime(0,0,0,$_POST['Date_month'],$_POST['Date_day'],$_POST['Date_year']));
	}
	else 
	{
		$Timestamp =  mysqlTimestamp(NOW);
	}
	$Outgoing_ID = getVariable('Outgoing_ID');
	$Value = $_POST['Value'];
	$Payment_Method = $_POST['Payment_Method'];
	$query = "INSERT INTO ". PHPA_OUTGOING_PAYMENT_TABLE ." (Outgoing_ID,Timestamp,Payment_Method,Value) VALUES ($Outgoing_ID,'$Timestamp','$Payment_Method','$Value')";
	$db_writer->exec($query);
	$action = 'edit_outgoings';
}

if($action == 'delete_payment')
{
	$Outgoing_ID = getVariable('Outgoing_ID');
	$Value = $_POST['Value'];
	$query = "DELETE FROM ". PHPA_OUTGOING_PAYMENT_TABLE ." WHERE Outgoing_ID = $Outgoing_ID AND Timestamp = '". $_GET['Timestamp'] ."' LIMIT 1";;
	$db_writer->exec($query);
	$action = 'edit_outgoings';
}



/*-----------------------------------------------------------------------------*\

Display Section

\*-----------------------------------------------------------------------------*/

if($action == 'outgoing_types')
{
	//get total outgoings
	$query = "SELECT SUM(O.VALUE) FROM ". PHPA_OUTGOING_TABLE ." O, ". PHPA_VENDOR_TABLE ." V  WHERE V.User_ID = $User_ID AND O.Vendor_ID = V.ID";
	$total_value = $db_reader->queryOne($query);
	$query = "SELECT * FROM ". PHPA_OUTGOING_TYPE_TABLE ." WHERE User_ID = $User_ID ORDER BY Outgoing_Type";
	$result = $db_reader->query($query);
	while($row = $result->fetchRow(MDB2_FETCHMODE_ASSOC))
	{
		if($i == '0') $i = '1';
		else $i = '0';
		$tmpl->AddVar('outgoing_type_results','I',$i);
		foreach($row as $key => $value) $tmpl->AddVar('outgoing_type_results',$key,$value);
		//lookup percentages
		$query = "SELECT SUM(Value) From ". PHPA_OUTGOING_TABLE ." WHERE Outgoing_Type_ID = ". $row['ID'];
		$value = $db_reader->queryOne($query);
		if($total_value != 0)
		{
		$percentage = round(($value/$total_value)*100,1);
		}
		$tmpl->AddVar('outgoing_type_results','PERCENTAGE',$percentage);
		$tmpl->AddVar('outgoing_type_results','GRAPH_PERCENTAGE',round($percentage*2));
		$tmpl->ParseTemplate('outgoing_type_results','a');
	}
	$tmpl->AddThisTemplate('outgoing_types');
}

if($action == 'view_outgoings')
{
	$Vendor_ID = getVariable('Vendor_ID');
	$Outgoing_Type_ID = getVariable('Outgoing_Type_ID');
	$Start_Timestamp = getVariable('Start_Timestamp');
	$End_Timestamp = getVariable('End_Timestamp');
	$Period = getVariable('Period');
	$Today = date('w');
	$Today_Timestamp = mktime(0,0,0,date('m'),date('d'),date('Y'));


	if(!$Period)
	{
		$Period = 'year';
	}

	if($Period == 'week')
	{
		if(!$Start_Timestamp)
		{
			$Start_Timestamp = $Today_Timestamp - (($Today -1) * ONE_DAY);
		}
		if(!$End_Timestamp)
		{
			$End_Timestamp =  $Today_Timestamp + ((7-($Today )) * ONE_DAY); //very start of next year
		}
	}
	elseif($Period == 'month')
	{
		if(!$Start_Timestamp)
		{
			$Start_Timestamp = mktime(0,0,0,date('m'),1,date('Y'));
		}
		if(!$End_Timestamp)
		{
			$Last_Day = date('t',$Start_Timestamp);
			$End_Timestamp = $Start_Timestamp + ($Last_Day * ONE_DAY); 
		}
	}

	elseif($Period == 'year')
	{
		if(!$Start_Timestamp)
		{
			$Start_Timestamp = mktime(0,0,0,1,1,date('Y'));
		}
		if(!$End_Timestamp)
		{
			$End_Timestamp = mktime(0,0,0,1,1,date('Y')+1);
		}
	}
	$tmpl->AddContent(viewOutgoings($Start_Timestamp,$End_Timestamp,$Period,$Vendor_ID,$Outgoing_Type_ID,$_GET['csv']));
}

if($action == 'edit_outgoings')
{
	
	$Outgoing_ID = getVariable('Outgoing_ID');
	if($Outgoing_ID || $Outgoing_ID = getVariable('Similar_ID'))
	{
		$query = "SELECT * FROM ". PHPA_OUTGOING_TABLE ." WHERE ID = $Outgoing_ID"; 
		$result = $db_reader->query($query);
		$row = $result->fetchRow(MDB2_FETCHMODE_ASSOC);
	
		if($_GET['Similar_ID'])
		{
			$tmpl->SetAttribute('payments','visibility','hidden');
			unset($row['ID']);
		}
		else
		{
			$tmpl->AddVar('payments','ID',$Outgoing_ID);
		}
		$tmpl->AddVar('edit_outgoings','PAYMENT_DATE',$row['Date']);

		$Vendor_ID = $row['Vendor_ID'];
		$Outgoing_Type_ID = $row['Outgoing_Type_ID'];

		foreach($row as $key => $value)
		{
			$tmpl->AddVar('edit_outgoings',$key,$value);
		}

		$tmpl->AddVar('edit_outgoings','DATE_SELECT',dateSelector('Date',$row['Date']));	
		$tmpl->AddVar('payments','PAYMENT_DATE_SELECT',dateSelector('Date',$row['Date']));	
		$tmpl->AddVar('payments','PAYMENT_VALUE',$row['Value']);
		//get payments
		$query = "SELECT Outgoing_ID,Timestamp,DATE_FORMAT(Timestamp,'". MYSQL_DATE_FORMAT ."') AS DATE, Value,Payment_Method
		FROM ". PHPA_OUTGOING_PAYMENT_TABLE ." WHERE Outgoing_ID = $Outgoing_ID";
		$result = $db_reader->query($query);
		if($result->NumRows() > 0)
		{
			while($row = $result->fetchRow(MDB2_FETCHMODE_ASSOC))
			{
				if($i == '0') $i = '1';
				else $i = '0';
				$tmpl->AddVar('payment_results','I',$i);
				foreach($row as $key => $value) $tmpl->AddVar('payment_results',$key,$value);
			}
		}
		else
		{
			//show message to say make payment
			$tmpl->SetAttribute('new_payment_message','visibility','show');
		}
	}
	//set todays date for new outgoing and hide payments buttom
	else
	{
		$tmpl->SetAttribute('payments','visibility','hidden');
		$tmpl->AddVar('edit_outgoings','DATE_SELECT',dateSelector('Date',NOW));	
		$tmpl->AddVar('payments','PAYMENT_DATE_SELECT',dateSelector('Date',NOW));	
	}
	$SELECT_LIST = selectlist('--Type','Outgoing_Type_ID',$Outgoing_Type_array,$Outgoing_Type_ID);
	$tmpl->AddVar('edit_outgoings','OUTGOING_TYPE_SELECT_LIST',$SELECT_LIST);
	$SELECT_LIST = selectlist('--Vendor','Vendor_ID',$Vendor_array,$Vendor_ID);
	$tmpl->AddVar('edit_outgoings','VENDOR_SELECT_LIST',$SELECT_LIST);
	$Payment_Method_array = getEnumOptions(PHPA_OUTGOING_PAYMENT_TABLE,'Payment_Method');
	foreach($Payment_Method_array as $key) 
	{
		$thearray[$key] = $key;
	}
	$tmpl->AddVar('payments','PAYMENT_METHOD_SELECT',selectlist2('Payment_Method',$thearray,'Credit Card'));

	$tmpl->AddThisTemplate('edit_outgoings');
}


if($action == 'search_outgoings')
{

	if($_POST)
	{
		$tmpl->AddContent(searchOutgoings($_POST,$_GET['csv']));
	}
	else
	{
		$SELECT_LIST = checkboxArray('Type','Outgoing_Type_IDs',$Outgoing_Type_array);
		$tmpl->AddVar('search','OUTGOING_TYPE_SELECT_LIST',$SELECT_LIST);
		$SELECT_LIST = checkboxArray('Vendor','Vendor_IDs',$Vendor_array);
		$tmpl->AddVar('search','VENDOR_SELECT_LIST',$SELECT_LIST);
		$tmpl->AddThisTemplate('search');
	}
}
if(!$action)
{
	$Today = date('w');
	$Today_Timestamp = mktime(0,0,0,date('m'),date('d'),date('Y'));

	$Period = getVariable('Period');
	$Start_Timestamp = getVariable('Start_Timestamp');
	$End_Timestamp = getVariable('End_Timestamp');

	if(!$Period)
	{
		$Period = 'year';
	}

	if($Period == 'week')
	{
		if(!$Start_Timestamp)
		{
			$Start_Timestamp = $Today_Timestamp - (($Today -1) * ONE_DAY);
		}
		if(!$End_Timestamp)
		{
			$End_Timestamp =  $Today_Timestamp + ((7-($Today )) * ONE_DAY); //very start of next year
		}
	}


	if($Period == 'year')
	{
		if(!$Start_Timestamp)
		{
			$Start_Timestamp = mktime(0,0,0,1,1,date('Y'));
		}
		if(!$End_Timestamp)
		{
			$End_Timestamp = mktime(0,0,0,1,1,date('Y')+1);
		}
	}
	$tmpl->AddContent(outgoingsGraph('outgoings',$Start_Timestamp,$End_Timestamp,$Period,false,'page=outgoings'));
}
//keep menu at top of this page
$tmpl->AddThisBeforeTemplate('menu');
?>
