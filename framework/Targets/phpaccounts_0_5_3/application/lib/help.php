<?php
$tmpl->ReadTemplatesFromFile('help.tmpl.html');

$section = getVariable('section');
if(!$section)
{
	$section = 'default';
}

$tmpl->AddThisTemplate($section);

$tmpl->AddThisBeforeTemplate('menu');

?>
