--
-- Table structure for table `country_code_list`
--

DROP TABLE country_code_list;

CREATE TABLE IF NOT EXISTS `country_code_list` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `iso2` char(2) DEFAULT NULL,
  `short_name` varchar(80) NOT NULL DEFAULT '',
  `long_name` varchar(80) NOT NULL DEFAULT '',
  `iso3` char(3) DEFAULT NULL,
  `numcode` varchar(6) DEFAULT NULL,
  `un_member` varchar(12) DEFAULT NULL,
  `calling_code` varchar(8) DEFAULT NULL,
  `cctld` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `country_code_list`
--

INSERT INTO `country_code_list` (`iso2`, `short_name`, `long_name`, `iso3`, `numcode`, `un_member`, `calling_code`, `cctld`) VALUES
('AT', 'Austria', 'Republic of Austria', 'AUT', '040', 'yes', '43', '.at'),
('CN', 'China', 'People''s Republic of China', 'CHN', '156', 'yes', '86', '.cn'),
('CZ', 'Czech Republic', 'Czech Republic', 'CZE', '203', 'yes', '420', '.cz'),
('FR', 'France', 'French Republic', 'FRA', '250', 'yes', '33', '.fr'),
('IT', 'Italy', 'Italian Republic', 'ITA', '380', 'yes', '39', '.jm'),
('JP', 'Japan', 'Japan', 'JPN', '392', 'yes', '81', '.jp'),
('ES', 'Spain', 'Kingdom of Spain', 'ESP', '724', 'yes', '34', '.es'),
('CH', 'Switzerland', 'Swiss Confederation', 'CHE', '756', 'yes', '41', '.ch'),
('GB', 'United Kingdom', 'United Kingdom of Great Britain and Nothern Ireland', 'GBR', '826', 'yes', '44', '.uk'),
('US', 'United States', 'United States of America', 'USA', '840', 'yes', '1', '.us'),
('0', 'Other', 'Other', NULL, NULL, NULL, NULL, NULL);

