DROP TABLE IF EXISTS `weight_data`;

CREATE TABLE `weight_data` (
  `weight_id` int(11) NOT NULL AUTO_INCREMENT,
  `weight_name` varchar(45) NOT NULL,
  `item_type_id` int(11) NOT NULL,
  `weight` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  PRIMARY KEY (`weight_id`),
  KEY `weightItem_idx` (`item_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

INSERT INTO `weight_data` VALUES (1,'가벼운 추',5,3,5),(2,'무게감 있는 추',5,5,10),(3,'무거운 추',5,8,15);