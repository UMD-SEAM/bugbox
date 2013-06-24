-- MySQL dump 10.13  Distrib 5.5.28, for debian-linux-gnu (i686)
--
-- Host: localhost    Database: family_connections_2_7_1
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


CREATE DATABASE /*!32312 IF NOT EXISTS*/ `family_connections_2_7_1` /*!40100 DEFAULT CHARACTER SET latin1 */;
 
USE `family_connections_2_7_1`;



--
-- Table structure for table `fcms_address`
--

DROP TABLE IF EXISTS `fcms_address`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fcms_address` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) NOT NULL DEFAULT '0',
  `country` char(2) DEFAULT NULL,
  `address` varchar(50) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `state` varchar(50) DEFAULT NULL,
  `zip` varchar(10) DEFAULT NULL,
  `home` varchar(20) DEFAULT NULL,
  `work` varchar(20) DEFAULT NULL,
  `cell` varchar(20) DEFAULT NULL,
  `created_id` int(11) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_id` int(11) NOT NULL DEFAULT '0',
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_ind` (`user`),
  KEY `create_ind` (`created_id`),
  KEY `update_ind` (`updated_id`),
  CONSTRAINT `fcms_address_ibfk_1` FOREIGN KEY (`user`) REFERENCES `fcms_users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fcms_address`
--

LOCK TABLES `fcms_address` WRITE;
/*!40000 ALTER TABLE `fcms_address` DISABLE KEYS */;
INSERT INTO `fcms_address` VALUES (1,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'2013-03-20 10:09:23',1,'2013-03-20 14:09:23');
/*!40000 ALTER TABLE `fcms_address` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fcms_alerts`
--

