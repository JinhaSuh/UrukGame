DROP TABLE IF EXISTS `boat_data`;

CREATE TABLE `boat_data` (
  `boat_id` int(11) NOT NULL,
  `step` int(11) NOT NULL,
  `max_durability` int(11) NOT NULL,
  `max_fuel` int(11) NOT NULL,
  `reduce_durability_per_min` int(11) NOT NULL,
  `speed_meter_per_sec` int(11) NOT NULL,
  PRIMARY KEY (`boat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `boat_data` VALUES (1,1,300,50,1,1),(2,2,400,60,1,2),(3,3,500,80,1,3),(4,4,600,100,1,4),(5,5,900,170,1,5),(6,6,1200,200,1,6);