-- MySQL dump 10.13  Distrib 5.6.28, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: wammy-labs-coop
-- ------------------------------------------------------
-- Server version	5.6.28-0ubuntu0.15.10.1

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
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `userid` varchar(32) NOT NULL,
  `peerid` varchar(32) NOT NULL,
  `type` enum('Sender','Recipient') NOT NULL DEFAULT 'Sender',
  `domain` varchar(200) NOT NULL,
  `username` varchar(64) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(198) NOT NULL,
  `last-ipid` varchar(32) NOT NULL,
  `score-alpha` float(12,8) NOT NULL DEFAULT '0.00000000',
  `score-beta` float(12,8) NOT NULL DEFAULT '0.00000000',
  `maximum-alpha` float(12,8) NOT NULL DEFAULT '0.00000000',
  `maximum-beta` float(12,8) NOT NULL DEFAULT '0.00000000',
  `average-alpha` float(12,8) NOT NULL DEFAULT '0.00000000',
  `average-beta` float(12,8) NOT NULL DEFAULT '0.00000000',
  `stddev-alpha` float(12,8) NOT NULL DEFAULT '0.00000000',
  `stddev-beta` float(12,8) NOT NULL DEFAULT '0.00000000',
  `tests` int(16) NOT NULL DEFAULT '0',
  `spams` int(16) NOT NULL DEFAULT '0',
  `hams` int(16) NOT NULL DEFAULT '0',
  `forgets` int(16) NOT NULL DEFAULT '0',
  `created` int(12) NOT NULL DEFAULT '0',
  `last` int(12) NOT NULL DEFAULT '0',
  PRIMARY KEY (`userid`,`peerid`,`type`,`username`,`domain`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-03-06 12:58:51
