DROP TABLE IF EXISTS `bait_data`;

CREATE TABLE `bait_data` (
  `bait_id` int(11) NOT NULL AUTO_INCREMENT,
  `bait_name` varchar(45) NOT NULL,
  `item_type_id` int(11) NOT NULL,
  `grade_id` int(11) NOT NULL,
  `buff_rare_fish_drop_rate` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  PRIMARY KEY (`bait_id`),
  KEY `baitGrade_idx` (`grade_id`),
  KEY `baitItem_idx` (`item_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;

INSERT INTO `bait_data` VALUES (1,'떡밥',6,0,10,3),(2,'건조 지렁이',6,1,20,10),(3,'미꾸라지',6,2,30,20),(4,'새우',6,2,70,100),(5,'바지락살',6,1,50,50);
