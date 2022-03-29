DROP TABLE IF EXISTS `account`;

CREATE TABLE `account` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `player_id` varchar(45) NOT NULL,
  `password` varchar(45) NOT NULL,
  `nation` varchar(45) DEFAULT NULL,
  `language` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`user_id`,`player_id`,`password`),
  UNIQUE KEY `user_id_UNIQUE` (`user_id`),
  UNIQUE KEY `player_id_UNIQUE` (`player_id`)
) ENGINE=InnoDB AUTO_INCREMENT=253 DEFAULT CHARSET=utf8mb4;