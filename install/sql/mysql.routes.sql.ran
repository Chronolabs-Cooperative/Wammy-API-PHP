-- MySQL dump 10.13  Distrib 5.7.19, for Linux (x86_64)
--
-- Host: localhost    Database: wammy-labs-coop
-- ------------------------------------------------------
-- Server version	5.7.19-0ubuntu0.16.04.1

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
-- Table structure for table `routes`
--

DROP TABLE IF EXISTS `routes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `routes` (
  `rid` mediumint(196) NOT NULL DEFAULT '0',
  `jid` mediumint(196) NOT NULL DEFAULT '0',
  `mode` enum('training','testing') NOT NULL DEFAULT 'testing',
  `medium` enum('textual','image') NOT NULL DEFAULT 'textual',
  `mimetype` enum('text/plain','text/html') NOT NULL DEFAULT 'text/plain',
  `training` enum('spam','ham','forgot','none') NOT NULL DEFAULT 'none',
  `api_url` varchar(255) NOT NULL,
  `path` varchar(255) NOT NULL,
  `file` varchar(255) NOT NULL,
  `subject` tinytext,
  `recipient_username` varchar(64) NOT NULL DEFAULT '',
  `recipient_name` varchar(64) NOT NULL DEFAULT '',
  `recipient_email` varchar(196) NOT NULL DEFAULT '',
  `recipient_ip` varchar(196) NOT NULL DEFAULT '',
  `senders_username` varchar(64) NOT NULL DEFAULT '',
  `senders_name` varchar(64) NOT NULL DEFAULT '',
  `senders_email` varchar(196) NOT NULL DEFAULT '',
  `senders_ip` varchar(196) NOT NULL DEFAULT '',
  `actionable` int(14) NOT NULL DEFAULT '0',
  `updated` int(14) NOT NULL DEFAULT '0',
  `created` int(14) NOT NULL DEFAULT '0',
  PRIMARY KEY (`rid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `routes`
--

LOCK TABLES `routes` WRITE;
/*!40000 ALTER TABLE `routes` DISABLE KEYS */;
/*!40000 ALTER TABLE `routes` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-10-08  2:18:22
