DROP TABLE IF EXISTS `item_type_data`;

CREATE TABLE `item_type_data` (
  `item_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `item_type_name` varchar(45) NOT NULL,
  PRIMARY KEY (`item_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4;

INSERT INTO `item_type_data` VALUES (1,'gold'),(2,'pearl'),(3,'fish'),(4,'fishing_rod'),(5,'weight'),(6,'bait'),(7,'reel'),(8,'hook'),(9,'fishing_line'),(10,'boat');