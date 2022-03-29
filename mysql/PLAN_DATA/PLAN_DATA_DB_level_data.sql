DROP TABLE IF EXISTS `level_data`;

CREATE TABLE `level_data` (
  `level` int(11) NOT NULL AUTO_INCREMENT,
  `need_exp` int(11) NOT NULL,
  `max_fatigue` int(11) NOT NULL,
  `buff_auction_sell_rate` int(11) NOT NULL,
  PRIMARY KEY (`level`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4;

INSERT INTO `level_data` VALUES (1,5,10,100),(2,10,12,102),(3,15,15,105),(4,20,20,108),(5,25,25,110),(6,30,30,114),(7,35,35,116),(8,40,40,118),(9,45,45,120),(10,50,50,125);