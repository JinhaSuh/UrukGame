DROP TABLE IF EXISTS `user_state`;

CREATE TABLE `user_state` (
  `user_id` int(11) NOT NULL,
  `state` int(11) NOT NULL,
  `depth` int(11) DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;