CREATE TABLE PHPA_CLIENT_SENT_MAIL_TABLE (
  `ID` int(10) unsigned NOT NULL auto_increment,
  `Client_ID` mediumint(8) unsigned NOT NULL default '0',
  `Read` enum('no','yes') NOT NULL default 'no',
  `Email` varchar(50) NOT NULL default '',
  `Subject` varchar(50) NOT NULL default '',
  `Timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `Message` text NOT NULL,
  PRIMARY KEY  (`ID`),
  KEY `User_ID` (`Client_ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

CREATE TABLE PHPA_CLIENT_TABLE (
  `ID` smallint(5) unsigned NOT NULL auto_increment,
  `User_ID` int(11) unsigned NOT NULL default '0',
  `Company_Name` varchar(100) NOT NULL default '',
  `Contact_First_Name` varchar(30) NOT NULL default '',
  `Contact_Surname` varchar(30) NOT NULL default '',
  `Address1` varchar(50) NOT NULL default '',
  `Address2` varchar(50) NOT NULL default '',
  `City` varchar(30) NOT NULL default '',
  `Region` varchar(30) NOT NULL default '',
  `Country` varchar(30) NOT NULL default '',
  `Postcode` varchar(12) NOT NULL default '',
  `Telephone` varchar(27) NOT NULL default '',
  `Telephone2` varchar(20) NOT NULL default '',
  `Mobile` varchar(20) NOT NULL default '',
  `Fax` varchar(20) NOT NULL default '',
  `Email` varchar(50) NOT NULL default '',
  `Notes` text NOT NULL,
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

CREATE TABLE PHPA_ERROR_LOG_TABLE (
  `ID` bigint(20) unsigned NOT NULL auto_increment,
  `Severity` tinyint(4) NOT NULL default '0',
  `Message` text NOT NULL,
  `Filename` varchar(100) NOT NULL default '',
  `Line_Number` int(11) NOT NULL default '0',
  `Request_URI` varchar(255) NOT NULL default '',
  `Page` varchar(50) NOT NULL default '',
  `Action` varchar(50) NOT NULL default '',
  `User_ID` mediumint(8) unsigned NOT NULL default '0',
  `Timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

CREATE TABLE PHPA_IMAGE_TABLE (
  `Image_ID` mediumint(8) unsigned NOT NULL default '0',
  `Artist_ID` smallint(5) unsigned NOT NULL default '0',
  `Title` varchar(255) NOT NULL default '',
  `XY_Ratio` decimal(3,2) NOT NULL default '0.00',
  PRIMARY KEY  (`Image_ID`,`Artist_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE PHPA_INVOICE_MAIL_LOOKUP_TABLE (
  `Mail_ID` int(10) unsigned NOT NULL default '0',
  `Invoice_ID` int(10) unsigned NOT NULL default '0',
  `Type` enum('invoice','first_invoice_reminder','second_invoice_reminder','final_invoice_reminder') NOT NULL default 'invoice',
  PRIMARY KEY  (`Mail_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE PHPA_INVOICE_PAYMENT_TABLE (
  `ID` int(10) unsigned NOT NULL auto_increment,
  `Invoice_ID` int(10) unsigned NOT NULL default '0',
  `Timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `Payment_Method` enum('Cash','Credit Card','Cheque','Transfer','Other','PayPal') NOT NULL default 'Cash',
  `Value` decimal(7,2) NOT NULL default '0.00',
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

CREATE TABLE  PHPA_INVOICE_TABLE (
  `ID` int(10) unsigned NOT NULL auto_increment,
  `Client_ID` smallint(5) unsigned NOT NULL default '0',
  `Reference` varchar(20) NOT NULL default '0',
  `Date` date NOT NULL default '0000-00-00',
  `Description` text NOT NULL,
  `Value` decimal(7,2) NOT NULL default '0.00',
  `Invoice_Address` text NOT NULL,
  `Reminders` enum('no','yes') NOT NULL default 'no',
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

CREATE TABLE PHPA_LINK_TABLE (
  `Artist_ID` smallint(5) unsigned NOT NULL default '0',
  `Url` varchar(255) NOT NULL default '',
  `Title` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`Artist_ID`,`Url`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE PHPA_OUTGOING_PAYMENT_TABLE (
  `Outgoing_ID` int(10) unsigned NOT NULL default '0',
  `Timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `Payment_Method` enum('Cash','Credit Card','Cheque','Transfer','Direct Debit','Other') NOT NULL default 'Cash',
  `Value` decimal(7,2) NOT NULL default '0.00',
  PRIMARY KEY  (`Outgoing_ID`,`Timestamp`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE PHPA_OUTGOING_TYPE_TABLE (
  `ID` tinyint(3) unsigned NOT NULL auto_increment,
  `Outgoing_Type` varchar(30) NOT NULL default '',
  `User_ID` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

CREATE TABLE PHPA_OUTGOING_TABLE (
  `ID` int(10) unsigned NOT NULL auto_increment,
  `User_ID` int(10) unsigned NOT NULL default '0',
  `Vendor_ID` smallint(6) NOT NULL default '0',
  `Date` date NOT NULL default '0000-00-00',
  `Value` decimal(5,2) NOT NULL default '0.00',
  `Outgoing_Type_ID` int(6) unsigned default NULL,
  `Description` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

CREATE TABLE PHPA_PHPSESSION_TABLE (
  `PHPSESSID` varchar(32) NOT NULL default '',
  `IP` varchar(15) NOT NULL default '',
  `User_ID` mediumint(9) NOT NULL default '0',
  `Timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`PHPSESSID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE PHPA_PREFERENCES_TABLE (
  `User_ID` int(10) unsigned NOT NULL default '0',
  `Preference` varchar(255) NOT NULL default '',
  `Value` text NOT NULL,
  PRIMARY KEY  (`User_ID`,`Preference`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE PHPA_PROJECT_INVOICE_TABLE (
  `Project_ID` int(10) unsigned NOT NULL default '0',
  `Invoice_ID` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`Project_ID`,`Invoice_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE PHPA_PROJECT_TIMESHEET_TABLE (
  `Project_ID` int(10) unsigned NOT NULL default '0',
  `Timesheet_ID` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`Project_ID`,`Timesheet_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE PHPA_PROJECT_TABLE (
  `ID` int(10) unsigned NOT NULL auto_increment,
  `Client_ID` mediumint(8) unsigned NOT NULL default '0',
  `Title` varchar(255) NOT NULL default 'new project',
  `Date_Opened` date NOT NULL default '0000-00-00',
  `Date_Closed` date NOT NULL default '0000-00-00',
  `Description` text NOT NULL,
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

CREATE TABLE PHPA_QUOTE_TABLE (
  `ID` mediumint(8) unsigned NOT NULL auto_increment,
  `Client_ID` mediumint(8) unsigned NOT NULL default '0',
  `Title` varchar(255) NOT NULL default '',
  `Value` decimal(7,2) NOT NULL default '0.00',
  `Date` date NOT NULL default '0000-00-00',
  `Description` text NOT NULL,
  `Quote_Address` text NOT NULL,
  `Approved_Date` date NOT NULL default '0000-00-00',
  `Accepted_Name` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`ID`),
  KEY `Client_ID` (`Client_ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

CREATE TABLE PHPA_REPEAT_INVOICE_LOG_TABLE (
  `Repeat_Invoice_ID` int(10) unsigned NOT NULL default '0',
  `Invoice_ID` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`Repeat_Invoice_ID`,`Invoice_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE PHPA_REPEAT_INVOICE_TABLE (
  `Invoice_ID` int(10) unsigned NOT NULL default '0',
  `Active` enum('no','yes') NOT NULL default 'no',
  `Day` enum('1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31','*') NOT NULL default '1',
  `Month` enum('1','2','3','4','5','6','7','8','9','10','11','12','*') NOT NULL default '1',
  `Reminders` enum('no','yes') NOT NULL default 'no',
  PRIMARY KEY  (`Invoice_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE  PHPA_TIMESHEET_TABLE (
  `ID` int(10) unsigned NOT NULL auto_increment,
  `Client_ID` smallint(5) unsigned NOT NULL default '0',
  `Timestamp` datetime default '0000-00-00 00:00:00',
  `Time` time NOT NULL default '00:00:00',
  `Description` varchar(255) NOT NULL default '',
  `Value` decimal(5,2) NOT NULL default '0.00',
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

CREATE TABLE PHPA_USER_TABLE (
  `ID` mediumint(8) unsigned NOT NULL auto_increment,
  `Email` varchar(50) NOT NULL default '',
  `Password` varchar(64) NOT NULL default '',
  `First_Name` varchar(30) NOT NULL default '',
  `Surname` varchar(30) NOT NULL default '',
  `Company_Name` varchar(50) NOT NULL default '',
  `Address1` varchar(50) NOT NULL default '',
  `Address2` varchar(50) NOT NULL default '',
  `City` varchar(50) NOT NULL default '',
  `Region` varchar(50) NOT NULL default '',
  `Country` varchar(20) NOT NULL default '',
  `Postcode` varchar(12) NOT NULL default '',
  `Telephone` varchar(12) NOT NULL default '',
  `Fax` varchar(12) NOT NULL default '',
  `Website` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

CREATE TABLE PHPA_VENDOR_TABLE (
  `ID` smallint(5) unsigned NOT NULL auto_increment,
  `User_ID` int(11) unsigned NOT NULL default '0',
  `Company_Name` varchar(100) NOT NULL default '',
  `Contact_First_Name` varchar(30) NOT NULL default '',
  `Contact_First_Surname` varchar(30) NOT NULL default '',
  `Address1` varchar(50) NOT NULL default '',
  `Address2` varchar(50) NOT NULL default '',
  `City` varchar(30) NOT NULL default '',
  `Region` varchar(30) NOT NULL default '',
  `Country` varchar(30) NOT NULL default '',
  `Postcode` varchar(12) NOT NULL default '',
  `Telephone` varchar(15) NOT NULL default '',
  `Fax` varchar(15) NOT NULL default '',
  `Email` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`ID`),
  KEY `Company_Name` (`Company_Name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
