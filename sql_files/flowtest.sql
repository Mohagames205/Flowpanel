-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 12, 2019 at 06:56 PM
-- Server version: 10.1.36-MariaDB
-- PHP Version: 7.2.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `flowtest`
--

-- --------------------------------------------------------

--
-- Table structure for table `afdelingen`
--

CREATE TABLE `afdelingen` (
  `afdeling_id` int(2) NOT NULL,
  `afdeling_name` varchar(224) NOT NULL,
  `afdeling_afkorting` varchar(5) NOT NULL,
  `func` varchar(10) NOT NULL DEFAULT 'AD'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `afdelingen`
--

INSERT INTO `afdelingen` (`afdeling_id`, `afdeling_name`, `afdeling_afkorting`, `func`) VALUES
(1, 'Stagiair Education', 'OW', 'AD'),
(2, 'Observer Education', 'OW', 'AD'),
(3, 'Teacher Education', 'OW', 'AD'),
(4, 'Head of Teacher Education', 'OW', 'AD'),
(5, 'Head of Training Education', 'OW', 'AD'),
(6, 'Site Administrator Education', 'OW', 'AD'),
(7, 'Forum Administrator Education', 'OW', 'AD'),
(8, 'Teamleader Education', 'OW', 'LE'),
(9, 'A-Director Education', 'OW', 'LE'),
(10, 'Director Education', 'OW', 'LE'),
(11, 'Stagiair Recruitment', 'RC', 'AD'),
(12, 'Checker Recruitment', 'RC', 'AD'),
(13, 'Reviewer Recruitment', 'RC', 'AD'),
(14, 'Consultant Recruitment', 'RC', 'AD'),
(15, 'Coordinator Recruitment', 'RC', 'AD'),
(16, 'Representative Recruitment', 'RC', 'AD'),
(17, 'Teamleader Recruitment', 'RC', 'LE'),
(18, 'Director Recruitment', 'RC', 'LE'),
(19, 'Stagiair Promotions', 'PM', 'AD'),
(20, 'Observer Promotions', 'PM', 'AD'),
(21, 'Promotor Promotions', 'PM', 'AD'),
(22, 'Administrator Promotions', 'PM', 'AD'),
(23, 'Coordinator Promotions', 'PM', 'AD'),
(24, 'Teamleader Promotions', 'PM', 'LE'),
(25, 'A-Director Promotions', 'PM', 'LE'),
(26, 'Director Promotions', 'PM', 'LE');

-- --------------------------------------------------------

--
-- Table structure for table `audit_log`
--

CREATE TABLE `audit_log` (
  `changer` varchar(225) NOT NULL,
  `change_type` varchar(225) NOT NULL,
  `change_slachtoffer` varchar(225) NOT NULL,
  `old_rank_id` int(11) NOT NULL,
  `new_rank_id` int(11) NOT NULL,
  `reason` varchar(225) NOT NULL,
  `audit_id` int(10) NOT NULL,
  `change_date` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `audit_log`
--

INSERT INTO `audit_log` (`changer`, `change_type`, `change_slachtoffer`, `old_rank_id`, `new_rank_id`, `reason`, `audit_id`, `change_date`) VALUES
('Mohamed', 'Promotie', 'er', 0, 2, 'erg', 20, '09/04/2019'),
('Mohamed', 'Promotie', 'Mohamed', 0, 2, 'dd', 21, '10/04/2019'),
('Mohamed', 'Promotie', 'jan', 1, 2, 'ezf', 22, '10/04/2019'),
('Mohamed', 'Promotie', 'jan', 2, 3, 'grg', 23, '10/04/2019'),
('Mohamed', 'Promotie', 'jan', 3, 4, 'rr', 24, '10/04/2019'),
('Mohamed', 'Degradatie', 'jan', 4, 3, 'rr', 25, '10/04/2019'),
('Mohamed', 'Promotie', 'jan', 3, 4, 'rr', 26, '10/04/2019'),
('Mohamed', 'Promotie', 'jan', 4, 5, 'rr', 27, '10/04/2019'),
('Mohamed', 'Promotie', 'jan', 5, 6, 'rr', 28, '10/04/2019'),
('Mohamed', 'Promotie', 'jan', 6, 7, 'rr', 29, '10/04/2019'),
('Mohamed', 'Promotie', 'tim', 2, 3, 'z', 30, '10/04/2019'),
('Mohamed', 'Degradatie', 'tim', 3, 2, 'ff', 31, '11/04/2019'),
('Mohamed', 'Degradatie', 'tim', 2, 1, 'f', 32, '11/04/2019'),
('Mohamed', 'Ontslag', 'tim', 1, 0, 'rr', 33, '11/04/2019'),
('Mohamed', 'Promotie', 'tim', 0, 2, 'welkom', 34, '11/04/2019'),
('Mohamed', 'Ontslag', 'tim', -1, -1, 'ery', 35, '11/04/2019'),
('Mohamed', 'Promotie', 'tim', -1, 0, 'ree', 36, '11/04/2019'),
('Mohamed', 'Promotie', 'tim', 0, 1, 'reg', 37, '11/04/2019'),
('Mohamed', 'Ontslag', 'tim', 1, -1, 'er', 38, '11/04/2019'),
('Mohamed', 'Promotie', 'tim', -1, 0, 'def', 39, '11/04/2019'),
('Mohamed', 'Promotie', 'tim', 0, 1, 'ef', 40, '11/04/2019'),
('Mohamed', 'Promotie', 'jannetje', 0, 2, 'd', 41, '11/04/2019'),
('Mohamed', 'Promotie', 'fakka', 0, 2, 'fg', 42, '11/04/2019'),
('Mohamed', 'Ontslag', 'fakka', 1, -1, 'reh', 43, '11/04/2019'),
('Mohamed', 'Promotie', 'bfdh', 0, 2, 'dh', 44, '11/04/2019'),
('Mohamed', 'Custom', 'ezggh', 1, 7, 'ezg', 45, '12/04/2019'),
('Mohamed', 'Custom', 'ezggh', 7, 2, 'zet', 46, '12/04/2019'),
('Mohamed', 'Custom', 'jimmy', 1, 5, 'rer', 47, '12/04/2019'),
('Mohamed', 'Promotie', 'rip', 0, 2, 'erh', 48, '12/04/2019'),
('Mohamed', 'Promotie', 'ezrh', 0, 2, 'rh', 49, '12/04/2019');

-- --------------------------------------------------------

--
-- Table structure for table `ranks`
--

CREATE TABLE `ranks` (
  `rank_id` int(10) NOT NULL,
  `rank_name` varchar(225) NOT NULL,
  `perm_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ranks`
--

INSERT INTO `ranks` (`rank_id`, `rank_name`, `perm_id`) VALUES
(-1, 'Ontslagen', 0),
(0, 'Niet werkzaam', 0),
(1, 'ha', 1),
(2, 'hey', 1),
(3, 'top', 1),
(4, 'erger', 1),
(5, 'yrtj', 1),
(6, 'gfsdgsd', 1),
(7, 'dfg', 1),
(8, 'fbdfbd', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(202) NOT NULL,
  `username` varchar(15) NOT NULL,
  `password` varchar(225) NOT NULL,
  `perm_id` int(5) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `perm_id`) VALUES
(1, 'Mohamed', '$2y$10$dqrEau1V8KDTvb57tWqjlOawnSV92o4pmirAVhsYF/qnADx4644z6', 5),
(2, 'jean', '$2y$10$e4G/Ozi.JqYjuZCA0OKZq.L8ca3ndurbznA1w0XAXwOu8UYZPEQ5O', 5);

-- --------------------------------------------------------

--
-- Table structure for table `user_ranks`
--

CREATE TABLE `user_ranks` (
  `username` varchar(225) CHARACTER SET utf8 NOT NULL,
  `rank_id` int(11) NOT NULL,
  `node` varchar(2) NOT NULL,
  `afdeling_id` int(2) DEFAULT NULL,
  `afdeling_afkorting` varchar(10) DEFAULT NULL,
  `tag` varchar(3) DEFAULT NULL,
  `motto` varchar(224) NOT NULL DEFAULT 'FlowPanel <3',
  `extra` varchar(224) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_ranks`
--

INSERT INTO `user_ranks` (`username`, `rank_id`, `node`, `afdeling_id`, `afdeling_afkorting`, `tag`, `motto`, `extra`) VALUES
('bfdh', 1, 'B', NULL, NULL, NULL, 'FlowPanel <3', NULL),
('efzgds4', 2, 'B', NULL, NULL, NULL, 'FlowPanel <3', NULL),
('erhger', 0, 'B', NULL, NULL, NULL, 'FlowPanel <3', NULL),
('ezggh', 2, 'B', NULL, NULL, NULL, 'FlowPanel <3', NULL),
('ezrh', 1, 'B', 24, 'PM', NULL, 'FlowPanel <3', NULL),
('fakka', -1, 'B', NULL, NULL, NULL, 'FlowPanel <3', NULL),
('ff', 2, 'B', NULL, NULL, NULL, 'FlowPanel <3', NULL),
('hry', 4, 'B', NULL, NULL, NULL, 'FlowPanel <3', NULL),
('jan', 7, 'B', NULL, NULL, NULL, 'FlowPanel <3', NULL),
('jannetje', 1, 'B', NULL, NULL, NULL, 'FlowPanel <3', NULL),
('jean', 2, 'B', NULL, NULL, NULL, 'FlowPanel <3', NULL),
('jimmy', 5, 'B', NULL, NULL, NULL, 'FlowPanel <3', NULL),
('jow', 2, 'B', NULL, NULL, NULL, 'FlowPanel <3', NULL),
('Mohamed', 2, 'B', NULL, NULL, NULL, 'FlowPanel <3', NULL),
('rip', 1, 'B', 15, 'RC', NULL, 'FlowPanel <3', NULL),
('rthtr', 0, 'B', NULL, NULL, NULL, 'FlowPanel <3', NULL),
('thomas', 1, 'B', NULL, NULL, NULL, 'FlowPanel <3', NULL),
('tim', 1, 'B', NULL, NULL, NULL, 'FlowPanel <3', NULL),
('timmerman', 0, 'B', NULL, NULL, NULL, 'FlowPanel <3', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `warns`
--

CREATE TABLE `warns` (
  `warn_id` int(11) NOT NULL,
  `waarschuwer` varchar(225) NOT NULL,
  `gewaarschuwde` varchar(225) NOT NULL,
  `reden` varchar(225) NOT NULL,
  `warn_type` varchar(225) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `warns`
--

INSERT INTO `warns` (`warn_id`, `waarschuwer`, `gewaarschuwde`, `reden`, `warn_type`) VALUES
(1, 'Mohamed', 'bfdh', 'stout zijn', '3w');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `afdelingen`
--
ALTER TABLE `afdelingen`
  ADD PRIMARY KEY (`afdeling_id`);

--
-- Indexes for table `audit_log`
--
ALTER TABLE `audit_log`
  ADD UNIQUE KEY `audit_id` (`audit_id`);

--
-- Indexes for table `ranks`
--
ALTER TABLE `ranks`
  ADD UNIQUE KEY `rank_id` (`rank_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `user_ranks`
--
ALTER TABLE `user_ranks`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `warns`
--
ALTER TABLE `warns`
  ADD PRIMARY KEY (`warn_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `audit_log`
--
ALTER TABLE `audit_log`
  MODIFY `audit_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(202) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `warns`
--
ALTER TABLE `warns`
  MODIFY `warn_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
