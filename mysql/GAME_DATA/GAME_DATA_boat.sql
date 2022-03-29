DROP TABLE IF EXISTS `boat`;

CREATE TABLE `boat` (
  `user_id` int(11) NOT NULL,
  `boat_id` int(11) NOT NULL,
  `durability` int(11) NOT NULL,
  `fuel` int(11) NOT NULL,
  `departure_time` datetime NOT NULL,
  `map_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_id_UNIQUE` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;