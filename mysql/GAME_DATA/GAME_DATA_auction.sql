DROP TABLE IF EXISTS `auction`;

CREATE TABLE `auction` (
  `auc_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `tank_id` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  PRIMARY KEY (`auc_id`)
) ENGINE=InnoDB AUTO_INCREMENT=65 DEFAULT CHARSET=utf8mb4;