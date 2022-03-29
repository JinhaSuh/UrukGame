DROP TABLE IF EXISTS `map_data`;

CREATE TABLE `map_data` (
  `map_id` int(11) NOT NULL AUTO_INCREMENT,
  `map_name` varchar(20) NOT NULL,
  `max_water_depth` int(11) NOT NULL,
  `distance` int(11) NOT NULL,
  `level_limit` int(11) NOT NULL,
  `departure_cost` int(11) NOT NULL,
  `departure_time` int(11) NOT NULL,
  `reduce_fatigue_per_min` int(11) NOT NULL,
  `reduce_durability_per_meter` int(11) NOT NULL,
  PRIMARY KEY (`map_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

INSERT INTO `map_data` VALUES (1,'얕은 연안',40,20,0,500,20,2,5),(2,'깊은 연안',70,40,2,800,40,4,6),(3,'근거리 바다',60,40,4,800,40,5,5),(4,'먼거리 바다',100,70,7,1200,70,9,6);