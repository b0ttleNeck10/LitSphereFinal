-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 06, 2024 at 04:19 PM
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
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `BookID` int(11) NOT NULL,
  `Title` varchar(255) NOT NULL,
  `AuthorName` varchar(255) NOT NULL,
  `Genre` varchar(100) DEFAULT NULL,
  `CoverImageURL` varchar(255) DEFAULT NULL,
  `Description` text DEFAULT NULL,
  `Status` enum('Available','Borrowed') DEFAULT 'Available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`BookID`, `Title`, `AuthorName`, `Genre`, `CoverImageURL`, `Description`, `Status`) VALUES
(1, 'It Ends With Us', 'Dexter Lauron', 'Romance', '/book_img/image2.svg', 'Lily hasn’t always had it easy, but that’s never stopped her from working hard for the life she wants. She’s come a long way from the small town in Maine where she grew up — she graduated from college, moved to Boston, and started her own business. So when she feels a spark with a gorgeous neurosurgeon named Ryle Kincaid, everything in Lily’s life suddenly seems almost too good to be true. Ryle is assertive, stubborn, maybe even a little arrogant. He’s also sensitive, brilliant, and has a total soft spot for Lily.', 'Available'),
(2, 'Harry Potter and The Cursed Child', 'Cristian Torrejos', 'Mystery', '/book_img/image1.svg', 'Harry Potter and the Cursed Child (2016) is a two-part play written by Jack Thorne, based on an original story collaboratively created by J. K. Rowling, John Tiffany, and Thorne himself. Set in the universe of the Harry Potter books penned by J. K. Rowling, the play follows events occurring 19 years after the epilogue of the seventh book, The Deathly Hallows (2007); the story revolves around Albus Potter, the second son and middle child of Harry Potter, and Albus’s relationship with his famous father. Thorne is an award-winning English screenwriter and playwright. His portfolio includes the television adaptation of the His Dark Materials series, the first book of which is The Golden Compass (1995); the screenplay of the movie Wonder; a new adaptation of A Christmas Carol by Charles Dickens for Broadway; and the creation of the television drama National Treasure, the latter of which won him a BAFTA award. Harry Potter and the Cursed Child is among his award-winning works; at the 2017 Laurence Olivier Awards, the London production received a record-breaking level of nominations, of which it took home a record-breaking nine awards, including Best New Play. The Broadway production, too, received similar honors at the 2018 Tony Awards, taking home six awards, including Best Play.', 'Available');

-- --------------------------------------------------------

--
-- Table structure for table `borrow`
--

CREATE TABLE `borrow` (
  `BorrowID` int(11) NOT NULL,
  `BookID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `BorrowDate` date NOT NULL,
  `DueDate` date NOT NULL,
  `ReturnDate` date DEFAULT NULL,
  `Status` enum('Active','Returned','Overdue') DEFAULT 'Active',
  `Purpose` enum('In House Reading','Referencing') DEFAULT 'In House Reading'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `borrowinghistory`
--

CREATE TABLE `borrowinghistory` (
  `HistoryID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `BookID` int(11) NOT NULL,
  `BorrowDate` date NOT NULL,
  `ReturnDate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `SuspensionReason` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`UserID`, `FirstName`, `LastName`, `Email`, `PasswordHash`, `IsSuspended`, `SuspensionDate`, `SuspensionDuration`, `SuspensionReason`) VALUES
(2, 'Dex', 'Lauron', 'dexterlauron1@gmail.com', '$2y$10$RHevkFJT58kK9DQu6ghjX.sasPlNS80LwGgWa5LHspBc6OFv.e7RC', 0, '2024-11-05 07:46:19', NULL, NULL),
(3, 'Cristian', 'Torrejos', 'cristian@gmail.com', '$2y$10$npyP.ZBpbV3HcPEBfvrhkeHe7MyjNI1.vgEaOQVnFo0uFOVjhmw1S', 0, '2024-11-02 13:59:23', NULL, NULL),
(4, 'John', 'Doe', 'Dem@gmail.com', '$2y$10$q/KVV0PkJFnP9/bODyHen.G37Kv11jfXpeFo6/WlBIjHYJw1ch/kq', 0, '2024-11-05 06:40:56', NULL, NULL),
(5, 'The', 'Quick', 'brownfox@gmail.com', '$2y$10$eB.aJOeWAfDVSHhAS9zeVeCJLcAdH21X1pCD3A7IYTAsC2qCw5NGa', 0, '2024-11-05 06:49:35', NULL, NULL),
(7, 'Admin', 'Librarian', 'admin@example.com', '$2y$10$oh7yVQu49ebsxmsu4FnhG.euvgotg4sj8kW0kRqWtVyy8yyjf96bC', 0, '2024-11-06 15:19:03', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`BookID`);

--
-- Indexes for table `borrow`
--
ALTER TABLE `borrow`
  ADD PRIMARY KEY (`BorrowID`),
  ADD KEY `BookID` (`BookID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `borrowinghistory`
--
ALTER TABLE `borrowinghistory`
  ADD PRIMARY KEY (`HistoryID`),
  ADD KEY `UserID` (`UserID`),
  ADD KEY `BookID` (`BookID`);

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
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `BookID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `borrow`
--
ALTER TABLE `borrow`
  MODIFY `BorrowID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `borrowinghistory`
--
ALTER TABLE `borrowinghistory`
  MODIFY `HistoryID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `borrow`
--
ALTER TABLE `borrow`
  ADD CONSTRAINT `borrow_ibfk_1` FOREIGN KEY (`BookID`) REFERENCES `books` (`BookID`) ON DELETE CASCADE,
  ADD CONSTRAINT `borrow_ibfk_2` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE;

--
-- Constraints for table `borrowinghistory`
--
ALTER TABLE `borrowinghistory`
  ADD CONSTRAINT `borrowinghistory_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE,
  ADD CONSTRAINT `borrowinghistory_ibfk_2` FOREIGN KEY (`BookID`) REFERENCES `books` (`BookID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
