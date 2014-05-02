--
-- Table structure for table `smtp_via_mq`
--

CREATE TABLE IF NOT EXISTS `smtp_via_mq` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `from` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `to` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `subject` text CHARACTER SET utf8,
  `body` longtext CHARACTER SET utf8,
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `created_on` datetime NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

