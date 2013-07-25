<?php

/*
 * General Configura
 *
 */
//
// Data Source Name: This is the universal connection string
define('DB_WRITER_DSN',  DB_DRIVER.'://'.DB_USER.':'.DB_PASS.'@'.DB_WRITER_HOST.'/'. DB_NAME );
define('DB_READER_DSN', DB_DRIVER.'://'.DB_USER.':'.DB_PASS.'@'.DB_READER_HOST.'/'. DB_NAME );

//database table names
define('PHPA_CLIENT_SENT_MAIL_TABLE',DB_PREFIX.'Client_Sent_Mail_tbl');
define('PHPA_CLIENT_TABLE',DB_PREFIX.'Client_tbl');
define('PHPA_ERROR_LOG_TABLE',DB_PREFIX.'Error_Log_tbl');
define('PHPA_IMAGE_TABLE',DB_PREFIX.'Image_tbl');
define('PHPA_INVOICE_MAIL_LOOKUP_TABLE',DB_PREFIX.'Invoice_Mail_Lookup_tbl');
define('PHPA_INVOICE_PAYMENT_TABLE',DB_PREFIX.'Invoice_Payment_tbl');
define('PHPA_INVOICE_TABLE',DB_PREFIX.'Invoice_tbl');
define('PHPA_LINK_TABLE',DB_PREFIX.'Link_tbl');
define('PHPA_OUTGOING_PAYMENT_TABLE',DB_PREFIX.'Outgoing_Payment_tbl');
define('PHPA_OUTGOING_TYPE_TABLE',DB_PREFIX.'Outgoing_Type_tbl');
define('PHPA_OUTGOING_TABLE',DB_PREFIX.'Outgoing_tbl');
define('PHPA_PHPSESSION_TABLE',DB_PREFIX.'PHPSESSION_tbl');
define('PHPA_PREFERENCES_TABLE',DB_PREFIX.'Preferences_tbl');
define('PHPA_PROJECT_INVOICE_TABLE',DB_PREFIX.'Project_Invoice_tbl');
define('PHPA_PROJECT_TIMESHEET_TABLE',DB_PREFIX.'Project_Timesheet_tbl');
define('PHPA_PROJECT_TABLE',DB_PREFIX.'Project_tbl');
define('PHPA_QUOTE_TABLE',DB_PREFIX.'Quote_tbl');
define('PHPA_REPEAT_INVOICE_LOG_TABLE',DB_PREFIX.'Repeat_Invoice_Log_tbl');
define('PHPA_REPEAT_INVOICE_TABLE',DB_PREFIX.'Repeat_Invoice_tbl');
define('PHPA_TIMESHEET_TABLE',DB_PREFIX.'Timesheet_tbl');
define('PHPA_USER_TABLE',DB_PREFIX.'User_tbl');
define('PHPA_VENDOR_TABLE',DB_PREFIX.'Vendor_tbl');

//time config
define('NOW',mktime());
define('ONE_MINUTE',60);
define('ONE_HOUR',60*ONE_MINUTE);
define('ONE_DAY',24*ONE_HOUR);
define('ONE_WEEK',7*ONE_DAY);
define('ONE_YEAR',365*ONE_DAY);
define('AUTOLOGOUT_PERIOD', ONE_DAY);
define('TAX_YEAR_START_MONTH',4);
define('TAX_YEAR_START_DAY',5);
define('PHP_DATE_FORMAT','Y-m-d');
define('MYSQL_DATE_FORMAT','%Y-%m-%d');


//html + graph config
define('SMALL_GRAPH_HEIGHT',100);
define('FPDF_FONTPATH',INCLUDE_PATH .'/font/');
define('ABSOLUTE_URI','');


//PDF related
define('LETTERHEAD_WIDTH',200);
define('LETTERHEAD_HEIGHT',38);

//currency symbols
$Currency_Symbols['USD']['ASCII'] = chr(36);
$Currency_Symbols['USD']['HTML'] = '&#36;';
$Currency_Symbols['CAD']['ASCII'] = chr(36);
$Currency_Symbols['CAD']['HTML'] = '&#36;';
$Currency_Symbols['GBP']['ASCII'] = chr(163);
$Currency_Symbols['GBP']['HTML'] = '&pound;';
$Currency_Symbols['EUR']['ASCII'] = chr(8364);
$Currency_Symbols['EUR']['HTML'] = '&euro;';
$Currency_Symbols['JPY']['ASCII'] = chr(165);
$Currency_Symbols['JPY']['HTML'] = '&yen;';

//Default Outgoing_Types
$default_Outgoing_Types = 
	array(
		'Capital Expenditure',
		'IT Capital Expenditure',
		'Employee costs',
		'Premises costs',
		'Repairs',
		'General administrative expenses',
		'Motor expenses',
		'Travel and subsistence',
		'Advertising, promotion and entertainment',
		'Legal and professional costs',
		'Interest',
		'Other finance charges',
		'Depreciation and loss/(profit) on sale',
		'Other expenses'
	);

//email confing
define('SYSTEM_EMAIL_HEADERS','From: '. ADMIN_EMAIL);

//debugging
define('DEBUG_ENV',true);

//php config
ini_set('register_globals',false);
ini_set('include_path', get_include_path(). PATH_SEPARATOR . INCLUDE_PATH .'/includes/pear');
ini_set('include_path', get_include_path(). PATH_SEPARATOR . INCLUDE_PATH .'/includes/magpierss');
ini_set('include_path', get_include_path(). PATH_SEPARATOR . INCLUDE_PATH .'/includes');
ini_set('display_errors','on');
error_reporting(E_ALL ^ E_NOTICE);

?>
