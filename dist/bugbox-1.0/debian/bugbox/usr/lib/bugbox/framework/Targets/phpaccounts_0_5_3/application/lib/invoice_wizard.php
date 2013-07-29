<?php
$tmpl->ReadTemplatesFromFile('invoice_wizard.tmpl.html');

$Client_ID = getVariable('Client_ID');

if($_POST['submit'])
{
	//instatiate invoiceWizard class and get data for new invoice
	$invoice = new invoiceWizard();

	$invoice->SetClient_ID($_POST['Client_ID']);
	$invoice->SetProject($_POST['Project_ID'],$_POST['Complete_Project']);
	$invoice->SetDetail($_POST['Detail']);
	$Dates = array('Start_Month'=>$_POST['Date_month'],'Start_Year'=>$_POST['Date_year']);
	$invoice->SetDates($Dates);
	$invoice->CreateInvoice();

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
		}
	}

	$Invoice_ID = getNextID(PHPA_INVOICE_TABLE);
	$query = "INSERT INTO ". PHPA_INVOICE_TABLE ." (ID,Client_ID,Reference,Value,Date,Invoice_Address,Description)
		VALUES($Invoice_ID,".  $invoice->Client_ID .",'". $Ref ."','".  $invoice->Value ."','".  $invoice->Date .";','".  $invoice->Invoice_Address ."','".  addslashes($invoice->GetDescription()) ."')";


	$db_writer->exec($query);

	//if it's a project invoice, create an table entry
	if($invoice->Project_ID)
	{
		$query = "INSERT INTO ". PHPA_PROJECT_INVOICE_TABLE ." (Project_ID,Invoice_ID)
			VALUES (". $invoice->Project_ID .",$Invoice_ID)";
		$db_writer->exec($query);
	}

	$action = 'edit_invoice';
	//set Invoice ID for getVariable
	$_GET['Invoice_ID'] = $Invoice_ID;
}
elseif($Client_ID)
{
	//look up projects
	$query = "SELECT ID,Title FROM ". PHPA_PROJECT_TABLE ." WHERE Client_ID = $Client_ID";
	$result = $db_reader->query($query);
	while(list($ID,$Title) = $result->FetchRow())
	{
		$Project_array[$ID] = $Title;		
	}
	$tmpl->AddVar('form','PROJECT_SELECT_LIST',selectlist('-- choose','Project_ID',$Project_array,0));

	//month selector
	$tmpl->AddVar('form','DATE_SELECT_LIST',dateSelector('Date',$row['Date'],false));
	$tmpl->AddVar('form','CLIENT_ID',$Client_ID);
	$tmpl->SetAttribute('form','visibility','show');
	$tmpl->AddThisTemplate('invoice_wizard');
}
else
{
	$tmpl->AddVar('choose_client','CLIENT_SELECT_LIST',selectlist2('Client_ID',$Client_array,$row['Client_ID']));
	$tmpl->SetAttribute('choose_client','visibility','show');
	$tmpl->AddThisTemplate('invoice_wizard');
}
?>
