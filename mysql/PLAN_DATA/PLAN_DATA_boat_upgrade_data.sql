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
-- Table structure for table `boat_upgrade_data`
--

DROP TABLE IF EXISTS `boat_upgrade_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `boat_upgrade_data` (
  `boat_id` int(11) NOT NULL,
  `step` int(11) NOT NULL,
  `success_rate` int(11) NOT NULL,
  `max_durability` int(11) NOT NULL,
  `max_fuel` int(11) NOT NULL,
  `reduce_durability_case_failure` int(11) NOT NULL,
  `reduce_durability_per_min` int(11) NOT NULL,
  `speed_meter_per_sec` int(11) NOT NULL,
  `need_item_type_id` int(11) NOT NULL,
  `need_count` int(11) NOT NULL,
  PRIMARY KEY (`boat_id`),
  KEY `needItem_idx` (`need_item_type_id`),
  CONSTRAINT `needItem` FOREIGN KEY (`need_item_type_id`) REFERENCES `item_type_data` (`item_type_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `boat_upgrade_data`
--

LOCK TABLES `boat_upgrade_data` WRITE;
/*!40000 ALTER TABLE `boat_upgrade_data` DISABLE KEYS */;
INSERT INTO `boat_upgrade_data` VALUES (1,0,100,300,50,0,100,1,1,4000),(2,1,95,400,60,10,95,2,1,6000),(3,2,85,500,80,20,90,3,1,10000),(4,3,80,600,100,30,85,4,1,20000),(5,4,60,900,170,40,80,5,1,40000),(6,5,50,1200,200,50,70,6,2,1);
/*!40000 ALTER TABLE `boat_upgrade_data` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-02-03 14:50:27
