-- MySQL dump 10.13  Distrib 5.5.28, for debian-linux-gnu (i686)
--
-- Host: localhost    Database: horde
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
-- Table structure for table `horde_alarms`
--

DROP TABLE IF EXISTS `horde_alarms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `horde_alarms` (
  `alarm_id` varchar(250) NOT NULL,
  `alarm_uid` varchar(250) NOT NULL,
  `alarm_start` datetime NOT NULL,
  `alarm_end` datetime DEFAULT NULL,
  `alarm_methods` varchar(255) DEFAULT NULL,
  `alarm_params` text,
  `alarm_title` varchar(255) NOT NULL,
  `alarm_text` text,
  `alarm_snooze` datetime DEFAULT NULL,
  `alarm_dismissed` tinyint(1) NOT NULL DEFAULT '0',
  `alarm_internal` text,
  KEY `alarm_id_idx` (`alarm_id`),
  KEY `alarm_user_idx` (`alarm_uid`),
  KEY `alarm_start_idx` (`alarm_start`),
  KEY `alarm_end_idx` (`alarm_end`),
  KEY `alarm_snooze_idx` (`alarm_snooze`),
  KEY `alarm_dismissed_idx` (`alarm_dismissed`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `horde_alarms`
--

LOCK TABLES `horde_alarms` WRITE;
/*!40000 ALTER TABLE `horde_alarms` DISABLE KEYS */;
/*!40000 ALTER TABLE `horde_alarms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `horde_cache`
--

DROP TABLE IF EXISTS `horde_cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `horde_cache` (
  `cache_id` varchar(32) NOT NULL,
  `cache_timestamp` bigint(20) NOT NULL,
  `cache_expiration` bigint(20) NOT NULL,
  `cache_data` longblob,
  PRIMARY KEY (`cache_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `horde_cache`
--

