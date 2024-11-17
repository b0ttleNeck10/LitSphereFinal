-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 17, 2024 at 07:56 AM
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
-- Database: `userdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `UserID` int(11) NOT NULL,
  `FirstName` varchar(100) NOT NULL,
  `LastName` varchar(100) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `PasswordHash` varchar(255) NOT NULL,
  `IsSuspended` tinyint(1) DEFAULT 0,
  `SuspensionDate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `SuspensionDuration` int(11) DEFAULT NULL,
  `SuspensionReason` varchar(1000) DEFAULT NULL,
  `Role` enum('Reader','Admin') DEFAULT 'Reader'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`UserID`, `FirstName`, `LastName`, `Email`, `PasswordHash`, `IsSuspended`, `SuspensionDate`, `SuspensionDuration`, `SuspensionReason`, `Role`) VALUES
(1, 'Dexter', 'Lauron', 'dexterlauron1@gmail.com', '$2y$10$RHevkFJT58kK9DQu6ghjX.sasPlNS80LwGgWa5LHspBc6OFv.e7RC', 0, '2024-11-17 06:55:34', NULL, NULL, 'Reader'),
(2, 'Cristian', 'Torrejos', 'cristian@gmail.com', '$2y$10$npyP.ZBpbV3HcPEBfvrhkeHe7MyjNI1.vgEaOQVnFo0uFOVjhmw1S', 0, '2024-11-17 06:55:34', NULL, NULL, 'Reader'),
(3, 'John', 'Doe', 'Dem@gmail.com', '$2y$10$q/KVV0PkJFnP9/bODyHen.G37Kv11jfXpeFo6/WlBIjHYJw1ch/kq', 0, '2024-11-17 06:55:34', NULL, NULL, 'Reader'),
(4, 'The', 'Quick', 'brownfox@gmail.com', '$2y$10$eB.aJOeWAfDVSHhAS9zeVeCJLcAdH21X1pCD3A7IYTAsC2qCw5NGa', 0, '2024-11-17 06:55:34', NULL, NULL, 'Reader'),
(5, 'Admin', 'Librarian', 'admin@example.com', '$2y$10$oh7yVQu49ebsxmsu4FnhG.euvgotg4sj8kW0kRqWtVyy8yyjf96bC', 0, '2024-11-17 06:55:34', NULL, NULL, 'Admin'),
(6, 'Test', 'Test', 'test@gmail.com', '$2y$10$elemLcn2AIlL5keqKYik7.M/ATYseTGaTqskDY06t9qNmDNPZojqK', 0, '2024-11-17 06:55:34', NULL, NULL, 'Reader');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UserID`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
