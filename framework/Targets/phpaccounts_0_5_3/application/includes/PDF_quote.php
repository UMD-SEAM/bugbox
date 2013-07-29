<?php
require_once('PDF_base.php');

class PDF_Quote extends PDF_base 
{
	//Private variables

	//Numeric Data
	var $Details = array();
	var $Quote_Count = 0;

	//Text
	var $Terms = QUOTE_TERMS; 
	//\n V.A.T.: All prices exclude VAT at the prevailing rate.
	var $Acceptance = 'To confirm acceptance of this proposal and that you have read and agreed to our terms and conditions, please sign and date where shown below.';
	var $Acceptance_Signature = 
		"\nSigned................................\n
		Print.................................\n
		Date..................................";


	//Posistion Data in mm
	var $Date_Coordinates = array('X'=>150,'Y'=>45,'Width'=>50,'Height'=>7);
	var $Quote_Address_Coordinates = array('X'=>20,'Y'=>50,'Width'=>70,'Height'=>6);
	var $Title_Coordinates = array('X'=>0,'Y'=>105,'Width'=>210,'Height'=>12); //this will be centred
	var $Details_Coordinates = array('X'=>25,'Y'=>120,'Width'=>160,'Height'=>3);
	var $Value_Coordinates = array('X'=>150,'Y'=>'variable','Width'=>35,'Height'=>4);
	var $Terms_Coordinates = array('X'=>25,'Y'=>'variable','Width'=>160,'Height'=>4);//this will be centred
	var $Client_Acceptance_Coordinates = array('X'=>25,'Y'=>'variable','Width'=>70,'Height'=>4);//this will be centred
	var $Our_Acceptance_Coordinates = array('X'=>135,'Y'=>'variable','Width'=>70,'Height'=>4);//this will be centred

	//create quote
	function Print_Quote()
	{
		$this->AddPage();
		//header image
		$this->Print_Image($this->Header_Image);

		//MrKirkland Address
		$this->SetFont('','',8);
		$this->SetXY($this->Header_Coordinates['X'],$this->Header_Coordinates['Y']);
		$this->MultiCell($this->Header_Coordinates['Width'],$this->Header_Coordinates['Height'],$this->Header,0,L);
		$this->SetFont('','',12);

		//Quote Address
		$address = $this->Details['Company_Name'] ."\n". $this->Details['Quote_Address'];

		$this->SetXY($this->Quote_Address_Coordinates['X'],$this->Quote_Address_Coordinates['Y']);
		$this->MultiCell($this->Quote_Address_Coordinates['Width'],$this->Quote_Address_Coordinates['Height'],$address);


		//Date + Ref
		$date_ref = $this->Details['Date'] . "\n". 'Ref: Quote '. $this->Details['ID'];
		$this->SetXY($this->Date_Coordinates['X'],$this->Date_Coordinates['Y']);
		$this->MultiCell($this->Date_Coordinates['Width'],$this->Date_Coordinates['Height'],$date_ref,0,'R');


		//Title	
		$Title = $this->Details['Title'];
		$this->SetFont('','B',16);
		$this->SetXY($this->Title_Coordinates['X'],$this->Title_Coordinates['Y']);
		$this->Cell($this->Title_Coordinates['Width'],$this->Title_Coordinates['Height'],$Title,0,0,C);
		$this->SetFont('','',12);


		//Display table of Activities

		//description header
		$this->SetFillColor(180,180,190);
		$this->SetLineWidth(.3);
		$this->SetTextColor(250);
		$this->SetXY($this->Details_Coordinates['X'],$this->Details_Coordinates['Y']);
		$this->Cell($this->Details_Coordinates['Width'],6,'Proposal',0,0,'L',1);
		$this->SetXY($this->Details_Coordinates['X'],$this->Details_Coordinates['Y']+6);

		//description
		$this->SetFillColor(250,250,230);
		$this->SetTextColor(120);
		$this->SetFont('','',8);
		$this->SetX($this->Details_Coordinates['X']);
		$this->MultiCell($this->Details_Coordinates['Width'],$this->Details_Coordinates['Height'],$this->Details['Description'],1,'L',1);

		//value header;
		$this->SetFillColor(180,180,190);
		$this->SetTextColor(255);
		$this->SetX($this->Value_Coordinates['X']);
		$this->Cell(($this->Value_Coordinates['Width']/2),$this->Value_Coordinates['Height'],'Total:',0,0,'R',1);

		//value
		$this->SetFillColor(250,250,200);
		$this->SetTextColor(0);
		$this->Cell(($this->Value_Coordinates['Width']/2),$this->Value_Coordinates['Height'],ASCII_CURRENCY_SYMBOL.$this->Details['Value'],1,0,'L',1);

		//Terms
		$this->SetFont('','',8);
		$this->ln();
		$this->SetX($this->Terms_Coordinates['X']);
		$this->MultiCell($this->Terms_Coordinates['Width'],$this->Terms_Coordinates['Height'],$this->Terms,0,'L');

		//Acceptance notice
		$this->SetFont('','B',10);
		$this->ln();
		$this->SetX($this->Terms_Coordinates['X']);
		$this->MultiCell($this->Terms_Coordinates['Width'],$this->Terms_Coordinates['Height'],$this->Acceptance,0,'C');

		//Acceptance signature bit

		$Client_Acceptance = 'Accepted on behalf of '. $this->Details['Company_Name'] ."\n". $this->Acceptance_Signature;
		$Our_Acceptance = "Accepted on behalf of Mr Kirkland \n". $this->Acceptance_Signature;

		$this->SetFont('','',8);
		$this->ln();
		$this->Our_Acceptance_Coordinates['Y'] = $this->GetY();
		$this->SetX($this->Client_Acceptance_Coordinates['X']);
		$this->MultiCell($this->Client_Acceptance_Coordinates['Width'],$this->Client_Acceptance_Coordinates['Height'],$Client_Acceptance,0,'L');

		$this->SetXY($this->Our_Acceptance_Coordinates['X'],$this->Our_Acceptance_Coordinates['Y']);
		$this->MultiCell($this->Our_Acceptance_Coordinates['Width'],$this->Our_Acceptance_Coordinates['Height'],$Our_Acceptance,0,'L');


		//footer
		$this->SetFont('','',7);
		$this->SetTextColor(120);
		$this->SetXY($this->Footer_Coordinates['X'],$this->Footer_Coordinates['Y']);
		$this->Cell($this->Footer_Coordinates['Width'],$this->Footer_Coordinates['Height'],$this->Footer,'T',0,'C');
	}



}
?>
