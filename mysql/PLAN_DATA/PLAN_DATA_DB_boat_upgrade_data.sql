DROP TABLE IF EXISTS `boat_upgrade_data`;

CREATE TABLE `boat_upgrade_data` (
  `step_id` int(11) NOT NULL,
  `success_rate` int(11) NOT NULL,
  `need_item_type_id` int(11) NOT NULL,
  `need_count` int(11) NOT NULL,
  `reduce_durability_case_failure` int(11) NOT NULL,
  PRIMARY KEY (`step_id`),
  KEY `needItem_idx` (`need_item_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `boat_upgrade_data` VALUES (1,100,1,4000,0),(2,95,1,6000,10),(3,85,1,10000,20),(4,80,1,20000,30),(5,60,1,40000,40),(6,50,2,1,50);