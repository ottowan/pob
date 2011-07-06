/*
SQLyog Ultimate v8.32 
MySQL - 5.5.8 : Database - testzikula
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

/*Table structure for table `z_admin_category` */

CREATE TABLE `z_admin_category` (
  `pn_cid` int(11) NOT NULL AUTO_INCREMENT,
  `pn_name` varchar(32) NOT NULL DEFAULT '',
  `pn_description` varchar(254) NOT NULL DEFAULT '',
  PRIMARY KEY (`pn_cid`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

/*Data for the table `z_admin_category` */

insert  into `z_admin_category`(`pn_cid`,`pn_name`,`pn_description`) values (1,'System','Core modules at the heart of operation of the site.'),(2,'Layout','Layout modules for controlling the site\'s look and feel.'),(3,'Users','Modules for controlling user membership, access rights and profiles.'),(4,'Content','Modules for providing content to your users.'),(5,'3rd-party','3rd-party add-on modules and newly-installed modules.'),(6,'Security','Modules for managing the site\'s security.'),(7,'Hooked','Auxiliary modules designed to be hooked to other modules, to give them added functionality.');

/*Table structure for table `z_admin_module` */

CREATE TABLE `z_admin_module` (
  `pn_amid` int(11) NOT NULL AUTO_INCREMENT,
  `pn_mid` int(11) NOT NULL DEFAULT '0',
  `pn_cid` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`pn_amid`),
  KEY `mid_cid` (`pn_mid`,`pn_cid`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;

/*Data for the table `z_admin_module` */

insert  into `z_admin_module`(`pn_amid`,`pn_mid`,`pn_cid`) values (1,1,1),(2,2,1),(3,13,3),(4,8,3),(5,5,2),(6,11,1),(7,21,3),(8,20,2),(9,18,1),(10,3,4),(11,17,6),(12,26,4),(13,6,4),(14,9,2),(15,24,4),(16,10,1),(17,7,1),(18,15,2),(19,14,1),(20,16,4),(21,22,1),(22,12,1),(23,19,6);

/*Table structure for table `z_block_placements` */

DROP TABLE IF EXISTS `z_block_placements`;

CREATE TABLE `z_block_placements` (
  `pn_pid` int(11) NOT NULL DEFAULT '0',
  `pn_bid` int(11) NOT NULL DEFAULT '0',
  `pn_order` int(11) NOT NULL DEFAULT '0',
  KEY `bid_pid_idx` (`pn_bid`,`pn_pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `z_block_placements` */

insert  into `z_block_placements`(`pn_pid`,`pn_bid`,`pn_order`) values (1,1,0),(1,2,0),(2,3,0),(2,4,0),(3,5,0);

/*Table structure for table `z_block_positions` */

CREATE TABLE `z_block_positions` (
  `pn_pid` int(11) NOT NULL AUTO_INCREMENT,
  `pn_name` varchar(255) NOT NULL DEFAULT '',
  `pn_description` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`pn_pid`),
  KEY `name_idx` (`pn_name`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Data for the table `z_block_positions` */

insert  into `z_block_positions`(`pn_pid`,`pn_name`,`pn_description`) values (1,'left','Left blocks'),(2,'right','Right blocks'),(3,'center','Centre blocks');

/*Table structure for table `z_blocks` */

CREATE TABLE `z_blocks` (
  `pn_bid` int(11) NOT NULL AUTO_INCREMENT,
  `pn_bkey` varchar(255) NOT NULL DEFAULT '',
  `pn_title` varchar(255) NOT NULL DEFAULT '',
  `pn_content` longtext NOT NULL,
  `pn_url` longtext NOT NULL,
  `pn_mid` int(11) NOT NULL DEFAULT '0',
  `pn_filter` longtext NOT NULL,
  `pn_active` tinyint(4) NOT NULL DEFAULT '1',
  `pn_collapsable` int(11) NOT NULL DEFAULT '1',
  `pn_defaultstate` int(11) NOT NULL DEFAULT '1',
  `pn_refresh` int(11) NOT NULL DEFAULT '0',
  `pn_last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `pn_language` varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`pn_bid`),
  KEY `active_idx` (`pn_active`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

/*Data for the table `z_blocks` */

insert  into `z_blocks`(`pn_bid`,`pn_bkey`,`pn_title`,`pn_content`,`pn_url`,`pn_mid`,`pn_filter`,`pn_active`,`pn_collapsable`,`pn_defaultstate`,`pn_refresh`,`pn_last_update`,`pn_language`) values (1,'extmenu','Main menu','a:5:{s:14:\"displaymodules\";s:1:\"0\";s:10:\"stylesheet\";s:11:\"extmenu.css\";s:8:\"template\";s:24:\"blocks_block_extmenu.htm\";s:11:\"blocktitles\";a:1:{s:2:\"en\";s:9:\"Main menu\";}s:5:\"links\";a:1:{s:2:\"en\";a:4:{i:0;a:7:{s:4:\"name\";s:4:\"Home\";s:3:\"url\";s:10:\"{homepage}\";s:5:\"title\";s:26:\"Go to the site\'s home page\";s:5:\"level\";i:0;s:6:\"parent\";i:0;s:5:\"image\";s:0:\"\";s:6:\"active\";s:1:\"1\";}i:1;a:7:{s:4:\"name\";s:16:\"Site admin panel\";s:3:\"url\";s:24:\"{Admin:adminpanel:admin}\";s:5:\"title\";s:26:\"Go to the site admin panel\";s:5:\"level\";i:0;s:6:\"parent\";i:0;s:5:\"image\";s:0:\"\";s:6:\"active\";s:1:\"1\";}i:2;a:7:{s:4:\"name\";s:18:\"User account panel\";s:3:\"url\";s:7:\"{Users}\";s:5:\"title\";s:29:\"Go to your user account panel\";s:5:\"level\";i:0;s:6:\"parent\";i:0;s:5:\"image\";s:0:\"\";s:6:\"active\";s:1:\"1\";}i:3;a:7:{s:4:\"name\";s:7:\"Log out\";s:3:\"url\";s:14:\"{Users:logout}\";s:5:\"title\";s:28:\"Log out of your user account\";s:5:\"level\";i:0;s:6:\"parent\";i:0;s:5:\"image\";s:0:\"\";s:6:\"active\";s:1:\"1\";}}}}','',5,'',1,1,1,3600,'2011-07-01 06:28:09',''),(2,'thelang','Languages','','',5,'',1,1,1,3600,'2011-07-01 06:28:09',''),(3,'login','User log-in','','',21,'',1,1,1,3600,'2011-07-01 06:28:09',''),(4,'online','Who\'s on-line','','',21,'',1,1,1,3600,'2011-07-01 06:28:09',''),(5,'messages','Admin messages','','',3,'',1,1,1,3600,'2011-07-01 06:28:09','');

/*Table structure for table `z_categories_category` */

CREATE TABLE `z_categories_category` (
  `cat_id` int(11) NOT NULL AUTO_INCREMENT,
  `cat_parent_id` int(11) NOT NULL DEFAULT '1',
  `cat_is_locked` tinyint(4) NOT NULL DEFAULT '0',
  `cat_is_leaf` tinyint(4) NOT NULL DEFAULT '0',
  `cat_name` varchar(255) NOT NULL DEFAULT '',
  `cat_value` varchar(255) NOT NULL DEFAULT '',
  `cat_sort_value` int(11) NOT NULL DEFAULT '0',
  `cat_display_name` text,
  `cat_display_desc` text,
  `cat_path` text,
  `cat_ipath` varchar(255) NOT NULL DEFAULT '',
  `cat_status` varchar(1) NOT NULL DEFAULT 'A',
  `cat_obj_status` varchar(1) NOT NULL DEFAULT 'A',
  `cat_cr_date` datetime NOT NULL DEFAULT '1970-01-01 00:00:00',
  `cat_cr_uid` int(11) NOT NULL DEFAULT '0',
  `cat_lu_date` datetime NOT NULL DEFAULT '1970-01-01 00:00:00',
  `cat_lu_uid` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`cat_id`),
  KEY `idx_categories_parent` (`cat_parent_id`),
  KEY `idx_categories_is_leaf` (`cat_is_leaf`),
  KEY `idx_categories_name` (`cat_name`),
  KEY `idx_categories_ipath` (`cat_ipath`,`cat_is_leaf`,`cat_status`),
  KEY `idx_categories_status` (`cat_status`),
  KEY `idx_categories_ipath_status` (`cat_ipath`,`cat_status`)
) ENGINE=MyISAM AUTO_INCREMENT=10000 DEFAULT CHARSET=utf8;

/*Data for the table `z_categories_category` */

insert  into `z_categories_category`(`cat_id`,`cat_parent_id`,`cat_is_locked`,`cat_is_leaf`,`cat_name`,`cat_value`,`cat_sort_value`,`cat_display_name`,`cat_display_desc`,`cat_path`,`cat_ipath`,`cat_status`,`cat_obj_status`,`cat_cr_date`,`cat_cr_uid`,`cat_lu_date`,`cat_lu_uid`) values (1,0,1,0,'__SYSTEM__','',0,'b:0;','b:0;','/__SYSTEM__','/1','A','A','2011-07-01 06:28:10',0,'2011-07-01 06:28:10',0),(2,1,0,0,'Modules','',0,'a:1:{s:2:\"en\";s:7:\"Modules\";}','a:1:{s:3:\"eng\";s:0:\"\";}','/__SYSTEM__/Modules','/1/2','A','A','2011-07-01 06:28:10',0,'2011-07-01 06:28:10',0),(3,1,0,0,'General','',0,'a:1:{s:2:\"en\";s:7:\"General\";}','a:1:{s:3:\"eng\";s:0:\"\";}','/__SYSTEM__/General','/1/3','A','A','2011-07-01 06:28:10',0,'2011-07-01 06:28:10',0),(4,3,0,0,'YesNo','',0,'a:1:{s:2:\"en\";s:6:\"Yes/No\";}','a:1:{s:3:\"eng\";s:0:\"\";}','/__SYSTEM__/General/YesNo','/1/3/4','A','A','2011-07-01 06:28:10',0,'2011-07-01 06:28:10',0),(5,4,0,1,'1 - Yes','',0,'b:0;','b:0;','/__SYSTEM__/General/YesNo/1 - Yes','/1/3/4/5','A','A','2011-07-01 06:28:10',0,'2011-07-01 06:28:10',0),(6,4,0,1,'2 - No','',0,'b:0;','b:0;','/__SYSTEM__/General/YesNo/2 - No','/1/3/4/6','A','A','2011-07-01 06:28:10',0,'2011-07-01 06:28:10',0),(10,3,0,0,'Publication Status (extended)','',0,'a:1:{s:2:\"en\";s:29:\"Publication status (extended)\";}','a:1:{s:3:\"eng\";s:0:\"\";}','/__SYSTEM__/General/Publication Status Extended','/1/3/10','A','A','2011-07-01 06:28:10',0,'2011-07-01 06:28:10',0),(11,10,0,1,'Pending','',0,'a:1:{s:2:\"en\";s:7:\"Pending\";}','a:1:{s:3:\"eng\";s:0:\"\";}','/__SYSTEM__/General/Publication Status Extended/Pending','/1/3/10/11','A','A','2011-07-01 06:28:10',0,'2011-07-01 06:28:10',0),(12,10,0,1,'Checked','',0,'a:1:{s:2:\"en\";s:7:\"Checked\";}','a:1:{s:3:\"eng\";s:0:\"\";}','/__SYSTEM__/General/Publication Status Extended/Checked','/1/3/10/12','A','A','2011-07-01 06:28:10',0,'2011-07-01 06:28:10',0),(13,10,0,1,'Approved','',0,'a:1:{s:2:\"en\";s:8:\"Approved\";}','a:1:{s:3:\"eng\";s:0:\"\";}','/__SYSTEM__/General/Publication Status Extended/Approved','/1/3/10/13','A','A','2011-07-01 06:28:10',0,'2011-07-01 06:28:10',0),(14,10,0,1,'On-line','',0,'a:1:{s:2:\"en\";s:7:\"On-line\";}','a:1:{s:3:\"eng\";s:0:\"\";}','/__SYSTEM__/General/Publication Status Extended/Online','/1/3/10/14','A','A','2011-07-01 06:28:10',0,'2011-07-01 06:28:10',0),(15,10,0,1,'Rejected','',0,'a:1:{s:2:\"en\";s:8:\"Rejected\";}','a:1:{s:3:\"eng\";s:0:\"\";}','/__SYSTEM__/General/Publication Status Extended/Rejected','/1/3/10/15','A','A','2011-07-01 06:28:10',0,'2011-07-01 06:28:10',0),(16,3,0,0,'Gender','',0,'a:1:{s:2:\"en\";s:6:\"Gender\";}','a:1:{s:3:\"eng\";s:0:\"\";}','/__SYSTEM__/General/Gender','/1/3/16','A','A','2011-07-01 06:28:10',0,'2011-07-01 06:28:10',0),(17,16,0,1,'Male','',0,'a:1:{s:2:\"en\";s:4:\"Male\";}','a:1:{s:3:\"eng\";s:0:\"\";}','/__SYSTEM__/General/Gender/Male','/1/3/16/17','A','A','2011-07-01 06:28:10',0,'2011-07-01 06:28:10',0),(18,16,0,1,'Female','',0,'a:1:{s:2:\"en\";s:6:\"Female\";}','a:1:{s:3:\"eng\";s:0:\"\";}','/__SYSTEM__/General/Gender/Female','/1/3/16/18','A','A','2011-07-01 06:28:10',0,'2011-07-01 06:28:10',0),(19,3,0,0,'Title','',0,'a:1:{s:2:\"en\";s:5:\"Title\";}','a:1:{s:3:\"eng\";s:0:\"\";}','/__SYSTEM__/General/Title','/1/3/19','A','A','2011-07-01 06:28:10',0,'2011-07-01 06:28:10',0),(20,19,0,1,'Mr','',0,'a:1:{s:2:\"en\";s:3:\"Mr.\";}','a:1:{s:3:\"eng\";s:0:\"\";}','/__SYSTEM__/General/Title/Mr','/1/3/19/20','A','A','2011-07-01 06:28:10',0,'2011-07-01 06:28:10',0),(21,19,0,1,'Mrs','',0,'a:1:{s:2:\"en\";s:4:\"Mrs.\";}','a:1:{s:3:\"eng\";s:0:\"\";}','/__SYSTEM__/General/Title/Mrs','/1/3/19/21','A','A','2011-07-01 06:28:10',0,'2011-07-01 06:28:10',0),(22,19,0,1,'Ms','',0,'a:1:{s:2:\"en\";s:3:\"Ms.\";}','a:1:{s:3:\"eng\";s:0:\"\";}','/__SYSTEM__/General/Title/Ms','/1/3/19/22','A','A','2011-07-01 06:28:10',0,'2011-07-01 06:28:10',0),(23,19,0,1,'Miss','',0,'a:1:{s:2:\"en\";s:4:\"Miss\";}','a:1:{s:3:\"eng\";s:0:\"\";}','/__SYSTEM__/General/Title/Miss','/1/3/19/23','A','A','2011-07-01 06:28:10',0,'2011-07-01 06:28:10',0),(24,19,0,1,'Dr','',0,'a:1:{s:2:\"en\";s:3:\"Dr.\";}','a:1:{s:3:\"eng\";s:0:\"\";}','/__SYSTEM__/General/Title/Dr','/1/3/19/24','A','A','2011-07-01 06:28:10',0,'2011-07-01 06:28:10',0),(25,3,0,0,'ActiveStatus','',0,'a:1:{s:2:\"en\";s:15:\"Activity status\";}','a:1:{s:3:\"eng\";s:0:\"\";}','/__SYSTEM__/General/ActiveStatus','/1/3/25','A','A','2011-07-01 06:28:10',0,'2011-07-01 06:28:10',0),(26,23,0,1,'Active','',0,'a:1:{s:2:\"en\";s:6:\"Active\";}','a:1:{s:3:\"eng\";s:0:\"\";}','/__SYSTEM__/General/ActiveStatus/Active','/1/3/25/26','A','A','2011-07-01 06:28:10',0,'2011-07-01 06:28:10',0),(27,23,0,1,'Inactive','',0,'a:1:{s:2:\"en\";s:8:\"Inactive\";}','a:1:{s:3:\"eng\";s:0:\"\";}','/__SYSTEM__/General/ActiveStatus/Inactive','/1/3/25/27','A','A','2011-07-01 06:28:10',0,'2011-07-01 06:28:10',0),(28,3,0,0,'Publication status (basic)','',0,'a:1:{s:2:\"en\";s:26:\"Publication status (basic)\";}','a:1:{s:3:\"eng\";s:0:\"\";}','/__SYSTEM__/General/Publication Status Basic','/1/3/28','A','A','2011-07-01 06:28:10',0,'2011-07-01 06:28:10',0),(29,28,0,1,'Pending','',0,'a:1:{s:2:\"en\";s:7:\"Pending\";}','a:1:{s:3:\"eng\";s:0:\"\";}','/__SYSTEM__/General/Publication Status Basic/Pending','/1/3/28/29','A','A','2011-07-01 06:28:10',0,'2011-07-01 06:28:10',0),(30,28,0,1,'Approved','',0,'a:1:{s:2:\"en\";s:8:\"Approved\";}','a:1:{s:3:\"eng\";s:0:\"\";}','/__SYSTEM__/General/Publication Status Basic/Approved','/1/3/28/30','A','A','2011-07-01 06:28:11',0,'2011-07-01 06:28:11',0),(31,1,0,0,'Users','',0,'a:1:{s:2:\"en\";s:5:\"Users\";}','a:1:{s:3:\"eng\";s:0:\"\";}','/__SYSTEM__/Users','/1/31','A','A','2011-07-01 06:28:11',0,'2011-07-01 06:28:11',0),(32,2,0,0,'Global','',0,'a:1:{s:2:\"en\";s:6:\"Global\";}','a:1:{s:3:\"eng\";s:0:\"\";}','/__SYSTEM__/Modules/Global','/1/2/32','A','A','2011-07-01 06:28:11',0,'2011-07-01 06:28:11',0),(33,32,0,1,'Blogging','',0,'a:1:{s:2:\"en\";s:8:\"Blogging\";}','a:1:{s:3:\"eng\";s:0:\"\";}','/__SYSTEM__/Modules/Global/Blogging','/1/2/32/33','A','A','2011-07-01 06:28:11',0,'2011-07-01 06:28:11',0),(34,32,0,1,'Music and audio','',0,'a:1:{s:2:\"en\";s:15:\"Music and audio\";}','a:1:{s:3:\"eng\";s:0:\"\";}','/__SYSTEM__/Modules/Global/MusicAndAudio','/1/2/32/34','A','A','2011-07-01 06:28:11',0,'2011-07-01 06:28:11',0),(35,32,0,1,'Art and photography','',0,'a:1:{s:2:\"en\";s:19:\"Art and photography\";}','a:1:{s:3:\"eng\";s:0:\"\";}','/__SYSTEM__/Modules/Global/ArtAndPhotography','/1/2/32/35','A','A','2011-07-01 06:28:11',0,'2011-07-01 06:28:11',0),(36,32,0,1,'Writing and thinking','',0,'a:1:{s:2:\"en\";s:20:\"Writing and thinking\";}','a:1:{s:3:\"eng\";s:0:\"\";}','/__SYSTEM__/Modules/Global/WritingAndThinking','/1/2/32/36','A','A','2011-07-01 06:28:11',0,'2011-07-01 06:28:11',0),(37,32,0,1,'Communications and media','',0,'a:1:{s:2:\"en\";s:24:\"Communications and media\";}','a:1:{s:3:\"eng\";s:0:\"\";}','/__SYSTEM__/Modules/Global/CommunicationsAndMedia','/1/2/32/37','A','A','2011-07-01 06:28:11',0,'2011-07-01 06:28:11',0),(38,32,0,1,'Travel and culture','',0,'a:1:{s:2:\"en\";s:18:\"Travel and culture\";}','a:1:{s:3:\"eng\";s:0:\"\";}','/__SYSTEM__/Modules/Global/TravelAndCulture','/1/2/32/38','A','A','2011-07-01 06:28:11',0,'2011-07-01 06:28:11',0),(39,32,0,1,'Science and technology','',0,'a:1:{s:2:\"en\";s:22:\"Science and technology\";}','a:1:{s:3:\"eng\";s:0:\"\";}','/__SYSTEM__/Modules/Global/ScienceAndTechnology','/1/2/32/39','A','A','2011-07-01 06:28:11',0,'2011-07-01 06:28:11',0),(40,32,0,1,'Sport and activities','',0,'a:1:{s:2:\"en\";s:20:\"Sport and activities\";}','a:1:{s:3:\"eng\";s:0:\"\";}','/__SYSTEM__/Modules/Global/SportAndActivities','/1/2/32/40','A','A','2011-07-01 06:28:11',0,'2011-07-01 06:28:11',0),(41,32,0,1,'Business and work','',0,'a:1:{s:2:\"en\";s:17:\"Business and work\";}','a:1:{s:3:\"eng\";s:0:\"\";}','/__SYSTEM__/Modules/Global/BusinessAndWork','/1/2/32/41','A','A','2011-07-01 06:28:11',0,'2011-07-01 06:28:11',0),(42,32,0,1,'Activism and action','',0,'a:1:{s:2:\"en\";s:19:\"Activism and action\";}','a:1:{s:3:\"eng\";s:0:\"\";}','/__SYSTEM__/Modules/Global/ActivismAndAction','/1/2/32/42','A','A','2011-07-01 06:28:11',0,'2011-07-01 06:28:11',0);

/*Table structure for table `z_categories_mapmeta` */

CREATE TABLE `z_categories_mapmeta` (
  `cmm_id` int(11) NOT NULL AUTO_INCREMENT,
  `cmm_meta_id` int(11) NOT NULL DEFAULT '0',
  `cmm_category_id` int(11) NOT NULL DEFAULT '0',
  `cmm_obj_status` varchar(1) NOT NULL DEFAULT 'A',
  `cmm_cr_date` datetime NOT NULL DEFAULT '1970-01-01 00:00:00',
  `cmm_cr_uid` int(11) NOT NULL DEFAULT '0',
  `cmm_lu_date` datetime NOT NULL DEFAULT '1970-01-01 00:00:00',
  `cmm_lu_uid` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`cmm_id`),
  KEY `idx_categories_mapmeta` (`cmm_meta_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `z_categories_mapmeta` */

/*Table structure for table `z_categories_mapobj` */

CREATE TABLE `z_categories_mapobj` (
  `cmo_id` int(11) NOT NULL AUTO_INCREMENT,
  `cmo_modname` varchar(60) NOT NULL DEFAULT '',
  `cmo_table` varchar(60) NOT NULL,
  `cmo_obj_id` int(11) NOT NULL DEFAULT '0',
  `cmo_obj_idcolumn` varchar(60) NOT NULL DEFAULT 'id',
  `cmo_reg_id` int(11) NOT NULL DEFAULT '0',
  `cmo_category_id` int(11) NOT NULL DEFAULT '0',
  `cmo_obj_status` varchar(1) NOT NULL DEFAULT 'A',
  `cmo_cr_date` datetime NOT NULL DEFAULT '1970-01-01 00:00:00',
  `cmo_cr_uid` int(11) NOT NULL DEFAULT '0',
  `cmo_lu_date` datetime NOT NULL DEFAULT '1970-01-01 00:00:00',
  `cmo_lu_uid` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`cmo_id`),
  KEY `idx_categories_mapobj` (`cmo_modname`,`cmo_table`,`cmo_obj_id`,`cmo_obj_idcolumn`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `z_categories_mapobj` */

/*Table structure for table `z_categories_registry` */

CREATE TABLE `z_categories_registry` (
  `crg_id` int(11) NOT NULL AUTO_INCREMENT,
  `crg_modname` varchar(60) NOT NULL DEFAULT '',
  `crg_table` varchar(60) NOT NULL DEFAULT '',
  `crg_property` varchar(60) NOT NULL DEFAULT '',
  `crg_category_id` int(11) NOT NULL DEFAULT '0',
  `crg_obj_status` varchar(1) NOT NULL DEFAULT 'A',
  `crg_cr_date` datetime NOT NULL DEFAULT '1970-01-01 00:00:00',
  `crg_cr_uid` int(11) NOT NULL DEFAULT '0',
  `crg_lu_date` datetime NOT NULL DEFAULT '1970-01-01 00:00:00',
  `crg_lu_uid` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`crg_id`),
  KEY `idx_categories_registry` (`crg_modname`,`crg_table`,`crg_property`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `z_categories_registry` */

/*Table structure for table `z_group_applications` */

CREATE TABLE `z_group_applications` (
  `pn_app_id` int(11) NOT NULL AUTO_INCREMENT,
  `pn_uid` int(11) NOT NULL DEFAULT '0',
  `pn_gid` int(11) NOT NULL DEFAULT '0',
  `pn_application` longblob,
  `pn_status` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`pn_app_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `z_group_applications` */

/*Table structure for table `z_group_membership` */

CREATE TABLE `z_group_membership` (
  `pn_gid` int(11) NOT NULL DEFAULT '0',
  `pn_uid` int(11) NOT NULL DEFAULT '0',
  KEY `gid_uid` (`pn_uid`,`pn_gid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `z_group_membership` */

insert  into `z_group_membership`(`pn_gid`,`pn_uid`) values (1,1),(2,2);

/*Table structure for table `z_group_perms` */

CREATE TABLE `z_group_perms` (
  `pn_pid` int(11) NOT NULL AUTO_INCREMENT,
  `pn_gid` int(11) NOT NULL DEFAULT '0',
  `pn_sequence` int(11) NOT NULL DEFAULT '0',
  `pn_realm` int(11) NOT NULL DEFAULT '0',
  `pn_component` varchar(255) NOT NULL DEFAULT '',
  `pn_instance` varchar(255) NOT NULL DEFAULT '',
  `pn_level` int(11) NOT NULL DEFAULT '0',
  `pn_bond` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`pn_pid`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

/*Data for the table `z_group_perms` */

insert  into `z_group_perms`(`pn_pid`,`pn_gid`,`pn_sequence`,`pn_realm`,`pn_component`,`pn_instance`,`pn_level`,`pn_bond`) values (1,2,1,0,'.*','.*',800,0),(2,-1,2,0,'ExtendedMenublock::','1:1:',0,0),(3,1,3,0,'.*','.*',300,0),(4,0,4,0,'ExtendedMenublock::','1:(1|2|3):',0,0),(5,0,5,0,'.*','.*',200,0);

/*Table structure for table `z_groups` */

CREATE TABLE `z_groups` (
  `pn_gid` int(11) NOT NULL AUTO_INCREMENT,
  `pn_name` varchar(255) NOT NULL DEFAULT '',
  `pn_gtype` tinyint(4) NOT NULL DEFAULT '0',
  `pn_description` varchar(200) NOT NULL DEFAULT '',
  `pn_prefix` varchar(25) NOT NULL DEFAULT '',
  `pn_state` tinyint(4) NOT NULL DEFAULT '0',
  `pn_nbuser` int(11) NOT NULL DEFAULT '0',
  `pn_nbumax` int(11) NOT NULL DEFAULT '0',
  `pn_link` int(11) NOT NULL DEFAULT '0',
  `pn_uidmaster` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`pn_gid`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `z_groups` */

insert  into `z_groups`(`pn_gid`,`pn_name`,`pn_gtype`,`pn_description`,`pn_prefix`,`pn_state`,`pn_nbuser`,`pn_nbumax`,`pn_link`,`pn_uidmaster`) values (1,'Users',0,'By default, all users are made members of this group.','usr',0,0,0,0,0),(2,'Administrators',0,'By default, all administrators are made members of this group.','adm',0,0,0,0,0);

/*Table structure for table `z_hooks` */

CREATE TABLE `z_hooks` (
  `pn_id` int(11) NOT NULL AUTO_INCREMENT,
  `pn_object` varchar(64) NOT NULL DEFAULT '',
  `pn_action` varchar(64) NOT NULL DEFAULT '',
  `pn_smodule` varchar(64) NOT NULL DEFAULT '',
  `pn_stype` varchar(64) NOT NULL DEFAULT '',
  `pn_tarea` varchar(64) NOT NULL DEFAULT '',
  `pn_tmodule` varchar(64) NOT NULL DEFAULT '',
  `pn_ttype` varchar(64) NOT NULL DEFAULT '',
  `pn_tfunc` varchar(64) NOT NULL DEFAULT '',
  `pn_sequence` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`pn_id`),
  KEY `smodule` (`pn_smodule`),
  KEY `smodule_tmodule` (`pn_smodule`,`pn_tmodule`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `z_hooks` */

/*Table structure for table `z_message` */

CREATE TABLE `z_message` (
  `pn_mid` int(11) NOT NULL AUTO_INCREMENT,
  `pn_title` varchar(100) NOT NULL DEFAULT '',
  `pn_content` longtext NOT NULL,
  `pn_date` int(11) NOT NULL DEFAULT '0',
  `pn_expire` int(11) NOT NULL DEFAULT '0',
  `pn_active` int(11) NOT NULL DEFAULT '1',
  `pn_view` int(11) NOT NULL DEFAULT '1',
  `pn_language` varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`pn_mid`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `z_message` */

insert  into `z_message`(`pn_mid`,`pn_title`,`pn_content`,`pn_date`,`pn_expire`,`pn_active`,`pn_view`,`pn_language`) values (1,'This site is powered by Zikula!','<p><a href=\"http://www.zikula.org\">Zikula</a> is a content management system (CMS) and application framework. It is secure and stable, and is a good choice for sites with a large volume of traffic.</p><p>With Zikula:</p><ul><li>you can customise all aspects of the site\'s appearance through themes, with support for CSS style sheets, JavaScript, Flash and all other modern web development technologies;</li><li>you can mark content as being suitable for either a single language or for all languages, and can control all aspects of localisation and internationalisation of your site and pages;</li><li>you can be sure that your pages will display properly in all browsers, thanks to Zikula\'s full compliance with W3C HTML standards;</li><li>you get a standard application-programming interface (API) that lets you easily augment your site\'s functionality through modules, blocks and other extensions;</li><li>you can get help and support from the Zikula community of webmasters and developers at <a href=\"http://www.zikula.org\">zikula.org</a>.</li></ul><p>Enjoy using Zikula!</p><p><strong>The Zikula team</strong></p><p><em>Note: Zikula is Free Open Source Software (FOSS) licensed under the GNU General Public License.</em></p>',1309494489,0,1,1,'');

/*Table structure for table `z_module_deps` */

CREATE TABLE `z_module_deps` (
  `pn_id` int(11) NOT NULL AUTO_INCREMENT,
  `pn_modid` int(11) NOT NULL DEFAULT '0',
  `pn_modname` varchar(64) NOT NULL DEFAULT '',
  `pn_minversion` varchar(10) NOT NULL DEFAULT '',
  `pn_maxversion` varchar(10) NOT NULL DEFAULT '',
  `pn_status` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`pn_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `z_module_deps` */

/*Table structure for table `z_module_vars` */

CREATE TABLE `z_module_vars` (
  `pn_id` int(11) NOT NULL AUTO_INCREMENT,
  `pn_modname` varchar(64) NOT NULL DEFAULT '',
  `pn_name` varchar(64) NOT NULL DEFAULT '',
  `pn_value` longtext,
  PRIMARY KEY (`pn_id`),
  KEY `mod_var` (`pn_modname`,`pn_name`)
) ENGINE=MyISAM AUTO_INCREMENT=197 DEFAULT CHARSET=utf8;

/*Data for the table `z_module_vars` */

insert  into `z_module_vars`(`pn_id`,`pn_modname`,`pn_name`,`pn_value`) values (1,'Modules','itemsperpage','i:25;'),(2,'/PNConfig','loadlegacy','0'),(3,'Admin','modulesperrow','i:3;'),(4,'Admin','itemsperpage','i:15;'),(5,'Admin','defaultcategory','i:5;'),(6,'Admin','modulestylesheet','s:11:\"navtabs.css\";'),(7,'Admin','admingraphic','i:1;'),(8,'Admin','startcategory','i:1;'),(9,'Admin','ignoreinstallercheck','0'),(10,'Admin','admintheme','s:0:\"\";'),(11,'Admin','displaynametype','i:1;'),(12,'Admin','moduledescription','i:1;'),(13,'Permissions','filter','i:1;'),(14,'Permissions','warnbar','i:1;'),(15,'Permissions','rowview','i:20;'),(16,'Permissions','rowedit','i:20;'),(17,'Permissions','lockadmin','i:1;'),(18,'Permissions','adminid','i:1;'),(19,'Groups','itemsperpage','i:25;'),(20,'Groups','defaultgroup','i:1;'),(21,'Groups','mailwarning','0'),(22,'Groups','hideclosed','0'),(23,'Blocks','collapseable','0'),(24,'Users','itemsperpage','i:25;'),(25,'Users','accountdisplaygraphics','i:1;'),(26,'Users','accountitemsperpage','i:25;'),(27,'Users','accountitemsperrow','i:5;'),(28,'Users','changepassword','i:1;'),(29,'Users','changeemail','i:1;'),(30,'Users','reg_allowreg','i:1;'),(31,'Users','reg_verifyemail','i:1;'),(32,'Users','reg_Illegalusername','s:87:\"root adm linux webmaster admin god administrator administrador nobody anonymous anonimo\";'),(33,'Users','reg_Illegaldomains','s:0:\"\";'),(34,'Users','reg_Illegaluseragents','s:0:\"\";'),(35,'Users','reg_noregreasons','s:51:\"Sorry! New user registration is currently disabled.\";'),(36,'Users','reg_uniemail','i:1;'),(37,'Users','reg_notifyemail','s:0:\"\";'),(38,'Users','reg_optitems','0'),(39,'Users','userimg','s:11:\"images/menu\";'),(40,'Users','avatarpath','s:13:\"images/avatar\";'),(41,'Users','minage','i:13;'),(42,'Users','minpass','i:5;'),(43,'Users','anonymous','s:5:\"Guest\";'),(44,'Users','savelastlogindate','0'),(45,'Users','loginviaoption','0'),(46,'Users','lowercaseuname','0'),(47,'Users','moderation','0'),(48,'Users','hash_method','s:6:\"sha256\";'),(49,'Users','login_redirect','i:1;'),(50,'Users','reg_question','s:0:\"\";'),(51,'Users','reg_answer','s:0:\"\";'),(52,'Users','idnnames','i:1;'),(53,'Theme','modulesnocache','s:0:\"\";'),(54,'Theme','enablecache','b:0;'),(55,'Theme','compile_check','1'),(56,'Theme','cache_lifetime','i:3600;'),(57,'Theme','force_compile','b:0;'),(58,'Theme','trimwhitespace','b:0;'),(59,'Theme','makelinks','b:0;'),(60,'Theme','maxsizeforlinks','i:30;'),(61,'Theme','itemsperpage','i:25;'),(62,'Theme','cssjscombine','b:0;'),(63,'Theme','cssjscompress','b:0;'),(64,'Theme','cssjsminify','b:0;'),(65,'Theme','cssjscombine_lifetime','i:3600;'),(66,'/PNConfig','debug','s:1:\"0\";'),(67,'/PNConfig','sitename','s:9:\"Site name\";'),(68,'/PNConfig','slogan','s:16:\"Site description\";'),(69,'/PNConfig','metakeywords','s:253:\"zikula, community, portal, portal web, open source, gpl, web site, website, weblog, blog, content management, content management system, web content management, web content management system, enterprise web content management, cms, application framework\";'),(70,'/PNConfig','startdate','s:7:\"07/2011\";'),(71,'/PNConfig','adminmail','s:19:\"admin@localhost.com\";'),(72,'/PNConfig','Default_Theme','s:9:\"andreas08\";'),(73,'/PNConfig','anonymous','s:5:\"Guest\";'),(74,'/PNConfig','timezone_offset','s:1:\"0\";'),(75,'/PNConfig','timezone_server','s:1:\"0\";'),(76,'/PNConfig','funtext','s:1:\"1\";'),(77,'/PNConfig','reportlevel','s:1:\"0\";'),(78,'/PNConfig','startpage','s:4:\"Tour\";'),(79,'/PNConfig','Version_Num','s:5:\"1.2.7\";'),(80,'/PNConfig','Version_ID','s:6:\"Zikula\";'),(81,'/PNConfig','Version_Sub','s:6:\"forest\";'),(82,'/PNConfig','debug_sql','s:1:\"0\";'),(83,'/PNConfig','multilingual','s:1:\"1\";'),(84,'/PNConfig','useflags','s:1:\"0\";'),(85,'/PNConfig','theme_change','s:1:\"0\";'),(86,'/PNConfig','UseCompression','s:1:\"0\";'),(87,'/PNConfig','errordisplay','i:1;'),(88,'/PNConfig','errorlog','0'),(89,'/PNConfig','errorlogtype','0'),(90,'/PNConfig','errormailto','s:14:\"me@example.com\";'),(91,'/PNConfig','siteoff','0'),(92,'/PNConfig','siteoffreason','s:0:\"\";'),(93,'/PNConfig','starttype','s:0:\"\";'),(94,'/PNConfig','startfunc','s:0:\"\";'),(95,'/PNConfig','startargs','s:0:\"\";'),(96,'/PNConfig','entrypoint','s:9:\"index.php\";'),(97,'/PNConfig','language_detect','0'),(98,'/PNConfig','shorturls','b:0;'),(99,'/PNConfig','shorturlstype','s:1:\"0\";'),(100,'/PNConfig','shorturlsext','s:4:\"html\";'),(101,'/PNConfig','shorturlsseparator','s:1:\"-\";'),(102,'/PNConfig','shorturlsstripentrypoint','b:0;'),(103,'/PNConfig','shorturlsdefaultmodule','s:0:\"\";'),(104,'/PNConfig','profilemodule','s:0:\"\";'),(105,'/PNConfig','messagemodule','s:0:\"\";'),(106,'/PNConfig','languageurl','0'),(107,'/PNConfig','ajaxtimeout','i:5000;'),(108,'/PNConfig','permasearch','s:161:\"À,Á,Â,Ã,Å,à,á,â,ã,å,Ò,Ó,Ô,Õ,Ø,ò,ó,ô,õ,ø,È,É,Ê,Ë,è,é,ê,ë,Ç,ç,Ì,Í,Î,Ï,ì,í,î,ï,Ù,Ú,Û,ù,ú,û,ÿ,Ñ,ñ,ß,ä,Ä,ö,Ö,ü,Ü\";'),(109,'/PNConfig','permareplace','s:114:\"A,A,A,A,A,a,a,a,a,a,O,O,O,O,O,o,o,o,o,o,E,E,E,E,e,e,e,e,C,c,I,I,I,I,i,i,i,i,U,U,U,u,u,u,y,N,n,ss,ae,Ae,oe,Oe,ue,Ue\";'),(110,'/PNConfig','language','s:3:\"eng\";'),(111,'/PNConfig','locale','s:2:\"en\";'),(112,'/PNConfig','language_i18n','s:2:\"en\";'),(113,'/PNConfig','language_bc','i:1;'),(114,'/PNConfig','timezone_info','a:38:{i:-12;s:31:\"(GMT -12:00 hours) Baker Island\";i:-11;s:39:\"(GMT -11:00 hours) Midway Island, Samoa\";i:-10;s:25:\"(GMT -10:00 hours) Hawaii\";s:4:\"-9.5\";s:34:\"(GMT -9:30 hours) French Polynesia\";i:-9;s:24:\"(GMT -9:00 hours) Alaska\";i:-8;s:44:\"(GMT -8:00 hours) Pacific Time (US & Canada)\";i:-7;s:45:\"(GMT -7:00 hours) Mountain Time (US & Canada)\";i:-6;s:57:\"(GMT -6:00 hours) Central Time (US & Canada), Mexico City\";i:-5;s:65:\"(GMT -5:00 hours) Eastern Time (US & Canada), Bogota, Lima, Quito\";i:-4;s:57:\"(GMT -4:00 hours) Atlantic Time (Canada), Caracas, La Paz\";s:4:\"-3.5\";s:30:\"(GMT -3:30 hours) Newfoundland\";i:-3;s:50:\"(GMT -3:00 hours) Brazil, Buenos Aires, Georgetown\";i:-2;s:30:\"(GMT -2:00 hours) Mid-Atlantic\";i:-1;s:44:\"(GMT -1:00 hours) Azores, Cape Verde Islands\";i:0;s:63:\"(GMT) Western Europe Time, London, Lisbon, Casablanca, Monrovia\";i:1;s:80:\"(GMT +1:00 hours) CET (Central Europe Time), Brussels, Copenhagen, Madrid, Paris\";i:2;s:70:\"(GMT +2:00 hours) EET (Eastern Europe Time), Kaliningrad, South Africa\";i:3;s:65:\"(GMT +3:00 hours) Baghdad, Kuwait, Riyadh, Moscow, St. Petersburg\";s:3:\"3.5\";s:24:\"(GMT +3:30 hours) Tehran\";i:4;s:50:\"(GMT +4:00 hours) Abu Dhabi, Muscat, Baku, Tbilisi\";s:3:\"4.5\";s:23:\"(GMT +4:30 hours) Kabul\";i:5;s:60:\"(GMT +5:00 hours) Ekaterinburg, Islamabad, Karachi, Tashkent\";s:3:\"5.5\";s:53:\"(GMT +5:30 hours) Bombay, Calcutta, Madras, New Delhi\";s:4:\"5.75\";s:27:\"(GMT +5:45 hours) Kathmandu\";i:6;s:40:\"(GMT +6:00 hours) Almaty, Dhaka, Colombo\";s:3:\"6.5\";s:40:\"(GMT +6:30 hours) Cocos Islands, Myanmar\";i:7;s:41:\"(GMT +7:00 hours) Bangkok, Hanoi, Jakarta\";i:8;s:81:\"(GMT +8:00 hours) Beijing, Perth, Singapore, Hong Kong, Chongqing, Urumqi, Taipei\";i:9;s:55:\"(GMT +9:00 hours) Tokyo, Seoul, Osaka, Sapporo, Yakutsk\";s:3:\"9.5\";s:34:\"(GMT +9:30 hours) Adelaide, Darwin\";i:10;s:50:\"(GMT +10:00 hours) EAST (East Australian Standard)\";s:4:\"10.5\";s:52:\"(GMT +10:30 hours) Lord Howe Island (NSW, Australia)\";i:11;s:58:\"(GMT +11:00 hours) Magadan, Solomon Islands, New Caledonia\";s:4:\"11.5\";s:33:\"(GMT +11:30 hours) Norfolk Island\";i:12;s:73:\"(GMT +12:00 hours) Auckland, Wellington, Fiji, Kamchatka, Marshall Island\";s:5:\"12.75\";s:34:\"(GMT +12:45 hours) Chatham Islands\";i:13;s:51:\"(GMT +13:00 hours Tonga, Kiribati (Phoenix Islands)\";i:14;s:42:\"(GMT +14:00 hours) Kiribati (Line Islands)\";}'),(115,'Admin_Messages','itemsperpage','i:25;'),(116,'Admin_Messages','allowsearchinactive','b:0;'),(117,'SecurityCenter','itemsperpage','i:10;'),(118,'/PNConfig','enableanticracker','i:1;'),(119,'/PNConfig','emailhackattempt','i:1;'),(120,'/PNConfig','loghackattempttodb','i:1;'),(121,'/PNConfig','onlysendsummarybyemail','i:1;'),(122,'/PNConfig','updatecheck','i:1;'),(123,'/PNConfig','updatefrequency','i:7;'),(124,'/PNConfig','updatelastchecked','0'),(125,'/PNConfig','updateversion','s:5:\"1.2.7\";'),(126,'/PNConfig','keyexpiry','0'),(127,'/PNConfig','sessionauthkeyua','b:0;'),(128,'/PNConfig','secure_domain','s:0:\"\";'),(129,'/PNConfig','signcookies','i:1;'),(130,'/PNConfig','signingkey','s:40:\"22a9be3643a25131f610f7feabfa458692695a15\";'),(131,'/PNConfig','seclevel','s:6:\"Medium\";'),(132,'/PNConfig','secmeddays','i:7;'),(133,'/PNConfig','secinactivemins','i:20;'),(134,'/PNConfig','sessionstoretofile','0'),(135,'/PNConfig','sessionsavepath','s:0:\"\";'),(136,'/PNConfig','gc_probability','i:100;'),(137,'/PNConfig','anonymoussessions','i:1;'),(138,'/PNConfig','sessionrandregenerate','1'),(139,'/PNConfig','sessionregenerate','1'),(140,'/PNConfig','sessionregeneratefreq','i:10;'),(141,'/PNConfig','sessionipcheck','0'),(142,'/PNConfig','sessionname','s:4:\"ZSID\";'),(143,'/PNConfig','filtergetvars','i:1;'),(144,'/PNConfig','filterpostvars','i:1;'),(145,'/PNConfig','filtercookievars','i:1;'),(146,'/PNConfig','outputfilter','i:1;'),(147,'/PNConfig','summarycontent','s:1130:\"For the attention of %sitename% administration staff:\n\nOn %date% at %time%, Zikula detected that somebody tried to interact with the site in a way that may have been intended compromise its security. This is not necessarily the case: it could have been caused by work you were doing on the site, or may have been due to some other reason. In any case, it was detected and blocked. \n\nThe suspicious activity was recognised in \'%filename%\' at line %linenumber%.\n\nType: %type%. \n\nAdditional information: %additionalinfo%.\n\nBelow is logged information that may help you identify what happened and who was responsible.\n\n=====================================\nInformation about the user:\n=====================================\nUser name:  %username%\nUser\'s e-mail address: %useremail%\nUser\'s real name: %userrealname%\n\n=====================================\nIP numbers (if this was a cracker, the IP numbers may not be the true point of origin)\n=====================================\nIP according to HTTP_CLIENT_IP: %httpclientip%\nIP according to REMOTE_ADDR: %remoteaddr%\nIP according to GetHostByName($REMOTE_ADDR): %gethostbyremoteaddr%\n\";'),(148,'/PNConfig','fullcontent','s:1289:\"=====================================\nInformation in the $_REQUEST array\n=====================================\n%requestarray%\n\n=====================================\nInformation in the $_GET array\n(variables that may have been in the URL string or in a \'GET\'-type form)\n=====================================\n%getarray%\n\n=====================================\nInformation in the $_POST array\n(visible and invisible form elements)\n=====================================\n%postarray%\n\n=====================================\nBrowser information\n=====================================\n%browserinfo%\n\n=====================================\nInformation in the $_SERVER array\n=====================================\n%serverarray%\n\n=====================================\nInformation in the $_ENV array\n=====================================\n%envarray%\n\n=====================================\nInformation in the $_COOKIE array\n=====================================\n%cookiearray%\n\n=====================================\nInformation in the $_FILES array\n=====================================\n%filearray%\n\n=====================================\nInformation in the $_SESSION array\n(session information -- variables starting with PNSV are Zikula session variables)\n=====================================\n%sessionarray%\n\";'),(149,'/PNConfig','usehtaccessbans','0'),(150,'/PNConfig','extrapostprotection','0'),(151,'/PNConfig','extragetprotection','0'),(152,'/PNConfig','checkmultipost','0'),(153,'/PNConfig','maxmultipost','i:4;'),(154,'/PNConfig','cpuloadmonitor','0'),(155,'/PNConfig','cpumaxload','d:10;'),(156,'/PNConfig','ccisessionpath','s:0:\"\";'),(157,'/PNConfig','htaccessfilelocation','s:9:\".htaccess\";'),(158,'/PNConfig','nocookiebanthreshold','i:10;'),(159,'/PNConfig','nocookiewarningthreshold','i:2;'),(160,'/PNConfig','fastaccessbanthreshold','i:40;'),(161,'/PNConfig','fastaccesswarnthreshold','i:10;'),(162,'/PNConfig','javababble','0'),(163,'/PNConfig','javaencrypt','0'),(164,'/PNConfig','preservehead','0'),(165,'/PNConfig','filterarrays','i:1;'),(166,'/PNConfig','htmlentities','s:1:\"1\";'),(167,'/PNConfig','AllowableHTML','a:83:{s:3:\"!--\";i:2;s:1:\"a\";i:2;s:4:\"abbr\";i:0;s:7:\"acronym\";i:0;s:7:\"address\";i:0;s:6:\"applet\";i:0;s:4:\"area\";i:0;s:1:\"b\";i:2;s:4:\"base\";i:0;s:8:\"basefont\";i:0;s:3:\"bdo\";i:0;s:3:\"big\";i:0;s:10:\"blockquote\";i:2;s:2:\"br\";i:2;s:6:\"button\";i:0;s:7:\"caption\";i:0;s:6:\"center\";i:2;s:4:\"cite\";i:0;s:4:\"code\";i:0;s:3:\"col\";i:0;s:8:\"colgroup\";i:0;s:3:\"del\";i:0;s:3:\"dfn\";i:0;s:3:\"dir\";i:0;s:3:\"div\";i:2;s:2:\"dl\";i:1;s:2:\"dd\";i:1;s:2:\"dt\";i:1;s:2:\"em\";i:2;s:5:\"embed\";i:0;s:8:\"fieldset\";i:0;s:4:\"font\";i:0;s:4:\"form\";i:0;s:2:\"h1\";i:1;s:2:\"h2\";i:1;s:2:\"h3\";i:1;s:2:\"h4\";i:1;s:2:\"h5\";i:1;s:2:\"h6\";i:1;s:2:\"hr\";i:2;s:1:\"i\";i:2;s:6:\"iframe\";i:0;s:3:\"img\";i:0;s:5:\"input\";i:0;s:3:\"ins\";i:0;s:3:\"kbd\";i:0;s:5:\"label\";i:0;s:6:\"legend\";i:0;s:2:\"li\";i:2;s:3:\"map\";i:0;s:7:\"marquee\";i:0;s:4:\"menu\";i:0;s:4:\"nobr\";i:0;s:6:\"object\";i:0;s:2:\"ol\";i:2;s:8:\"optgroup\";i:0;s:6:\"option\";i:0;s:1:\"p\";i:2;s:5:\"param\";i:0;s:3:\"pre\";i:2;s:1:\"q\";i:0;s:1:\"s\";i:0;s:4:\"samp\";i:0;s:6:\"script\";i:0;s:6:\"select\";i:0;s:5:\"small\";i:0;s:4:\"span\";i:0;s:6:\"strike\";i:0;s:6:\"strong\";i:2;s:3:\"sub\";i:0;s:3:\"sup\";i:0;s:5:\"table\";i:2;s:5:\"tbody\";i:0;s:2:\"td\";i:2;s:8:\"textarea\";i:0;s:5:\"tfoot\";i:0;s:2:\"th\";i:2;s:5:\"thead\";i:0;s:2:\"tr\";i:2;s:2:\"tt\";i:2;s:1:\"u\";i:0;s:2:\"ul\";i:2;s:3:\"var\";i:0;}'),(168,'Categories','userrootcat','s:17:\"/__SYSTEM__/Users\";'),(169,'Categories','allowusercatedit','0'),(170,'Categories','autocreateusercat','0'),(171,'Categories','autocreateuserdefaultcat','0'),(172,'Categories','userdefaultcatname','s:7:\"Default\";'),(173,'legal','termsofuse','1'),(174,'legal','privacypolicy','1'),(175,'legal','accessibilitystatement','1'),(176,'Mailer','mailertype','i:1;'),(177,'Mailer','charset','s:5:\"utf-8\";'),(178,'Mailer','encoding','s:4:\"8bit\";'),(179,'Mailer','html','b:0;'),(180,'Mailer','wordwrap','i:50;'),(181,'Mailer','msmailheaders','b:0;'),(182,'Mailer','sendmailpath','s:18:\"/usr/sbin/sendmail\";'),(183,'Mailer','smtpauth','b:0;'),(184,'Mailer','smtpserver','s:9:\"localhost\";'),(185,'Mailer','smtpport','i:25;'),(186,'Mailer','smtptimeout','i:10;'),(187,'Mailer','smtpusername','s:0:\"\";'),(188,'Mailer','smtppassword','s:0:\"\";'),(189,'pnRender','compile_check','1'),(190,'pnRender','force_compile','b:0;'),(191,'pnRender','cache','b:0;'),(192,'pnRender','expose_template','b:0;'),(193,'pnRender','lifetime','i:3600;'),(194,'Search','itemsperpage','i:10;'),(195,'Search','limitsummary','i:255;'),(196,'/PNConfig','log_last_rotate','i:1309494578;');

/*Table structure for table `z_modules` */

CREATE TABLE `z_modules` (
  `pn_id` int(11) NOT NULL AUTO_INCREMENT,
  `pn_name` varchar(64) NOT NULL DEFAULT '',
  `pn_type` tinyint(4) NOT NULL DEFAULT '0',
  `pn_displayname` varchar(64) NOT NULL DEFAULT '',
  `pn_url` varchar(64) NOT NULL DEFAULT '',
  `pn_description` varchar(255) NOT NULL DEFAULT '',
  `pn_regid` int(11) NOT NULL DEFAULT '0',
  `pn_directory` varchar(64) NOT NULL DEFAULT '',
  `pn_version` varchar(10) NOT NULL DEFAULT '0',
  `pn_official` tinyint(4) NOT NULL DEFAULT '0',
  `pn_author` varchar(255) NOT NULL DEFAULT '',
  `pn_contact` varchar(255) NOT NULL DEFAULT '',
  `pn_admin_capable` tinyint(4) NOT NULL DEFAULT '0',
  `pn_user_capable` tinyint(4) NOT NULL DEFAULT '0',
  `pn_profile_capable` tinyint(4) NOT NULL DEFAULT '0',
  `pn_message_capable` tinyint(4) NOT NULL DEFAULT '0',
  `pn_state` smallint(6) NOT NULL DEFAULT '0',
  `pn_credits` varchar(255) NOT NULL DEFAULT '',
  `pn_changelog` varchar(255) NOT NULL DEFAULT '',
  `pn_help` varchar(255) NOT NULL DEFAULT '',
  `pn_license` varchar(255) NOT NULL DEFAULT '',
  `pn_securityschema` text,
  PRIMARY KEY (`pn_id`),
  KEY `state` (`pn_state`),
  KEY `mod_state` (`pn_name`,`pn_state`)
) ENGINE=MyISAM AUTO_INCREMENT=27 DEFAULT CHARSET=utf8;

/*Data for the table `z_modules` */

insert  into `z_modules`(`pn_id`,`pn_name`,`pn_type`,`pn_displayname`,`pn_url`,`pn_description`,`pn_regid`,`pn_directory`,`pn_version`,`pn_official`,`pn_author`,`pn_contact`,`pn_admin_capable`,`pn_user_capable`,`pn_profile_capable`,`pn_message_capable`,`pn_state`,`pn_credits`,`pn_changelog`,`pn_help`,`pn_license`,`pn_securityschema`) values (1,'Modules',3,'Modules manager','modules','Provides support for modules, and incorporates an interface for adding, removing and administering core system modules and add-on modules.',0,'Modules','3.6',1,'Jim McDonald, Mark West','http://www.zikula.org',1,0,0,0,3,'','','','','a:1:{s:9:\"Modules::\";s:2:\"::\";}'),(2,'Admin',3,'Admin panel manager','adminpanel','Provides the site\'s administration panel, and the ability to configure and manage it.',0,'Admin','1.8',1,'Mark West','http://www.markwest.me.uk/',1,0,0,0,3,'pndocs/credits.txt','pndocs/changelog.txt','pndocs/help.txt','pndocs/license.txt','a:1:{s:7:\"Admin::\";s:38:\"Admin Category name::Admin Category ID\";}'),(3,'Admin_Messages',3,'Admin messages manager','adminmessages','Provides a means of publishing, editing and scheduling special announcements from the site administrator.',0,'Admin_Messages','2.2',1,'Mark West','http://www.markwest.me.uk',1,0,0,0,3,'pndocs/credits.txt','pndocs/changelog.txt','pndocs/help.txt','pndocs/license.txt','a:1:{s:16:\"Admin_Messages::\";s:25:\"message title::message id\";}'),(4,'AuthPN',3,'Authentication manager','authpn','Provides the ability to employ non-core user-authentication systems within a site, notably LDAP or OpenID.',0,'AuthPN','1.1',1,'Mark West','http://www.markwest.me.uk/',1,0,0,0,1,'pndocs/credits.txt','pndocs/changelog.txt','pndocs/help.txt','pndocs/license.txt','a:0:{}'),(5,'Blocks',3,'Blocks manager','blocksmanager','Provides an interface for adding, removing and administering the site\'s side and centre blocks.',0,'Blocks','3.6',1,'Jim McDonald, Mark West','http://www.mcdee.net/, http://www.markwest.me.uk/',1,1,0,0,3,'','','','','a:2:{s:8:\"Blocks::\";s:30:\"Block key:Block title:Block ID\";s:16:\"Blocks::position\";s:26:\"Position name::Position ID\";}'),(6,'Categories',3,'Categories manager','categories','Provides support for categorisation of content in other modules, and an interface for adding, removing and administering categories.',0,'Categories','1.1',1,'Robert Gasch','rgasch@gmail.com',1,1,0,0,3,'pndocs/credits.txt','pndocs/changelog.txt','pndocs/help.txt','pndocs/license.txt','a:1:{s:20:\"Categories::Category\";s:40:\"Category ID:Category Path:Category IPath\";}'),(7,'Errors',3,'Error logger','errorlogger','Provides the core system of the site with error-logging capability.',0,'Errors','1.1',1,'Brian Lindner <Furbo>','furbo@sigtauonline.com',0,1,0,0,3,'pndocs/credits.txt','pndocs/changelog.txt','pndocs/help.txt','pndocs/license.txt','a:1:{s:8:\"Errors::\";s:2:\"::\";}'),(8,'Groups',3,'Groups manager','groupsmanager','Provides support for user groups, and incorporates an interface for adding, removing and administering them.',0,'Groups','2.3',1,'Mark West, Franky Chestnut, Michael Halbook','http://www.markwest.me.uk/, http://dev.pnconcept.com, http://www.halbrooktech.com',1,1,0,0,3,'pndocs/credits.txt','pndocs/changelog.txt','pndocs/help.txt','pndocs/license.txt','a:1:{s:8:\"Groups::\";s:10:\"Group ID::\";}'),(9,'Header_Footer',3,'Header and footer handler','headerfooter','Provides the header and footer portions of legacy themes and other non-Xanthia themes.',0,'Header_Footer','1.1',1,'Mark West','http://www.markwest.me.uk/',0,1,0,0,3,'pndocs/credits.txt','pndocs/changelog.txt','pndocs/help.txt','pndocs/license.txt','a:0:{}'),(10,'Mailer',3,'Mailer','mailer','Provides mail-sending functionality for communication with the site\'s users, and an interface for managing the e-mail service settings used by the mailer.',0,'Mailer','1.3',1,'Mark West','http://www.markwest.me.uk/',1,0,0,0,3,'pndocs/credits.txt','pndocs/changelog.txt','pndocs/help.txt','pndocs/license.txt','a:1:{s:8:\"Mailer::\";s:2:\"::\";}'),(11,'ObjectData',3,'Object data manager','objectdata','Provides a framework for implementing and managing object-model data items, for use by other modules and applications.',0,'ObjectData','1.03',0,'Robert Gasch','rgasch@gmail.com',0,0,0,0,3,'docs/credits.txt','docs/changelog.txt','docs/help.txt','docs/license.txt','a:1:{s:12:\"ObjectData::\";s:2:\"::\";}'),(12,'PageLock',3,'Page lock manager','pagelock','Provides the ability to lock pages when they are in use, for content and access control.',0,'PageLock','1.1',1,'Jorn Wildt','http://www.elfisk.dk',0,0,0,0,3,'pndocs/credits.txt','pndocs/changelog.txt','pndocs/install.txt','pndocs/license.txt','a:1:{s:10:\"PageLock::\";s:2:\"::\";}'),(13,'Permissions',3,'Permission rules manager','permissions','Provides an interface for fine-grained management of accessibility of the site\'s functionality and content through permission rules.',0,'Permissions','1.1',1,'Jim McDonald, M.Maes','http://www.mcdee.net/, http://www.mmaes.com',1,0,0,0,3,'pndocs/credits.txt','pndocs/changelog.txt','pndocs/install.txt','pndocs/license.txt','a:1:{s:13:\"Permissions::\";s:2:\"::\";}'),(14,'pnForm',3,'Forms manager','pnforms','Provides a framework for standardised presentation of the site\'s forms.',0,'pnForm','1.1',1,'The Zikula development team','http://www.zikula.org/',0,0,0,0,3,'pndocs/credits.txt','pndocs/changelog.txt','pndocs/help.txt','pndocs/license.txt','a:1:{s:10:\"pnRender::\";s:2:\"::\";}'),(15,'pnRender',3,'Rendering engine','pnrender','Provides the core system with a Smarty-based engine to control content rendering and presentation.',0,'pnRender','1.1',1,'The Zikula development team','http://www.zikula.org/',1,0,0,0,3,'pndocs/credits.txt','pndocs/changelog.txt','pndocs/help.txt','pndocs/license.txt','a:1:{s:10:\"pnRender::\";s:2:\"::\";}'),(16,'Search',3,'Site search engine','search','Provides an engine for searching within the site, and an interface for managing search page settings.',0,'Search','1.5',1,'Patrick Kellum','http://www.ctarl-ctarl.com',1,1,0,0,3,'pndocs/credits.txt','pndocs/changelog.txt','pndocs/install.txt','pndocs/license.txt','a:1:{s:8:\"Search::\";s:13:\"Module name::\";}'),(17,'SecurityCenter',3,'Security centre','securitycenter','Provides the ability to manage site security. It logs attempted hacks and similar events, and incorporates a user interface for customising alerting and security settings.',0,'SecurityCenter','1.3',1,'Mark West','http://www.markwest.me.uk',1,0,0,0,3,'pndocs/credits.txt','pndocs/changelog.txt','pndocs/help.txt','pndocs/license.txt','a:1:{s:16:\"SecurityCenter::\";s:16:\"hackid::hacktime\";}'),(18,'Settings',3,'General settings manager','settings','Provides an interface for managing the site\'s general settings, i.e. ownership information, site start page settings, multi-lingual settings, error reporting options, and various other features that are not administered within other modules.',0,'Settings','2.9.1',1,'Simon Wunderlin','',1,0,0,0,3,'pndocs/credits.txt','pndocs/changelog.txt','pndocs/help.txt','pndocs/license.txt','a:1:{s:10:\"Settings::\";s:2:\"::\";}'),(19,'SysInfo',3,'System info panel','sysinfo','Provides detailed information reports about the system configuration and environment, for tracking and troubleshooting purposes.',0,'SysInfo','1.1',1,'Simon Birtwistle','hammerhead@zikula.org',1,0,0,0,3,'pndocs/credits.txt','pndocs/changelog.txt','pndocs/help.txt','pndocs/license.txt','a:1:{s:9:\"SysInfo::\";s:2:\"::\";}'),(20,'Theme',3,'Themes manager','theme','Provides the site\'s theming system, and an interface for managing themes, to control the site\'s presentation and appearance.',0,'Theme','3.3',1,'Mark West','http://www.markwest.me.uk/',1,1,0,0,3,'pndocs/credits.txt','pndocs/changelog.txt','pndocs/help.txt','pndocs/license.txt','a:1:{s:7:\"Theme::\";s:12:\"Theme name::\";}'),(21,'Users',3,'Users manager','users','Provides an interface for configuring and administering registered user accounts. Incorporates all needed functionality, but can work in close unison with the third party profile module configured in the general settings of the site.',0,'Users','1.13',1,'Xiaoyu Huang, Drak','class007@sina.com, drak@zikula.org',1,1,0,0,3,'pndocs/credits.txt','pndocs/changelog.txt','pndocs/help.txt','pndocs/license.txt','a:2:{s:7:\"Users::\";s:14:\"Uname::User ID\";s:16:\"Users::MailUsers\";s:2:\"::\";}'),(22,'Workflow',3,'Workflow manager','workflow','Provides a workflow engine, and an interface for designing and administering workflows comprised of actions and events.',0,'Workflow','1.1',1,'Drak','drak@hostnuke.com',0,0,0,0,3,'pndocs/credits.txt','pndocs/changelog.txt','pndocs/manual.html','pndocs/copying.txt','a:1:{s:10:\"Workflow::\";s:2:\"::\";}'),(23,'DomainManager',2,'Profile manager','profile','Provides a personal account control panel for each registered user, an interface to administer the personal information items displayed within it, and a registered users list functionality. Works in close unison with the \'Users\' module.',0,'DomainManager','1.5.2',1,'Mateo Tibaquirá, Mark West, Franky Chestnut','http://nestormateo.com/, http://www.markwest.me.uk/, http://dev.pnconcept.com/',0,0,1,0,1,'pndocs/credits.txt','pndocs/changelog.txt','pndocs/help.txt','pndocs/license.txt','a:5:{s:9:\"Profile::\";s:2:\"::\";s:13:\"Profile::item\";s:56:\"DynamicUserData PropertyName::DynamicUserData PropertyID\";s:16:\"Profile:Members:\";s:2:\"::\";s:22:\"Profile:Members:recent\";s:2:\"::\";s:22:\"Profile:Members:online\";s:2:\"::\";}'),(24,'legal',2,'Legal info manager','legalmod','Provides an interface for managing the site\'s \'Terms of use\', \'Privacy statement\' and \'Accessibility statement\'.',0,'legal','1.3',1,'Michael M. Wechsler','michael@thelaw.com',1,1,0,0,3,'pndocs/credits.txt','pndocs/changelog.txt','pndocs/install.txt','pndocs/license.txt','a:4:{s:7:\"legal::\";s:2:\"::\";s:17:\"legal::termsofuse\";s:2:\"::\";s:14:\"legal::privacy\";s:2:\"::\";s:29:\"legal::accessibilitystatement\";s:2:\"::\";}'),(25,'Profile',2,'Profile manager','profile','Provides a personal account control panel for each registered user, an interface to administer the personal information items displayed within it, and a registered users list functionality. Works in close unison with the \'Users\' module.',0,'Profile','1.5.2',1,'Mateo Tibaquirá, Mark West, Franky Chestnut','http://nestormateo.com/, http://www.markwest.me.uk/, http://dev.pnconcept.com/',1,1,1,0,1,'pndocs/credits.txt','pndocs/changelog.txt','pndocs/help.txt','pndocs/license.txt','a:5:{s:9:\"Profile::\";s:2:\"::\";s:13:\"Profile::item\";s:56:\"DynamicUserData PropertyName::DynamicUserData PropertyID\";s:16:\"Profile:Members:\";s:2:\"::\";s:22:\"Profile:Members:recent\";s:2:\"::\";s:22:\"Profile:Members:online\";s:2:\"::\";}'),(26,'Tour',2,'Tour','tour','First time configuration and Zikula Tour.',0,'Tour','1.2',1,'Simon Birtwistle','http://www.itbegins.co.uk',0,1,0,0,3,'pndocs/credits.txt','pndocs/changelog.txt','pndocs/install.txt','pndocs/license.txt','a:0:{}');

/*Table structure for table `z_objectdata_attributes` */

CREATE TABLE `z_objectdata_attributes` (
  `oba_id` int(11) NOT NULL AUTO_INCREMENT,
  `oba_attribute_name` varchar(80) NOT NULL DEFAULT '',
  `oba_object_id` int(11) NOT NULL DEFAULT '0',
  `oba_object_type` varchar(80) NOT NULL DEFAULT '',
  `oba_value` text,
  `oba_obj_status` varchar(1) NOT NULL DEFAULT 'A',
  `oba_cr_date` datetime NOT NULL DEFAULT '1970-01-01 00:00:00',
  `oba_cr_uid` int(11) NOT NULL DEFAULT '0',
  `oba_lu_date` datetime NOT NULL DEFAULT '1970-01-01 00:00:00',
  `oba_lu_uid` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`oba_id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

/*Data for the table `z_objectdata_attributes` */

insert  into `z_objectdata_attributes`(`oba_id`,`oba_attribute_name`,`oba_object_id`,`oba_object_type`,`oba_value`,`oba_obj_status`,`oba_cr_date`,`oba_cr_uid`,`oba_lu_date`,`oba_lu_uid`) values (1,'code',5,'categories_category','Y','A','2011-07-01 06:28:10',0,'2011-07-01 06:28:10',0),(2,'code',6,'categories_category','N','A','2011-07-01 06:28:10',0,'2011-07-01 06:28:10',0),(3,'code',11,'categories_category','P','A','2011-07-01 06:28:10',0,'2011-07-01 06:28:10',0),(4,'code',12,'categories_category','C','A','2011-07-01 06:28:10',0,'2011-07-01 06:28:10',0),(5,'code',13,'categories_category','A','A','2011-07-01 06:28:10',0,'2011-07-01 06:28:10',0),(6,'code',14,'categories_category','O','A','2011-07-01 06:28:10',0,'2011-07-01 06:28:10',0),(7,'code',15,'categories_category','R','A','2011-07-01 06:28:10',0,'2011-07-01 06:28:10',0),(8,'code',17,'categories_category','M','A','2011-07-01 06:28:10',0,'2011-07-01 06:28:10',0),(9,'code',18,'categories_category','F','A','2011-07-01 06:28:10',0,'2011-07-01 06:28:10',0),(10,'code',26,'categories_category','A','A','2011-07-01 06:28:10',0,'2011-07-01 06:28:10',0),(11,'code',27,'categories_category','I','A','2011-07-01 06:28:10',0,'2011-07-01 06:28:10',0),(12,'code',29,'categories_category','P','A','2011-07-01 06:28:10',0,'2011-07-01 06:28:10',0),(13,'code',30,'categories_category','A','A','2011-07-01 06:28:11',0,'2011-07-01 06:28:11',0);

/*Table structure for table `z_objectdata_log` */

CREATE TABLE `z_objectdata_log` (
  `obl_id` int(11) NOT NULL AUTO_INCREMENT,
  `obl_object_type` varchar(80) NOT NULL DEFAULT '',
  `obl_object_id` int(11) NOT NULL DEFAULT '0',
  `obl_op` varchar(16) NOT NULL DEFAULT '',
  `obl_diff` text,
  `obl_obj_status` varchar(1) NOT NULL DEFAULT 'A',
  `obl_cr_date` datetime NOT NULL DEFAULT '1970-01-01 00:00:00',
  `obl_cr_uid` int(11) NOT NULL DEFAULT '0',
  `obl_lu_date` datetime NOT NULL DEFAULT '1970-01-01 00:00:00',
  `obl_lu_uid` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`obl_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `z_objectdata_log` */

/*Table structure for table `z_objectdata_meta` */

CREATE TABLE `z_objectdata_meta` (
  `obm_id` int(11) NOT NULL AUTO_INCREMENT,
  `obm_module` varchar(40) NOT NULL DEFAULT '',
  `obm_table` varchar(40) NOT NULL DEFAULT '',
  `obm_idcolumn` varchar(40) NOT NULL DEFAULT '',
  `obm_obj_id` int(11) NOT NULL DEFAULT '0',
  `obm_permissions` varchar(255) DEFAULT '',
  `obm_dc_title` varchar(80) DEFAULT '',
  `obm_dc_author` varchar(80) DEFAULT '',
  `obm_dc_subject` varchar(255) DEFAULT '',
  `obm_dc_keywords` varchar(128) DEFAULT '',
  `obm_dc_description` varchar(255) DEFAULT '',
  `obm_dc_publisher` varchar(128) DEFAULT '',
  `obm_dc_contributor` varchar(128) DEFAULT '',
  `obm_dc_startdate` datetime DEFAULT '1970-01-01 00:00:00',
  `obm_dc_enddate` datetime DEFAULT '1970-01-01 00:00:00',
  `obm_dc_type` varchar(128) DEFAULT '',
  `obm_dc_format` varchar(128) DEFAULT '',
  `obm_dc_uri` varchar(255) DEFAULT '',
  `obm_dc_source` varchar(128) DEFAULT '',
  `obm_dc_language` varchar(32) DEFAULT '',
  `obm_dc_relation` varchar(255) DEFAULT '',
  `obm_dc_coverage` varchar(64) DEFAULT '',
  `obm_dc_entity` varchar(64) DEFAULT '',
  `obm_dc_comment` varchar(255) DEFAULT '',
  `obm_dc_extra` varchar(255) DEFAULT '',
  `obm_obj_status` varchar(1) NOT NULL DEFAULT 'A',
  `obm_cr_date` datetime NOT NULL DEFAULT '1970-01-01 00:00:00',
  `obm_cr_uid` int(11) NOT NULL DEFAULT '0',
  `obm_lu_date` datetime NOT NULL DEFAULT '1970-01-01 00:00:00',
  `obm_lu_uid` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`obm_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `z_objectdata_meta` */

/*Table structure for table `z_pagelock` */

CREATE TABLE `z_pagelock` (
  `plock_id` int(11) NOT NULL AUTO_INCREMENT,
  `plock_name` varchar(100) NOT NULL DEFAULT '',
  `plock_cdate` datetime NOT NULL,
  `plock_edate` datetime NOT NULL,
  `plock_session` varchar(50) NOT NULL,
  `plock_title` varchar(100) NOT NULL,
  `plock_ipno` varchar(30) NOT NULL,
  PRIMARY KEY (`plock_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `z_pagelock` */

/*Table structure for table `z_sc_anticracker` */

CREATE TABLE `z_sc_anticracker` (
  `pn_hid` int(11) NOT NULL AUTO_INCREMENT,
  `pn_hacktime` varchar(20) DEFAULT NULL,
  `pn_hackfile` varchar(255) DEFAULT '',
  `pn_hackline` int(11) DEFAULT NULL,
  `pn_hacktype` varchar(255) DEFAULT '',
  `pn_hackinfo` longtext,
  `pn_userid` int(11) DEFAULT NULL,
  `pn_browserinfo` longtext,
  `pn_requestarray` longtext,
  `pn_gettarray` longtext,
  `pn_postarray` longtext,
  `pn_serverarray` longtext,
  `pn_envarray` longtext,
  `pn_cookiearray` longtext,
  `pn_filesarray` longtext,
  `pn_sessionarray` longtext,
  PRIMARY KEY (`pn_hid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `z_sc_anticracker` */

/*Table structure for table `z_sc_log_event` */

CREATE TABLE `z_sc_log_event` (
  `lge_id` int(11) NOT NULL AUTO_INCREMENT,
  `lge_date` datetime DEFAULT NULL,
  `lge_uid` int(11) DEFAULT NULL,
  `lge_component` varchar(64) DEFAULT NULL,
  `lge_module` varchar(64) DEFAULT NULL,
  `lge_type` varchar(64) DEFAULT NULL,
  `lge_function` varchar(64) DEFAULT NULL,
  `lge_sec_component` varchar(64) DEFAULT NULL,
  `lge_sec_instance` varchar(64) DEFAULT NULL,
  `lge_sec_permission` varchar(64) DEFAULT NULL,
  `lge_message` varchar(255) DEFAULT '',
  PRIMARY KEY (`lge_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `z_sc_log_event` */

/*Table structure for table `z_search_result` */

CREATE TABLE `z_search_result` (
  `sres_id` int(11) NOT NULL AUTO_INCREMENT,
  `sres_title` varchar(255) NOT NULL DEFAULT '',
  `sres_text` longtext,
  `sres_module` varchar(100) DEFAULT NULL,
  `sres_extra` varchar(100) DEFAULT NULL,
  `sres_created` datetime DEFAULT NULL,
  `sres_found` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `sres_sesid` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`sres_id`),
  KEY `title` (`sres_title`),
  KEY `module` (`sres_module`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `z_search_result` */

/*Table structure for table `z_search_stat` */

CREATE TABLE `z_search_stat` (
  `pn_id` int(11) NOT NULL AUTO_INCREMENT,
  `pn_search` varchar(50) NOT NULL DEFAULT '',
  `pn_count` int(11) NOT NULL DEFAULT '0',
  `pn_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`pn_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `z_search_stat` */

/*Table structure for table `z_session_info` */

CREATE TABLE `z_session_info` (
  `pn_sessid` varchar(40) NOT NULL DEFAULT '',
  `pn_ipaddr` varchar(32) NOT NULL DEFAULT '',
  `pn_lastused` datetime DEFAULT '1970-01-01 00:00:00',
  `pn_uid` int(11) DEFAULT '0',
  `pn_remember` tinyint(4) NOT NULL DEFAULT '0',
  `pn_vars` longtext NOT NULL,
  PRIMARY KEY (`pn_sessid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `z_session_info` */

insert  into `z_session_info`(`pn_sessid`,`pn_ipaddr`,`pn_lastused`,`pn_uid`,`pn_remember`,`pn_vars`) values ('7baramcm55fb2k7h5rq281tuddfrh34b','f528764d624db129b32c21fbca0cb8d6','2011-07-01 06:29:43',2,0,'PNSVrand|a:0:{}PNSVuseragent|s:40:\"e575ca073bfd3627586b00cdf1b91fd8e44bc9a0\";PNSVuid|i:2;');

/*Table structure for table `z_themes` */

CREATE TABLE `z_themes` (
  `pn_id` int(11) NOT NULL AUTO_INCREMENT,
  `pn_name` varchar(64) NOT NULL DEFAULT '',
  `pn_type` tinyint(4) NOT NULL DEFAULT '0',
  `pn_displayname` varchar(64) NOT NULL DEFAULT '',
  `pn_description` varchar(255) NOT NULL DEFAULT '',
  `pn_regid` int(11) NOT NULL DEFAULT '0',
  `pn_directory` varchar(64) NOT NULL DEFAULT '',
  `pn_version` varchar(10) NOT NULL DEFAULT '0',
  `pn_official` tinyint(4) NOT NULL DEFAULT '0',
  `pn_author` varchar(255) NOT NULL DEFAULT '',
  `pn_contact` varchar(255) NOT NULL DEFAULT '',
  `pn_admin` tinyint(4) NOT NULL DEFAULT '0',
  `pn_user` tinyint(4) NOT NULL DEFAULT '0',
  `pn_system` tinyint(4) NOT NULL DEFAULT '0',
  `pn_state` tinyint(4) NOT NULL DEFAULT '0',
  `pn_credits` varchar(255) NOT NULL DEFAULT '',
  `pn_changelog` varchar(255) NOT NULL DEFAULT '',
  `pn_help` varchar(255) NOT NULL DEFAULT '',
  `pn_license` varchar(255) NOT NULL DEFAULT '',
  `pn_xhtml` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`pn_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

/*Data for the table `z_themes` */

insert  into `z_themes`(`pn_id`,`pn_name`,`pn_type`,`pn_displayname`,`pn_description`,`pn_regid`,`pn_directory`,`pn_version`,`pn_official`,`pn_author`,`pn_contact`,`pn_admin`,`pn_user`,`pn_system`,`pn_state`,`pn_credits`,`pn_changelog`,`pn_help`,`pn_license`,`pn_xhtml`) values (1,'andreas08',3,'Andreas08','The Andreas08 theme is a very good template for light CSS-compatible browser-oriented themes.',0,'andreas08','1.1',0,'David Brucas, Mark West, Andreas Viklund','http://dbrucas.povpromotions.com, http://www.markwest.me.uk, http://www.andreasviklund.com',1,1,0,1,'','','','',1),(2,'Atom',3,'Atom','The Atom theme is an auxiliary theme specially designed for rendering pages in Atom mark-up.',0,'Atom','1.0',0,'Franz Skaaning','http://www.lexebus.net/',0,0,1,1,'','','','',0),(3,'Printer',3,'Printer','The Printer theme is an auxiliary theme designed specially for outputting pages in a printer-friendly format.',0,'Printer','2.0',0,'Mark West','http://www.markwest.me.uk',0,0,1,1,'','','','',1),(4,'RSS',3,'RSS','The RSS theme is an auxiliary theme designed specially for outputting pages as an RSS feed.',0,'rss','1.0',0,'Mark West','http://www.markwest.me.uk',0,0,1,1,'docs/credits.txt','docs/changelog.txt','docs/help.txt','docs/license.txt',0),(5,'SeaBreeze',3,'SeaBreeze','The SeaBreeze theme is a browser-oriented theme, and was updated for the release of Zikula 1.0, with revised colours and new graphics.',0,'SeaBreeze','3.1',0,'Carsten Volmer, Vanessa Haakenson, Mark West, Martin Andersen','http://www.zikula.org',0,1,0,1,'','','','',1),(6,'VoodooDolly',3,'VoodooDolly','The VoodooDolly theme is a conservative browser-oriented theme with a Web 2.0 look and feel.',0,'voodoodolly','1.0',0,'Mark West, pogy366','http://www.markwest.me.uk, http://www.dbfnetwork.info/rayk/index.html',0,1,0,1,'','','','',1);

/*Table structure for table `z_userblocks` */

CREATE TABLE `z_userblocks` (
  `pn_uid` int(11) NOT NULL DEFAULT '0',
  `pn_bid` int(11) NOT NULL DEFAULT '0',
  `pn_active` tinyint(4) NOT NULL DEFAULT '1',
  `pn_last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY `bid_uid_idx` (`pn_uid`,`pn_bid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `z_userblocks` */

/*Table structure for table `z_users` */

CREATE TABLE `z_users` (
  `pn_uid` int(11) NOT NULL AUTO_INCREMENT,
  `pn_uname` varchar(25) NOT NULL DEFAULT '',
  `pn_email` varchar(60) NOT NULL DEFAULT '',
  `pn_user_regdate` datetime NOT NULL DEFAULT '1970-01-01 00:00:00',
  `pn_user_viewemail` smallint(6) DEFAULT '0',
  `pn_user_theme` varchar(64) DEFAULT '',
  `pn_pass` varchar(128) NOT NULL DEFAULT '',
  `pn_storynum` int(4) NOT NULL DEFAULT '10',
  `pn_ublockon` tinyint(4) NOT NULL DEFAULT '0',
  `pn_ublock` text,
  `pn_theme` varchar(255) NOT NULL DEFAULT '',
  `pn_counter` int(11) NOT NULL DEFAULT '0',
  `pn_activated` tinyint(4) NOT NULL DEFAULT '0',
  `pn_lastlogin` datetime NOT NULL DEFAULT '1970-01-01 00:00:00',
  `pn_validfrom` int(11) NOT NULL DEFAULT '0',
  `pn_validuntil` int(11) NOT NULL DEFAULT '0',
  `pn_hash_method` tinyint(4) NOT NULL DEFAULT '8',
  PRIMARY KEY (`pn_uid`),
  KEY `uname` (`pn_uname`),
  KEY `email` (`pn_email`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `z_users` */

insert  into `z_users`(`pn_uid`,`pn_uname`,`pn_email`,`pn_user_regdate`,`pn_user_viewemail`,`pn_user_theme`,`pn_pass`,`pn_storynum`,`pn_ublockon`,`pn_ublock`,`pn_theme`,`pn_counter`,`pn_activated`,`pn_lastlogin`,`pn_validfrom`,`pn_validuntil`,`pn_hash_method`) values (1,'guest','','1970-01-01 00:00:00',0,'','',10,0,'','',0,1,'1970-01-01 00:00:00',0,0,1),(2,'admin','admin@localhost.com','2011-07-01 06:28:55',0,'','1b6a15d1c1c86cbb5f921e7eb5c02b8e6162a97117930a0985efd07409204bdb',10,0,'','',0,1,'2011-07-01 06:28:55',0,0,8);

/*Table structure for table `z_users_temp` */

CREATE TABLE `z_users_temp` (
  `pn_tid` int(11) NOT NULL AUTO_INCREMENT,
  `pn_uname` varchar(25) NOT NULL DEFAULT '',
  `pn_email` varchar(60) NOT NULL DEFAULT '',
  `pn_femail` tinyint(4) NOT NULL DEFAULT '0',
  `pn_pass` varchar(128) NOT NULL DEFAULT '',
  `pn_dynamics` longtext NOT NULL,
  `pn_comment` varchar(254) NOT NULL DEFAULT '',
  `pn_type` tinyint(4) NOT NULL DEFAULT '0',
  `pn_tag` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`pn_tid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `z_users_temp` */

/*Table structure for table `z_workflows` */

CREATE TABLE `z_workflows` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `metaid` int(11) NOT NULL DEFAULT '0',
  `module` varchar(255) NOT NULL DEFAULT '',
  `schemaname` varchar(255) NOT NULL DEFAULT '',
  `state` varchar(255) NOT NULL DEFAULT '',
  `type` smallint(6) NOT NULL DEFAULT '1',
  `obj_table` varchar(40) NOT NULL DEFAULT '',
  `obj_idcolumn` varchar(40) NOT NULL DEFAULT '',
  `obj_id` int(11) NOT NULL DEFAULT '0',
  `busy` int(11) NOT NULL DEFAULT '0',
  `debug` longblob,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `z_workflows` */

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
