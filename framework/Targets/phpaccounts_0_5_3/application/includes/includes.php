<?php
//database connection and startup
require_once( INCLUDE_PATH .'/includes/config.php');

//database connection and startup
require_once( INCLUDE_PATH .'/includes/db.php');

//error reporting
require_once( INCLUDE_PATH .'/includes/error.php');

//form functions
require_once( INCLUDE_PATH .'/includes/form_fns.php');

//database functions
require_once( INCLUDE_PATH .'/includes/db_fns.php');

//genral functions
require_once( INCLUDE_PATH .'/includes/main_fns.php');

//pear config
require_once( INCLUDE_PATH .'/includes/pear.php');

//instantiate  the extention of the template class
require_once( INCLUDE_PATH .'/includes/display.php');

//include  the invoice wizard
require_once( INCLUDE_PATH .'/includes/invoiceWizard.php');

?>
