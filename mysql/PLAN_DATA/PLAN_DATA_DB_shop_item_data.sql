DROP TABLE IF EXISTS `shop_item_data`;

CREATE TABLE `shop_item_data` (
  `item_type_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `item_count` int(11) NOT NULL,
  `need_item_type_id` int(11) NOT NULL,
  `need_count` int(11) NOT NULL,
  PRIMARY KEY (`item_type_id`,`item_id`),
  KEY `shopItem_idx` (`item_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `shop_item_data` VALUES (4,4,1,1,1000),(4,7,1,1,5000),(4,200,1,1,10000),(5,1,1,1,50),(5,2,1,1,100),(5,3,1,1,200),(6,1,1,1,10),(6,2,1,1,50),(6,3,1,1,100),(7,5,1,1,3000),(7,10,1,1,5000),(8,6,1,1,3000),(8,10,1,1,5000),(9,4,1,1,4000);