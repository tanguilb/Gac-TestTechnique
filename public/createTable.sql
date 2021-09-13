-- gac_test_technique.call_data definition

DROP TABLE IF EXISTS `call_data`;

CREATE TABLE IF NOT EXISTS `call_data` (
  `id` int NOT NULL AUTO_INCREMENT,
  `billed_account_id` int NOT NULL,
  `bill_id` int NOT NULL,
  `user_id` int NOT NULL,
  `call_date` date NOT NULL,
  `call_time` time NOT NULL,
  `real_duration` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `billed_duration` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `call_type` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ix_call_date` (`call_date`),
  KEY `ix_call_type` (`call_type`),
  KEY `ix_call_time` (`call_time`)
) ENGINE=InnoDB AUTO_INCREMENT=100706 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
