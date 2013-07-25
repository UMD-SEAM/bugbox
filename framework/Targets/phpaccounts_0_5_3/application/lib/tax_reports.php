<?
if($_GET['csv'])
{
	$tmpl->ReadTemplatesFromFile('csv_tax_reports.tmpl.html');
}
else
{
	$tmpl->ReadTemplatesFromFile('tax_reports.tmpl.html');
	$tmpl->AddThisBeforeTemplate('menu');
}
//summary
$year = getVariable('year');

if(!$year)
{
	$year = date('Y') - 1;
}


$Start_Timestamp = mktime(0,0,0,TAX_YEAR_START_MONTH,TAX_YEAR_START_DAY,$year);
$End_Timestamp = mktime(0,0,0,TAX_YEAR_START_MONTH,TAX_YEAR_START_DAY,$year+1);

//MYSQL FORMATED
$MySQL_Start_Timestamp = mysqlDatetime($Start_Timestamp);
$MySQL_End_Timestamp = mysqlDatetime($End_Timestamp);

$MySQL_Start_Date = mysqlDate($Start_Timestamp);
$MySQL_End_Date = mysqlDate($End_Timestamp);


//get Payments
$query = "SELECT I.ID AS Invoice_ID,I.Client_ID,I.Description,IP.Value AS Value_IN,UNIX_TIMESTAMP(IP.Timestamp) AS TIMESTAMP 
FROM ". PHPA_INVOICE_TABLE ." I LEFT JOIN ". PHPA_CLIENT_TABLE ." C ON(I.Client_ID = C.ID), ". PHPA_INVOICE_PAYMENT_TABLE ." IP
WHERE C.User_ID = $User_ID AND IP.Timestamp > '$MySQL_Start_Timestamp' AND IP.Timestamp < '$MySQL_End_Timestamp' AND IP.Invoice_ID = I.ID";


$result = $db_reader->query($query);
while($row = $result->fetchRow(MDB2_FETCHMODE_ASSOC))
{
	$this_result = array('Invoice_ID'=>$row['Invoice_ID'],'Outgoing_ID'=>'','Client'=>$Client_array[$row['Client_ID']],'Vendor'=>'','Description'=>$row['Description'],'Value_IN'=>$row['Value_IN'],'Capital_Expenditure'=>$row['Capital_Expenditure'],'Value_OUT'=>'','Outgoing_Type'=>$row['Outgoing_Type']);
	$Tax_Report[$row['TIMESTAMP']]['OUT'][] = $this_result;
}

//get Outgoings
$query = "SELECT O.ID AS Outgoing_ID,O.Vendor_ID,O.Description,IF(OT.Outgoing_Type = 'Capital Expenditure' OR OT.Outgoing_Type = 'IT Capital Expenditure',NULL,OP.Value) AS Value_OUT,IF(OT.Outgoing_Type = 'Capital Expenditure' OR OT.Outgoing_Type = 'IT Capital Expenditure',OP.Value,NULL) AS Capital_Expenditure,UNIX_TIMESTAMP(OP.Timestamp) AS TIMESTAMP,OT.Outgoing_Type
FROM ". PHPA_OUTGOING_TABLE ." O LEFT JOIN ". PHPA_VENDOR_TABLE ." V ON (O.Vendor_ID = V.ID), ". PHPA_OUTGOING_PAYMENT_TABLE ." OP,". PHPA_OUTGOING_TYPE_TABLE ." OT
WHERE V.User_ID = $User_ID AND OP.Timestamp > '$MySQL_Start_Timestamp' AND OP.Timestamp < '$MySQL_End_Timestamp' AND OP.Outgoing_ID = O.ID AND O.Outgoing_Type_ID = OT.ID";
$result = $db_reader->query($query);
while($row = $result->fetchRow(MDB2_FETCHMODE_ASSOC))
{
	$this_result = array('Invoice_ID'=>'','Outgoing_ID'=>$row['Outgoing_ID'],'Vendor'=>$Vendor_array[$row['Vendor_ID']],'Client'=>'','Description'=>$row['Description'],'Value_OUT'=>$row['Value_OUT'],'Capital_Expenditure'=>$row['Capital_Expenditure'],'Value_IN'=>'','Outgoing_Type'=>$row['Outgoing_Type']);
	$Tax_Report[$row['TIMESTAMP']]['OUT'][] = $this_result;
}

if($Tax_Report)
{
	ksort($Tax_Report);
	foreach($Tax_Report as $Timestamp => $array)
	{
		$DATE = date('Y-m-d',$Timestamp);
		if($array['OUT'])
		{
			foreach($array['OUT'] as $OUT)	
			{
				if($i) $i=0;
				else $i=1;
				$tmpl->AddVar('tax_report_results','I',$i);

				foreach($OUT as $key => $value)
				{
					$tmpl->AddVar('tax_report_results',$key,$value);
				}
				$tmpl->AddVar('tax_report_results','DATE',$DATE);
				$tmpl->ParseTemplate('tax_report_results','a');
				$TOTAL_OUT += $OUT['Value_OUT'];
				$TOTAL_Capital_Expenditure += $OUT['Capital_Expenditure'];
				$TOTAL_IN += $OUT['Value_IN'];
				$OUTGOING_TYPE_TOTAL[$OUT['Outgoing_Type']] += ($OUT['Value_OUT'] + $OUT['Capital_Expenditure']);
			}
		}

		if($array['IN'])
		{
			foreach($array['IN'] as $IN)	
			{
				if($i) $i=0;
				else $i=1;
				$tmpl->AddVar('tax_report_results','I',$i);

				foreach($IN as $key => $value)
				{
					$tmpl->AddVar('tax_report_results',$key,$value);
				}
				$tmpl->AddVar('tax_report_results','DATE',$DATE);
				$tmpl->ParseTemplate('tax_report_results','a');
			}
		}
	}
}
$tmpl->AddVar('menu','THIS_YEAR',$year);
$tmpl->AddVar('menu','NEXT_YEAR',$year + 1);
$tmpl->AddVar('tax_report','LAST_YEAR',$year - 1);
$tmpl->AddVar('tax_report','THIS_YEAR',$year);
$tmpl->AddVar('tax_report','NEXT_YEAR',$year + 1);
$tmpl->AddVar('tax_report','NEXT_NEXT_YEAR',$year + 2);
$tmpl->AddVar('tax_report','TOTAL_IN',$TOTAL_IN);
$tmpl->AddVar('tax_report','TOTAL_OUT',$TOTAL_OUT);
$tmpl->AddVar('tax_report','TOTAL_CAPITAL_EXPENDITURE',$TOTAL_Capital_Expenditure);

//outgoings grouped by type
if($OUTGOING_TYPE_TOTAL)
{
	ksort($OUTGOING_TYPE_TOTAL);
	foreach($OUTGOING_TYPE_TOTAL as $OUTGOING_TYPE => $VALUE)
	{
		if($i) $i=0;
		else $i=1;
		$tmpl->AddVar('outgoing_types','I',$i);
		$tmpl->AddVar('outgoing_types','OUTGOING_TYPE',$OUTGOING_TYPE);
		$tmpl->AddVar('outgoing_types','VALUE',$VALUE);
		$tmpl->ParseTemplate('outgoing_types','a');
	}
	$tmpl->AddVar('tax_report','OUTGOING_TYPE_TOTAL',array_sum($OUTGOING_TYPE_TOTAL));
}
$tmpl->AddThisTemplate('tax_report');

?>
