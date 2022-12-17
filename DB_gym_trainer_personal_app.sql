-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 25, 2021 at 04:13 PM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 7.4.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gym_trainer_personal_app`
--

-- --------------------------------------------------------

--
-- Table structure for table `achievements`
--

CREATE TABLE `achievements` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `type` varchar(30) NOT NULL,
  `remark` double NOT NULL,
  `attained_date` date NOT NULL,
  `trainer_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE `clients` (
  `id` int(11) NOT NULL,
  `name` varchar(60) NOT NULL,
  `dob` date DEFAULT NULL,
  `profile_picture` text DEFAULT NULL,
  `phone` varchar(30) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `pwd` text NOT NULL,
  `acc_creation_date` date DEFAULT NULL,
  `acc_status` varchar(30) NOT NULL DEFAULT 'pending',
  `trainer_id` int(11) NOT NULL,
  `gym_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`id`, `name`, `dob`, `profile_picture`, `phone`, `email`, `pwd`, `acc_creation_date`, `acc_status`, `trainer_id`, `gym_id`) VALUES
(100, 'none', '2021-08-06', NULL, '01611223344', NULL, '123456', '2021-08-06', 'pending', 3959, 1);

-- --------------------------------------------------------

--
-- Stand-in structure for view `clients_view`
-- (See below for the actual view)
--
CREATE TABLE `clients_view` (
`id` int(11)
,`name` varchar(60)
,`dob` date
,`profile_picture` text
,`phone` varchar(30)
,`email` varchar(150)
,`pwd` text
,`acc_creation_date` date
,`acc_status` varchar(30)
,`trainer_id` int(11)
,`gym_id` int(11)
,`gym_name` varchar(255)
);

-- --------------------------------------------------------

--
-- Table structure for table `client_body_measurement`
--

CREATE TABLE `client_body_measurement` (
  `initial_date` date DEFAULT NULL,
  `updated_date` date DEFAULT NULL,
  `initial_measurement` text NOT NULL,
  `updated_measurement` text DEFAULT NULL,
  `client_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `client_health_issue`
--

CREATE TABLE `client_health_issue` (
  `issue` text DEFAULT NULL,
  `client_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `diets`
--

CREATE TABLE `diets` (
  `id` bigint(20) NOT NULL,
  `title` varchar(255) NOT NULL,
  `breakfast` text NOT NULL,
  `lunch` text NOT NULL,
  `dinner` text NOT NULL,
  `d_status` varchar(20) DEFAULT 'active',
  `client_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Stand-in structure for view `diets_view`
-- (See below for the actual view)
--
CREATE TABLE `diets_view` (
`id` bigint(20)
,`title` varchar(255)
,`breakfast` text
,`lunch` text
,`dinner` text
,`d_status` varchar(20)
,`client_id` int(11)
,`client_name` varchar(60)
);

-- --------------------------------------------------------

--
-- Table structure for table `gyms`
--

CREATE TABLE `gyms` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `location` text DEFAULT NULL,
  `salary` double DEFAULT NULL,
  `joined_date` date NOT NULL,
  `retire_date` date DEFAULT NULL,
  `trainer_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `gyms`
--

INSERT INTO `gyms` (`id`, `name`, `location`, `salary`, `joined_date`, `retire_date`, `trainer_id`) VALUES
(1, 'My Gym', NULL, 15000, '2021-07-30', NULL, 3959);

-- --------------------------------------------------------

--
-- Table structure for table `ratings`
--

CREATE TABLE `ratings` (
  `id` bigint(20) NOT NULL,
  `star` int(2) NOT NULL,
  `remark` varchar(255) NOT NULL,
  `r_status` varchar(30) DEFAULT 'pending',
  `date_posted` date DEFAULT NULL,
  `trainer_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `site_setting`
--

CREATE TABLE `site_setting` (
  `title` varchar(20) NOT NULL,
  `icon` text NOT NULL,
  `banner` text NOT NULL,
  `trainer_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `site_setting`
--

INSERT INTO `site_setting` (`title`, `icon`, `banner`, `trainer_id`) VALUES
('FitNess App', 'r1668_dt20210806161508_dumbbells_64.png', 'r9404_dt20210806161515_hero-bg_jpg', 3959);

-- --------------------------------------------------------

--
-- Table structure for table `social_media`
--

CREATE TABLE `social_media` (
  `id` int(11) NOT NULL,
  `platform` varchar(30) NOT NULL,
  `link` text NOT NULL,
  `icon` varchar(100) NOT NULL,
  `trainer_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `trainer`
--

CREATE TABLE `trainer` (
  `id` int(11) NOT NULL,
  `name` varchar(60) NOT NULL,
  `dob` date DEFAULT NULL,
  `profile_picture` text NOT NULL,
  `education` varchar(255) DEFAULT NULL,
  `about` text DEFAULT NULL,
  `debut_date` date NOT NULL,
  `acc_creation_date` date DEFAULT NULL,
  `phone` varchar(30) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `pwd` text NOT NULL,
  `acc_status` varchar(30) DEFAULT 'locked'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `trainer`
--

INSERT INTO `trainer` (`id`, `name`, `dob`, `profile_picture`, `education`, `about`, `debut_date`, `acc_creation_date`, `phone`, `email`, `pwd`, `acc_status`) VALUES
(3959, 'Helas Brown', NULL, 'helasbrown_r7125_dt20210806191124_p2.png', NULL, NULL, '2021-01-01', '2021-06-20', '01711223344', NULL, '10470c3b4b1fed12c3baac014be15fac67c6e815', 'unlocked');

-- --------------------------------------------------------

--
-- Table structure for table `workouts`
--

CREATE TABLE `workouts` (
  `id` bigint(20) NOT NULL,
  `title` varchar(250) NOT NULL,
  `start_date` date DEFAULT NULL,
  `interval_days` varchar(30) NOT NULL,
  `end_date` date NOT NULL,
  `last_updated` date DEFAULT NULL,
  `w_status` varchar(30) DEFAULT 'active',
  `name` mediumtext NOT NULL,
  `days` mediumtext NOT NULL,
  `reps` mediumtext DEFAULT NULL,
  `sets` mediumtext DEFAULT NULL,
  `note` longtext DEFAULT NULL,
  `client_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Stand-in structure for view `workouts_view`
-- (See below for the actual view)
--
CREATE TABLE `workouts_view` (
`id` bigint(20)
,`title` varchar(250)
,`start_date` date
,`interval_days` varchar(30)
,`end_date` date
,`last_updated` date
,`w_status` varchar(30)
,`name` mediumtext
,`days` mediumtext
,`reps` mediumtext
,`sets` mediumtext
,`note` longtext
,`client_id` int(11)
,`client_name` varchar(60)
);

-- --------------------------------------------------------

--
-- Structure for view `clients_view`
--
DROP TABLE IF EXISTS `clients_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `clients_view`  AS SELECT `c`.`id` AS `id`, `c`.`name` AS `name`, `c`.`dob` AS `dob`, `c`.`profile_picture` AS `profile_picture`, `c`.`phone` AS `phone`, `c`.`email` AS `email`, `c`.`pwd` AS `pwd`, `c`.`acc_creation_date` AS `acc_creation_date`, `c`.`acc_status` AS `acc_status`, `c`.`trainer_id` AS `trainer_id`, `c`.`gym_id` AS `gym_id`, `g`.`name` AS `gym_name` FROM (`clients` `c` join `gyms` `g`) WHERE `c`.`gym_id` = `g`.`id` ;

-- --------------------------------------------------------

--
-- Structure for view `diets_view`
--
DROP TABLE IF EXISTS `diets_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `diets_view`  AS SELECT `d`.`id` AS `id`, `d`.`title` AS `title`, `d`.`breakfast` AS `breakfast`, `d`.`lunch` AS `lunch`, `d`.`dinner` AS `dinner`, `d`.`d_status` AS `d_status`, `d`.`client_id` AS `client_id`, `c`.`name` AS `client_name` FROM (`diets` `d` join `clients` `c`) WHERE `d`.`client_id` = `c`.`id` ;

-- --------------------------------------------------------

--
-- Structure for view `workouts_view`
--
DROP TABLE IF EXISTS `workouts_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `workouts_view`  AS SELECT `w`.`id` AS `id`, `w`.`title` AS `title`, `w`.`start_date` AS `start_date`, `w`.`interval_days` AS `interval_days`, `w`.`end_date` AS `end_date`, `w`.`last_updated` AS `last_updated`, `w`.`w_status` AS `w_status`, `w`.`name` AS `name`, `w`.`days` AS `days`, `w`.`reps` AS `reps`, `w`.`sets` AS `sets`, `w`.`note` AS `note`, `w`.`client_id` AS `client_id`, `c`.`name` AS `client_name` FROM (`workouts` `w` join `clients` `c`) WHERE `w`.`client_id` = `c`.`id` ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `achievements`
--
ALTER TABLE `achievements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `trainer_id` (`trainer_id`);

--
-- Indexes for table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `phone` (`phone`),
  ADD KEY `trainer_id` (`trainer_id`),
  ADD KEY `gym_id` (`gym_id`);

--
-- Indexes for table `client_body_measurement`
--
ALTER TABLE `client_body_measurement`
  ADD UNIQUE KEY `client_id` (`client_id`);

--
-- Indexes for table `client_health_issue`
--
ALTER TABLE `client_health_issue`
  ADD UNIQUE KEY `client_id` (`client_id`);

--
-- Indexes for table `diets`
--
ALTER TABLE `diets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_id` (`client_id`);

--
-- Indexes for table `gyms`
--
ALTER TABLE `gyms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `trainer_id` (`trainer_id`);

--
-- Indexes for table `ratings`
--
ALTER TABLE `ratings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `client_id` (`client_id`),
  ADD KEY `trainer_id` (`trainer_id`);

--
-- Indexes for table `site_setting`
--
ALTER TABLE `site_setting`
  ADD KEY `trainer_id` (`trainer_id`);

--
-- Indexes for table `social_media`
--
ALTER TABLE `social_media`
  ADD PRIMARY KEY (`id`),
  ADD KEY `trainer_id` (`trainer_id`);

--
-- Indexes for table `trainer`
--
ALTER TABLE `trainer`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `phone` (`phone`);

--
-- Indexes for table `workouts`
--
ALTER TABLE `workouts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_id` (`client_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `achievements`
--
ALTER TABLE `achievements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT for table `diets`
--
ALTER TABLE `diets`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `gyms`
--
ALTER TABLE `gyms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `ratings`
--
ALTER TABLE `ratings`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `social_media`
--
ALTER TABLE `social_media`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `trainer`
--
ALTER TABLE `trainer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3961;

--
-- AUTO_INCREMENT for table `workouts`
--
ALTER TABLE `workouts`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `achievements`
--
ALTER TABLE `achievements`
  ADD CONSTRAINT `achievements_ibfk_1` FOREIGN KEY (`trainer_id`) REFERENCES `trainer` (`id`);

--
-- Constraints for table `clients`
--
ALTER TABLE `clients`
  ADD CONSTRAINT `clients_ibfk_1` FOREIGN KEY (`trainer_id`) REFERENCES `trainer` (`id`),
  ADD CONSTRAINT `clients_ibfk_2` FOREIGN KEY (`gym_id`) REFERENCES `gyms` (`id`);

--
-- Constraints for table `client_body_measurement`
--
ALTER TABLE `client_body_measurement`
  ADD CONSTRAINT `client_body_measurement_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`);

--
-- Constraints for table `client_health_issue`
--
ALTER TABLE `client_health_issue`
  ADD CONSTRAINT `client_health_issue_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`);

--
-- Constraints for table `diets`
--
ALTER TABLE `diets`
  ADD CONSTRAINT `diets_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`);

--
-- Constraints for table `gyms`
--
ALTER TABLE `gyms`
  ADD CONSTRAINT `gyms_ibfk_1` FOREIGN KEY (`trainer_id`) REFERENCES `trainer` (`id`);

--
-- Constraints for table `ratings`
--
ALTER TABLE `ratings`
  ADD CONSTRAINT `ratings_ibfk_1` FOREIGN KEY (`trainer_id`) REFERENCES `trainer` (`id`),
  ADD CONSTRAINT `ratings_ibfk_2` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`);

--
-- Constraints for table `site_setting`
--
ALTER TABLE `site_setting`
  ADD CONSTRAINT `site_setting_ibfk_1` FOREIGN KEY (`trainer_id`) REFERENCES `trainer` (`id`);

--
-- Constraints for table `social_media`
--
ALTER TABLE `social_media`
  ADD CONSTRAINT `social_media_ibfk_1` FOREIGN KEY (`trainer_id`) REFERENCES `trainer` (`id`);

--
-- Constraints for table `workouts`
--
ALTER TABLE `workouts`
  ADD CONSTRAINT `workouts_ibfk_2` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
