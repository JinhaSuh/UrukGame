DROP TABLE IF EXISTS `tide_data`;

CREATE TABLE `tide_data` (
  `default_tide` int(11) NOT NULL,
  `min_tide` int(11) NOT NULL,
  `max_tide` int(11) NOT NULL,
  `iter_term` int(11) NOT NULL,
  `splash_time` int(11) NOT NULL,
  `change_range` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `tide_data` VALUES (5,3,11,20,2,2);