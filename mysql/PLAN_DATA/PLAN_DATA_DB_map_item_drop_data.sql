DROP TABLE IF EXISTS `map_item_drop_data`;

CREATE TABLE `map_item_drop_data` (
  `map_id` int(11) NOT NULL,
  `item_type_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `item_count` int(11) NOT NULL,
  PRIMARY KEY (`item_type_id`,`item_id`),
  KEY `dropItem_idx` (`item_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `map_item_drop_data` VALUES (1,4,100,1),(1,5,1,1),(1,5,2,1),(1,5,3,1),(1,6,4,1),(1,6,5,1);