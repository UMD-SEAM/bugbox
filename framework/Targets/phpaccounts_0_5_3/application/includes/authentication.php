<?php

function resendPassword($Email)
{
	global $db_reader;
	global $tmpl;
	$query = "SELECT Password FROM ". PHPA_USER_TABLE ." WHERE Email = '$Email'";
	$Password = $db_reader->queryOne($query);
	if($Password)
	{
		$email = ADMIN_EMAIL;
		$headers = "From: $email\r\nX-Mailer: PHP/ ". phpversion();
		$other = "-f $email";

		$tmpl->AddVar('resend_password','EMAIL',$Email);
		$tmpl->AddVar('resend_password','PASSWORD',$Password);
		$message = $tmpl->GetParsedTemplate('resend_password');
		mail($Email,'Your Login Details',$message,$headers,$other);
		return true;
	}
	else
	{
		return false;
	}
}
function showLogin($params=false)
{
	global $tmpl;
	global $Auth_Message;
	if(is_array($params))
	{
		foreach($params as $key=>$param)
		{
			//array of values
			if(is_array($param))
			{
				foreach($param as $key=>$value)
				{
					$tmpl->AddVar('login',$key,$value);
					$tmpl->AddVar('hidden_fields','NAME',$key);
					$tmpl->AddVar('hidden_fields','VALUE',str_replace('"','\'',$value));
					$tmpl->ParseTemplate('hidden_fields','a');
				}	
			}
			//scalar
			else
			{
				$tmpl->AddVar('login',$key,$param);
				$tmpl->AddVar('hidden_fields','NAME',$key);
				$tmpl->AddVar('hidden_fields','VALUE',str_replace('"','\'',$param));
				$tmpl->ParseTemplate('hidden_fields','a');
			}
		}
	}
	$tmpl->AddVar('login','LOGIN_USERNAME',$_COOKIE['Login_Username']);
	$tmpl->AddVar('login','LOGIN_PASSWORD',$_COOKIE['Login_Password']);
	$tmpl->AddVar('login','AUTH_MESSAGE',$Auth_Message);
	$tmpl->AddThisTemplate('login');
}	

//forgotten password
if($_POST['forgotten_password'])
{
	if(resendPassword($_POST['Login_Username']))
	{
		unset($_POST);
		$Content .= $tmpl->GetParsedTemplate('password_sent');
	}
	else
	{
		$tmpl->AddVar('cant_find_password','EMAIL',$_POST['Login_Username']);
		$Content .= $tmpl->GetParsedTemplate('cant_find_password');
	}
}


//start session, get IP and session ID (if exists)
//ini_set('session.use_only_cookies',1);
//session_set_cookie_params(AUTOLOGOUT_PERIOD);
if(!$PHPSESSID = $_COOKIE['PHPSESSID'])
{
	session_name('PHPSESSID');
	session_start();
	$PHPSESSID = session_id();
	setcookie("PHPSESSID",$PHPSESSID, time()+(AUTOLOGOUT_PERIOD),"/");
}
$IP = $_SERVER['REMOTE_ADDR'];

//check for login attempt
if($_POST['Login_Username'] && $_POST['Login_Password'])
{
	$Login_Username = $_POST['Login_Username'];
	$Login_Password = $_POST['Login_Password'];
	//check username and password in database
	$query = "SELECT ID,First_Name
		FROM ". PHPA_USER_TABLE ."
		WHERE Email = '$Login_Username' AND Password = '$Login_Password'";
	if(list($User_ID,$_SESSION['Logged_In_Username'])= $db_reader->queryRow($query))
	{
		$Auth_Message = 'logged in';
		$Authorised = true;
	}
	if($Authorised)
	{
		if($_POST['remember_me'])
		{
			//remeber_me cookie
			setcookie('Login_Username',$Login_Username, time()+(ONE_YEAR),"/");
			setcookie('Login_Password',$Login_Password, time()+(ONE_YEAR),"/");
		}
		else
		{
			//delete cookie
			setcookie('Login_Username','', NOW-(ONE_YEAR),"/");
			setcookie('Login_Password','', NOW-(ONE_YEAR),"/");
		}
	}

}
//check for existing session in database
elseif($PHPSESSID && $IP)
{
	//lets stop the IP checking AOL ruins this!
	$query = "SELECT UNIX_TIMESTAMP(Timestamp),User_ID
		FROM ". PHPA_PHPSESSION_TABLE ." P 
		WHERE PHPSESSID = '$PHPSESSID'"; // AND IP = '". $IP ."'";
	if(list($Timestamp,$User_ID) = $db_reader->queryRow($query))
	{
		session_register('User_ID');
		//SESSION EXISTS, check IP and TIME OUT
		if(NOW - $Timestamp < AUTOLOGOUT_PERIOD) 
		{
			$Authorised = true;
		}
		else
		{
			$Authorised = false;
			$Auth_Message = 'Timed out';
		}
	}
	else
	{
		//no session show login
		$Authorised = false;
	}
}
else
{
	$Authorised = false;
	$Auth_Message = 'no session';
}

$action = getVariable('action');
if($action == 'logout')
{
	$Auth_Message = 'user logged out';
	$Authorised = false;
}

if($Authorised)
{
	//insert session into session table
	$query = "SELECT Timestamp,User_ID FROM ". PHPA_PHPSESSION_TABLE ." WHERE PHPSESSID = '$PHPSESSID'"; 
	if($result = $db_reader->queryRow($query))
	{
		list($Timestamp,$User_ID) = $result;
		//update existing timeout
		$query = "UPDATE ". PHPA_PHPSESSION_TABLE ."
			SET Timestamp = NOW()
			WHERE PHPSESSID = '$PHPSESSID'";
	}
	else
	{
		//or create new entry in session table
		$query = "INSERT INTO ". PHPA_PHPSESSION_TABLE ."
			(PHPSESSID,IP,User_ID)
			VALUES('$PHPSESSID','$IP','". $User_ID ."')";

	}
	$result = $db_writer->exec($query);

	// Always check that $result is not an error
	if (MDB2::isError($result))
	{
		trigger_error($result->getMessage());
		exit();
	}
	//register User_ID
	$_SESSION['User_ID'] = $User_ID;

}
else
{
	//not logged in
	if($PHPSESSID = session_id())
	{
		setcookie('PHPSESSID',$PHPSESSID, time()-1000,'/');
		session_unset();
		session_destroy();
	}
	$Auth_Message = 'not authorised';
	$Authorsed = false;
}
if($action == 'logout')
{
	//not logged in
	if($PHPSESSID = session_id())
	{
		setcookie('PHPSESSID',$PHPSESSID, time()-1000,'/');
		session_unset();
		session_destroy();
	}
	$Auth_Message = 'user logged out';
	$Authorsed = false;
}
if(!$Authorised)
{
showLogin();
}
?>
