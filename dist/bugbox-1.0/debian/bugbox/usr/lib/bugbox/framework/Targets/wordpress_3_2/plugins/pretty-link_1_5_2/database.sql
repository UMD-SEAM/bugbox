-- MySQL dump 10.13  Distrib 5.5.28, for debian-linux-gnu (i686)
--
-- Host: localhost    Database: wordpress_3_2
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
-- Current Database: `wordpress_3_2`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `wordpress_3_2` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `wordpress_3_2`;

--
-- Table structure for table `wp_bwbps_categories`
--

DROP TABLE IF EXISTS `wp_bwbps_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_bwbps_categories` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `image_id` bigint(20) NOT NULL,
  `category_id` bigint(20) DEFAULT NULL,
  `tag_name` varchar(250) DEFAULT NULL,
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `image_id` (`image_id`),
  KEY `category_id` (`category_id`),
  KEY `tag_name` (`tag_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_bwbps_categories`
--

LOCK TABLES `wp_bwbps_categories` WRITE;
/*!40000 ALTER TABLE `wp_bwbps_categories` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_bwbps_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_bwbps_customdata`
--

DROP TABLE IF EXISTS `wp_bwbps_customdata`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_bwbps_customdata` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `image_id` int(11) NOT NULL,
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `bwbps_status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `image_id` (`image_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_bwbps_customdata`
--

LOCK TABLES `wp_bwbps_customdata` WRITE;
/*!40000 ALTER TABLE `wp_bwbps_customdata` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_bwbps_customdata` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_bwbps_favorites`
--

DROP TABLE IF EXISTS `wp_bwbps_favorites`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_bwbps_favorites` (
  `favorite_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `image_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`favorite_id`),
  KEY `image_id` (`image_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_bwbps_favorites`
--

LOCK TABLES `wp_bwbps_favorites` WRITE;
/*!40000 ALTER TABLE `wp_bwbps_favorites` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_bwbps_favorites` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_bwbps_fields`
--

DROP TABLE IF EXISTS `wp_bwbps_fields`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_bwbps_fields` (
  `field_id` int(11) NOT NULL AUTO_INCREMENT,
  `form_id` int(4) NOT NULL DEFAULT '0',
  `field_name` varchar(50) DEFAULT NULL,
  `label` varchar(255) DEFAULT NULL,
  `type` int(4) DEFAULT NULL,
  `numeric_field` tinyint(1) NOT NULL DEFAULT '0',
  `multi_val` tinyint(1) NOT NULL,
  `default_val` varchar(255) DEFAULT NULL,
  `auto_capitalize` tinyint(1) DEFAULT NULL,
  `keyboard_type` tinyint(1) DEFAULT NULL,
  `html_filter` tinyint(1) DEFAULT NULL,
  `date_format` tinyint(1) DEFAULT NULL,
  `seq` int(4) DEFAULT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`field_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_bwbps_fields`
--

LOCK TABLES `wp_bwbps_fields` WRITE;
/*!40000 ALTER TABLE `wp_bwbps_fields` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_bwbps_fields` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_bwbps_forms`
--

DROP TABLE IF EXISTS `wp_bwbps_forms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_bwbps_forms` (
  `form_id` int(11) NOT NULL AUTO_INCREMENT,
  `form_name` varchar(30) DEFAULT NULL,
  `form` text,
  `css` text,
  `fields_used` text,
  `category` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`form_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_bwbps_forms`
--

LOCK TABLES `wp_bwbps_forms` WRITE;
/*!40000 ALTER TABLE `wp_bwbps_forms` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_bwbps_forms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_bwbps_galleries`
--

DROP TABLE IF EXISTS `wp_bwbps_galleries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_bwbps_galleries` (
  `gallery_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `post_id` bigint(20) DEFAULT NULL,
  `gallery_name` varchar(255) DEFAULT NULL,
  `gallery_description` text,
  `gallery_type` tinyint(1) NOT NULL DEFAULT '0',
  `caption` text,
  `add_text` varchar(255) DEFAULT NULL,
  `upload_form_caption` varchar(255) DEFAULT NULL,
  `contrib_role` tinyint(1) NOT NULL DEFAULT '0',
  `anchor_class` varchar(255) DEFAULT NULL,
  `img_count` bigint(11) DEFAULT NULL,
  `img_rel` varchar(255) DEFAULT NULL,
  `img_class` varchar(255) DEFAULT NULL,
  `img_perrow` tinyint(1) DEFAULT NULL,
  `img_perpage` int(4) DEFAULT NULL,
  `mini_aspect` tinyint(1) DEFAULT NULL,
  `mini_width` int(4) DEFAULT NULL,
  `mini_height` int(4) DEFAULT NULL,
  `thumb_aspect` tinyint(1) DEFAULT NULL,
  `thumb_width` int(4) DEFAULT NULL,
  `thumb_height` int(4) DEFAULT NULL,
  `medium_aspect` tinyint(1) DEFAULT NULL,
  `medium_width` int(4) DEFAULT NULL,
  `medium_height` int(4) DEFAULT NULL,
  `image_aspect` tinyint(1) DEFAULT NULL,
  `image_width` int(4) DEFAULT NULL,
  `image_height` int(4) DEFAULT NULL,
  `show_caption` tinyint(1) DEFAULT NULL,
  `nofollow_caption` tinyint(1) DEFAULT NULL,
  `caption_template` varchar(255) DEFAULT NULL,
  `show_imgcaption` tinyint(1) DEFAULT NULL,
  `img_status` tinyint(1) DEFAULT NULL,
  `allow_no_image` tinyint(1) DEFAULT NULL,
  `suppress_no_image` tinyint(1) DEFAULT NULL,
  `default_image` varchar(255) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `layout_id` int(4) DEFAULT NULL,
  `use_customform` tinyint(1) DEFAULT NULL,
  `custom_formid` int(4) DEFAULT NULL,
  `use_customfields` tinyint(1) DEFAULT NULL,
  `cover_imageid` int(4) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `sort_field` tinyint(1) DEFAULT NULL,
  `sort_order` tinyint(1) DEFAULT NULL,
  `poll_id` int(4) DEFAULT NULL,
  `rating_position` int(4) DEFAULT NULL,
  `hide_toggle_ratings` tinyint(1) DEFAULT NULL,
  `pext_insert_setid` int(4) DEFAULT NULL,
  `max_user_uploads` int(4) DEFAULT NULL,
  `uploads_period` int(4) DEFAULT NULL,
  PRIMARY KEY (`gallery_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_bwbps_galleries`
--

LOCK TABLES `wp_bwbps_galleries` WRITE;
/*!40000 ALTER TABLE `wp_bwbps_galleries` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_bwbps_galleries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_bwbps_imageratings`
--

DROP TABLE IF EXISTS `wp_bwbps_imageratings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_bwbps_imageratings` (
  `rating_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `image_id` bigint(20) NOT NULL,
  `gallery_id` bigint(20) DEFAULT NULL,
  `poll_id` bigint(20) DEFAULT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `user_ip` varchar(30) DEFAULT NULL,
  `rating` tinyint(1) DEFAULT NULL,
  `comment` varchar(250) DEFAULT NULL,
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`rating_id`),
  KEY `image_id` (`image_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_bwbps_imageratings`
--

LOCK TABLES `wp_bwbps_imageratings` WRITE;
/*!40000 ALTER TABLE `wp_bwbps_imageratings` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_bwbps_imageratings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_bwbps_images`
--

DROP TABLE IF EXISTS `wp_bwbps_images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_bwbps_images` (
  `image_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `gallery_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL DEFAULT '0',
  `post_id` bigint(20) DEFAULT NULL,
  `comment_id` bigint(20) DEFAULT NULL,
  `image_name` varchar(250) DEFAULT NULL,
  `image_caption` text,
  `file_type` tinyint(1) DEFAULT NULL,
  `file_name` text,
  `file_url` text,
  `mini_url` text,
  `thumb_url` text,
  `medium_url` text,
  `image_url` text,
  `wp_attach_id` bigint(11) DEFAULT NULL,
  `url` varchar(250) DEFAULT NULL,
  `custom_fields` text,
  `meta_data` text,
  `geolong` double DEFAULT NULL,
  `geolat` double DEFAULT NULL,
  `img_attribution` text,
  `img_license` tinyint(1) DEFAULT NULL,
  `updated_by` bigint(20) NOT NULL DEFAULT '0',
  `created_date` datetime DEFAULT NULL,
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `alerted` tinyint(1) NOT NULL DEFAULT '0',
  `seq` bigint(11) NOT NULL DEFAULT '0',
  `favorites_cnt` bigint(11) DEFAULT NULL,
  `avg_rating` float(8,4) NOT NULL DEFAULT '0.0000',
  `rating_cnt` bigint(11) NOT NULL DEFAULT '0',
  `votes_sum` bigint(11) NOT NULL DEFAULT '0',
  `votes_cnt` bigint(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`image_id`),
  KEY `gallery_id` (`gallery_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_bwbps_images`
--

LOCK TABLES `wp_bwbps_images` WRITE;
/*!40000 ALTER TABLE `wp_bwbps_images` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_bwbps_images` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_bwbps_layouts`
--

DROP TABLE IF EXISTS `wp_bwbps_layouts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_bwbps_layouts` (
  `layout_id` int(11) NOT NULL AUTO_INCREMENT,
  `layout_name` varchar(30) DEFAULT NULL,
  `layout_type` tinyint(4) NOT NULL DEFAULT '0',
  `layout` text,
  `alt_layout` text,
  `wrapper` text,
  `cells_perrow` tinyint(4) NOT NULL DEFAULT '0',
  `css` text,
  `pagination_class` varchar(255) DEFAULT NULL,
  `lists` varchar(255) DEFAULT NULL,
  `post_type` varchar(20) DEFAULT NULL,
  `fields_used` text,
  `footer_layout` text,
  PRIMARY KEY (`layout_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_bwbps_layouts`
--

LOCK TABLES `wp_bwbps_layouts` WRITE;
/*!40000 ALTER TABLE `wp_bwbps_layouts` DISABLE KEYS */;
INSERT INTO `wp_bwbps_layouts` VALUES (1,'Std_Widget',0,'\n			<div class=\'bwbps_image\'>[thumb_linktoimage]</div>\n			','','',0,'','bwbps_pagination',NULL,NULL,NULL,NULL),(2,'media_rss',0,'\n<item>\n	<title><![CDATA[[caption]]]></title>\n	<description><![CDATA[]]></description>\n	<link><![CDATA[]]></link>\n	<media:content url=\'[image_url]\' medium=\'image\' />\n	<media:title><![CDATA[[caption]]]></media:title>\n	<media:description><![CDATA[]]></media:description>\n	<media:thumbnail url=\'[thumb_url]\' width=\'100\' height=\'75\' />\n	<media:keywords><![CDATA[]]></media:keywords>\n	<media:copyright><![CDATA[Copyright (c) [blog_name]]]></media:copyright>\n</item>\n			','','',0,'','bwbps_pagination',NULL,NULL,NULL,NULL),(3,'gallery_viewer',0,'\n<div class=\'bwbps_galviewer\'>\n	<div class=\'bwbps_galviewer_head\'>\n		<a href=\'[gallery_url]\' title=\'Gallery: \n		[image_gallery_name]\'>\n		[image_gallery_name length=16] ([gallery_image_count])</a>\n	</div>\n	<div class=\'bwbps_image\'>\n		<a href=\'[gallery_url]\' title=\'Gallery: \n		[image_gallery_name]\'>\n		[thumb_image]</a>\n	</div>\n</div>\n			','','<h2>Galleries:</h2>',0,'','bwbps_pag_2',NULL,NULL,NULL,NULL),(4,'gallery_view_layout',0,'\n<li class=\'psgal_[gallery_id]\'>\n	<div class=\'bwbps_image bwbps_relative\'>\n		<a rel=\'lightbox[album_[gallery_id]]\' href=\'[image_url]\' title=\'[caption_escaped]\'>[thumb_image]</a>\n[ps_rating]\n		<div class=\'bwbps_postlink_top_rt bwbps_postlink\'>\n			<a href=\'[post_url]\' title=\'Visit image page.\'>\n				<img src=\'[plugin_url]/photosmash-galleries/images/post-out.png\' />\n			</a>\n		</div>\n	</div>\n	<div style=\'clear: both;\'>\n		<a rel=\'lightbox[caption_[gallery_id]]\' href=\'[image_url]\' title=\'[caption_escaped]\'>\n			[caption length=20]\n		</a>\n	</div>\n</li>\n			','','<span style=\'float:right;\'>[piclens]</span><div class=\'clear\'></div>\n<h3>Gallery: [gallery_name]</h3>\n<div class=\'bwbps_gallery_container0\'>\n<ul class=\'bwbps_gallery\'>\n[gallery]\n</ul>\n<div style=\'clear:both;\'></div>\n</div>\n',0,'','bwbps_pag_2',NULL,NULL,NULL,NULL),(5,'image_view_layout',0,'\n<div class=\'bwbps_galviewer\' style=\'width:100%; text-align: center;\'>\n	<div class=\'\'>\n		<a rel=\'lightbox[album_[gallery_id]]\' href=\'[image_url]\' title=\'[caption_escaped]\'>[medium]</a>\n	</div>\n	<div style=\'clear: both;\'>\n			[caption]\n	</div>\n	<h3 style=\'width: 100%; text-align: center;\'>Meta Data</h3>\n	<table class=\'bwbps-meta-table\' style=\'margin: 10px auto !important; text-align: left;\'>\n		<tr><th>Contributor:</th><td>[author_link]</td></tr>\n		<tr><th>Date added:</th><td>[date_added]</td></tr>\n		<tr><th>Related Post:</th><td><a href=\'[post_url]\'>[post_name]</a></td></tr>\n		<tr><th>Attribution:</th><td>[img_attribution]</td></tr>\n		<tr><th>License:</th><td>[img_license]</td></tr>\n	</table>\n	<h3 style=\'width: 100%; text-align: center;\'>EXIF Data</h3>\n	[exif_table no_exif_msg=\'No EXIF data available\' show_blank=false]\n</div>\n','','',0,'','bwbps_pag_2',NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `wp_bwbps_layouts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_bwbps_lookup`
--

DROP TABLE IF EXISTS `wp_bwbps_lookup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_bwbps_lookup` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `field_id` int(4) DEFAULT NULL,
  `value` varchar(255) DEFAULT NULL,
  `label` varchar(255) DEFAULT NULL,
  `seq` int(4) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `field_id` (`field_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_bwbps_lookup`
--

LOCK TABLES `wp_bwbps_lookup` WRITE;
/*!40000 ALTER TABLE `wp_bwbps_lookup` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_bwbps_lookup` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_bwbps_params`
--

DROP TABLE IF EXISTS `wp_bwbps_params`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_bwbps_params` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `param_group` varchar(20) DEFAULT NULL,
  `param` varchar(100) DEFAULT NULL,
  `num_value` float DEFAULT NULL,
  `text_value` varchar(255) DEFAULT NULL,
  `user_ip` varchar(30) DEFAULT NULL,
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `param_group` (`param_group`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_bwbps_params`
--

LOCK TABLES `wp_bwbps_params` WRITE;
/*!40000 ALTER TABLE `wp_bwbps_params` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_bwbps_params` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_bwbps_ratingssummary`
--

DROP TABLE IF EXISTS `wp_bwbps_ratingssummary`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_bwbps_ratingssummary` (
  `rating_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `image_id` bigint(20) NOT NULL,
  `gallery_id` bigint(20) DEFAULT NULL,
  `poll_id` bigint(20) DEFAULT NULL,
  `avg_rating` float(8,4) NOT NULL,
  `rating_cnt` bigint(11) NOT NULL,
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`rating_id`),
  KEY `image_id` (`image_id`),
  KEY `gallery_poll` (`gallery_id`,`poll_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_bwbps_ratingssummary`
--

LOCK TABLES `wp_bwbps_ratingssummary` WRITE;
/*!40000 ALTER TABLE `wp_bwbps_ratingssummary` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_bwbps_ratingssummary` ENABLE KEYS */;
UNLOCK TABLES;

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
  KEY `comment_date_gmt` (`comment_date_gmt`),
  KEY `comment_parent` (`comment_parent`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_comments`
--

LOCK TABLES `wp_comments` WRITE;
/*!40000 ALTER TABLE `wp_comments` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_gigpress_artists`
--

DROP TABLE IF EXISTS `wp_gigpress_artists`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_gigpress_artists` (
  `artist_id` int(4) NOT NULL AUTO_INCREMENT,
  `artist_name` varchar(255) NOT NULL,
  `artist_order` int(4) DEFAULT '0',
  PRIMARY KEY (`artist_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_gigpress_artists`
--

LOCK TABLES `wp_gigpress_artists` WRITE;
/*!40000 ALTER TABLE `wp_gigpress_artists` DISABLE KEYS */;
INSERT INTO `wp_gigpress_artists` VALUES (1,'The Haxors',0),(2,'The Haxors',0);
/*!40000 ALTER TABLE `wp_gigpress_artists` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_gigpress_shows`
--

DROP TABLE IF EXISTS `wp_gigpress_shows`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_gigpress_shows` (
  `show_id` int(4) NOT NULL AUTO_INCREMENT,
  `show_artist_id` int(4) NOT NULL,
  `show_venue_id` int(4) NOT NULL,
  `show_tour_id` int(4) DEFAULT '0',
  `show_date` date NOT NULL,
  `show_multi` int(1) DEFAULT NULL,
  `show_time` time NOT NULL,
  `show_expire` date NOT NULL,
  `show_price` varchar(32) DEFAULT NULL,
  `show_tix_url` varchar(255) DEFAULT NULL,
  `show_tix_phone` varchar(255) DEFAULT NULL,
  `show_ages` varchar(255) DEFAULT NULL,
  `show_notes` text,
  `show_related` bigint(20) DEFAULT '0',
  `show_status` varchar(32) DEFAULT 'active',
  `show_tour_restore` int(1) DEFAULT '0',
  `show_address` varchar(255) DEFAULT NULL,
  `show_locale` varchar(255) DEFAULT NULL,
  `show_country` varchar(2) DEFAULT NULL,
  `show_venue` varchar(255) DEFAULT NULL,
  `show_venue_url` varchar(255) DEFAULT NULL,
  `show_venue_phone` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`show_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_gigpress_shows`
--

LOCK TABLES `wp_gigpress_shows` WRITE;
/*!40000 ALTER TABLE `wp_gigpress_shows` DISABLE KEYS */;
INSERT INTO `wp_gigpress_shows` VALUES (1,1,1,0,'2013-04-03',0,'00:00:01','2013-04-03','','','','Not sure','<script>alert(\'w00t\');</script>',0,'deleted',0,NULL,NULL,NULL,NULL,NULL,NULL),(2,2,2,0,'2013-04-03',0,'00:00:01','2013-04-03','','','','Not sure','<script>alert(\'w00t\');</script>',0,'deleted',0,NULL,NULL,NULL,NULL,NULL,NULL),(3,2,1,0,'2013-04-03',0,'00:00:01','2013-04-03','','','','Not sure','test',0,'deleted',0,NULL,NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `wp_gigpress_shows` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_gigpress_tours`
--

DROP TABLE IF EXISTS `wp_gigpress_tours`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_gigpress_tours` (
  `tour_id` int(4) NOT NULL AUTO_INCREMENT,
  `tour_name` varchar(255) NOT NULL,
  `tour_status` varchar(32) DEFAULT 'active',
  PRIMARY KEY (`tour_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_gigpress_tours`
--

LOCK TABLES `wp_gigpress_tours` WRITE;
/*!40000 ALTER TABLE `wp_gigpress_tours` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_gigpress_tours` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_gigpress_venues`
--

DROP TABLE IF EXISTS `wp_gigpress_venues`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_gigpress_venues` (
  `venue_id` int(4) NOT NULL AUTO_INCREMENT,
  `venue_name` varchar(255) NOT NULL,
  `venue_address` varchar(255) DEFAULT NULL,
  `venue_city` varchar(255) NOT NULL,
  `venue_country` varchar(2) NOT NULL,
  `venue_url` varchar(255) DEFAULT NULL,
  `venue_phone` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`venue_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_gigpress_venues`
--

LOCK TABLES `wp_gigpress_venues` WRITE;
/*!40000 ALTER TABLE `wp_gigpress_venues` DISABLE KEYS */;
INSERT INTO `wp_gigpress_venues` VALUES (1,'UMD','','College Park','US','',''),(2,'UMD','','College Park','US','','');
/*!40000 ALTER TABLE `wp_gigpress_venues` ENABLE KEYS */;
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
INSERT INTO `wp_links` VALUES (1,'http://codex.wordpress.org/','Documentation','','','','Y',1,0,'0000-00-00 00:00:00','','',''),(2,'http://wordpress.org/news/','WordPress Blog','','','','Y',1,0,'0000-00-00 00:00:00','','','http://wordpress.org/news/feed/'),(3,'http://wordpress.org/extend/ideas/','Suggest Ideas','','','','Y',1,0,'0000-00-00 00:00:00','','',''),(4,'http://wordpress.org/support/','Support Forum','','','','Y',1,0,'0000-00-00 00:00:00','','',''),(5,'http://wordpress.org/extend/plugins/','Plugins','','','','Y',1,0,'0000-00-00 00:00:00','','',''),(6,'http://wordpress.org/extend/themes/','Themes','','','','Y',1,0,'0000-00-00 00:00:00','','',''),(7,'http://planet.wordpress.org/','WordPress Planet','','','','Y',1,0,'0000-00-00 00:00:00','','','');
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
  PRIMARY KEY (`option_id`),
  UNIQUE KEY `option_name` (`option_name`)
) ENGINE=InnoDB AUTO_INCREMENT=351 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_options`
--

LOCK TABLES `wp_options` WRITE;
/*!40000 ALTER TABLE `wp_options` DISABLE KEYS */;
INSERT INTO `wp_options` VALUES (1,0,'siteurl','http://127.0.0.1/wordpress','yes'),(2,0,'blogname','Wordpress 3.2 Target','yes'),(3,0,'blogdescription','Just another WordPress site','yes'),(4,0,'users_can_register','0','yes'),(5,0,'admin_email','gnilson@terpmail.umd.edu','yes'),(6,0,'start_of_week','1','yes'),(7,0,'use_balanceTags','0','yes'),(8,0,'use_smilies','1','yes'),(9,0,'require_name_email','1','yes'),(10,0,'comments_notify','1','yes'),(11,0,'posts_per_rss','10','yes'),(12,0,'rss_use_excerpt','0','yes'),(13,0,'mailserver_url','mail.example.com','yes'),(14,0,'mailserver_login','login@example.com','yes'),(15,0,'mailserver_pass','password','yes'),(16,0,'mailserver_port','110','yes'),(17,0,'default_category','1','yes'),(18,0,'default_comment_status','open','yes'),(19,0,'default_ping_status','open','yes'),(20,0,'default_pingback_flag','0','yes'),(21,0,'default_post_edit_rows','20','yes'),(22,0,'posts_per_page','10','yes'),(23,0,'date_format','F j, Y','yes'),(24,0,'time_format','g:i a','yes'),(25,0,'links_updated_date_format','F j, Y g:i a','yes'),(26,0,'links_recently_updated_prepend','<em>','yes'),(27,0,'links_recently_updated_append','</em>','yes'),(28,0,'links_recently_updated_time','120','yes'),(29,0,'comment_moderation','0','yes'),(30,0,'moderation_notify','1','yes'),(31,0,'permalink_structure','/wordpress/%post_id%','yes'),(32,0,'gzipcompression','0','yes'),(33,0,'hack_file','0','yes'),(34,0,'blog_charset','UTF-8','yes'),(35,0,'moderation_keys','','no'),(36,0,'active_plugins','a:1:{i:0;s:27:\"pretty-link/pretty-link.php\";}','yes'),(37,0,'home','http://127.0.0.1/wordpress','yes'),(38,0,'category_base','','yes'),(39,0,'ping_sites','http://rpc.pingomatic.com/','yes'),(40,0,'advanced_edit','0','yes'),(41,0,'comment_max_links','2','yes'),(42,0,'gmt_offset','0','yes'),(43,0,'default_email_category','1','yes'),(44,0,'recently_edited','','no'),(45,0,'template','twentyeleven','yes'),(46,0,'stylesheet','twentyeleven','yes'),(47,0,'comment_whitelist','1','yes'),(48,0,'blacklist_keys','','no'),(49,0,'comment_registration','0','yes'),(50,0,'rss_language','en','yes'),(51,0,'html_type','text/html','yes'),(52,0,'use_trackback','0','yes'),(53,0,'default_role','subscriber','yes'),(54,0,'db_version','18226','yes'),(55,0,'uploads_use_yearmonth_folders','1','yes'),(56,0,'upload_path','','yes'),(57,0,'blog_public','0','yes'),(58,0,'default_link_category','2','yes'),(59,0,'show_on_front','posts','yes'),(60,0,'tag_base','','yes'),(61,0,'show_avatars','1','yes'),(62,0,'avatar_rating','G','yes'),(63,0,'upload_url_path','','yes'),(64,0,'thumbnail_size_w','150','yes'),(65,0,'thumbnail_size_h','150','yes'),(66,0,'thumbnail_crop','1','yes'),(67,0,'medium_size_w','300','yes'),(68,0,'medium_size_h','300','yes'),(69,0,'avatar_default','mystery','yes'),(70,0,'enable_app','0','yes'),(71,0,'enable_xmlrpc','0','yes'),(72,0,'large_size_w','1024','yes'),(73,0,'large_size_h','1024','yes'),(74,0,'image_default_link_type','file','yes'),(75,0,'image_default_size','','yes'),(76,0,'image_default_align','','yes'),(77,0,'close_comments_for_old_posts','0','yes'),(78,0,'close_comments_days_old','14','yes'),(79,0,'thread_comments','1','yes'),(80,0,'thread_comments_depth','5','yes'),(81,0,'page_comments','0','yes'),(82,0,'comments_per_page','50','yes'),(83,0,'default_comments_page','newest','yes'),(84,0,'comment_order','asc','yes'),(85,0,'sticky_posts','a:0:{}','yes'),(86,0,'widget_categories','a:2:{i:2;a:4:{s:5:\"title\";s:0:\"\";s:5:\"count\";i:0;s:12:\"hierarchical\";i:0;s:8:\"dropdown\";i:0;}s:12:\"_multiwidget\";i:1;}','yes'),(87,0,'widget_text','a:2:{i:2;a:0:{}s:12:\"_multiwidget\";i:1;}','yes'),(88,0,'widget_rss','a:2:{i:2;a:0:{}s:12:\"_multiwidget\";i:1;}','yes'),(89,0,'timezone_string','','yes'),(90,0,'embed_autourls','1','yes'),(91,0,'embed_size_w','','yes'),(92,0,'embed_size_h','600','yes'),(93,0,'page_for_posts','0','yes'),(94,0,'page_on_front','0','yes'),(95,0,'default_post_format','0','yes'),(96,0,'wp_user_roles','a:5:{s:13:\"administrator\";a:2:{s:4:\"name\";s:13:\"Administrator\";s:12:\"capabilities\";a:62:{s:13:\"switch_themes\";b:1;s:11:\"edit_themes\";b:1;s:16:\"activate_plugins\";b:1;s:12:\"edit_plugins\";b:1;s:10:\"edit_users\";b:1;s:10:\"edit_files\";b:1;s:14:\"manage_options\";b:1;s:17:\"moderate_comments\";b:1;s:17:\"manage_categories\";b:1;s:12:\"manage_links\";b:1;s:12:\"upload_files\";b:1;s:6:\"import\";b:1;s:15:\"unfiltered_html\";b:1;s:10:\"edit_posts\";b:1;s:17:\"edit_others_posts\";b:1;s:20:\"edit_published_posts\";b:1;s:13:\"publish_posts\";b:1;s:10:\"edit_pages\";b:1;s:4:\"read\";b:1;s:8:\"level_10\";b:1;s:7:\"level_9\";b:1;s:7:\"level_8\";b:1;s:7:\"level_7\";b:1;s:7:\"level_6\";b:1;s:7:\"level_5\";b:1;s:7:\"level_4\";b:1;s:7:\"level_3\";b:1;s:7:\"level_2\";b:1;s:7:\"level_1\";b:1;s:7:\"level_0\";b:1;s:17:\"edit_others_pages\";b:1;s:20:\"edit_published_pages\";b:1;s:13:\"publish_pages\";b:1;s:12:\"delete_pages\";b:1;s:19:\"delete_others_pages\";b:1;s:22:\"delete_published_pages\";b:1;s:12:\"delete_posts\";b:1;s:19:\"delete_others_posts\";b:1;s:22:\"delete_published_posts\";b:1;s:20:\"delete_private_posts\";b:1;s:18:\"edit_private_posts\";b:1;s:18:\"read_private_posts\";b:1;s:20:\"delete_private_pages\";b:1;s:18:\"edit_private_pages\";b:1;s:18:\"read_private_pages\";b:1;s:12:\"delete_users\";b:1;s:12:\"create_users\";b:1;s:17:\"unfiltered_upload\";b:1;s:14:\"edit_dashboard\";b:1;s:14:\"update_plugins\";b:1;s:14:\"delete_plugins\";b:1;s:15:\"install_plugins\";b:1;s:13:\"update_themes\";b:1;s:14:\"install_themes\";b:1;s:11:\"update_core\";b:1;s:10:\"list_users\";b:1;s:12:\"remove_users\";b:1;s:9:\"add_users\";b:1;s:13:\"promote_users\";b:1;s:18:\"edit_theme_options\";b:1;s:13:\"delete_themes\";b:1;s:6:\"export\";b:1;}}s:6:\"editor\";a:2:{s:4:\"name\";s:6:\"Editor\";s:12:\"capabilities\";a:34:{s:17:\"moderate_comments\";b:1;s:17:\"manage_categories\";b:1;s:12:\"manage_links\";b:1;s:12:\"upload_files\";b:1;s:15:\"unfiltered_html\";b:1;s:10:\"edit_posts\";b:1;s:17:\"edit_others_posts\";b:1;s:20:\"edit_published_posts\";b:1;s:13:\"publish_posts\";b:1;s:10:\"edit_pages\";b:1;s:4:\"read\";b:1;s:7:\"level_7\";b:1;s:7:\"level_6\";b:1;s:7:\"level_5\";b:1;s:7:\"level_4\";b:1;s:7:\"level_3\";b:1;s:7:\"level_2\";b:1;s:7:\"level_1\";b:1;s:7:\"level_0\";b:1;s:17:\"edit_others_pages\";b:1;s:20:\"edit_published_pages\";b:1;s:13:\"publish_pages\";b:1;s:12:\"delete_pages\";b:1;s:19:\"delete_others_pages\";b:1;s:22:\"delete_published_pages\";b:1;s:12:\"delete_posts\";b:1;s:19:\"delete_others_posts\";b:1;s:22:\"delete_published_posts\";b:1;s:20:\"delete_private_posts\";b:1;s:18:\"edit_private_posts\";b:1;s:18:\"read_private_posts\";b:1;s:20:\"delete_private_pages\";b:1;s:18:\"edit_private_pages\";b:1;s:18:\"read_private_pages\";b:1;}}s:6:\"author\";a:2:{s:4:\"name\";s:6:\"Author\";s:12:\"capabilities\";a:10:{s:12:\"upload_files\";b:1;s:10:\"edit_posts\";b:1;s:20:\"edit_published_posts\";b:1;s:13:\"publish_posts\";b:1;s:4:\"read\";b:1;s:7:\"level_2\";b:1;s:7:\"level_1\";b:1;s:7:\"level_0\";b:1;s:12:\"delete_posts\";b:1;s:22:\"delete_published_posts\";b:1;}}s:11:\"contributor\";a:2:{s:4:\"name\";s:11:\"Contributor\";s:12:\"capabilities\";a:5:{s:10:\"edit_posts\";b:1;s:4:\"read\";b:1;s:7:\"level_1\";b:1;s:7:\"level_0\";b:1;s:12:\"delete_posts\";b:1;}}s:10:\"subscriber\";a:2:{s:4:\"name\";s:10:\"Subscriber\";s:12:\"capabilities\";a:2:{s:4:\"read\";b:1;s:7:\"level_0\";b:1;}}}','yes'),(97,0,'widget_search','a:2:{i:2;a:1:{s:5:\"title\";s:0:\"\";}s:12:\"_multiwidget\";i:1;}','yes'),(98,0,'widget_recent-posts','a:2:{i:2;a:2:{s:5:\"title\";s:0:\"\";s:6:\"number\";i:5;}s:12:\"_multiwidget\";i:1;}','yes'),(99,0,'widget_recent-comments','a:2:{i:2;a:2:{s:5:\"title\";s:0:\"\";s:6:\"number\";i:5;}s:12:\"_multiwidget\";i:1;}','yes'),(100,0,'widget_archives','a:2:{i:2;a:3:{s:5:\"title\";s:0:\"\";s:5:\"count\";i:0;s:8:\"dropdown\";i:0;}s:12:\"_multiwidget\";i:1;}','yes'),(101,0,'widget_meta','a:2:{i:2;a:1:{s:5:\"title\";s:0:\"\";}s:12:\"_multiwidget\";i:1;}','yes'),(102,0,'sidebars_widgets','a:8:{s:19:\"wp_inactive_widgets\";a:0:{}s:19:\"primary-widget-area\";a:6:{i:0;s:8:\"search-2\";i:1;s:14:\"recent-posts-2\";i:2;s:17:\"recent-comments-2\";i:3;s:10:\"archives-2\";i:4;s:12:\"categories-2\";i:5;s:6:\"meta-2\";}s:21:\"secondary-widget-area\";a:0:{}s:24:\"first-footer-widget-area\";a:0:{}s:25:\"second-footer-widget-area\";a:0:{}s:24:\"third-footer-widget-area\";a:0:{}s:25:\"fourth-footer-widget-area\";a:0:{}s:13:\"array_version\";i:3;}','yes'),(103,0,'cron','a:3:{i:1372278485;a:1:{s:19:\"wp_scheduled_delete\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:5:\"daily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:86400;}}}i:1372313176;a:3:{s:16:\"wp_version_check\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:10:\"twicedaily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:43200;}}s:17:\"wp_update_plugins\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:10:\"twicedaily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:43200;}}s:16:\"wp_update_themes\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:10:\"twicedaily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:43200;}}}s:7:\"version\";i:2;}','yes'),(104,0,'_transient_doing_cron','1372274776','yes'),(107,0,'current_theme','Twenty Eleven','yes'),(108,0,'_site_transient_update_core','O:8:\"stdClass\":3:{s:7:\"updates\";a:1:{i:0;O:8:\"stdClass\":9:{s:8:\"response\";s:7:\"upgrade\";s:8:\"download\";s:40:\"http://wordpress.org/wordpress-3.5.1.zip\";s:6:\"locale\";s:5:\"en_US\";s:8:\"packages\";O:8:\"stdClass\":4:{s:4:\"full\";s:40:\"http://wordpress.org/wordpress-3.5.1.zip\";s:10:\"no_content\";s:51:\"http://wordpress.org/wordpress-3.5.1-no-content.zip\";s:11:\"new_bundled\";s:52:\"http://wordpress.org/wordpress-3.5.1-new-bundled.zip\";s:7:\"partial\";b:0;}s:7:\"current\";s:5:\"3.5.1\";s:11:\"php_version\";s:5:\"5.2.4\";s:13:\"mysql_version\";s:3:\"5.0\";s:11:\"new_bundled\";s:3:\"3.5\";s:15:\"partial_version\";s:0:\"\";}}s:12:\"last_checked\";i:1372274777;s:15:\"version_checked\";s:3:\"3.2\";}','yes'),(109,0,'_site_transient_update_plugins','O:8:\"stdClass\":3:{s:12:\"last_checked\";i:1372274797;s:7:\"checked\";a:3:{s:19:\"akismet/akismet.php\";s:5:\"2.5.3\";s:9:\"hello.php\";s:3:\"1.6\";s:27:\"pretty-link/pretty-link.php\";s:5:\"1.5.2\";}s:8:\"response\";a:1:{s:19:\"akismet/akismet.php\";O:8:\"stdClass\":5:{s:2:\"id\";s:2:\"15\";s:4:\"slug\";s:7:\"akismet\";s:11:\"new_version\";s:5:\"2.5.8\";s:3:\"url\";s:37:\"http://wordpress.org/plugins/akismet/\";s:7:\"package\";s:55:\"http://downloads.wordpress.org/plugin/akismet.2.5.8.zip\";}}}','yes'),(110,0,'_site_transient_update_themes','O:8:\"stdClass\":3:{s:12:\"last_checked\";i:1372274817;s:7:\"checked\";a:2:{s:12:\"twentyeleven\";s:3:\"1.1\";s:9:\"twentyten\";s:3:\"1.2\";}s:8:\"response\";a:2:{s:12:\"twentyeleven\";a:3:{s:11:\"new_version\";s:3:\"1.5\";s:3:\"url\";s:40:\"http://wordpress.org/themes/twentyeleven\";s:7:\"package\";s:57:\"http://wordpress.org/themes/download/twentyeleven.1.5.zip\";}s:9:\"twentyten\";a:3:{s:11:\"new_version\";s:3:\"1.5\";s:3:\"url\";s:37:\"http://wordpress.org/themes/twentyten\";s:7:\"package\";s:54:\"http://wordpress.org/themes/download/twentyten.1.5.zip\";}}}','yes'),(111,0,'widget_pages','a:2:{i:2;a:0:{}s:12:\"_multiwidget\";i:1;}','yes'),(112,0,'widget_calendar','a:2:{i:2;a:0:{}s:12:\"_multiwidget\";i:1;}','yes'),(113,0,'widget_links','a:2:{i:2;a:0:{}s:12:\"_multiwidget\";i:1;}','yes'),(114,0,'widget_tag_cloud','a:2:{i:2;a:0:{}s:12:\"_multiwidget\";i:1;}','yes'),(115,0,'widget_nav_menu','a:2:{i:2;a:0:{}s:12:\"_multiwidget\";i:1;}','yes'),(116,0,'widget_widget_twentyeleven_ephemera','a:2:{i:2;a:0:{}s:12:\"_multiwidget\";i:1;}','yes'),(117,0,'_site_transient_timeout_browser_d9e8a45e78e5d7c5b931564fc5f45dc7','1365625685','yes'),(118,0,'_site_transient_browser_d9e8a45e78e5d7c5b931564fc5f45dc7','a:9:{s:8:\"platform\";s:5:\"Linux\";s:4:\"name\";s:7:\"Firefox\";s:7:\"version\";s:6:\"13.0.1\";s:10:\"update_url\";s:23:\"http://www.firefox.com/\";s:7:\"img_src\";s:50:\"http://s.wordpress.org/images/browsers/firefox.png\";s:11:\"img_src_ssl\";s:49:\"https://wordpress.org/images/browsers/firefox.png\";s:15:\"current_version\";s:2:\"16\";s:7:\"upgrade\";b:1;s:8:\"insecure\";b:0;}','yes'),(119,0,'dashboard_widget_options','a:4:{s:25:\"dashboard_recent_comments\";a:1:{s:5:\"items\";i:5;}s:24:\"dashboard_incoming_links\";a:5:{s:4:\"home\";s:26:\"http://127.0.0.1/wordpress\";s:4:\"link\";s:102:\"http://blogsearch.google.com/blogsearch?scoring=d&partner=wordpress&q=link:http://127.0.0.1/wordpress/\";s:3:\"url\";s:135:\"http://blogsearch.google.com/blogsearch_feeds?scoring=d&ie=utf-8&num=10&output=rss&partner=wordpress&q=link:http://127.0.0.1/wordpress/\";s:5:\"items\";i:10;s:9:\"show_date\";b:0;}s:17:\"dashboard_primary\";a:7:{s:4:\"link\";s:26:\"http://wordpress.org/news/\";s:3:\"url\";s:31:\"http://wordpress.org/news/feed/\";s:5:\"title\";s:14:\"WordPress Blog\";s:5:\"items\";i:2;s:12:\"show_summary\";i:1;s:11:\"show_author\";i:0;s:9:\"show_date\";i:1;}s:19:\"dashboard_secondary\";a:7:{s:4:\"link\";s:28:\"http://planet.wordpress.org/\";s:3:\"url\";s:33:\"http://planet.wordpress.org/feed/\";s:5:\"title\";s:20:\"Other WordPress News\";s:5:\"items\";i:5;s:12:\"show_summary\";i:0;s:11:\"show_author\";i:0;s:9:\"show_date\";i:0;}}','yes'),(122,0,'can_compress_scripts','0','yes'),(157,0,'recently_activated','a:0:{}','yes'),(159,0,'uninstall_plugins','a:2:{i:0;b:0;s:21:\"gigpress/gigpress.php\";s:18:\"gigpress_uninstall\";}','yes'),(160,0,'widget_gigpress','a:2:{i:2;a:0:{}s:12:\"_multiwidget\";i:1;}','yes'),(169,0,'_transient_timeout_feed_3f3acdaaa076c5c8f7ef1e97a6996807','1365117949','no'),(170,0,'_transient_feed_3f3acdaaa076c5c8f7ef1e97a6996807','a:4:{s:5:\"child\";a:1:{s:0:\"\";a:1:{s:3:\"rss\";a:1:{i:0;a:6:{s:4:\"data\";s:4:\"\n  \n\";s:7:\"attribs\";a:1:{s:0:\"\";a:1:{s:7:\"version\";s:3:\"2.0\";}}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";s:5:\"child\";a:1:{s:0:\"\";a:1:{s:7:\"channel\";a:1:{i:0;a:6:{s:4:\"data\";s:33:\"\n    \n    \n    \n    \n    \n    \n  \";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";s:5:\"child\";a:2:{s:0:\"\";a:3:{s:5:\"title\";a:1:{i:0;a:5:{s:4:\"data\";s:57:\"link:http://127.0.0.1/wordpress/ - Google Blog Search\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:4:\"link\";a:1:{i:0;a:5:{s:4:\"data\";s:94:\"http://www.google.com/search?ie=utf-8&q=link:http://127.0.0.1/wordpress/&tbm=blg&tbs=sbd:1\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:11:\"description\";a:1:{i:0;a:5:{s:4:\"data\";s:88:\"Your search - <b>link:http://127.0.0.1/wordpress/</b> - did not match any documents.\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}s:36:\"http://a9.com/-/spec/opensearch/1.1/\";a:3:{s:12:\"totalResults\";a:1:{i:0;a:5:{s:4:\"data\";s:1:\"0\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:10:\"startIndex\";a:1:{i:0;a:5:{s:4:\"data\";s:1:\"1\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}s:12:\"itemsPerPage\";a:1:{i:0;a:5:{s:4:\"data\";s:2:\"10\";s:7:\"attribs\";a:0:{}s:8:\"xml_base\";s:0:\"\";s:17:\"xml_base_explicit\";b:0;s:8:\"xml_lang\";s:0:\"\";}}}}}}}}}}}}s:4:\"type\";i:128;s:7:\"headers\";a:9:{s:12:\"content-type\";s:28:\"text/xml; charset=ISO-8859-1\";s:4:\"date\";s:29:\"Thu, 04 Apr 2013 11:27:55 GMT\";s:7:\"expires\";s:2:\"-1\";s:13:\"cache-control\";s:18:\"private, max-age=0\";s:10:\"set-cookie\";a:2:{i:0;s:143:\"PREF=ID=ae347fc7a472474e:FF=0:TM=1365074875:LM=1365074875:S=J8cHRGQDf6tmdC7r; expires=Sat, 04-Apr-2015 11:27:55 GMT; path=/; domain=.google.com\";i:1;s:212:\"NID=67=iP0pBWJWxATGWK2SY3OgXYfdcH4ZZS_dNceVY0KFIShZxK7MVKvOSwE2fkHu6dngxpRcyKKNtaLt4Nl4pNqoAxAch76F-9MGE5e3W1FTA6jYANHi81cg-R2EvRmuXIEh; expires=Fri, 04-Oct-2013 11:27:55 GMT; path=/; domain=.google.com; HttpOnly\";}s:3:\"p3p\";s:122:\"CP=\"This is not a P3P policy! See http://www.google.com/support/accounts/bin/answer.py?hl=en&answer=151657 for more info.\"\";s:6:\"server\";s:3:\"gws\";s:16:\"x-xss-protection\";s:13:\"1; mode=block\";s:15:\"x-frame-options\";s:10:\"SAMEORIGIN\";}s:5:\"build\";s:14:\"20090627192103\";}','no'),(171,0,'_transient_timeout_feed_mod_3f3acdaaa076c5c8f7ef1e97a6996807','1365117949','no'),(172,0,'_transient_feed_mod_3f3acdaaa076c5c8f7ef1e97a6996807','1365074749','no'),(203,0,'_site_transient_timeout_browser_5ec8c1ed49733eeafd82c0b3a24edcd9','1372182209','yes'),(204,0,'_site_transient_browser_5ec8c1ed49733eeafd82c0b3a24edcd9','a:9:{s:8:\"platform\";s:5:\"Linux\";s:4:\"name\";s:7:\"Firefox\";s:7:\"version\";s:4:\"17.0\";s:10:\"update_url\";s:23:\"http://www.firefox.com/\";s:7:\"img_src\";s:50:\"http://s.wordpress.org/images/browsers/firefox.png\";s:11:\"img_src_ssl\";s:49:\"https://wordpress.org/images/browsers/firefox.png\";s:15:\"current_version\";s:2:\"16\";s:7:\"upgrade\";b:0;s:8:\"insecure\";b:0;}','yes'),(238,0,'_site_transient_timeout_poptags_40cd750bba9870f18aada2478b24840a','1371588232','yes'),(239,0,'_site_transient_poptags_40cd750bba9870f18aada2478b24840a','a:40:{s:6:\"widget\";a:3:{s:4:\"name\";s:6:\"widget\";s:4:\"slug\";s:6:\"widget\";s:5:\"count\";s:4:\"3827\";}s:4:\"post\";a:3:{s:4:\"name\";s:4:\"Post\";s:4:\"slug\";s:4:\"post\";s:5:\"count\";s:4:\"2420\";}s:6:\"plugin\";a:3:{s:4:\"name\";s:6:\"plugin\";s:4:\"slug\";s:6:\"plugin\";s:5:\"count\";s:4:\"2308\";}s:5:\"admin\";a:3:{s:4:\"name\";s:5:\"admin\";s:4:\"slug\";s:5:\"admin\";s:5:\"count\";s:4:\"1914\";}s:5:\"posts\";a:3:{s:4:\"name\";s:5:\"posts\";s:4:\"slug\";s:5:\"posts\";s:5:\"count\";s:4:\"1829\";}s:7:\"sidebar\";a:3:{s:4:\"name\";s:7:\"sidebar\";s:4:\"slug\";s:7:\"sidebar\";s:5:\"count\";s:4:\"1569\";}s:7:\"twitter\";a:3:{s:4:\"name\";s:7:\"twitter\";s:4:\"slug\";s:7:\"twitter\";s:5:\"count\";s:4:\"1305\";}s:6:\"google\";a:3:{s:4:\"name\";s:6:\"google\";s:4:\"slug\";s:6:\"google\";s:5:\"count\";s:4:\"1304\";}s:8:\"comments\";a:3:{s:4:\"name\";s:8:\"comments\";s:4:\"slug\";s:8:\"comments\";s:5:\"count\";s:4:\"1289\";}s:6:\"images\";a:3:{s:4:\"name\";s:6:\"images\";s:4:\"slug\";s:6:\"images\";s:5:\"count\";s:4:\"1244\";}s:4:\"page\";a:3:{s:4:\"name\";s:4:\"page\";s:4:\"slug\";s:4:\"page\";s:5:\"count\";s:4:\"1201\";}s:5:\"image\";a:3:{s:4:\"name\";s:5:\"image\";s:4:\"slug\";s:5:\"image\";s:5:\"count\";s:4:\"1114\";}s:5:\"links\";a:3:{s:4:\"name\";s:5:\"links\";s:4:\"slug\";s:5:\"links\";s:5:\"count\";s:3:\"972\";}s:9:\"shortcode\";a:3:{s:4:\"name\";s:9:\"shortcode\";s:4:\"slug\";s:9:\"shortcode\";s:5:\"count\";s:3:\"960\";}s:8:\"facebook\";a:3:{s:4:\"name\";s:8:\"Facebook\";s:4:\"slug\";s:8:\"facebook\";s:5:\"count\";s:3:\"956\";}s:3:\"seo\";a:3:{s:4:\"name\";s:3:\"seo\";s:4:\"slug\";s:3:\"seo\";s:5:\"count\";s:3:\"929\";}s:9:\"wordpress\";a:3:{s:4:\"name\";s:9:\"wordpress\";s:4:\"slug\";s:9:\"wordpress\";s:5:\"count\";s:3:\"822\";}s:7:\"gallery\";a:3:{s:4:\"name\";s:7:\"gallery\";s:4:\"slug\";s:7:\"gallery\";s:5:\"count\";s:3:\"809\";}s:6:\"social\";a:3:{s:4:\"name\";s:6:\"social\";s:4:\"slug\";s:6:\"social\";s:5:\"count\";s:3:\"763\";}s:3:\"rss\";a:3:{s:4:\"name\";s:3:\"rss\";s:4:\"slug\";s:3:\"rss\";s:5:\"count\";s:3:\"710\";}s:7:\"widgets\";a:3:{s:4:\"name\";s:7:\"widgets\";s:4:\"slug\";s:7:\"widgets\";s:5:\"count\";s:3:\"677\";}s:6:\"jquery\";a:3:{s:4:\"name\";s:6:\"jquery\";s:4:\"slug\";s:6:\"jquery\";s:5:\"count\";s:3:\"670\";}s:5:\"pages\";a:3:{s:4:\"name\";s:5:\"pages\";s:4:\"slug\";s:5:\"pages\";s:5:\"count\";s:3:\"666\";}s:5:\"email\";a:3:{s:4:\"name\";s:5:\"email\";s:4:\"slug\";s:5:\"email\";s:5:\"count\";s:3:\"615\";}s:4:\"ajax\";a:3:{s:4:\"name\";s:4:\"AJAX\";s:4:\"slug\";s:4:\"ajax\";s:5:\"count\";s:3:\"611\";}s:5:\"media\";a:3:{s:4:\"name\";s:5:\"media\";s:4:\"slug\";s:5:\"media\";s:5:\"count\";s:3:\"580\";}s:10:\"javascript\";a:3:{s:4:\"name\";s:10:\"javascript\";s:4:\"slug\";s:10:\"javascript\";s:5:\"count\";s:3:\"560\";}s:5:\"video\";a:3:{s:4:\"name\";s:5:\"video\";s:4:\"slug\";s:5:\"video\";s:5:\"count\";s:3:\"552\";}s:10:\"buddypress\";a:3:{s:4:\"name\";s:10:\"buddypress\";s:4:\"slug\";s:10:\"buddypress\";s:5:\"count\";s:3:\"544\";}s:4:\"feed\";a:3:{s:4:\"name\";s:4:\"feed\";s:4:\"slug\";s:4:\"feed\";s:5:\"count\";s:3:\"534\";}s:7:\"content\";a:3:{s:4:\"name\";s:7:\"content\";s:4:\"slug\";s:7:\"content\";s:5:\"count\";s:3:\"519\";}s:5:\"photo\";a:3:{s:4:\"name\";s:5:\"photo\";s:4:\"slug\";s:5:\"photo\";s:5:\"count\";s:3:\"518\";}s:4:\"link\";a:3:{s:4:\"name\";s:4:\"link\";s:4:\"slug\";s:4:\"link\";s:5:\"count\";s:3:\"497\";}s:6:\"photos\";a:3:{s:4:\"name\";s:6:\"photos\";s:4:\"slug\";s:6:\"photos\";s:5:\"count\";s:3:\"492\";}s:5:\"login\";a:3:{s:4:\"name\";s:5:\"login\";s:4:\"slug\";s:5:\"login\";s:5:\"count\";s:3:\"452\";}s:4:\"spam\";a:3:{s:4:\"name\";s:4:\"spam\";s:4:\"slug\";s:4:\"spam\";s:5:\"count\";s:3:\"451\";}s:8:\"category\";a:3:{s:4:\"name\";s:8:\"category\";s:4:\"slug\";s:8:\"category\";s:5:\"count\";s:3:\"448\";}s:5:\"stats\";a:3:{s:4:\"name\";s:5:\"stats\";s:4:\"slug\";s:5:\"stats\";s:5:\"count\";s:3:\"448\";}s:7:\"youtube\";a:3:{s:4:\"name\";s:7:\"youtube\";s:4:\"slug\";s:7:\"youtube\";s:5:\"count\";s:3:\"431\";}s:5:\"share\";a:3:{s:4:\"name\";s:5:\"Share\";s:4:\"slug\";s:5:\"share\";s:5:\"count\";s:3:\"426\";}}','yes'),(245,0,'prli_options','O:11:\"PrliOptions\":23:{s:16:\"prli_exclude_ips\";s:0:\"\";s:13:\"whitelist_ips\";s:0:\"\";s:13:\"filter_robots\";i:0;s:17:\"extended_tracking\";s:6:\"normal\";s:19:\"prettybar_image_url\";s:86:\"http://127.0.0.1/wordpress/wp-content/plugins/pretty-link/images/pretty-link-48x48.png\";s:30:\"prettybar_background_image_url\";s:83:\"http://127.0.0.1/wordpress/wp-content/plugins/pretty-link/images/bar_background.png\";s:15:\"prettybar_color\";s:0:\"\";s:20:\"prettybar_text_color\";s:6:\"000000\";s:20:\"prettybar_link_color\";s:6:\"0000ee\";s:21:\"prettybar_hover_color\";s:6:\"ababab\";s:23:\"prettybar_visited_color\";s:6:\"551a8b\";s:20:\"prettybar_show_title\";s:1:\"1\";s:26:\"prettybar_show_description\";s:1:\"1\";s:26:\"prettybar_show_share_links\";s:1:\"1\";s:30:\"prettybar_show_target_url_link\";s:1:\"1\";s:21:\"prettybar_title_limit\";s:2:\"25\";s:20:\"prettybar_desc_limit\";s:2:\"30\";s:20:\"prettybar_link_limit\";s:2:\"30\";s:18:\"link_redirect_type\";s:3:\"307\";s:11:\"link_prefix\";i:0;s:13:\"link_track_me\";s:1:\"1\";s:13:\"link_nofollow\";s:1:\"0\";s:16:\"bookmarklet_auth\";s:32:\"efd3354e10ef6281dd3e8c2bc01aca64\";}','yes'),(246,0,'prli_db_version','12','yes'),(247,0,'BWBPhotosmashAdminOptions','a:73:{s:8:\"auto_add\";i:0;s:11:\"img_perpage\";i:0;s:10:\"img_perrow\";i:0;s:23:\"use_wp_upload_functions\";i:1;s:23:\"add_to_wp_media_library\";i:1;s:13:\"max_file_size\";i:0;s:11:\"mini_aspect\";i:0;s:10:\"mini_width\";i:125;s:11:\"mini_height\";i:125;s:12:\"thumb_aspect\";i:0;s:11:\"thumb_width\";i:125;s:12:\"thumb_height\";i:125;s:13:\"medium_aspect\";i:0;s:12:\"medium_width\";i:300;s:13:\"medium_height\";i:300;s:12:\"image_aspect\";i:0;s:11:\"image_width\";i:0;s:12:\"image_height\";i:0;s:12:\"anchor_class\";s:0:\"\";s:7:\"img_rel\";s:15:\"lightbox[album]\";s:8:\"add_text\";s:9:\"Add Photo\";s:15:\"gallery_caption\";s:18:\"PhotoSmash Gallery\";s:19:\"upload_form_caption\";s:26:\"Select an image to upload:\";s:9:\"img_class\";s:9:\"ps_images\";s:12:\"show_caption\";i:1;s:16:\"nofollow_caption\";i:1;s:17:\"alert_all_uploads\";i:0;s:10:\"img_alerts\";i:3600;s:15:\"show_imgcaption\";i:1;s:12:\"contrib_role\";i:10;s:10:\"img_status\";i:0;s:10:\"last_alert\";i:0;s:12:\"use_advanced\";i:0;s:12:\"use_urlfield\";i:0;s:15:\"use_attribution\";i:0;s:14:\"use_customform\";i:0;s:16:\"use_customfields\";i:0;s:12:\"use_thickbox\";i:1;s:18:\"use_alt_ajaxscript\";i:0;s:14:\"alt_ajaxscript\";s:0:\"\";s:14:\"alt_javascript\";s:0:\"\";s:18:\"uploadform_visible\";i:0;s:14:\"use_manualform\";i:0;s:9:\"layout_id\";i:-1;s:17:\"caption_targetnew\";i:0;s:13:\"img_targetnew\";i:0;s:13:\"custom_formid\";i:0;s:12:\"use_donelink\";i:0;s:8:\"css_file\";s:0:\"\";s:19:\"exclude_default_css\";i:0;s:11:\"date_format\";s:5:\"m/d/Y\";s:18:\"upload_authmessage\";s:0:\"\";s:23:\"imglinks_postpages_only\";i:0;s:10:\"sort_field\";i:0;s:10:\"sort_order\";i:1;s:14:\"contrib_gal_on\";i:0;s:22:\"suppress_contrib_posts\";i:0;s:7:\"poll_id\";i:0;s:9:\"favorites\";i:0;s:15:\"rating_position\";i:0;s:17:\"rating_allow_anon\";i:0;s:12:\"mod_send_msg\";i:0;s:15:\"mod_approve_msg\";s:117:\"Thanks for submitting your image to [blogname]! It has been accepted and is now visible in the appropriate galleries.\";s:18:\"mod_reject_message\";s:221:\"Sorry, the image you submitted to [blogname] has been reviewed, but did not meet our submission guidelines.  Please review our guidelines to see what types of images we accept.  We look forward to your future submissions.\";s:7:\"version\";s:5:\"1.0.0\";s:9:\"tb_height\";i:390;s:8:\"tb_width\";i:545;s:10:\"gmap_width\";i:450;s:11:\"gmap_height\";i:350;s:7:\"gmap_js\";b:0;s:11:\"gmap_layout\";s:0:\"\";s:16:\"auto_maptowidget\";i:0;s:10:\"tags_mapid\";b:0;}','yes'),(248,0,'bwbps_cf_stdfields','a:31:{i:0;s:12:\"image_select\";i:1;s:14:\"image_select_2\";i:2;s:12:\"video_select\";i:3;s:6:\"submit\";i:4;s:7:\"caption\";i:5;s:8:\"caption2\";i:6;s:9:\"user_name\";i:7;s:8:\"user_url\";i:8;s:3:\"url\";i:9;s:9:\"thumbnail\";i:10;s:11:\"thumbnail_2\";i:11;s:18:\"user_submitted_url\";i:12;s:4:\"done\";i:13;s:7:\"loading\";i:14;s:7:\"message\";i:15;s:15:\"img_attribution\";i:16;s:11:\"img_license\";i:17;s:13:\"category_name\";i:18;s:13:\"category_link\";i:19;s:11:\"category_id\";i:20;s:7:\"post_id\";i:21;s:14:\"allow_no_image\";i:22;s:8:\"post_cat\";i:23;s:9:\"post_cat1\";i:24;s:9:\"post_cat2\";i:25;s:9:\"post_cat3\";i:26;s:9:\"post_tags\";i:27;s:12:\"tag_dropdown\";i:28;s:8:\"bloginfo\";i:29;s:10:\"plugin_url\";i:30;s:12:\"preview_post\";}','yes'),(249,0,'bwbps_custfield_ver','22','yes'),(250,0,'widget_photosmash-widget','a:2:{i:2;a:0:{}s:12:\"_multiwidget\";i:1;}','yes'),(251,0,'widget_photosmash-tags-widget','a:2:{i:2;a:0:{}s:12:\"_multiwidget\";i:1;}','yes'),(252,0,'widget_psmap-widget','a:2:{i:2;a:0:{}s:12:\"_multiwidget\";i:1;}','yes'),(291,0,'_transient_plugins_delete_result_1','1','yes'),(331,0,'_site_transient_timeout_theme_roots','1372282017','yes'),(332,0,'_site_transient_theme_roots','a:2:{s:12:\"twentyeleven\";s:7:\"/themes\";s:9:\"twentyten\";s:7:\"/themes\";}','yes'),(333,0,'_site_transient_timeout__prli_messages','1372276661','yes'),(334,0,'_site_transient__prli_messages','a:1:{i:0;s:38:\"Add a Pretty Link from your Dashboard:\";}','yes'),(335,0,'_transient_timeout_dash_20494a3d90a6669585674ed0eb8dcd8f','1372318083','no'),(336,0,'_transient_dash_20494a3d90a6669585674ed0eb8dcd8f','<p><strong>RSS Error</strong>: WP HTTP Error: Could not open handle for fopen() to http://blogsearch.google.com/blogsearch_feeds?scoring=d&ie=utf-8&num=10&output=rss&partner=wordpress&q=link:http://127.0.0.1/wordpress/</p>','no'),(337,0,'_transient_timeout_dash_4077549d03da2e451c8b5f002294ff51','1372318083','no'),(338,0,'_transient_dash_4077549d03da2e451c8b5f002294ff51','<div class=\"rss-widget\"><p><strong>RSS Error</strong>: WP HTTP Error: Could not open handle for fopen() to http://wordpress.org/news/feed/</p></div>','no'),(339,0,'_transient_timeout_dash_aa95765b5cc111c56d5993d476b1c2f0','1372318084','no'),(340,0,'_transient_dash_aa95765b5cc111c56d5993d476b1c2f0','<div class=\"rss-widget\"><p><strong>RSS Error</strong>: WP HTTP Error: Could not open handle for fopen() to http://planet.wordpress.org/feed/</p></div>','no'),(341,0,'_transient_timeout_plugin_slugs','1372361376','no'),(342,0,'_transient_plugin_slugs','a:3:{i:0;s:19:\"akismet/akismet.php\";i:1;s:9:\"hello.php\";i:2;s:27:\"pretty-link/pretty-link.php\";}','no'),(343,0,'_transient_timeout_dash_de3249c4736ad3bd2cd29147c4a0d43e','1372318124','no'),(344,0,'_transient_dash_de3249c4736ad3bd2cd29147c4a0d43e','','no'),(350,0,'rewrite_rules','a:71:{s:57:\"wordpress/category/(.+?)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:52:\"index.php?category_name=$matches[1]&feed=$matches[2]\";s:52:\"wordpress/category/(.+?)/(feed|rdf|rss|rss2|atom)/?$\";s:52:\"index.php?category_name=$matches[1]&feed=$matches[2]\";s:45:\"wordpress/category/(.+?)/page/?([0-9]{1,})/?$\";s:53:\"index.php?category_name=$matches[1]&paged=$matches[2]\";s:27:\"wordpress/category/(.+?)/?$\";s:35:\"index.php?category_name=$matches[1]\";s:54:\"wordpress/tag/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:42:\"index.php?tag=$matches[1]&feed=$matches[2]\";s:49:\"wordpress/tag/([^/]+)/(feed|rdf|rss|rss2|atom)/?$\";s:42:\"index.php?tag=$matches[1]&feed=$matches[2]\";s:42:\"wordpress/tag/([^/]+)/page/?([0-9]{1,})/?$\";s:43:\"index.php?tag=$matches[1]&paged=$matches[2]\";s:24:\"wordpress/tag/([^/]+)/?$\";s:25:\"index.php?tag=$matches[1]\";s:55:\"wordpress/type/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:50:\"index.php?post_format=$matches[1]&feed=$matches[2]\";s:50:\"wordpress/type/([^/]+)/(feed|rdf|rss|rss2|atom)/?$\";s:50:\"index.php?post_format=$matches[1]&feed=$matches[2]\";s:43:\"wordpress/type/([^/]+)/page/?([0-9]{1,})/?$\";s:51:\"index.php?post_format=$matches[1]&paged=$matches[2]\";s:25:\"wordpress/type/([^/]+)/?$\";s:33:\"index.php?post_format=$matches[1]\";s:14:\".*wp-atom.php$\";s:19:\"index.php?feed=atom\";s:13:\".*wp-rdf.php$\";s:18:\"index.php?feed=rdf\";s:13:\".*wp-rss.php$\";s:18:\"index.php?feed=rss\";s:14:\".*wp-rss2.php$\";s:19:\"index.php?feed=rss2\";s:14:\".*wp-feed.php$\";s:19:\"index.php?feed=feed\";s:22:\".*wp-commentsrss2.php$\";s:34:\"index.php?feed=rss2&withcomments=1\";s:32:\"feed/(feed|rdf|rss|rss2|atom)/?$\";s:27:\"index.php?&feed=$matches[1]\";s:27:\"(feed|rdf|rss|rss2|atom)/?$\";s:27:\"index.php?&feed=$matches[1]\";s:20:\"page/?([0-9]{1,})/?$\";s:28:\"index.php?&paged=$matches[1]\";s:41:\"comments/feed/(feed|rdf|rss|rss2|atom)/?$\";s:42:\"index.php?&feed=$matches[1]&withcomments=1\";s:36:\"comments/(feed|rdf|rss|rss2|atom)/?$\";s:42:\"index.php?&feed=$matches[1]&withcomments=1\";s:29:\"comments/page/?([0-9]{1,})/?$\";s:28:\"index.php?&paged=$matches[1]\";s:44:\"search/(.+)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:40:\"index.php?s=$matches[1]&feed=$matches[2]\";s:39:\"search/(.+)/(feed|rdf|rss|rss2|atom)/?$\";s:40:\"index.php?s=$matches[1]&feed=$matches[2]\";s:32:\"search/(.+)/page/?([0-9]{1,})/?$\";s:41:\"index.php?s=$matches[1]&paged=$matches[2]\";s:14:\"search/(.+)/?$\";s:23:\"index.php?s=$matches[1]\";s:57:\"wordpress/author/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:50:\"index.php?author_name=$matches[1]&feed=$matches[2]\";s:52:\"wordpress/author/([^/]+)/(feed|rdf|rss|rss2|atom)/?$\";s:50:\"index.php?author_name=$matches[1]&feed=$matches[2]\";s:45:\"wordpress/author/([^/]+)/page/?([0-9]{1,})/?$\";s:51:\"index.php?author_name=$matches[1]&paged=$matches[2]\";s:27:\"wordpress/author/([^/]+)/?$\";s:33:\"index.php?author_name=$matches[1]\";s:84:\"wordpress/date/([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/feed/(feed|rdf|rss|rss2|atom)/?$\";s:80:\"index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]&feed=$matches[4]\";s:79:\"wordpress/date/([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/(feed|rdf|rss|rss2|atom)/?$\";s:80:\"index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]&feed=$matches[4]\";s:72:\"wordpress/date/([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/page/?([0-9]{1,})/?$\";s:81:\"index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]&paged=$matches[4]\";s:54:\"wordpress/date/([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/?$\";s:63:\"index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]\";s:71:\"wordpress/date/([0-9]{4})/([0-9]{1,2})/feed/(feed|rdf|rss|rss2|atom)/?$\";s:64:\"index.php?year=$matches[1]&monthnum=$matches[2]&feed=$matches[3]\";s:66:\"wordpress/date/([0-9]{4})/([0-9]{1,2})/(feed|rdf|rss|rss2|atom)/?$\";s:64:\"index.php?year=$matches[1]&monthnum=$matches[2]&feed=$matches[3]\";s:59:\"wordpress/date/([0-9]{4})/([0-9]{1,2})/page/?([0-9]{1,})/?$\";s:65:\"index.php?year=$matches[1]&monthnum=$matches[2]&paged=$matches[3]\";s:41:\"wordpress/date/([0-9]{4})/([0-9]{1,2})/?$\";s:47:\"index.php?year=$matches[1]&monthnum=$matches[2]\";s:58:\"wordpress/date/([0-9]{4})/feed/(feed|rdf|rss|rss2|atom)/?$\";s:43:\"index.php?year=$matches[1]&feed=$matches[2]\";s:53:\"wordpress/date/([0-9]{4})/(feed|rdf|rss|rss2|atom)/?$\";s:43:\"index.php?year=$matches[1]&feed=$matches[2]\";s:46:\"wordpress/date/([0-9]{4})/page/?([0-9]{1,})/?$\";s:44:\"index.php?year=$matches[1]&paged=$matches[2]\";s:28:\"wordpress/date/([0-9]{4})/?$\";s:26:\"index.php?year=$matches[1]\";s:38:\"wordpress/[0-9]+/attachment/([^/]+)/?$\";s:32:\"index.php?attachment=$matches[1]\";s:48:\"wordpress/[0-9]+/attachment/([^/]+)/trackback/?$\";s:37:\"index.php?attachment=$matches[1]&tb=1\";s:68:\"wordpress/[0-9]+/attachment/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:49:\"index.php?attachment=$matches[1]&feed=$matches[2]\";s:63:\"wordpress/[0-9]+/attachment/([^/]+)/(feed|rdf|rss|rss2|atom)/?$\";s:49:\"index.php?attachment=$matches[1]&feed=$matches[2]\";s:63:\"wordpress/[0-9]+/attachment/([^/]+)/comment-page-([0-9]{1,})/?$\";s:50:\"index.php?attachment=$matches[1]&cpage=$matches[2]\";s:31:\"wordpress/([0-9]+)/trackback/?$\";s:28:\"index.php?p=$matches[1]&tb=1\";s:51:\"wordpress/([0-9]+)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:40:\"index.php?p=$matches[1]&feed=$matches[2]\";s:46:\"wordpress/([0-9]+)/(feed|rdf|rss|rss2|atom)/?$\";s:40:\"index.php?p=$matches[1]&feed=$matches[2]\";s:39:\"wordpress/([0-9]+)/page/?([0-9]{1,})/?$\";s:41:\"index.php?p=$matches[1]&paged=$matches[2]\";s:46:\"wordpress/([0-9]+)/comment-page-([0-9]{1,})/?$\";s:41:\"index.php?p=$matches[1]&cpage=$matches[2]\";s:31:\"wordpress/([0-9]+)(/[0-9]+)?/?$\";s:40:\"index.php?p=$matches[1]&page=$matches[2]\";s:27:\"wordpress/[0-9]+/([^/]+)/?$\";s:32:\"index.php?attachment=$matches[1]\";s:37:\"wordpress/[0-9]+/([^/]+)/trackback/?$\";s:37:\"index.php?attachment=$matches[1]&tb=1\";s:57:\"wordpress/[0-9]+/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:49:\"index.php?attachment=$matches[1]&feed=$matches[2]\";s:52:\"wordpress/[0-9]+/([^/]+)/(feed|rdf|rss|rss2|atom)/?$\";s:49:\"index.php?attachment=$matches[1]&feed=$matches[2]\";s:52:\"wordpress/[0-9]+/([^/]+)/comment-page-([0-9]{1,})/?$\";s:50:\"index.php?attachment=$matches[1]&cpage=$matches[2]\";s:25:\".+?/attachment/([^/]+)/?$\";s:32:\"index.php?attachment=$matches[1]\";s:35:\".+?/attachment/([^/]+)/trackback/?$\";s:37:\"index.php?attachment=$matches[1]&tb=1\";s:55:\".+?/attachment/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:49:\"index.php?attachment=$matches[1]&feed=$matches[2]\";s:50:\".+?/attachment/([^/]+)/(feed|rdf|rss|rss2|atom)/?$\";s:49:\"index.php?attachment=$matches[1]&feed=$matches[2]\";s:50:\".+?/attachment/([^/]+)/comment-page-([0-9]{1,})/?$\";s:50:\"index.php?attachment=$matches[1]&cpage=$matches[2]\";s:18:\"(.+?)/trackback/?$\";s:35:\"index.php?pagename=$matches[1]&tb=1\";s:38:\"(.+?)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:47:\"index.php?pagename=$matches[1]&feed=$matches[2]\";s:33:\"(.+?)/(feed|rdf|rss|rss2|atom)/?$\";s:47:\"index.php?pagename=$matches[1]&feed=$matches[2]\";s:26:\"(.+?)/page/?([0-9]{1,})/?$\";s:48:\"index.php?pagename=$matches[1]&paged=$matches[2]\";s:33:\"(.+?)/comment-page-([0-9]{1,})/?$\";s:48:\"index.php?pagename=$matches[1]&cpage=$matches[2]\";s:18:\"(.+?)(/[0-9]+)?/?$\";s:47:\"index.php?pagename=$matches[1]&page=$matches[2]\";}','yes');
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
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_postmeta`
--

LOCK TABLES `wp_postmeta` WRITE;
/*!40000 ALTER TABLE `wp_postmeta` DISABLE KEYS */;
INSERT INTO `wp_postmeta` VALUES (1,2,'_wp_page_template','default');
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
  KEY `post_parent` (`post_parent`),
  KEY `post_author` (`post_author`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_posts`
--

LOCK TABLES `wp_posts` WRITE;
/*!40000 ALTER TABLE `wp_posts` DISABLE KEYS */;
INSERT INTO `wp_posts` VALUES (1,1,'2013-04-03 18:06:12','2013-04-03 18:06:12','Welcome to WordPress. This is your first post. Edit or delete it, then start blogging!','Hello world!','','publish','open','open','','hello-world','','','2013-04-03 18:06:12','2013-04-03 18:06:12','',0,'http://127.0.0.1/wordpress/?p=1',0,'post','',1),(2,1,'2013-04-03 18:06:12','2013-04-03 18:06:12','This is an example page. It\'s different from a blog post because it will stay in one place and will show up in your site navigation (in most themes). Most people start with an About page that introduces them to potential site visitors. It might say something like this:\n\n<blockquote>Hi there! I\'m a bike messenger by day, aspiring actor by night, and this is my blog. I live in Los Angeles, have a great dog named Jack, and I like pi&#241;a coladas. (And gettin\' caught in the rain.)</blockquote>\n\n...or something like this:\n\n<blockquote>The XYZ Doohickey Company was founded in 1971, and has been providing quality doohickies to the public ever since. Located in Gotham City, XYZ employs over 2,000 people and does all kinds of awesome things for the Gotham community.</blockquote>\n\nAs a new WordPress user, you should go to <a href=\"http://127.0.0.1/wordpress/wp-admin/\">your dashboard</a> to delete this page and create new pages for your content. Have fun!','Sample Page','','publish','open','open','','sample-page','','','2013-04-03 18:06:12','2013-04-03 18:06:12','',0,'http://127.0.0.1/wordpress/?page_id=2',0,'page','',0),(3,1,'2013-04-03 20:28:05','0000-00-00 00:00:00','','Auto Draft','','auto-draft','open','open','','','','','2013-04-03 20:28:05','0000-00-00 00:00:00','',0,'http://127.0.0.1/wordpress/?p=3',0,'post','',0),(8,2,'2013-04-04 11:31:44','0000-00-00 00:00:00','','Auto Draft','','auto-draft','open','open','','','','','2013-04-04 11:31:44','0000-00-00 00:00:00','',0,'http://127.0.0.1/wordpress/?p=8',0,'post','',0);
/*!40000 ALTER TABLE `wp_posts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_prli_clicks`
--

DROP TABLE IF EXISTS `wp_prli_clicks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_prli_clicks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(255) DEFAULT NULL,
  `browser` varchar(255) DEFAULT NULL,
  `btype` varchar(255) DEFAULT NULL,
  `bversion` varchar(255) DEFAULT NULL,
  `os` varchar(255) DEFAULT NULL,
  `referer` varchar(255) DEFAULT NULL,
  `host` varchar(255) DEFAULT NULL,
  `uri` varchar(255) DEFAULT NULL,
  `robot` tinyint(4) DEFAULT '0',
  `first_click` tinyint(4) DEFAULT '0',
  `created_at` datetime NOT NULL,
  `link_id` int(11) DEFAULT NULL,
  `vuid` varchar(25) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `link_id` (`link_id`),
  KEY `ip` (`ip`),
  KEY `browser` (`browser`),
  KEY `btype` (`btype`),
  KEY `bversion` (`bversion`),
  KEY `os` (`os`),
  KEY `referer` (`referer`),
  KEY `host` (`host`),
  KEY `uri` (`uri`),
  KEY `robot` (`robot`),
  KEY `first_click` (`first_click`),
  KEY `vuid` (`vuid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_prli_clicks`
--

LOCK TABLES `wp_prli_clicks` WRITE;
/*!40000 ALTER TABLE `wp_prli_clicks` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_prli_clicks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_prli_groups`
--

DROP TABLE IF EXISTS `wp_prli_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_prli_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `description` text,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_prli_groups`
--

LOCK TABLES `wp_prli_groups` WRITE;
/*!40000 ALTER TABLE `wp_prli_groups` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_prli_groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_prli_link_metas`
--

DROP TABLE IF EXISTS `wp_prli_link_metas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_prli_link_metas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `meta_key` varchar(255) DEFAULT NULL,
  `meta_value` longtext,
  `link_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `meta_key` (`meta_key`),
  KEY `link_id` (`link_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_prli_link_metas`
--

LOCK TABLES `wp_prli_link_metas` WRITE;
/*!40000 ALTER TABLE `wp_prli_link_metas` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_prli_link_metas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_prli_links`
--

DROP TABLE IF EXISTS `wp_prli_links`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_prli_links` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `description` text,
  `url` text,
  `slug` varchar(255) DEFAULT NULL,
  `nofollow` tinyint(1) DEFAULT '0',
  `track_me` tinyint(1) DEFAULT '1',
  `param_forwarding` varchar(255) DEFAULT NULL,
  `param_struct` varchar(255) DEFAULT NULL,
  `redirect_type` varchar(255) DEFAULT '307',
  `created_at` datetime NOT NULL,
  `group_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `group_id` (`group_id`),
  KEY `name` (`name`),
  KEY `nofollow` (`nofollow`),
  KEY `track_me` (`track_me`),
  KEY `param_forwarding` (`param_forwarding`),
  KEY `param_struct` (`param_struct`),
  KEY `redirect_type` (`redirect_type`),
  KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_prli_links`
--

LOCK TABLES `wp_prli_links` WRITE;
/*!40000 ALTER TABLE `wp_prli_links` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_prli_links` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_schreikasten`
--

DROP TABLE IF EXISTS `wp_schreikasten`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_schreikasten` (
  `id` bigint(1) NOT NULL AUTO_INCREMENT,
  `alias` tinytext NOT NULL,
  `text` text NOT NULL,
  `date` datetime NOT NULL,
  `ip` char(32) NOT NULL,
  `status` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `email` tinytext NOT NULL,
  `reply` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_schreikasten`
--

LOCK TABLES `wp_schreikasten` WRITE;
/*!40000 ALTER TABLE `wp_schreikasten` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_schreikasten` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_schreikasten_blacklist`
--

DROP TABLE IF EXISTS `wp_schreikasten_blacklist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_schreikasten_blacklist` (
  `id` bigint(1) NOT NULL AUTO_INCREMENT,
  `pc` bigint(1) NOT NULL,
  `date` datetime NOT NULL,
  `forever` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_schreikasten_blacklist`
--

LOCK TABLES `wp_schreikasten_blacklist` WRITE;
/*!40000 ALTER TABLE `wp_schreikasten_blacklist` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_schreikasten_blacklist` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_usermeta`
--

LOCK TABLES `wp_usermeta` WRITE;
/*!40000 ALTER TABLE `wp_usermeta` DISABLE KEYS */;
INSERT INTO `wp_usermeta` VALUES (1,1,'first_name',''),(2,1,'last_name',''),(3,1,'nickname','wpadmin'),(4,1,'description',''),(5,1,'rich_editing','true'),(6,1,'comment_shortcuts','false'),(7,1,'admin_color','fresh'),(8,1,'use_ssl','0'),(9,1,'show_admin_bar_front','true'),(10,1,'show_admin_bar_admin','false'),(11,1,'aim',''),(12,1,'yim',''),(13,1,'jabber',''),(14,1,'wp_capabilities','a:1:{s:13:\"administrator\";s:1:\"1\";}'),(15,1,'wp_user_level','10'),(16,1,'wp_user-settings','editor=html'),(17,1,'wp_user-settings-time','1365020880'),(18,1,'wp_dashboard_quick_press_last_post_id','3'),(19,2,'first_name',''),(20,2,'last_name',''),(21,2,'nickname','contributor'),(22,2,'description',''),(23,2,'rich_editing','true'),(24,2,'comment_shortcuts','false'),(25,2,'admin_color','fresh'),(26,2,'use_ssl','0'),(27,2,'show_admin_bar_front','true'),(28,2,'show_admin_bar_admin','false'),(29,2,'aim',''),(30,2,'yim',''),(31,2,'jabber',''),(32,2,'wp_capabilities','a:1:{s:11:\"contributor\";s:1:\"1\";}'),(33,2,'wp_user_level','1'),(34,2,'wp_dashboard_quick_press_last_post_id','8'),(35,1,'gigpress_sort','DESC');
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_users`
--

LOCK TABLES `wp_users` WRITE;
/*!40000 ALTER TABLE `wp_users` DISABLE KEYS */;
INSERT INTO `wp_users` VALUES (1,'wpadmin','$P$B8PK7VaBkB4/draLuLsEBT93UmKxOy.','wpadmin','gnilson@terpmail.umd.edu','','2013-04-03 18:06:12','',0,'wpadmin'),(2,'contributor','$P$BYoUpbVts1TQ1k5M4F04juk.arKNPt0','contributor','contributor@terpmail.umd.edu','','2013-04-03 20:42:13','',0,'contributor');
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

-- Dump completed on 2013-06-26 15:44:44
