-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Aug 29, 2025 at 09:42 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bible_school`
--

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `student_id` int(11) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `year_enrolled` year(4) NOT NULL,
  `year_graduated` varchar(20) DEFAULT NULL,
  `status` varchar(255) NOT NULL,
  `school_name` varchar(255) NOT NULL,
  `date_registered` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`student_id`, `lastname`, `firstname`, `year_enrolled`, `year_graduated`, `status`, `school_name`, `date_registered`) VALUES
(1, 'Gaco', 'Ernest Mae', '2020', '2024', 'graduate', 'MBSchool', '2025-08-27 02:55:59'),
(7, 'Boyonas', 'Grace', '2020', '2023', 'graduate', 'MBC', '2025-08-27 05:27:20');

-- --------------------------------------------------------

--
-- Stand-in structure for view `students_sorted`
-- (See below for the actual view)
--
CREATE TABLE `students_sorted` (
`student_id` int(11)
,`lastname` varchar(100)
,`firstname` varchar(100)
,`year_enrolled` year(4)
,`year_graduated` varchar(20)
,`status` varchar(255)
,`school_name` varchar(255)
,`date_registered` timestamp
);

-- --------------------------------------------------------

--
-- Structure for view `students_sorted`
--
DROP TABLE IF EXISTS `students_sorted`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `students_sorted`  AS SELECT `students`.`student_id` AS `student_id`, `students`.`lastname` AS `lastname`, `students`.`firstname` AS `firstname`, `students`.`year_enrolled` AS `year_enrolled`, `students`.`year_graduated` AS `year_graduated`, `students`.`status` AS `status`, `students`.`school_name` AS `school_name`, `students`.`date_registered` AS `date_registered` FROM `students` ORDER BY `students`.`year_enrolled` ASC, `students`.`lastname` ASC, `students`.`firstname` ASC ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`student_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
