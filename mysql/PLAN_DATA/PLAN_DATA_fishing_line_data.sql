-- MySQL dump 10.13  Distrib 8.0.27, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: PLAN_DATA
-- ------------------------------------------------------
-- Server version	5.7.36

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `fishing_line_data`
--

DROP TABLE IF EXISTS `fishing_line_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `fishing_line_data` (
  `fishing_line_id` int(11) NOT NULL AUTO_INCREMENT,
  `fishign_line_name` varchar(45) NOT NULL,
  `item_type_id` int(11) NOT NULL,
  `number` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `buff_suppress_rate` int(11) NOT NULL,
  `buff_hooking_rate` int(11) NOT NULL,
  `possible_weight` int(11) NOT NULL,
  PRIMARY KEY (`fishing_line_id`),
  KEY `itemId_idx` (`item_type_id`),
  CONSTRAINT `fishingLineItem` FOREIGN KEY (`item_type_id`) REFERENCES `item_type_data` (`item_type_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `itemId` FOREIGN KEY (`item_type_id`) REFERENCES `item_type_data` (`item_type_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fishing_line_data`
--

LOCK TABLES `fishing_line_data` WRITE;
/*!40000 ALTER TABLE `fishing_line_data` DISABLE KEYS */;
INSERT INTO `fishing_line_data` VALUES (1,'기본 낚시줄',9,1,400,65,30,3),(2,'낚시줄',9,2,1200,80,20,10),(3,'낚시줄',9,3,2000,92,10,20),(4,'단단한 낚시줄',9,3,1500,85,10,30);
/*!40000 ALTER TABLE `fishing_line_data` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-02-03 14:50:30
