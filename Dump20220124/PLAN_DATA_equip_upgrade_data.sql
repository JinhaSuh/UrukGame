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
-- Table structure for table `equip_upgrade_data`
--

DROP TABLE IF EXISTS `equip_upgrade_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `equip_upgrade_data` (
  `item_type_id` int(11) NOT NULL,
  `grade_id` int(11) NOT NULL,
  `step` int(11) NOT NULL,
  `need_item_type_id` int(11) NOT NULL,
  `need_count` int(11) NOT NULL,
  PRIMARY KEY (`item_type_id`,`step`,`grade_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `equip_upgrade_data`
--

LOCK TABLES `equip_upgrade_data` WRITE;
/*!40000 ALTER TABLE `equip_upgrade_data` DISABLE KEYS */;
INSERT INTO `equip_upgrade_data` VALUES (4,1,1,0,500),(4,2,1,0,500),(4,3,1,0,4000),(4,1,2,0,700),(4,2,2,0,1500),(4,3,2,0,6000),(4,1,3,0,900),(4,2,3,0,2000),(4,3,3,0,10000),(7,1,1,0,200),(7,2,1,0,500),(7,3,1,0,1500),(7,1,2,0,300),(7,2,2,0,1000),(7,3,2,0,1800),(7,1,3,0,400),(7,2,3,0,1300),(7,3,3,0,2000),(9,0,1,0,800),(9,0,2,0,2000),(9,0,3,0,5000);
/*!40000 ALTER TABLE `equip_upgrade_data` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-01-24 18:15:10
