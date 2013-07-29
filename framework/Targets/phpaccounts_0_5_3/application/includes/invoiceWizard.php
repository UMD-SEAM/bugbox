<?php
class invoiceWizard
{
	var $Detail = 'full';
	var $Client_ID = false;
	var $Project_ID = false;
	var $Start_Month = false;
	var $Start_Year = false;
	var $End_Month = false;
	var $End_Year = false;

	function SetClient_ID($value)
	{
		if(!is_numeric($value))
		{
			trigger_error("Client_ID is not an Integer: $value");
		}
		
		$this->Client_ID = $value;
	}

	function SetProject($value,$Complete_Project)
	{
		$this->Project_ID = $value;
		$this->Complete_Project = $Complete_Project;
	}

	function SetDetail($value)
	{
		$this->Detail = $value;
	}

	function SetDates($Dates)
	{
		foreach($Dates as $key => $value)
		{
		 setType($value,'integer');
			if(!is_numeric($value))
			{
			trigger_error("$key is not an Integer: $value");
			}
			else
			{
				$this->$key = $value;
			}			
		}
		$this->Start_Timestamp = mktime(0,0,0,$this->Start_Month,1,$this->Start_Year);
		if($this->Start_Month == 12)
		{
		$this->End_Timestamp = mktime(0,0,0,1,1,$this->Start_Year + 1);
		}
		else
		{
		$this->End_Timestamp = mktime(0,0,0,$this->Start_Month + 1,1,$this->Start_Year);
		}

		//Format for MySQL
		$this->MySQL_Start_Timestamp = mysqlDatetime($this->Start_Timestamp);
		$this->MySQL_End_Timestamp = mysqlDatetime($this->End_Timestamp);

		//Format for invoice
		$this->Formatted_Date = date('M Y',$this->Start_Timestamp);
	}

	function createInvoice()
	{
		global $db_reader;

		//get details from timesheets

		//Project conditions
		if($this->Project_ID)
		{
			$WHERE = " AND PT.Project_ID =  $this->Project_ID AND PT.Timesheet_ID = T.ID";
			$FROM = ",". PHPA_PROJECT_TIMESHEET_TABLE ." PT";
			if(!$this->Complete_Project)
			{
			$WHERE .= " AND Timestamp >= '". $this->MySQL_Start_Timestamp . "' AND Timestamp <= '". $this->MySQL_End_Timestamp ."'";
			}
		}
		else
		{
			$WHERE .= " AND Timestamp >= '". $this->MySQL_Start_Timestamp . "' AND Timestamp <= '". $this->MySQL_End_Timestamp ."'";
		}
		
		//Date conditions - ignore if complete project
		if(!$this->Project || !$this->Complete_Project)
		{
		}
		
		$query = "SELECT Description,TIME_TO_SEC(Time),Value FROM ". PHPA_TIMESHEET_TABLE ." T $FROM
		WHERE Client_ID = $this->Client_ID $WHERE";
		
		$result = $db_reader->query($query);
		while(list($currentDescription,$currentTime,$currentValue) = $result->FetchRow())
		{
			$this->Description .= "$currentDescription,\t\t\t\t ". ASCII_CURRENCY_SYMBOL."$currentValue (". formatTime($currentTime,'short') .")\n";
			$this->Value += $currentValue;
			$this->Time += $currentTime;
		}
		$this->Description .= "Total Time: ". formatTime($this->Time,'short');

		//get invoice address
		$query = "SELECT Address1,Address2,City,Region,Postcode FROM ". PHPA_CLIENT_TABLE ." WHERE ID = $this->Client_ID";
		$result = $db_reader->query($query);
		$row = $result->fetchRow();
		$this->Invoice_Address = implode("\n",$row);

		//set date
		$this->Date = date(PHP_DATE_FORMAT);
	}	

	function getDescription()
	{
		global $db_reader;
		if($this->Project_ID)
		{
			$query = "SELECT Title FROM ". PHPA_PROJECT_TABLE ." WHERE ID = $this->Project_ID";
			$Title = $db_reader->queryOne($query);
			$this->Title = "Work on $Title";
			if(!$this->Complete_Project)
			{
				$this->Title .= " during $this->Formatted_Date";
			}
		}
		else
		{
			$this->Title = "Work during $this->Formatted_Date";
		}
		if($this->Detail == 'full')
		{
			$count = strlen($this->Title);
			for($i=0;$i<$count;$i++) $underline .= '=';
			return $this->Title ."\n$underline\n". $this->Description;
		}
		else
		{
			return $this->Title;
		}
		
	}

}



?>
