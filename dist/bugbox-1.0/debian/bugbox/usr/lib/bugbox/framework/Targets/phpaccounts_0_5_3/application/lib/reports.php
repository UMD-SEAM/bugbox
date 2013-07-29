<?
$tmpl->ReadTemplatesFromFile('reports.tmpl.html');
//get first day of week
//Start on monday standard is 0 for sunday.
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

$Type = getVariable('Type');
if(!$Type)
{
	$Type = 'cashflow';
}

switch($Type)
{
	case 'timesheet':
	case 'invoice':
	case 'repeat_invoice':
	case 'forecast_repeat_invoice':
	case 'payment':
	
	{
		$tmpl->AddContent(timesheetGraph($Type,$Start_Timestamp,$End_Timestamp,$Period,false,"page=reports&Type=$Type"));
		break;
	}
	case 'unpaid_invoices':
	
	{
		$tmpl->AddContent(viewUnpaidInvoices());
		break;
	}


	case 'outgoings':
	{
		$tmpl->AddContent(outgoingsGraph($Type,$Start_Timestamp,$End_Timestamp,$Period,false,'page=reports&Type=outgoings'));
		break;
	}
	
	case 'cashflow':
	{
		$tmpl->AddContent(cashflowGraph($Type,$Start_Timestamp,$End_Timestamp,$Period,false,'page=reports&Type=cashflow'));
		break;
	}
}

//keep menu at top of this page
$tmpl->AddThisBeforeTemplate('summary_menu');
?>
