DROP TABLE IF EXISTS `water_tank`;

CREATE TABLE `water_tank` (
  `tank_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `fish_id` int(11) NOT NULL,
  `length` int(11) NOT NULL,
  `caught_time` datetime NOT NULL,
  PRIMARY KEY (`tank_id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4;