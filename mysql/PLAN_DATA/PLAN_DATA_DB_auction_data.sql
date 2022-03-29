DROP TABLE IF EXISTS `auction_data`;

CREATE TABLE `auction_data` (
  `fish_id` int(11) NOT NULL,
  `iter_term` int(11) NOT NULL,
  `min_sell_rate` int(11) NOT NULL,
  `max_sell_rate` int(11) NOT NULL,
  `change_range` int(11) NOT NULL,
  PRIMARY KEY (`fish_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `auction_data` VALUES (1,60,80,130,10),(2,60,80,130,10),(3,60,80,130,10),(4,60,80,130,10),(5,60,80,130,20),(6,60,80,130,20),(7,60,70,130,20),(8,60,70,150,20),(9,60,70,150,20),(10,60,70,150,20),(11,60,70,150,30),(12,60,50,150,30),(13,60,50,150,30),(14,60,60,150,30),(15,40,60,150,30),(16,40,60,150,30),(17,40,60,150,40),(18,40,50,150,40),(19,40,50,150,40),(20,40,50,150,40),(21,40,50,170,40),(22,30,50,170,40),(23,30,50,170,40);