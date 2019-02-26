# Host: localhost  (Version 5.5.5-10.1.36-MariaDB)
# Date: 2019-02-26 21:00:15
# Generator: MySQL-Front 6.1  (Build 1.24)


#
# Structure for table "audit_log"
#

DROP TABLE IF EXISTS `audit_log`;
CREATE TABLE `audit_log` (
  `changer` varchar(225) NOT NULL,
  `change_type` varchar(225) NOT NULL,
  `change_slachtoffer` varchar(225) NOT NULL,
  `old_rank_id` int(11) NOT NULL,
  `new_rank_id` int(11) NOT NULL,
  `reason` varchar(225) NOT NULL,
  `audit_id` int(10) NOT NULL AUTO_INCREMENT,
  `change_date` varchar(100) NOT NULL,
  UNIQUE KEY `audit_id` (`audit_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#
# Data for table "audit_log"
#


#
# Structure for table "gebruikers"
#

DROP TABLE IF EXISTS `gebruikers`;
CREATE TABLE `gebruikers` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `name` varchar(225) NOT NULL,
  `balance` int(225) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

#
# Data for table "gebruikers"
#


#
# Structure for table "ranks"
#

DROP TABLE IF EXISTS `ranks`;
CREATE TABLE `ranks` (
  `rank_id` int(10) NOT NULL,
  `rank_name` varchar(225) NOT NULL,
  `perm_id` int(11) NOT NULL,
  UNIQUE KEY `rank_id` (`rank_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

#
# Data for table "ranks"
#


#
# Structure for table "reports"
#

DROP TABLE IF EXISTS `reports`;
CREATE TABLE `reports` (
  `report_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

#
# Data for table "reports"
#


#
# Structure for table "user_ranks"
#

DROP TABLE IF EXISTS `user_ranks`;
CREATE TABLE `user_ranks` (
  `username` varchar(225) CHARACTER SET utf8 NOT NULL,
  `rank_id` int(11) NOT NULL,
  `node` varchar(2) NOT NULL,
  PRIMARY KEY (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

#
# Data for table "user_ranks"
#


#
# Structure for table "users"
#

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `user_id` int(202) NOT NULL AUTO_INCREMENT,
  `username` varchar(15) NOT NULL,
  `password` varchar(225) NOT NULL,
  `perm_id` int(5) NOT NULL,
  UNIQUE KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

#
# Data for table "users"
#


#
# Structure for table "warns"
#

DROP TABLE IF EXISTS `warns`;
CREATE TABLE `warns` (
  `warn_id` int(11) NOT NULL AUTO_INCREMENT,
  `waarschuwer` varchar(225) NOT NULL,
  `gewaarschuwde` varchar(225) NOT NULL,
  `reden` varchar(225) NOT NULL,
  `warn_type` varchar(225) NOT NULL,
  PRIMARY KEY (`warn_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

#
# Data for table "warns"
#

