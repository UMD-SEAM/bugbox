<?php

	if(!function_exists('scandir'))
	{
		function scandir($dir = './', $sort = 0)
		{
			$dir_open = @ opendir($dir);

			if (! $dir_open)
			return false;


			while (($dir_content = readdir($dir_open)) !== false)
			$files[] = $dir_content;

			if ($sort == 1)
			rsort($files, SORT_STRING);
			else
			sort($files, SORT_STRING);

			return $files;
		}
	}

	function checkValidEmail($email)
	{
		$result = strstr($email,"@");
		return $result;
	}

	function checkEmail($email)
	{
		global $db_reader;
		//check email is valid
		if(!checkValidEmail($email)) return false;

		//check to see if member exists
		$query = "SELECT ID FROM ". PHPA_USER_TABLE ." WHERE Email = '$email'";
		if($db_reader->queryOne($query))
		{
			return false;
		}
		else
		{	
			return true;
		}
	}

	function createUser($data)
	{
		global $db_writer;
		global $default_Outgoing_Types;

		$User_ID = getNextID(PHPA_USER_TABLE,'ID');
		if(is_array($data))
		{
			$query = buildINSERT($data,PHPA_USER_TABLE);
			$db_writer->exec($query);
		}

		//create default outgoing types
		foreach($default_Outgoing_Types as $Type)
		{
			$query = "INSERT INTO ". PHPA_OUTGOING_TYPE_TABLE ." (User_ID,Outgoing_Type) VALUES($User_ID,'". addslashes($Type) ."')";
			$db_writer->exec($query);
		}
		return $User_ID;
	}

	function sendLoginDetails($User_ID)
	{
		global $db_reader;
		global $tmpl;
		$query = "SELECT Email,Password FROM ". PHPA_USER_TABLE ." WHERE ID = $User_ID";
		list($Email,$Password) = $db_reader->queryRow($query);
		$tmpl->ReadTemplatesFromFile('emails.tmpl.txt');
		$tmpl->AddVar('login_details','EMAIL',$Email);
		$tmpl->AddVar('login_details','Password',$Password);
		if(!mail($Email,'Your Login Details',$tmpl->GetParsedTemplate('login_details'),SYSTEM_EMAIL_HEADERS))
		{ 
			trigger_error('Failed sending login details mail '. $tmpl->GetParsedTemplate('login_details') ." to $Email");
		}
	}

	function getVariable($Variable_Name)
	{
		if($Variable = $_GET[$Variable_Name])
		{
			return $Variable;
		}
		elseif($Variable = $_POST[$Variable_Name])
		{
			return $Variable;
		}
		else 
		{
			return false;
		}
	}

	function displayCSV($Content)
	{
		global $action;
		if(!$action)
		{
			$action = 'report';
		}
		header("Content-type: application/octet-stream");
		header("Content-disposition: attachment; filename=$action.csv");
		header("Pragma: no-cache");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Expires: 0"); 

		echo $Content;
		exit();
	}

	function formatTime($time,$format='text')
	{
		// calculate elapsed time (in seconds!)
		$days = 0; $hours = 0; $mins = 0; $secs = 0;

		$sec_in_a_week = 7*60*60*24;
		while($time >= $sec_in_a_week){$weeks++; $time -= $sec_in_a_week;}
		$sec_in_a_day = 60*60*24;
		while($time >= $sec_in_a_day){$days++; $time -= $sec_in_a_day;}
		$sec_in_an_hour = 60*60;
		while($time >= $sec_in_an_hour){$hours++; $time -= $sec_in_an_hour;}
		$sec_in_a_min = 60;
		while($time >= $sec_in_a_min){$mins++; $time -= $sec_in_a_min;}
		$secs = $time;

		if($format == 'text')
		{
			unset($time);
			if($weeks > 0) $time = $weeks.'w ';
			if($days > 0 || $time) $time .= $days.'d ';
			if($hours > 0 || $time) $time .= $hours.'h ';
			if($mins > 0 || $time) $time .= $mins.'m ';
			if($secs > 0 || $time) $time .= $secs.'s ';
			return ($time);
		}
		elseif($format == 'array')
		{
			$time['weeks'] = $weeks;
			$time['days'] = $days;
			$time['hours'] = $hours;
			$time['mins'] = $mins;
			$time['secs'] = $secs;
		}
	}

	function mysqlTimestamp($UNIX_TIMESTAMP)
	{
		return date('YmdHis',$UNIX_TIMESTAMP);
	}

	function mysqlDatetime($UNIX_TIMESTAMP)
	{
		return date('Y-m-d H:i:s',$UNIX_TIMESTAMP);
	}

	function mysqlDate($UNIX_TIMESTAMP)
	{
		return date('Y-m-d',$UNIX_TIMESTAMP);
	}



	function timesheetGraph($Type,$Start_Timestamp,$End_Timestamp,$Period,$Client_ID=false,$action='default',$summary='page=reports')
	{
		if($action == 'default')
		{
			if($Type == 'timesheet')
			{
				$action = 'page=clients&action=timesheet_summary&Type=timesheet';
			}
			elseif($Type == 'invoice')
			{
				$action = 'page=clients&action=invoice_summary&Type=invoice';
			}
			elseif($Type == 'repeat_invoice')
			{
				$action = 'page=clients&action=repeat_invoice_summary&Type=repeat_invoice';
			}
			elseif($Type == 'forecast_repeat_invoice')
			{
				$action = 'page=clients&action=forecast_repeat_invoice_summary&Type=forecast_repeat_invoice';
			}
			elseif($Type == 'payment')
			{
				$action = 'page=clients&action=payments_summary&Type=payment';
			}
		}

		global $tmpl;
		global $db_reader;
		global $Client_array;
		global $User_ID;
		$tmpl->ReadTemplatesFromFile('timesheet.tmpl.html');
		//MYSQL FORMATED
		$MySQL_Start_Timestamp = mysqlDatetime($Start_Timestamp);
		$MySQL_End_Timestamp = mysqlDatetime($End_Timestamp);

		$MySQL_Start_Date = mysqlDate($Start_Timestamp);
		$MySQL_End_Date = mysqlDate($End_Timestamp);

		$Year = date('Y',$Start_Timestamp);
		$Month = date('m',$Start_Timestamp);
		if($Client_ID)
		{
			$WHERE .= " AND Client_ID = $Client_ID ";
		}
		if($Type == 'timesheet')
		{
			$WHERE .= "AND Timestamp >= '$MySQL_Start_Timestamp' AND Timestamp <= '$MySQL_End_Timestamp'";
			$graph_action = 'page=clients&action=view_timesheet';			
		}
		if($Type == 'payment')
		{
			$WHERE .= "AND Timestamp >= '$MySQL_Start_Timestamp' AND Timestamp <= '$MySQL_End_Timestamp'";
			$graph_action = 'page=clients&action=view_payments';			
		}
		elseif($Type == 'invoice')
		{
			$WHERE .= "AND Date >= '$MySQL_Start_Date' AND Date <= '$MySQL_End_Date'";
			$graph_action = 'page=clients&action=view_invoices';
		}
		elseif($Type == 'repeat_invoice')
		{
			$WHERE .= "AND Date >= '$MySQL_Start_Date' AND Date <= '$MySQL_End_Date'";
			$graph_action = 'page=clients&action=view_repeat_invoices';
		}

		elseif($Type == 'forecast_repeat_invoice')
		{
			$graph_action = 'page=clients&action=view_forecast_repeat_invoices';
			//only year listings
			$Period == 'year';
		}


		//set the timestamps for links
		$Next_Period_Start = $End_Timestamp ;
		$Last_Period_End = $Start_Timestamp ;

		//set timestamps and queries  specific to the period
		if($Period == 'year')
		{
			$Next_Period_End = $Next_Period_Start + ONE_YEAR;
			$Last_Period_Start = $Last_Period_End - ONE_YEAR;
			$Period_Start = 1;
			$Period_End = 12;
			$Period_Length = 12;
			if($Type == 'timesheet')
			{
				$query = "SELECT MONTH(Timestamp) AS PERIOD, SUM(Value) AS Value,SUM(TIME_TO_SEC(Time)) AS Time
				FROM ". PHPA_TIMESHEET_TABLE ." T LEFT JOIN ". PHPA_CLIENT_TABLE ." C ON(T.Client_ID = C.ID) 
				WHERE C.User_ID = $User_ID $WHERE
				GROUP BY MONTH(Timestamp) ";
			}
			elseif($Type == 'payment')
			{
				$query = "SELECT MONTH(IP.Timestamp) AS PERIOD, SUM(IP.Value) AS Value
				FROM ". PHPA_INVOICE_PAYMENT_TABLE ." IP, ". PHPA_INVOICE_TABLE ." I LEFT JOIN ". PHPA_CLIENT_TABLE ." C ON(I.Client_ID = C.ID) 
				WHERE C.User_ID = $User_ID $WHERE AND IP.Invoice_ID = I.ID
				GROUP BY MONTH(IP.Timestamp) ";
			}
			elseif($Type == 'invoice')
			{
				$query = "SELECT MONTH(Date) AS PERIOD, SUM(Value) AS Value,(COUNT(*)*". ONE_HOUR .") AS Time
				FROM ". PHPA_INVOICE_TABLE ." I LEFT JOIN ". PHPA_CLIENT_TABLE ." C ON(I.Client_ID = C.ID) 
				WHERE C.User_ID = $User_ID $WHERE
				GROUP BY MONTH(Date) ";
			}
			elseif($Type == 'repeat_invoice')
			{
				$query = "SELECT MONTH(I.Date) AS PERIOD, SUM(I.Value) AS Value,(COUNT(*)*". ONE_HOUR .") AS Time
				FROM `". PHPA_REPEAT_INVOICE_TABLE ."` RI LEFT JOIN ". PHPA_INVOICE_TABLE ." I ON(RI.Invoice_ID = I.ID) LEFT JOIN ". PHPA_CLIENT_TABLE ." C ON(I.Client_ID = C.ID) 
				WHERE C.User_ID = $User_ID $WHERE
				GROUP BY MONTH(Date) ";
			}
			$View_Period = 'month';
		}
		elseif($Period == 'week')
		{		
			$Next_Period_End = $Next_Period_Start + ONE_WEEK;
			$Last_Period_Start = $Last_Period_End - ONE_WEEK;
			$Period_Start = date('d',$Start_Timestamp);
			$Period_End = date('d',$End_Timestamp);
			//last day of month
			$Peiod_Cutoff = date('t',$Start_Timestamp);
			$Period_Length = 7;
			if($Type == 'timesheet')
			{
				$query = "SELECT DATE_FORMAT(Timestamp,'%e') AS PERIOD, SUM(Value) AS Value,SUM(TIME_TO_SEC(Time)) AS Time
				FROM ". PHPA_TIMESHEET_TABLE ."  T LEFT JOIN ". PHPA_CLIENT_TABLE ." C ON(T.Client_ID = C.ID) 
				WHERE C.User_ID = $User_ID $WHERE
				GROUP BY DATE_FORMAT(Timestamp,'%e') ";
			}
			elseif($Type == 'payment')
			{
				$query = "SELECT DATE_FORMAT(IP.Timestamp,'%e') AS PERIOD, SUM(IP.Value) AS Value
				FROM ". PHPA_INVOICE_PAYMENT_TABLE ." IP, ". PHPA_INVOICE_TABLE ." I LEFT JOIN ". PHPA_CLIENT_TABLE ." C ON(I.Client_ID = C.ID) 
				WHERE C.User_ID = $User_ID $WHERE AND IP.Invoice_ID = I.ID
				GROUP BY DATE_FORMAT(Timestamp,'%e') ";
			}

			elseif($Type == 'invoice')
			{
				$query = "SELECT DATE_FORMAT(Date,'%e') AS PERIOD, SUM(Value) AS Value,(COUNT(*)*". ONE_HOUR .") AS Time
				FROM ". PHPA_INVOICE_TABLE ." I LEFT JOIN ". PHPA_CLIENT_TABLE ." C ON(I.Client_ID = C.ID) 
				WHERE C.User_ID = $User_ID $WHERE
				GROUP BY DATE_FORMAT(Date,'%e') ";
			}


			elseif($Type == 'repeat_invoice')
			{
				$query = "SELECT DATE_FORMAT(I.Date,'%e') AS PERIOD, SUM(I.Value) AS Value,(COUNT(*)*". ONE_HOUR .") AS Time
				FROM `". PHPA_REPEAT_INVOICE_TABLE ."` RI LEFT JOIN ". PHPA_INVOICE_TABLE ." I ON(RI.Invoice_ID = I.ID) LEFT JOIN ". PHPA_CLIENT_TABLE ." C ON(I.Client_ID = C.ID) 
				WHERE C.User_ID = $User_ID $WHERE
				GROUP BY DATE_FORMAT(Date,'%e') ";
			}

			$View_Period = 'day';
		}

		//special case for forecast repeat invoices
		if($Type == 'forecast_repeat_invoice')
		{
			$query = "SELECT I.Value,Day,Month
			FROM `". PHPA_REPEAT_INVOICE_TABLE ."` RI LEFT JOIN ". PHPA_INVOICE_TABLE ." I ON(RI.Invoice_ID = I.ID) LEFT JOIN ". PHPA_CLIENT_TABLE ." C ON(I.Client_ID = C.ID) 
			WHERE C.User_ID = $User_ID AND I.Date < '$MySQL_End_Date' AND RI.Active = 'yes' ";
			$result = $db_reader->query($query);
			while($row = $result->fetchRow(MDB2_FETCHMODE_ASSOC))
			{

				//every month
				if($row['Month'] == '*')
				{
					for($i=1;$i<=12;$i++)
					{
						//and every day	
						if($row['Day'] == '*')
						{
							$days = date('t',mktime(1,1,1,$i,1,2000));
							for($j=1;$j<=$days;$j++)
							{
								$Period_Data[$i]['Value'] += $row['Value'];
								$Period_Data[$i]['Time']++;
							}
						}
						//once a month
						else
						{
							$Period_Data[$i]['Value'] += $row['Value'];
							$Period_Data[$i]['Time']++;
						}

						//max and totals
						$max_value = max($max_value, $Period_Data[$i]['Value']);
						$max_time = max($max_time,  $Period_Data[$i]['Time']);
						$total_time += $Period_Data[$i]['Time'];
						$total_value += $Period_Data[$i]['Value'];
					}

				}
				//once a year
				else
				{
					$Period_Data[$row['Month']]['Value'] += $row['Value'];
					$Period_Data[$row['Month']]['Time']++;
					//max and totals
					$max_value = max($max_value, $Period_Data[$row['Month']]['Value']);
					$max_time = max($max_time, $Period_Data[$row['Month']]['Time']);
					$total_time++;
					$total_value += $row['Value'];
				}
			}
		}

		else
		{
			$result = $db_reader->query($query);
			while($row = $result->fetchRow(MDB2_FETCHMODE_ASSOC))
			{
				$Period_Data[$row['PERIOD']]['Value'] = $row['Value'];
				$Period_Data[$row['PERIOD']]['Time'] = $row['Time'];
				$max_value = max($max_value, $row['Value']);
				$max_time = max($max_time, $row['Time']);
				$total_time += $row['Time'];
				$total_value += $row['Value'];
			}
		}


		//loop through months and output results

		//catch loop over end of month


		for($j=0;$j<$Period_Length;$j++)
		{
			$i = $Period_Start + $j;
			//catch end of month and reset
			if($Period_Cutoff && $i > $Period_Cutoff)
			{
				$i = 1 + $j;
			}
			if($I == 'a') $I = 'b';
			else $I = 'a';
			//heading
			if($Period == 'year')
			{
				$this_Period = date('M',mktime(0,0,0,$i,1,$Year));
				$this_Start_Timestamp = mktime(0,0,0,$i,1,$Year);
				$this_Numeric_Month = date('m',mktime(0,0,0,$i,1,$Year));
			}
			elseif($Period == 'week')
			{
				$this_Period = date('D',mktime(0,0,0,$Month,$i,$Year));
				$this_Start_Timestamp = mktime(0,0,0,$Month,$i,$Year);
				$this_Numeric_Month = date('m',mktime(0,0,0,$Month,$i,$Year));
			}


			$tmpl->AddVar('months','PERIOD',$this_Period);
			$tmpl->AddVar('months','VIEW_PERIOD',$View_Period);
			$tmpl->AddVar('months','START_TIMESTAMP',$this_Start_Timestamp);
			$tmpl->AddVar('months','CLIENT_ID',$Client_ID);
			$tmpl->AddVar('months','I',$I);
			$tmpl->ParseTemplate('months','a');

			//link action
			$tmpl->AddVar('month_timesheet_graphs','GRAPH_ACTION',$graph_action);		

			//graphs
			if($max_time !=0)
			{
				$this_time = round(($Period_Data[$i]['Time']/$max_time)*SMALL_GRAPH_HEIGHT);
			}
			else
			{
				$this_time = 0;
			}
			if($max_value !=0)
			{
				$this_value = round(($Period_Data[$i]['Value']/$max_value)*SMALL_GRAPH_HEIGHT);
			}
			else
			{
				$this_value = 0;
			}
			$tmpl->AddVar('month_timesheet_graphs','TIME_GRAPH',$this_time);
			$tmpl->AddVar('month_timesheet_graphs','VALUE_GRAPH',$this_value);
			$tmpl->AddVar('month_timesheet_graphs','VALUE',intval($Period_Data[$i]['Value']));
			$tmpl->AddVar('month_timesheet_graphs','Time',formatTime($Period_Data[$i]['Time']));
			$tmpl->AddVar('month_timesheet_graphs','VIEW_PERIOD',$View_Period);
			$tmpl->AddVar('month_timesheet_graphs','START_TIMESTAMP',$this_Start_Timestamp);
			$tmpl->AddVar('month_timesheet_graphs','CLIENT_ID',$Client_ID);
			$tmpl->AddVar('month_timesheet_graphs','I',$I);
			$tmpl->ParseTemplate('month_timesheet_graphs','a');

			//figures
			$tmpl->AddVar('month_timesheet_figures','VALUE',intval($Period_Data[$i]['Value']));
			$tmpl->AddVar('month_timesheet_figures','Time',round($Period_Data[$i]['Time']/ONE_HOUR));
			$tmpl->AddVar('month_timesheet_figures','I',$I);
			$tmpl->ParseTemplate('month_timesheet_figures','a');
		}

		//years for selector
		for($i=2002;$i<=2010;$i++)
		{
			$years[$i] = $i;
		}

		//colspan calculation 
		$Colspan = ($Period_Length);
		$tmpl->AddVar('timesheet_summary','HEADING_COLSPAN',$Colspan);
		$tmpl->AddVar('timesheet_summary','SPACER_COLSPAN',$Colspan - 4);
		$tmpl->AddVar('timesheet_summary','CLIENT_ID',$Client_ID);
		$tmpl->AddVar('timesheet_summary','NEXT_PERIOD_START',$Next_Period_Start);
		$tmpl->AddVar('timesheet_summary','NEXT_PERIOD_END',$Next_Period_End);
		$tmpl->AddVar('timesheet_summary','LAST_PERIOD_START',$Last_Period_Start);
		$tmpl->AddVar('timesheet_summary','LAST_PERIOD_END',$Last_Period_End);
		$tmpl->AddVar('timesheet_summary','TIMESHEET_PERIOD', date('d/m/Y',$Start_Timestamp) .' - '. date('d/m/Y',$End_Timestamp));
		$tmpl->AddVar('timesheet_summary','PERIOD',$Period);
		$tmpl->AddVar('timesheet_summary','ACTION',$action);
		$tmpl->AddVar('timesheet_summary','TYPE',ucfirst($Type).'s');
		$tmpl->AddVar('timesheet_summary','SUMMARY',$summary);
		$tmpl->AddVar('timesheet_summary','TOTAL_VALUE',$total_value);
		$tmpl->AddVar('timesheet_summary','TOTAL_TIME',round($total_time/ONE_HOUR));
		$tmpl->AddVar('timesheet_summary','CLIENT',$Client_array[$Client_ID]);
		return $tmpl->GetParsedTemplate('timesheet_summary');
	}


	function cashflowGraph($Type,$Start_Timestamp,$End_Timestamp,$Period,$Client_ID=false,$action='default',$summary='page=reports')
	{
		if($action == 'default')
		{
			$action = 'page=reports&Type=cashflow';
		}
		$graph_action = $action;
		global $tmpl;
		global $db_reader;
		global $Client_array;
		global $User_ID;
		$tmpl->ReadTemplatesFromFile('cashflow.tmpl.html');
		//MYSQL FORMATED
		$MySQL_Start_Timestamp = mysqlDatetime($Start_Timestamp);
		$MySQL_End_Timestamp = mysqlDatetime($End_Timestamp);

		$MySQL_Start_Date = mysqlDate($Start_Timestamp);
		$MySQL_End_Date = mysqlDate($End_Timestamp);

		$Year = date('Y',$Start_Timestamp);
		$Month = date('m',$Start_Timestamp);
		if($Client_ID)
		{
			$WHERE .= " AND Client_ID = $Client_ID ";
		}

		$PAYMENT_WHERE = $WHERE .  " AND Timestamp >= '$MySQL_Start_Timestamp' AND Timestamp <= '$MySQL_End_Timestamp'";
		$OUTGOING_WHERE = $WHERE .  " AND Date >= '$MySQL_Start_Timestamp' AND Date <= '$MySQL_End_Timestamp'";

		//set the timestamps for links
		$Next_Period_Start = $End_Timestamp ;
		$Last_Period_End = $Start_Timestamp ;

		//Payments
		//set timestamps and queries  specific to the period
		if($Period == 'year')
		{
			$Next_Period_End = $Next_Period_Start + ONE_YEAR;
			$Last_Period_Start = $Last_Period_End - ONE_YEAR;
			$Period_Start = 1;
			$Period_End = 12;
			$Period_Length = 12;
			$payment_query = "SELECT MONTH(IP.Timestamp) AS PERIOD, SUM(IP.Value) AS Value
			FROM ". PHPA_INVOICE_PAYMENT_TABLE ." IP, ". PHPA_INVOICE_TABLE ." I LEFT JOIN ". PHPA_CLIENT_TABLE ." C ON(I.Client_ID = C.ID) 
			WHERE C.User_ID = $User_ID $PAYMENT_WHERE AND IP.Invoice_ID = I.ID
			GROUP BY MONTH(IP.Timestamp) ";
			$outgoing_query = "SELECT MONTH(Date) AS PERIOD, SUM(Value) AS Value
			FROM `". PHPA_OUTGOING_TABLE ."`  O LEFT JOIN ". PHPA_VENDOR_TABLE ." V ON(O.Vendor_ID = V.ID) 
			WHERE V.User_ID = $User_ID $OUTGOING_WHERE
			GROUP BY MONTH(Date) ";
			$View_Period = 'month';
		}
		elseif($Period == 'week')
		{		
			$Next_Period_End = $Next_Period_Start + ONE_WEEK;
			$Last_Period_Start = $Last_Period_End - ONE_WEEK;
			$Period_Start = date('d',$Start_Timestamp);
			$Period_End = date('d',$End_Timestamp);
			//last day of month
			$Peiod_Cutoff = date('t',$Start_Timestamp);
			$Period_Length = 7;
			$payment_query = "SELECT DATE_FORMAT(IP.Timestamp,'%e') AS PERIOD, SUM(IP.Value) AS Value
			FROM ". PHPA_INVOICE_PAYMENT_TABLE ." IP, ". PHPA_INVOICE_TABLE ." I LEFT JOIN ". PHPA_CLIENT_TABLE ." C ON(I.Client_ID = C.ID) 
			WHERE C.User_ID = $User_ID $PAYMENT_WHERE AND IP.Invoice_ID = I.ID
			GROUP BY DATE_FORMAT(Timestamp,'%e') ";
			$outgoing_query = "SELECT DATE_FORMAT(Date,'%e') AS PERIOD, SUM(Value) AS Value
			FROM `". PHPA_OUTGOING_TABLE ."` O LEFT JOIN ". PHPA_VENDOR_TABLE ." V ON(O.Vendor_ID = V.ID) 
			WHERE V.User_ID = $User_ID $OUTGOING_WHERE
			GROUP BY DATE_FORMAT(Date,'%e') ";
			$View_Period = 'day';
		}

		$result = $db_reader->query($payment_query);
		while($row = $result->fetchRow(MDB2_FETCHMODE_ASSOC))
		{
			$Period_Data[$row['PERIOD']]['Payment']['Value'] = $row['Value'];
			$total_payment_value += $row['Value'];
		}

		//Outgoing
		$result = $db_reader->query($outgoing_query);
		while($row = $result->fetchRow(MDB2_FETCHMODE_ASSOC))
		{
			$Period_Data[$row['PERIOD']]['Outgoing']['Value'] = $row['Value'];
			$total_outgoing_value += $row['Value'];
		}

		//calculate max
		if($Period_Data)
		{
			foreach($Period_Data as $array)
			{
				$max_value = max($max_value,abs($array['Payment']['Value'] - $array['Outgoing']['Value']));
			}	
		}
		//loop through months and output results
		//catch loop over end of month


		for($j=0;$j<$Period_Length;$j++)
		{
			$i = $Period_Start + $j;
			//catch end of month and reset
			if($Period_Cutoff && $i > $Period_Cutoff)
			{
				$i = 1 + $j;
			}
			if($I == 'a') $I = 'b';
			else $I = 'a';
			//heading
			if($Period == 'year')
			{
				$this_Period = date('M',mktime(0,0,0,$i,1,$Year));
				$this_Start_Timestamp = mktime(0,0,0,$i,1,$Year);
				$this_Numeric_Month = date('m',mktime(0,0,0,$i,1,$Year));
			}
			elseif($Period == 'week')
			{
				$this_Period = date('D',mktime(0,0,0,$Month,$i,$Year));
				$this_Start_Timestamp = mktime(0,0,0,$Month,$i,$Year);
				$this_Numeric_Month = date('m',mktime(0,0,0,$Month,$i,$Year));
			}


			$tmpl->AddVar('months','PERIOD',$this_Period);
			$tmpl->AddVar('months','VIEW_PERIOD',$View_Period);
			$tmpl->AddVar('months','START_TIMESTAMP',$this_Start_Timestamp);
			$tmpl->AddVar('months','CLIENT_ID',$Client_ID);
			$tmpl->AddVar('months','I',$I);
			$tmpl->ParseTemplate('months','a');

			//link action
			$tmpl->AddVar('month_timesheet_graphs','GRAPH_ACTION',$graph_action);		

			$profit = intval($Period_Data[$i]['Payment']['Value']) - intval($Period_Data[$i]['Outgoing']['Value']);
			//graphs
			if($max_value <>0)
			{
				$this_value = round(($profit/$max_value)*SMALL_GRAPH_HEIGHT);
			}
			else
			{
				$this_value = 0;
			}
			if($this_value > 0)
			{
				$profit_graph = $this_value;
				$loss_graph = 0;
			}
			elseif($this_value < 0)
			{
				$profit_graph = 0; 
				$loss_graph = $this_value;
			}
			else
			{
				$profit_graph = 0;
				$loss_graph = 0;
			}
			$tmpl->AddVar('month_timesheet_graphs','VALUE_GRAPH',$profit_graph);
			$tmpl->AddVar('month_timesheet_graphs','PROFIT_GRAPH',$profit_graph);
			$tmpl->AddVar('month_timesheet_graphs','LOSS_GRAPH',$loss_graph);
			$tmpl->AddVar('month_timesheet_graphs','VALUE',$profit);
			$tmpl->AddVar('month_timesheet_graphs','VIEW_PERIOD',$View_Period);
			$tmpl->AddVar('month_timesheet_graphs','START_TIMESTAMP',$this_Start_Timestamp);
			$tmpl->AddVar('month_timesheet_graphs','CLIENT_ID',$Client_ID);
			$tmpl->AddVar('month_timesheet_graphs','I',$I);
			$tmpl->ParseTemplate('month_timesheet_graphs','a');

			//figures
			$tmpl->AddVar('month_payment_figures','VALUE',intval($Period_Data[$i]['Payment']['Value']));
			$tmpl->AddVar('month_outgoing_figures','VALUE',intval($Period_Data[$i]['Outgoing']['Value']));
			$tmpl->AddVar('month_profit_figures','VALUE',$profit);
			$tmpl->AddVar('month_payment_figures','I',$I);
			$tmpl->AddVar('month_outgoing_figures','I',$I);
			$tmpl->AddVar('month_profit_figures','I',$I);
			$tmpl->ParseTemplate('month_payment_figures','a');
			$tmpl->ParseTemplate('month_outgoing_figures','a');
			$tmpl->ParseTemplate('month_profit_figures','a');
		}

		//years for selector
		for($i=2002;$i<=2010;$i++)
		{
			$years[$i] = $i;
		}

		//colspan calculation 
		$Colspan = ($Period_Length);
		$tmpl->AddVar('timesheet_summary','HEADING_COLSPAN',$Colspan);
		$tmpl->AddVar('timesheet_summary','SPACER_COLSPAN',$Colspan - 4);
		$tmpl->AddVar('timesheet_summary','CLIENT_ID',$Client_ID);
		$tmpl->AddVar('timesheet_summary','NEXT_PERIOD_START',$Next_Period_Start);
		$tmpl->AddVar('timesheet_summary','NEXT_PERIOD_END',$Next_Period_End);
		$tmpl->AddVar('timesheet_summary','LAST_PERIOD_START',$Last_Period_Start);
		$tmpl->AddVar('timesheet_summary','LAST_PERIOD_END',$Last_Period_End);
		$tmpl->AddVar('timesheet_summary','TIMESHEET_PERIOD', date('d/m/Y',$Start_Timestamp) .' - '. date('d/m/Y',$End_Timestamp));
		$tmpl->AddVar('timesheet_summary','PERIOD',$Period);
		$tmpl->AddVar('timesheet_summary','ACTION',$action);
		$tmpl->AddVar('timesheet_summary','TYPE',ucfirst($Type).'s');
		$tmpl->AddVar('timesheet_summary','SUMMARY',$summary);
		$tmpl->AddVar('timesheet_summary','TOTAL_PAYMENT',$total_payment_value);
		$tmpl->AddVar('timesheet_summary','TOTAL_OUTGOING',$total_outgoing_value);
		$tmpl->AddVar('timesheet_summary','TOTAL_PROFIT',intval($total_payment_value - $total_outgoing_value));
		$tmpl->AddVar('timesheet_summary','CLIENT',$Client_array[$Client_ID]);
		return $tmpl->GetParsedTemplate('timesheet_summary');
	}



	function outgoingsGraph($Type,$Start_Timestamp,$End_Timestamp,$Period,$Vendor_ID=false,$action='default',$summary='page=reports')
	{
		if($action == 'default')
		{
			if($Type == 'outgoings')
			{
				$action = 'page=clients&action=outgoings_summary';
			}
			elseif($Type == 'invoice')
			{
				$action = 'page=clients&action=invoice_summary';
			}
		}

		global $tmpl;
		global $db_reader;
		global $Client_array;
		global $User_ID;
		$tmpl->ReadTemplatesFromFile('outgoings.tmpl.html');
		//MYSQL FORMATED
		$MySQL_Start_Timestamp = mysqlDatetime($Start_Timestamp);
		$MySQL_End_Timestamp = mysqlDatetime($End_Timestamp);

		$MySQL_Start_Date = mysqlDate($Start_Timestamp);
		$MySQL_End_Date = mysqlDate($End_Timestamp);

		$Year = date('Y',$Start_Timestamp);
		$Month = date('m',$Start_Timestamp);
		if($Vendor_ID)
		{
			$WHERE .= " AND Vendor_ID = $Vendor_ID ";
		}
		if($Type == 'outgoings')
		{
			$WHERE .= "AND Date >= '$MySQL_Start_Date' AND Date <= '$MySQL_End_Date'";
		}

		//set the timestamps for links
		$Next_Period_Start = $End_Timestamp ;
		$Last_Period_End = $Start_Timestamp ;

		//set timestamps and queries  specific to the period
		if($Period == 'year')
		{
			$Next_Period_End = $Next_Period_Start + ONE_YEAR;
			$Last_Period_Start = $Last_Period_End - ONE_YEAR;
			$Period_Start = 1;
			$Period_End = 12;
			$Period_Length = 12;
			if($Type == 'outgoings')
			{
				$query = "SELECT IF(Outgoing_Type_ID,Outgoing_Type_ID,'none') AS Outgoing_Type_ID,MONTH(Date) AS PERIOD, SUM(Value) AS Value
				FROM `". PHPA_OUTGOING_TABLE ."`  O LEFT JOIN ". PHPA_VENDOR_TABLE ." V ON(O.Vendor_ID = V.ID) 
				WHERE V.User_ID = $User_ID $WHERE
				GROUP BY MONTH(Date),Outgoing_Type_ID ";
			}
			$View_Period = 'month';
		}
		elseif($Period == 'week')
		{		
			$Next_Period_End = $Next_Period_Start + ONE_WEEK;
			$Last_Period_Start = $Last_Period_End - ONE_WEEK;
			$Period_Start = date('d',$Start_Timestamp);
			$Period_End = date('d',$End_Timestamp);
			//last day of month
			$Peiod_Cutoff = date('t',$Start_Timestamp);
			$Period_Length = 7;
			if($Type == 'outgoings')
			{
				$query = "SELECT IF(Outgoing_Type_ID,Outgoing_Type_ID,'none') AS Outgoing_Type_ID, DATE_FORMAT(Date,'%e') AS PERIOD, SUM(Value) AS Value
				FROM `". PHPA_OUTGOING_TABLE ."` O LEFT JOIN ". PHPA_VENDOR_TABLE ." V ON(O.Vendor_ID = V.ID) 
				WHERE V.User_ID = $User_ID $WHERE
				GROUP BY DATE_FORMAT(Date,'%e'),Outgoing_Type_ID ";
			}

			$View_Period = 'day';
		}

		$result = $db_reader->query($query);
		while($row = $result->fetchRow(MDB2_FETCHMODE_ASSOC))
		{
			$Period_Data[$row['PERIOD']][$row['Outgoing_Type_ID']]['Value'] = $row['Value'];
			$max_value = max($max_value, $row['Value']);
			$total_value += $row['Value'];

		}



		//loop through months and output results

		//catch loop over end of month


		for($j=0;$j<$Period_Length;$j++)
		{
			$i = $Period_Start + $j;
			//catch end of month and reset
			if($Period_Cutoff && $i > $Period_Cutoff)
			{
				$i = 1 + $j;
			}
			if($I == 'a') $I = 'b';
			else $I = 'a';
			//heading
			if($Period == 'year')
			{
				$this_Period = date('M',mktime(0,0,0,$i,1,$Year));
				$this_Start_Timestamp = mktime(0,0,0,$i,1,$Year);
				$this_Numeric_Month = date('m',mktime(0,0,0,$i,1,$Year));
			}
			elseif($Period == 'week')
			{
				$this_Period = date('D',mktime(0,0,0,$Month,$i,$Year));
				$this_Start_Timestamp = mktime(0,0,0,$Month,$i,$Year);
				$this_Numeric_Month = date('m',mktime(0,0,0,$Month,$i,$Year));
			}


			$tmpl->AddVar('months','PERIOD',$this_Period);
			$tmpl->AddVar('months','VIEW_PERIOD',$View_Period);
			$tmpl->AddVar('months','START_TIMESTAMP',$this_Start_Timestamp);
			$tmpl->AddVar('months','VENDOR_ID',$Vendor_ID);
			$tmpl->AddVar('months','I',$I);
			$tmpl->ParseTemplate('months','a');

			//get total
			if($Period_Data[$i])
			{
				foreach($Period_Data[$i] as $this_Data)
				{
					$sum[$i] += $this_Data['Value'];
				}
			}

			//graphs
			if($max_value <> 0)
			{
				$this_value = round(($sum[$i]/$max_value)*SMALL_GRAPH_HEIGHT);
			}
			else
			{
				$this_value = 0;
			}
			$tmpl->AddVar('month_outgoings_graphs','VALUE_GRAPH',$this_value);
			$tmpl->AddVar('month_outgoings_graphs','VALUE',intval($sum[$i]));
			$tmpl->AddVar('month_outgoings_graphs','VIEW_PERIOD',$View_Period);
			$tmpl->AddVar('month_outgoings_graphs','START_TIMESTAMP',$this_Start_Timestamp);
			$tmpl->AddVar('month_outgoings_graphs','VENDOR_ID',$Vendor_ID);
			$tmpl->AddVar('month_outgoings_graphs','I',$I);
			$tmpl->ParseTemplate('month_outgoings_graphs','a');

			//figures
			$tmpl->AddVar('month_outgoings_figures','VALUE',intval($sum[$i]));
			$tmpl->AddVar('month_outgoings_figures','I',$I);
			$tmpl->ParseTemplate('month_outgoings_figures','a');
		}

		//do the breakdown by type
		$query = "SELECT ID,Outgoing_Type FROM ". PHPA_OUTGOING_TYPE_TABLE ." WHERE User_ID = $User_ID";
		$result = $db_reader->query($query);
		while($this_row = $result->fetchRow(MDB2_FETCHMODE_ASSOC))
		{
			$rows[$this_row['ID']] = $this_row['Outgoing_Type'];
		}
		$rows['none'] = 'unclassified';
		foreach($rows as $row['ID'] => $row['Outgoing_Type'])
		{
			if(!$I) $I = 1;
			else $I = 0;
			$J = 'b';
			$tmpl->ClearTemplate('outgoing_type_breakdown_results');
			$tmpl->AddVar('outgoing_type_breakdown','OUTGOING_TYPE',$row['Outgoing_Type']);
			$tmpl->AddVar('outgoing_type_breakdown','I',$I);
			$tmpl->AddVar('outgoing_type_breakdown','J',$J);
			for($j=0;$j<$Period_Length;$j++)
			{
				$i = $Period_Start + $j;
				//catch end of month and reset
				if($Period_Cutoff && $i > $Period_Cutoff)
				{
					$i = 1 + $j;
				}
				if($J == 'a') $J = 'b';
				else $J = 'a';
				//do 
				$tmpl->AddVar('outgoing_type_breakdown_results','VALUE',$Period_Data[$i][$row['ID']]['Value']);
				$tmpl->AddVar('outgoing_type_breakdown_results','I',$I);
				$tmpl->AddVar('outgoing_type_breakdown_results','J',$J);
				$tmpl->ParseTemplate('outgoing_type_breakdown_results','a');
				$outgoing_type_sum[$row['ID']] += $Period_Data[$i][$row['ID']]['Value'];
			}

			//Totals
			if($J == 'a') $J = 'b';
			else $J = 'a';
			$tmpl->AddVar('outgoing_type_breakdown_results','VALUE',$outgoing_type_sum[$row['ID']]);
			$tmpl->AddVar('outgoing_type_breakdown_results','I',$I);
			$tmpl->AddVar('outgoing_type_breakdown_results','J',$J);
			$tmpl->ParseTemplate('outgoing_type_breakdown_results','a');
			//end of row
			$tmpl->ParseTemplate('outgoing_type_breakdown','a');
		}
		//years for selector
		for($i=2002;$i<=2010;$i++)
		{
			$years[$i] = $i;
		}

		//colspan calculation 
		$Colspan = ($Period_Length);
		$tmpl->AddVar('outgoings_summary','HEADING_COLSPAN',$Colspan);
		$tmpl->AddVar('outgoings_summary','SPACER_COLSPAN',$Colspan - 4);
		$tmpl->AddVar('outgoings_summary','VENDOR_ID',$Vendor_ID);
		$tmpl->AddVar('outgoings_summary','NEXT_PERIOD_START',$Next_Period_Start);
		$tmpl->AddVar('outgoings_summary','NEXT_PERIOD_END',$Next_Period_End);
		$tmpl->AddVar('outgoings_summary','LAST_PERIOD_START',$Last_Period_Start);
		$tmpl->AddVar('outgoings_summary','LAST_PERIOD_END',$Last_Period_End);
		$tmpl->AddVar('outgoings_summary','TIMESHEET_PERIOD', date('d/m/Y',$Start_Timestamp) .' - '. date('d/m/Y',$End_Timestamp));
		$tmpl->AddVar('outgoings_summary','PERIOD',$Period);
		$tmpl->AddVar('outgoings_summary','ACTION',$action);
		$tmpl->AddVar('outgoings_summary','SUMMARY',$summary);
		$tmpl->AddVar('outgoings_summary','TOTAL_VALUE',$total_value);
		$tmpl->AddVar('outgoings_summary','TOTAL_TIME',round($total_time/ONE_HOUR));
		$tmpl->AddVar('outgoings_summary','CLIENT',$Client_array[$Vendor_ID]);
		return $tmpl->GetParsedTemplate('outgoings_summary');
	}


	function viewTimesheet($Start_Timestamp,$End_Timestamp,$Period,$Client_ID=false,$csv=false,$Project_ID=false)
	{
		global $tmpl;
		global $db_reader;
		global $User_ID;
		if($csv)
		{
			$tmpl->ReadTemplatesFromFile('csv_timesheet.tmpl.html');
		}
		else
		{
			$tmpl->ReadTemplatesFromFile('timesheet.tmpl.html');
		}

		$Next_Period_Start = $End_Timestamp ;
		$Last_Period_End = $Start_Timestamp ;
		if($Period == 'year')
		{
			$Next_Period_End = $Next_Period_Start + ONE_YEAR;
			$Last_Period_Start = $Last_Period_End - ONE_YEAR;
			$Timesheet_Period = date('Y',$Start_Timestamp);
		}
		elseif($Period == 'month')
		{
			//it's seems seconds are dangerous because of leap years etc. so we'll just do month checking
			$This_Month = date('m',$Last_Period_End);
			$Last_Month = (($This_Month - 1) + 12)%12;
			$Next_Month = (($This_Month + 2) + 12)%12;
			$This_Year = date('Y',$Last_Period_End);
			$Next_Year = $This_Year;
			$Last_Year = $This_Year;

			if($This_Month == 1)
			{
				$Last_Year = $This_Year -1;
				$Last_Month = 12;

			}
			elseif($This_Month >= 11)
			{
				$Next_Year = $This_Year + 1;
			}

			$Last_Period_Start = mktime(0,0,0,$Last_Month,1,$Last_Year);
			$Next_Period_End = mktime(0,0,0,$Next_Month,1,$Next_Year);
			$Timesheet_Period = date('F',$Start_Timestamp);
		}
		elseif($Period == 'week')
		{
			$Next_Period_End = $Next_Period_Start + ONE_WEEK;
			$Last_Period_Start = $Last_Period_End - ONE_WEEK;
			$Timesheet_Period = date('d-m-Y',$Start_Timestamp) .' - '. date('d-m-Y',$End_Timestamp);
		}
		elseif($Period == 'day')
		{
			$Next_Period_End = $Next_Period_Start + ONE_DAY;
			$Last_Period_Start = $Last_Period_End - ONE_DAY;
			$Timesheet_Period = date('d-m-Y',$Start_Timestamp);
		}

		//MYSQL FORMATED
		$MySQL_Start_Timestamp = mysqlDatetime($Start_Timestamp);
		$MySQL_End_Timestamp = mysqlDatetime($End_Timestamp);

		$Year = date('Y',$Start_Timestamp);
		if($Client_ID)
		{
			$WHERE = "Client_ID = $Client_ID AND ";
		}

		//ignore times and dates if for a project
		if($Project_ID)
		{
			$WHERE .= "PT.Project_ID = $Project_ID AND TS.ID = PT.Timesheet_ID";
			$FROM = ", ". PHPA_PROJECT_TIMESHEET_TABLE ." PT";
			//get Project Title
			$query = "SELECT Title FROM ". PHPA_PROJECT_TABLE ." WHERE ID = $Project_ID";
			$Title = $db_reader->queryOne($query);
			$Timesheet_Period = "Project '$Title'";
		}
		else
		{
			$WHERE .= "Timestamp >= '$MySQL_Start_Timestamp' AND Timestamp < '$MySQL_End_Timestamp'";
		}
		//look up Timesheets
		$query = "SELECT TS.ID,TS.Client_ID,LEFT(TS.Description,40) AS DESCRIPTION,TS.Value,DATE_FORMAT(TS.Timestamp,'%a, %d %b %Y %h:%m:%s') AS DATE,TS.Time,TIME_TO_SEC(Time) AS SECONDS, C.Company_Name
		FROM ". PHPA_TIMESHEET_TABLE ." TS LEFT JOIN ". PHPA_CLIENT_TABLE ." C ON (TS.Client_ID = C.ID) $FROM
		WHERE C.User_ID = $User_ID AND $WHERE
		GROUP BY TS.Timestamp DESC";
		$result = $db_reader->query($query);
		while($row = $result->fetchRow(MDB2_FETCHMODE_ASSOC))
		{
			if($i) $i=0;
			else $i=1;
			$tmpl->AddVar('timesheet_results','I',$i);
			foreach($row as $key => $value)
			{
				if($csv)
				{
					$value = addslashes($value);
				}
				$tmpl->AddVar('timesheet_results',$key,$value);
			}
			$tmpl->ParseTemplate('timesheet_results','a');
			$CLIENT_TOTALS[$row['Client_ID']]['Time'] += $row['SECONDS'];
			$CLIENT_TOTALS[$row['Client_ID']]['Value'] += $row['Value'];
			$CLIENT_TOTALS[$row['Client_ID']]['Company_Name'] = $row['Company_Name'];
			$TOTAL_VALUE += $row['Value'];
			$TOTAL_TIME += $row['SECONDS'];
			$TIMESHEETS++;
		}
		$tmpl->AddVar('view_timesheet','TOTAL_VALUE',$TOTAL_VALUE);
		$tmpl->AddVar('view_timesheet','TOTAL_TIME',formatTime($TOTAL_TIME));
		$tmpl->AddVar('view_timesheet','TIMESHEETS',$TIMESHEETS);
		$tmpl->AddVar('view_timesheet','NEXT_PERIOD_START',$Next_Period_Start);
		$tmpl->AddVar('view_timesheet','NEXT_PERIOD_END',$Next_Period_End);
		$tmpl->AddVar('view_timesheet','LAST_PERIOD_START',$Last_Period_Start);
		$tmpl->AddVar('view_timesheet','LAST_PERIOD_END',$Last_Period_End);
		$tmpl->AddVar('view_timesheet','PERIOD',$Period);
		$tmpl->AddVar('view_timesheet','TIMESHEET_PERIOD',$Timesheet_Period);
		$tmpl->AddVar('view_timesheet','PROJECT_ID',$Project_ID);

		$tmpl->AddVar('view_timesheet','CLIENT_ID',$Client_ID);

		if($CLIENT_TOTALS)
		{
			foreach($CLIENT_TOTALS as $this_Client_ID => $array)
			{
				if($i) $i=0;
				else $i=1;
				$tmpl->AddVar('client_results','I',$i);
				$tmpl->AddVar('client_results','CLIENT_ID',$this_Client_ID);
				$tmpl->AddVar('client_results','COMPANY_NAME',$array['Company_Name']);
				$tmpl->AddVar('client_results','TOTAL_TIME',formatTime($array['Time']));
				$tmpl->AddVar('client_results','TOTAL_VALUE',$array['Value']);
				$tmpl->ParseTemplate('client_results','a');
			}
		}
		return $tmpl->GetParsedTemplate('view_timesheet'); 
	}

	function viewOutgoings($Start_Timestamp,$End_Timestamp,$Period,$Vendor_ID=false,$Outgoing_Type_ID=false,$csv=false)
	{
		global $tmpl;
		global $db_reader;
		global $User_ID;
		if($csv)
		{
			$tmpl->ReadTemplatesFromFile('csv_outgoings.tmpl.html');
		}
		else
		{
			$tmpl->ReadTemplatesFromFile('outgoings.tmpl.html');
		}
		$Next_Period_Start = $End_Timestamp ;
		$Last_Period_End = $Start_Timestamp ;
		if($Period == 'year')
		{
			$Next_Period_End = $Next_Period_Start + ONE_YEAR;
			$Last_Period_Start = $Last_Period_End - ONE_YEAR;
			$Timesheet_Period = date('Y',$Start_Timestamp);
		}
		elseif($Period == 'month')
		{
			//it's seems seconds are dangerous because of leap years etc. so we'll just do month checking
			$This_Month = date('m',$Last_Period_End);
			$Last_Month = (($This_Month - 1) + 12)%12;
			$Next_Month = (($This_Month + 2) + 12)%12;
			$This_Year = date('Y',$Last_Period_End);
			$Next_Year = $This_Year;
			$Last_Year = $This_Year;

			if($This_Month == 1)
			{
				$Last_Year = $This_Year -1;
				$Last_Month = 12;

			}
			elseif($This_Month >= 11)
			{
				$Next_Year = $This_Year + 1;
			}

			$Last_Period_Start = mktime(0,0,0,$Last_Month,1,$Last_Year);
			$Next_Period_End = mktime(0,0,0,$Next_Month,1,$Next_Year);
			$Timesheet_Period = date('F',$Start_Timestamp);
			/*		echo "<br><br><br>Year Last $Last_Year This $This_Year NExt $Next_Year <br>";
			echo "Month Last $Last_Month This $This_Month NExt $Next_Month <br>";
			echo "Last Period Start: ". date('d/m/Y H:m',$Last_Period_Start) ."<br>";
			echo "Last Period End: ". date('d/m/Y H:m',$Last_Period_End) ."<br>";
			echo "Next Period Start: ". date('d/m/Y H:m',$Next_Period_Start) ."<br>";
			echo "NExt Period End: ". date('d/m/Y H:m',$Next_Period_End) ."<br>";

			echo "<br><br>Start Timestamp ". date('d/m/Y H:m',$Start_Timestamp) ." End Timestamp ". date('d/m/Y H:m',$End_Timestamp) ."<br>";
			*/
		}
		elseif($Period == 'week')
		{
			$Last_Period_Start = $Last_Period_End - ONE_WEEK;
			$Timesheet_Period = date('d-m-Y',$Start_Timestamp) .' - '. date('d-m-Y',$End_Timestamp);
		}
		elseif($Period == 'day')
		{
			$Next_Period_End = $Next_Period_Start + ONE_DAY;
			$Last_Period_Start = $Last_Period_End - ONE_DAY;
			$Timesheet_Period = date('d-m-Y',$Start_Timestamp);
		}

		//MYSQL FORMATED
		$MySQL_Start_Timestamp = mysqlDatetime($Start_Timestamp);
		$MySQL_End_Timestamp = mysqlDatetime($End_Timestamp);

		$Year = date('Y',$Start_Timestamp);
		if($Vendor_ID)
		{
			$WHERE = "Vendor_ID = $Vendor_ID AND ";
		}
		if($Outgoing_Type_ID)
		{
			$WHERE = "Outgoing_Type_ID = $Outgoing_Type_ID AND ";
		}
		$WHERE .= "Date >= '$MySQL_Start_Timestamp' AND Date < '$MySQL_End_Timestamp'";

		//look up Outgoings
		$query = "SELECT O.Value - SUM(IF(OP.Value>0,OP.Value,0)) AS Due,SUM(IF(OP.Value>0,1,0)) AS Payments,O.ID,O.Vendor_ID,V.Company_Name AS VENDOR,LEFT(O.Description,40) AS DESCRIPTION,O.Value,DATE_FORMAT(O.Date,'%a, %d %b %Y') AS DATE,OT.Outgoing_Type
		FROM ". PHPA_OUTGOING_TABLE ." O LEFT JOIN ". PHPA_OUTGOING_TYPE_TABLE ." OT ON(O.Outgoing_Type_ID = OT.ID) LEFT JOIN ". PHPA_VENDOR_TABLE ." V ON (O.Vendor_ID = V.ID) LEFT JOIN ". PHPA_OUTGOING_PAYMENT_TABLE ." OP ON(OP.Outgoing_ID = O.ID)
		WHERE O.User_ID = $User_ID AND $WHERE
		GROUP BY O.ID
		ORDER BY O.Date";
		$result = $db_reader->query($query);
		while($row = $result->fetchRow(MDB2_FETCHMODE_ASSOC))
		{
			if($i) $i=0;
			else $i=1;
			$tmpl->AddVar('outgoings_results','I',$i);
			foreach($row as $key => $value) $tmpl->AddVar('outgoings_results',$key,$value);
			if($row['Due'] > 0)
			{
				$tmpl->AddVar('outgoings_results','DUE_CLASS','red');
			}
			else
			{
				$tmpl->AddVar('outgoings_results','DUE_CLASS','');
			}
			$tmpl->ParseTemplate('outgoings_results','a');
			$TOTAL_VALUE += $row['Value'];
			$TOTAL_DUE += $row['Due'];
			$ITEMS++;
		}
		$tmpl->AddVar('view_outgoings','ITEMS',$ITEMS);
		$tmpl->AddVar('view_outgoings','TOTAL_VALUE',$TOTAL_VALUE);
		$tmpl->AddVar('view_outgoings','TOTAL_DUE',$TOTAL_DUE);
		$tmpl->AddVar('view_outgoings','NEXT_PERIOD_START',$Next_Period_Start);
		$tmpl->AddVar('view_outgoings','NEXT_PERIOD_END',$Next_Period_End);
		$tmpl->AddVar('view_outgoings','LAST_PERIOD_START',$Last_Period_Start);
		$tmpl->AddVar('view_outgoings','LAST_PERIOD_END',$Last_Period_End);
		$tmpl->AddVar('view_outgoings','PERIOD',$Period);
		$tmpl->AddVar('view_outgoings','TIMESHEET_PERIOD',$Timesheet_Period);

		$tmpl->AddVar('view_outgoings','VENDOR_ID',$Vendor_ID);
		$tmpl->AddVar('view_outgoings','OUTGOING_TYPE_ID',$Outgoing_Type_ID);
		return $tmpl->GetParsedTemplate('view_outgoings'); 
	}


	function searchOutgoings($Search_Terms,$csv=false)
	{
		global $tmpl;
		global $db_reader;
		global $User_ID;
		if($csv)
		{
			$tmpl->ReadTemplatesFromFile('csv_outgoings.tmpl.html');
		}
		else
		{
			$tmpl->ReadTemplatesFromFile('outgoings.tmpl.html');
		}

		//create the WHERE Clause
		if($Search_Terms['Vendor_IDs'])
		{
			$Vendor_IDs .= implode(',',$Search_Terms['Vendor_IDs']);
			$WHERE .= " AND O.Vendor_ID IN ($Vendor_IDs)";
		}
		if($Search_Terms['Outgoing_Type_IDs'])
		{
			$Outgoing_Type_IDs .= implode(',',$Search_Terms['Outgoing_Type_IDs']);
			$WHERE .= " AND O.Outgoing_Type_ID IN ($Outgoing_Type_IDs)";
		}
		if($Search_Terms['Search_Query'])
		{
			$WHERE .= " AND O.Description LIKE '%{$Search_Terms['Search_Query']}%'"; 
		}
		if($Search_Terms['Price'])
		{
			$WHERE .= " AND O.Value LIKE '{$Search_Terms['Price']}%'"; 
		}

		//look up Outgoings
		$query = "SELECT O.Value - SUM(IF(OP.Value>0,OP.Value,0)) AS Due,SUM(IF(OP.Value>0,1,0)) AS Payments,O.ID,O.Vendor_ID,V.Company_Name AS VENDOR,LEFT(O.Description,40) AS DESCRIPTION,O.Value,O.Date,OT.Outgoing_Type
		FROM ". PHPA_OUTGOING_TABLE ." O LEFT JOIN ". PHPA_OUTGOING_TYPE_TABLE ." OT ON(O.Outgoing_Type_ID = OT.ID) LEFT JOIN ". PHPA_VENDOR_TABLE ." V ON (O.Vendor_ID = V.ID) LEFT JOIN ". PHPA_OUTGOING_PAYMENT_TABLE ." OP ON(OP.Outgoing_ID = O.ID)
		WHERE O.User_ID = $User_ID $WHERE
		GROUP BY O.ID
		ORDER BY O.Date";
		$result = $db_reader->query($query);
		while($row = $result->fetchRow(MDB2_FETCHMODE_ASSOC))
		{
			if($i) $i=0;
			else $i=1;
			$tmpl->AddVar('outgoings_results','I',$i);
			foreach($row as $key => $value) $tmpl->AddVar('outgoings_results',$key,$value);
			if($row['Due'] > 0)
			{
				$tmpl->AddVar('outgoings_results','DUE_CLASS','red');
			}
			else
			{
				$tmpl->AddVar('outgoings_results','DUE_CLASS','');
			}
			$tmpl->ParseTemplate('outgoings_results','a');
			$TOTAL_VALUE += $row['Value'];
			$TOTAL_DUE += $row['Due'];
			$ITEMS++;
		}
		$tmpl->AddVar('search_outgoings','SEARCH_QUERY',$Search_Query);
		$tmpl->AddVar('search_outgoings','ITEMS',$ITEMS);
		$tmpl->AddVar('search_outgoings','TOTAL_VALUE',$TOTAL_VALUE);
		$tmpl->AddVar('search_outgoings','TOTAL_DUE',$TOTAL_DUE);
		$tmpl->AddVar('search_outgoings','NEXT_PERIOD_START',$Next_Period_Start);
		$tmpl->AddVar('search_outgoings','NEXT_PERIOD_END',$Next_Period_End);
		$tmpl->AddVar('search_outgoings','LAST_PERIOD_START',$Last_Period_Start);
		$tmpl->AddVar('search_outgoings','LAST_PERIOD_END',$Last_Period_End);
		$tmpl->AddVar('search_outgoings','PERIOD',$Period);
		$tmpl->AddVar('search_outgoings','TIMESHEET_PERIOD',$Timesheet_Period);

		$tmpl->AddVar('search_outgoings','VENDOR_ID',$Vendor_ID);
		$tmpl->AddVar('search_outgoings','OUTGOING_TYPE_ID',$Outgoing_Type_ID);
		return $tmpl->GetParsedTemplate('search_outgoings'); 
	}


	function viewProjects($Start_Timestamp,$End_Timestamp,$Period,$Client_ID=false)
	{
		global $tmpl;
		global $Client_array;
		global $db_reader;
		global $User_ID;
		$tmpl->ReadTemplatesFromFile('projects.tmpl.html');

		$Next_Period_Start = $End_Timestamp ;
		$Last_Period_End = $Start_Timestamp ;
		if($Period == 'year')
		{
			$Next_Period_End = $Next_Period_Start + ONE_YEAR;
			$Last_Period_Start = $Last_Period_End - ONE_YEAR;
			$Timesheet_Period = date('Y',$Start_Timestamp);
		}
		elseif($Period == 'month')
		{
			//don't bother with ends we calculate it 
			$Last_Month = date('m',$Last_Period_End);
			$Last_Day = date('t',mktime(0,0,0,$Last_Month,1,date('Y',$Last_Period_End)));
			$Last_Period_Start = $Last_Period_End - (($Last_Day) * ONE_DAY);
			$Next_Month = date('m',$Next_Period_Start);
			$Last_Day = date('t',mktime(0,0,0,$Next_Month,1,date('Y',$Next_Period_Start)));
			$Next_Period_End = $Next_Period_Start + (($Last_Day) * ONE_DAY);
			$Timesheet_Period = date('F',$Start_Timestamp);
		}
		elseif($Period == 'week')
		{
			$Next_Period_End = $Next_Period_Start + ONE_WEEK;
			$Last_Period_Start = $Last_Period_End - ONE_WEEK;
			$Timesheet_Period = date('d-m-Y',$Start_Timestamp) .' - '. date('d-m-Y',$End_Timestamp);
		}
		elseif($Period == 'day')
		{
			$Next_Period_End = $Next_Period_Start + ONE_DAY;
			$Last_Period_Start = $Last_Period_End - ONE_DAY;
			$Timesheet_Period = date('d-m-Y',$Start_Timestamp);
		}

		//MYSQL FORMATED
		$MySQL_Start_Timestamp = mysqlDatetime($Start_Timestamp);
		$MySQL_End_Timestamp = mysqlDatetime($End_Timestamp);



		if($Client_ID)
		{
			$WHERE .= "P.Client_ID = $Client_ID AND ";
		}

		$WHERE .= "Date_Opened >= '$MySQL_Start_Timestamp' AND (IF(Date_Closed='0000-00-00',Date_Opened <= '$MySQL_End_Timestamp',Date_Closed <= '$MySQL_End_Timestamp'))";

		//look up Projects
		$query = "SELECT P.ID,LEFT(P.Description,40) AS DESCRIPTION,P.Date_Opened,P.Date_Closed,P.Client_ID,SUM(IF(PT.Timesheet_ID,1,0)) AS TIMESHEETS
		FROM ". PHPA_PROJECT_TABLE ." P LEFT JOIN ". PHPA_CLIENT_TABLE ." C ON(P.Client_ID = C.ID) LEFT JOIN ". PHPA_PROJECT_TIMESHEET_TABLE ." PT ON(PT.Project_ID = P.ID)
		WHERE C.User_ID = $User_ID AND $WHERE
		GROUP BY P.ID";
		$result = $db_reader->query($query);
		while($row = $result->fetchRow(MDB2_FETCHMODE_ASSOC))
		{
			if($i) $i=0;
			else $i=1;
			$tmpl->AddVar('project_results','I',$i);
			foreach($row as $key => $value) $tmpl->AddVar('project_results',$key,$value);
			$tmpl->AddVar('project_results','CLIENT',$Client_array[$row['Client_ID']]);
			$tmpl->ParseTemplate('project_results','a');
			$PROJECTS++;
		}
		$tmpl->AddVar('view_projects','PROJECTS',$PROJECTS);
		$tmpl->AddVar('view_projects','CLIENT',$Client_array[$Client_ID]);
		$tmpl->AddVar('view_projects','CLIENT_ID',$Client_ID);
		$tmpl->AddVar('view_projects','NEXT_PERIOD_START',$Next_Period_Start);
		$tmpl->AddVar('view_projects','NEXT_PERIOD_END',$Next_Period_End);
		$tmpl->AddVar('view_projects','LAST_PERIOD_START',$Last_Period_Start);
		$tmpl->AddVar('view_projects','LAST_PERIOD_END',$Last_Period_End);
		$tmpl->AddVar('view_projects','PERIOD',$Period);
		$tmpl->AddVar('view_projects','PROJECTS_PERIOD',$Timesheet_Period);

		return $tmpl->GetParsedTemplate('view_projects');
	}



	function viewQuotes($Start_Timestamp,$End_Timestamp,$Period,$Client_ID=false)
	{
		global $tmpl;
		global $Client_array;
		global $db_reader;
		global $User_ID;
		$tmpl->ReadTemplatesFromFile('quotes.tmpl.html');

		$Next_Period_Start = $End_Timestamp ;
		$Last_Period_End = $Start_Timestamp ;
		if($Period == 'year')
		{
			$Next_Period_End = $Next_Period_Start + ONE_YEAR;
			$Last_Period_Start = $Last_Period_End - ONE_YEAR;
			$Timesheet_Period = date('Y',$Start_Timestamp);
		}
		elseif($Period == 'month')
		{
			//don't bother with ends we calculate it 
			$Last_Month = date('m',$Last_Period_End);
			$Last_Day = date('t',mktime(0,0,0,$Last_Month,1,date('Y',$Last_Period_End)));
			$Last_Period_Start = $Last_Period_End - (($Last_Day) * ONE_DAY);
			$Next_Month = date('m',$Next_Period_Start);
			$Last_Day = date('t',mktime(0,0,0,$Next_Month,1,date('Y',$Next_Period_Start)));
			$Next_Period_End = $Next_Period_Start + (($Last_Day) * ONE_DAY);
			$Timesheet_Period = date('F',$Start_Timestamp);
		}
		elseif($Period == 'week')
		{
			$Next_Period_End = $Next_Period_Start + ONE_WEEK;
			$Last_Period_Start = $Last_Period_End - ONE_WEEK;
			$Timesheet_Period = date('d-m-Y',$Start_Timestamp) .' - '. date('d-m-Y',$End_Timestamp);
		}
		elseif($Period == 'day')
		{
			$Next_Period_End = $Next_Period_Start + ONE_DAY;
			$Last_Period_Start = $Last_Period_End - ONE_DAY;
			$Timesheet_Period = date('d-m-Y',$Start_Timestamp);
		}

		//MYSQL FORMATED
		$MySQL_Start_Timestamp = mysqlDatetime($Start_Timestamp);
		$MySQL_End_Timestamp = mysqlDatetime($End_Timestamp);



		if($Client_ID)
		{
			$WHERE .= "Q.Client_ID = $Client_ID AND ";
		}

		$WHERE .= "Date >= '$MySQL_Start_Timestamp' AND Date < '$MySQL_End_Timestamp'";

		//look up Quotes
		$query = "SELECT Q.ID,LEFT(Q.Description,40) AS DESCRIPTION,Q.Value,Q.Date,Q.Client_ID
		FROM ". PHPA_QUOTE_TABLE ." Q LEFT JOIN ". PHPA_CLIENT_TABLE ." C ON(Q.Client_ID = C.ID) 
		WHERE C.User_ID = $User_ID AND $WHERE
		GROUP BY Q.ID";
		$result = $db_reader->query($query);
		while($row = $result->fetchRow(MDB2_FETCHMODE_ASSOC))
		{
			if($i) $i=0;
			else $i=1;
			$tmpl->AddVar('quote_results','I',$i);
			foreach($row as $key => $value) $tmpl->AddVar('quote_results',$key,$value);
			$tmpl->AddVar('quote_results','CLIENT',$Client_array[$row['Client_ID']]);
			$tmpl->ParseTemplate('quote_results','a');
			$QUOTES[] = $row['Value'];
		}
		if($QUOTES)
		{
			$tmpl->AddVar('view_quotes','QUOTES',sizeof($QUOTES));
			$tmpl->AddVar('view_quotes','TOTAL',array_sum($QUOTES));
		}
		$tmpl->AddVar('view_quotes','CLIENT',$Client_array[$Client_ID]);
		$tmpl->AddVar('view_quotes','CLIENT_ID',$Client_ID);
		$tmpl->AddVar('view_quotes','NEXT_PERIOD_START',$Next_Period_Start);
		$tmpl->AddVar('view_quotes','NEXT_PERIOD_END',$Next_Period_End);
		$tmpl->AddVar('view_quotes','LAST_PERIOD_START',$Last_Period_Start);
		$tmpl->AddVar('view_quotes','LAST_PERIOD_END',$Last_Period_End);
		$tmpl->AddVar('view_quotes','PERIOD',$Period);
		$tmpl->AddVar('view_quotes','QUOTES_PERIOD',$Timesheet_Period);

		return $tmpl->GetParsedTemplate('view_quotes');
	}



	function viewInvoices($Start_Timestamp,$End_Timestamp,$Period,$Client_ID=false)
	{
		global $tmpl;
		global $Client_array;
		global $db_reader;
		global $User_ID;
		$tmpl->ReadTemplatesFromFile('invoices.tmpl.html');

		$Next_Period_Start = $End_Timestamp ;
		$Last_Period_End = $Start_Timestamp ;
		if($Period == 'year')
		{
			$Next_Period_End = $Next_Period_Start + ONE_YEAR;
			$Last_Period_Start = $Last_Period_End - ONE_YEAR;
			$Timesheet_Period = date('Y',$Start_Timestamp);
		}
		elseif($Period == 'month')
		{
			//don't bother with ends we calculate it 
			$Last_Month = date('m',$Last_Period_End);
			$Last_Day = date('t',mktime(0,0,0,$Last_Month,1,date('Y',$Last_Period_End)));
			$Last_Period_Start = $Last_Period_End - (($Last_Day) * ONE_DAY);
			$Next_Month = date('m',$Next_Period_Start);
			$Last_Day = date('t',mktime(0,0,0,$Next_Month,1,date('Y',$Next_Period_Start)));
			$Next_Period_End = $Next_Period_Start + (($Last_Day) * ONE_DAY);
			$Timesheet_Period = date('F',$Start_Timestamp);
		}
		elseif($Period == 'week')
		{
			$Next_Period_End = $Next_Period_Start + ONE_WEEK;
			$Last_Period_Start = $Last_Period_End - ONE_WEEK;
			$Timesheet_Period = date('d-m-Y',$Start_Timestamp) .' - '. date('d-m-Y',$End_Timestamp);
		}
		elseif($Period == 'day')
		{
			$Next_Period_End = $Next_Period_Start + ONE_DAY;
			$Last_Period_Start = $Last_Period_End - ONE_DAY;
			$Timesheet_Period = date('d-m-Y',$Start_Timestamp);
		}

		//MYSQL FORMATED
		$MySQL_Start_Timestamp = mysqlDatetime($Start_Timestamp);
		$MySQL_End_Timestamp = mysqlDatetime($End_Timestamp);



		if($Client_ID)
		{
			$WHERE .= "I.Client_ID = $Client_ID AND ";
		}

		$WHERE .= "Date >= '$MySQL_Start_Timestamp' AND Date < '$MySQL_End_Timestamp'";

		//look up Invoices
		$query = "SELECT I.Value - SUM(IF(IP.Value>0,IP.Value,0)) AS Due,SUM(IF(IP.Value>0,1,0)) AS Payments, I.ID,I.Reference,LEFT(I.Description,40) AS DESCRIPTION,I.Value,I.Date,I.Client_ID,IF(RPL.Invoice_ID IS NULL,'',IF(RPL.Invoice_ID = RPL.Repeat_Invoice_ID,'master','repeat')) AS Repeat_Status
		FROM ". PHPA_INVOICE_TABLE ." I LEFT JOIN ". PHPA_CLIENT_TABLE ." C ON(I.Client_ID = C.ID) LEFT JOIN ". PHPA_INVOICE_PAYMENT_TABLE ." IP ON(IP.Invoice_ID = I.ID) LEFT JOIN ". PHPA_REPEAT_INVOICE_LOG_TABLE ." RPL ON(RPL.Invoice_ID = I.ID)
		WHERE C.User_ID = $User_ID AND $WHERE
		GROUP BY I.ID";
		$result = $db_reader->query($query);
		while($row = $result->fetchRow(MDB2_FETCHMODE_ASSOC))
		{
			if($i) $i=0;
			else $i=1;
			$tmpl->AddVar('invoice_results','I',$i);
			foreach($row as $key => $value) $tmpl->AddVar('invoice_results',$key,$value);
			$tmpl->AddVar('invoice_results','CLIENT',$Client_array[$row['Client_ID']]);
			$tmpl->ParseTemplate('invoice_results','a');
			$INVOICES[] = $row['Value'];
			$TOTAL_DUE += $row['Due'];
		}
		if($INVOICES)
		{
			$tmpl->AddVar('view_invoices','INVOICES',sizeof($INVOICES));
			$tmpl->AddVar('view_invoices','TOTAL',array_sum($INVOICES));
		}
		$tmpl->AddVar('view_invoices','TOTAL_DUE',$TOTAL_DUE);
		$tmpl->AddVar('view_invoices','CLIENT',$Client_array[$Client_ID]);
		$tmpl->AddVar('view_invoices','CLIENT_ID',$Client_ID);
		$tmpl->AddVar('view_invoices','NEXT_PERIOD_START',$Next_Period_Start);
		$tmpl->AddVar('view_invoices','NEXT_PERIOD_END',$Next_Period_End);
		$tmpl->AddVar('view_invoices','LAST_PERIOD_START',$Last_Period_Start);
		$tmpl->AddVar('view_invoices','LAST_PERIOD_END',$Last_Period_End);
		$tmpl->AddVar('view_invoices','PERIOD',$Period);
		$tmpl->AddVar('view_invoices','INVOICES_PERIOD',$Timesheet_Period);

		return $tmpl->GetParsedTemplate('view_invoices');
	}



	function viewForecastRepeatInvoices($Start_Timestamp,$End_Timestamp,$Period,$Client_ID=false)
	{
		global $tmpl;
		global $Client_array;
		global $db_reader;
		global $User_ID;
		$tmpl->ReadTemplatesFromFile('forecast_repeat_invoices.tmpl.html');

		$Next_Period_Start = $End_Timestamp ;
		$Last_Period_End = $Start_Timestamp ;

		//only have a month for repeat invoice forecast
		$Period = 'month';
		if($Period == 'month')
		{
			//don't bother with ends we calculate it 
			$Last_Month = date('m',$Last_Period_End);
			$Last_Day = date('t',mktime(0,0,0,$Last_Month,1,date('Y',$Last_Period_End)));
			$Last_Period_Start = $Last_Period_End - (($Last_Day) * ONE_DAY);
			$Next_Month = date('m',$Next_Period_Start);
			$Last_Day = date('t',mktime(0,0,0,$Next_Month,1,date('Y',$Next_Period_Start)));
			$Next_Period_End = $Next_Period_Start + (($Last_Day) * ONE_DAY);
			$Timesheet_Period = date('F',$Start_Timestamp);
		}
		//MYSQL FORMATED
		$MySQL_Start_Timestamp = mysqlDatetime($Start_Timestamp);
		$MySQL_End_Timestamp = mysqlDatetime($End_Timestamp);
		$this_Month = date('n',$Start_Timestamp);
		$this_Year = date('Y',$Start_Timestamp);



		if($Client_ID)
		{
			$WHERE .= "AND I.Client_ID = $Client_ID AND ";
		}

		$query = "SELECT RI.Day,I.ID,I.Reference,LEFT(I.Description,40) AS DESCRIPTION,I.Value,I.Date,I.Client_ID
		FROM `". PHPA_REPEAT_INVOICE_TABLE ."` RI LEFT JOIN ". PHPA_INVOICE_TABLE ." I ON(RI.Invoice_ID = I.ID) LEFT JOIN ". PHPA_CLIENT_TABLE ." C ON(I.Client_ID = C.ID) 
		WHERE C.User_ID = $User_ID AND I.Date < '$MySQL_End_Timestamp' AND (RI.Month = '*' OR RI.Month = '$this_Month') AND RI.Active = 'yes' $WHERE";
		$result = $db_reader->query($query);
		while($row = $result->fetchRow(MDB2_FETCHMODE_ASSOC))
		{
			$i++;
			//every day	
			if($row['Day'] == '*')
			{
				$days = date('t',mktime(1,1,1,$this_Month,1,2000));
				for($j=1;$j<=$days;$j++)
				{
					//format date
					$row['Date'] = $this_Year .'-'. $this_Month .'-'. $i;
					$Data[$i] = $row;
					$i++;
				}
			}
			//once a month
			else
			{
				$bits = explode('-',$row['Date']);
				$row['Date'] = $this_Year .'-'. $this_Month .'-'. $bits[2];
				$Data[$i] = $row;
			}
		}


		if($Data)
		{
			foreach($Data as $row)
			{
				if($i) $i=0;
				else $i=1;
				$tmpl->AddVar('repeat_invoice_results','I',$i);
				foreach($row as $key => $value) $tmpl->AddVar('repeat_invoice_results',$key,$value);
				$tmpl->AddVar('repeat_invoice_results','CLIENT',$Client_array[$row['Client_ID']]);
				$tmpl->ParseTemplate('repeat_invoice_results','a');
				$INVOICES[] = $row['Value'];
				$TOTAL_DUE += $row['Due'];
			}
		}
		if($INVOICES)
		{
			$tmpl->AddVar('view_repeat_invoices','INVOICES',sizeof($INVOICES));
			$tmpl->AddVar('view_repeat_invoices','TOTAL',array_sum($INVOICES));
		}
		$tmpl->AddVar('view_repeat_invoices','TOTAL_DUE',$TOTAL_DUE);
		$tmpl->AddVar('view_repeat_invoices','CLIENT',$Client_array[$Client_ID]);
		$tmpl->AddVar('view_repeat_invoices','CLIENT_ID',$Client_ID);
		$tmpl->AddVar('view_repeat_invoices','NEXT_PERIOD_START',$Next_Period_Start);
		$tmpl->AddVar('view_repeat_invoices','NEXT_PERIOD_END',$Next_Period_End);
		$tmpl->AddVar('view_repeat_invoices','LAST_PERIOD_START',$Last_Period_Start);
		$tmpl->AddVar('view_repeat_invoices','LAST_PERIOD_END',$Last_Period_End);
		$tmpl->AddVar('view_repeat_invoices','PERIOD',$Period);
		$tmpl->AddVar('view_repeat_invoices','INVOICES_PERIOD',$Timesheet_Period);

		return $tmpl->GetParsedTemplate('view_repeat_invoices');
	}


	function viewRepeatInvoices($Start_Timestamp,$End_Timestamp,$Period,$Client_ID=false)
	{
		global $tmpl;
		global $Client_array;
		global $db_reader;
		global $User_ID;
		$tmpl->ReadTemplatesFromFile('repeat_invoices.tmpl.html');

		$Next_Period_Start = $End_Timestamp ;
		$Last_Period_End = $Start_Timestamp ;
		if($Period == 'year')
		{
			$Next_Period_End = $Next_Period_Start + ONE_YEAR;
			$Last_Period_Start = $Last_Period_End - ONE_YEAR;
			$Timesheet_Period = date('Y',$Start_Timestamp);
		}
		elseif($Period == 'month')
		{
			//don't bother with ends we calculate it 
			$Last_Month = date('m',$Last_Period_End);
			$Last_Day = date('t',mktime(0,0,0,$Last_Month,1,date('Y',$Last_Period_End)));
			$Last_Period_Start = $Last_Period_End - (($Last_Day) * ONE_DAY);
			$Next_Month = date('m',$Next_Period_Start);
			$Last_Day = date('t',mktime(0,0,0,$Next_Month,1,date('Y',$Next_Period_Start)));
			$Next_Period_End = $Next_Period_Start + (($Last_Day) * ONE_DAY);
			$Timesheet_Period = date('F',$Start_Timestamp);
		}
		elseif($Period == 'week')
		{
			$Next_Period_End = $Next_Period_Start + ONE_WEEK;
			$Last_Period_Start = $Last_Period_End - ONE_WEEK;
			$Timesheet_Period = date('d-m-Y',$Start_Timestamp) .' - '. date('d-m-Y',$End_Timestamp);
		}
		elseif($Period == 'day')
		{
			$Next_Period_End = $Next_Period_Start + ONE_DAY;
			$Last_Period_Start = $Last_Period_End - ONE_DAY;
			$Timesheet_Period = date('d-m-Y',$Start_Timestamp);
		}

		//MYSQL FORMATED
		$MySQL_Start_Timestamp = mysqlDatetime($Start_Timestamp);
		$MySQL_End_Timestamp = mysqlDatetime($End_Timestamp);



		if($Client_ID)
		{
			$WHERE .= "I.Client_ID = $Client_ID AND ";
		}

		$WHERE .= "Date >= '$MySQL_Start_Timestamp' AND Date < '$MySQL_End_Timestamp'";

		//look up Invoices
		$query = "SELECT I.Value - SUM(IF(IP.Value>0,IP.Value,0)) AS Due,SUM(IF(IP.Value>0,1,0)) AS Payments, I.ID,I.Reference,LEFT(I.Description,40) AS DESCRIPTION,I.Value,I.Date,I.Client_ID
		FROM ". PHPA_REPEAT_INVOICE_TABLE ." RI, ". PHPA_INVOICE_TABLE ." I LEFT JOIN ". PHPA_CLIENT_TABLE ." C ON(I.Client_ID = C.ID) LEFT JOIN ". PHPA_INVOICE_PAYMENT_TABLE ." IP ON(IP.Invoice_ID = I.ID)
		WHERE C.User_ID = $User_ID AND $WHERE AND RI.Invoice_ID = I.ID
		GROUP BY I.ID";
		$result = $db_reader->query($query);
		while($row = $result->fetchRow(MDB2_FETCHMODE_ASSOC))
		{
			if($i) $i=0;
			else $i=1;
			$tmpl->AddVar('repeat_invoice_results','I',$i);
			foreach($row as $key => $value) $tmpl->AddVar('repeat_invoice_results',$key,$value);
			$tmpl->AddVar('repeat_invoice_results','CLIENT',$Client_array[$row['Client_ID']]);
			$tmpl->ParseTemplate('repeat_invoice_results','a');
			$INVOICES[] = $row['Value'];
			$TOTAL_DUE += $row['Due'];
		}
		if($INVOICES)
		{
			$tmpl->AddVar('view_repeat_invoices','INVOICES',sizeof($INVOICES));
			$tmpl->AddVar('view_repeat_invoices','TOTAL',array_sum($INVOICES));
		}
		$tmpl->AddVar('view_repeat_invoices','TOTAL_DUE',$TOTAL_DUE);
		$tmpl->AddVar('view_repeat_invoices','CLIENT',$Client_array[$Client_ID]);
		$tmpl->AddVar('view_repeat_invoices','CLIENT_ID',$Client_ID);
		$tmpl->AddVar('view_repeat_invoices','NEXT_PERIOD_START',$Next_Period_Start);
		$tmpl->AddVar('view_repeat_invoices','NEXT_PERIOD_END',$Next_Period_End);
		$tmpl->AddVar('view_repeat_invoices','LAST_PERIOD_START',$Last_Period_Start);
		$tmpl->AddVar('view_repeat_invoices','LAST_PERIOD_END',$Last_Period_End);
		$tmpl->AddVar('view_repeat_invoices','PERIOD',$Period);
		$tmpl->AddVar('view_repeat_invoices','INVOICES_PERIOD',$Timesheet_Period);

		return $tmpl->GetParsedTemplate('view_repeat_invoices');
	}

	function viewUnpaidInvoices($all=false)
	{
		global $User_ID;
		global $db_reader;
		global $tmpl;
		global $Client_array;
		if(!$all)
		{
			$DATE = "AND I.Date <= NOW()";
		}
		$tmpl->ReadTemplatesFromFile('invoices.tmpl.html');
		//look up Invoices
		$query = "SELECT I.Value - SUM(IF(IP.Value>0,IP.Value,0)) AS Due,SUM(IF(IP.Value>0,1,0)) AS Payments, I.ID,I.Reference,LEFT(I.Description,40) AS DESCRIPTION,I.Value,I.Date,I.Client_ID
		FROM ". PHPA_INVOICE_TABLE ." I LEFT JOIN ". PHPA_CLIENT_TABLE ." C ON(I.Client_ID = C.ID) LEFT JOIN ". PHPA_INVOICE_PAYMENT_TABLE ." IP ON(IP.Invoice_ID = I.ID)
		WHERE C.User_ID = $User_ID $DATE
		GROUP BY I.ID HAVING Due > 0";
		$result = $db_reader->query($query);
		while($row = $result->fetchRow(MDB2_FETCHMODE_ASSOC))
		{
			if($i) $i=0;
			else $i=1;
			$tmpl->AddVar('invoice_results','I',$i);
			foreach($row as $key => $value) $tmpl->AddVar('invoice_results',$key,$value);
			$tmpl->AddVar('invoice_results','CLIENT',$Client_array[$row['Client_ID']]);
			$tmpl->ParseTemplate('invoice_results','a');
			$INVOICES[] = $row['Value'];
			$TOTAL_DUE += $row['Due'];
		}
		if($INVOICES)
		{
			$tmpl->AddVar('unpaid_invoices','INVOICES',sizeof($INVOICES));
			$tmpl->AddVar('unpaid_invoices','TOTAL',array_sum($INVOICES));
		}
		$tmpl->AddVar('unpaid_invoices','TOTAL_DUE',$TOTAL_DUE);
		$tmpl->AddVar('unpaid_invoices','CLIENT',$Client_array[$Client_ID]);
		$tmpl->AddVar('unpaid_invoices','CLIENT_ID',$Client_ID);
		$tmpl->AddVar('unpaid_invoices','NEXT_PERIOD_START',$Next_Period_Start);
		$tmpl->AddVar('unpaid_invoices','NEXT_PERIOD_END',$Next_Period_End);
		$tmpl->AddVar('unpaid_invoices','LAST_PERIOD_START',$Last_Period_Start);
		$tmpl->AddVar('unpaid_invoices','LAST_PERIOD_END',$Last_Period_End);
		$tmpl->AddVar('unpaid_invoices','PERIOD',$Period);
		$tmpl->AddVar('unpaid_invoices','INVOICES_PERIOD',$Timesheet_Period);

		return $tmpl->GetParsedTemplate('unpaid_invoices');
	}


	function viewPayments($Start_Timestamp,$End_Timestamp,$Period,$Client_ID=false)
	{
		global $tmpl;
		global $Client_array;
		global $db_reader;
		global $User_ID;
		$tmpl->ReadTemplatesFromFile('payments.tmpl.html');

		$Next_Period_Start = $End_Timestamp ;
		$Last_Period_End = $Start_Timestamp ;
		if($Period == 'year')
		{
			$Next_Period_End = $Next_Period_Start + ONE_YEAR;
			$Last_Period_Start = $Last_Period_End - ONE_YEAR;
			$Timesheet_Period = date('Y',$Start_Timestamp);
		}
		elseif($Period == 'month')
		{
			//don't bother with ends we calculate it 
			$Last_Month = date('m',$Last_Period_End);
			$Last_Day = date('t',mktime(0,0,0,$Last_Month,1,date('Y',$Last_Period_End)));
			$Last_Period_Start = $Last_Period_End - (($Last_Day) * ONE_DAY);
			$Next_Month = date('m',$Next_Period_Start);
			$Last_Day = date('t',mktime(0,0,0,$Next_Month,1,date('Y',$Next_Period_Start)));
			$Next_Period_End = $Next_Period_Start + (($Last_Day) * ONE_DAY);
			$Timesheet_Period = date('F',$Start_Timestamp);
		}
		elseif($Period == 'week')
		{
			$Next_Period_End = $Next_Period_Start + ONE_WEEK;
			$Last_Period_Start = $Last_Period_End - ONE_WEEK;
			$Timesheet_Period = date('d-m-Y',$Start_Timestamp) .' - '. date('d-m-Y',$End_Timestamp);
		}
		elseif($Period == 'day')
		{
			$Next_Period_End = $Next_Period_Start + ONE_DAY;
			$Last_Period_Start = $Last_Period_End - ONE_DAY;
			$Timesheet_Period = date('d-m-Y',$Start_Timestamp);
		}

		//MYSQL FORMATED
		$MySQL_Start_Timestamp = mysqlDatetime($Start_Timestamp);
		$MySQL_End_Timestamp = mysqlDatetime($End_Timestamp);



		if($Client_ID)
		{
			$WHERE .= "I.Client_ID = $Client_ID AND ";
		}

		$WHERE .= " IP.Timestamp >= '$MySQL_Start_Timestamp' AND IP.Timestamp < '$MySQL_End_Timestamp'";

		//look up Invoices
		$query = "SELECT IP.Value, DATE_FORMAT(IP.Timestamp,'%Y-%m-%d') AS PAYMENT_DATE, I.ID AS INVOICE_ID, LEFT(I.Description,40) AS DESCRIPTION,I.Date AS INVOICE_DATE,I.Client_ID,C.Company_Name
		FROM ". PHPA_INVOICE_PAYMENT_TABLE ." IP  LEFT JOIN ". PHPA_INVOICE_TABLE ." I ON(IP.Invoice_ID = I.ID) LEFT JOIN ". PHPA_CLIENT_TABLE ." C ON(I.Client_ID = C.ID)  
		WHERE C.User_ID = $User_ID AND $WHERE";
		$result = $db_reader->query($query);
		while($row = $result->fetchRow(MDB2_FETCHMODE_ASSOC))
		{
			if($i) $i=0;
			else $i=1;
			$tmpl->AddVar('payment_results','I',$i);
			foreach($row as $key => $value) $tmpl->AddVar('payment_results',$key,$value);
			$tmpl->ParseTemplate('payment_results','a');
			$PAYMENTS[] = $row['Value'];
		}
		$tmpl->AddVar('view_payments','TOTAL',array_sum($PAYMENTS));
		$tmpl->AddVar('view_payments','PAYMENTS',sizeof($PAYMENTS));
		$tmpl->AddVar('view_payments','CLIENT',$Client_array[$Client_ID]);
		$tmpl->AddVar('view_payments','CLIENT_ID',$Client_ID);
		$tmpl->AddVar('view_payments','NEXT_PERIOD_START',$Next_Period_Start);
		$tmpl->AddVar('view_payments','NEXT_PERIOD_END',$Next_Period_End);
		$tmpl->AddVar('view_payments','LAST_PERIOD_START',$Last_Period_Start);
		$tmpl->AddVar('view_payments','LAST_PERIOD_END',$Last_Period_End);
		$tmpl->AddVar('view_payments','PERIOD',$Period);
		$tmpl->AddVar('view_payments','INVOICES_PERIOD',$Timesheet_Period);

		return $tmpl->GetParsedTemplate('view_payments');
	}

	function sendClientInvoice($Invoice_ID,$file,$type='invoice')
	{
		global $db_writer;
		global $db_reader;
		global $tmpl;
		require_once(INCLUDE_PATH .'/includes/class.phpmailer.php');

		$query = "SELECT U.First_Name,U.Surname,U.Email,U.Company_Name,P.Value AS LETTER_FOOTER,C.Contact_First_Name AS Client_First_Name,C.Email AS Client_Email,I.Description,I.Reference,I.Value,I.Date,I.Client_ID,SUM(IF(IP.Value,IP.Value,0)) AS Paid,I.Value - SUM(IF(IP.Value,IP.Value,0)) AS Balance
		FROM ". PHPA_INVOICE_TABLE ." I LEFT JOIN ". PHPA_INVOICE_PAYMENT_TABLE ." IP ON(IP.Invoice_ID = I.ID), ". PHPA_CLIENT_TABLE ." C, ". PHPA_USER_TABLE ." U LEFT JOIN ". PHPA_PREFERENCES_TABLE ." P ON(P.User_ID = U.ID AND P.Preference = 'LETTER_FOOTER')
		WHERE I.ID = $Invoice_ID AND I.Client_ID = C.ID AND C.User_ID = U.ID
		GROUP BY I.ID";
		$result = $db_reader->query($query);
		$row = $result->FetchRow(MDB2_FETCHMODE_ASSOC);

		//create message
		$tmpl->ReadTemplatesFromFile('mail.tmpl.html');
		$tmpl->ClearTemplate($type);
		foreach($row as $key => $value)
		{
			$tmpl->AddVar($type,$key,$value);
		}

		$message = $tmpl->GetParsedTemplate($type);
		if($type == 'invoice')
		{
		$subject = "Your Invoice RE:{$row['Reference']}";
		}
		elseif(strstr($type,'reminder'))
		{
		$subject = "Invoice Reminder RE:{$row['Reference']}";
		}

		//send email
		$mail = new phpmailer();

		//config
		$mail->IsSendmail(true); 
		$mail->Host = "mail.bltc.net;localhost";


		//address etc.
		$mail->AddAddress($row['Client_Email'], $row['Client_First_Name']);
		$mail->From = $row['Email'];
		$mail->FromName = $row['First_Name'] .' '. $row['Last_Name'];
		$mail->Sender = $row['Email'];
		$mail->Body = $message;
		$mail->Subject .= $subject;
		//attatch pdf
		if($type == 'invoice')
		{
			$mail->AddAttachment($file,'invoice_'. $row['Reference'] .'.pdf','base64','application/pdf');
		}
		if(!checkValidEmail($row['Client_Email']) || !checkValidEmail($row['Email']) || !$mail->Send())
		{
			$Content .= "<br>There was an error sending the message to <b>".$row['Email']."</b> or <b>". $row['Client_Email'] ."</b>Email not valid?<br>";
			$Content .= "Error: ". $mail->ErrorInfo;
			trigger_error($Content);
			return false;
		}
		//update sent mail table
		else
		{
			$table = PHPA_CLIENT_SENT_MAIL_TABLE;
			$Mail_ID = getNextID(PHPA_CLIENT_SENT_MAIL_TABLE);
			$query = "INSERT INTO ". PHPA_CLIENT_SENT_MAIL_TABLE ."
			(ID,Client_ID,Email,Subject,Message)
			VALUES ($Mail_ID,{$row['Client_ID']},'{$row['Client_Email']}','". addslashes($subject) ."','". addslashes($message) ."')";
			$db_writer->exec($query);

			$query = "INSERT INTO ". PHPA_INVOICE_MAIL_LOOKUP_TABLE ."
			(Invoice_ID,Mail_ID,Type) VALUES ($Invoice_ID,$Mail_ID,'$type')";
			$db_writer->exec($query);

			//notify user and Admin
			$subject = "$type sent";
			$message = "Client {$row['Client_Email']} has just been mailed $type, ref {$row['Reference']}\n";
			$message .= "User: {$row['First_Name']} {$row['Last_Name']} Email: {$row['Email']}\n[automated email]";
			
			//admin
			mailAdmin($subject,$message);

			//user

			$headers="From: ". ADMIN_EMAIL;
			mail($row['Email'],$subject,$message,$headers);
			
			return true;
		}
	}

	function createRepeatInvoice($Invoice_ID,$Date=false,$notify=false)
	{
		global $db_writer;
		global $db_reader;

		//check this is a repeat invoice and get the date
		$query = "SELECT Reminders,Day,Month FROM ". PHPA_REPEAT_INVOICE_TABLE ." WHERE Invoice_ID = $Invoice_ID";
		$result = $db_reader->query($query);
		if($result->NumRows() > 0)
		{
			$row = $result->FetchRow(MDB2_FETCHMODE_ASSOC);
		}
		else
		{
			//no invoice found
			trigger_error("Invoice $Invoice_ID is not a repeat invoice");
			return false;
		}


		//format Date if not suppliced
		if(!$Date)
		{
			if($row['Month'] = '*')
			{
				$row['Month'] = date('n',NOW);
			}

			if($row['Day'] = '*')
			{
				$row['Day'] = date('d',NOW);
			}

			$Year = date('Y',NOW);
			$Date = "$Year-{$row['Month']}-{$row['Day']}";
		}
		//inserting invoice
		$New_Invoice_ID = getNextID(PHPA_INVOICE_TABLE);

		$query = "SELECT $New_Invoice_ID,C.Company_Name,Client_ID,Value,Description,'$Date','{$row['Reminders']}' FROM ". PHPA_INVOICE_TABLE ." I, ". PHPA_CLIENT_TABLE ." C WHERE I.ID = $Invoice_ID AND C.ID = I.Client_ID";
		print_r($db_reader->queryRow($query));

		//Basic Invoice Detail
		$query = "INSERT INTO ". PHPA_INVOICE_TABLE ." (ID,Client_ID,Value,Description,Date,Reminders) SELECT $New_Invoice_ID,Client_ID,Value,Description,'$Date','{$row['Reminders']}' FROM ". PHPA_INVOICE_TABLE ." WHERE ID = $Invoice_ID";
		$query = $db_writer->exec($query);

		//Repeat Invoice Reference Log
		$query = "INSERT INTO ". PHPA_REPEAT_INVOICE_LOG_TABLE ." (Invoice_ID,Repeat_Invoice_ID) VALUES($New_Invoice_ID,$Invoice_ID)";
		$query = $db_writer->exec($query);

		//update to current Address
		$query = "SELECT Address1,Address2,City,Region,Postcode FROM ". PHPA_CLIENT_TABLE ." C, ". PHPA_INVOICE_TABLE ." I WHERE I.ID = $Invoice_ID AND I.Client_ID = C.ID";
		$result = $db_reader->query($query);
		$row = $result->fetchRow(MDB2_FETCHMODE_ASSOC);
		$address = addslashes(implode("\n",$row));
		$query = "UPDATE ". PHPA_INVOICE_TABLE ." SET Invoice_Address = '$address' WHERE ID = $New_Invoice_ID";
		$query = $db_writer->exec($query);

		//Invoice Ref Number
		//NB need to consider AUTO_INVOICE_NUMBER case
		//get Last Invoice in the series
		$query = "SELECT Invoice_ID FROM ". PHPA_REPEAT_INVOICE_LOG_TABLE ." WHERE Repeat_Invoice_ID = $Invoice_ID ORDER BY Invoice_ID DESC LIMIT 1,1";
		$Last_Invoice_ID = $db_reader->queryOne($query);
		echo "Invoice_ID = $Last_Invoice_ID";
		if($Ref = nextRef($Last_Invoice_ID))
		{
			$query = "UPDATE ". PHPA_INVOICE_TABLE ." SET Reference = '$Ref' WHERE ID = $New_Invoice_ID";
			$query = $db_writer->exec($query);
		}

		//send notification
		if($notify)
		{
			global $tmpl;
			$tmpl->ReadTemplatesFromFile('mail.tmpl.html');
			$query = "SELECT C.User_ID,U.Company_Name AS User,C.Company_Name,I.Value,I.Reference,I.Date,I.Description FROM ". PHPA_INVOICE_TABLE ." I,". PHPA_CLIENT_TABLE ." C,". PHPA_USER_TABLE ." U WHERE I.ID = $Invoice_ID AND C.ID = I.Client_ID AND U.ID = C.User_ID";
			$result = $db_reader->query($query);
			$row = $result->FetchRow(MDB2_FETCHMODE_ASSOC);
			foreach($row as $key => $value)
			{
				$tmpl->AddVar('user_repeat_invoice_created',$key,$value);
			}


			$subject = "Repeat Invoice Created For $Company_Name";
			$message = $tmpl->GetParsedTemplate('user_repeat_invoice_created');

			//user
			mailUser($User_ID,$subject,$message);

			//admin
			mailAdmin($subject,$message);
		}
		return($New_Invoice_ID);
	}

	function deleteInvoice($Invoice_ID)
	{
		global $db_writer;
		global $db_reader;
		$query = "DELETE FROM ". PHPA_INVOICE_TABLE ." WHERE ID = $Invoice_ID";
		$db_writer->exec($query);
		$query = "DELETE FROM ". PHPA_REPEAT_INVOICE_TABLE ." WHERE Invoice_ID = $Invoice_ID";
		$db_writer->exec($query);

		//remove references to this invoices
		if($db_writer->affectedRows() > 0)
		{
			$query = "DELETE FROM ". PHPA_REPEAT_INVOICE_LOG_TABLE ." WHERE Repeat_Invoice_ID = $Invoice_ID";
			$db_writer->exec($query);
		}

		//if this is referencing a repeat invoice delete from log
		$query = "DELETE FROM ". PHPA_REPEAT_INVOICE_LOG_TABLE ." WHERE Invoice_ID = $Invoice_ID";
		$db_writer->exec($query);

		$db_writer->exec($query);
		$query = "DELETE FROM ". PHPA_PROJECT_INVOICE_TABLE ." WHERE Invoice_ID = $Invoice_ID";
		$db_writer->exec($query);
		$query = "SELECT Mail_ID FROM ". PHPA_INVOICE_MAIL_LOOKUP_TABLE ." WHERE Invoice_ID = $Invoice_ID";
		if($Mail_ID = $db_reader->queryOne($query))
		{
			$query = "DELETE FROM ". PHPA_CLIENT_SENT_MAIL_TABLE ." WHERE ID = $Mail_ID";
		}
		$db_writer->exec($query);
		$query = "DELETE FROM ". PHPA_INVOICE_MAIL_LOOKUP_TABLE ." WHERE Invoice_ID = $Invoice_ID";
		$db_writer->exec($query);
	}

	function mailUser($User_ID,$subject,$message,$headers=false)
	{
		global $db_reader;
		$query = "SELECT Email FROM ". PHPA_USER_TABLE ." WHERE ID = $User_ID";
		$email = $db_reader->queryOne($query);
		if(!$headers)
		{
			$headers="From: ". ADMIN_EMAIL;
		}

		if(!mail($email,$subject,$message,$headers))
		{
			return false;
		}
		else
		{
			return true;	
		}	
	}



	function mailAdmin($subject,$message,$headers=false)
	{
		if(!$headers)
		{
			$headers="From: ". ADMIN_EMAIL;
		}

		if(!mail(ADMIN_EMAIL,$subject,$message,$headers))
		{
			return false;
		}
		else
		{
			return true;	
		}	
	}



	function checkReminders()
	{
		global $User_ID;
		global $db_writer;
		global $db_reader;

		$Preference['DAYS_TO_SEND_FIRST_REMINDER'] = 30;
		$Preference['DAYS_TO_SEND_SECOND_REMINDER'] = 37;
		$Preference['DAYS_TO_SEND_FINAL_REMINDER'] = 44;

		foreach($Preference as $key => $value)
		{
			$query = "SELECT COUNT(*) FROM ". PHPA_PREFERENCES_TABLE ." WHERE User_ID = $User_ID AND Preference = '$key'";
			if($db_reader->queryOne($query) == 0)
			{
				$query = "INSERT INTO ". PHPA_PREFERENCES_TABLE ." (User_ID,Preference,Value) VALUES($User_ID,'$key','". addslashes($value) ."')";
				$db_writer->exec($query);
			}
		}
	}

	function nextRef($Invoice_ID)
	{
		global $db_reader;
		//get current
		$query = "SELECT C.User_ID,Client_ID,Reference FROM ". PHPA_INVOICE_TABLE ." I, ". PHPA_CLIENT_TABLE ." C WHERE I.ID = $Invoice_ID AND C.ID = I.Client_ID";
		list($User_ID,$Client_ID,$Ref) = $db_reader->queryRow($query);

		echo "Was $Ref\n";
		//client + id regex
		$cid_regex = '/^([a-zA-Z]*)([0-9]*)$/';

		//simple numeric - increment to user max
		if(is_numeric($Ref))
		{
			$query = "SELECT ID FROM ". PHPA_CLIENT_TABLE ." WHERE User_ID = $User_ID";
			$result = $db_reader->query($query);
			while(list($this_Client_ID) = $result->FetchRow())
			{
				$Clients[] = $this_Client_ID;
			}
			if($Clients)
			{
				$Clients = implode(',',$Clients);
				$query = "SELECT Reference FROM ". PHPA_INVOICE_TABLE ." WHERE Client_ID IN($Clients) AND Reference REGEXP '^[0-9]*$'"; 
				$result = $db_reader->query($query);
				while(list($this_Ref) = $result->FetchRow())
				{
					$Ref = max($this_Ref,$Ref);
				}
				echo "now $Ref\n";
				$Ref++;
			}
		}
		elseif(preg_match($cid_regex,$Ref,$matches))
		{
			$client = $matches[1];
			$id = $matches[2];
			$id++;

			$id = str_pad($id++,2,0,STR_PAD_LEFT);
			$Ref = $client . $id;
		}

		return($Ref);

	}

	function writeFile($filename,$content)
	{       
		clearstatcache();
		//
		// Let's make sure the file exists and is writable first.

		
			//
			// In our example we're opening $filename in append mode.
			// The file pointer is at the bottom of the file hence
			// that's where $somecontent will go when we fwrite() it.
			if (!$handle = fopen($filename, 'w')) 
			{
				$message = "Cannot open file ($filename)";
				trigger_error($message);
				return false;
			}
			//
			// Write $somecontent to our opened file.
			if (fwrite($handle, $content) === FALSE) 
			{
				$message = "Cannot open file2 ($filename)";
				trigger_error($message);
				return false;
			}
			//change permission and create
			chmod($filename,0666);
			fclose($handle);
			return true;
	
	}

	function human_time_diff( $from, $to = '' ) 
	{
		if ( empty($to) )
			$to = time();
		$diff = (int) abs($to - $from);
		if ($diff <= 3600) {
			$mins = round($diff / 60);
			if ($mins < 2) 
			{
				$mins = 1;
				$since = sprintf('%s min', $mins);
			}
			else
			{

				$since = sprintf('%s mins', $mins);
			}
		} 
		else if (($diff <= 86400) && ($diff > 3600)) 
		{
			$hours = round($diff / 3600);
			if ($hours < 2) {
				$hour = 1;
				$since = sprintf('%s hour', $hours);
			}
			else
			{

				$since = sprintf('%s hours', $hours);
			}
		} 
		elseif ($diff >= 86400) {
			$days = round($diff / 86400);
			if ($days < 2) {
				$days = 1;
				$since = sprintf('%s day', $days);
			}
			else
			{
				$since = sprintf('%s days', $days);

			}
		}
		return $since;
	}


?>