LOCK TABLES `horde_cache` WRITE;
/*!40000 ALTER TABLE `horde_cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `horde_cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `horde_datatree`
--

DROP TABLE IF EXISTS `horde_datatree`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `horde_datatree` (
  `datatree_id` int(10) unsigned NOT NULL,
  `group_uid` varchar(255) NOT NULL,
  `user_uid` varchar(255) NOT NULL,
  `datatree_name` varchar(255) NOT NULL,
  `datatree_parents` varchar(255) NOT NULL,
  `datatree_order` int(11) DEFAULT NULL,
  `datatree_data` text,
  `datatree_serialized` smallint(6) NOT NULL DEFAULT '0',
  PRIMARY KEY (`datatree_id`),
  KEY `datatree_datatree_name_idx` (`datatree_name`),
  KEY `datatree_group_idx` (`group_uid`),
  KEY `datatree_user_idx` (`user_uid`),
  KEY `datatree_serialized_idx` (`datatree_serialized`),
  KEY `datatree_parents_idx` (`datatree_parents`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `horde_datatree`
--

LOCK TABLES `horde_datatree` WRITE;
/*!40000 ALTER TABLE `horde_datatree` DISABLE KEYS */;
/*!40000 ALTER TABLE `horde_datatree` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `horde_datatree_attributes`
--

DROP TABLE IF EXISTS `horde_datatree_attributes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `horde_datatree_attributes` (
  `datatree_id` int(10) unsigned NOT NULL,
  `attribute_name` varchar(255) NOT NULL,
  `attribute_key` varchar(255) NOT NULL DEFAULT '',
  `attribute_value` text,
  KEY `datatree_attribute_idx` (`datatree_id`),
  KEY `datatree_attribute_name_idx` (`attribute_name`),
  KEY `datatree_attribute_key_idx` (`attribute_key`),
  KEY `datatree_attribute_value_idx` (`attribute_value`(255))
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `horde_datatree_attributes`
--

LOCK TABLES `horde_datatree_attributes` WRITE;
/*!40000 ALTER TABLE `horde_datatree_attributes` DISABLE KEYS */;
/*!40000 ALTER TABLE `horde_datatree_attributes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `horde_groups`
--

DROP TABLE IF EXISTS `horde_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `horde_groups` (
  `group_uid` int(10) unsigned NOT NULL,
  `group_name` varchar(255) NOT NULL,
  `group_parents` varchar(255) NOT NULL,
  `group_email` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`group_uid`),
  UNIQUE KEY `group_name` (`group_name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `horde_groups`
--

LOCK TABLES `horde_groups` WRITE;
/*!40000 ALTER TABLE `horde_groups` DISABLE KEYS */;
/*!40000 ALTER TABLE `horde_groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `horde_groups_members`
--

DROP TABLE IF EXISTS `horde_groups_members`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `horde_groups_members` (
  `group_uid` int(10) unsigned NOT NULL,
  `user_uid` varchar(255) NOT NULL,
  KEY `group_uid_idx` (`group_uid`),
  KEY `user_uid_idx` (`user_uid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `horde_groups_members`
--

LOCK TABLES `horde_groups_members` WRITE;
/*!40000 ALTER TABLE `horde_groups_members` DISABLE KEYS */;
/*!40000 ALTER TABLE `horde_groups_members` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `horde_histories`
--

DROP TABLE IF EXISTS `horde_histories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `horde_histories` (
  `history_id` int(10) unsigned NOT NULL,
  `object_uid` varchar(255) NOT NULL,
  `history_action` varchar(32) NOT NULL,
  `history_ts` bigint(20) NOT NULL,
  `history_desc` text,
  `history_who` varchar(255) DEFAULT NULL,
  `history_extra` text,
  PRIMARY KEY (`history_id`),
  KEY `history_action_idx` (`history_action`),
  KEY `history_ts_idx` (`history_ts`),
  KEY `history_uid_idx` (`object_uid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `horde_histories`
--

LOCK TABLES `horde_histories` WRITE;
/*!40000 ALTER TABLE `horde_histories` DISABLE KEYS */;
/*!40000 ALTER TABLE `horde_histories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `horde_locks`
--

DROP TABLE IF EXISTS `horde_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `horde_locks` (
  `lock_id` varchar(36) NOT NULL,
  `lock_owner` varchar(32) NOT NULL,
  `lock_scope` varchar(32) NOT NULL,
  `lock_principal` varchar(255) NOT NULL,
  `lock_origin_timestamp` bigint(20) NOT NULL,
  `lock_update_timestamp` bigint(20) NOT NULL,
  `lock_expiry_timestamp` bigint(20) NOT NULL,
  `lock_type` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`lock_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `horde_locks`
--

LOCK TABLES `horde_locks` WRITE;
/*!40000 ALTER TABLE `horde_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `horde_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `horde_perms`
--

DROP TABLE IF EXISTS `horde_perms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `horde_perms` (
  `perm_id` int(11) NOT NULL,
  `perm_name` varchar(255) NOT NULL,
  `perm_parents` varchar(255) NOT NULL,
  `perm_data` text,
  PRIMARY KEY (`perm_id`),
  UNIQUE KEY `perm_name` (`perm_name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `horde_perms`
--

LOCK TABLES `horde_perms` WRITE;
/*!40000 ALTER TABLE `horde_perms` DISABLE KEYS */;
/*!40000 ALTER TABLE `horde_perms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `horde_prefs`
--

DROP TABLE IF EXISTS `horde_prefs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `horde_prefs` (
  `pref_uid` varchar(200) NOT NULL,
  `pref_scope` varchar(16) NOT NULL DEFAULT '',
  `pref_name` varchar(32) NOT NULL,
  `pref_value` longtext,
  PRIMARY KEY (`pref_uid`,`pref_scope`,`pref_name`),
  KEY `pref_uid_idx` (`pref_uid`),
  KEY `pref_scope_idx` (`pref_scope`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `horde_prefs`
--

LOCK TABLES `horde_prefs` WRITE;
/*!40000 ALTER TABLE `horde_prefs` DISABLE KEYS */;
/*!40000 ALTER TABLE `horde_prefs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `horde_sessionhandler`
--

DROP TABLE IF EXISTS `horde_sessionhandler`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `horde_sessionhandler` (
  `session_id` varchar(32) NOT NULL,
  `session_lastmodified` bigint(20) NOT NULL,
  `session_data` longblob,
  PRIMARY KEY (`session_id`),
  KEY `session_lastmodified_idx` (`session_lastmodified`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `horde_sessionhandler`
--

LOCK TABLES `horde_sessionhandler` WRITE;
/*!40000 ALTER TABLE `horde_sessionhandler` DISABLE KEYS */;
/*!40000 ALTER TABLE `horde_sessionhandler` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `horde_signups`
--

DROP TABLE IF EXISTS `horde_signups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `horde_signups` (
  `user_name` varchar(255) NOT NULL,
  `signup_date` varchar(255) NOT NULL,
  `signup_host` varchar(255) NOT NULL,
  `signup_data` text NOT NULL,
  PRIMARY KEY (`user_name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `horde_signups`
--

LOCK TABLES `horde_signups` WRITE;
/*!40000 ALTER TABLE `horde_signups` DISABLE KEYS */;
/*!40000 ALTER TABLE `horde_signups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `horde_syncml_anchors`
--

DROP TABLE IF EXISTS `horde_syncml_anchors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `horde_syncml_anchors` (
  `syncml_syncpartner` varchar(255) NOT NULL,
  `syncml_db` varchar(255) NOT NULL,
  `syncml_uid` varchar(255) NOT NULL,
  `syncml_clientanchor` varchar(255) DEFAULT NULL,
  `syncml_serveranchor` varchar(255) DEFAULT NULL,
  KEY `syncml_anchors_syncpartner_idx` (`syncml_syncpartner`),
  KEY `syncml_anchors_db_idx` (`syncml_db`),
  KEY `syncml_anchors_uid_idx` (`syncml_uid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `horde_syncml_anchors`
--

LOCK TABLES `horde_syncml_anchors` WRITE;
/*!40000 ALTER TABLE `horde_syncml_anchors` DISABLE KEYS */;
/*!40000 ALTER TABLE `horde_syncml_anchors` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `horde_syncml_map`
--

DROP TABLE IF EXISTS `horde_syncml_map`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `horde_syncml_map` (
  `syncml_syncpartner` varchar(255) NOT NULL,
  `syncml_db` varchar(255) NOT NULL,
  `syncml_uid` varchar(255) NOT NULL,
  `syncml_cuid` varchar(255) DEFAULT NULL,
  `syncml_suid` varchar(255) DEFAULT NULL,
  `syncml_timestamp` int(11) DEFAULT NULL,
  KEY `syncml_syncpartner_idx` (`syncml_syncpartner`),
  KEY `syncml_db_idx` (`syncml_db`),
  KEY `syncml_uid_idx` (`syncml_uid`),
  KEY `syncml_cuid_idx` (`syncml_cuid`),
  KEY `syncml_suid_idx` (`syncml_suid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `horde_syncml_map`
--

LOCK TABLES `horde_syncml_map` WRITE;
/*!40000 ALTER TABLE `horde_syncml_map` DISABLE KEYS */;
/*!40000 ALTER TABLE `horde_syncml_map` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `horde_tokens`
--

DROP TABLE IF EXISTS `horde_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `horde_tokens` (
  `token_address` varchar(100) NOT NULL,
  `token_id` varchar(32) NOT NULL,
  `token_timestamp` bigint(20) NOT NULL,
  PRIMARY KEY (`token_address`,`token_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `horde_tokens`
--

LOCK TABLES `horde_tokens` WRITE;
/*!40000 ALTER TABLE `horde_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `horde_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `horde_users`
--

DROP TABLE IF EXISTS `horde_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `horde_users` (
  `user_uid` varchar(255) NOT NULL,
  `user_pass` varchar(255) NOT NULL,
  `user_soft_expiration_date` int(11) DEFAULT NULL,
  `user_hard_expiration_date` int(11) DEFAULT NULL,
  PRIMARY KEY (`user_uid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `horde_users`
--

LOCK TABLES `horde_users` WRITE;
/*!40000 ALTER TABLE `horde_users` DISABLE KEYS */;
/*!40000 ALTER TABLE `horde_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `horde_vfs`
--

DROP TABLE IF EXISTS `horde_vfs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `horde_vfs` (
  `vfs_id` int(10) unsigned NOT NULL,
  `vfs_type` smallint(5) unsigned NOT NULL,
  `vfs_path` varchar(255) NOT NULL,
  `vfs_name` varchar(255) NOT NULL,
  `vfs_modified` bigint(20) NOT NULL,
  `vfs_owner` varchar(255) NOT NULL,
  `vfs_data` longblob,
  PRIMARY KEY (`vfs_id`),
  KEY `vfs_path_idx` (`vfs_path`),
  KEY `vfs_name_idx` (`vfs_name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `horde_vfs`
--

LOCK TABLES `horde_vfs` WRITE;
/*!40000 ALTER TABLE `horde_vfs` DISABLE KEYS */;
/*!40000 ALTER TABLE `horde_vfs` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2013-03-20 22:40:04
