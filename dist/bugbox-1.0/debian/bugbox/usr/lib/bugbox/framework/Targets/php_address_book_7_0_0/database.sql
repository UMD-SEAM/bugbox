-- MySQL dump 10.13  Distrib 5.5.28, for debian-linux-gnu (i686)
--
-- Host: localhost    Database: php_address_book_7_0_0
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
-- Current Database: `php_address_book_7_0_0`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `php_address_book_7_0_0` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `php_address_book_7_0_0`;

--
-- Table structure for table `address_in_groups`
--

DROP TABLE IF EXISTS `address_in_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `address_in_groups` (
  `domain_id` int(9) unsigned NOT NULL DEFAULT '0',
  `id` int(9) unsigned NOT NULL DEFAULT '0',
  `group_id` int(9) unsigned NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `deprecated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`group_id`,`id`,`deprecated`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `address_in_groups`
--

LOCK TABLES `address_in_groups` WRITE;
/*!40000 ALTER TABLE `address_in_groups` DISABLE KEYS */;
/*!40000 ALTER TABLE `address_in_groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `addressbook`
--

DROP TABLE IF EXISTS `addressbook`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `addressbook` (
  `domain_id` int(9) unsigned NOT NULL DEFAULT '0',
  `id` int(9) unsigned NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `nickname` varchar(255) NOT NULL,
  `company` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `addr_long` text,
  `addr_lat` text,
  `addr_status` text,
  `home` text NOT NULL,
  `mobile` text NOT NULL,
  `work` text NOT NULL,
  `fax` text NOT NULL,
  `email` text NOT NULL,
  `email2` text NOT NULL,
  `email3` text NOT NULL,
  `im` text NOT NULL,
  `im2` text NOT NULL,
  `im3` text NOT NULL,
  `homepage` text NOT NULL,
  `bday` tinyint(2) NOT NULL,
  `bmonth` varchar(50) NOT NULL,
  `byear` varchar(4) NOT NULL,
  `aday` tinyint(2) NOT NULL,
  `amonth` varchar(50) NOT NULL,
  `ayear` varchar(4) NOT NULL,
  `address2` text NOT NULL,
  `phone2` text NOT NULL,
  `notes` text NOT NULL,
  `photo` mediumtext,
  `x_vcard` mediumtext,
  `x_activesync` mediumtext,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `deprecated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `password` varchar(256) DEFAULT NULL,
  `login` date DEFAULT NULL,
  `role` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id`,`deprecated`,`domain_id`),
  KEY `deprecated_domain_id_idx` (`deprecated`,`domain_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `addressbook`
--

LOCK TABLES `addressbook` WRITE;
/*!40000 ALTER TABLE `addressbook` DISABLE KEYS */;
INSERT INTO `addressbook` VALUES (0,1,'frank','smith','','','','',NULL,NULL,NULL,'444-444-4444','','','','frank.smith@notawebsite.com','','','','','','',0,'-','',0,'-','','','','','',NULL,NULL,'2013-07-08 16:14:02','2013-07-08 16:14:02','2013-07-09 09:43:52',NULL,NULL,NULL),(0,2,'will','h','willis','','','',NULL,NULL,NULL,'(111)111-1111','','','','will.h@willh.com','','','','','','',0,'-','',0,'-','','','','','',NULL,NULL,'2013-07-08 17:07:57','2013-07-08 17:07:57','2013-07-09 09:43:40',NULL,NULL,NULL),(0,2,'will','h','willis','','','',NULL,NULL,NULL,'(111)111-1111','','','','will.h@willh.com','','','','','','',0,'-','',0,'-','','','','','',NULL,NULL,'2013-07-09 09:43:40','2013-07-09 09:43:40','2013-07-09 09:43:52',NULL,NULL,NULL),(0,3,'A','A','a','','','',NULL,NULL,NULL,'111-111-1111','','','','a.a@','','','','','','',0,'-','',0,'-','','','','','',NULL,NULL,'2013-07-09 10:18:12','2013-07-09 10:18:12','2013-07-09 10:42:27',NULL,NULL,NULL),(0,4,'A first name','A last name','A nickname','','A title','',NULL,NULL,NULL,'','','','','a-first-name.a-last-name@','','','','','','',0,'-','',0,'-','','','','','',NULL,NULL,'2013-07-09 10:45:51','2013-07-09 10:45:51','2013-07-09 10:50:30',NULL,NULL,NULL),(0,4,'A first name','A last name','A nickname','A company','A title','nowhere',NULL,NULL,NULL,'(111) 111-1111','','','','a-first-name.a-last-name@','','','','','','',0,'-','',0,'-','','','','','',NULL,NULL,'2013-07-09 10:50:30','2013-07-09 10:50:30','2013-07-09 11:54:23',NULL,NULL,NULL),(0,5,'Johnny','Testcase','','','','',NULL,NULL,NULL,'sadf','','','','johnny.testcase@','','','','','','',0,'-','',0,'-','','','','','',NULL,NULL,'2013-07-09 11:56:32','2013-07-09 11:56:32','0000-00-00 00:00:00',NULL,NULL,NULL);
/*!40000 ALTER TABLE `addressbook` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `group_list`
--

DROP TABLE IF EXISTS `group_list`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `group_list` (
  `domain_id` int(9) unsigned NOT NULL DEFAULT '0',
  `group_id` int(9) unsigned NOT NULL AUTO_INCREMENT,
  `group_parent_id` int(9) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `deprecated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `group_name` varchar(255) NOT NULL DEFAULT '',
  `group_header` mediumtext NOT NULL,
  `group_footer` mediumtext NOT NULL,
  PRIMARY KEY (`group_id`,`deprecated`,`domain_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `group_list`
--

LOCK TABLES `group_list` WRITE;
/*!40000 ALTER TABLE `group_list` DISABLE KEYS */;
/*!40000 ALTER TABLE `group_list` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `month_lookup`
--

DROP TABLE IF EXISTS `month_lookup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `month_lookup` (
  `bmonth` varchar(50) NOT NULL DEFAULT '',
  `bmonth_short` char(3) NOT NULL DEFAULT '',
  `bmonth_num` int(2) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`bmonth_num`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `month_lookup`
--

LOCK TABLES `month_lookup` WRITE;
/*!40000 ALTER TABLE `month_lookup` DISABLE KEYS */;
INSERT INTO `month_lookup` VALUES ('','',0),('January','Jan',1),('February','Feb',2),('March','Mar',3),('April','Apr',4),('May','May',5),('June','Jun',6),('July','Jul',7),('August','Aug',8),('September','Sep',9),('October','Oct',10),('November','Nov',11),('December','Dec',12);
/*!40000 ALTER TABLE `month_lookup` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2013-07-09 14:21:03
