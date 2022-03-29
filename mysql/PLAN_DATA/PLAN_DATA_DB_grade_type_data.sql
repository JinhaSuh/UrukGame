DROP TABLE IF EXISTS `grade_type_data`;

CREATE TABLE `grade_type_data` (
  `grade_id` int(11) NOT NULL AUTO_INCREMENT,
  `grade_name` varchar(10) NOT NULL,
  PRIMARY KEY (`grade_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;

INSERT INTO `grade_type_data` VALUES (-1,'기타'),(1,'희귀'),(2,'전설'),(6,'일반');