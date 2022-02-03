-- MySQL dump 10.13  Distrib 8.0.27, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: GAME_DATA
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
-- Table structure for table `maiilbox`
--

DROP TABLE IF EXISTS `maiilbox`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `maiilbox` (
  `user_id` int(11) NOT NULL,
  `item_type_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `item_count` int(11) NOT NULL,
  `recv_date` datetime NOT NULL,
  `expr_date` datetime NOT NULL,
  PRIMARY KEY (`user_id`,`item_type_id`,`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `maiilbox`
--

LOCK TABLES `maiilbox` WRITE;
/*!40000 ALTER TABLE `maiilbox` DISABLE KEYS */;
INSERT INTO `maiilbox` VALUES (1,1,0,15000,'2022-01-23 22:11:11','2022-01-23 22:11:11'),(1,2,0,3,'2022-01-23 22:11:12','2022-01-23 22:11:12'),(1,4,100,1,'2022-01-23 22:11:13','2022-01-23 22:11:13'),(1,5,3,1,'2022-01-23 22:11:14','2022-01-23 22:11:14'),(1,6,4,1,'2022-01-23 22:11:15','2022-01-23 22:11:15'),(1,7,2,1,'2022-01-23 22:11:16','2022-01-23 22:11:16'),(1,8,5,1,'2022-01-23 22:11:17','2022-01-23 22:11:17'),(1,9,3,1,'2022-01-23 22:11:18','2022-01-23 22:11:18'),(1,10,3,1,'2022-01-23 22:11:19','2022-01-23 22:11:19');
/*!40000 ALTER TABLE `maiilbox` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-02-03 14:50:25