DROP TABLE IF EXISTS `fcms_alerts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fcms_alerts` (
  `id` int(25) NOT NULL AUTO_INCREMENT,
  `alert` varchar(50) NOT NULL DEFAULT '0',
  `user` int(25) NOT NULL DEFAULT '0',
  `hide` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `alert_ind` (`alert`),
  KEY `user_ind` (`user`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fcms_alerts`
--

LOCK TABLES `fcms_alerts` WRITE;
/*!40000 ALTER TABLE `fcms_alerts` DISABLE KEYS */;
INSERT INTO `fcms_alerts` VALUES (1,'alert_new_user_home',1,1);
/*!40000 ALTER TABLE `fcms_alerts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fcms_board_posts`
--

DROP TABLE IF EXISTS `fcms_board_posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fcms_board_posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `thread` int(11) NOT NULL DEFAULT '0',
  `user` int(11) NOT NULL DEFAULT '0',
  `post` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `thread_ind` (`thread`),
  KEY `user_ind` (`user`),
  CONSTRAINT `fcms_posts_ibfk_1` FOREIGN KEY (`thread`) REFERENCES `fcms_board_threads` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fcms_posts_ibfk_2` FOREIGN KEY (`user`) REFERENCES `fcms_users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fcms_board_posts`
--

LOCK TABLES `fcms_board_posts` WRITE;
/*!40000 ALTER TABLE `fcms_board_posts` DISABLE KEYS */;
INSERT INTO `fcms_board_posts` VALUES (1,'2013-03-20 14:09:23',1,1,'Welcome to the Family Connections Message Board.');
/*!40000 ALTER TABLE `fcms_board_posts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fcms_board_threads`
--

DROP TABLE IF EXISTS `fcms_board_threads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fcms_board_threads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subject` varchar(50) NOT NULL DEFAULT 'Subject',
  `started_by` int(11) NOT NULL DEFAULT '0',
  `updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int(11) NOT NULL DEFAULT '0',
  `views` smallint(6) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `start_ind` (`started_by`),
  KEY `up_ind` (`updated_by`),
  CONSTRAINT `fcms_threads_ibfk_1` FOREIGN KEY (`started_by`) REFERENCES `fcms_users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fcms_threads_ibfk_2` FOREIGN KEY (`updated_by`) REFERENCES `fcms_users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fcms_board_threads`
--

LOCK TABLES `fcms_board_threads` WRITE;
/*!40000 ALTER TABLE `fcms_board_threads` DISABLE KEYS */;
INSERT INTO `fcms_board_threads` VALUES (1,'Welcome',1,'2013-03-20 14:09:23',1,0);
/*!40000 ALTER TABLE `fcms_board_threads` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fcms_calendar`
--

DROP TABLE IF EXISTS `fcms_calendar`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fcms_calendar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL DEFAULT '0000-00-00',
  `time_start` time DEFAULT NULL,
  `time_end` time DEFAULT NULL,
  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `title` varchar(50) NOT NULL DEFAULT 'MyDate',
  `desc` text,
  `created_by` int(11) NOT NULL DEFAULT '0',
  `category` int(11) NOT NULL DEFAULT '0',
  `repeat` varchar(20) DEFAULT NULL,
  `private` tinyint(1) NOT NULL DEFAULT '0',
  `invite` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `by_ind` (`created_by`),
  CONSTRAINT `fcms_calendar_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `fcms_users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fcms_calendar`
--

LOCK TABLES `fcms_calendar` WRITE;
/*!40000 ALTER TABLE `fcms_calendar` DISABLE KEYS */;
INSERT INTO `fcms_calendar` VALUES (1,'2007-12-25',NULL,NULL,'2007-12-25 01:00:00','Christmas',NULL,1,4,'yearly',0,0),(2,'2007-02-14',NULL,NULL,'2007-02-14 01:00:00','Valentine\'s Day',NULL,1,4,'yearly',0,0),(3,'2007-01-01',NULL,NULL,'2007-01-01 01:00:00','New Year\'s Day',NULL,1,4,'yearly',0,0),(4,'2007-07-04',NULL,NULL,'2007-07-04 01:00:00','Independence Day',NULL,1,4,'yearly',0,0),(5,'2007-02-02',NULL,NULL,'2007-02-02 01:00:00','Groundhog Day',NULL,1,4,'yearly',0,0),(6,'2007-03-17',NULL,NULL,'2007-03-17 01:00:00','St. Patrick\'s Day',NULL,1,4,'yearly',0,0),(7,'2007-04-01',NULL,NULL,'2007-04-01 01:00:00','April Fools Day',NULL,1,4,'yearly',0,0),(8,'2007-10-31',NULL,NULL,'2007-10-31 01:00:00','Halloween',NULL,1,4,'yearly',0,0);
/*!40000 ALTER TABLE `fcms_calendar` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fcms_category`
--

DROP TABLE IF EXISTS `fcms_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fcms_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `type` varchar(20) NOT NULL,
  `user` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `color` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_ind` (`user`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fcms_category`
--

LOCK TABLES `fcms_category` WRITE;
/*!40000 ALTER TABLE `fcms_category` DISABLE KEYS */;
INSERT INTO `fcms_category` VALUES (1,'','calendar',1,'2013-03-20 14:09:23','none'),(2,'Anniversary','calendar',1,'2013-03-20 14:09:23','green'),(3,'Birthday','calendar',1,'2013-03-20 14:09:23','red'),(4,'Holiday','calendar',1,'2013-03-20 14:09:23','indigo');
/*!40000 ALTER TABLE `fcms_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fcms_changelog`
--

DROP TABLE IF EXISTS `fcms_changelog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fcms_changelog` (
  `id` int(25) NOT NULL AUTO_INCREMENT,
  `user` int(25) NOT NULL DEFAULT '0',
  `table` varchar(50) NOT NULL,
  `column` varchar(50) NOT NULL,
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `user` (`user`),
  CONSTRAINT `fcms_changelog_ibfk_1` FOREIGN KEY (`user`) REFERENCES `fcms_users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fcms_changelog`
--

LOCK TABLES `fcms_changelog` WRITE;
/*!40000 ALTER TABLE `fcms_changelog` DISABLE KEYS */;
/*!40000 ALTER TABLE `fcms_changelog` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fcms_chat_messages`
--

DROP TABLE IF EXISTS `fcms_chat_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fcms_chat_messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userID` int(11) NOT NULL,
  `userName` varchar(64) COLLATE utf8_bin NOT NULL,
  `userRole` int(1) NOT NULL,
  `channel` int(11) NOT NULL,
  `dateTime` datetime NOT NULL,
  `ip` varbinary(16) NOT NULL,
  `text` text COLLATE utf8_bin,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fcms_chat_messages`
--

LOCK TABLES `fcms_chat_messages` WRITE;
/*!40000 ALTER TABLE `fcms_chat_messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `fcms_chat_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fcms_chat_online`
--

DROP TABLE IF EXISTS `fcms_chat_online`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fcms_chat_online` (
  `userID` int(11) NOT NULL,
  `userName` varchar(64) COLLATE utf8_bin NOT NULL,
  `userRole` int(1) NOT NULL,
  `channel` int(11) NOT NULL,
  `dateTime` datetime NOT NULL,
  `ip` varbinary(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fcms_chat_online`
--

LOCK TABLES `fcms_chat_online` WRITE;
/*!40000 ALTER TABLE `fcms_chat_online` DISABLE KEYS */;
/*!40000 ALTER TABLE `fcms_chat_online` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fcms_config`
--

DROP TABLE IF EXISTS `fcms_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fcms_config` (
  `name` varchar(50) NOT NULL,
  `value` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fcms_config`
--

LOCK TABLES `fcms_config` WRITE;
/*!40000 ALTER TABLE `fcms_config` DISABLE KEYS */;
INSERT INTO `fcms_config` VALUES ('sitename','Security Research'),('contact','gnilson@terpmail.umd.edu'),('current_version','Family Connections 2.7.1'),('auto_activate','0'),('registration','1'),('full_size_photos','0'),('site_off','0'),('log_errors','1'),('fs_client_id',NULL),('fs_client_secret',NULL),('fs_callback_url',NULL),('external_news_date',NULL),('fb_app_id',NULL),('fb_secret',NULL),('youtube_key',NULL),('running_job','0'),('country','US');
/*!40000 ALTER TABLE `fcms_config` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fcms_documents`
--

DROP TABLE IF EXISTS `fcms_documents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fcms_documents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `mime` varchar(50) NOT NULL DEFAULT 'application/download',
  `user` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fcms_documents_ibfk_1` (`user`),
  CONSTRAINT `fcms_documents_ibfk_1` FOREIGN KEY (`user`) REFERENCES `fcms_users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fcms_documents`
--

LOCK TABLES `fcms_documents` WRITE;
/*!40000 ALTER TABLE `fcms_documents` DISABLE KEYS */;
/*!40000 ALTER TABLE `fcms_documents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fcms_gallery_comments`
--

DROP TABLE IF EXISTS `fcms_gallery_comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fcms_gallery_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `photo` int(11) NOT NULL DEFAULT '0',
  `comment` text NOT NULL,
  `date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `photo_ind` (`photo`),
  KEY `user_ind` (`user`),
  CONSTRAINT `fcms_gallery_comments_ibfk_1` FOREIGN KEY (`user`) REFERENCES `fcms_users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fcms_gallery_comments_ibfk_2` FOREIGN KEY (`photo`) REFERENCES `fcms_gallery_photos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fcms_gallery_comments`
--

LOCK TABLES `fcms_gallery_comments` WRITE;
/*!40000 ALTER TABLE `fcms_gallery_comments` DISABLE KEYS */;
/*!40000 ALTER TABLE `fcms_gallery_comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fcms_gallery_photos`
--

DROP TABLE IF EXISTS `fcms_gallery_photos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fcms_gallery_photos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `filename` varchar(25) NOT NULL DEFAULT 'noimage.gif',
  `caption` text,
  `category` int(11) NOT NULL DEFAULT '0',
  `user` int(11) NOT NULL DEFAULT '0',
  `views` smallint(6) NOT NULL DEFAULT '0',
  `votes` smallint(6) NOT NULL DEFAULT '0',
  `rating` float NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `cat_ind` (`category`),
  KEY `user_ind` (`user`),
  CONSTRAINT `fcms_gallery_photos_ibfk_1` FOREIGN KEY (`user`) REFERENCES `fcms_users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fcms_gallery_photos_ibfk_2` FOREIGN KEY (`category`) REFERENCES `fcms_category` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fcms_gallery_photos`
--

LOCK TABLES `fcms_gallery_photos` WRITE;
/*!40000 ALTER TABLE `fcms_gallery_photos` DISABLE KEYS */;
/*!40000 ALTER TABLE `fcms_gallery_photos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fcms_gallery_photos_tags`
--

DROP TABLE IF EXISTS `fcms_gallery_photos_tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fcms_gallery_photos_tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) NOT NULL DEFAULT '0',
  `photo` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `tag_photo_ind` (`photo`),
  KEY `tag_user_ind` (`user`),
  CONSTRAINT `fcms_gallery_photos_tags_ibfk_1` FOREIGN KEY (`user`) REFERENCES `fcms_users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fcms_gallery_photos_tags_ibfk_2` FOREIGN KEY (`photo`) REFERENCES `fcms_gallery_photos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fcms_gallery_photos_tags`
--

LOCK TABLES `fcms_gallery_photos_tags` WRITE;
/*!40000 ALTER TABLE `fcms_gallery_photos_tags` DISABLE KEYS */;
/*!40000 ALTER TABLE `fcms_gallery_photos_tags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fcms_invitation`
--

DROP TABLE IF EXISTS `fcms_invitation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fcms_invitation` (
  `id` int(25) NOT NULL AUTO_INCREMENT,
  `event_id` int(25) NOT NULL DEFAULT '0',
  `user` int(25) NOT NULL DEFAULT '0',
  `email` varchar(50) DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated` datetime DEFAULT NULL,
  `attending` tinyint(1) DEFAULT NULL,
  `code` char(13) DEFAULT NULL,
  `response` text,
  PRIMARY KEY (`id`),
  KEY `event_id` (`event_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fcms_invitation`
--

LOCK TABLES `fcms_invitation` WRITE;
/*!40000 ALTER TABLE `fcms_invitation` DISABLE KEYS */;
/*!40000 ALTER TABLE `fcms_invitation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fcms_navigation`
--

DROP TABLE IF EXISTS `fcms_navigation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fcms_navigation` (
  `id` int(25) NOT NULL AUTO_INCREMENT,
  `link` varchar(30) NOT NULL,
  `col` tinyint(1) NOT NULL,
  `order` tinyint(2) NOT NULL,
  `req` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fcms_navigation`
--

LOCK TABLES `fcms_navigation` WRITE;
/*!40000 ALTER TABLE `fcms_navigation` DISABLE KEYS */;
INSERT INTO `fcms_navigation` VALUES (1,'home',1,1,1),(2,'profile',2,1,1),(3,'settings',2,2,1),(4,'pm',2,3,1),(5,'messageboard',3,1,1),(6,'photogallery',4,1,1),(7,'videogallery',4,2,1),(8,'addressbook',4,3,1),(9,'calendar',4,4,1),(10,'members',5,1,1),(11,'contact',5,2,1),(12,'help',5,3,1),(13,'admin_upgrade',6,1,1),(14,'admin_configuration',6,2,1),(15,'admin_members',6,3,1),(16,'admin_photogallery',6,4,1),(17,'admin_polls',6,5,1),(18,'admin_awards',6,6,1),(19,'admin_facebook',6,7,1),(20,'admin_youtube',6,8,1),(21,'admin_scheduler',6,9,1),(22,'familynews',3,0,0),(23,'prayers',3,0,0),(24,'recipes',4,0,0),(25,'tree',4,0,0),(26,'documents',4,0,0),(27,'whereiseveryone',4,0,0);
/*!40000 ALTER TABLE `fcms_navigation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fcms_news`
--

DROP TABLE IF EXISTS `fcms_news`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fcms_news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL DEFAULT '',
  `news` text NOT NULL,
  `user` int(11) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `external_type` varchar(20) DEFAULT NULL,
  `external_id` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `userindx` (`user`),
  CONSTRAINT `fcms_news_ibfk_1` FOREIGN KEY (`user`) REFERENCES `fcms_users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fcms_news`
--

LOCK TABLES `fcms_news` WRITE;
/*!40000 ALTER TABLE `fcms_news` DISABLE KEYS */;
/*!40000 ALTER TABLE `fcms_news` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fcms_news_comments`
--

DROP TABLE IF EXISTS `fcms_news_comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fcms_news_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `news` int(11) NOT NULL DEFAULT '0',
  `comment` text NOT NULL,
  `date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `photo_ind` (`news`),
  KEY `user_ind` (`user`),
  CONSTRAINT `fcms_news_comments_ibfk_2` FOREIGN KEY (`user`) REFERENCES `fcms_users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fcms_news_comments_ibfk_1` FOREIGN KEY (`news`) REFERENCES `fcms_news` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fcms_news_comments`
--

LOCK TABLES `fcms_news_comments` WRITE;
/*!40000 ALTER TABLE `fcms_news_comments` DISABLE KEYS */;
/*!40000 ALTER TABLE `fcms_news_comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fcms_poll_options`
--

DROP TABLE IF EXISTS `fcms_poll_options`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fcms_poll_options` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `poll_id` int(11) NOT NULL DEFAULT '0',
  `option` text NOT NULL,
  `votes` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `pollid_ind` (`poll_id`),
  CONSTRAINT `fcms_poll_options_ibfk_1` FOREIGN KEY (`poll_id`) REFERENCES `fcms_polls` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fcms_poll_options`
--

LOCK TABLES `fcms_poll_options` WRITE;
/*!40000 ALTER TABLE `fcms_poll_options` DISABLE KEYS */;
INSERT INTO `fcms_poll_options` VALUES (1,1,'Easy to use!',0),(2,1,'Visually appealing!',0),(3,1,'Just what our family needed!',0);
/*!40000 ALTER TABLE `fcms_poll_options` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fcms_poll_votes`
--

DROP TABLE IF EXISTS `fcms_poll_votes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fcms_poll_votes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) NOT NULL DEFAULT '0',
  `option` int(11) NOT NULL DEFAULT '0',
  `poll_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user_ind` (`user`),
  KEY `option_ind` (`option`),
  KEY `poll_id_ind` (`poll_id`),
  CONSTRAINT `fcms_poll_votes_ibfk_1` FOREIGN KEY (`user`) REFERENCES `fcms_users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fcms_poll_votes_ibfk_2` FOREIGN KEY (`option`) REFERENCES `fcms_poll_options` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fcms_poll_votes_ibfk_3` FOREIGN KEY (`poll_id`) REFERENCES `fcms_polls` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fcms_poll_votes`
--

LOCK TABLES `fcms_poll_votes` WRITE;
/*!40000 ALTER TABLE `fcms_poll_votes` DISABLE KEYS */;
/*!40000 ALTER TABLE `fcms_poll_votes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fcms_polls`
--

DROP TABLE IF EXISTS `fcms_polls`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fcms_polls` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question` text NOT NULL,
  `started` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fcms_polls`
--

LOCK TABLES `fcms_polls` WRITE;
/*!40000 ALTER TABLE `fcms_polls` DISABLE KEYS */;
INSERT INTO `fcms_polls` VALUES (1,'Family Connections software is...','2013-03-20 10:09:23');
/*!40000 ALTER TABLE `fcms_polls` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fcms_prayers`
--

DROP TABLE IF EXISTS `fcms_prayers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fcms_prayers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `for` varchar(50) NOT NULL DEFAULT '',
  `desc` text NOT NULL,
  `user` int(11) NOT NULL DEFAULT '0',
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `userindx` (`user`),
  CONSTRAINT `fcms_prayers_ibfk_1` FOREIGN KEY (`user`) REFERENCES `fcms_users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fcms_prayers`
--

LOCK TABLES `fcms_prayers` WRITE;
/*!40000 ALTER TABLE `fcms_prayers` DISABLE KEYS */;
/*!40000 ALTER TABLE `fcms_prayers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fcms_privatemsg`
--

DROP TABLE IF EXISTS `fcms_privatemsg`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fcms_privatemsg` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `to` int(11) NOT NULL,
  `from` int(11) NOT NULL,
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `title` varchar(50) NOT NULL DEFAULT 'PM Title',
  `msg` text,
  `read` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `to_ind` (`to`),
  KEY `from_ind` (`from`),
  CONSTRAINT `fcms_privatemsg_ibfk_1` FOREIGN KEY (`to`) REFERENCES `fcms_users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fcms_privatemsg_ibfk_2` FOREIGN KEY (`from`) REFERENCES `fcms_users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fcms_privatemsg`
--

LOCK TABLES `fcms_privatemsg` WRITE;
/*!40000 ALTER TABLE `fcms_privatemsg` DISABLE KEYS */;
/*!40000 ALTER TABLE `fcms_privatemsg` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fcms_recipe_comment`
--

DROP TABLE IF EXISTS `fcms_recipe_comment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fcms_recipe_comment` (
  `id` int(25) NOT NULL AUTO_INCREMENT,
  `recipe` int(25) NOT NULL,
  `comment` text NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user` int(25) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `recipe` (`recipe`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fcms_recipe_comment`
--

LOCK TABLES `fcms_recipe_comment` WRITE;
/*!40000 ALTER TABLE `fcms_recipe_comment` DISABLE KEYS */;
/*!40000 ALTER TABLE `fcms_recipe_comment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fcms_recipes`
--

DROP TABLE IF EXISTS `fcms_recipes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fcms_recipes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT 'My Recipe',
  `thumbnail` varchar(255) NOT NULL DEFAULT 'no_recipe.jpg',
  `category` int(11) NOT NULL,
  `ingredients` text NOT NULL,
  `directions` text NOT NULL,
  `user` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fcms_recipes_ibfk_1` (`user`),
  CONSTRAINT `fcms_recipes_ibfk_1` FOREIGN KEY (`user`) REFERENCES `fcms_users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fcms_recipes`
--

LOCK TABLES `fcms_recipes` WRITE;
/*!40000 ALTER TABLE `fcms_recipes` DISABLE KEYS */;
/*!40000 ALTER TABLE `fcms_recipes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fcms_relationship`
--

DROP TABLE IF EXISTS `fcms_relationship`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fcms_relationship` (
  `id` int(25) NOT NULL AUTO_INCREMENT,
  `user` int(25) NOT NULL,
  `relationship` varchar(4) NOT NULL,
  `rel_user` int(25) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_ind` (`user`),
  KEY `rel_user` (`rel_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fcms_relationship`
--

LOCK TABLES `fcms_relationship` WRITE;
/*!40000 ALTER TABLE `fcms_relationship` DISABLE KEYS */;
/*!40000 ALTER TABLE `fcms_relationship` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fcms_schedule`
--

DROP TABLE IF EXISTS `fcms_schedule`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fcms_schedule` (
  `id` int(25) NOT NULL AUTO_INCREMENT,
  `type` varchar(50) NOT NULL DEFAULT 'familynews',
  `repeat` varchar(50) NOT NULL DEFAULT 'hourly',
  `lastrun` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fcms_schedule`
--

LOCK TABLES `fcms_schedule` WRITE;
/*!40000 ALTER TABLE `fcms_schedule` DISABLE KEYS */;
INSERT INTO `fcms_schedule` VALUES (1,'familynews','hourly','0000-00-00 00:00:00',0),(2,'youtube','hourly','0000-00-00 00:00:00',0);
/*!40000 ALTER TABLE `fcms_schedule` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fcms_status`
--

DROP TABLE IF EXISTS `fcms_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fcms_status` (
  `id` int(25) NOT NULL AUTO_INCREMENT,
  `user` int(25) NOT NULL DEFAULT '0',
  `status` text,
  `parent` int(25) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user` (`user`),
  CONSTRAINT `fcms_status_ibfk_1` FOREIGN KEY (`user`) REFERENCES `fcms_users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fcms_status`
--

LOCK TABLES `fcms_status` WRITE;
/*!40000 ALTER TABLE `fcms_status` DISABLE KEYS */;
/*!40000 ALTER TABLE `fcms_status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fcms_user_awards`
--

DROP TABLE IF EXISTS `fcms_user_awards`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fcms_user_awards` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) NOT NULL DEFAULT '0',
  `award` varchar(100) NOT NULL,
  `month` int(6) NOT NULL,
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `item_id` int(11) DEFAULT NULL,
  `count` smallint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user` (`user`),
  CONSTRAINT `fcms_user_awards_ibfk_1` FOREIGN KEY (`user`) REFERENCES `fcms_users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fcms_user_awards`
--

LOCK TABLES `fcms_user_awards` WRITE;
/*!40000 ALTER TABLE `fcms_user_awards` DISABLE KEYS */;
/*!40000 ALTER TABLE `fcms_user_awards` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fcms_user_settings`
--

DROP TABLE IF EXISTS `fcms_user_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fcms_user_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) NOT NULL,
  `theme` varchar(25) NOT NULL DEFAULT 'default',
  `boardsort` set('ASC','DESC') NOT NULL DEFAULT 'ASC',
  `showavatar` tinyint(1) NOT NULL DEFAULT '1',
  `displayname` set('1','2','3') NOT NULL DEFAULT '1',
  `frontpage` set('1','2') NOT NULL DEFAULT '1',
  `timezone` set('-12 hours','-11 hours','-10 hours','-9 hours','-8 hours','-7 hours','-6 hours','-5 hours','-4 hours','-3 hours -30 minutes','-3 hours','-2 hours','-1 hours','-0 hours','+1 hours','+2 hours','+3 hours','+3 hours +30 minutes','+4 hours','+4 hours +30 minutes','+5 hours','+5 hours +30 minutes','+6 hours','+7 hours','+8 hours','+9 hours','+9 hours +30 minutes','+10 hours','+11 hours','+12 hours') NOT NULL DEFAULT '-5 hours',
  `dst` tinyint(1) NOT NULL DEFAULT '0',
  `email_updates` tinyint(1) NOT NULL DEFAULT '0',
  `advanced_upload` tinyint(1) NOT NULL DEFAULT '1',
  `advanced_tagging` tinyint(1) NOT NULL DEFAULT '1',
  `language` varchar(6) NOT NULL DEFAULT 'en_US',
  `fs_user_id` int(11) DEFAULT NULL,
  `fs_access_token` char(50) DEFAULT NULL,
  `blogger` varchar(255) DEFAULT NULL,
  `tumblr` varchar(255) DEFAULT NULL,
  `wordpress` varchar(255) DEFAULT NULL,
  `posterous` varchar(255) DEFAULT NULL,
  `fb_access_token` varchar(255) DEFAULT NULL,
  `youtube_session_token` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_ind` (`user`),
  CONSTRAINT `fcms_user_stgs_ibfk_1` FOREIGN KEY (`user`) REFERENCES `fcms_users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fcms_user_settings`
--

LOCK TABLES `fcms_user_settings` WRITE;
/*!40000 ALTER TABLE `fcms_user_settings` DISABLE KEYS */;
INSERT INTO `fcms_user_settings` VALUES (1,1,'default','ASC',1,'1','1','-5 hours',0,0,1,1,'en_US',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `fcms_user_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fcms_users`
--

DROP TABLE IF EXISTS `fcms_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fcms_users` (
  `id` int(25) NOT NULL AUTO_INCREMENT,
  `access` tinyint(1) NOT NULL DEFAULT '3',
  `activity` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `joindate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fname` varchar(25) NOT NULL DEFAULT 'fname',
  `mname` varchar(25) DEFAULT NULL,
  `lname` varchar(25) NOT NULL DEFAULT 'lname',
  `maiden` varchar(25) DEFAULT NULL,
  `sex` char(1) NOT NULL DEFAULT 'M',
  `email` varchar(50) NOT NULL DEFAULT 'me@mail.com',
  `dob_year` char(4) DEFAULT NULL,
  `dob_month` char(2) DEFAULT NULL,
  `dob_day` char(2) DEFAULT NULL,
  `dod_year` char(4) DEFAULT NULL,
  `dod_month` char(2) DEFAULT NULL,
  `dod_day` char(2) DEFAULT NULL,
  `username` varchar(25) NOT NULL DEFAULT '0',
  `password` varchar(255) NOT NULL DEFAULT '0',
  `avatar` varchar(25) NOT NULL DEFAULT 'no_avatar.jpg',
  `gravatar` varchar(255) DEFAULT NULL,
  `bio` varchar(200) DEFAULT NULL,
  `activate_code` char(13) DEFAULT NULL,
  `activated` tinyint(1) NOT NULL DEFAULT '0',
  `login_attempts` tinyint(1) NOT NULL DEFAULT '0',
  `locked` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fcms_users`
--

LOCK TABLES `fcms_users` WRITE;
/*!40000 ALTER TABLE `fcms_users` DISABLE KEYS */;
INSERT INTO `fcms_users` VALUES (1,1,'2013-03-20 10:35:43','2013-03-20 14:09:23','Admin',NULL,'Nilson',NULL,'M','gnilson@terpmail.umd.edu','1900','01','01',NULL,NULL,NULL,'familyadmin','679c725c7f1c98595e49672c8f1005b5','no_avatar.jpg',NULL,NULL,NULL,1,0,'0000-00-00 00:00:00');
/*!40000 ALTER TABLE `fcms_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fcms_video`
--

DROP TABLE IF EXISTS `fcms_video`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fcms_video` (
  `id` int(25) NOT NULL AUTO_INCREMENT,
  `source_id` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL DEFAULT 'untitled',
  `description` varchar(255) DEFAULT NULL,
  `duration` int(25) DEFAULT NULL,
  `source` varchar(50) DEFAULT NULL,
  `height` int(4) NOT NULL DEFAULT '420',
  `width` int(4) NOT NULL DEFAULT '780',
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_id` int(25) NOT NULL,
  `updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_id` int(25) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fcms_video`
--

LOCK TABLES `fcms_video` WRITE;
/*!40000 ALTER TABLE `fcms_video` DISABLE KEYS */;
/*!40000 ALTER TABLE `fcms_video` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fcms_video_comment`
--

DROP TABLE IF EXISTS `fcms_video_comment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fcms_video_comment` (
  `id` int(25) NOT NULL AUTO_INCREMENT,
  `video_id` int(25) NOT NULL,
  `comment` text NOT NULL,
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_id` int(25) NOT NULL,
  `updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_id` int(25) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `video_id` (`video_id`),
  CONSTRAINT `fcms_video_comment_ibfk_1` FOREIGN KEY (`video_id`) REFERENCES `fcms_video` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fcms_video_comment`
--

LOCK TABLES `fcms_video_comment` WRITE;
/*!40000 ALTER TABLE `fcms_video_comment` DISABLE KEYS */;
/*!40000 ALTER TABLE `fcms_video_comment` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2013-03-20 13:30:48
