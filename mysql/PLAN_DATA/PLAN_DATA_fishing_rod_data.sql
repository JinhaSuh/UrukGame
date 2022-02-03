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
-- Table structure for table `fishing_rod_data`
--

DROP TABLE IF EXISTS `fishing_rod_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `fishing_rod_data` (
  `fishing_rod_id` int(11) NOT NULL AUTO_INCREMENT,
  `fishing_rod_name` varchar(45) NOT NULL,
  `item_type_id` int(11) NOT NULL,
  `grade_id` int(11) NOT NULL,
  `step` int(11) NOT NULL,
  `hardness` int(11) NOT NULL,
  `price` int(11) DEFAULT NULL,
  `buff_suppress_rate` int(11) NOT NULL,
  `buff_hooking_rate` int(11) NOT NULL,
  `max_durability` int(11) NOT NULL,
  PRIMARY KEY (`fishing_rod_id`),
  KEY `gradeId_idx` (`grade_id`),
  KEY `fishingRodItem_idx` (`item_type_id`),
  CONSTRAINT `fishingRodGrade` FOREIGN KEY (`grade_id`) REFERENCES `grade_type_data` (`grade_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fishingRodItem` FOREIGN KEY (`item_type_id`) REFERENCES `item_type_data` (`item_type_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=201 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fishing_rod_data`
--

LOCK TABLES `fishing_rod_data` WRITE;
/*!40000 ALTER TABLE `fishing_rod_data` DISABLE KEYS */;
INSERT INTO `fishing_rod_data` VALUES (1,'일반 낚싯대',4,0,0,3,400,65,30,40),(2,'일반 낚싯대',4,0,1,5,500,68,30,45),(3,'일반 낚싯대',4,0,2,6,600,75,30,50),(4,'희귀 낚싯대',4,1,0,10,2000,80,20,70),(5,'희귀 낚싯대',4,1,1,14,2500,85,20,80),(6,'희귀 낚싯대',4,1,2,17,3000,88,20,100),(7,'전설 낚싯대',4,2,0,20,10000,92,10,150),(8,'전설 낚싯대',4,2,1,23,12000,94,10,180),(9,'전설 낚싯대',4,2,2,30,14000,98,10,200),(100,'누군가가 빠뜨린 낚싯대',4,0,100,10,200,75,30,10),(200,'개발자의 낚싯대',4,2,100,100,3333,100,30,1);
/*!40000 ALTER TABLE `fishing_rod_data` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-02-03 14:50:28
