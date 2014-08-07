ALTER TABLE `robot_customs` DROP FOREIGN KEY `robot_customs_ibfk_1` ;

ALTER TABLE `robot_custom_data` DROP FOREIGN KEY `@0020robot_custom_data_ibfk_1` ;

ALTER TABLE `robot_custom_data` DROP FOREIGN KEY `@0020robot_custom_data_ibfk_2` ;

DROP TABLE `robot_customs`, `robot_custom_data`, `robot_custom_data_types`;
