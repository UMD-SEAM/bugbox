<?php
require_once('PDF_base.php');

class PDF_Invoice extends PDF_base
{
	//Private variables

	//Numeric Data
	var $Details = array();
	var $Invoice_Count = 0;

	//Text
	var $Thank_You = INVOICE_THANKYOU;

	//Posistion Data in mm
	var $Date_Coordinates = array('X'=>150,'Y'=>45,'Width'=>50,'Height'=>7);
	var $Invoice_Address_Coordinates = array('X'=>20,'Y'=>50,'Width'=>70,'Height'=>6);
	var $Title_Coordinates = array('X'=>0,'Y'=>105,'Width'=>210,'Height'=>12); //this will be centred
	var $Details_Coordinates = array('X'=>25,'Y'=>120,'Width'=>160,'Height'=>4);
	var $Value_Coordinates = array('X'=>150,'Y'=>'variable','Width'=>35,'Height'=>6);
	var $Thank_You_Coordinates = array('X'=>0,'Y'=>'variable','Width'=>210,'Height'=>8);//this will be centred

	//create invoice
	function Print_Invoice()
	{
		$this->AddPage();
		//header image
		$this->Print_Image($this->Header_Image);


		//MrKirkland Address
		$this->SetFont('','',8);
		$this->SetXY($this->Header_Coordinates['X'],$this->Header_Coordinates['Y']);
		$this->MultiCell($this->Header_Coordinates['Width'],$this->Header_Coordinates['Height'],$this->Header,0,L);
		$this->SetFont('','',12);

		//Invoice Address
		$address = $this->Details['Company_Name'] ."\n". $this->Details['Invoice_Address'];

		$this->SetXY($this->Invoice_Address_Coordinates['X'],$this->Invoice_Address_Coordinates['Y']);
		$this->MultiCell($this->Invoice_Address_Coordinates['Width'],$this->Invoice_Address_Coordinates['Height'],$address);


		//Date + Ref
		$date_ref = $this->Details['Date'] . "\n". 'Ref: Invoice '. $this->Details['ID'];
		$this->SetXY($this->Date_Coordinates['X'],$this->Date_Coordinates['Y']);
		$this->MultiCell($this->Date_Coordinates['Width'],$this->Date_Coordinates['Height'],$date_ref,0,'R');


		//Title	
		$Title = 'Invoice';		
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
		$this->Cell($this->Details_Coordinates['Width'],6,'Description',0,0,'L',1);
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

		//thank you
		$this->SetFont('','',12);
		$this->ln();
		$this->SetX($this->Thankyou_Coordinates['X']);
		$this->MultiCell($this->Details_Coordinates['Width'],$this->Details_Coordinates['Height'],$this->Thank_You,0,'C');

		//footer
		$this->SetFont('','',7);
		$this->SetTextColor(120);
		$this->SetXY($this->Footer_Coordinates['X'],$this->Footer_Coordinates['Y']);
		$this->Cell($this->Footer_Coordinates['Width'],$this->Footer_Coordinates['Height'],$this->Footer,'T',0,'C');
		
		}


	}
?>
