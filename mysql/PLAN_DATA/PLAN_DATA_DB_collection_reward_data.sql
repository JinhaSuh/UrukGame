DROP TABLE IF EXISTS `collection_reward_data`;

CREATE TABLE `collection_reward_data` (
  `filled_count` int(11) NOT NULL,
  `reward_item_type_id` int(11) NOT NULL,
  `reward_item_count` int(11) NOT NULL,
  KEY `rewardItem_idx` (`reward_item_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `collection_reward_data` VALUES (5,1,5000),(10,1,10000),(20,2,5),(25,2,10);