DROP TABLE IF EXISTS `weather`;

CREATE TABLE `weather` (
  `user_id` int(11) NOT NULL,
  `wind_volume` int(11) NOT NULL,
  `temperature` int(11) NOT NULL,
  `tide` int(11) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;