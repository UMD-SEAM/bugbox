-- MySQL dump 10.13  Distrib 5.5.28, for debian-linux-gnu (i686)
--
-- Host: localhost    Database: wordpress_2_8_3
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
-- Current Database: `wordpress_2_8_3`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `wordpress_2_8_3` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `wordpress_2_8_3`;

--
-- Table structure for table `wp_comments`
--

DROP TABLE IF EXISTS `wp_comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_comments` (
  `comment_ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `comment_post_ID` bigint(20) unsigned NOT NULL DEFAULT '0',
  `comment_author` tinytext NOT NULL,
  `comment_author_email` varchar(100) NOT NULL DEFAULT '',
  `comment_author_url` varchar(200) NOT NULL DEFAULT '',
  `comment_author_IP` varchar(100) NOT NULL DEFAULT '',
  `comment_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `comment_date_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `comment_content` text NOT NULL,
  `comment_karma` int(11) NOT NULL DEFAULT '0',
  `comment_approved` varchar(20) NOT NULL DEFAULT '1',
  `comment_agent` varchar(255) NOT NULL DEFAULT '',
  `comment_type` varchar(20) NOT NULL DEFAULT '',
  `comment_parent` bigint(20) unsigned NOT NULL DEFAULT '0',
  `user_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`comment_ID`),
  KEY `comment_approved` (`comment_approved`),
  KEY `comment_post_ID` (`comment_post_ID`),
  KEY `comment_approved_date_gmt` (`comment_approved`,`comment_date_gmt`),
  KEY `comment_date_gmt` (`comment_date_gmt`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_comments`
--

LOCK TABLES `wp_comments` WRITE;
/*!40000 ALTER TABLE `wp_comments` DISABLE KEYS */;
INSERT INTO `wp_comments` VALUES (1,1,'Mr WordPress','','http://wordpress.org/','','2013-06-26 18:43:48','2013-06-26 18:43:48','Hi, this is a comment.<br />To delete a comment, just log in and view the post&#039;s comments. There you will have the option to edit or delete them.',0,'1','','',0,0);
/*!40000 ALTER TABLE `wp_comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_links`
--

DROP TABLE IF EXISTS `wp_links`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_links` (
  `link_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `link_url` varchar(255) NOT NULL DEFAULT '',
  `link_name` varchar(255) NOT NULL DEFAULT '',
  `link_image` varchar(255) NOT NULL DEFAULT '',
  `link_target` varchar(25) NOT NULL DEFAULT '',
  `link_description` varchar(255) NOT NULL DEFAULT '',
  `link_visible` varchar(20) NOT NULL DEFAULT 'Y',
  `link_owner` bigint(20) unsigned NOT NULL DEFAULT '1',
  `link_rating` int(11) NOT NULL DEFAULT '0',
  `link_updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `link_rel` varchar(255) NOT NULL DEFAULT '',
  `link_notes` mediumtext NOT NULL,
  `link_rss` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`link_id`),
  KEY `link_visible` (`link_visible`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_links`
--

LOCK TABLES `wp_links` WRITE;
/*!40000 ALTER TABLE `wp_links` DISABLE KEYS */;
INSERT INTO `wp_links` VALUES (1,'http://codex.wordpress.org/','Documentation','','','','Y',1,0,'0000-00-00 00:00:00','','',''),(2,'http://wordpress.org/development/','Development Blog','','','','Y',1,0,'0000-00-00 00:00:00','','','http://wordpress.org/development/feed/'),(3,'http://wordpress.org/extend/ideas/','Suggest Ideas','','','','Y',1,0,'0000-00-00 00:00:00','','',''),(4,'http://wordpress.org/support/','Support Forum','','','','Y',1,0,'0000-00-00 00:00:00','','',''),(5,'http://wordpress.org/extend/plugins/','Plugins','','','','Y',1,0,'0000-00-00 00:00:00','','',''),(6,'http://wordpress.org/extend/themes/','Themes','','','','Y',1,0,'0000-00-00 00:00:00','','',''),(7,'http://planet.wordpress.org/','WordPress Planet','','','','Y',1,0,'0000-00-00 00:00:00','','','');
/*!40000 ALTER TABLE `wp_links` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_options`
--

DROP TABLE IF EXISTS `wp_options`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_options` (
  `option_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `blog_id` int(11) NOT NULL DEFAULT '0',
  `option_name` varchar(64) NOT NULL DEFAULT '',
  `option_value` longtext NOT NULL,
  `autoload` varchar(20) NOT NULL DEFAULT 'yes',
  PRIMARY KEY (`option_id`,`blog_id`,`option_name`),
  KEY `option_name` (`option_name`)
) ENGINE=InnoDB AUTO_INCREMENT=120 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_options`
--

LOCK TABLES `wp_options` WRITE;
/*!40000 ALTER TABLE `wp_options` DISABLE KEYS */;
INSERT INTO `wp_options` VALUES (1,0,'_transient_random_seed','9354bbea20f8442ec2ef868e75375216','yes'),(2,0,'siteurl','http://127.0.0.1/wordpress','yes'),(3,0,'blogname','Vulnerable Blog','yes'),(4,0,'blogdescription','Just another WordPress weblog','yes'),(5,0,'users_can_register','0','yes'),(6,0,'admin_email','nothing@umd.edu','yes'),(7,0,'start_of_week','1','yes'),(8,0,'use_balanceTags','0','yes'),(9,0,'use_smilies','1','yes'),(10,0,'require_name_email','1','yes'),(11,0,'comments_notify','1','yes'),(12,0,'posts_per_rss','10','yes'),(13,0,'rss_excerpt_length','50','yes'),(14,0,'rss_use_excerpt','0','yes'),(15,0,'mailserver_url','mail.example.com','yes'),(16,0,'mailserver_login','login@example.com','yes'),(17,0,'mailserver_pass','password','yes'),(18,0,'mailserver_port','110','yes'),(19,0,'default_category','1','yes'),(20,0,'default_comment_status','open','yes'),(21,0,'default_ping_status','open','yes'),(22,0,'default_pingback_flag','0','yes'),(23,0,'default_post_edit_rows','10','yes'),(24,0,'posts_per_page','10','yes'),(25,0,'date_format','F j, Y','yes'),(26,0,'time_format','g:i a','yes'),(27,0,'links_updated_date_format','F j, Y g:i a','yes'),(28,0,'links_recently_updated_prepend','<em>','yes'),(29,0,'links_recently_updated_append','</em>','yes'),(30,0,'links_recently_updated_time','120','yes'),(31,0,'comment_moderation','0','yes'),(32,0,'moderation_notify','1','yes'),(33,0,'permalink_structure','','yes'),(34,0,'gzipcompression','0','yes'),(35,0,'hack_file','0','yes'),(36,0,'blog_charset','UTF-8','yes'),(37,0,'moderation_keys','','no'),(38,0,'active_plugins','a:0:{}','yes'),(39,0,'home','http://127.0.0.1/wordpress','yes'),(40,0,'category_base','','yes'),(41,0,'ping_sites','http://rpc.pingomatic.com/','yes'),(42,0,'advanced_edit','0','yes'),(43,0,'comment_max_links','2','yes'),(44,0,'gmt_offset','0','yes'),(45,0,'default_email_category','1','yes'),(46,0,'recently_edited','','no'),(47,0,'use_linksupdate','0','yes'),(48,0,'template','default','yes'),(49,0,'stylesheet','default','yes'),(50,0,'comment_whitelist','1','yes'),(51,0,'blacklist_keys','','no'),(52,0,'comment_registration','0','yes'),(53,0,'rss_language','en','yes'),(54,0,'html_type','text/html','yes'),(55,0,'use_trackback','0','yes'),(56,0,'default_role','subscriber','yes'),(57,0,'db_version','11548','yes'),(58,0,'uploads_use_yearmonth_folders','1','yes'),(59,0,'upload_path','/var/www/wordpress/wp-content/uploads','yes'),(60,0,'secret','J9I^IHHaz6TEOdZOuNIZlfIQws2uY8L$HXsIv*ruLjTJ0###5&OUYN9iQzikvb^i','yes'),(61,0,'blog_public','0','yes'),(62,0,'default_link_category','2','yes'),(63,0,'show_on_front','posts','yes'),(64,0,'tag_base','','yes'),(65,0,'show_avatars','1','yes'),(66,0,'avatar_rating','G','yes'),(67,0,'upload_url_path','','yes'),(68,0,'thumbnail_size_w','150','yes'),(69,0,'thumbnail_size_h','150','yes'),(70,0,'thumbnail_crop','1','yes'),(71,0,'medium_size_w','300','yes'),(72,0,'medium_size_h','300','yes'),(73,0,'avatar_default','mystery','yes'),(74,0,'enable_app','0','yes'),(75,0,'enable_xmlrpc','0','yes'),(76,0,'large_size_w','1024','yes'),(77,0,'large_size_h','1024','yes'),(78,0,'image_default_link_type','file','yes'),(79,0,'image_default_size','','yes'),(80,0,'image_default_align','','yes'),(81,0,'close_comments_for_old_posts','0','yes'),(82,0,'close_comments_days_old','14','yes'),(83,0,'thread_comments','0','yes'),(84,0,'thread_comments_depth','5','yes'),(85,0,'page_comments','1','yes'),(86,0,'comments_per_page','50','yes'),(87,0,'default_comments_page','newest','yes'),(88,0,'comment_order','asc','yes'),(89,0,'sticky_posts','a:0:{}','yes'),(90,0,'widget_categories','a:2:{i:2;a:0:{}s:12:\"_multiwidget\";i:1;}','yes'),(91,0,'widget_text','a:2:{i:2;a:0:{}s:12:\"_multiwidget\";i:1;}','yes'),(92,0,'widget_rss','a:2:{i:2;a:0:{}s:12:\"_multiwidget\";i:1;}','yes'),(93,0,'timezone_string','','yes'),(94,0,'wp_user_roles','a:5:{s:13:\"administrator\";a:2:{s:4:\"name\";s:13:\"Administrator\";s:12:\"capabilities\";a:54:{s:13:\"switch_themes\";b:1;s:11:\"edit_themes\";b:1;s:16:\"activate_plugins\";b:1;s:12:\"edit_plugins\";b:1;s:10:\"edit_users\";b:1;s:10:\"edit_files\";b:1;s:14:\"manage_options\";b:1;s:17:\"moderate_comments\";b:1;s:17:\"manage_categories\";b:1;s:12:\"manage_links\";b:1;s:12:\"upload_files\";b:1;s:6:\"import\";b:1;s:15:\"unfiltered_html\";b:1;s:10:\"edit_posts\";b:1;s:17:\"edit_others_posts\";b:1;s:20:\"edit_published_posts\";b:1;s:13:\"publish_posts\";b:1;s:10:\"edit_pages\";b:1;s:4:\"read\";b:1;s:8:\"level_10\";b:1;s:7:\"level_9\";b:1;s:7:\"level_8\";b:1;s:7:\"level_7\";b:1;s:7:\"level_6\";b:1;s:7:\"level_5\";b:1;s:7:\"level_4\";b:1;s:7:\"level_3\";b:1;s:7:\"level_2\";b:1;s:7:\"level_1\";b:1;s:7:\"level_0\";b:1;s:17:\"edit_others_pages\";b:1;s:20:\"edit_published_pages\";b:1;s:13:\"publish_pages\";b:1;s:12:\"delete_pages\";b:1;s:19:\"delete_others_pages\";b:1;s:22:\"delete_published_pages\";b:1;s:12:\"delete_posts\";b:1;s:19:\"delete_others_posts\";b:1;s:22:\"delete_published_posts\";b:1;s:20:\"delete_private_posts\";b:1;s:18:\"edit_private_posts\";b:1;s:18:\"read_private_posts\";b:1;s:20:\"delete_private_pages\";b:1;s:18:\"edit_private_pages\";b:1;s:18:\"read_private_pages\";b:1;s:12:\"delete_users\";b:1;s:12:\"create_users\";b:1;s:17:\"unfiltered_upload\";b:1;s:14:\"edit_dashboard\";b:1;s:14:\"update_plugins\";b:1;s:14:\"delete_plugins\";b:1;s:15:\"install_plugins\";b:1;s:13:\"update_themes\";b:1;s:14:\"install_themes\";b:1;}}s:6:\"editor\";a:2:{s:4:\"name\";s:6:\"Editor\";s:12:\"capabilities\";a:34:{s:17:\"moderate_comments\";b:1;s:17:\"manage_categories\";b:1;s:12:\"manage_links\";b:1;s:12:\"upload_files\";b:1;s:15:\"unfiltered_html\";b:1;s:10:\"edit_posts\";b:1;s:17:\"edit_others_posts\";b:1;s:20:\"edit_published_posts\";b:1;s:13:\"publish_posts\";b:1;s:10:\"edit_pages\";b:1;s:4:\"read\";b:1;s:7:\"level_7\";b:1;s:7:\"level_6\";b:1;s:7:\"level_5\";b:1;s:7:\"level_4\";b:1;s:7:\"level_3\";b:1;s:7:\"level_2\";b:1;s:7:\"level_1\";b:1;s:7:\"level_0\";b:1;s:17:\"edit_others_pages\";b:1;s:20:\"edit_published_pages\";b:1;s:13:\"publish_pages\";b:1;s:12:\"delete_pages\";b:1;s:19:\"delete_others_pages\";b:1;s:22:\"delete_published_pages\";b:1;s:12:\"delete_posts\";b:1;s:19:\"delete_others_posts\";b:1;s:22:\"delete_published_posts\";b:1;s:20:\"delete_private_posts\";b:1;s:18:\"edit_private_posts\";b:1;s:18:\"read_private_posts\";b:1;s:20:\"delete_private_pages\";b:1;s:18:\"edit_private_pages\";b:1;s:18:\"read_private_pages\";b:1;}}s:6:\"author\";a:2:{s:4:\"name\";s:6:\"Author\";s:12:\"capabilities\";a:10:{s:12:\"upload_files\";b:1;s:10:\"edit_posts\";b:1;s:20:\"edit_published_posts\";b:1;s:13:\"publish_posts\";b:1;s:4:\"read\";b:1;s:7:\"level_2\";b:1;s:7:\"level_1\";b:1;s:7:\"level_0\";b:1;s:12:\"delete_posts\";b:1;s:22:\"delete_published_posts\";b:1;}}s:11:\"contributor\";a:2:{s:4:\"name\";s:11:\"Contributor\";s:12:\"capabilities\";a:5:{s:10:\"edit_posts\";b:1;s:4:\"read\";b:1;s:7:\"level_1\";b:1;s:7:\"level_0\";b:1;s:12:\"delete_posts\";b:1;}}s:10:\"subscriber\";a:2:{s:4:\"name\";s:10:\"Subscriber\";s:12:\"capabilities\";a:2:{s:4:\"read\";b:1;s:7:\"level_0\";b:1;}}}','yes'),(95,0,'_transient_rewrite_rules','','yes'),(96,0,'cron','a:2:{i:1372315459;a:3:{s:16:\"wp_version_check\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:10:\"twicedaily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:43200;}}s:17:\"wp_update_plugins\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:10:\"twicedaily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:43200;}}s:16:\"wp_update_themes\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:10:\"twicedaily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:43200;}}}s:7:\"version\";i:2;}','yes'),(97,0,'_transient_doing_cron','1372272259','yes'),(98,0,'_transient_update_core','O:8:\"stdClass\":3:{s:7:\"updates\";a:0:{}s:15:\"version_checked\";s:5:\"2.8.3\";s:12:\"last_checked\";i:1372272259;}','yes'),(99,0,'auth_salt','0v@3zdoN&n9pKl4c^h4@z)D@5QjiPz665l9z2elaF$%un)n^mLbgqMk8$3V^hnrT','yes'),(100,0,'logged_in_salt','gpO1iBi!KkCgFsxOLTgLVTklkMIlAItOY$poz%bol#j2B**wEHS$)*emYaP#e9%r','yes'),(101,0,'widget_pages','a:2:{i:2;a:0:{}s:12:\"_multiwidget\";i:1;}','yes'),(102,0,'widget_calendar','a:2:{i:2;a:0:{}s:12:\"_multiwidget\";i:1;}','yes'),(103,0,'widget_archives','a:2:{i:2;a:0:{}s:12:\"_multiwidget\";i:1;}','yes'),(104,0,'widget_links','a:2:{i:2;a:0:{}s:12:\"_multiwidget\";i:1;}','yes'),(105,0,'widget_meta','a:2:{i:2;a:0:{}s:12:\"_multiwidget\";i:1;}','yes'),(106,0,'widget_search','a:2:{i:2;a:0:{}s:12:\"_multiwidget\";i:1;}','yes'),(107,0,'widget_recent-posts','a:2:{i:2;a:0:{}s:12:\"_multiwidget\";i:1;}','yes'),(108,0,'widget_recent-comments','a:2:{i:2;a:0:{}s:12:\"_multiwidget\";i:1;}','yes'),(109,0,'widget_tag_cloud','a:2:{i:2;a:0:{}s:12:\"_multiwidget\";i:1;}','yes'),(111,0,'_transient_update_plugins','O:8:\"stdClass\":1:{s:12:\"last_checked\";i:1372272279;}','yes'),(113,0,'_transient_update_themes','O:8:\"stdClass\":1:{s:12:\"last_checked\";i:1372272299;}','yes'),(114,0,'dashboard_widget_options','a:3:{s:24:\"dashboard_incoming_links\";a:5:{s:4:\"home\";s:26:\"http://127.0.0.1/wordpress\";s:4:\"link\";s:108:\"http://blogsearch.google.com/blogsearch?hl=en&scoring=d&partner=wordpress&q=link:http://127.0.0.1/wordpress/\";s:3:\"url\";s:141:\"http://blogsearch.google.com/blogsearch_feeds?hl=en&scoring=d&ie=utf-8&num=20&output=rss&partner=wordpress&q=link:http://127.0.0.1/wordpress/\";s:5:\"items\";i:10;s:9:\"show_date\";b:0;}s:17:\"dashboard_primary\";a:7:{s:4:\"link\";s:33:\"http://wordpress.org/development/\";s:3:\"url\";s:38:\"http://wordpress.org/development/feed/\";s:5:\"title\";s:26:\"WordPress Development Blog\";s:5:\"items\";i:2;s:12:\"show_summary\";i:1;s:11:\"show_author\";i:0;s:9:\"show_date\";i:1;}s:19:\"dashboard_secondary\";a:4:{s:4:\"link\";s:28:\"http://planet.wordpress.org/\";s:3:\"url\";s:33:\"http://planet.wordpress.org/feed/\";s:5:\"title\";s:20:\"Other WordPress News\";s:5:\"items\";i:5;}}','yes'),(115,0,'nonce_salt','hxCgfdplj*PlXtCPERxdXlOjn&&PoIC4LxpCKfWuZb%mGNb$qS577Bvv32UTNpo&','yes'),(116,0,'current_theme','WordPress Default','yes'),(117,0,'can_compress_scripts','0','yes'),(118,0,'_transient_timeout_plugin_slugs','1372358767','no'),(119,0,'_transient_plugin_slugs','a:2:{i:0;s:19:\"akismet/akismet.php\";i:1;s:9:\"hello.php\";}','no');
/*!40000 ALTER TABLE `wp_options` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_postmeta`
--

DROP TABLE IF EXISTS `wp_postmeta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_postmeta` (
  `meta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `meta_key` varchar(255) DEFAULT NULL,
  `meta_value` longtext,
  PRIMARY KEY (`meta_id`),
  KEY `post_id` (`post_id`),
  KEY `meta_key` (`meta_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_postmeta`
--

LOCK TABLES `wp_postmeta` WRITE;
/*!40000 ALTER TABLE `wp_postmeta` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_postmeta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_posts`
--

DROP TABLE IF EXISTS `wp_posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_posts` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `post_author` bigint(20) unsigned NOT NULL DEFAULT '0',
  `post_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_date_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_content` longtext NOT NULL,
  `post_title` text NOT NULL,
  `post_excerpt` text NOT NULL,
  `post_status` varchar(20) NOT NULL DEFAULT 'publish',
  `comment_status` varchar(20) NOT NULL DEFAULT 'open',
  `ping_status` varchar(20) NOT NULL DEFAULT 'open',
  `post_password` varchar(20) NOT NULL DEFAULT '',
  `post_name` varchar(200) NOT NULL DEFAULT '',
  `to_ping` text NOT NULL,
  `pinged` text NOT NULL,
  `post_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_modified_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_content_filtered` text NOT NULL,
  `post_parent` bigint(20) unsigned NOT NULL DEFAULT '0',
  `guid` varchar(255) NOT NULL DEFAULT '',
  `menu_order` int(11) NOT NULL DEFAULT '0',
  `post_type` varchar(20) NOT NULL DEFAULT 'post',
  `post_mime_type` varchar(100) NOT NULL DEFAULT '',
  `comment_count` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  KEY `post_name` (`post_name`),
  KEY `type_status_date` (`post_type`,`post_status`,`post_date`,`ID`),
  KEY `post_parent` (`post_parent`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_posts`
--

LOCK TABLES `wp_posts` WRITE;
/*!40000 ALTER TABLE `wp_posts` DISABLE KEYS */;
INSERT INTO `wp_posts` VALUES (1,1,'2013-06-26 18:43:48','2013-06-26 18:43:48','Welcome to WordPress. This is your first post. Edit or delete it, then start blogging!','Hello world!','','publish','open','open','','hello-world','','','2013-06-26 18:43:48','2013-06-26 18:43:48','',0,'http://127.0.0.1/wordpress/?p=1',0,'post','',1),(2,1,'2013-06-26 18:43:48','2013-06-26 18:43:48','This is an example of a WordPress page, you could edit this to put information about yourself or your site so readers know where you are coming from. You can create as many pages like this one or sub-pages as you like and manage all of your content inside of WordPress.','About','','publish','open','open','','about','','','2013-06-26 18:43:48','2013-06-26 18:43:48','',0,'http://127.0.0.1/wordpress/?page_id=2',0,'page','',0);
/*!40000 ALTER TABLE `wp_posts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_term_relationships`
--

DROP TABLE IF EXISTS `wp_term_relationships`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_term_relationships` (
  `object_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `term_taxonomy_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `term_order` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`object_id`,`term_taxonomy_id`),
  KEY `term_taxonomy_id` (`term_taxonomy_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_term_relationships`
--

LOCK TABLES `wp_term_relationships` WRITE;
/*!40000 ALTER TABLE `wp_term_relationships` DISABLE KEYS */;
INSERT INTO `wp_term_relationships` VALUES (1,1,0),(1,2,0),(2,2,0),(3,2,0),(4,2,0),(5,2,0),(6,2,0),(7,2,0);
/*!40000 ALTER TABLE `wp_term_relationships` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_term_taxonomy`
--

DROP TABLE IF EXISTS `wp_term_taxonomy`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_term_taxonomy` (
  `term_taxonomy_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `term_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `taxonomy` varchar(32) NOT NULL DEFAULT '',
  `description` longtext NOT NULL,
  `parent` bigint(20) unsigned NOT NULL DEFAULT '0',
  `count` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`term_taxonomy_id`),
  UNIQUE KEY `term_id_taxonomy` (`term_id`,`taxonomy`),
  KEY `taxonomy` (`taxonomy`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_term_taxonomy`
--

LOCK TABLES `wp_term_taxonomy` WRITE;
/*!40000 ALTER TABLE `wp_term_taxonomy` DISABLE KEYS */;
INSERT INTO `wp_term_taxonomy` VALUES (1,1,'category','',0,1),(2,2,'link_category','',0,7);
/*!40000 ALTER TABLE `wp_term_taxonomy` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_terms`
--

DROP TABLE IF EXISTS `wp_terms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_terms` (
  `term_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL DEFAULT '',
  `slug` varchar(200) NOT NULL DEFAULT '',
  `term_group` bigint(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`term_id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_terms`
--

LOCK TABLES `wp_terms` WRITE;
/*!40000 ALTER TABLE `wp_terms` DISABLE KEYS */;
INSERT INTO `wp_terms` VALUES (1,'Uncategorized','uncategorized',0),(2,'Blogroll','blogroll',0);
/*!40000 ALTER TABLE `wp_terms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_usermeta`
--

DROP TABLE IF EXISTS `wp_usermeta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_usermeta` (
  `umeta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `meta_key` varchar(255) DEFAULT NULL,
  `meta_value` longtext,
  PRIMARY KEY (`umeta_id`),
  KEY `user_id` (`user_id`),
  KEY `meta_key` (`meta_key`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_usermeta`
--

LOCK TABLES `wp_usermeta` WRITE;
/*!40000 ALTER TABLE `wp_usermeta` DISABLE KEYS */;
INSERT INTO `wp_usermeta` VALUES (1,1,'nickname','admin'),(2,1,'rich_editing','true'),(3,1,'comment_shortcuts','false'),(4,1,'admin_color','fresh'),(5,1,'wp_capabilities','a:1:{s:13:\"administrator\";b:1;}'),(6,1,'default_password_nag','1'),(7,1,'wp_user_level','10');
/*!40000 ALTER TABLE `wp_usermeta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_users`
--

DROP TABLE IF EXISTS `wp_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_users` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_login` varchar(60) NOT NULL DEFAULT '',
  `user_pass` varchar(64) NOT NULL DEFAULT '',
  `user_nicename` varchar(50) NOT NULL DEFAULT '',
  `user_email` varchar(100) NOT NULL DEFAULT '',
  `user_url` varchar(100) NOT NULL DEFAULT '',
  `user_registered` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user_activation_key` varchar(60) NOT NULL DEFAULT '',
  `user_status` int(11) NOT NULL DEFAULT '0',
  `display_name` varchar(250) NOT NULL DEFAULT '',
  PRIMARY KEY (`ID`),
  KEY `user_login_key` (`user_login`),
  KEY `user_nicename` (`user_nicename`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_users`
--

LOCK TABLES `wp_users` WRITE;
/*!40000 ALTER TABLE `wp_users` DISABLE KEYS */;
INSERT INTO `wp_users` VALUES (1,'admin','$P$BP4.VFYKBsNQTIl5IOa0izCNrf/A/N/','admin','nothing@umd.edu','','2013-06-26 18:43:48','',0,'admin');
/*!40000 ALTER TABLE `wp_users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2013-06-26 14:52:50
