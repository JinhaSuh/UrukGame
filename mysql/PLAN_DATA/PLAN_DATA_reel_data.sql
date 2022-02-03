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
-- Table structure for table `reel_data`
--

DROP TABLE IF EXISTS `reel_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reel_data` (
  `reel_id` int(11) NOT NULL AUTO_INCREMENT,
  `reel_name` varchar(45) NOT NULL,
  `item_type_id` int(11) NOT NULL,
  `grade_id` int(11) NOT NULL,
  `number` int(11) NOT NULL,
  `max_durability` int(11) NOT NULL,
  `winding_amount` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  PRIMARY KEY (`reel_id`),
  KEY `reelGrade_idx` (`grade_id`),
  KEY `reelItem_idx` (`item_type_id`),
  CONSTRAINT `reelGrade` FOREIGN KEY (`grade_id`) REFERENCES `grade_type_data` (`grade_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `reelItem` FOREIGN KEY (`item_type_id`) REFERENCES `item_type_data` (`item_type_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reel_data`
--

LOCK TABLES `reel_data` WRITE;
/*!40000 ALTER TABLE `reel_data` DISABLE KEYS */;
INSERT INTO `reel_data` VALUES (1,'기본 릴',7,0,1,8,30,400),(2,'릴',7,0,2,10,40,400),(3,'릴',7,0,3,11,55,400),(4,'릴',7,1,1,15,70,1200),(5,'릴',7,1,2,16,80,1200),(6,'릴',7,1,3,17,90,1200),(7,'릴',7,2,1,24,100,2000),(8,'릴',7,2,2,27,110,2000),(9,'릴',7,2,3,30,120,2000),(10,'베이트 릴',7,-1,-1,10,70,1000);
/*!40000 ALTER TABLE `reel_data` ENABLE KEYS */;
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
