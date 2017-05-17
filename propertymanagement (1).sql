-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 17, 2017 at 12:45 AM
-- Server version: 10.1.21-MariaDB
-- PHP Version: 7.1.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `propertymanagement`
--

-- --------------------------------------------------------

--
-- Table structure for table `houses`
--

CREATE TABLE `houses` (
  `id` int(11) NOT NULL,
  `ownerId` int(11) NOT NULL,
  `postCode` varchar(10) NOT NULL,
  `address` varchar(500) NOT NULL,
  `phoneNumber` varchar(15) NOT NULL,
  `numberOfBedroom` int(11) NOT NULL,
  `Price` decimal(10,0) NOT NULL,
  `yearOfBuild` year(4) NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `imagepaths`
--

CREATE TABLE `imagepaths` (
  `id` int(11) NOT NULL,
  `houseId` int(11) NOT NULL,
  `imagePath` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(250) NOT NULL,
  `password` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `name`) VALUES
(4, 'chen@c.c', 'pop1234', 'Chen Chen'),
(11, 'Yu@zhi.com', 'Jac12345', 'Yuzhi'),
(12, 'flavie@g.com', 'Jac12345', 'flavie'),
(13, 'oulin@g.com', 'Pop12345', 'oulin'),
(14, 'elmira@e.com', 'AAAaaa111', 'amani'),
(15, 'E.amanollahi@gmail.com', 'AAAaaa111', 'Amanollahi');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `houses`
--
ALTER TABLE `houses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ownerId` (`ownerId`);

--
-- Indexes for table `imagepaths`
--
ALTER TABLE `imagepaths`
  ADD PRIMARY KEY (`id`),
  ADD KEY `houseId` (`houseId`),
  ADD KEY `houseId_2` (`houseId`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `houses`
--
ALTER TABLE `houses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `imagepaths`
--
ALTER TABLE `imagepaths`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `houses`
--
ALTER TABLE `houses`
  ADD CONSTRAINT `houses_ibfk_1` FOREIGN KEY (`ownerId`) REFERENCES `users` (`id`);

--
-- Constraints for table `imagepaths`
--
ALTER TABLE `imagepaths`
  ADD CONSTRAINT `imagepaths_ibfk_1` FOREIGN KEY (`houseId`) REFERENCES `houses` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
