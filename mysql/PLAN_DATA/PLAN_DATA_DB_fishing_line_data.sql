DROP TABLE IF EXISTS `fishing_line_data`;

CREATE TABLE `fishing_line_data` (
  `fishing_line_id` int(11) NOT NULL AUTO_INCREMENT,
  `fishing_line_name` varchar(45) NOT NULL,
  `item_type_id` int(11) NOT NULL,
  `number` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `buff_suppress_rate` int(11) NOT NULL,
  `buff_hooking_rate` int(11) NOT NULL,
  `possible_weight` int(11) NOT NULL,
  PRIMARY KEY (`fishing_line_id`),
  KEY `itemId_idx` (`item_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

INSERT INTO `fishing_line_data` VALUES (1,'기본 낚시줄',9,1,400,75,60,3),(2,'낚시줄',9,2,1200,80,50,10),(3,'낚시줄',9,3,2000,92,40,20),(4,'단단한 낚시줄',9,3,1500,85,40,30);
