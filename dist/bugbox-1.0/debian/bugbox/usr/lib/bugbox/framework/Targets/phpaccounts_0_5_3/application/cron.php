<?php
	/*
	*
	* Automated stuff
	*
	*/
	define('INCLUDE_PATH',getcwd());
	include(INCLUDE_PATH .'/includes/includes.php');

	//instantiate our display class, based on patTemplate
	$tmpl = new tmpl();
	//$tmpl = new patTemplate();
	//this is where the templates live
	$tmpl->setBasedir(INCLUDE_PATH .'/templates');

	// Read main Templates
	$tmpl->readTemplatesFromFile("main.tmpl.html");

	
	//Date to send invoice in advanced
	define('SEND_DATE',NOW + (2 * ONE_WEEK));
	
	//First User Advanced Warnings of invoice to be sent
	define('FIRST_WARNING',NOW + (3 * ONE_WEEK));
	
	//SECOND User Advanced Warnings of invoice to be sent
	define('SECOND_WARNING',NOW + (4 * ONE_WEEK));
	
	

	// Repeat Invoices
	$Year = date('Y',NOW);
	$Day = date('d',NOW);
	$Month = date('n',NOW);
	$Next_Month = date('n',NOW + (date('t',NOW) * ONE_DAY));


	//look up Repeats for this month
	$query = "SELECT RI.Invoice_ID,Reminders,RI.Day,RI.Month FROM ". PHPA_REPEAT_INVOICE_TABLE ." RI WHERE Month IN('*','$Month','$Next_Month') AND Active = 'yes'";
	$result = $db_reader->Query($query);
	while(list($Invoice_ID,$Reminders,$Repeat_Day,$Repeat_Month) = $result->FetchRow())
	{
		if($Repeat_Month == '*')
		{
			$Repeat_Month = $Month;
		}

		if($Repeat_Day == '*')
		{
			$Repeat_Day = $Day;
		}

		$query = "SELECT * FROM ". PHPA_REPEAT_INVOICE_LOG_TABLE ." RPL WHERE RPL.Repeat_Invoice_ID = $Invoice_ID";
		$check_result = $db_reader->query($query);

		//let's check we haven't created one already for this month (or later i.e. in advance for next year)
		$MYSQL_START_MONTH = mysqlDate(mktime(0,0,0,$Repeat_Month,1,$Year));
		$query = "SELECT I.Date FROM ". PHPA_REPEAT_INVOICE_LOG_TABLE ." RPL, ". PHPA_INVOICE_TABLE ." I WHERE RPL.Repeat_Invoice_ID = $Invoice_ID AND I.ID = RPL.Invoice_ID AND (I.Date >= '$MYSQL_START_MONTH')";
		$Count = $db_reader->queryRow($query);
		if(!strstr($Count[0],'-'))
		{
			$i++;
			$Date = "$Year-$Repeat_Month-$Repeat_Day";
			createRepeatInvoice($Invoice_ID,$Date);
		}
	}


	$MYSQL_NOW = mysqlDate(NOW);
	$MYSQL_SEND_DATE = mysqlDate(SEND_DATE);
	$MYSQL_WARNING_DATE1 = mysqlDate(FIRST_WARNING);
	$MYSQL_WARNING_DATE2 = mysqlDate(SECOND_WARNING);

	//look up Invoices due to go in the next 4 weeks
	$query = "SELECT I.ID,IF(I.Date < '$MYSQL_SEND_DATE','SEND',IF(I.Date IN('$MYSQL_WARNING_DATE1','$MYSQL_WARNING_DATE2'),'WARNING','NO YET')) AS Type 
	FROM ". PHPA_INVOICE_TABLE ." I LEFT JOIN ". PHPA_INVOICE_MAIL_LOOKUP_TABLE ." IML ON(IML.Invoice_ID = I.ID)
	WHERE I.Date >= '$MYSQL_NOW' AND I.Date < '$MYSQL_WARNING_DATE2' AND I.Reminders = 'yes' AND IML.Invoice_ID IS NULL";
	$result = $db_reader->Query($query);
	if($result->NumRows() > 0)
	{

		include(INCLUDE_PATH .'/includes/PDF_invoice.php');
		while(list($Invoice_ID,$Type) = $result->FetchRow())
		{

			//get details
			$query = "SELECT I.Reference AS ID,C.Company_Name,I.Client_ID,I.Value,DATE_FORMAT(I.Date,'". MYSQL_DATE_FORMAT ."') AS Date,I.Description,I.Invoice_Address,C.User_ID
			FROM ". PHPA_INVOICE_TABLE ." I LEFT JOIN ". PHPA_CLIENT_TABLE ." C ON(I.Client_ID = C.ID)
			WHERE I.ID = $Invoice_ID";
			$invoice_result = $db_reader->query($query);
			$row = $invoice_result->fetchRow(MDB2_FETCHMODE_ASSOC);

			if($Type == 'SEND')
			{
				$Invoice = new PDF_Invoice();
				$Invoice->Open();
				$Invoice->Add_Details($row);

				//have to set users prefs
				$prefs = array('LETTER_FOOTER','LETTER_FONT','INVOICE_THANKYOU');
				foreach($prefs as $pref)
				{
					$row[$pref] = $db_reader->queryOne("SELECT Value FROM ". PHPA_PREFERENCES_TABLE ." WHERE User_ID = {$row['User_ID']} AND Preference = '$pref'");
				}
				$Invoice->Footer = stripcslashes($row['LETTER_FOOTER']);
				$Invoice->Thank_You = stripcslashes($row['INVOICE_THANKYOU']);

				$row['LETTERHEAD_IMAGE'] = INCLUDE_PATH ."/users/{$row['User_ID']}/letterhead.png";

				$Invoice->Header_Image = array('Filename'=>$row['LETTERHEAD_IMAGE'],'X'=>5,'Y'=>5,'Width'=>LETTERHEAD_WIDTH,'Height'=>LETTERHEAD_HEIGHT);
				$Invoice->SetFont($row['LETTER_FONT'],'',12);


				$Invoice->Print_Invoice();
				$file = INCLUDE_PATH .'/temp/invoice.pdf';
				$Invoice->Output($file);
						sendClientInvoice($Invoice_ID,$file);
			}
			elseif($Type == 'NOT YET')
			{
			}
			elseif($Type == 'WARNING')
			{
				$message = "This email is notice that the following details are due to be sent after $MYSQL_SEND_DATE\n\n";
				echo "{$row['Company_Name']} {$row['Reference']} $Type\n";
				foreach($row as $key=>$value)
				{
					$message .= "\n$key: $value";
				}
				$subject = "Advanced notice of invoice to be sent";
				//user
								mailUser($row['User_ID'],$subject,$message);
			}
				echo "{$row['Company_Name']} {$row['Reference']} $Type\n";
		}	
	}

	//look up Repeats due to go out next week

	//first find users with invoices with reminders
	$query = "SELECT User_ID 
	FROM ". PHPA_INVOICE_TABLE ." I, ". PHPA_CLIENT_TABLE ." C
	WHERE I.Reminders = 'yes' AND I.Client_ID = C.ID
	GROUP BY C.User_ID";
	$result = $db_reader->query($query);
	while(list($User_ID) = $result->FetchRow())
	{
		$Users[$User_ID] = $User_ID;
	}

	foreach($Users as $User_ID)
	{
		//make sure reminder dates are set
		checkReminders();	

		//get reminder days
		$query = "SELECT Value FROM ". PHPA_PREFERENCES_TABLE ." WHERE User_ID = $User_ID AND Preference = 'DAYS_TO_SEND_FIRST_REMINDER'";
		$days['first_invoice_reminder'] = $db_reader->queryOne($query);
		$query = "SELECT Value FROM ". PHPA_PREFERENCES_TABLE ." WHERE User_ID = $User_ID AND Preference = 'DAYS_TO_SEND_SECOND_REMINDER'";
		$days['second_invoice_reminder'] = $db_reader->queryOne($query);
		$query = "SELECT Value FROM ". PHPA_PREFERENCES_TABLE ." WHERE User_ID = $User_ID AND Preference = 'DAYS_TO_SEND_FINAL_REMINDER'";
		$days['final_invoice_reminder'] = $db_reader->queryOne($query);

		unset($Dates);
		foreach($days as $day)
		{
			$Dates[] = '\''. mysqlDate(NOW - ($day * ONE_DAY)) .'\'';
		}

		$Dates = implode(",",$Dates);

		$query = "SELECT I.ID, SUM(IF (IP.Value, IP.Value, 0)) AS Paid, I.Value - SUM(IF(IP.Value, IP.Value, 0)) AS Balance, TO_DAYS(NOW()) - TO_DAYS(I.DATE) AS Overdue
		FROM ". PHPA_INVOICE_TABLE ." I LEFT JOIN ". PHPA_INVOICE_PAYMENT_TABLE ." IP ON ( IP.Invoice_ID = I.ID ) , ". PHPA_CLIENT_TABLE ." C
		WHERE C.User_ID = $User_ID AND I.Date IN($Dates) AND I.Reminders = 'yes' AND I.Client_ID = C.ID
		GROUP BY I.ID
		HAVING Balance >0";
		$result = $db_reader->query($query);
		while($row = $result->FetchRow(MDB2_FETCHMODE_ASSOC))
		{
			//check which amount overdue by and send corresponding reminder
			reset($days);
			foreach($days as $key => $value)
			{
				if($row['Overdue'] == $value)
				{
					sendClientInvoice($row['ID'],false,$key);
				}
			}
		}
	}
?>
