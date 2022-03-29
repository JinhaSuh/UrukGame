DROP TABLE IF EXISTS `temperature_data`;

CREATE TABLE `temperature_data` (
  `default_temperature` int(11) NOT NULL,
  `min_temperature` int(11) NOT NULL,
  `max_temperature` int(11) NOT NULL,
  `iter_term` int(11) NOT NULL,
  `change_range` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `temperature_data` VALUES (12,-10,35,10,5);