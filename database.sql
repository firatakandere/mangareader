-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.5.34-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             8.0.0.4396
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping structure for table mangareader.mr_config
CREATE TABLE IF NOT EXISTS `mr_config` (
  `config_name` varchar(225) NOT NULL,
  `config_value` varchar(225) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table mangareader.mr_config: ~18 rows (approximately)
/*!40000 ALTER TABLE `mr_config` DISABLE KEYS */;
INSERT INTO `mr_config` (`config_name`, `config_value`) VALUES
	('session_span', '15'),
	('register_open', '1'),
	('site_title', 'Manga Reader'),
	('default_template', 'default'),
	('default_language', 'en_US'),
	('board_salt', 'm2Lk2mK'),
	('email_check_mx', '0'),
	('allow_emailreuse', '0'),
	('gzip_compress', '1'),
	('mod_rewrite', '0'),
	('domain', 'localhost'),
	('board_contact', 'f.akandere@gmail.com'),
	('min_username_chars', '6'),
	('max_username_chars', '12'),
	('min_password_chars', '3'),
	('max_password_chars', '999'),
	('activation_required', '0'),
	('board_timezone', '0.00');
/*!40000 ALTER TABLE `mr_config` ENABLE KEYS */;


-- Dumping structure for table mangareader.mr_groups
CREATE TABLE IF NOT EXISTS `mr_groups` (
  `group_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `group_name` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`group_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- Dumping data for table mangareader.mr_groups: ~5 rows (approximately)
/*!40000 ALTER TABLE `mr_groups` DISABLE KEYS */;
INSERT INTO `mr_groups` (`group_id`, `group_name`) VALUES
	(1, 'INACTIVE_USERS'),
	(2, 'REGISTERED_USERS'),
	(3, 'GUESTS'),
	(4, 'GLOBAL_MODERATORS'),
	(5, 'ADMINISTRATORS');
/*!40000 ALTER TABLE `mr_groups` ENABLE KEYS */;


-- Dumping structure for table mangareader.mr_group_perm
CREATE TABLE IF NOT EXISTS `mr_group_perm` (
  `group_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `perm_name` varchar(225) NOT NULL,
  `perm_type` tinyint(1) unsigned NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table mangareader.mr_group_perm: ~0 rows (approximately)
/*!40000 ALTER TABLE `mr_group_perm` DISABLE KEYS */;
/*!40000 ALTER TABLE `mr_group_perm` ENABLE KEYS */;


-- Dumping structure for table mangareader.mr_perms
CREATE TABLE IF NOT EXISTS `mr_perms` (
  `perm_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `perm_name` varchar(255) NOT NULL DEFAULT '',
  `perm_slug` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`perm_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table mangareader.mr_perms: ~0 rows (approximately)
/*!40000 ALTER TABLE `mr_perms` DISABLE KEYS */;
/*!40000 ALTER TABLE `mr_perms` ENABLE KEYS */;


-- Dumping structure for table mangareader.mr_sessions
CREATE TABLE IF NOT EXISTS `mr_sessions` (
  `session_id` varchar(32) NOT NULL,
  `session_user_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `session_fingerprint` varchar(64) NOT NULL DEFAULT '0',
  `session_start` int(11) unsigned NOT NULL DEFAULT '0',
  `session_last_visit` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table mangareader.mr_sessions: ~0 rows (approximately)
/*!40000 ALTER TABLE `mr_sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `mr_sessions` ENABLE KEYS */;


-- Dumping structure for table mangareader.mr_users
CREATE TABLE IF NOT EXISTS `mr_users` (
  `user_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `group_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(225) NOT NULL DEFAULT '',
  `username_clean` varchar(225) NOT NULL DEFAULT '',
  `user_password` varchar(40) NOT NULL DEFAULT '',
  `user_ip` varchar(40) NOT NULL DEFAULT '',
  `user_email` varchar(100) NOT NULL DEFAULT '',
  `user_email_hash` bigint(20) NOT NULL DEFAULT '0',
  `user_regdate` int(11) unsigned NOT NULL DEFAULT '0',
  `user_timezone` decimal(5,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Dumping data for table mangareader.mr_users: ~1 rows (approximately)
/*!40000 ALTER TABLE `mr_users` DISABLE KEYS */;
INSERT INTO `mr_users` (`user_id`, `group_id`, `username`, `username_clean`, `user_password`, `user_ip`, `user_email`, `user_email_hash`, `user_regdate`, `user_timezone`) VALUES
	(1, 3, 'Guest', '', '', '', '', 0, 0, 0.00);
/*!40000 ALTER TABLE `mr_users` ENABLE KEYS */;


-- Dumping structure for table mangareader.mr_user_perm
CREATE TABLE IF NOT EXISTS `mr_user_perm` (
  `user_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `perm_name` varchar(225) NOT NULL,
  `perm_type` tinyint(1) unsigned NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table mangareader.mr_user_perm: ~0 rows (approximately)
/*!40000 ALTER TABLE `mr_user_perm` DISABLE KEYS */;
/*!40000 ALTER TABLE `mr_user_perm` ENABLE KEYS */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
