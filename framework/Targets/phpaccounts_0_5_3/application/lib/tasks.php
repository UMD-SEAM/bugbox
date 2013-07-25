<?php

$tmpl->ReadTemplatesFromFile('tasks.tmpl.html');


/*-----------------------------------------------------------------------------*\

Process Section

\*-----------------------------------------------------------------------------*/

if($action == 'my_details' && $_POST)
{
	$query = buildUPDATE($_POST,PHPA_USER_TABLE,' ID = '. $_POST['ID']);
	$db_writer->exec($query);
}


if($action == 'preferences' && $_POST)
{
	//drop current prefs
	$query = "DELETE FROM ". PHPA_PREFERENCES_TABLE ." WHERE User_ID = $User_ID";
	$db_writer->exec($query);

	if($_POST['default'])
	{
		defaultPreferences();
	}
	else
	{
		//INSERT new prefs
		foreach($_POST['Preferences'] as $Preference => $value)
		{
			$query = "INSERT INTO ". PHPA_PREFERENCES_TABLE ." (User_ID,Preference,Value) VALUES ($User_ID,'$Preference','". addslashes($value) ."')";
			$db_writer->exec($query);
		}
		//now update preferences file
		writePreferences();

		//letterhead
		if($_POST['default_letterhead'])
		{
			defaultLetterhead();
		}
		if($image = $_FILES['letterhead_image']['tmp_name'])
		{
			$tmp_image = USER_DIR .'/'. $_FILES['letterhead_image']['name'];

			if(!move_uploaded_file ($image,$tmp_image))
			{
				if(!copy($image,$tmp_image))
				{
					echo "MAIN IMAGE IS $image <br>";
					echo 'failed to move uploaded image to '. $tmp_image;
					exit();
				}
			}

			//turn into png
			$command = "convert $tmp_image ". LETTERHEAD_IMAGE;
			//create jpeg thumbnail
			system($command);
			$command = "convert -resize 200x38  $tmp_image ". LETTERHEAD_THUMBNAIL;
			system($command);
			chmod(LETTERHEAD_IMAGE,0777);
			chmod(LETTERHEAD_THUMBNAIL,0777);
		}

	}

}


/*-----------------------------------------------------------------------------*\

  Display Section

  \*-----------------------------------------------------------------------------*/

if($action == 'my_details')
{

	$FORM = updateForm(PHPA_USER_TABLE,"?page=tasks&action=my_details","ID = $User_ID",'My Details Edit');
	$tmpl->AddVar('my_details','FORM',$FORM);
	$tmpl->AddThisTemplate('my_details');

}
if($action == 'preferences')
{
	$fonts = array('times','helvetica','arial','courier','verdana','tahoma','trebuchet');
	foreach($fonts as $font)
	{
		$font_array[$font] = $font;
	}

	$query = "SELECT Preference,Value FROM ". PHPA_PREFERENCES_TABLE ." WHERE USER_ID = $User_ID";
	$result = $db_reader->query($query);
	while($row = $result->FetchRow(MDB2_FETCHMODE_ASSOC))
	{
		foreach($row as $key => $value)
		{
			//font select list
			if($value == 'LETTER_FONT')
			{
				$tmpl->AddVar('font_select','SELECT_LIST',selectlist2('Preferences[LETTER_FONT]',$font_array,$row['Value']));

				continue 2;
			}
			else
			{
				$tmpl->AddVar('preferences_results',$key,stripslashes($value));
			}
		}
		$tmpl->AddVar('preferences_results','TITLE',str_replace('_',' ',$row['Preference']));
		$tmpl->ParseTemplate('preferences_results','a');
	}
	$tmpl->AddVar('preferences','USER_ID',$User_ID);
	$tmpl->AddThisTemplate('preferences');

}

if(!$action)
{
	//include rss class for the dashboard
	require_once('magpierss/rss_fetch.inc');

	//get development news
	$rss = @fetch_rss('http://phpaccounts.com/category/development/feed/');
	if ( isset($rss->items) && 0 != count($rss->items) )
	{
		$tmpl->SetAttribute('development_news','visibility','show');
		$rss->items = array_slice($rss->items, 0, 3);
		foreach ($rss->items as $item ) 
		{
			$date = human_time_diff(strtotime($item['pubdate'], NOW)) ; 
			$tmpl->AddVar('development_news_results','link',$item['link']);
			$tmpl->AddVar('development_news_results','title',$item['title']);
			$tmpl->AddVar('development_news_results','description',substr($item['description'],0,150));
			$tmpl->AddVar('development_news_results','date',$date);
			$tmpl->ParseTemplate('development_news_results','a');
		}
	}

	$tmpl->AddVar('tasks','CLIENT_SELECT_LIST',selectlist('-- choose client','Client_ID',$Client_array,$row['Client_ID']));
	$tmpl->AddThisTemplate('tasks');
}
$tmpl->AddThisBeforeTemplate('menu');
?>
