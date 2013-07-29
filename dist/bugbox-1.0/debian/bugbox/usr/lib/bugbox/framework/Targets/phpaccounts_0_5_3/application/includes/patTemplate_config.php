<?php
// Initialize the patTemplate-class, and create an object
$tmpl = new patTemplate();
$tmpl->setBasedir(INCLUDE_PATH .'/templates/');

// Read main Templates
$tmpl->readTemplatesFromFile("main.tmpl.html");
?>
