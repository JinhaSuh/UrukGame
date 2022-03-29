DROP TABLE IF EXISTS `equip_upgrade_data`;

CREATE TABLE `equip_upgrade_data` (
  `item_type_id` int(11) NOT NULL,
  `grade_id` int(11) NOT NULL,
  `step` int(11) NOT NULL,
  `need_item_type_id` int(11) NOT NULL,
  `need_count` int(11) NOT NULL,
  PRIMARY KEY (`item_type_id`,`grade_id`,`step`),
  KEY `equipGrade_idx` (`grade_id`),
  KEY `needItem2_idx` (`need_item_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `equip_upgrade_data` VALUES (4,0,1,1,500),(4,0,2,1,700),(4,0,3,1,900),(4,1,1,1,500),(4,1,2,1,1500),(4,1,3,1,2000),(4,2,1,1,4000),(4,2,2,1,6000),(4,2,3,1,10000),(7,0,1,1,200),(7,0,2,1,300),(7,0,3,1,400),(7,1,1,1,500),(7,1,2,1,1000),(7,1,3,1,1300),(7,2,1,1,1500),(7,2,2,1,1800),(7,2,3,1,2000),(9,-1,1,1,800),(9,-1,2,1,2000),(9,-1,3,1,5000);