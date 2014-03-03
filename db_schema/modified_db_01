--
-- Database: `dev_neato`
--

-- --------------------------------------------------------

--
-- Table structure for table `alive_robot`
--

CREATE TABLE IF NOT EXISTS `alive_robot` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `serial_number` varchar(500) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE IF NOT EXISTS `roles` (
  `id` bigint(20) NOT NULL,
  `role_name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `role_name`) VALUES
(1, 'admin'),
(2, 'customer_support'),
(3, 'user');

-- --------------------------------------------------------

--
-- Table structure for table `user_role`
--

CREATE TABLE IF NOT EXISTS `user_role` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(255) NOT NULL,
  `user_role_id` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `user_role`
--

insert into user_role (user_id, user_role_id) select id, 3 from users
-------------------------------------------------------------------------

--
-- Update table `user_role`
--

UPDATE user_role ur inner join users u on ur.user_id = u.id SET ur.user_role_id = 1 WHERE u.email = 'admin@neatorobotics.com'

