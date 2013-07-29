<?php
	$tmpl->ReadTemplatesFromFile('clients.tmpl.html');

	/*
	if($action == 'create_demo_data')
	{
		//demo User_ID is 3
		$Demo_User_ID = 4;

		//Copy clients
		$query  = "SELECT ID FROM ". PHPA_CLIENT_TABLE ." WHERE User_ID = 1";
		$result = $db_reader->query($query);
		while($row = $result->fetchRow(MDB2_FETCHMODE_ASSOC))
		{
			//Assign Old Client ID
			$Old_Client_ID = $row['ID'];

			//get next ID
			$Client_ID = getNextID(PHPA_CLIENT_TABLE);

			//insert client
			$query = "INSERT INTO ". PHPA_CLIENT_TABLE ." 
			SELECT $Client_ID,$Demo_User_ID,Company_Name,Contact_First_Name, Contact_First_Surname, '','','','','','','','',''
			FROM ". PHPA_CLIENT_TABLE ."
			WHERE ID = $Old_Client_ID";
			$db_writer->exec($query);

			$tmpl->AddContent('<h2>Client</h2>'. $query .'<br>');

			//insert invoices
			$query = "SELECT ID FROM ". PHPA_INVOICE_TABLE ." WHERE Client_ID = $Old_Client_ID";
			$invoice_result = $db_reader->query($query);
			$tmpl->AddContent('<h4>Invoices</h4>');
			while($invoice_row = $invoice_result->fetchRow(MDB2_FETCHMODE_ASSOC))
			{
				$Old_Invoice_ID = $invoice_row['ID']; 
				//get next ID
				$Invoice_ID = getNextID(PHPA_INVOICE_TABLE);
				$query = "INSERT INTO ". PHPA_INVOICE_TABLE ." 
				SELECT $Invoice_ID,$Client_ID,Date, Description, Value,''
				FROM ". PHPA_INVOICE_TABLE ." 
				WHERE ID = $Old_Invoice_ID";

				$db_writer->exec($query);


				$tmpl->AddContent($query .'<br>');
				//invoice payments
				$query = "INSERT INTO ". PHPA_INVOICE_PAYMENT_TABLE ." (Invoice_ID, Timestamp, Payment_Method, Value)
				SELECT $Invoice_ID,Timestamp, Payment_Method, Value
				FROM ". PHPA_INVOICE_PAYMENT_TABLE ."
				WHERE Invoice_ID = $Old_Invoice_ID";
				$db_writer->exec($query);
				$tmpl->AddContent($query .'<br>');
			}

			//insert timesheets
			$query = "INSERT INTO ". PHPA_TIMESHEET_TABLE ." (Client_ID, Timestamp, Time, Description, Value)
			SELECT $Client_ID, Timestamp, Time, Description, Value
			FROM ". PHPA_TIMESHEET_TABLE ." 
			WHERE Client_ID = $Old_Client_ID";
			$db_writer->exec($query);
			$tmpl->AddContent($query .'<br>');

		}
	}
	*/


	/*-----------------------------------------------------------------------------*\

	Process Section

	\*-----------------------------------------------------------------------------*/

	if($_POST['Submit'] == 'Delete' || substr($action,0,6) == 'delete')
	{
		$new_action = 'delete_'. substr($action,7);
		$Confirm = getVariable('Confirm');
		if($Confirm)
		{
			$action = $new_action;
		}
		else 
		{
			//form the url for deleteing grab the ID fields	
			foreach($_POST as $key => $value)
			{
				if(substr($key,strlen($key)-2) == 'ID')
				{
					$args .= "&$key=$value";
				}
			}

			foreach($_GET as $key => $value)
			{
				if(!$_POST[$key] && substr($key,strlen($key)-2) == 'ID')
				{
					$args .= "&$key=$value";
				}
			}

			$tmpl->AddVar('confirm','ACTION','page=clients&action='.$new_action . $args);
			$tmpl->AddThisTemplate('confirm');
			$action = 'confirm';
		}
	}

	if($action == 'update_invoice_addreses')
	{
		$query = 'SELECT I.ID,Address1,Address2,City,Region,Postcode,Country 
		FROM ". PHPA_INVOICE_TABLE ." I LEFT JOIN ". PHPA_CLIENT_TABLE ." C ON (I.Client_ID = C.ID)
		WHERE C.User_ID = '. $User_ID;
		$result = $db_reader->query($query);
		while($row = $result->fetchRow(MDB2_FETCHMODE_ASSOC))
		{
			unset($address);
			while(list($key,$value)=each($row))
			{
				if($value && ($key != 'ID'))
				{
					$address .= $value ."\n";
				}
			}
			$query = "UPDATE ". PHPA_INVOICE_TABLE ." SET Invoice_Address = '". addslashes($address) ."' WHERE ID = {$row['ID']}";
			$db_writer->exec($query);
			$tmpl->AddContent("<br>$query");
		}
	}

	if($action == 'submit_client')
	{
		//Insert User ID
		$_POST['User_ID'] = $User_ID;
		$query = buildUPDATE($_POST,PHPA_CLIENT_TABLE,' ID = '. $_POST['ID']);
		$db_writer->exec($query);
		//set ID
		$_GET['Client_ID'] = $_POST['ID'];
		$action = 'view_client';
	}

	if($action == 'delete_client')
	{
		$Client_ID = getVariable('Client_ID');
		$Confirm = $_GET['Confirm'];
		if(!$Client_ID)
		{
			generic_error();
		}
		//look for payments

		$query = "SELECT COUNT(*) FROM ". PHPA_INVOICE_TABLE ." WHERE Client_ID = $Client_ID";
		$invoices = $db_reader->queryOne($query);

		$query = "SELECT COUNT(*) FROM ". PHPA_TIMESHEET_TABLE ." WHERE Client_ID = $Client_ID";
		$timesheets = $db_reader->queryOne($query);
		if($payments + $timesheets == 0 || $Confirm == true)
		{
			//Invoices and Payments
			$query = "SELECT ID FROM ". PHPA_INVOICE_TABLE ." WHERE Client_ID = $Client_ID";
			$result = $db_reader->query($query);
			while($row = $result->fetchRow(MDB2_FETCHMODE_ASSOC))
			{
				deleteInvoice($row['ID']);
			}

			//Invoices and Payments
			$query = "SELECT ID FROM ". PHPA_PROJECT_TABLE ." WHERE Client_ID = $Client_ID";
			$result = $db_reader->query($query);
			unset($IDs);
			while($row = $result->fetchRow(MDB2_FETCHMODE_ASSOC))
			{
				$IDs[] = $row['ID'];
			}
			if($IDs)
			{
				$IDs = implode(',',$IDs);
				$query = "DELETE FROM ". PHPA_PROJECT_TIMESHEET_TABLE ." WHERE Project_ID IN ($IDs)";
				$db_writer->exec($query);
				$query = "DELETE FROM ". PHPA_PROJECT_INVOICE_TABLE ." WHERE Project_ID IN ($IDs)";
				$db_writer->exec($query);
				$query = "DELETE FROM ". PHPA_PROJECT_TABLE ." WHERE ID IN ($IDs)";
				$db_writer->exec($query);
			}


			//Quotes
			$query = "DELETE FROM ". PHPA_QUOTE_TABLE ." WHERE Client_ID = $Client_ID";
			$db_writer->exec($query);

			//Timesheets
			$query = "DELETE FROM ". PHPA_TIMESHEET_TABLE ." WHERE Client_ID = $Client_ID";
			$db_writer->exec($query);

			//Client
			$query = "DELETE FROM ". PHPA_CLIENT_TABLE ." WHERE ID = $Client_ID";
			$db_writer->exec($query);
			$action = 'list_clients';
		}
		else 
		{
			$tmpl->AddVar('cant_delete_invoice','ACTION',"page=clients&action=$action&Client_ID=$Client_ID");
			$tmpl->AddThisTemplate('cant_delete_invoice');
			$action = 'view_client';
		}
	}

	if($action == 'create_repeat_invoice' && $_GET['Invoice_ID'])
	{
		 if($New_Invoice_ID = createRepeatInvoice($_GET['Invoice_ID']))
		 {
			 $action = "edit_invoice";
			 $_GET['Invoice_ID' ] = $New_Invoice_ID;
		 }
		 else
		 {
			 $action = 'edit_repeat_invoice';
		 }
	}

	if($action == 'submit_invoice')
	{
		if(!$_POST['Reminders'])
		{
			$_POST['Reminders'] = 'no';	
		}
		//format date
		if($_GET['Invoice_ID'])
		{
			$query = buildUPDATE($_POST,PHPA_INVOICE_TABLE,'ID = '. $_GET['Invoice_ID']);
			$Invoice_ID = $_GET['Invoice_ID'];
		}
		else
		{
			$query = buildINSERT($_POST,PHPA_INVOICE_TABLE);	
			$_GET['Invoice_ID'] = getNextID(PHPA_INVOICE_TABLE);
		}	
		$db_writer->exec($query);
		$Client_ID = getVariable('Client_ID');
		//check for repeat invoice
		if($_POST['Submit'] == 'Make Repeat Invoice')
		{
			//submit new repeat invoice, not yet active
			$query = "INSERT IGNORE INTO ". PHPA_REPEAT_INVOICE_TABLE ."
			(Invoice_ID,Day,Month)
			VALUES({$_GET['Invoice_ID']},{$_POST['Date_day']},{$_POST['Date_month']})";
			$db_writer->exec($query);

			//put one in the log
			$query = "INSERT IGNORE INTO ". PHPA_REPEAT_INVOICE_LOG_TABLE ."
			(Repeat_Invoice_ID,Invoice_ID)
			VALUES({$_GET['Invoice_ID']},{$_GET['Invoice_ID']})";
			$db_writer->exec($query);
			$action = 'edit_repeat_invoice';
		}
		else
		{
			$action = 'edit_invoice';
		}
	}

	if($action == 'delete_invoice')
	{
		$Invoice_ID = getVariable('Invoice_ID'); 
		$Client_ID = getVariable('Client_ID');
		$Double_Confirm = getVariable('Double_Confirm');
		if(!$Invoice_ID)
		{
			generic_error();
		}

		//look for Repeat Invoice
		$query = "SELECT COUNT(*) FROM ". PHPA_REPEAT_INVOICE_TABLE ." WHERE Invoice_ID = $Invoice_ID";
		list($repeats) = $db_reader->queryOne($query);
		//look for paymets
		$query = "SELECT COUNT(*) FROM ". PHPA_INVOICE_PAYMENT_TABLE ." WHERE Invoice_ID = $Invoice_ID";
		list($payments) = $db_reader->queryOne($query);
		if($repeats + $payments == 0 || $Double_Confirm == true)
		{
			deleteInvoice($Invoice_ID);
			$action = 'view_client';
		}
		if($payments > 0 && $Double_Confirm != true) 
		{
			$tmpl->AddVar('cant_delete_invoice','ACTION',"page=clients&action=$action&Client_ID=$Client_ID&Invoice_ID=$Invoice_ID");
			$tmpl->AddThisTemplate('cant_delete_invoice');
		}
		if($repeats > 0 && $Double_Confirm != true)
		{
			$tmpl->AddVar('cant_delete_repeat_invoice','ACTION',"page=clients&action=$action&Client_ID=$Client_ID&Invoice_ID=$Invoice_ID");
			$tmpl->AddThisTemplate('cant_delete_repeat_invoice');
		}
	}


	if($action == 'submit_repeat_invoice' && $_GET['Invoice_ID'])
	{
		if(!$_POST['Reminders'])
		{
			$_POST['Reminders'] = 'no';	
		}
		$query = buildUPDATE($_POST,PHPA_REPEAT_INVOICE_TABLE,'Invoice_ID = '. $_GET['Invoice_ID']);
		$Invoice_ID = $_GET['Invoice_ID'];
		$db_writer->exec($query);
		//check for repeat invoice
		$action = 'edit_repeat_invoice';
	}



	if($action == 'new_payment')
	{
		$Date = $_POST['Date'];
		$Value = $_POST['Value'];
		$Payment_Method = $_POST['Payment_Method'];

		if(!$Invoice_ID)
		{
			$Invoice_ID = getVariable('Invoice_ID');
		}

		if($_POST['Date_day'])
		{
			$Timestamp = mysqlTimestamp(mktime(0,0,0,$_POST['Date_month'],$_POST['Date_day'],$_POST['Date_year']));
		}
		else
		{
			$Timestamp =  mysqlTimestamp(NOW);
		}
		$query = "INSERT INTO ". PHPA_INVOICE_PAYMENT_TABLE ." (Invoice_ID,Timestamp,Payment_Method,Value) VALUES ($Invoice_ID,'$Timestamp','$Payment_Method','$Value')";
		$db_writer->exec($query);
		$action = 'edit_invoice';
	}

	if($action == 'delete_payment')
	{
		$Invoice_Payment_ID = $_GET['Invoice_Payment_ID'];
		$query = "DELETE FROM ". PHPA_INVOICE_PAYMENT_TABLE ." WHERE ID = $Invoice_Payment_ID LIMIT 1";;
		$db_writer->exec($query);
		$action = 'edit_invoice';
	}

	if($action == 'submit_quote')
	{
		//format date
		if($_GET['Quote_ID'])
		{
			$query = buildUPDATE($_POST,PHPA_QUOTE_TABLE,'ID = '. $_GET['Quote_ID']);
			$Quote_ID = $_GET['Quote_ID'];
		}
		else
		{
			$_GET['Quote_ID'] = getNextID(PHPA_QUOTE_TABLE);
			$query = buildINSERT($_POST,PHPA_QUOTE_TABLE);	
		}	
		$action = 'edit_quote';
		$db_writer->exec($query);
		$Client_ID = getVariable('Client_ID');
	}

	if($action == 'delete_quote')
	{
		$Quote_ID = getVariable('Quote_ID'); 
		$Client_ID = getVariable('Client_ID');
		if(!$Quote_ID)
		{
			generic_error();
		}
		//look for paymets
		$Confirm = getVariable('Confirm');
		if($Confirm == true)
		{
			$query = "DELETE FROM ". PHPA_QUOTE_TABLE ." WHERE ID = $Quote_ID";
			$db_writer->exec($query);
			$action = 'view_client';
		}
		else 
		{
			$tmpl->AddVar('confirm','ACTION',"page=clients&action=$action&Client_ID=$Client_ID&Quote_ID=$Quote_ID");
			$tmpl->AddThisTemplate('confirm');
		}
	}


	if($action == 'submit_project')
	{

		if($_GET['Project_ID'])
		{
			$query = buildUPDATE($_POST,PHPA_PROJECT_TABLE,'ID = '. $_GET['Project_ID']);
			$Project_ID = $_GET['Project_ID'];
		}
		else
		{
			$_GET['Project_ID'] = getNextID(PHPA_PROJECT_TABLE);
			$query = buildINSERT($_POST,PHPA_PROJECT_TABLE);	
		}	
		$action = 'edit_project';
		$db_writer->exec($query);
		$Client_ID = getVariable('Client_ID');
	}

	if($action == 'delete_project')
	{
		$Project_ID = getVariable('Project_ID'); 
		$Client_ID = getVariable('Client_ID');
		if(!$Project_ID)
		{
			generic_error();
		}
		//look for paymets
		$Confirm = getVariable('Confirm');
		if($Confirm == true)
		{
			$query = "DELETE FROM ". PHPA_PROJECT_TABLE ." WHERE ID = $Project_ID";
			$db_writer->exec($query);
			$action = 'view_client';
		}
		else 
		{
			$tmpl->AddVar('confirm','ACTION',"page=clients&action=$action&Client_ID=$Client_ID&Project_ID=$Project_ID");
			$tmpl->AddThisTemplate('confirm');
		}
	}



	if($action == 'submit_timesheet')
	{
		$_POST['User_ID'] = $User_ID;
		if($_POST['Opened'])
		{
			$Timestamp = strtotime($_POST['Opened']);
			$_POST['Timestamp'] = mysqlTimestamp($Timestamp);
		}
		if($_POST['ID'])
		{
			$query = buildUPDATE($_POST,PHPA_TIMESHEET_TABLE,'ID = '. $_POST['ID']);
		}
		else
		{
			$_POST['ID'] = getNextID(PHPA_TIMESHEET_TABLE);
			$query = buildINSERT($_POST,PHPA_TIMESHEET_TABLE);
		}
		$db_writer->exec($query);

		//Project Timesheets
		$query  = "DELETE FROM ". PHPA_PROJECT_TIMESHEET_TABLE ." WHERE Timesheet_ID = {$_POST['ID']}";
		$db_writer->exec($query);
		if($_POST['Project_ID'])
		{
			$query = "INSERT INTO ". PHPA_PROJECT_TIMESHEET_TABLE ." (Project_ID,Timesheet_ID) VALUES ({$_POST['Project_ID']},{$_POST['ID']})";
		}
		$db_writer->exec($query);
		$Client_ID = $_POST['Client_ID'];
		$action = 'view_timesheet';
	}

	if($action == 'delete_timesheet')
	{
		$Timesheet_ID = getVariable('Timesheet_ID');
		$Client_ID = getVariable('Client_ID');
		$query = "DELETE FROM ". PHPA_TIMESHEET_TABLE .' WHERE ID = '. $Timesheet_ID .' LIMIT 1';
		$db_writer->exec($query);
		$action = 'view_timesheet';
	}


	if($action == 'invoice_wizard')
	{
		include('invoice_wizard.php');
	}

	/*-----------------------------------------------------------------------------*\

	Display Section

	\*-----------------------------------------------------------------------------*/

	//default to list clients
	if(!$action)
	{
		$action = 'list_clients';
	}

	#new actdions from form at top
	if($action == 'new')
	{
		$action = 'edit_'. getVariable('Submit');
	}

	if($action == 'new_client')
	{
		//create a blank entry
		$query = "INSERT INTO ". PHPA_CLIENT_TABLE ." (Company_Name) VALUES ('New Client')";
		$db_reader->query($query);
		$_GET['Client_ID'] = getLastID(PHPA_CLIENT_TABLE);
		$action = 'view_client';
	}


	if($action == 'list_clients')
	{
		if($order = getVariable('order'))
		{
			$ORDER_BY = 'ORDER BY '. $order .' ';
		}
		else
		{
			$ORDER_BY = 'ORDER BY Company_Name ';
		}
		if($order_direction = getVariable('order_direction'))
		{
			$ORDER_BY .= $order_direction;
		}

		$query = "SELECT C.ID, C.Company_Name, C.City, SUM(IF(TS.ID,1,0)) AS Timesheets
		FROM ". PHPA_CLIENT_TABLE ." C LEFT JOIN ". PHPA_TIMESHEET_TABLE ." TS ON(TS.Client_ID = C.ID) 
		WHERE C.User_ID = $User_ID
		GROUP BY C.ID $ORDER_BY";
		$result = $db_reader->query($query);
		if($result->NumRows() == 0)
		{
			$tmpl->AddThisTemplate('no_clients');

		}
		else
		{
			while($row = $result->fetchRow(MDB2_FETCHMODE_ASSOC))
			{
				if($i) $i=0;
				else $i=1;
				$tmpl->AddVar('results','I',$i);
				foreach($row as $key => $value)
				{
					if(!$headers[$key])
					{
						$headers[$key] = $key;
					}
					$tmpl->AddVar('results',$key,$value);
				}

				//count  quotes
				$query = "SELECT COUNT(*) FROM ". PHPA_QUOTE_TABLE ." WHERE Client_ID = {$row['ID']}";
				$QUOTES = $db_reader->queryOne($query);
				$tmpl->AddVar('results','QUOTES',$QUOTES);

				//count  projects
				$query = "SELECT COUNT(*) FROM ". PHPA_PROJECT_TABLE ." WHERE Client_ID = {$row['ID']}";
				$PROJECTS = $db_reader->queryOne($query);
				$tmpl->AddVar('results','PROJECTS',$PROJECTS);

				$tmpl->ClearTemplate('unpaid_invoices');
				$INVOICES = 0;
				//count  invoices
				$query = "SELECT SUM(IF(IP.Value,IP.Value,0)) AS Paid,I.Value,I.ID,I.Reference,DATE_FORMAT(I.Date,'". MYSQL_DATE_FORMAT ."') AS Date
				FROM ". PHPA_INVOICE_TABLE ." I LEFT JOIN ". PHPA_INVOICE_PAYMENT_TABLE ." IP ON(IP.Invoice_ID = I.ID)
				WHERE I.Client_ID = {$row['ID']}
				GROUP BY I.ID";

				$unpaid_invoice_result = $db_reader->query($query);
				while($unpaid_invoice_row = $unpaid_invoice_result->fetchRow(MDB2_FETCHMODE_ASSOC))
				{
					if($unpaid_invoice_row['Paid'] < $unpaid_invoice_row['Value'])
					{
						$this_BALANCE = ($unpaid_invoice_row['Value'] - $unpaid_invoice_row['Paid']);
						$tmpl->AddVar('unpaid_invoices','DATE',$unpaid_invoice_row['Date']);
						$tmpl->AddVar('unpaid_invoices','PAID',$unpaid_invoice_row['Paid']);
						$tmpl->AddVar('unpaid_invoices','VALUE',$unpaid_invoice_row['Value']);
						$tmpl->AddVar('unpaid_invoices','BALANCE',$this_BALANCE);
						$tmpl->AddVar('unpaid_invoices','ID',$unpaid_invoice_row['ID']);
						$tmpl->AddVar('unpaid_invoices','REFERENCE',$unpaid_invoice_row['Reference']);
						$tmpl->ParseTemplate('unpaid_invoices','a');
						$BALANCE[$row['ID']] += $this_BALANCE;
					}
					$INVOICES++;
				}	
				$tmpl->AddVar('results','TOTAL_UNPAID',intval($BALANCE[$row['ID']]));
				$tmpl->AddVar('results','INVOICES',$INVOICES);
				$tmpl->ParseTemplate('results','a');		
				$CLIENTS++;
			}
			$tmpl->AddVar('list_clients','CLIENTS',$CLIENTS);
			if($BALANCE)
			{
				$tmpl->AddVar('list_clients','GRAND_TOTAL_UNPAID',array_sum($BALANCE));
			}

			//sort direction on the headers
			if($headers)
			{
				foreach($headers as $key)
				{
					if($key == $order)
					{
						if($order_direction == 'ASC')
						{
							$this_order_direction = 'DESC';
						}
						else
						{
							$this_order_direction = 'ASC';
						}
					}
					else
					{
						$this_order_direction = 'DESC';
					}
					$tmpl->AddVar('list_clients',$key,$this_order_direction);
				}
			}
			$tmpl->AddThisTemplate('list_clients');
		}
	}


	if($action == 'view_client')
	{
		$Client_ID = getVariable('Client_ID'); 
		if(!$Client_ID)
		{
			generic_error();
		}
		//main details
		$FORM = updateForm(PHPA_CLIENT_TABLE,"?page=clients&action=submit_client&Client_ID=$Client_ID","ID = $Client_ID",'Client Details Edit');
		$tmpl->AddVar('view_client','FORM',$FORM);
		$tmpl->AddVar('view_client','CLIENT_ID',$Client_ID);

		//count invoices, timesheets etc.
		$count_array = array(PHPA_TIMESHEET_TABLE,PHPA_INVOICE_TABLE,PHPA_PROJECT_TABLE,PHPA_QUOTE_TABLE);
		foreach($count_array as $key)
		{
			$query = "SELECT COUNT(*) FROM ". $key ." WHERE Client_ID = $Client_ID";
			$count = $db_reader->queryOne($query);
			$item = str_replace('_tbl','',$key);
			$item = str_replace(DB_PREFIX,'',$item);
			$tmpl->AddVar('view_client',$item.'s',$count);
		}

		$tmpl->AddThisTemplate('view_client');
	}


	/*-----------------------------------------------------------------------------*\

	Invoice & Statment Section

	\*-----------------------------------------------------------------------------*/

	if($action == 'view_forecast_repeat_invoices' || $action == 'view_repeat_invoices' || $action == 'view_invoices' || $action == 'view_payments' || $action == 'view_projects' || $action == 'view_quotes')
	{
		$Client_ID = getVariable('Client_ID');
		$Today_Timestamp = NOW;
		$Period = getVariable('Period');
		$Start_Timestamp = getVariable('Start_Timestamp');
		$End_Timestamp = getVariable('End_Timestamp');
		if(!$Period)
		{
			$Period = 'year';
		}
		if($Period == 'day')
		{
			if(!$Start_Timestamp)
			{
				$Start_Timestamp = $Today_Timestamp;
			}
			if(!$End_Timestamp)
			{
				$End_Timestamp =  $Today_Timestamp  + ONE_DAY; //very start of next year
			}
		}	
		elseif($Period == 'week')
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
			$Last_Day = date('t',$Start_Timestamp);
			$End_Timestamp = $Start_Timestamp + ($Last_Day*ONE_DAY); //very start of next month day
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
		switch($action)
		{
		case('view_forecast_repeat_invoices'):
			$tmpl->AddContent(viewForecastRepeatInvoices($Start_Timestamp,$End_Timestamp,$Period,$Client_ID));
			break;
		case('view_repeat_invoices'):
			$tmpl->AddContent(viewRepeatInvoices($Start_Timestamp,$End_Timestamp,$Period,$Client_ID));
			break;
		case('view_invoices'):
			$tmpl->AddContent(viewInvoices($Start_Timestamp,$End_Timestamp,$Period,$Client_ID));
			break;
		case('view_payments'):
			$tmpl->AddContent(viewPayments($Start_Timestamp,$End_Timestamp,$Period,$Client_ID));
			break;
		case('view_projects'):
			$tmpl->AddContent(viewProjects($Start_Timestamp,$End_Timestamp,$Period,$Client_ID));
			break;
		case('view_quotes'):
			$tmpl->AddContent(viewQuotes($Start_Timestamp,$End_Timestamp,$Period,$Client_ID));
			break;
		}
	}



	if($action == 'edit_invoice')
	{
		$Client_ID = getVariable('Client_ID');
		$Invoice_ID = getVariable('Invoice_ID');
		if($Invoice_ID == 'OPENING_BALANCE')
		{
			generic_error();
		}
		elseif($Invoice_ID)
		{
			$query = "SELECT I.Reference,SUM(IF(IP.Invoice_ID,1,0)) AS PAYMENTS,SUM(IP.Value) AS PAYMENTS_VALUE,I.Client_ID,UNIX_TIMESTAMP(I.DATE) AS Date,I.Value,I.Invoice_Address,I.Description,IF(I.Reminders='yes','checked=\"checked\"','') AS REMINDER, RPL.Repeat_Invoice_ID
			FROM ". PHPA_INVOICE_TABLE ." I LEFT JOIN ". PHPA_INVOICE_PAYMENT_TABLE ." IP ON(IP.Invoice_ID = I.ID) LEFT JOIN ". PHPA_REPEAT_INVOICE_LOG_TABLE ." RPL ON(RPL.Invoice_ID = I.ID)
			WHERE I.ID = $Invoice_ID
			GROUP BY I.ID";
			$result = $db_reader->query($query);
			$row = $result->fetchRow(MDB2_FETCHMODE_ASSOC);
			foreach($row as $key => $value) 
			{
				$tmpl->AddVar('edit_invoice',$key,$value);
			}
			$balance = $row['Value'] - $row['PAYMENTS_VALUE'];
			$tmpl->AddVar('edit_invoice','BALANCE',$balance);
			//get date select
			settype($row['Date'],'integer');
			$tmpl->AddVar('edit_invoice','DATE_SELECT',dateSelector('Date',$row['Date']));
			$tmpl->AddVar('edit_invoice','PAYMENT_DATE_SELECT',dateSelector('Date',''));
			$tmpl->AddVar('edit_invoice','CLIENT_SELECT',selectlist2('Client_ID',$Client_array,$row['Client_ID']));
			$Client_ID = $row['Client_ID'];

			if($row['Repeat_Invoice_ID'])
			{
				$tmpl->SetAttribute('repeat_invoice_link','visibility','show');
				$tmpl->AddVar('repeat_invoice_link','INVOICE_ID',$row['Repeat_Invoice_ID']);
			}
			else
			{
				 $tmpl->SetAttribute('make_repeat_invoice_button','visibility','show');
			}

			//get payments
			$query = "SELECT ID,Invoice_ID,DATE_FORMAT(Timestamp,'". MYSQL_DATE_FORMAT ."') AS DATE, Value,Payment_Method
			FROM ". PHPA_INVOICE_PAYMENT_TABLE ." WHERE Invoice_ID = $Invoice_ID";
			$result = $db_reader->query($query);
			while($row = $result->fetchRow(MDB2_FETCHMODE_ASSOC))
			{
				if($i) $i=0;
				else $i=1;
				$tmpl->AddVar('payment_results','I',$i);
				foreach($row as $key => $value) $tmpl->AddVar('payment_results',$key,$value);
				$tmpl->ParseTemplate('payment_results','a');
				$tmpl->SetAttribute('payment_results','visibility','show');
			}

			//get sent mail
			$query = "SELECT Mail_ID,Invoice_ID,Type FROM ". PHPA_INVOICE_MAIL_LOOKUP_TABLE ." WHERE Invoice_ID = $Invoice_ID";
			$result = $db_reader->query($query);
			while($row = $result->fetchRow(MDB2_FETCHMODE_ASSOC))
			{
				foreach($row as $key => $value) $tmpl->AddVar('sent_reminders',$key,$value);
				$tmpl->SetAttribute('sent_reminders','visibility','show');
				$tmpl->ParseTemplate('sent_reminders','a');
			}


		}
		elseif(!$Client_ID)
		{
			$tmpl->AddVar('choose_client','CLIENT_SELECT_LIST',selectlist2('Client_ID',$Client_array,''));
			$tmpl->AddVar('choose_client','ACTION','edit_invoice');
			$tmpl->AddThisTemplate('choose_client');
			$tmpl->SetAttribute('edit_invoice','visibility','hidden');
		}
		elseif($Client_ID)
		{
			//get Address
			$query = "SELECT Address1,Address2,City,Region,Postcode FROM ". PHPA_CLIENT_TABLE ." WHERE ID = $Client_ID";
			$result = $db_reader->query($query);
			$row = $result->fetchRow();
			$address = implode("\n",$row);
			$tmpl->AddVar('edit_invoice','CLIENT_ID',$Client_ID);
			$tmpl->AddVar('edit_invoice','Invoice_Address',$address);
			$tmpl->AddVar('edit_invoice','DATE_SELECT',dateSelector('Date',''));
			$tmpl->AddVar('edit_invoice','PAYMENT_DATE_SELECT',dateSelector('Date',$row['Date']));
			$tmpl->AddVar('edit_invoice','CLIENT_SELECT',selectlist2('Client_ID',$Client_array,$Client_ID));

			//check for auto numbering
			if(AUTO_NUMBER_INVOICES == 'yes')
			{
				$query = "SELECT MAX(Reference) 
				FROM ". PHPA_INVOICE_TABLE ." I LEFT JOIN ". PHPA_CLIENT_TABLE ." C ON(I.Client_ID = C.ID) 
				WHERE C.User_ID = $User_ID";
				$Ref = $db_reader->queryOne($query);
				if(is_numeric($Ref) || !$Ref)
				{
					$Ref++;
					$tmpl->AddVar('edit_invoice','REFERENCE',$Ref);
				}
			}

		}

		$Payment_Method_array = getEnumOptions(PHPA_INVOICE_PAYMENT_TABLE,'Payment_Method');
		foreach($Payment_Method_array as $key) 
		{
			$thearray[$key] = $key;
		}
		$tmpl->AddVar('edit_invoice','PAYMENT_METHOD_SELECT',selectlist2('Payment_Method',$thearray,'Cheque'));

		$tmpl->AddVar('edit_invoice','INVOICE_ID',$Invoice_ID);
		$tmpl->AddVar('edit_invoice','CLIENT',$Client_array[$Client_ID]);
		$tmpl->AddThisTemplate('edit_invoice');
	}

	if($action == 'edit_repeat_invoice')
	{
		$Invoice_ID = getVariable('Invoice_ID');
		//get repeate details and basic invoice details
		$query = "SELECT I.Client_ID,I.Date,RI.Invoice_ID,I.Value,RI.Day,RI.Month,IF(RI.Reminders='yes','checked=\"checked\"','') AS Reminders,IF(RI.Active='yes','selected=\"selected\"','') AS Active,IF(RI.Active='no','selected=\"selected\"','') AS Inactive,C.Company_Name,I.Client_ID
		FROM ". PHPA_INVOICE_TABLE ." I, ". PHPA_REPEAT_INVOICE_TABLE ." RI, ". PHPA_CLIENT_TABLE ." C
		WHERE I.ID = $Invoice_ID AND I.ID = RI.Invoice_ID AND C.ID = I.Client_ID";
		$result = $db_reader->query($query);
		$row = $result->FetchRow(MDB2_FETCHMODE_ASSOC);
		foreach($row as $key => $value)
		{
			$tmpl->AddVar('edit_repeat_invoice',$key,$value);
		}

		//date select
		//day
		$tmpl->AddVar('day_results','DAY','every day');
		$tmpl->AddVar('day_results','I','*');
		if($row['Day']=='*')
		{
			$tmpl->AddVar('day_results','SELECTED','selected="selected"');
		}
		else
		{
		}
		$tmpl->ParseTemplate('day_results','a');
		for($i=1;$i<=31;$i++)
		{
			if($row['Day']==$i)
			{
				$tmpl->AddVar('day_results','SELECTED','selected="selected"');
			}
			else
			{
				$tmpl->AddVar('day_results','SELECTED','');
			}
			$tmpl->AddVar('day_results','I',$i);
			$tmpl->AddVar('day_results','DAY',$i);
			$tmpl->ParseTemplate('day_results','a');
		}

		//month
		$tmpl->AddVar('month_results','MONTH','every month');
		$tmpl->AddVar('month_results','I','*');
		if($row['Month']=='*')
		{
			$tmpl->AddVar('month_results','SELECTED','selected="selected"');
		}
		$tmpl->ParseTemplate('month_results','a');
		for($i=1;$i<=12;$i++)
		{
			if($row['Month']==$i)
			{
				$tmpl->AddVar('month_results','SELECTED','selected="selected"');
			}
			else
			{
				$tmpl->AddVar('month_results','SELECTED','');
			}
			$tmpl->AddVar('month_results','MONTH',date('M',mktime(0,0,0,$i,1,2000)));
			$tmpl->AddVar('month_results','I',$i);
			$tmpl->ParseTemplate('month_results','a');
		}

		//get Existing Invoices
		$query = "SELECT I.Client_ID,I.Value - SUM(IF(IP.Value>0,IP.Value,0)) AS Due,SUM(IF(IP.Value>0,1,0)) AS Payments, I.ID AS Invoice_ID,I.Reference,I.Value,I.Date
		FROM ". PHPA_INVOICE_TABLE ." I LEFT JOIN ". PHPA_INVOICE_PAYMENT_TABLE ." IP ON(IP.Invoice_ID = I.ID), ". PHPA_REPEAT_INVOICE_LOG_TABLE ." RI
		WHERE RI.Repeat_Invoice_ID = $Invoice_ID AND RI.Invoice_ID = I.ID
		GROUP BY I.ID";
		$result = $db_reader->query($query);
		while($row = $result->fetchRow(MDB2_FETCHMODE_ASSOC))
		{
			if($i) $i=0;
			else $i=1;
			$tmpl->AddVar('repeat_invoice_results','I',$i);
			foreach($row as $key => $value)
			{
				$tmpl->AddVar('repeat_invoice_results',$key,$value);
			}
			$tmpl->ParseTemplate('repeat_invoice_results','a');
			$INVOICES[] = $row['Value'];
			$TOTAL_DUE += $row['Due'];
		}
		if($INVOICES)
		{
			$tmpl->SetAttribute('repeat_invoice_results','visibility','show');
			$tmpl->AddVar('edit_repeat_invoices','INVOICES',sizeof($INVOICES));
			$tmpl->AddVar('edit_repeat_invoices','TOTAL',array_sum($INVOICES));
			$tmpl->AddVar('edit_repeat_invoices','TOTAL_DUE',$TOTAL_DUE);
		}

		$tmpl->AddThisTemplate('edit_repeat_invoice');

	}


	if($action == 'print_invoice' && $_GET['Invoice_ID'])
	{
		include(INCLUDE_PATH .'/includes/PDF_invoice.php');
		$Invoice = new PDF_Invoice();
		$Invoice->Open();

		//get details
		$Invoice_ID = $_GET['Invoice_ID'];
		$query = "SELECT I.Reference AS ID,C.Company_Name,I.Client_ID,I.Value,DATE_FORMAT(I.Date,'". MYSQL_DATE_FORMAT ."') AS Date,I.Description,I.Invoice_Address
		FROM ". PHPA_INVOICE_TABLE ." I LEFT JOIN ". PHPA_CLIENT_TABLE ." C ON(I.Client_ID = C.ID)
		WHERE I.ID = $Invoice_ID";
		$result = $db_reader->query($query);
		$row = $result->fetchRow(MDB2_FETCHMODE_ASSOC);
		$Invoice->Add_Details($row);
		$Invoice->Print_Invoice();
		if(getVariable('mail'))
		{
			$file = INCLUDE_PATH .'/temp/invoice.pdf';
			$Invoice->Output($file);
			sendClientInvoice($Invoice_ID,$file);
			$tmpl->AddThisTemplate('invoice_sent');
			$_GET['Client_ID'] = $row['Client_ID'];
			$action = 'edit_invoice';
		}
		else
		{
			$Invoice->Output();
			exit();
		}
	}

	if($action == 'print_invoice_reminder' && $_GET['Invoice_ID'])
	{
		include(INCLUDE_PATH .'/includes/PDF_invoice_reminder.php');
		$Invoice = new PDF_Invoice_reminder();
		$Invoice->Open();

		//get details
		$Invoice_ID = $_GET['Invoice_ID'];
		$query = "SELECT I.Reference AS ID,C.Company_Name,CONCAT(C.Contact_First_Name,' ',Contact_Surname) AS Contact_Name,I.Value,DATE_FORMAT(I.Date,'". MYSQL_DATE_FORMAT ."') AS Date,I.Description,I.Invoice_Address
		FROM ". PHPA_INVOICE_TABLE ." I LEFT JOIN ". PHPA_CLIENT_TABLE ." C ON(I.Client_ID = C.ID)
		WHERE I.ID = $Invoice_ID";
		$result = $db_reader->query($query);
		$row = $result->fetchRow(MDB2_FETCHMODE_ASSOC);
		$Invoice->Add_Details($row);
		$Invoice->Print_Invoice();
		$Invoice->Output();
		exit();
	}


	if($action == 'print_receipt' && $_GET['Invoice_Payment_ID'])
	{
		include(INCLUDE_PATH .'/includes/PDF_invoice_payment.php');
		$Invoice = new PDF_Invoice_payment();
		$Invoice->Open();

		//get details
		$Invoice_Payment_ID = $_GET['Invoice_Payment_ID'];
		$query = "SELECT I.Reference AS ID,C.Company_Name,CONCAT(C.Contact_First_Name,' ',Contact_Surname) AS Contact_Name,IP.Value,DATE_FORMAT(IP.Timestamp,'". MYSQL_DATE_FORMAT ."') AS Date,IP.Value,DATE_FORMAT(I.Date,'". MYSQL_DATE_FORMAT ."') AS Invoice_Date,I.Invoice_Address
		FROM ". PHPA_INVOICE_PAYMENT_TABLE ." IP LEFT JOIN ". PHPA_INVOICE_TABLE ." I ON(I.ID = IP.Invoice_ID) LEFT JOIN ". PHPA_CLIENT_TABLE ." C ON(I.Client_ID = C.ID)
		WHERE IP.ID = $Invoice_Payment_ID";
		$result = $db_reader->query($query);
		$row = $result->fetchRow(MDB2_FETCHMODE_ASSOC);
		$Invoice->Add_Details($row);
		$Invoice->Print_Invoice();
		$Invoice->Output();
		exit();
	}


	if($action == 'view_statement')
	{
		$Client_ID =getVariable('Client_ID');
		if(!$Client_ID)
		{
			generic_error();
		}
		$Start_Timestamp = getVariable('Start_Timestamp');
		if(!$Start_Timestamp)
		{
			//set to this year
			$Start_Timestamp = mktime(0,0,0,1,1,date('Y'));
		}
		$End_Timestamp = $_GET['End_Timestamp'];
		if(!$End_Timestamp)
		{
			//set to one year l8ter
			$End_Timestamp = $Start_Timestamp + ONE_YEAR; 
		}
		$MYSQL_END_TIMESTAMP = mysqlTimestamp($End_Timestamp);
		$MYSQL_END_DATE = mysqlDate($End_Timestamp);
		//get Invoices
		$query = "SELECT I.ID,I.Reference,UNIX_TIMESTAMP(Date) AS TIMESTAMP,Value 
		FROM ". PHPA_INVOICE_TABLE ." I
		WHERE Client_ID = $Client_ID AND Date <= '$MYSQL_END_DATE'";
		$result = $db_reader->query($query);
		while($row = $result->fetchRow(MDB2_FETCHMODE_ASSOC))
		{
			//stop duplication
			while($Statement[$row['TIMESTAMP']])
			{
				$row['TIMESTAMP']++;
			}
			$Statement[$row['TIMESTAMP']]['Invoice'] = $row['Value'];
			$Statement[$row['TIMESTAMP']]['Invoice_ID'] = $row['ID'];
			$Statement[$row['TIMESTAMP']]['Reference'] = $row['Reference'];
		}

		//get Payments
		$query = "SELECT UNIX_TIMESTAMP(Timestamp) AS TIMESTAMP,IP.Value,IP.Invoice_ID,I.Reference 
		FROM ". PHPA_INVOICE_PAYMENT_TABLE ." IP, ". PHPA_INVOICE_TABLE ." I 
		WHERE I.Client_ID = $Client_ID AND IP.Timestamp <= '$MYSQL_END_TIMESTAMP' AND IP.Invoice_ID = I.ID";
		$result = $db_reader->query($query);
		while($row = $result->fetchRow(MDB2_FETCHMODE_ASSOC))
		{
			//stop duplication
			while($Statement[$row['TIMESTAMP']])
			{
				$row['TIMESTAMP']++;
			}
			$Statement[$row['TIMESTAMP']]['Payment'] = $row['Value'];
			$Statement[$row['TIMESTAMP']]['Invoice_ID'] = $row['Invoice_ID'];
			$Statement[$row['TIMESTAMP']]['Reference'] = $row['Reference'];
		}

		if($Statement)
		{
			ksort($Statement);
			if($_GET['pdf'] == true)
			{
				include(INCLUDE_PATH .'/includes/PDF_statement.php');
				$PDF_Statement = new PDF_Statement();
				$PDF_Statement->Open();
				//Add Statement details
				$PDF_Statement->Add_Details($Statement,$Start_Timestamp);

				//Look up address details
				$query = "SELECT Company_Name,Address1,Address2,City,Region,Postcode
				FROM ". PHPA_CLIENT_TABLE ." 
				WHERE ID = $Client_ID";
				$result = $db_reader->query($query);
				$row = $result->fetchRow(MDB2_FETCHMODE_ASSOC);
				$PDF_Statement->Add_Client_Details($row);
				$PDF_Statement->Print_Statement();
				$PDF_Statement->Output();
				exit();
			}
			else
			{
				foreach($Statement as $Timestamp => $array)
				{
					if($i) $i=0;
					else $i=1;
					$tmpl->AddVar('statement_results','I',$i);

					//get balance
					$BALANCE -= $array['Invoice'];
					$BALANCE += $array['Payment'];

					if($Timestamp >= $Start_Timestamp)
					{
						if($BALANCE < 0)
						{
							$CLASS = 'red';
						}
						else
						{
							$CLASS = '';
						}
						//check for first row
						if(!$first_check)
						{
							$tmpl->AddVar('statement_results','DATE',date('Y-m-d',$Timestamp));
							$tmpl->AddVar('statement_results','INVOICE_ID','OPENING BALANCE');
							$tmpl->AddVar('statement_results','REFERENCE','OPENING BALANCE');
							//take off or add on to compensate for first transaction
							$tmpl->AddVar('statement_results','BALANCE',$BALANCE + $array['Invoice'] - $array['Payment']);
							$tmpl->AddVar('statement_results','CLASS',$CLASS);
							$tmpl->ParseTemplate('statement_results','a');
							$first_check = true;
						}	

						$tmpl->AddVar('statement_results','DATE',date('Y-m-d',$Timestamp));
						$tmpl->AddVar('statement_results','INVOICE_ID',$array['Invoice_ID']);
						$tmpl->AddVar('statement_results','REFERENCE',$array['Reference']);
						$tmpl->AddVar('statement_results','INVOICE',$array['Invoice']);
						$tmpl->AddVar('statement_results','PAYMENT',$array['Payment']);
						$tmpl->AddVar('statement_results','BALANCE',$BALANCE);
						$tmpl->AddVar('statement_results','CLASS',$CLASS);
						$tmpl->ParseTemplate('statement_results','a');
					}	
				}
			}
		}
		$tmpl->AddVar('view_statement','CLIENT_ID',$Client_ID);
		$tmpl->AddVar('view_statement','CLIENT',$Client_array[$Client_ID]);
		$tmpl->AddVar('view_statement','CLASS',$CLASS);
		$tmpl->AddVar('view_statement','BALANCE',$BALANCE);
		$tmpl->AddVar('view_statement','LAST_START_TIMESTAMP',$Start_Timestamp - ONE_YEAR);
		$tmpl->AddVar('view_statement','START_TIMESTAMP',$Start_Timestamp);
		$tmpl->AddVar('view_statement','NEXT_START_TIMESTAMP',$End_Timestamp);
		$tmpl->AddVar('view_statement','YEAR',date('Y',$Start_Timestamp));
		$tmpl->AddThisTemplate('view_statement');
	}

	/*-----------------------------------------------------------------------------*\

	Project Section

	\*-----------------------------------------------------------------------------*/


	if($action == 'edit_project')
	{
		$Client_ID = getVariable('Client_ID');
		$Project_ID = getVariable('Project_ID');
		if($Project_ID)
		{
			$query = "SELECT * FROM ". PHPA_PROJECT_TABLE ." WHERE ID = $Project_ID";
			$result = $db_reader->query($query);
			$row = $result->FetchRow(MDB2_FETCHMODE_ASSOC);
			$Client_ID = $row['Client_ID'];
			foreach($row as $key => $value)
			{
				$tmpl->AddVar('edit_project',$key,$value);
			}
			settype($row['Date_Closed'],'integer');
			if($row['Date_Closed'] != 0)
			{
				$tmpl->AddVar('edit_project','CLOSED','checked="checked"');
			}
			$tmpl->AddVar('edit_project','DATE_CLOSED_SELECT',dateSelector('Date_Closed',$row['Date_Closed']));
			$tmpl->AddVar('edit_project','DATE_OPENED_SELECT',dateSelector('Date_Opened',$row['Date_Opened']));
			$tmpl->AddVar('edit_project','CLIENT_SELECT',selectlist2('Client_ID',$Client_array,$row['Client_ID']));
		}
		elseif(!$Client_ID)
		{
			$tmpl->addvar('choose_client','CLIENT_SELECT_LIST',selectlist2('Client_ID',$Client_array,''));
			$tmpl->addvar('choose_client','ACTION','edit_project');
			$tmpl->addthistemplate('choose_client');
			$tmpl->setattribute('edit_project','visibility','hidden');
		}
		elseif($Client_ID)
		{
			$tmpl->AddVar('edit_project','DATE_CLOSED_SELECT',dateSelector('Date_Closed',NOW));
			$tmpl->AddVar('edit_project','DATE_OPENED_SELECT',dateSelector('Date_Opened',NOW));
			$tmpl->AddVar('edit_project','CLIENT_ID',$Client_ID);
			$tmpl->AddVar('edit_project','TITLE','Project');
			$tmpl->AddVar('edit_project','CLIENT_SELECT',selectlist2('Client_ID',$Client_array,$Client_ID));
		}
		else
		{
			generic_error();
		}
		$tmpl->AddVar('edit_project','CLIENT',$Client_array[$Client_ID]);
		$tmpl->AddThisTemplate('edit_project');
	}


	/*-----------------------------------------------------------------------------*\

	Quote Section

	\*-----------------------------------------------------------------------------*/


	if($action == 'edit_quote')
	{
		$Client_ID = getVariable('Client_ID');
		$Quote_ID = getVariable('Quote_ID');
		if($Quote_ID)
		{
			$query = "SELECT * FROM ". PHPA_QUOTE_TABLE ." WHERE ID = $Quote_ID";
			$result = $db_reader->query($query);
			$row = $result->FetchRow(MDB2_FETCHMODE_ASSOC);
			$Client_ID = $row['Client_ID'];
			foreach($row as $key => $value)
			{
				$tmpl->AddVar('edit_quote',$key,$value);
			}
			$tmpl->AddVar('edit_quote','DATE_SELECT',dateSelector('Date',$row['Date']));
			$tmpl->AddVar('edit_quote','APPROVED_DATE_SELECT',dateSelector('Approved_Date',$row['Approved_Date']));
			$tmpl->AddVar('edit_quote','CLIENT_SELECT',selectlist2('Client_ID',$Client_array,$row['Client_ID']));
		}
		elseif(!$Client_ID)
		{
			$tmpl->addvar('choose_client','CLIENT_SELECT_LIST',selectlist2('Client_ID',$Client_array,''));
			$tmpl->addvar('choose_client','ACTION','edit_quote');
			$tmpl->addthistemplate('choose_client');
			$tmpl->setattribute('edit_quote','visibility','hidden');
		}
		elseif($Client_ID)
		{
			$tmpl->AddVar('edit_quote','CLIENT_ID',$Client_ID);
			$tmpl->AddVar('edit_quote','TITLE','Quote');
			$tmpl->AddVar('edit_quote','CLIENT_SELECT',selectlist2('Client_ID',$Client_array,$Client_ID));
			$tmpl->AddVar('edit_quote','DATE_SELECT',dateSelector('Date',mysqlDate(NOW)));
			$tmpl->AddVar('edit_quote','APPROVED_DATE_SELECT',dateSelector('Approved_Date',mysqlDate(NOW)));
			//get Address
			$query = "SELECT Address1,Address2,City,Region,Postcode FROM ". PHPA_CLIENT_TABLE ." WHERE ID = $Client_ID";
			$result = $db_reader->query($query);
			$row = $result->fetchRow();
			$address = implode("\n",$row);
			$tmpl->AddVar('edit_quote','QUOTE_ADDRESS',$address);
		}
		else
		{
			generic_error();
		}
		$tmpl->AddVar('edit_quote','CLIENT',$Client_array[$Client_ID]);
		$tmpl->AddThisTemplate('edit_quote');
	}


	if($action == 'print_quote' && $_GET['Quote_ID'])
	{
		include(INCLUDE_PATH .'/includes/PDF_quote.php');
		$Quote = new PDF_Quote();
		$Quote->Open();

		//get details
		$Quote_ID = $_GET['Quote_ID'];
		$query = "SELECT Q.ID,Q.Title,C.Company_Name,Q.Value,DATE_FORMAT(Q.Date,'". MYSQL_DATE_FORMAT ."') AS Date,Q.Description,Q.Quote_Address
		FROM ". PHPA_QUOTE_TABLE ." Q LEFT JOIN ". PHPA_CLIENT_TABLE ." C ON(Q.Client_ID = C.ID)
		WHERE Q.ID = $Quote_ID";
		$result = $db_reader->query($query);
		$row = $result->fetchRow(MDB2_FETCHMODE_ASSOC);
		$Quote->Add_Details($row);
		$Quote->Print_Quote();
		$Quote->Output();
		exit();
	}


	/*-----------------------------------------------------------------------------*\

	Timesheet Section

	\*-----------------------------------------------------------------------------*/


	if($action == 'timesheet_summary')
	{	

		$Client_ID = getVariable('Client_ID');

		$Year = getVariable('Timesheet_year');

		$Start_Timestamp = getVariable('Start_Timestamp');
		$End_Timestamp = getVariable('End_Timestamp');

		//set date to this year if not set
		if(!$Year)
		{
			$Year = date('Y');
		}

		if(!$Start_Timestamp)
		{
			$Start_Timestamp = mktime(0,0,0,1,1,$Year);
		}
		if(!$End_Timestamp)
		{
			$End_Timestamp = mktime(0,0,0,1,1,$Year + 1); //very start of next year
		}

		$tmpl->AddContent(timesheetGraph('timesheet',$Start_Timestamp,$End_Timestamp,'year',$Client_ID,'page=clients&action=timesheet_summary','page=clients'));

	}

	if($action == 'view_timesheet')
	{
		$Client_ID = getVariable('Client_ID');

		$Start_Timestamp = getVariable('Start_Timestamp');
		$End_Timestamp = getVariable('End_Timestamp');
		$Period = getVariable('Period');

		if(!$Period)
		{
			$Period = 'month';
		}

		//set date to this month if not set
		if(!$Start_Timestamp)
		{
			$Month = date('m');
			$Year = date('Y');
			$Start_Timestamp = mktime(0,0,0,$Month,1,$Year);
		}

		if(!$Month || !$Year)
		{
			$Month = date('m',$Start_Timestamp);
			$Year = date('Y',$Start_Timestamp);
		}

		if(!$End_Timestamp)
		{
			if($Period == 'year')
			{
				$End_Timestamp = $Start_Timestamp + ONE_YEAR; 
			}
			elseif($Period == 'month')
			{
				$Last_Day = date('t',$Start_Timestamp);
				$End_Timestamp = mktime(0,0,0,$Month,$Last_Day,$Year)+ONE_DAY; //very start of next month day
			}
			elseif($Period == 'week')
			{
				$End_Timestamp = $Start_Timestamp + ONE_WEEK; 
			}
			elseif($Period == 'day')
			{
				$End_Timestamp = $Start_Timestamp + ONE_DAY; 
			}
		}

		$tmpl->AddContent(viewTimesheet($Start_Timestamp,$End_Timestamp,$Period,$Client_ID,$_GET['csv'],$_GET['Project_ID']));

	}

	if($action == 'edit_timesheet')
	{
		$tmpl->ReadTemplatesFromFile('timesheet.tmpl.html');
		$Client_ID = getVariable('Client_ID'); 
		if(!$Client_ID)
		{
			$tmpl->AddVar('choose_client','CLIENT_SELECT_LIST',selectlist2('Client_ID',$Client_array,''));
			$tmpl->AddVar('choose_client','ACTION','edit_timesheet');
			$tmpl->AddThisTemplate('choose_client');
		}
		else
		{
			$tmpl->AddVar('edit_timesheet','CLIENT_ID',$Client_ID);
			$tmpl->AddVar('edit_timesheet','COMPANY_NAME',$Client_array[$Client_ID]);
			//edit else it's a new submission
			$Timesheet_ID = $_GET['Timesheet_ID'];
			if($Timesheet_ID)
			{
				$query = "SELECT TS.ID,TS.Client_ID,C.Company_Name,DATE_FORMAT(TS.Timestamp,'%a, %d %b %Y %h:%m:%s') AS OPENED,DATE_FORMAT(NOW(),'%a, %b %M %Y %h:%m:%s') AS TIME_NOW,IF(TS.Time='00:00:00',SEC_TO_TIME(UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(TS.Timestamp)),TS.Time) AS Time,IF(TS.Time='00:00:00',UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(TS.Timestamp),TIME_TO_SEC(TS.Time)) AS `Seconds`,TS.Value,TS.Description, Project_ID 
				FROM ". PHPA_TIMESHEET_TABLE ." TS LEFT JOIN ". PHPA_CLIENT_TABLE ." C ON(TS.Client_ID = C.ID) LEFT JOIN ". PHPA_PROJECT_TIMESHEET_TABLE ." PT ON(PT.Timesheet_ID = TS.ID)
				WHERE TS.ID = $Timesheet_ID";
				$result = $db_reader->query($query);
				$row = $result->fetchRow(MDB2_FETCHMODE_ASSOC);
				if(!$row['Value'])
				{
					$row['Value'] = round((($row['Seconds']/ONE_HOUR) * HOURLY_RATE),2);
				}
				foreach($row as $key => $value)
				{
					$tmpl->AddVar('edit_timesheet',$key,$value);
				}

			}
			else
			{
				$tmpl->AddVar('edit_timesheet','OPENED',date('D, d M Y H:i:s'));
				$tmpl->AddVar('edit_timesheet','TIME_NOW',date('D, d M Y H:i:s'));

			}
			//Time Selector
			$tmpl->AddVar('edit_timesheet','TIME_SELECT_LIST',timeSelector('Time',$row['Time']));
			$query = "SELECT ID,Title FROM ". PHPA_PROJECT_TABLE ." WHERE Client_ID = $Client_ID";
			$result = $db_reader->query($query);
			for($i=0;$i<$result->NumRows();$i++)
			{
				list($ID,$Title) = $result->FetchRow();
				$Project_array[$ID] = $Title;
			}

			//keep menu at top of this page
			$tmpl->AddVar('edit_timesheet','PROJECT_SELECT_LIST',selectlist('-- choose Project','Project_ID',$Project_array,$row['Project_ID']));

			$tmpl->AddThisTemplate('edit_timesheet');
		}
	}
	
	//special display with no menu etc.
	if($action == 'show_mail')
	{
		if($Mail_ID = getVariable('Mail_ID'))
		{
			//remove menu etc.
			$tmpl->ClearContent();
			$query = "SELECT C.Company_Name, CSM.Email, Subject, DATE_FORMAT( CSM.Timestamp, '%Y-%m-%d' ) AS Date, Message
			FROM `". PHPA_CLIENT_SENT_MAIL_TABLE ."` CSM
			LEFT JOIN ". PHPA_CLIENT_TABLE ." C ON ( CSM.Client_ID = C.ID )
			WHERE CSM.ID = $Mail_ID";
			$result = $db_reader->query($query);
			$row = $result->FetchRow(MDB2_FETCHMODE_ASSOC);
			foreach($row as $key => $value)
			{
				$tmpl->AddVar('show_mail',$key,$value);
			}
		 	$tmpl->AddThisTemplate('show_mail');
			$tmpl->SetDisplayType('content_only');
		}
	}

	$query = "SELECT COUNT(*) FROM ". PHPA_CLIENT_TABLE ." WHERE User_ID = $User_ID";
	$CLIENTS = $db_reader->queryOne($query);
	$tmpl->AddVar('menu','CLIENTS',$CLIENTS);

	//keep menu at top of this page
	$tmpl->AddVar('menu','CLIENT_SELECT_LIST',selectlist2('Client_ID',$Client_array,$row['Client_ID']));
	$tmpl->AddThisBeforeTemplate('menu');

?>
