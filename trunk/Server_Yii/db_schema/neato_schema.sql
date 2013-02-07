-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 07, 2013 at 02:14 PM
-- Server version: 5.5.16
-- PHP Version: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `neato`
--
CREATE DATABASE `neato` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `neato`;

-- --------------------------------------------------------

--
-- Table structure for table `api_users`
--

CREATE TABLE IF NOT EXISTS `api_users` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_site` bigint(20) NOT NULL,
  `api_key` varchar(100) NOT NULL,
  `secret_key` varchar(100) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `id_site` (`id_site`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `api_users`
--

INSERT INTO `api_users` (`id`, `id_site`, `api_key`, `secret_key`, `active`) VALUES
(2, 1, '1e26686d806d82144a71ea9a99d1b3169adaad917', '1e26686d806d82144a71ea9a99d1b3169adaad917', 1);

-- --------------------------------------------------------

--
-- Table structure for table `app_info`
--

CREATE TABLE IF NOT EXISTS `app_info` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `app_id` bigint(20) NOT NULL,
  `current_app_version` varchar(200) CHARACTER SET utf8 NOT NULL,
  `os_version` varchar(500) CHARACTER SET utf8 NOT NULL,
  `os_type` varchar(500) CHARACTER SET utf8 NOT NULL,
  `latest_version` varchar(500) CHARACTER SET utf8 NOT NULL,
  `latest_version_url` varchar(500) CHARACTER SET utf8 NOT NULL,
  `upgrade_status` int(11) NOT NULL COMMENT '0 = no change, 1 = optional upgrade available, 2 = mandatory upgrade necessary, 3 = app has to be deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `atlas_grid_image`
--

CREATE TABLE IF NOT EXISTS `atlas_grid_image` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_atlas` bigint(20) NOT NULL,
  `id_grid` varchar(20) NOT NULL,
  `blob_data_file_name` varchar(100) NOT NULL,
  `version` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_atlas` (`id_atlas`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `device_details`
--

CREATE TABLE IF NOT EXISTS `device_details` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '',
  `operating_system` varchar(20) NOT NULL DEFAULT '',
  `version` varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `robot_atlas`
--

CREATE TABLE IF NOT EXISTS `robot_atlas` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_robot` bigint(20) NOT NULL,
  `xml_data_file_name` varchar(100) NOT NULL,
  `version` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_robot` (`id_robot`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `robot_custom_data`
--

CREATE TABLE IF NOT EXISTS `robot_custom_data` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_robot_custom` bigint(20) NOT NULL,
  `id_robot_custom_data_type` bigint(20) NOT NULL,
  `file_name` varchar(100) NOT NULL,
  `version` bigint(20) NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_robot_custom` (`id_robot_custom`,`id_robot_custom_data_type`),
  KEY `id_robot_custom_data_type` (`id_robot_custom_data_type`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `robot_custom_data_types`
--

CREATE TABLE IF NOT EXISTS `robot_custom_data_types` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `robot_customs`
--

CREATE TABLE IF NOT EXISTS `robot_customs` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_robot` bigint(20) NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_robot` (`id_robot`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `robot_map_blob_data_versions`
--

CREATE TABLE IF NOT EXISTS `robot_map_blob_data_versions` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_robot_map` bigint(20) NOT NULL,
  `version` bigint(20) NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_robot_map` (`id_robot_map`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `robot_map_xml_data_versions`
--

CREATE TABLE IF NOT EXISTS `robot_map_xml_data_versions` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_robot_map` bigint(20) NOT NULL,
  `version` bigint(20) NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_robot_map` (`id_robot_map`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `robot_maps`
--

CREATE TABLE IF NOT EXISTS `robot_maps` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_robot` bigint(20) NOT NULL,
  `xml_data_file_name` varchar(100) NOT NULL,
  `blob_data_file_name` varchar(100) NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `id_robot` (`id_robot`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `robot_schedule_blob_data_versions`
--

CREATE TABLE IF NOT EXISTS `robot_schedule_blob_data_versions` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_robot_schedule` bigint(20) NOT NULL,
  `version` bigint(20) NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_robot_schedule` (`id_robot_schedule`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `robot_schedule_xml_data_versions`
--

CREATE TABLE IF NOT EXISTS `robot_schedule_xml_data_versions` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_robot_schedule` bigint(20) NOT NULL,
  `version` bigint(20) NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_robot_schedule` (`id_robot_schedule`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `robot_schedules`
--

CREATE TABLE IF NOT EXISTS `robot_schedules` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_robot` bigint(20) NOT NULL,
  `type` varchar(20) NOT NULL DEFAULT 'basic' COMMENT 'basic or advanced',
  `xml_data_file_name` varchar(100) NOT NULL,
  `blob_data_file_name` varchar(100) NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `id_robot` (`id_robot`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `robots`
--

CREATE TABLE IF NOT EXISTS `robots` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8 NOT NULL,
  `serial_number` varchar(100) CHARACTER SET utf8 NOT NULL,
  `chat_id` varchar(100) CHARACTER SET utf8 NOT NULL,
  `chat_pwd` varchar(100) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `serial_number` (`serial_number`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `sites`
--

CREATE TABLE IF NOT EXISTS `sites` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `description` text NOT NULL,
  `url` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `sites`
--

INSERT INTO `sites` (`id`, `name`, `description`, `url`) VALUES
(1, 'neato local', 'neato local', 'http://localhost/Neato_Server/Server_Yii/Neato/');

-- --------------------------------------------------------

--
-- Table structure for table `socialservicetypes`
--

CREATE TABLE IF NOT EXISTS `socialservicetypes` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) CHARACTER SET utf8 NOT NULL,
  `consumer_key` varchar(250) CHARACTER SET utf8 NOT NULL,
  `secret_key` varchar(250) CHARACTER SET utf8 NOT NULL,
  `username` varchar(250) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `socialservicetypes`
--

INSERT INTO `socialservicetypes` (`id`, `name`, `consumer_key`, `secret_key`, `username`) VALUES
(1, 'Facebook', '518570994821484', 'afe4709f35091e54aa2faed6cc9c3fba', 'Neato Yii localhost');

-- --------------------------------------------------------

--
-- Table structure for table `upgrade_status`
--

CREATE TABLE IF NOT EXISTS `upgrade_status` (
  `upgrade_status_key` int(11) NOT NULL,
  `upgrade_status_value` varchar(100) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------
INSERT INTO `upgrade_status` (`upgrade_status_key`, `upgrade_status_value`) VALUES
(0, 'no change'),
(1, 'optional upgrade available'),
(2, 'mandatory upgrade necessary'),
(3, 'app has to be deleted');
--
-- Table structure for table `user_devices`
--

CREATE TABLE IF NOT EXISTS `user_devices` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `id_user` bigint(20) NOT NULL,
  `id_device_details` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`),
  KEY `id_device_details` (`id_device_details`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) CHARACTER SET utf8 NOT NULL,
  `password` varchar(128) CHARACTER SET utf8 NOT NULL,
  `reset_password` varchar(128) CHARACTER SET utf8 NOT NULL,
  `email` varchar(128) CHARACTER SET utf8 NOT NULL,
  `is_emailVerified` tinyint(1) NOT NULL DEFAULT '0',
  `is_admin` tinyint(1) NOT NULL DEFAULT '0',
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `chat_id` varchar(128) CHARACTER SET utf8 NOT NULL,
  `chat_pwd` varchar(128) CHARACTER SET utf8 NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `password`, `reset_password`, `email`, `is_emailVerified`, `is_admin`, `created_on`, `chat_id`, `chat_pwd`, `is_active`) VALUES
(1, 'admin', '=UlVadFVYh2USxGZ2RFbSVFZEZlVUxmQXJFbwlnUtFzUZZlSZZlM0gnVGFUP', '=UlVKdFVYZ1aiZkWy90Vxc1UFp1caZlWhJmRkhmVrpFViJDazZVMJhnVGFUP', 'admin@neatorobotics.com', 1, 1, '2013-02-07 12:17:42', '1352014076_user@rajatogo', '1352014076_user', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users_api_sessions`
--

CREATE TABLE IF NOT EXISTS `users_api_sessions` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_user` bigint(20) NOT NULL,
  `id_site` bigint(20) NOT NULL,
  `token` varchar(100) NOT NULL,
  `expires` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`,`id_site`),
  KEY `id_site_me` (`id_site`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `users_robots`
--

CREATE TABLE IF NOT EXISTS `users_robots` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_user` bigint(20) NOT NULL,
  `id_robot` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`),
  KEY `id_robot` (`id_robot`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `users_socialservices`
--

CREATE TABLE IF NOT EXISTS `users_socialservices` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_socialservicetype` bigint(20) NOT NULL,
  `id_user` bigint(20) NOT NULL,
  `user_social_id` varchar(250) CHARACTER SET utf8 NOT NULL,
  `username` varchar(128) CHARACTER SET utf8 NOT NULL,
  `access_token` varchar(500) CHARACTER SET utf8 NOT NULL,
  `expires_on` date NOT NULL,
  `raw_data` text CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`),
  KEY `id_socialservicetype` (`id_socialservicetype`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `ws_logging`
--

CREATE TABLE IF NOT EXISTS `ws_logging` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_site` bigint(20) NOT NULL,
  `remote_address` varchar(100) NOT NULL,
  `method_name` varchar(100) NOT NULL,
  `api_key` varchar(100) NOT NULL,
  `response_type` varchar(30) NOT NULL,
  `handler_name` varchar(30) NOT NULL,
  `request_type` varchar(30) NOT NULL DEFAULT 'post',
  `request_data` text NOT NULL,
  `response_data` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `date_and_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_site` (`id_site`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `api_users`
--
ALTER TABLE `api_users`
  ADD CONSTRAINT `api_users_ibfk_1` FOREIGN KEY (`id_site`) REFERENCES `sites` (`id`);

--
-- Constraints for table `atlas_grid_image`
--
ALTER TABLE `atlas_grid_image`
  ADD CONSTRAINT `atlas_grid_image_ibfk_1` FOREIGN KEY (`id_atlas`) REFERENCES `robot_atlas` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `robot_atlas`
--
ALTER TABLE `robot_atlas`
  ADD CONSTRAINT `robot_atlas_ibfk_1` FOREIGN KEY (`id_robot`) REFERENCES `robots` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `robot_custom_data`
--
ALTER TABLE `robot_custom_data`
  ADD CONSTRAINT `@0020robot_custom_data_ibfk_1` FOREIGN KEY (`id_robot_custom`) REFERENCES `robot_customs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `@0020robot_custom_data_ibfk_2` FOREIGN KEY (`id_robot_custom_data_type`) REFERENCES `robot_custom_data_types` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `robot_customs`
--
ALTER TABLE `robot_customs`
  ADD CONSTRAINT `robot_customs_ibfk_1` FOREIGN KEY (`id_robot`) REFERENCES `robots` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `robot_map_blob_data_versions`
--
ALTER TABLE `robot_map_blob_data_versions`
  ADD CONSTRAINT `robot_map_blob_data_versions_ibfk_1` FOREIGN KEY (`id_robot_map`) REFERENCES `robot_maps` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `robot_map_xml_data_versions`
--
ALTER TABLE `robot_map_xml_data_versions`
  ADD CONSTRAINT `robot_map_xml_data_versions_ibfk_1` FOREIGN KEY (`id_robot_map`) REFERENCES `robot_maps` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `robot_maps`
--
ALTER TABLE `robot_maps`
  ADD CONSTRAINT `robot_maps_ibfk_1` FOREIGN KEY (`id_robot`) REFERENCES `robots` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `robot_schedule_blob_data_versions`
--
ALTER TABLE `robot_schedule_blob_data_versions`
  ADD CONSTRAINT `robot_schedule_blob_data_versions_ibfk_1` FOREIGN KEY (`id_robot_schedule`) REFERENCES `robot_schedules` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `robot_schedule_xml_data_versions`
--
ALTER TABLE `robot_schedule_xml_data_versions`
  ADD CONSTRAINT `robot_schedule_xml_data_versions_ibfk_1` FOREIGN KEY (`id_robot_schedule`) REFERENCES `robot_schedules` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `robot_schedules`
--
ALTER TABLE `robot_schedules`
  ADD CONSTRAINT `robot_schedules_ibfk_1` FOREIGN KEY (`id_robot`) REFERENCES `robots` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users_api_sessions`
--
ALTER TABLE `users_api_sessions`
  ADD CONSTRAINT `users_api_sessions_ibfk_3` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `users_api_sessions_ibfk_4` FOREIGN KEY (`id_site`) REFERENCES `sites` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users_robots`
--
ALTER TABLE `users_robots`
  ADD CONSTRAINT `users_robots_ibfk_3` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `users_robots_ibfk_4` FOREIGN KEY (`id_robot`) REFERENCES `robots` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users_socialservices`
--
ALTER TABLE `users_socialservices`
  ADD CONSTRAINT `users_socialservices_ibfk_3` FOREIGN KEY (`id_socialservicetype`) REFERENCES `socialservicetypes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `users_socialservices_ibfk_4` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `ws_logging`
--
ALTER TABLE `ws_logging`
  ADD CONSTRAINT `ws_logging_ibfk_1` FOREIGN KEY (`id_site`) REFERENCES `sites` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
