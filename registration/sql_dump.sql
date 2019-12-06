-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 10, 2019 at 01:39 AM
-- Server version: 10.1.38-MariaDB
-- PHP Version: 5.6.40

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cs435p3`
--
CREATE DATABASE IF NOT EXISTS `cs435p3` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `cs435p3`;

-- --------------------------------------------------------

--
-- Table structure for table `presentation_slots`
--

CREATE TABLE `presentation_slots` (
  `id` int(11) NOT NULL,
  `date` varchar(30) NOT NULL,
  `slots_left` int(11) NOT NULL DEFAULT '6'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `presentation_slots`
--

INSERT INTO `presentation_slots` (`id`, `date`, `slots_left`) VALUES
(4, '12/10/19, 6:00 PM - 7:00 PM', 6),
(5, '12/10/19, 7:00 PM - 8:00 PM', 6),
(6, '12/10/19, 8:00 PM - 9:00 PM', 6),
(1, '12/9/19, 6:00 PM - 7:00 PM', 6),
(2, '12/9/19, 7:00 PM - 8:00 PM', 6),
(3, '12/9/19, 8:00 PM - 9:00 PM', 6);

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `umid` varchar(8) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `project_title` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone_number` varchar(12) DEFAULT NULL,
  `time_slot` int(11) NOT NULL,
  `presentation_date` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `presentation_slots`
--
ALTER TABLE `presentation_slots`
  ADD PRIMARY KEY (`date`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`umid`),
  ADD UNIQUE KEY `UMID` (`umid`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
