DROP TABLE IF EXISTS `hook_data`;

CREATE TABLE `hook_data` (
  `hook_id` int(11) NOT NULL AUTO_INCREMENT,
  `hook_name` varchar(45) NOT NULL,
  `item_type_id` int(11) NOT NULL,
  `number` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `buff_suppress_rate` int(11) NOT NULL,
  `buff_fish_drop_rate` int(11) NOT NULL,
  PRIMARY KEY (`hook_id`),
  KEY `hookItem_idx` (`item_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4;

INSERT INTO `hook_data` VALUES (1,'기본 낚시 바늘',8,1,400,65,99),(2,'낚시 바늘',8,1,400,68,98),(3,'낚시 바늘',8,1,400,75,97),(4,'낚시 바늘',8,2,1200,80,95),(5,'낚시 바늘',8,2,1200,85,93),(6,'낚시 바늘',8,2,1200,88,91),(7,'낚시 바늘',8,3,2000,92,90),(8,'낚시 바늘',8,3,2000,94,88),(9,'낚시 바늘',8,3,2000,98,84),(10,'더블 훅',8,3,1500,90,90);
