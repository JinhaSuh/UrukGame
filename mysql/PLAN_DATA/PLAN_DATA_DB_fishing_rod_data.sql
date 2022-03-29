DROP TABLE IF EXISTS `fishing_rod_data`;

CREATE TABLE `fishing_rod_data` (
  `fishing_rod_id` int(11) NOT NULL AUTO_INCREMENT,
  `fishing_rod_name` varchar(45) NOT NULL,
  `item_type_id` int(11) NOT NULL,
  `grade_id` int(11) NOT NULL,
  `step` int(11) NOT NULL,
  `hardness` int(11) NOT NULL,
  `price` int(11) DEFAULT NULL,
  `buff_suppress_rate` int(11) NOT NULL,
  `buff_hooking_rate` int(11) NOT NULL,
  `max_durability` int(11) NOT NULL,
  PRIMARY KEY (`fishing_rod_id`),
  KEY `gradeId_idx` (`grade_id`),
  KEY `fishingRodItem_idx` (`item_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=201 DEFAULT CHARSET=utf8mb4;

INSERT INTO `fishing_rod_data` VALUES (1,'일반 낚싯대',4,0,0,3,400,65,80,40),(2,'일반 낚싯대',4,0,1,5,500,68,70,45),(3,'일반 낚싯대',4,0,2,6,600,75,50,50),(4,'희귀 낚싯대',4,1,0,10,2000,80,70,70),(5,'희귀 낚싯대',4,1,1,14,2500,85,60,80),(6,'희귀 낚싯대',4,1,2,17,3000,88,40,100),(7,'전설 낚싯대',4,2,0,20,10000,92,60,150),(8,'전설 낚싯대',4,2,1,23,12000,94,40,180),(9,'전설 낚싯대',4,2,2,30,14000,98,30,200),(100,'누군가가 빠뜨린 낚싯대',4,0,100,10,200,75,40,10),(200,'개발자의 낚싯대',4,2,100,100,3333,100,30,1);