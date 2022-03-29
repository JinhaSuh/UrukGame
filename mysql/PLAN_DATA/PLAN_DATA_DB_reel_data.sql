DROP TABLE IF EXISTS `reel_data`;

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
  KEY `reelItem_idx` (`item_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4;

INSERT INTO `reel_data` VALUES (1,'기본 릴',7,0,1,8,30,400),(2,'릴',7,0,2,10,40,400),(3,'릴',7,0,3,11,55,400),(4,'릴',7,1,1,15,70,1200),(5,'릴',7,1,2,16,80,1200),(6,'릴',7,1,3,17,90,1200),(7,'릴',7,2,1,24,100,2000),(8,'릴',7,2,2,27,110,2000),(9,'릴',7,2,3,30,120,2000),(10,'베이트 릴',7,-1,-1,10,70,1000);
