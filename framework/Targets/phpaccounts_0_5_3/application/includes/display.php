<?php
//patTemplate must be in the path
require_once(INCLUDE_PATH .'/includes/patTemplate.php');



class tmpl extends patTemplate{

	var $DisplayType = 'normal';
	//constructor instantiates patTemplate class

	function SetDisplayType($value)
	{
		$this->DisplayType = $value;
	}

	//add parsed template on the end of our Content
	function AddThisTemplate($template)
	{
		$this->PageContent .= $this->GetParsedTemplate($template);
	}

	//add parsed template on the beggining of our Content
	function AddThisBeforeTemplate($template)
	{
		$this->BeforePageContent = $this->GetParsedTemplate($template) . $this->BeforePageContent;
	}

	function AddContent($Content)
	{
		$this->PageContent .= $Content;
	}

	function ClearContent()
	{
		$this->PageContent .= false;
	}

	function AddBeforeContent($Content)
	{
		$this->PageContent = $Content . $this->PageContent;
	}

	function Filter($Content)
	{
		//should have some register deregistering commands really

		//currency
		$Content = str_replace('_HTML_CURENCY_SYMBOL_',HTML_CURRENCY_SYMBOL,$Content);
		$Content = str_replace('_ASCII_CURENCY_SYMBOL_',ASCII_CURRENCY_SYMBOL,$Content);

		return $Content;
	}

	function DisplayContent()
	{
		switch($this->DisplayType)
		{
			case 'normal':
				$this->AddVar('main','CONTENT',$this->BeforePageContent . $this->PageContent);
				$Content = $this->GetParsedTemplate('main');
				break;

			case 'content_only':
				$this->AddVar('main','CONTENT',$this->PageContent);
				$Content = $this->GetParsedTemplate('main');
				break;
	
			case 'frameset':
				$Content = $this->GetParsedTemplate('frameset');
				break;

			case 'login':
				$Content = $this->GetParsedTemplate('login');
				break;

			case 'no_cookie_message':
				$Content = $this->GetParsedTemplate('no_cookie_message');
				break;

			case 'csv':
				$this->OutputCSVHeaders();
				$Content = $this->PageContent;
				break;
				
			case 'none':
				$Content = false;
				break;

			default:
				echo 'dogs';
				exit();
		}
		
		//final output
		echo $this->Filter($Content);
		exit();
	}

	function OutputCSVHeaders()
	{
		global $action;
		if(!$action)
		{
			$action = 'report';
		}
		header("Content-type: application/octet-stream");
		header("Content-disposition: attachment; filename=$action.csv");
		header("Pragma: no-cache");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Expires: 0"); 
	}

}
?>
