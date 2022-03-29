DROP TABLE IF EXISTS `weather_type_data`;

CREATE TABLE `weather_type_data` (
  `weather_id` int(11) NOT NULL AUTO_INCREMENT,
  `weather_type` varchar(10) CHARACTER SET utf8mb4 NOT NULL,
  PRIMARY KEY (`weather_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

INSERT INTO `weather_type_data` VALUES (1,'조류'),(2,'바람'),(3,'기온');