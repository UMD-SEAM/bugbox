<?php
require_once('fpdf.php');
class PDF_Base extends FPDF 
{
	//Fixed Text
	var $Footer = LETTER_FOOTER;
	var $Footer_Coordinates = array('X'=>0,'Y'=>280,'Width'=>210,'Height'=>7);//this will be centred

	// Constructor
	function PDF_Base ()
	{
		//set to Portrait mm and A4
		parent::FPDF('P', 'mm', 'A4');
		$this->SetMargins(0,0,0);
		//include some fonts
		
		//trebuchet
		$this->AddFont('trebuchet','','trebuc.php');
		$this->AddFont('trebuchet','I','trebucit.php');
		$this->AddFont('trebuchet','B','trebucbd.php');
		
		//Verdana
		$this->AddFont('verdana','','Verdana.php');
		$this->AddFont('verdana','I','Verdana.php');
		$this->AddFont('verdana','B','Verdana.php');
	
		//tahoma
		$this->AddFont('tahoma','','Tahoma.php');
		$this->AddFont('tahoma','I','Tahoma.php');
		$this->AddFont('tahoma','B','Tahoma.php');
		
		if(defined('LETTER_FONT'))
		{
		$this->SetFont(LETTER_FONT,'',12);
		}
		//Fixed Images
		if(defined('LETTERHEAD_IMAGE'))
		{
			$this->Header_Image = array('Filename'=>LETTERHEAD_IMAGE,'X'=>5,'Y'=>5,'Width'=>LETTERHEAD_WIDTH,'Height'=>LETTERHEAD_HEIGHT);
		}
		$this->SetAutoPageBreak(false);
		$this->Formatted_Date = date('d/m/Y');
		$this->SetDrawColor(180,180,190);
	}

	//input data
	function Add_Details($data)
	{
		$this->Details = $data;
	}


	function Get_Formatted_Value($Value)
	{
		return number_format($Value, 2, '.', '');
	}

	function Get_Figures($Value)
	{
		//split into Bases
		foreach($this->Bases as $base)
		{
			$Figures[$base] = (int)floor($Value/$base);
			$Value -= $Figures[$base]  * $base;
			$Figures[$base] = (string)$Figures[$base];
		}	
		return $Figures;	
	}


	function Print_Image($image_array)
	{
		if(file_exists($image_array['Filename']))
		{
			$this->Image(
				$image_array['Filename'],
				$image_array['X'],
				$image_array['Y'],
				$image_array['Width'],
				$image_array['Height']
			);
		}
	}

}
?>
