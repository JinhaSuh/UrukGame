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
-- Table structure for table `hook_data`
--

DROP TABLE IF EXISTS `hook_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `hook_data` (
  `hook_id` int(11) NOT NULL AUTO_INCREMENT,
  `hook_name` varchar(45) NOT NULL,
  `item_type_id` int(11) NOT NULL,
  `number` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `buff_suppress_rate` int(11) NOT NULL,
  `buff_fish_drop_rate` int(11) NOT NULL,
  PRIMARY KEY (`hook_id`),
  KEY `hookItem_idx` (`item_type_id`),
  CONSTRAINT `hookItem` FOREIGN KEY (`item_type_id`) REFERENCES `item_type_data` (`item_type_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hook_data`
--

LOCK TABLES `hook_data` WRITE;
/*!40000 ALTER TABLE `hook_data` DISABLE KEYS */;
INSERT INTO `hook_data` VALUES (1,'기본 낚시 바늘',8,1,400,65,99),(2,'낚시 바늘',8,1,400,68,98),(3,'낚시 바늘',8,1,400,75,97),(4,'낚시 바늘',8,2,1200,80,95),(5,'낚시 바늘',8,2,1200,85,93),(6,'낚시 바늘',8,2,1200,88,91),(7,'낚시 바늘',8,3,2000,92,90),(8,'낚시 바늘',8,3,2000,94,88),(9,'낚시 바늘',8,3,2000,98,84),(10,'더블 훅',8,3,1500,90,90);
/*!40000 ALTER TABLE `hook_data` ENABLE KEYS */;
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
