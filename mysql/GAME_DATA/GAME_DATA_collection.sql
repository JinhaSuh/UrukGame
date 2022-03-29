DROP TABLE IF EXISTS `collection`;

CREATE TABLE `collection` (
  `user_id` int(11) NOT NULL,
  `fish_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`,`fish_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;