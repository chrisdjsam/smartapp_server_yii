ALTER TABLE `ws_logging`  ADD `serial_number` VARCHAR(512) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,  ADD `email` VARCHAR(512) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,  ADD `api_request` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;

ALTER TABLE `ws_logging`  ADD `start_time` VARCHAR(512) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Time milliseconds',  ADD `end_time` VARCHAR(512) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Time milliseconds';

ALTER TABLE `ws_logging`  ADD `internal_process_values` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;

ALTER TABLE `xmpp_notification_via_mq`  ADD `status` TINYINT UNSIGNED NOT NULL DEFAULT '0';

ALTER TABLE `xmpp_notification_via_mq`  ADD `start_time` VARCHAR(512) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Time milliseconds',  ADD `end_time` VARCHAR(512) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Time milliseconds';

ALTER TABLE `smtp_via_mq`  ADD `start_time` VARCHAR(512) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Time milliseconds',  ADD `end_time` VARCHAR(512) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Time milliseconds';

ALTER TABLE `smtp_via_mq` DROP `created_on`;

ALTER TABLE `ws_logging` DROP FOREIGN KEY `ws_logging_ibfk_1` ;

ALTER TABLE ws_logging DROP INDEX id_site;

ALTER TABLE `ws_logging` DROP `id_site`, DROP `api_key`, DROP `response_type`, DROP `handler_name`, DROP `request_type`;

ALTER TABLE `ws_logging` ADD `source` TINYINT UNSIGNED NULL DEFAULT NULL;