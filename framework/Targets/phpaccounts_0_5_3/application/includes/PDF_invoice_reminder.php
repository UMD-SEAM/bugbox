<?php
require_once('PDF_base.php');

class PDF_Invoice_Reminder extends PDF_base 
{
	//Private variables

	//Numeric Data
	var $Details = array();
	var $Invoice_Count = 0;


	//Posistion Data in mm
	var $Date_Coordinates = array('X'=>150,'Y'=>45,'Width'=>50,'Height'=>7);
	var $Invoice_Address_Coordinates = array('X'=>20,'Y'=>50,'Width'=>70,'Height'=>6);
	var $Title_Coordinates = array('X'=>0,'Y'=>105,'Width'=>210,'Height'=>12); //this will be centred
	var $Details_Coordinates = array('X'=>25,'Y'=>120,'Width'=>160,'Height'=>8);
	var $Value_Coordinates = array('X'=>150,'Y'=>'variable','Width'=>35,'Height'=>6);

	//create invoice
	function Print_Invoice()
	{
		$this->AddPage();
		//header image
		$this->Print_Image($this->Header_Image);

		//Address
		$this->SetFont('','',8);
		$this->SetXY($this->Header_Coordinates['X'],$this->Header_Coordinates['Y']);
		$this->MultiCell($this->Header_Coordinates['Width'],$this->Header_Coordinates['Height'],$this->Header,0,L);
		$this->SetFont('','',12);

		//Invoice Address
		$address = $this->Details['Company_Name'] ."\n". $this->Details['Invoice_Address'] ."\n\nDear ". $this->Details['Contact_Name'] .",";

		$this->SetXY($this->Invoice_Address_Coordinates['X'],$this->Invoice_Address_Coordinates['Y']);
		$this->MultiCell($this->Invoice_Address_Coordinates['Width'],$this->Invoice_Address_Coordinates['Height'],$address);


		//Date + Ref
		$date_ref =  $this->Formatted_Date . "\n". 'Ref: Invoice '. $this->Details['ID'];
		$this->SetXY($this->Date_Coordinates['X'],$this->Date_Coordinates['Y']);
		$this->MultiCell($this->Date_Coordinates['Width'],$this->Date_Coordinates['Height'],$date_ref,0,'R');


		//Title	
		$this->SetTextColor(255,0,0);
		$Title = 'Re: Invoice '. $this->Details['ID'] .' Dated '. $this->Details['Date'];		
		$this->SetFont('','B',16);
		$this->SetXY($this->Title_Coordinates['X'],$this->Title_Coordinates['Y']);
		$this->Cell($this->Title_Coordinates['Width'],$this->Title_Coordinates['Height'],$Title,0,0,C);
		$this->SetFont('','',12);


		//description
		$Description = 'On checking our records, it appears that we have not yet received payment for the invoice we sent you dated '. $this->Details['Date'];
		$Description .= ' for '. ASCII_CURRENCY_SYMBOL . $this->Details['Value'] .'.';
		$Description .= 'Please could you ensure we receive payment for the amount due by return.';

		$Description .= "\n\nIf you have sent payment in the last few days, please accept our apologies for this unneseccary reminder.";
		$Description .= "\n\nYours Sincerely,\n\n\n\tChris Kirkland";
		$this->SetTextColor(0);
		$this->SetXY($this->Details_Coordinates['X'],$this->Details_Coordinates['Y']);
		$this->MultiCell($this->Details_Coordinates['Width'],$this->Details_Coordinates['Height'],$Description,0,'L');

		//footer
		$this->SetFont('','',7);
		$this->SetTextColor(120);
		$this->SetXY($this->Footer_Coordinates['X'],$this->Footer_Coordinates['Y']);
		$this->Cell($this->Footer_Coordinates['Width'],$this->Footer_Coordinates['Height'],$this->Footer,'T',0,'C');

	}
}
?>
