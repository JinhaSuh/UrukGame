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
-- Table structure for table `fish_data`
--

DROP TABLE IF EXISTS `fish_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `fish_data` (
  `fish_id` int(11) NOT NULL AUTO_INCREMENT,
  `fish_name` varchar(45) NOT NULL,
  `rarity` int(11) NOT NULL,
  `min_length` int(11) NOT NULL,
  `max_length` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `exp` int(11) NOT NULL,
  PRIMARY KEY (`fish_id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fish_data`
--

LOCK TABLES `fish_data` WRITE;
/*!40000 ALTER TABLE `fish_data` DISABLE KEYS */;
INSERT INTO `fish_data` VALUES (1,'다금바리',8,100,140,20000,80),(2,'줄가자미',7,40,55,18000,70),(3,'붉바리',7,35,45,16000,70),(4,'돌돔',7,35,55,17000,70),(5,'강담돔',5,60,80,10000,60),(6,'자바리',6,100,120,14000,60),(7,'능성어',5,80,100,10000,50),(8,'볼락',4,30,40,8000,50),(9,'쥐치',4,15,24,8000,40),(10,'우럭',4,50,75,8000,40),(11,'감성돔',4,30,60,7000,40),(12,'광어',4,40,80,8000,40),(13,'고등어',4,30,50,8000,40),(14,'전어',4,15,30,7000,40),(15,'가자미',3,15,25,3000,30),(16,'방어',3,90,110,4000,30),(17,'농어',3,90,100,4000,30),(18,'붕장어',4,50,100,5000,40),(19,'독가시치',3,30,50,4000,30),(20,'숭어',3,30,80,2000,30),(21,'참돔',2,80,100,1000,20),(22,'고등어',2,20,50,500,20),(23,'놀래미',2,20,50,500,20);
/*!40000 ALTER TABLE `fish_data` ENABLE KEYS */;
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
