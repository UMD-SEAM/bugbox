<?php
	include_once ("../config/config.inc.php");
	include_once ("../language_files/language.inc.php");
	include_once ("../pages/version.inc.php");
	
	//--- open database
	$nConnection = mysql_connect($DATABASE_HOST, $DATABASE_UID, $DATABASE_PWD);
	if ($nConnection)
	{
		if (mysql_select_db($DATABASE_DB, $nConnection))
		{
			//-----------------------------------------------
			//--- get the senders userid
			//-----------------------------------------------
			
			$strQuery = "SELECT nSenderId FROM `cf_circulationform` WHERE nID=$nCirculationId";
			$nResult = mysql_query($strQuery, $nConnection);
			if ($nResult)
			{
				if (mysql_num_rows($nResult) > 0)
				{
					$arrSenderID = mysql_fetch_array($nResult);		
					$nSenderID = $arrSenderID["nSenderId"];
				}
			}
			
			//-----------------------------------------------
			//--- get sender details
			//-----------------------------------------------				
			
			$strQuery = "SELECT strLastName, strFirstName FROM `cf_user` WHERE nID=$nSenderID";
			$nResult = mysql_query($strQuery, $nConnection);
			if ($nResult)
			{
				while ($row = mysql_fetch_array($nResult))
				{
					$arrSenderDetails[] = $row["strLastName"];
					$arrSenderDetails[] = $row["strFirstName"];
				}
			}
			
			//-----------------------------------------------
			//--- get the sending date
			//-----------------------------------------------
			
			$strQuery = "SELECT dateSending FROM `cf_circulationhistory` WHERE nCirculationFormId=$nCirculationId";
			$nResult = mysql_query($strQuery, $nConnection);
			if ($nResult)
			{
				if (mysql_num_rows($nResult) > 0)
				{
					$arrSendingDateResult = mysql_fetch_array($nResult);
					$strMySendingDate = $arrSendingDateResult["dateSending"];
					$strSendingDate = convertDateFromDB($strMySendingDate);				
				}
			}
		}
	}

//init vars
$CurLang = $_REQUEST["language"];
$SENDER = $arrSenderDetails[0].", ".$arrSenderDetails[1];
$SENDDATE = $strSendingDate."\n";

$strParams					= 'cpid='.$Circulation_cpid.'&language='.$CurLang;
$strEncyrptedParams			= $objURL->encryptURL($strParams);
$strEncryptedBrowserview	= $CUTEFLOW_SERVER.'/pages/editworkflow_standalone.php?key='.$strEncyrptedParams;

$strMessage = "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\" \"http://www.w3.org/TR/html4/loose.dtd\">
<html>
<head>
	<title></title>
	<meta http-equiv=\"Content-Type\" content=\"text/html; charset=$DEFAULT_CHARSET\">
	<style>
		body, table, td, tr
		{
			font-family: Verdana, Geneva, Arial, Helvetica, sans-serif;
			font-size: 9pt;
		}
		a
		{
			font-family: Verdana, Geneva, Arial, Helvetica, sans-serif;
			font-size: 9pt;
			text-decoration: none;
		}
		a:hover
		{ text-decoration: underline; }
		.BorderRed
		{ border: 1px solid Red; }
		.BorderGray
		{ border: 1px solid Gray; }
		.FormInput
		{
			font-family: Verdana, Geneva, Arial, Helvetica, sans-serif;
			font-size: 8pt;
			border: 1px solid #B8B8B8;
		}
		.Button
		{
			font-size: 8pt;
			border: 1px solid #C10000;
			color: Black;
			padding: 2px 2px 2px 2px;
		}
		.note
		{
			padding-left : 2px;
			padding-top  : 2px;
			border-width : 1px;
			border-color : #B0B0AF;
			border-style : solid;
			background-color: #FCFBE9;
			font-size: 8pt;
		}
		.table_header
		{
			background-color: #8e8f90; 
			color: #fff; 
			font-size: 12px; 
			font-weight: bold;
		}
		.mandatory
		{ font-weight: bold; }
	</style>
</head>
<body bgcolor=\"#ffffff\">
	<br>
	<br>
	<div align=\"center\">
		
		<table width=\"90%\" style=\"border: 1px solid #c8c8c8; background: #efefef;\" cellspacing=\"0\" cellpadding=\"3\">
			<tr>
				<td colspan=\"2\" align=\"left\" class=\"table_header\" style=\"border-bottom: 3px solid #ffa000;\">
					$MAIL_HEADER_PRE $Circulation_Name
				</td>
			</tr>
			<tr>
				<td colspan=\"2\" align=\"left\">
					$Circulation_AdditionalText
				</td>
			</tr>
			<tr>
				<td colspan=\"2\" style=\"border-top:1px solid Gray;\" height=\"10px\">&nbsp;</td>
			</tr>
			<tr>
				<td width=\"18%\" align=\"left\">$CIRCDETAIL_SENDER</td>
				<td align=\"left\">$SENDER</td>
			</tr>
			<tr>
				<td align=\"left\">$CIRCDETAIL_SENDDATE</td>
				<td align=\"left\">$SENDDATE</td>
			</tr>
			<tr><td><br></td></tr>
			<tr>
				<td  colspan=\"2\" class=\"note\" style=\"background-color:white;\">$MAIL_LINK_DESCRIPTION
				<a href=\"$strEncryptedBrowserview\">$EMAIL_BROWSERVIEW</a>
				</td>
			</tr>
			<tr><td><br></td></tr></table>

		<br><br>
		<strong style=\"font-size:8pt;font-weight:normal\">powered by</strong><br>
		<a href=\"http://cuteflow.org\" target=\"_blank\"><img src=\"$CUTEFLOW_SERVER/images/cuteflow_logo_small.png\" border=\"0\" /></a><br>
		<strong style=\"font-size:8pt;font-weight:normal\">Version $CUTEFLOW_VERSION</strong><br>
			
</div>
</body>
</html>";

?>