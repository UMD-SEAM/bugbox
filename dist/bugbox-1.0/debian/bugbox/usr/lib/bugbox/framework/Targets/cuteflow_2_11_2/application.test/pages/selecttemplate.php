<?php
	/** Copyright (c) Timo Haberkern. All rights reserved.
	*
	* Redistribution and use in source and binary forms, with or without 
	* modification, are permitted provided that the following conditions are met:
	* 
	*  o Redistributions of source code must retain the above copyright notice, 
	*    this list of conditions and the following disclaimer. 
	*     
	*  o Redistributions in binary form must reproduce the above copyright notice, 
	*    this list of conditions and the following disclaimer in the documentation 
	*    and/or other materials provided with the distribution. 
	*     
	*  o Neither the name of Timo Haberkern nor the names of 
	*    its contributors may be used to endorse or promote products derived 
	*    from this software without specific prior written permission. 
	*     
	* THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" 
	* AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, 
	* THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR 
	* PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR 
	* CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, 
	* EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, 
	* PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; 
	* OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, 
	* WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR 
	* OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, 
	* EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
	*/
	
	include_once ("../config/config.inc.php");
	include_once ("../language_files/language.inc.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=<?php echo $DEFAULT_CHARSET ?>">
	<title></title>	
	<link rel="stylesheet" href="format.css" type="text/css">
	<script language="JavaScript">
	<!--
		function doOk()
		{
			if (document.forms.BrowseTemplate.Template.options.selectedIndex != -1)
			{
				nID = document.forms.BrowseTemplate.Template.options[document.forms.BrowseTemplate.Template.options.selectedIndex].value;
				strTemplateName = document.forms.BrowseTemplate.Template.options[document.forms.BrowseTemplate.Template.options.selectedIndex].innerHTML;
				
				opener.SetTemplate(nID, strTemplateName);
					
				window.close();				
			}	
			else
			{
				alert ('<?php echo str_replace("'", "\'", $TEMPLATE_SELECT_NO_SELECT);?>');				
			}
		}
	//-->
	</script>	
</head>
<body topmargin="0" leftmargin="0" style="margin-top: 5px;">

	<form action="" id="BrowseTemplate">
		<table style="background: #efefef; border: 1px solid #c8c8c8;" cellspacing="0" cellpadding="3" align="center">
			<tr>
				<td colspan="2" class="table_header" style="border-bottom: 3px solid #ffa000;">
					<?php echo $TEMPLATE_SELECT_FORM_HEADER;?>
				</td>
			</tr>
			<tr>
				<td style="padding: 8px 4px 8px 4px;">
					<select id="Template" class="FormInput" size="7" style="width:250px;">
    					<?php
    						//--- open database
                        	$nConnection = mysql_connect($DATABASE_HOST, $DATABASE_UID, $DATABASE_PWD);
                        	
                        	if ($nConnection)
                        	{
                        		//--- get maximum count of users
                        		if (mysql_select_db($DATABASE_DB, $nConnection))
                        		{
                        			//--- read the values of the user
                    				$strQuery = "SELECT * FROM cf_formtemplate WHERE bDeleted=0 ORDER BY strName ASC";
                    				$nResult = mysql_query($strQuery, $nConnection);
                            
                            		if ($nResult)
                            		{
                            			if (mysql_num_rows($nResult) > 0)
                            			{
                            				while (	$arrRow = mysql_fetch_array($nResult))
                            				{
                         						echo "<option value=\"".$arrRow["nID"]."\">".$arrRow["strName"];
                            				}		
                            			}
                            		}
                          		}
                        	}
    					?>
					</select>
				</td>
			</tr>
		</table>
		
		<table cellspacing="0" cellpadding="3" align="center" width="260">
		<tr>
			<td align="left">
				<input type="button" value="<?php echo $BTN_CANCEL;?>" class="Button" onClick="window.close()">
			</td>
			<td align="right">
				<input type="button" value="<?php echo $BTN_OK;?>" class="Button" onClick="doOk()">
			</td>
		</tr>
		</table>
	</form>

</body>
</html>
