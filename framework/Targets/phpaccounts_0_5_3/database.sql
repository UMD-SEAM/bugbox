-- MySQL dump 10.13  Distrib 5.5.28, for debian-linux-gnu (i686)
--
-- Host: localhost    Database: phpaccounts_0_5_3
-- ------------------------------------------------------
-- Server version	5.5.28-1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Current Database: `phpaccounts_0_5_3`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `phpaccounts_0_5_3` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `phpaccounts_0_5_3`;

--
-- Table structure for table `PHPA_Client_Sent_Mail_tbl`
--

DROP TABLE IF EXISTS `PHPA_Client_Sent_Mail_tbl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PHPA_Client_Sent_Mail_tbl` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Client_ID` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Read` enum('no','yes') NOT NULL DEFAULT 'no',
  `Email` varchar(50) NOT NULL DEFAULT '',
  `Subject` varchar(50) NOT NULL DEFAULT '',
  `Timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Message` text NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `User_ID` (`Client_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `PHPA_Client_Sent_Mail_tbl`
--

LOCK TABLES `PHPA_Client_Sent_Mail_tbl` WRITE;
/*!40000 ALTER TABLE `PHPA_Client_Sent_Mail_tbl` DISABLE KEYS */;
/*!40000 ALTER TABLE `PHPA_Client_Sent_Mail_tbl` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `PHPA_Client_tbl`
--

DROP TABLE IF EXISTS `PHPA_Client_tbl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PHPA_Client_tbl` (
  `ID` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `User_ID` int(11) unsigned NOT NULL DEFAULT '0',
  `Company_Name` varchar(100) NOT NULL DEFAULT '',
  `Contact_First_Name` varchar(30) NOT NULL DEFAULT '',
  `Contact_Surname` varchar(30) NOT NULL DEFAULT '',
  `Address1` varchar(50) NOT NULL DEFAULT '',
  `Address2` varchar(50) NOT NULL DEFAULT '',
  `City` varchar(30) NOT NULL DEFAULT '',
  `Region` varchar(30) NOT NULL DEFAULT '',
  `Country` varchar(30) NOT NULL DEFAULT '',
  `Postcode` varchar(12) NOT NULL DEFAULT '',
  `Telephone` varchar(27) NOT NULL DEFAULT '',
  `Telephone2` varchar(20) NOT NULL DEFAULT '',
  `Mobile` varchar(20) NOT NULL DEFAULT '',
  `Fax` varchar(20) NOT NULL DEFAULT '',
  `Email` varchar(50) NOT NULL DEFAULT '',
  `Notes` text NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `PHPA_Client_tbl`
--

LOCK TABLES `PHPA_Client_tbl` WRITE;
/*!40000 ALTER TABLE `PHPA_Client_tbl` DISABLE KEYS */;
/*!40000 ALTER TABLE `PHPA_Client_tbl` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `PHPA_Error_Log_tbl`
--

DROP TABLE IF EXISTS `PHPA_Error_Log_tbl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PHPA_Error_Log_tbl` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `Severity` tinyint(4) NOT NULL DEFAULT '0',
  `Message` text NOT NULL,
  `Filename` varchar(100) NOT NULL DEFAULT '',
  `Line_Number` int(11) NOT NULL DEFAULT '0',
  `Request_URI` varchar(255) NOT NULL DEFAULT '',
  `Page` varchar(50) NOT NULL DEFAULT '',
  `Action` varchar(50) NOT NULL DEFAULT '',
  `User_ID` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `PHPA_Error_Log_tbl`
--

LOCK TABLES `PHPA_Error_Log_tbl` WRITE;
/*!40000 ALTER TABLE `PHPA_Error_Log_tbl` DISABLE KEYS */;
/*!40000 ALTER TABLE `PHPA_Error_Log_tbl` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `PHPA_Image_tbl`
--

DROP TABLE IF EXISTS `PHPA_Image_tbl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PHPA_Image_tbl` (
  `Image_ID` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Artist_ID` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Title` varchar(255) NOT NULL DEFAULT '',
  `XY_Ratio` decimal(3,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`Image_ID`,`Artist_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `PHPA_Image_tbl`
--

LOCK TABLES `PHPA_Image_tbl` WRITE;
/*!40000 ALTER TABLE `PHPA_Image_tbl` DISABLE KEYS */;
/*!40000 ALTER TABLE `PHPA_Image_tbl` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `PHPA_Invoice_Mail_Lookup_tbl`
--

DROP TABLE IF EXISTS `PHPA_Invoice_Mail_Lookup_tbl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PHPA_Invoice_Mail_Lookup_tbl` (
  `Mail_ID` int(10) unsigned NOT NULL DEFAULT '0',
  `Invoice_ID` int(10) unsigned NOT NULL DEFAULT '0',
  `Type` enum('invoice','first_invoice_reminder','second_invoice_reminder','final_invoice_reminder') NOT NULL DEFAULT 'invoice',
  PRIMARY KEY (`Mail_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `PHPA_Invoice_Mail_Lookup_tbl`
--

LOCK TABLES `PHPA_Invoice_Mail_Lookup_tbl` WRITE;
/*!40000 ALTER TABLE `PHPA_Invoice_Mail_Lookup_tbl` DISABLE KEYS */;
/*!40000 ALTER TABLE `PHPA_Invoice_Mail_Lookup_tbl` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `PHPA_Invoice_Payment_tbl`
--

DROP TABLE IF EXISTS `PHPA_Invoice_Payment_tbl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PHPA_Invoice_Payment_tbl` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Invoice_ID` int(10) unsigned NOT NULL DEFAULT '0',
  `Timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Payment_Method` enum('Cash','Credit Card','Cheque','Transfer','Other','PayPal') NOT NULL DEFAULT 'Cash',
  `Value` decimal(7,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `PHPA_Invoice_Payment_tbl`
--

LOCK TABLES `PHPA_Invoice_Payment_tbl` WRITE;
/*!40000 ALTER TABLE `PHPA_Invoice_Payment_tbl` DISABLE KEYS */;
/*!40000 ALTER TABLE `PHPA_Invoice_Payment_tbl` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `PHPA_Invoice_tbl`
--

DROP TABLE IF EXISTS `PHPA_Invoice_tbl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PHPA_Invoice_tbl` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Client_ID` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Reference` varchar(20) NOT NULL DEFAULT '0',
  `Date` date NOT NULL DEFAULT '0000-00-00',
  `Description` text NOT NULL,
  `Value` decimal(7,2) NOT NULL DEFAULT '0.00',
  `Invoice_Address` text NOT NULL,
  `Reminders` enum('no','yes') NOT NULL DEFAULT 'no',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `PHPA_Invoice_tbl`
--

LOCK TABLES `PHPA_Invoice_tbl` WRITE;
/*!40000 ALTER TABLE `PHPA_Invoice_tbl` DISABLE KEYS */;
/*!40000 ALTER TABLE `PHPA_Invoice_tbl` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `PHPA_Link_tbl`
--

DROP TABLE IF EXISTS `PHPA_Link_tbl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PHPA_Link_tbl` (
  `Artist_ID` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Url` varchar(255) NOT NULL DEFAULT '',
  `Title` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`Artist_ID`,`Url`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `PHPA_Link_tbl`
--

LOCK TABLES `PHPA_Link_tbl` WRITE;
/*!40000 ALTER TABLE `PHPA_Link_tbl` DISABLE KEYS */;
/*!40000 ALTER TABLE `PHPA_Link_tbl` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `PHPA_Outgoing_Payment_tbl`
--

DROP TABLE IF EXISTS `PHPA_Outgoing_Payment_tbl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PHPA_Outgoing_Payment_tbl` (
  `Outgoing_ID` int(10) unsigned NOT NULL DEFAULT '0',
  `Timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Payment_Method` enum('Cash','Credit Card','Cheque','Transfer','Direct Debit','Other') NOT NULL DEFAULT 'Cash',
  `Value` decimal(7,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`Outgoing_ID`,`Timestamp`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `PHPA_Outgoing_Payment_tbl`
--

LOCK TABLES `PHPA_Outgoing_Payment_tbl` WRITE;
/*!40000 ALTER TABLE `PHPA_Outgoing_Payment_tbl` DISABLE KEYS */;
/*!40000 ALTER TABLE `PHPA_Outgoing_Payment_tbl` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `PHPA_Outgoing_Type_tbl`
--

DROP TABLE IF EXISTS `PHPA_Outgoing_Type_tbl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PHPA_Outgoing_Type_tbl` (
  `ID` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `Outgoing_Type` varchar(30) NOT NULL DEFAULT '',
  `User_ID` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `PHPA_Outgoing_Type_tbl`
--

LOCK TABLES `PHPA_Outgoing_Type_tbl` WRITE;
/*!40000 ALTER TABLE `PHPA_Outgoing_Type_tbl` DISABLE KEYS */;
INSERT INTO `PHPA_Outgoing_Type_tbl` VALUES (1,'Capital Expenditure',1),(2,'IT Capital Expenditure',1),(3,'Employee costs',1),(4,'Premises costs',1),(5,'Repairs',1),(6,'General administrative expense',1),(7,'Motor expenses',1),(8,'Travel and subsistence',1),(9,'Advertising, promotion and ent',1),(10,'Legal and professional costs',1),(11,'Interest',1),(12,'Other finance charges',1),(13,'Depreciation and loss/(profit)',1),(14,'Other expenses',1);
/*!40000 ALTER TABLE `PHPA_Outgoing_Type_tbl` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `PHPA_Outgoing_tbl`
--

DROP TABLE IF EXISTS `PHPA_Outgoing_tbl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PHPA_Outgoing_tbl` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `User_ID` int(10) unsigned NOT NULL DEFAULT '0',
  `Vendor_ID` smallint(6) NOT NULL DEFAULT '0',
  `Date` date NOT NULL DEFAULT '0000-00-00',
  `Value` decimal(5,2) NOT NULL DEFAULT '0.00',
  `Outgoing_Type_ID` int(6) unsigned DEFAULT NULL,
  `Description` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `PHPA_Outgoing_tbl`
--

LOCK TABLES `PHPA_Outgoing_tbl` WRITE;
/*!40000 ALTER TABLE `PHPA_Outgoing_tbl` DISABLE KEYS */;
/*!40000 ALTER TABLE `PHPA_Outgoing_tbl` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `PHPA_PHPSESSION_tbl`
--

DROP TABLE IF EXISTS `PHPA_PHPSESSION_tbl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PHPA_PHPSESSION_tbl` (
  `PHPSESSID` varchar(32) NOT NULL DEFAULT '',
  `IP` varchar(15) NOT NULL DEFAULT '',
  `User_ID` mediumint(9) NOT NULL DEFAULT '0',
  `Timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`PHPSESSID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `PHPA_PHPSESSION_tbl`
--

LOCK TABLES `PHPA_PHPSESSION_tbl` WRITE;
/*!40000 ALTER TABLE `PHPA_PHPSESSION_tbl` DISABLE KEYS */;
INSERT INTO `PHPA_PHPSESSION_tbl` VALUES ('vf0579an3hvlndf2agclt52f63','69.140.15.240',1,'2013-07-11 02:07:43'),('70vhk4ohjne3ffcseo7742k045','69.140.15.240',1,'2013-07-11 02:09:40');
/*!40000 ALTER TABLE `PHPA_PHPSESSION_tbl` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `PHPA_Preferences_tbl`
--

DROP TABLE IF EXISTS `PHPA_Preferences_tbl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PHPA_Preferences_tbl` (
  `User_ID` int(10) unsigned NOT NULL DEFAULT '0',
  `Preference` varchar(255) NOT NULL DEFAULT '',
  `Value` text NOT NULL,
  PRIMARY KEY (`User_ID`,`Preference`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `PHPA_Preferences_tbl`
--

LOCK TABLES `PHPA_Preferences_tbl` WRITE;
/*!40000 ALTER TABLE `PHPA_Preferences_tbl` DISABLE KEYS */;
INSERT INTO `PHPA_Preferences_tbl` VALUES (1,'CURRENCY','USD'),(1,'AUTO_NUMBER_INVOICES','yes'),(1,'DAYS_TO_SEND_FIRST_REMINDER','30'),(1,'DAYS_TO_SEND_SECOND_REMINDER','37'),(1,'DAYS_TO_SEND_FINAL_REMINDER','44'),(1,'HOURLY_RATE','0'),(1,'LETTER_FONT','verdana'),(1,'LETTER_HEADER',' | phpaccounts@umd.edu |  | , , , , , '),(1,'LETTER_FOOTER','Registered Office: , , , , , '),(1,'INVOICE_THANKYOU','Please make cheques payable to \'UMD\'\nThank You for your business!'),(1,'QUOTE_TERMS','Payment Terms: Nett 30 days.\n Acceptance: Quotations are valid for 30 days from date of issue.\nTerms & Conditions: You must also agree to our standard terms & conditions, as published on our website.');
/*!40000 ALTER TABLE `PHPA_Preferences_tbl` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `PHPA_Project_Invoice_tbl`
--

DROP TABLE IF EXISTS `PHPA_Project_Invoice_tbl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PHPA_Project_Invoice_tbl` (
  `Project_ID` int(10) unsigned NOT NULL DEFAULT '0',
  `Invoice_ID` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`Project_ID`,`Invoice_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `PHPA_Project_Invoice_tbl`
--

LOCK TABLES `PHPA_Project_Invoice_tbl` WRITE;
/*!40000 ALTER TABLE `PHPA_Project_Invoice_tbl` DISABLE KEYS */;
/*!40000 ALTER TABLE `PHPA_Project_Invoice_tbl` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `PHPA_Project_Timesheet_tbl`
--

DROP TABLE IF EXISTS `PHPA_Project_Timesheet_tbl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PHPA_Project_Timesheet_tbl` (
  `Project_ID` int(10) unsigned NOT NULL DEFAULT '0',
  `Timesheet_ID` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`Project_ID`,`Timesheet_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `PHPA_Project_Timesheet_tbl`
--

LOCK TABLES `PHPA_Project_Timesheet_tbl` WRITE;
/*!40000 ALTER TABLE `PHPA_Project_Timesheet_tbl` DISABLE KEYS */;
/*!40000 ALTER TABLE `PHPA_Project_Timesheet_tbl` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `PHPA_Project_tbl`
--

DROP TABLE IF EXISTS `PHPA_Project_tbl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PHPA_Project_tbl` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Client_ID` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Title` varchar(255) NOT NULL DEFAULT 'new project',
  `Date_Opened` date NOT NULL DEFAULT '0000-00-00',
  `Date_Closed` date NOT NULL DEFAULT '0000-00-00',
  `Description` text NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `PHPA_Project_tbl`
--

LOCK TABLES `PHPA_Project_tbl` WRITE;
/*!40000 ALTER TABLE `PHPA_Project_tbl` DISABLE KEYS */;
/*!40000 ALTER TABLE `PHPA_Project_tbl` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `PHPA_Quote_tbl`
--

DROP TABLE IF EXISTS `PHPA_Quote_tbl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PHPA_Quote_tbl` (
  `ID` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `Client_ID` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `Title` varchar(255) NOT NULL DEFAULT '',
  `Value` decimal(7,2) NOT NULL DEFAULT '0.00',
  `Date` date NOT NULL DEFAULT '0000-00-00',
  `Description` text NOT NULL,
  `Quote_Address` text NOT NULL,
  `Approved_Date` date NOT NULL DEFAULT '0000-00-00',
  `Accepted_Name` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`ID`),
  KEY `Client_ID` (`Client_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `PHPA_Quote_tbl`
--

LOCK TABLES `PHPA_Quote_tbl` WRITE;
/*!40000 ALTER TABLE `PHPA_Quote_tbl` DISABLE KEYS */;
/*!40000 ALTER TABLE `PHPA_Quote_tbl` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `PHPA_Repeat_Invoice_Log_tbl`
--

DROP TABLE IF EXISTS `PHPA_Repeat_Invoice_Log_tbl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PHPA_Repeat_Invoice_Log_tbl` (
  `Repeat_Invoice_ID` int(10) unsigned NOT NULL DEFAULT '0',
  `Invoice_ID` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`Repeat_Invoice_ID`,`Invoice_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `PHPA_Repeat_Invoice_Log_tbl`
--

LOCK TABLES `PHPA_Repeat_Invoice_Log_tbl` WRITE;
/*!40000 ALTER TABLE `PHPA_Repeat_Invoice_Log_tbl` DISABLE KEYS */;
/*!40000 ALTER TABLE `PHPA_Repeat_Invoice_Log_tbl` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `PHPA_Repeat_Invoice_tbl`
--

DROP TABLE IF EXISTS `PHPA_Repeat_Invoice_tbl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PHPA_Repeat_Invoice_tbl` (
  `Invoice_ID` int(10) unsigned NOT NULL DEFAULT '0',
  `Active` enum('no','yes') NOT NULL DEFAULT 'no',
  `Day` enum('1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31','*') NOT NULL DEFAULT '1',
  `Month` enum('1','2','3','4','5','6','7','8','9','10','11','12','*') NOT NULL DEFAULT '1',
  `Reminders` enum('no','yes') NOT NULL DEFAULT 'no',
  PRIMARY KEY (`Invoice_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `PHPA_Repeat_Invoice_tbl`
--

LOCK TABLES `PHPA_Repeat_Invoice_tbl` WRITE;
/*!40000 ALTER TABLE `PHPA_Repeat_Invoice_tbl` DISABLE KEYS */;
/*!40000 ALTER TABLE `PHPA_Repeat_Invoice_tbl` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `PHPA_Timesheet_tbl`
--

DROP TABLE IF EXISTS `PHPA_Timesheet_tbl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PHPA_Timesheet_tbl` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Client_ID` smallint(5) unsigned NOT NULL DEFAULT '0',
  `Timestamp` datetime DEFAULT '0000-00-00 00:00:00',
  `Time` time NOT NULL DEFAULT '00:00:00',
  `Description` varchar(255) NOT NULL DEFAULT '',
  `Value` decimal(5,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `PHPA_Timesheet_tbl`
--

LOCK TABLES `PHPA_Timesheet_tbl` WRITE;
/*!40000 ALTER TABLE `PHPA_Timesheet_tbl` DISABLE KEYS */;
/*!40000 ALTER TABLE `PHPA_Timesheet_tbl` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `PHPA_User_tbl`
--

DROP TABLE IF EXISTS `PHPA_User_tbl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PHPA_User_tbl` (
  `ID` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `Email` varchar(50) NOT NULL DEFAULT '',
  `Password` varchar(64) NOT NULL DEFAULT '',
  `First_Name` varchar(30) NOT NULL DEFAULT '',
  `Surname` varchar(30) NOT NULL DEFAULT '',
  `Company_Name` varchar(50) NOT NULL DEFAULT '',
  `Address1` varchar(50) NOT NULL DEFAULT '',
  `Address2` varchar(50) NOT NULL DEFAULT '',
  `City` varchar(50) NOT NULL DEFAULT '',
  `Region` varchar(50) NOT NULL DEFAULT '',
  `Country` varchar(20) NOT NULL DEFAULT '',
  `Postcode` varchar(12) NOT NULL DEFAULT '',
  `Telephone` varchar(12) NOT NULL DEFAULT '',
  `Fax` varchar(12) NOT NULL DEFAULT '',
  `Website` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `PHPA_User_tbl`
--

LOCK TABLES `PHPA_User_tbl` WRITE;
/*!40000 ALTER TABLE `PHPA_User_tbl` DISABLE KEYS */;
INSERT INTO `PHPA_User_tbl` VALUES (1,'phpaccounts@umd.edu','phpaccountspw21','security','tester','UMD','','','','','','','','','');
/*!40000 ALTER TABLE `PHPA_User_tbl` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `PHPA_Vendor_tbl`
--

DROP TABLE IF EXISTS `PHPA_Vendor_tbl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PHPA_Vendor_tbl` (
  `ID` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `User_ID` int(11) unsigned NOT NULL DEFAULT '0',
  `Company_Name` varchar(100) NOT NULL DEFAULT '',
  `Contact_First_Name` varchar(30) NOT NULL DEFAULT '',
  `Contact_First_Surname` varchar(30) NOT NULL DEFAULT '',
  `Address1` varchar(50) NOT NULL DEFAULT '',
  `Address2` varchar(50) NOT NULL DEFAULT '',
  `City` varchar(30) NOT NULL DEFAULT '',
  `Region` varchar(30) NOT NULL DEFAULT '',
  `Country` varchar(30) NOT NULL DEFAULT '',
  `Postcode` varchar(12) NOT NULL DEFAULT '',
  `Telephone` varchar(15) NOT NULL DEFAULT '',
  `Fax` varchar(15) NOT NULL DEFAULT '',
  `Email` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`ID`),
  KEY `Company_Name` (`Company_Name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `PHPA_Vendor_tbl`
--

LOCK TABLES `PHPA_Vendor_tbl` WRITE;
/*!40000 ALTER TABLE `PHPA_Vendor_tbl` DISABLE KEYS */;
/*!40000 ALTER TABLE `PHPA_Vendor_tbl` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2013-07-10 22:10:37
