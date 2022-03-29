DROP TABLE IF EXISTS `ranking`;

CREATE TABLE `ranking` (
  `user_id` int(11) NOT NULL,
  `gold_sum` int(11) DEFAULT NULL,
  `week_date` datetime DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;