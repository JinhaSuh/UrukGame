DROP TABLE IF EXISTS `wind_data`;

CREATE TABLE `wind_data` (
  `default_volume` int(11) NOT NULL,
  `min_volume` int(11) NOT NULL,
  `max_volume` int(11) NOT NULL,
  `iter_term` int(11) NOT NULL,
  `change_range` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `wind_data` VALUES (5,0,10,10,3);