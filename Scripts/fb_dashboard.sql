-- MySQL dump 10.13  Distrib 5.6.12, for osx10.6 (x86_64)
--
-- Host: localhost    Database: fb_dashboard
-- ------------------------------------------------------
-- Server version	5.6.12

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
-- Table structure for table `App_FB_Users`
--

DROP TABLE IF EXISTS `App_FB_Users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `App_FB_Users` (
  `App_FBuid` bigint(20) NOT NULL,
  `FB_FBuid` bigint(20) NOT NULL,
  `MutualFriends` int(11) NOT NULL,
  `SharedPhoto` int(11) DEFAULT NULL,
  `SharedStatus` int(11) DEFAULT NULL,
  `SharedPost` int(11) DEFAULT NULL,
  `SharedCheckin` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Table des relations entre les utilisateurs et leurs amis sur FB';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Friends`
--

DROP TABLE IF EXISTS `Friends`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Friends` (
  `FriendUid` int(11) NOT NULL AUTO_INCREMENT,
  `FBuid` bigint(20) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `FriendCount` int(11) DEFAULT NULL,
  `PostCount` int(11) DEFAULT NULL,
  `Sex` varchar(10) DEFAULT NULL,
  `Birthday` date DEFAULT NULL,
  `Picture` varchar(100) NOT NULL,
  `CurrentCountry` varchar(50) DEFAULT NULL,
  `CurrentCity` varchar(100) DEFAULT NULL,
  `OriginCountry` varchar(50) DEFAULT NULL,
  `WorkCompany` varchar(100) CHARACTER SET latin1 COLLATE latin1_bin DEFAULT NULL,
  `School` varchar(100) DEFAULT NULL,
  `UpdateDate` timestamp NULL DEFAULT NULL,
  `AddDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `AddUser` int(11) NOT NULL,
  PRIMARY KEY (`FriendUid`),
  UNIQUE KEY `FBuid` (`FBuid`)
) ENGINE=InnoDB AUTO_INCREMENT=4797 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Likes`
--

DROP TABLE IF EXISTS `Likes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Likes` (
  `FBuid` bigint(11) NOT NULL,
  `FBpid` bigint(11) NOT NULL,
  `AddDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Relationship`
--

DROP TABLE IF EXISTS `Relationship`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Relationship` (
  `FBuid1` bigint(20) NOT NULL,
  `FBuid2` bigint(20) NOT NULL,
  `AddDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `EndDate` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Table des couples';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Users`
--

DROP TABLE IF EXISTS `Users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Users` (
  `Uid` int(11) NOT NULL AUTO_INCREMENT,
  `FBuid` bigint(20) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `FriendCount` int(11) NOT NULL,
  `PostCount` int(11) NOT NULL,
  `Picture` varchar(100) NOT NULL,
  `Top10` varchar(300) DEFAULT NULL,
  `LastLoginDate` timestamp NULL DEFAULT NULL,
  `AddDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`Uid`),
  UNIQUE KEY `FBuid` (`FBuid`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1 COMMENT='Table des utilisateurs de l''application';
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2013-12-17 22:29:38
