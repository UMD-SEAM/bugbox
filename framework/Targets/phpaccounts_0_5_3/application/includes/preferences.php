<?php
	define('USER_DIR',INCLUDE_PATH ."/users/$User_ID");
	define('LETTERHEAD_IMAGE',USER_DIR ."/letterhead.png");
	define('LETTERHEAD_THUMBNAIL',USER_DIR ."/t_letterhead.jpg");
	define('PREFERENCES_FILE',USER_DIR ."/preferences.php");

	if(!is_writable(INCLUDE_PATH ."/users"))
	{
		$tmpl->AddVar('user_directory_not_writable','USER_DIR',INCLUDE_PATH ."/users");
		$tmpl->AddVar('user_directory_not_writable','CACHE_DIR',INCLUDE_PATH ."/cache");
		$tmpl->DisplayParsedTemplate('user_directory_not_writable');
		exit();
	}

	//define the default preferences
	function getDefaultPreferences()
	{
		$Preference['CURRENCY'] = 'USD';
		$Preference['AUTO_NUMBER_INVOICES'] = 'yes';
		$Preference['DAYS_TO_SEND_FIRST_REMINDER'] = 30;
		$Preference['DAYS_TO_SEND_SECOND_REMINDER'] = 37;
		$Preference['DAYS_TO_SEND_FINAL_REMINDER'] = 44;
		$Preference['HOURLY_RATE'] = 0;
		return $Preference;
	}

	function defaultPreferences()
	{
		global $User_ID;
		global $db_writer;
		global $db_reader;

		//check real user
		$User_ID = $db_reader->queryOne("SELECT ID FROM ". PHPA_USER_TABLE ." WHERE ID = '$User_ID'");

		if(!$User_ID)
		{
			exit;
		}

		$Preference = getDefaultPreferences();
		$query = "DELETE FROM ". PHPA_PREFERENCES_TABLE ." WHERE User_ID = $User_ID";
		$db_writer->exec($query);

		//get some basic details to create default preferences
		$query = "SELECT * FROM ". PHPA_USER_TABLE ." WHERE ID = $User_ID";
		$result = $db_reader->query($query);
		$row = $result->FetchRow(MDB2_FETCHMODE_ASSOC);

		$Address = array($row['Address1'],$row['Address2'],$row['City'],$row['Region'],$row['Postcode'],$row['Country']);
		$Address_Linear = implode(', ',$Address);

		//default user preferences
		$Preference['LETTER_FONT'] = 'verdana';
		$Preference['LETTER_HEADER'] = "{$row['Website']} | {$row['Email']} | {$row['Telephone']} | $Address_Linear";
		$Preference['LETTER_FOOTER'] = "Registered Office: $Address_Linear";
		$Preference['INVOICE_THANKYOU'] = "Please make cheques payable to '{$row['Company_Name']}'\nThank You for your business!";
		$Preference['QUOTE_TERMS'] = "Payment Terms: Nett 30 days.\n Acceptance: Quotations are valid for 30 days from date of issue.\nTerms & Conditions: You must also agree to our standard terms & conditions, as published on our website.";

		//build query
		foreach($Preference as $key => $value)
		{
			$query = "INSERT INTO ". PHPA_PREFERENCES_TABLE ." (User_ID,Preference,Value) VALUES($User_ID,'$key','". addslashes($value) ."')";
			$db_writer->exec($query);
		}

		writePreferences();
	}


	function checkPreferences()
	{
		global $User_ID;
		global $db_writer;
		global $db_reader;
		$Preference = getDefaultPreferences();

		$query = "SELECT Preference FROM ". PHPA_PREFERENCES_TABLE ." WHERE User_ID = $User_ID";
		$result = $db_reader->query($query);
		while($row = $result->FetchRow(MDB2_FETCHMODE_ASSOC))
		{
			unset($Preference[$row['Preference']]);
		}

		//any Preferences left must now be inserted
		while(list($key,$value) = each($Preference))
		{
			$query = "INSERT INTO ". PHPA_PREFERENCES_TABLE ." (User_ID,Preference,Value) VALUES($User_ID,'$key','". addslashes($value) ."')";
			$Affected_Rows =& $db_writer->exec($query);
			if(is_int($Affected_Rows))
			{
				$Affected = true;
			}
		}
		if($Affected)
		{
			writePreferences();
		}
	}

	function writePreferences()
	{
		global $db_reader;
		global $User_ID;
		global $Currency_Symbols;;
		if(!$User_ID)
		{
			return false;
		}
		//create php file
		$data = "<?php\n";

		//look up and create prefence file
		$query = "SELECT Preference,Value FROM ". PHPA_PREFERENCES_TABLE ."  WHERE User_ID = $User_ID ORDER BY Preference";
		$result = $db_reader->query($query);
		if($result->NumRows() > 0)
		{
			while(list($key,$value) = $result->FetchRow())
			{
				if($key == 'CURRENCY')
				{
					$currency = $value;
				}
				$data .= "define('$key',\"". str_replace('"','\"',stripslashes($value)) ."\");\n";
			}
		}
		else
		{
			defaultPreferences();
		}

		//now create the currencies
		$data .= "define('HTML_CURRENCY_SYMBOL',\"". $Currency_Symbols[$currency]['HTML'] ."\");\n";
		$data .= "define('ASCII_CURRENCY_SYMBOL',\"". $Currency_Symbols[$currency]['ASCII'] ."\");\n";

		$data .= "?>";

		$file = PREFERENCES_FILE;
		writeFile($file,$data);
		@chmod($file,0777);
	}


	function defaultLetterhead()
	{
		ini_set( "memory_limit", "20M" );
		global $User_ID;
		global $db_reader;

		$query = "SELECT Company_Name FROM ". PHPA_USER_TABLE ." WHERE ID = $User_ID";
		$Company_Name = $db_reader->queryOne($query);

		$im = @imagecreatetruecolor(LETTERHEAD_WIDTH * 11, LETTERHEAD_HEIGHT * 11) or die("Cannot Initialize new GD image stream");
		$black = imagecolorallocate($im, 50, 50, 50);
		$white = imagecolorallocate($im, 255, 255, 255);
		imagefilledrectangle($im, 0, 0, LETTERHEAD_WIDTH * 11, LETTERHEAD_HEIGHT * 11, $white);

		// Add the Company Name
		$font = FPDF_FONTPATH .'/trebuc.ttf'; 
		imagettftext($im, 60, 0, 10, 100, $black, $font, $Company_Name);

		//Add the contact detail
		imagettftext($im, 30, 0, 10, 300, $black, $font, LETTER_HEADER);

		imagepng($im,LETTERHEAD_IMAGE);
		imagedestroy($im);

		//make thumbnail
		$command = 'convert -resize 200x38 '. LETTERHEAD_IMAGE .' '. LETTERHEAD_THUMBNAIL;
		system($command);

		chmod(LETTERHEAD_IMAGE,0777);
		chmod(LETTERHEAD_THUMBNAIL,0777);
	}

	//check for existining prefence file
	if(!file_exists(PREFERENCES_FILE))
	{
		//check for Dir
		if(!file_exists(USER_DIR))
		{
			mkdir(USER_DIR);
			chmod(USER_DIR,0777);
		}
		defaultPreferences();
	}

	//check for existining letterhead image
	if(!file_exists(LETTERHEAD_IMAGE))
	{
		//check for Dir
		if(!file_exists(USER_DIR))
		{
			mkdir(USER_DIR);
			chmod(USER_DIR,0777);
		}
		defaultLetterhead();
	}

	//check for missing prefs
	checkPreferences();

	include(PREFERENCES_FILE);

?>
