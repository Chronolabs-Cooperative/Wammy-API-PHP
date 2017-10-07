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
-- Table structure for table `sentences`
--

DROP TABLE IF EXISTS `sentences`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sentences` (
  `sid` mediumint(128) NOT NULL DEFAULT '0',
  `numbers` mediumint(32) NOT NULL DEFAULT '0',
  `words` mediumint(32) NOT NULL DEFAULT '0',
  `spammers` mediumint(32) NOT NULL DEFAULT '0',
  `hammers` mediumint(32) NOT NULL DEFAULT '0',
  `crassifications` mediumint(32) NOT NULL DEFAULT '0',
  `breaths` mediumint(32) NOT NULL DEFAULT '0',
  `index` mediumint(32) NOT NULL DEFAULT '0',
  `total` mediumint(32) NOT NULL DEFAULT '0',
  `key` varchar(32) NOT NULL DEFAULT '',
  `sequence` varchar(32) NOT NULL DEFAULT '',
  `job` varchar(32) NOT NULL DEFAULT '',
  `mode` enum('textual','image','unknown') NOT NULL DEFAULT 'unknown',
  `typal` enum('sentence','header','question','list','unknown') NOT NULL DEFAULT 'unknown',
  `balance` enum('spamming','hamming','crassed','balanced') NOT NULL DEFAULT 'balanced',
  `highest` mediumint(32) NOT NULL DEFAULT '0',
  `lowest` mediumint(32) NOT NULL DEFAULT '0',
  `maximum` mediumint(32) NOT NULL DEFAULT '0',
  `minimum` mediumint(32) NOT NULL DEFAULT '0',
  `average` float(42,22) NOT NULL DEFAULT '0.0000000000000000000000',
  `stdev` float(42,22) NOT NULL DEFAULT '0.0000000000000000000000',
  `occasions_alpha` float(42,22) NOT NULL DEFAULT '0.0000000000000000000000',
  `hams_alpha` float(42,22) NOT NULL DEFAULT '0.0000000000000000000000',
  `spams_alpha` float(42,22) NOT NULL DEFAULT '0.0000000000000000000000',
  `forgots_alpha` float(42,22) NOT NULL DEFAULT '0.0000000000000000000000',
  `occasions_gamma` float(42,22) NOT NULL DEFAULT '0.0000000000000000000000',
  `hams_gamma` float(42,22) NOT NULL DEFAULT '0.0000000000000000000000',
  `spams_gamma` float(42,22) NOT NULL DEFAULT '0.0000000000000000000000',
  `forgots_gamma` float(42,22) NOT NULL DEFAULT '0.0000000000000000000000',
  `hits` mediumint(32) NOT NULL DEFAULT '0',
  `tests` mediumint(32) NOT NULL DEFAULT '0',
  `trainings` mediumint(32) NOT NULL DEFAULT '0',
  `images` mediumint(32) NOT NULL DEFAULT '0',
  `spammings` float(42,22) NOT NULL DEFAULT '0.0000000000000000000000',
  `hammings` float(42,22) NOT NULL DEFAULT '0.0000000000000000000000',
  `forgottens` float(42,22) NOT NULL DEFAULT '0.0000000000000000000000',
  `occasions` mediumint(32) NOT NULL DEFAULT '0',
  `hams` mediumint(32) NOT NULL DEFAULT '0',
  `spams` mediumint(32) NOT NULL DEFAULT '0',
  `forgots` mediumint(32) NOT NULL DEFAULT '0',
  `alpha` float(42,22) NOT NULL DEFAULT '0.0000000000000000000000',
  `gamma` float(42,22) NOT NULL DEFAULT '0.0000000000000000000000',
  `actionable` int(14) NOT NULL DEFAULT '0',
  `updated` int(14) NOT NULL DEFAULT '0',
  `created` int(14) NOT NULL DEFAULT '0',
  PRIMARY KEY (`sid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sentences`
--

LOCK TABLES `sentences` WRITE;
/*!40000 ALTER TABLE `sentences` DISABLE KEYS */;
/*!40000 ALTER TABLE `sentences` ENABLE KEYS */;
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
