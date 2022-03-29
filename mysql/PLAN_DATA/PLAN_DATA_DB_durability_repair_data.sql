DROP TABLE IF EXISTS `durability_repair_data`;

CREATE TABLE `durability_repair_data` (
  `item_type_id` int(11) NOT NULL,
  `increase_durability` int(11) NOT NULL,
  `need_item_type_id` int(11) NOT NULL,
  `need_count` int(11) NOT NULL,
  PRIMARY KEY (`item_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `durability_repair_data` VALUES (4,10,1,1000),(7,5,1,200),(10,30,1,2000);