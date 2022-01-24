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
-- Table structure for table `map_fish_drop_data`
--

DROP TABLE IF EXISTS `map_fish_drop_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `map_fish_drop_data` (
  `map_id` int(11) NOT NULL,
  `fish_id` int(11) NOT NULL,
  `min_water_depth` int(11) NOT NULL,
  `max_water_depth` int(11) NOT NULL,
  PRIMARY KEY (`map_id`,`fish_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `map_fish_drop_data`
--

LOCK TABLES `map_fish_drop_data` WRITE;
/*!40000 ALTER TABLE `map_fish_drop_data` DISABLE KEYS */;
INSERT INTO `map_fish_drop_data` VALUES (1,13,20,40),(1,14,20,40),(1,15,20,40),(1,16,20,40),(1,17,20,40),(1,18,10,40),(1,19,10,30),(1,20,0,30),(1,21,0,20),(1,22,0,20),(1,23,0,20),(2,5,50,70),(2,6,50,70),(2,7,50,70),(2,8,40,70),(2,10,40,60),(2,11,30,60),(2,12,30,50),(2,13,20,50),(2,15,20,34),(2,17,10,30),(2,18,10,30),(2,19,10,20),(2,21,0,20),(2,22,0,20),(2,23,0,20),(3,3,40,60),(3,4,40,60),(3,7,40,60),(3,8,30,60),(3,9,30,60),(3,12,30,50),(3,13,20,40),(3,14,20,50),(3,15,20,40),(3,16,20,40),(3,19,10,30),(3,20,10,30),(3,21,0,30),(3,22,0,20),(3,23,0,30),(4,1,80,100),(4,2,70,100),(4,3,70,95),(4,4,60,90),(4,5,60,90),(4,6,50,90),(4,7,50,80),(4,8,40,60),(4,9,30,40),(4,10,20,40),(4,19,10,30),(4,20,0,30),(4,22,0,20),(4,23,0,20);
/*!40000 ALTER TABLE `map_fish_drop_data` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-01-24 18:15:08
