<?php
require_once('PDF_base.php');

class PDF_Statement extends PDF_base 
{
	//Private variables

	//Numeric Data
	var $Details = array();
	var $Statement_Count = 0;

	//Posistion Data in mm
	var $Date_Coordinates = array('X'=>150,'Y'=>45,'Width'=>50,'Height'=>7);
	var $Statement_Address_Coordinates = array('X'=>20,'Y'=>50,'Width'=>70,'Height'=>6);
	var $Title_Coordinates = array('X'=>0,'Y'=>105,'Width'=>210,'Height'=>12); //this will be centred
	var $Details_Coordinates = array('X'=>25,'Y'=>120,'Width'=>160,'Height'=>8);
	var $Value_Coordinates = array('X'=>150,'Y'=>'variable','Width'=>35,'Height'=>6);

	//input data
	function Add_Details($data,$Start_Timestamp)
	{
		foreach($data as $Timestamp => $array)
		{	
			$BALANCE -= $array['Invoice'];
			$BALANCE += $array['Payment'];

			if($Timestamp >= $Start_Timestamp)
			{
				//first row
				if(!$this->Details)
				{
					$row = array(
							date(PHP_DATE_FORMAT,$Start_Timestamp),
							'OPENING BALANCE',
							false,
							false,
							$BALANCE + $array['Invoice'] - $array['Payment']
						    );
					$this->Details[] = $row;
				}

				$row = array(
						date(PHP_DATE_FORMAT,$Timestamp),
						$array['Reference'],
						$array['Invoice'],
						$array['Payment'],
						$BALANCE
					    );
				$this->Details[] = $row;
			}
		}
		$this->Balance = $BALANCE;	
		$this->Start_Timestamp = $Start_Timestamp;
		$this->header = array('Date','Invoice Ref','Invoice','Payment','balance');
	}
	//input data
	function Add_Client_Details($data)
	{
		$this->Client_Details = $data;
		$Statement_Address_Fields = array('Address1','Address2','City','Region','Postcode');
		foreach($Statement_Address_Fields as $Field)
		{
			if($this->Client_Details[$Field])
			{
				$this->Client_Details['Statement_Address'] = $this->Client_Details[$Field]. "\n";
			}
		}
	}

	//create invoice
	function Print_Statement()
	{
		$this->AddPage();
		//header image
		$this->Print_Image($this->Header_Image);

		//MrKirkland Address
		$this->SetFont('','',8);
		$this->SetXY($this->Header_Coordinates['X'],$this->Header_Coordinates['Y']);
		$this->MultiCell($this->Header_Coordinates['Width'],$this->Header_Coordinates['Height'],$this->Header,0,L);
		$this->SetFont('','',12);

		//Statement Address
		$address = $this->Client_Details['Company_Name'] ."\n". $this->Client_Details['Statement_Address'];

		$this->SetXY($this->Statement_Address_Coordinates['X'],$this->Statement_Address_Coordinates['Y']);
		$this->MultiCell($this->Statement_Address_Coordinates['Width'],$this->Statement_Address_Coordinates['Height'],$address);


		//Date + Ref
		$date_ref = $this->Details['Date']; 
		$this->SetXY($this->Date_Coordinates['X'],$this->Date_Coordinates['Y']);
		$this->MultiCell($this->Date_Coordinates['Width'],$this->Date_Coordinates['Height'],$date_ref,0,'R');


		//Title	
		$Title = 'Statement '. date('Y',$this->Start_Timestamp);		
		$this->SetFont('','B',16);
		$this->SetXY($this->Title_Coordinates['X'],$this->Title_Coordinates['Y']);
		$this->Cell($this->Title_Coordinates['Width'],$this->Title_Coordinates['Height'],$Title,0,0,C);


		//Display table of Activities

		$this->SetXY($this->Details_Coordinates['X'],$this->Details_Coordinates['Y']);
		$this->FancyTable($this->header,$this->Details,180,$this->Balance);
		
		//footer
		$this->SetFont('','',7);
		$this->SetTextColor(120);
		$this->SetXY($this->Footer_Coordinates['X'],$this->Footer_Coordinates['Y']);
		$this->Cell($this->Footer_Coordinates['Width'],$this->Footer_Coordinates['Height'],$this->Footer,'T',0,'C');

	}

	function FancyTable($header,$data,$width,$Balance)
	{
		//Colors, line width and bold font
		$this->SetFillColor(180,180,190);
		$this->SetTextColor(255);
		$this->SetLineWidth(.3);
		$this->SetFont('','',12);
		//Header
		$cell_width = round($width/count($header));
		for($i=0;$i<count($header);$i++)
		{
			$w[$i]=$cell_width;
		}
		$this->SetX($this->Details_Coordinates['X']);
		for($i=0;$i<count($header);$i++)
			$this->Cell($w[$i],7,$header[$i],1,0,'C',1);
		$this->Ln();
		//Color and font restoration
		$this->SetFillColor(250,250,200);
		$this->SetTextColor(0);
		$this->SetFont('','',8);
		//Data
		$fill=0;
		foreach($data as $row)
		{
			$this->SetX($this->Details_Coordinates['X']);
			$this->Cell($w[0],6,$row[0],'LR',0,'L',$fill);
			$this->Cell($w[1],6,$row[1],'LR',0,'L',$fill);
			$this->Cell($w[2],6,$row[2],'LR',0,'L',$fill);
			$this->Cell($w[3],6,ASCII_CURRENCY_SYMBOL.number_format($row[3]),'LR',0,'R',$fill);
			//check for negative balance
			if($row[4] < 0)
			{
				$this->SetTextColor(255,0,0);
			}
			else
			{
				$this->SetTextColor(0);
			}
			$this->Cell($w[4],6,ASCII_CURRENCY_SYMBOL.number_format($row[4]),'LR',0,'R',$fill);
			$this->SetTextColor(0);
			$this->Ln();
			$fill=!$fill;
		}
		//Line
		$this->SetX($this->Details_Coordinates['X']);
		$this->Cell(array_sum($w),0,'','T');

		//Total
		$this->SetFont('','B');
		$this->SetX($this->Details_Coordinates['X']);
		$this->Cell($w[0],6,'',0,0,'L',0);
		$this->Cell($w[1],6,'',0,0,'L',0);
		$this->Cell($w[2],6,'',0,0,'L',0);
		$this->Cell($w[3],6,'Balance:','LR',0,'R',$fill);

		$this->SetTextColor(255,30,30);
		$this->Cell($w[4],6,ASCII_CURRENCY_SYMBOL.$Balance,'LR',0,'R',$fill);
		$this->SetTextColor(0);
		$this->Ln();

		$this->SetX($this->Details_Coordinates['X']+$w[0]+$w[1]+$w[3]);
		$this->Cell($w[3]+$w[4],0,'','T');
	}
}
?>
