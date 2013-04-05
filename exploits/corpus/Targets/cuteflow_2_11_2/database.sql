-- MySQL dump 10.13  Distrib 5.5.28, for debian-linux-gnu (i686)
--
-- Host: localhost    Database: cuteflow_2_11_2
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
-- Table structure for table `cf_additional_text`
--

DROP TABLE IF EXISTS `cf_additional_text`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cf_additional_text` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  `content` text NOT NULL,
  `is_default` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cf_additional_text`
--

LOCK TABLES `cf_additional_text` WRITE;
/*!40000 ALTER TABLE `cf_additional_text` DISABLE KEYS */;
/*!40000 ALTER TABLE `cf_additional_text` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cf_attachment`
--

DROP TABLE IF EXISTS `cf_attachment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cf_attachment` (
  `nID` int(11) NOT NULL AUTO_INCREMENT,
  `strPath` text NOT NULL,
  `nCirculationHistoryId` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`nID`),
  UNIQUE KEY `nID` (`nID`),
  KEY `nID_2` (`nID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cf_attachment`
--

LOCK TABLES `cf_attachment` WRITE;
/*!40000 ALTER TABLE `cf_attachment` DISABLE KEYS */;
/*!40000 ALTER TABLE `cf_attachment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cf_circulationform`
--

DROP TABLE IF EXISTS `cf_circulationform`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cf_circulationform` (
  `nID` int(11) NOT NULL AUTO_INCREMENT,
  `nSenderId` int(11) NOT NULL DEFAULT '0',
  `strName` text NOT NULL,
  `nMailingListId` int(11) NOT NULL DEFAULT '0',
  `bIsArchived` tinyint(4) NOT NULL DEFAULT '0',
  `nEndAction` tinyint(4) NOT NULL DEFAULT '0',
  `bDeleted` int(11) NOT NULL,
  `bAnonymize` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`nID`),
  UNIQUE KEY `nID` (`nID`),
  KEY `nID_2` (`nID`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cf_circulationform`
--

LOCK TABLES `cf_circulationform` WRITE;
/*!40000 ALTER TABLE `cf_circulationform` DISABLE KEYS */;
INSERT INTO `cf_circulationform` VALUES (10,1,'My First Circulation No1',1,0,3,0,0),(11,1,'My First Circulation No2',2,0,3,0,0),(12,1,'My First Circulation No333',3,0,3,0,0),(13,1,'Checkbox Testing',2,1,3,0,0),(14,1,'Product 200 Test No1',4,0,3,0,0);
/*!40000 ALTER TABLE `cf_circulationform` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cf_circulationhistory`
--

DROP TABLE IF EXISTS `cf_circulationhistory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cf_circulationhistory` (
  `nID` int(11) NOT NULL AUTO_INCREMENT,
  `nRevisionNumber` int(11) NOT NULL DEFAULT '0',
  `dateSending` int(15) NOT NULL DEFAULT '0',
  `strAdditionalText` text NOT NULL,
  `nCirculationFormId` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`nID`)
) ENGINE=MyISAM AUTO_INCREMENT=33 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cf_circulationhistory`
--

LOCK TABLES `cf_circulationhistory` WRITE;
/*!40000 ALTER TABLE `cf_circulationhistory` DISABLE KEYS */;
INSERT INTO `cf_circulationhistory` VALUES (21,3,1180007140,'',10),(11,1,1180003974,'Send Date: 2007-05-24',10),(20,2,1180007130,'',10),(22,4,1180007206,'',10),(23,5,1180009391,'',10),(24,1,1180013225,'2007-05-24',11),(25,1,1180013299,'Test',12),(26,6,1180013536,'',10),(27,1,1180017037,'',13),(28,2,1180018130,'',13),(29,3,1180018277,'',13),(30,4,1180018395,'',13),(31,5,1180018556,'',13),(32,1,1180076469,'',14);
/*!40000 ALTER TABLE `cf_circulationhistory` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cf_circulationprocess`
--

DROP TABLE IF EXISTS `cf_circulationprocess`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cf_circulationprocess` (
  `nID` int(11) NOT NULL AUTO_INCREMENT,
  `nCirculationFormId` int(11) NOT NULL DEFAULT '0',
  `nSlotId` int(11) NOT NULL DEFAULT '0',
  `nUserId` int(11) NOT NULL DEFAULT '0',
  `dateInProcessSince` int(15) NOT NULL DEFAULT '0',
  `nDecissionState` tinyint(4) NOT NULL DEFAULT '0',
  `dateDecission` int(15) NOT NULL DEFAULT '0',
  `nIsSubstitiuteOf` int(11) NOT NULL DEFAULT '0',
  `nCirculationHistoryId` int(11) NOT NULL DEFAULT '0',
  `nResendCount` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`nID`),
  KEY `nCirculationFormId` (`nCirculationFormId`),
  KEY `nSlotId` (`nSlotId`),
  KEY `nUserId` (`nUserId`),
  KEY `nCirculationHistoryId` (`nCirculationHistoryId`),
  KEY `dateDecission` (`dateDecission`),
  KEY `dateInProcessSince` (`dateInProcessSince`),
  KEY `nDecissionState` (`nDecissionState`),
  KEY `nIsSubstitiuteOf` (`nIsSubstitiuteOf`)
) ENGINE=MyISAM AUTO_INCREMENT=199 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cf_circulationprocess`
--

LOCK TABLES `cf_circulationprocess` WRITE;
/*!40000 ALTER TABLE `cf_circulationprocess` DISABLE KEYS */;
INSERT INTO `cf_circulationprocess` VALUES (110,10,3,25,1180000130,4,1180000150,0,20,0),(107,10,2,27,1179999980,4,1180000000,0,20,0),(108,10,2,2,1180000030,4,1180000050,0,20,0),(109,10,2,1,1180000080,4,1180000100,0,20,0),(69,10,2,1,1180004063,16,0,0,11,0),(68,10,2,2,1180004048,1,1180004063,0,11,0),(67,10,2,32,1180004024,1,1180004048,66,11,0),(66,10,2,27,1180003993,8,1180004024,0,11,0),(65,10,2,13,1180003974,1,1180003993,0,11,0),(111,10,3,21,1180007130,16,0,0,20,0),(106,10,2,13,1179999930,4,1179999950,0,20,0),(113,10,2,27,1179999990,4,1180000010,0,21,0),(114,10,2,2,1180007140,1,1180007190,0,21,0),(112,10,2,13,1179999940,4,1179999960,0,21,0),(115,10,2,1,1180007190,16,0,0,21,0),(116,10,2,13,1180000006,4,1180000026,0,22,0),(117,10,2,27,1180000056,4,1180000076,0,22,0),(118,10,2,2,1180000106,4,1180000126,0,22,0),(119,10,2,1,1180000156,4,1180000176,0,22,0),(120,10,3,25,1180000206,4,1180000226,0,22,0),(121,10,3,21,1180000256,4,1180000276,0,22,0),(122,10,4,1,1180000306,4,1180000326,0,22,0),(123,10,4,6,1180000356,4,1180000376,0,22,0),(124,10,4,29,1180007206,16,0,0,22,0),(125,10,2,13,1180002191,4,1180002211,0,23,0),(126,10,2,27,1180002241,4,1180002261,0,23,0),(127,10,2,2,1180002291,4,1180002311,0,23,0),(128,10,2,1,1180002341,4,1180002361,0,23,0),(129,10,3,25,1180002391,4,1180002411,0,23,0),(130,10,3,21,1180002441,4,1180002461,0,23,0),(131,10,4,1,1180002491,4,1180002511,0,23,0),(132,10,4,6,1180002541,4,1180002561,0,23,0),(133,10,4,29,1180009391,16,0,0,23,0),(134,11,2,13,1180013225,4,1180013179,0,24,0),(135,11,2,12,1180013239,4,1180013241,0,24,0),(136,11,2,-2,1180013240,4,1180013242,0,24,0),(137,11,3,20,1180013241,0,0,0,24,0),(138,12,2,13,1180013299,1,1180013327,0,25,0),(139,12,2,12,1180013327,1,1180013341,0,25,0),(140,12,3,20,1180013341,1,1180016369,0,25,0),(141,10,2,13,1180006336,4,1180006356,0,26,0),(142,10,2,27,1180006386,4,1180006406,0,26,0),(143,10,2,2,1180006436,4,1180006456,0,26,0),(144,10,2,1,1180006486,4,1180006506,0,26,0),(145,10,3,25,1180006536,4,1180006556,0,26,0),(146,10,3,21,1180006586,4,1180006606,0,26,0),(147,10,4,1,1180006636,4,1180006656,0,26,0),(148,10,4,6,1180006686,4,1180006706,0,26,0),(149,10,4,29,1180013536,0,0,0,26,0),(150,12,3,7,1180016369,0,0,0,25,0),(151,13,2,13,1180017037,1,1180017054,0,27,0),(152,13,2,12,1180017054,1,1180017066,0,27,0),(153,13,2,1,1180017066,1,1180017075,0,27,0),(154,13,3,20,1180017075,1,1180017604,0,27,0),(155,13,3,7,1180017604,16,0,0,27,0),(156,13,2,13,1180018130,4,1180018084,0,28,0),(157,13,2,12,1180018144,4,1180018146,0,28,0),(158,13,2,-2,1180018145,1,1180018151,0,28,0),(159,13,3,20,1180018151,1,1180018167,0,28,0),(160,13,3,7,1180018167,16,0,0,28,0),(161,13,2,13,1180018277,4,1180018225,0,29,0),(162,13,2,12,1180018285,4,1180018287,0,29,0),(163,13,2,-2,1180018286,1,1180018291,0,29,0),(164,13,3,20,1180018291,1,1180018303,0,29,0),(165,13,3,7,1180018303,16,0,0,29,0),(166,13,2,13,1180018395,4,1180018345,0,30,0),(167,13,2,12,1180018405,4,1180018407,0,30,0),(168,13,2,-2,1180018406,1,1180018412,0,30,0),(169,13,3,20,1180018412,1,1180018425,0,30,0),(170,13,3,7,1180018425,1,1180018435,0,30,0),(171,13,3,29,1180018435,1,1180018444,0,30,0),(172,13,4,1,1180018444,1,1180018464,0,30,0),(173,13,5,31,1180018464,1,1180018472,0,30,0),(174,13,5,10,1180018472,1,1180018480,0,30,0),(175,13,5,21,1180018480,1,1180018522,0,30,0),(176,13,5,5,1180018522,2,1180018537,0,30,0),(177,13,2,13,1180011356,4,1180011376,0,31,0),(178,13,2,12,1180011406,4,1180011426,0,31,0),(179,13,2,1,1180011456,4,1180011476,0,31,0),(180,13,3,20,1180011506,4,1180011526,0,31,0),(181,13,3,7,1180011556,4,1180011576,0,31,0),(182,13,3,29,1180011606,4,1180011626,0,31,0),(183,13,4,1,1180011656,4,1180011676,0,31,0),(184,13,5,31,1180011706,4,1180011726,0,31,0),(185,13,5,10,1180011756,4,1180011776,0,31,0),(186,13,5,21,1180011806,4,1180011826,0,31,0),(188,13,5,5,1180018654,1,1180018669,0,31,0),(189,14,6,24,1180076469,1,1180076528,0,32,0),(193,14,6,13,1180076737,8,1180076800,0,32,0),(195,14,6,29,1180077119,1,1180077152,194,32,0),(194,14,6,27,1180076800,8,1180077119,193,32,0),(196,14,6,28,1180077152,1,1180077165,0,32,0),(197,14,6,27,1180077165,1,1180077178,0,32,0),(198,14,7,30,1180077178,0,0,0,32,0);
/*!40000 ALTER TABLE `cf_circulationprocess` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cf_config`
--

DROP TABLE IF EXISTS `cf_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cf_config` (
  `strCF_Server` text NOT NULL,
  `strSMTP_use_auth` text NOT NULL,
  `strSMTP_server` text NOT NULL,
  `strSMTP_port` varchar(8) NOT NULL DEFAULT '',
  `strSMTP_userid` text NOT NULL,
  `strSMTP_pwd` tinytext NOT NULL,
  `strSysReplyAddr` text NOT NULL,
  `strMailAddTextDef` text NOT NULL,
  `strDefLang` char(3) NOT NULL DEFAULT 'en',
  `bDetailSeperateWindow` varchar(5) NOT NULL DEFAULT 'true',
  `strDefSortCol` varchar(32) NOT NULL DEFAULT 'COL_CIRCULATION_NAME',
  `bShowPosMail` varchar(5) NOT NULL DEFAULT 'true',
  `bFilter_AR_Wordstart` varchar(5) NOT NULL DEFAULT 'true',
  `strCirculation_cols` varchar(255) NOT NULL DEFAULT '12345',
  `nDelay_norm` int(11) NOT NULL DEFAULT '7',
  `nDelay_interm` int(11) NOT NULL DEFAULT '10',
  `nDelay_late` int(11) NOT NULL DEFAULT '12',
  `strEmail_Format` varchar(8) NOT NULL DEFAULT 'HTML',
  `strEmail_Values` varchar(8) NOT NULL DEFAULT 'IFRAME',
  `nSubstitutePerson_Hours` int(11) NOT NULL DEFAULT '96',
  `strSubstitutePerson_Unit` text NOT NULL,
  `nConfigID` int(11) NOT NULL DEFAULT '0',
  `strSortDirection` text NOT NULL,
  `strVersion` text NOT NULL,
  `nShowRows` int(11) DEFAULT NULL,
  `nAutoReload` int(11) NOT NULL DEFAULT '0',
  `strUrlPassword` text NOT NULL,
  `tsLastUpdate` int(11) NOT NULL,
  `bAllowUnencryptedRequest` int(11) NOT NULL,
  `UserDefined1_Title` text NOT NULL,
  `UserDefined2_Title` text NOT NULL,
  `strDateFormat` tinytext NOT NULL,
  `strMailSendType` text NOT NULL,
  `strMtaPath` text NOT NULL,
  `strSlotVisibility` varchar(100) NOT NULL,
  `strSmtpEncryption` varchar(100) NOT NULL,
  `bSendWorkflowMail` int(11) NOT NULL,
  `bSendReminderMail` int(11) NOT NULL,
  PRIMARY KEY (`nConfigID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cf_config`
--

LOCK TABLES `cf_config` WRITE;
/*!40000 ALTER TABLE `cf_config` DISABLE KEYS */;
INSERT INTO `cf_config` VALUES ('http://128.8.127.115/cuteflow','','','25','','','cuteflow@localhost.de','','en','true','COL_CIRCULATION_PROCESS_DAYS','true','true','NAME---1---STATION---1---DAYS---1---START---1---SENDER---1---WHOLETIME---0---MAILLIST---0---TEMPLATE---0',7,10,12,'HTML','IFRAME',1,'DAYS',1,'ASC','2.11.2',50,60,'b29be15ab02bd20badf254b2f97035e5',1363662098,0,'user-defined1','user-defined2','m-d-Y','PHP','','ALL','NONE',1,0);
/*!40000 ALTER TABLE `cf_config` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cf_fieldvalue`
--

DROP TABLE IF EXISTS `cf_fieldvalue`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cf_fieldvalue` (
  `nID` int(11) NOT NULL AUTO_INCREMENT,
  `nInputFieldId` int(11) NOT NULL DEFAULT '0',
  `strFieldValue` text NOT NULL,
  `nSlotId` int(11) NOT NULL DEFAULT '0',
  `nFormId` int(11) NOT NULL DEFAULT '0',
  `nCirculationHistoryId` int(11) DEFAULT NULL,
  PRIMARY KEY (`nID`),
  UNIQUE KEY `nID` (`nID`),
  KEY `nID_2` (`nID`),
  KEY `nInputFieldId` (`nInputFieldId`),
  KEY `nSlotId` (`nSlotId`),
  KEY `nFormId` (`nFormId`),
  KEY `nCirculationHistoryId` (`nCirculationHistoryId`)
) ENGINE=MyISAM AUTO_INCREMENT=471 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cf_fieldvalue`
--

LOCK TABLES `cf_fieldvalue` WRITE;
/*!40000 ALTER TABLE `cf_fieldvalue` DISABLE KEYS */;
INSERT INTO `cf_fieldvalue` VALUES (369,9,'',4,11,24),(370,4,'xx3xx2004-09-11',5,11,24),(365,2,'0',3,11,24),(366,8,'0---0---0---1---0---',3,11,24),(368,7,'0---0---1---1---0---0---1---0---',4,11,24),(367,9,'',3,11,24),(363,2,'0',2,11,24),(364,4,'xx3xx2004-09-11',2,11,24),(362,5,'nOnSeNs NoNsEnS nOnSeNs NoNsEnS nOnSeNs NoNsEnSnOnSeNsNoNsEnS nOnSeNs NoNsEnS nOnSeNs NoNsEnS',2,11,24),(361,6,'0---0---1---0---',2,11,24),(360,2,'0',5,10,23),(359,1,'default value',5,10,23),(358,4,'xx3xx2004-09-11',5,10,23),(357,9,'',4,10,23),(144,2,'',5,10,11),(143,1,'default value',5,10,11),(142,4,'xx3xx2004-09-11',5,10,11),(141,9,'',4,10,11),(140,7,'0---0---1---1---0---0---1---0---',4,10,11),(138,8,'0---0---0---1---0---',3,10,11),(139,9,'',3,10,11),(137,2,'',3,10,11),(136,4,'xx3xx2004-09-22',2,10,11),(135,2,'',2,10,11),(355,9,'',3,10,23),(356,7,'0---0---1---1---0---0---1---0---',4,10,23),(134,5,'ZwO',2,10,11),(133,6,'0---1---0---0---',2,10,11),(353,2,'0',3,10,23),(354,8,'0---0---0---1---0---',3,10,23),(352,4,'xx3xx2004-09-11',2,10,23),(351,2,'0',2,10,23),(350,5,'nOnSeNs NoNsEnS nOnSeNs NoNsEnS nOnSeNs NoNsEnSnOnSeNsNoNsEnS nOnSeNs NoNsEnS nOnSeNs NoNsEnS',2,10,23),(349,6,'0---0---1---0---',2,10,23),(348,6,'0---0---0---1---',2,10,22),(347,2,'on',2,10,22),(346,4,'xx3xx2004-09-24',2,10,22),(345,5,'ZwO Drei',2,10,22),(344,2,'',3,10,22),(342,9,'',3,10,22),(343,8,'0---0---0---1---0---',3,10,22),(341,9,'',4,10,22),(340,7,'0---0---1---1---0---0---1---0---',4,10,22),(339,4,'xx3xx2004-09-11',5,10,22),(338,1,'default value',5,10,22),(336,2,'',5,10,21),(337,2,'',5,10,22),(335,1,'default value',5,10,21),(334,4,'xx3xx2004-09-11',5,10,21),(332,7,'0---0---1---1---0---0---1---0---',4,10,21),(333,9,'',4,10,21),(330,9,'',3,10,21),(331,8,'0---0---0---1---0---',3,10,21),(329,2,'',3,10,21),(326,5,'ZwO Drei',2,10,21),(327,4,'xx3xx2004-09-24',2,10,21),(328,2,'on',2,10,21),(325,6,'0---0---0---1---',2,10,21),(324,6,'0---1---0---0---',2,10,20),(323,5,'ZwO',2,10,20),(321,4,'xx3xx2004-09-22',2,10,20),(322,2,'',2,10,20),(320,2,'',3,10,20),(319,9,'',3,10,20),(318,8,'0---0---0---1---0---',3,10,20),(317,7,'0---0---1---1---0---0---1---0---',4,10,20),(316,9,'',4,10,20),(315,4,'xx3xx2004-09-11',5,10,20),(314,1,'default value',5,10,20),(313,2,'',5,10,20),(371,1,'default value',5,11,24),(372,2,'0',5,11,24),(373,6,'0---0---1---0---',2,12,25),(374,5,'OnE11',2,12,25),(375,2,'',2,12,25),(376,4,'xx3xx2004-01-11',2,12,25),(377,2,'on',3,12,25),(378,8,'---5---Option No1---1---Option No2---0---Option No3---0---Option No4 (default)---0---Option No5---0',3,12,25),(379,9,'---1---3_12_25---CuteFlow_bin_v250.ziprrrrr',3,12,25),(380,7,'---8---Checkbox - No1---1---Checkbox - No2---0---Checkbox - No3 (default)---0---Checkbox - No4 (default)---0---Checkbox - No5---0---Checkbox - No6---0---Checkbox - No7 (default)---0---Checkbox - No8---0',4,12,25),(381,9,'',4,12,25),(382,4,'xx3xx2004-09-01',5,12,25),(383,1,'default value one',5,12,25),(384,2,'',5,12,25),(385,2,'0',5,10,26),(386,1,'default value',5,10,26),(387,4,'xx3xx2004-09-11',5,10,26),(388,9,'',4,10,26),(389,9,'',3,10,26),(390,7,'0---0---1---1---0---0---1---0---',4,10,26),(391,2,'0',3,10,26),(392,8,'0---0---0---1---0---',3,10,26),(393,4,'xx3xx2004-09-11',2,10,26),(394,2,'0',2,10,26),(395,5,'nOnSeNs NoNsEnS nOnSeNs NoNsEnS nOnSeNs NoNsEnSnOnSeNsNoNsEnS nOnSeNs NoNsEnS nOnSeNs NoNsEnS',2,10,26),(396,6,'0---0---1---0---',2,10,26),(397,6,'0---0---1---0---',2,13,27),(398,5,'nOnSeNs NoNsEnS nOnSeNs NoNsEnS nOnSeNs NoNsEnSnOnSeNsNoNsEnS nOnSeNs NoNsEnS nOnSeNs NoNsEnS',2,13,27),(399,2,'',2,13,27),(400,4,'xx3xx2004-09-11',2,13,27),(401,2,'on',3,13,27),(402,8,'0---0---0---1---0---',3,13,27),(403,9,'',3,13,27),(404,7,'0---0---1---1---0---0---1---0---',4,13,27),(405,9,'',4,13,27),(406,4,'xx3xx2004-09-11',5,13,27),(407,1,'default value',5,13,27),(408,2,'',5,13,27),(409,6,'0---0---1---0---',2,13,28),(410,5,'nOnSeNs NoNsEnS nOnSeNs NoNsEnS nOnSeNs NoNsEnSnOnSeNsNoNsEnS nOnSeNs NoNsEnS nOnSeNs NoNsEnS',2,13,28),(411,2,'',2,13,28),(412,4,'xx3xx2004-09-11',2,13,28),(413,2,'',3,13,28),(414,8,'0---0---0---1---0---',3,13,28),(415,9,'',3,13,28),(416,7,'0---0---1---1---0---0---1---0---',4,13,28),(417,9,'',4,13,28),(418,4,'xx3xx2004-09-11',5,13,28),(419,1,'default value',5,13,28),(420,2,'',5,13,28),(421,6,'0---0---1---0---',2,13,29),(422,5,'nOnSeNs NoNsEnS nOnSeNs NoNsEnS nOnSeNs NoNsEnSnOnSeNsNoNsEnS nOnSeNs NoNsEnS nOnSeNs NoNsEnS',2,13,29),(423,2,'',2,13,29),(424,4,'xx3xx2004-09-11',2,13,29),(425,2,'',3,13,29),(426,8,'0---0---0---1---0---',3,13,29),(427,9,'',3,13,29),(428,7,'0---0---1---1---0---0---1---0---',4,13,29),(429,9,'',4,13,29),(430,4,'xx3xx2004-09-11',5,13,29),(431,1,'default value',5,13,29),(432,2,'',5,13,29),(433,6,'0---0---1---0---',2,13,30),(434,5,'nOnSeNs NoNsEnS nOnSeNs NoNsEnS nOnSeNs NoNsEnSnOnSeNsNoNsEnS nOnSeNs NoNsEnS nOnSeNs NoNsEnS',2,13,30),(435,2,'on',2,13,30),(436,4,'xx3xx2004-09-11',2,13,30),(437,2,'',3,13,30),(438,8,'0---0---0---1---0---',3,13,30),(439,9,'',3,13,30),(440,7,'0---1---1---1---1---1---1---0---',4,13,30),(441,9,'',4,13,30),(442,4,'xx3xx2004-09-11',5,13,30),(443,1,'default value',5,13,30),(444,2,'on',5,13,30),(445,6,'0---0---1---0---',2,13,31),(446,5,'nOnSeNs NoNsEnS nOnSeNs NoNsEnS nOnSeNs NoNsEnSnOnSeNsNoNsEnS nOnSeNs NoNsEnS nOnSeNs NoNsEnS',2,13,31),(447,2,'on',2,13,31),(448,4,'xx3xx2004-09-11',2,13,31),(449,2,'',3,13,31),(450,8,'0---0---0---1---0---',3,13,31),(451,9,'',3,13,31),(452,7,'0---1---1---1---1---1---1---0---',4,13,31),(453,9,'',4,13,31),(454,4,'xx3xx2004-09-11',5,13,31),(455,1,'default value',5,13,31),(456,2,'',5,13,31),(457,8,'0---0---0---1---0---',6,14,32),(458,7,'1---1---0---0---1---0---0---0---',6,14,32),(459,2,'on',6,14,32),(460,5,'nOnSeNs NoNsEnS nOnSeNs NoNsEnS nOnSeNs NoNsEnSnOnSeNsNoNsEnS nOnSeNs NoNsEnS nOnSeNs NoNsEnS',7,14,32),(461,1,'default value',7,14,32),(462,6,'0---0---1---0---',7,14,32),(463,3,'xx1xx1337',7,14,32),(464,9,'',7,14,32),(465,4,'xx3xx2004-09-11',7,14,32),(466,1,'default value',8,14,32),(467,2,'',8,14,32),(468,6,'0---0---1---0---',9,14,32),(469,1,'default value',9,14,32),(470,5,'nOnSeNs NoNsEnS nOnSeNs NoNsEnS nOnSeNs NoNsEnSnOnSeNsNoNsEnS nOnSeNs NoNsEnS nOnSeNs NoNsEnS',9,14,32);
/*!40000 ALTER TABLE `cf_fieldvalue` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cf_filter`
--

DROP TABLE IF EXISTS `cf_filter`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cf_filter` (
  `nID` int(11) NOT NULL AUTO_INCREMENT,
  `nUserID` int(11) NOT NULL DEFAULT '0',
  `strLabel` text NOT NULL,
  `strName` text NOT NULL,
  `nStationID` int(11) NOT NULL DEFAULT '0',
  `nDaysInProgress_Start` text NOT NULL,
  `nDaysInProgress_End` text NOT NULL,
  `strSendDate_Start` text NOT NULL,
  `strSendDate_End` text NOT NULL,
  `nMailinglistID` int(11) NOT NULL DEFAULT '0',
  `nTemplateID` int(11) NOT NULL DEFAULT '0',
  `strCustomFilter` text NOT NULL,
  `nSenderID` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`nID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cf_filter`
--

LOCK TABLES `cf_filter` WRITE;
/*!40000 ALTER TABLE `cf_filter` DISABLE KEYS */;
/*!40000 ALTER TABLE `cf_filter` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cf_formslot`
--

DROP TABLE IF EXISTS `cf_formslot`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cf_formslot` (
  `nID` int(11) NOT NULL AUTO_INCREMENT,
  `strName` tinytext NOT NULL,
  `nTemplateId` int(11) NOT NULL DEFAULT '0',
  `nSlotNumber` int(11) NOT NULL DEFAULT '0',
  `nSendType` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`nID`),
  UNIQUE KEY `nID` (`nID`),
  KEY `nID_2` (`nID`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cf_formslot`
--

LOCK TABLES `cf_formslot` WRITE;
/*!40000 ALTER TABLE `cf_formslot` DISABLE KEYS */;
INSERT INTO `cf_formslot` VALUES (2,'Slot No1',3,1,0),(3,'Slot No2',3,2,0),(4,'Slot No3',3,3,0),(5,'Slot No4',3,4,0),(6,'technology',4,1,0),(7,'purchasing',4,2,0),(8,'marketing',4,3,0),(9,'accounting',4,4,0);
/*!40000 ALTER TABLE `cf_formslot` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cf_formtemplate`
--

DROP TABLE IF EXISTS `cf_formtemplate`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cf_formtemplate` (
  `nID` int(11) NOT NULL AUTO_INCREMENT,
  `strName` tinytext NOT NULL,
  `bDeleted` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`nID`),
  UNIQUE KEY `nID` (`nID`),
  KEY `nID_2` (`nID`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cf_formtemplate`
--

LOCK TABLES `cf_formtemplate` WRITE;
/*!40000 ALTER TABLE `cf_formtemplate` DISABLE KEYS */;
INSERT INTO `cf_formtemplate` VALUES (4,'Product 2000',0),(3,'Template - Test No1',0);
/*!40000 ALTER TABLE `cf_formtemplate` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cf_inputfield`
--

DROP TABLE IF EXISTS `cf_inputfield`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cf_inputfield` (
  `nID` int(11) NOT NULL AUTO_INCREMENT,
  `strName` tinytext NOT NULL,
  `nType` int(11) NOT NULL DEFAULT '0',
  `strStandardValue` text NOT NULL,
  `bReadOnly` tinyint(4) NOT NULL DEFAULT '0',
  `strBgColor` tinytext NOT NULL,
  PRIMARY KEY (`nID`),
  UNIQUE KEY `nID` (`nID`),
  KEY `nID_2` (`nID`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cf_inputfield`
--

LOCK TABLES `cf_inputfield` WRITE;
/*!40000 ALTER TABLE `cf_inputfield` DISABLE KEYS */;
INSERT INTO `cf_inputfield` VALUES (1,'TESTFIELD - Text',1,'default value',0,''),(2,'TESTFIELD - Checkbox',2,'0',0,''),(3,'TESTFIELD - Number',3,'xx1xx1337',0,''),(4,'TESTFIELD - Date',4,'xx3xx2004-09-11',0,''),(5,'TESTFIELD - Textfield',5,'nOnSeNs NoNsEnS nOnSeNs NoNsEnS nOnSeNs NoNsEnSnOnSeNsNoNsEnS nOnSeNs NoNsEnS nOnSeNs NoNsEnS',0,''),(6,'TESTFIELD - Radiogroup',6,'---4---Radiobutton - No1---0---Radiobutton - No2---0---Radiobutton - No3 (default)---1---Radiobutton - No4---0',0,''),(7,'TESTFIELD - Checkboxgroup',7,'---8---Checkbox - No1---0---Checkbox - No2---0---Checkbox - No3 (default)---1---Checkbox - No4 (default)---1---Checkbox - No5---0---Checkbox - No6---0---Checkbox - No7 (default)---1---Checkbox - No8---0',0,''),(8,'TESTFIELD - Combobox',8,'---5---Option No1---0---Option No2---0---Option No3---0---Option No4 (default)---1---Option No5---0',0,''),(9,'TESTFIELD - File',9,'',0,'');
/*!40000 ALTER TABLE `cf_inputfield` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cf_mailinglist`
--

DROP TABLE IF EXISTS `cf_mailinglist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cf_mailinglist` (
  `nID` int(11) NOT NULL AUTO_INCREMENT,
  `strName` text NOT NULL,
  `nTemplateId` int(11) NOT NULL DEFAULT '0',
  `bIsEdited` int(11) DEFAULT NULL,
  `bIsDefault` int(11) NOT NULL DEFAULT '0',
  `bDeleted` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`nID`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cf_mailinglist`
--

LOCK TABLES `cf_mailinglist` WRITE;
/*!40000 ALTER TABLE `cf_mailinglist` DISABLE KEYS */;
INSERT INTO `cf_mailinglist` VALUES (1,'Mailinglist - Test No1',3,0,0,0),(2,'Mailinglist - Test No2',3,0,0,0),(3,'Mailinglist - Test No2',3,1,0,0),(4,'General Mailinglist 2000',4,0,1,0);
/*!40000 ALTER TABLE `cf_mailinglist` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cf_slottofield`
--

DROP TABLE IF EXISTS `cf_slottofield`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cf_slottofield` (
  `nID` int(11) NOT NULL AUTO_INCREMENT,
  `nSlotId` int(11) NOT NULL DEFAULT '0',
  `nFieldId` int(11) NOT NULL DEFAULT '0',
  `nPosition` int(11) NOT NULL,
  PRIMARY KEY (`nID`)
) ENGINE=MyISAM AUTO_INCREMENT=42 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cf_slottofield`
--

LOCK TABLES `cf_slottofield` WRITE;
/*!40000 ALTER TABLE `cf_slottofield` DISABLE KEYS */;
INSERT INTO `cf_slottofield` VALUES (2,2,6,0),(3,2,5,0),(4,2,2,0),(5,2,4,0),(6,3,2,0),(7,3,8,0),(8,3,9,0),(9,4,7,0),(10,4,9,0),(11,5,4,0),(12,5,1,0),(13,5,2,0),(30,6,8,0),(29,6,7,0),(28,6,2,0),(36,7,5,0),(35,7,1,0),(34,7,6,0),(33,7,3,0),(32,7,9,0),(31,7,4,0),(38,8,1,0),(37,8,2,0),(41,9,6,0),(40,9,1,0),(39,9,5,0);
/*!40000 ALTER TABLE `cf_slottofield` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cf_slottouser`
--

DROP TABLE IF EXISTS `cf_slottouser`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cf_slottouser` (
  `nID` int(11) NOT NULL AUTO_INCREMENT,
  `nSlotId` int(11) NOT NULL DEFAULT '0',
  `nMailingListId` int(11) NOT NULL DEFAULT '0',
  `nUserId` int(11) NOT NULL DEFAULT '0',
  `nPosition` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`nID`),
  UNIQUE KEY `nID` (`nID`),
  KEY `nID_2` (`nID`)
) ENGINE=MyISAM AUTO_INCREMENT=95 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cf_slottouser`
--

LOCK TABLES `cf_slottouser` WRITE;
/*!40000 ALTER TABLE `cf_slottouser` DISABLE KEYS */;
INSERT INTO `cf_slottouser` VALUES (62,4,1,29,3),(57,2,1,-2,4),(56,2,1,2,3),(55,2,1,27,2),(65,2,2,13,1),(59,3,1,21,2),(58,3,1,25,1),(61,4,1,6,2),(60,4,1,-2,1),(54,2,1,13,1),(64,5,1,17,2),(63,5,1,22,1),(66,2,2,12,2),(67,2,2,-2,3),(68,3,2,20,1),(69,3,2,7,2),(70,3,2,29,3),(71,4,2,-2,1),(72,5,2,31,1),(73,5,2,10,2),(74,5,2,21,3),(75,5,2,5,4),(76,2,3,13,1),(77,2,3,12,2),(78,3,3,20,1),(79,3,3,7,2),(80,4,3,-2,1),(81,4,3,3,2),(82,5,3,31,1),(83,5,3,10,2),(84,6,4,24,1),(85,6,4,13,2),(86,6,4,28,3),(87,6,4,27,4),(88,7,4,30,1),(89,7,4,3,2),(90,8,4,4,1),(91,8,4,34,2),(92,9,4,21,1),(93,9,4,29,2),(94,9,4,5,3);
/*!40000 ALTER TABLE `cf_slottouser` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cf_substitute`
--

DROP TABLE IF EXISTS `cf_substitute`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cf_substitute` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `substitute_id` int(11) NOT NULL,
  `position` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=69 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cf_substitute`
--

LOCK TABLES `cf_substitute` WRITE;
/*!40000 ALTER TABLE `cf_substitute` DISABLE KEYS */;
INSERT INTO `cf_substitute` VALUES (66,13,22,2),(65,13,29,1),(64,13,27,0),(63,27,30,1),(62,27,3,0),(51,25,8,0),(55,4,2,3),(54,4,8,2),(53,4,7,1),(52,4,10,0),(56,19,1,0),(68,1,5,1),(67,1,28,0);
/*!40000 ALTER TABLE `cf_substitute` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cf_user`
--

DROP TABLE IF EXISTS `cf_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cf_user` (
  `nID` int(11) NOT NULL AUTO_INCREMENT,
  `strLastName` tinytext NOT NULL,
  `strFirstName` tinytext NOT NULL,
  `strEMail` tinytext NOT NULL,
  `nAccessLevel` int(11) NOT NULL DEFAULT '0',
  `strUserId` tinytext NOT NULL,
  `strPassword` tinytext NOT NULL,
  `strEmail_Format` varchar(8) NOT NULL DEFAULT 'HTML',
  `strEmail_Values` varchar(8) NOT NULL DEFAULT 'IFRAME',
  `nSubstitudeId` int(11) NOT NULL DEFAULT '0',
  `tsLastAction` int(11) NOT NULL,
  `bDeleted` int(11) NOT NULL,
  `strStreet` text NOT NULL,
  `strCountry` text NOT NULL,
  `strZipcode` text NOT NULL,
  `strCity` text NOT NULL,
  `strPhone_Main1` text NOT NULL,
  `strPhone_Main2` text NOT NULL,
  `strPhone_Mobile` text NOT NULL,
  `strFax` text NOT NULL,
  `strOrganisation` text NOT NULL,
  `strDepartment` text NOT NULL,
  `strCostCenter` text NOT NULL,
  `UserDefined1_Value` text NOT NULL,
  `UserDefined2_Value` text NOT NULL,
  `nSubstituteTimeValue` int(11) NOT NULL,
  `strSubstituteTimeUnit` text NOT NULL,
  `bUseGeneralSubstituteConfig` int(11) NOT NULL,
  `bUseGeneralEmailConfig` int(11) NOT NULL,
  PRIMARY KEY (`nID`),
  UNIQUE KEY `nID` (`nID`)
) ENGINE=MyISAM AUTO_INCREMENT=35 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cf_user`
--

LOCK TABLES `cf_user` WRITE;
/*!40000 ALTER TABLE `cf_user` DISABLE KEYS */;
INSERT INTO `cf_user` VALUES (1,'None','Administrator','gnilson@terpmail.umd.edu',2,'admin','21232f297a57a5a743894a0e4a801fc3','HTML','IFRAME',0,0,0,'','','','','','','','','','','','','',1,'DAYS',0,1),(2,'Habercore','Timo','gnilson@terpmail.umd.edu',2,'thabercore','21232f297a57a5a743894a0e4a801fc3','HTML','IFRAME',0,1178715596,0,'','','','','','','','','','','','','',1,'DAYS',1,1),(12,'Brew','Steven','gnilson@terpmail.umd.edu',1,'sbrew','21232f297a57a5a743894a0e4a801fc3','HTML','IFRAME',0,0,0,'','','','','07135 - 103255','07135-8794515','0170/5598798','','','','','test 1-2-3','test-test',5,'MINUTES',1,1),(13,'Cash','Friedel','gnilson@terpmail.umd.edu',2,'fcash','21232f297a57a5a743894a0e4a801fc3','HTML','IFRAME',-2,0,0,'Livingstreet 88','Germany','74074','HeilbronX','07131 5555584','','0162 444777888','07131 - 22010987','none','nothing','test value 2000','empty','empty2',1,'MINUTES',1,1),(31,'Kevlar','Jennifer','gnilson@terpmail.umd.edu',2,'jkevlar','21232f297a57a5a743894a0e4a801fc3','HTML','IFRAME',0,0,0,'','','','','','','','','','','','','',1,'DAYS',1,1),(17,'Sturner','Tina','gnilson@terpmail.umd.edu',4,'tsturner','21232f297a57a5a743894a0e4a801fc3','HTML','IFRAME',0,0,0,'','','','','','','','','','','','','',1,'DAYS',1,1),(18,'Lay','Marcus','gnilson@terpmail.umd.edu',4,'mlay','21232f297a57a5a743894a0e4a801fc3','HTML','IFRAME',0,0,0,'','','','','','','','','','','','','',1,'DAYS',1,1),(19,'Munich','Claudia','gnilson@terpmail.umd.edu',1,'cmunich','21232f297a57a5a743894a0e4a801fc3','HTML','IFRAME',-2,0,0,'','France','40048','ilElle','08009 852741','08009 337944','','','','','','','',1,'HOURS',1,1),(20,'Focker','Jonathan','gnilson@terpmail.umd.edu',1,'jfocker','21232f297a57a5a743894a0e4a801fc3','HTML','IFRAME',0,1180016376,0,'','','','','','','','','','','','','',1,'DAYS',1,1),(21,'Schlemmer','Horst','gnilson@terpmail.umd.edu',8,'hschlemmer','21232f297a57a5a743894a0e4a801fc3','HTML','IFRAME',0,1179989496,0,'','','','','','','','','','','','','',1,'DAYS',1,1),(22,'Summer','Ursula','gnilson@terpmail.umd.edu',4,'usummer','21232f297a57a5a743894a0e4a801fc3','HTML','IFRAME',0,0,0,'','','','','','','','','','','','','',1,'DAYS',1,1),(23,'Fighthint','Ulu','gnilson@terpmail.umd.edu',1,'ufighthint','21232f297a57a5a743894a0e4a801fc3','HTML','IFRAME',0,0,0,'','','','','','','','','','','','','',5500,'MINUTES',1,1),(24,'Beck','Steven','gnilson@terpmail.umd.edu',8,'sbeck','21232f297a57a5a743894a0e4a801fc3','HTML','IFRAME',0,1179835725,0,'Forest 135','Germany','20048','Goblinhausen','03114 500407','','','','','','','foo is more senseless than bar','',1,'DAYS',1,1),(25,'Free','Warner','gnilson@terpmail.umd.edu',1,'wfree','21232f297a57a5a743894a0e4a801fc3','HTML','IFRAME',-2,0,0,'Ringstr. 16','Germany','74248','Ellhofen','07134/ 6616','07134/ 458798','0176 - 5594879504','07134-661605','Fabrik','Technik','Technik','sinnfrei','',1,'DAYS',1,1),(26,'Meastro','George','gnilson@terpmail.umd.edu',1,'gmeastro','21232f297a57a5a743894a0e4a801fc3','HTML','IFRAME',0,0,0,'','','','','','','','','','','','','',1,'DAYS',1,1),(27,'Davinci','Anabela','gnilson@terpmail.umd.edu',1,'adavinci','21232f297a57a5a743894a0e4a801fc3','HTML','IFRAME',-2,0,0,'Cruzingway 4','France','887799','Paris','01234/ 123456','01234/ 987654','','','','','','','',1,'MINUTES',1,1),(28,'Cook','Joseph','gnilson@terpmail.umd.edu',1,'jcook','21232f297a57a5a743894a0e4a801fc3','HTML','IFRAME',0,0,0,'','','','','','','','','','','','','',1,'DAYS',1,1),(29,'Schulz','Jeffry','gnilson@terpmail.umd.edu',1,'jschulz','21232f297a57a5a743894a0e4a801fc3','HTML','IFRAME',0,0,0,'','','','','','','','','','','','','',1,'DAYS',1,1),(30,'Ghost','Andrew','gnilson@terpmail.umd.edu',1,'aghost','21232f297a57a5a743894a0e4a801fc3','HTML','IFRAME',0,0,0,'','','','','','','','','','','','','',1,'DAYS',1,1),(32,'Edwinson','Beatrix','gnilson@terpmail.umd.edu',4,'bedwinson','21232f297a57a5a743894a0e4a801fc3','HTML','IFRAME',0,0,0,'','','','','','','','','','','','','',1,'DAYS',1,1),(34,'Miller','Marc','gnilson@terpmail.umd.edu',4,'mmiller','21232f297a57a5a743894a0e4a801fc3','HTML','IFRAME',0,1179836745,0,'','','','','','','','','','','','','',1,'MINUTES',1,1),(16,'Woodwait','Jürgen','gnilson@terpmail.umd.edu',1,'jwoodwait','21232f297a57a5a743894a0e4a801fc3','HTML','IFRAME',0,0,0,'','','','','','','','','','','','','',1,'DAYS',1,1),(5,'Sindecade','Marc','gnilson@terpmail.umd.edu',1,'msindecade','21232f297a57a5a743894a0e4a801fc3','HTML','IFRAME',0,0,0,'','','','','','','','','','','','','',1,'DAYS',1,1),(11,'Minz','Margreta','gnilson@terpmail.umd.edu',4,'mminz','21232f297a57a5a743894a0e4a801fc3','HTML','IFRAME',0,0,0,'','','','','','','','','','','','','',1,'DAYS',1,1),(3,'Haaik','Volkman','gnilson@terpmail.umd.edu',4,'vhaaik','21232f297a57a5a743894a0e4a801fc3','HTML','IFRAME',0,0,0,'','','','','','','','','blub','','','hui','',1,'DAYS',1,1),(4,'Link','Thomas','gnilson@terpmail.umd.edu',4,'tlink','21232f297a57a5a743894a0e4a801fc3','HTML','IFRAME',-2,0,0,'Witzelweg 46','Austria','111222','Wien','07133 54046','','','','','','','','',20,'MINUTES',1,1),(6,'Freeliving','Anna','gnilson@terpmail.umd.edu',2,'afreeliving','21232f297a57a5a743894a0e4a801fc3','HTML','IFRAME',0,1178722412,0,'','','','','','','','','','','','','',1,'MINUTES',1,1),(7,'Rich','Martin','gnilson@terpmail.umd.edu',2,'mrich','21232f297a57a5a743894a0e4a801fc3','HTML','IFRAME',0,0,0,'','','','','','','','','','','','','',90,'HOURS',1,1),(8,'Cherry','Tom','gnilson@terpmail.umd.edu',2,'tcherry','21232f297a57a5a743894a0e4a801fc3','HTML','IFRAME',0,0,0,'Street Of London 85','England','77777','City','0544 4445444','','','','xyz','','','','',1,'DAYS',1,1),(10,'Prinsk','Frank','gnilson@terpmail.umd.edu',4,'fprinsk','21232f297a57a5a743894a0e4a801fc3','HTML','IFRAME',0,0,0,'','','','','','','','','','','','','',1,'DAYS',1,1);
/*!40000 ALTER TABLE `cf_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cf_user_index`
--

DROP TABLE IF EXISTS `cf_user_index`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cf_user_index` (
  `user_id` int(11) NOT NULL,
  `index` text NOT NULL,
  UNIQUE KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cf_user_index`
--

LOCK TABLES `cf_user_index` WRITE;
/*!40000 ALTER TABLE `cf_user_index` DISABLE KEYS */;
INSERT INTO `cf_user_index` VALUES (2,'Habercore Timo gnilson@terpmail.umd.edu thabercore             '),(12,'Brew Steven gnilson@terpmail.umd.edu sbrew     07135 - 103255 07135-8794515 0170/5598798     test 1-2-3 test-test'),(13,'Bert Heinz gnilson@terpmail.umd.edu hbert Livingstreet 88 Germany 74074 HeilbronX 07131 5555584  0162 444777888 07131 - 22010987 none nothing test value 2000 empty empty2'),(17,'Sturner Tina gnilson@terpmail.umd.edu tsturner             '),(18,'Lay Marcus gnilson@terpmail.umd.edu mlay             '),(19,'Munich Claudia gnilson@terpmail.umd.edu cmunich  France 40048 ilElle 08009 852741 08009 337944       '),(20,'Focker Jonathan gnilson@terpmail.umd.edu jfocker             '),(21,'Schlemmer Horst gnilson@terpmail.umd.edu hschlemmer             '),(22,'Summer Ursula gnilson@terpmail.umd.edu usummer             '),(23,'Fighthint Ulu gnilson@terpmail.umd.edu ufighthint             '),(24,'Beck Steven gnilson@terpmail.umd.edu sbeck Forest 135 Germany 20048 Goblinhausen 03114 500407       foo is more senseless than bar '),(25,'Free Warner gnilson@terpmail.umd.edu wfree Ringstr. 16 Germany 74248 Ellhofen 07134/ 6616 07134/ 458798 0176 - 5594879504 07134-661605 Fabrik Technik Technik sinnfrei '),(26,'Meastro George gnilson@terpmail.umd.edu gmeastro             '),(27,'Davinci Anabela gnilson@terpmail.umd.edu adavinci Cruzingway 4 France 887799 Paris 01234/ 123456 01234/ 987654       '),(28,'Cook Joseph gnilson@terpmail.umd.edu jcook             '),(29,'Schulz Jeffry gnilson@terpmail.umd.edu jschulz             '),(30,'Ghost Andrew gnilson@terpmail.umd.edu aghost             '),(31,'Kevlar Jennifer gnilson@terpmail.umd.edu jkevlar             '),(32,'Edwinson Beatrix gnilson@terpmail.umd.edu bedwinson             '),(34,'Miller Marc gnilson@terpmail.umd.edu mmiller             '),(16,'Woodwait Jürgen gnilson@terpmail.umd.edu jwoodwait             '),(11,'Minz Margreta gnilson@terpmail.umd.edu mminz             '),(3,'Haaik Volkman gnilson@terpmail.umd.edu vhaaik         blub   hui '),(4,'Link Thomas gnilson@terpmail.umd.edu tlink Witzelweg 46 Austria 111222 Wien 07133 54046        '),(5,'Sindecade Marc gnilson@terpmail.umd.edu msindecade             '),(6,'Freeliving Anna gnilson@terpmail.umd.edu afreeliving             '),(7,'Rich Martin gnilson@terpmail.umd.edu mrich             '),(8,'Cherry Tom gnilson@terpmail.umd.edu tcherry Street Of London 85 England 77777 City 0544 4445444    xyz    '),(10,'Prinsk Frank gnilson@terpmail.umd.edu fprinsk             '),(1,'None Administrator gnilson@terpmail.umd.edu admin           ');
/*!40000 ALTER TABLE `cf_user_index` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2013-03-18 23:03:56
