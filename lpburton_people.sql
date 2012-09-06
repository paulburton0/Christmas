-- MySQL dump 10.9
--
-- Host: localhost    Database: lpburton_people
-- ------------------------------------------------------
-- Server version	4.1.22-standard

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `people`
--

DROP TABLE IF EXISTS `people`;
CREATE TABLE `people` (
  `id` tinyint(1) unsigned NOT NULL auto_increment,
  `name` varchar(15) collate utf8_unicode_ci NOT NULL default '',
  `email` varchar(64) collate utf8_unicode_ci default NULL,
  `password` varchar(255) collate utf8_unicode_ci default NULL,
  `spouse_id` tinyint(1) NOT NULL default '0',
  `location` char(1) collate utf8_unicode_ci NOT NULL default '',
  `assigned_person_id` tinyint(1) default NULL,
  `is_assigned` char(1) collate utf8_unicode_ci NOT NULL default 'N',
  `active` char(1) collate utf8_unicode_ci NOT NULL default 'N',
  `is_admin` char(1) collate utf8_unicode_ci NOT NULL default 'N',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `people`
--

LOCK TABLES `people` WRITE;
/*!40000 ALTER TABLE `people` DISABLE KEYS */;
INSERT INTO `people` (`id`, `name`, `email`, `password`, `spouse_id`, `location`, `assigned_person_id`, `is_assigned`, `active`, `is_admin`) VALUES (1,'Paul Burton','paulburto@gmail.com','patwHV5Hr541w',2,'E',9,'N','Y','Y'),(2,'Linda Burton',NULL,NULL,1,'E',NULL,'N','N','N'),(3,'Dwayne Melton',NULL,NULL,4,'E',NULL,'N','N','N'),(4,'Becky Melton','rlbmrn@yahoo.com','rl8iLQLxGPuKQ',3,'E',NULL,'N','N','N'),(5,'Suzie Burton','auntasuzie@verizon.net','aud8Ux0QMySp2',0,'E',10,'Y','Y','N'),(6,'Jenn Mason','mikenjen@shentel.net','mikyNds3VJrbc',7,'W',5,'N','Y','N'),(7,'Mike Mason',NULL,NULL,6,'W',NULL,'N','N','N'),(8,'Kent Mason',NULL,NULL,9,'W',NULL,'N','N','N'),(9,'Mary Mason','kentmary@shentel.net','keGDTHGRl/2z.',8,'W',11,'Y','Y','N'),(10,'Jeremy Keegan',NULL,NULL,11,'W',NULL,'Y','N','N'),(11,'Sarah Keegan',NULL,NULL,10,'W',NULL,'Y','N','N');
/*!40000 ALTER TABLE `people` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wishlist`
--

DROP TABLE IF EXISTS `wishlist`;
CREATE TABLE `wishlist` (
  `id` int(10) NOT NULL auto_increment,
  `person_id` varchar(2) collate utf8_unicode_ci NOT NULL default '',
  `title` varchar(255) collate utf8_unicode_ci NOT NULL default '',
  `description` text collate utf8_unicode_ci,
  `links` text collate utf8_unicode_ci,
  `images` text collate utf8_unicode_ci,
  `status` char(1) collate utf8_unicode_ci default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `wishlist`
--

LOCK TABLES `wishlist` WRITE;
/*!40000 ALTER TABLE `wishlist` DISABLE KEYS */;
INSERT INTO `wishlist` (`id`, `person_id`, `title`, `description`, `links`, `images`, `status`) VALUES (1,'1','Books','Any kind really, or a gift certificate to Amazon or Borders.','<a href=\"http://www.amazon.com/gp/product/B00067L6TQ/ref=topnav_giftcert_gw\" target=\"_blank\">http://www.amazon.com/gp/product/B00067L6TQ/ref=topnav_giftcert_gw</a><br />',NULL,NULL);
/*!40000 ALTER TABLE `wishlist` ENABLE KEYS */;
UNLOCK TABLES;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

