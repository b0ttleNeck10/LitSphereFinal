-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 01, 2024 at 07:09 AM
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
(1, 'It Ends With Us', 'Dexter Lauron', 'Romance', '../book_img/image2.svg', 'Lily hasn’t always had it easy, but that’s never stopped her from working hard for the life she wants. She’s come a long way from the small town in Maine where she grew up — she graduated from college, moved to Boston, and started her own business. So when she feels a spark with a gorgeous neurosurgeon named Ryle Kincaid, everything in Lily’s life suddenly seems almost too good to be true. Ryle is assertive, stubborn, maybe even a little arrogant. He’s also sensitive, brilliant, and has a total soft spot for Lily.', 'Available'),
(2, 'Harry Potter and The Cursed Child', 'Cristian Torrejos', 'Fantasy', '../book_img/image1.svg', 'Harry Potter and the Cursed Child (2016) is a two-part play written by Jack Thorne, based on an original story collaboratively created by J. K. Rowling, John Tiffany, and Thorne himself. Set in the universe of the Harry Potter books penned by J. K. Rowling, the play follows events occurring 19 years after the epilogue of the seventh book, The Deathly Hallows (2007); the story revolves around Albus Potter, the second son and middle child of Harry Potter, and Albus’s relationship with his famous father. Thorne is an award-winning English screenwriter and playwright. His portfolio includes the television adaptation of the His Dark Materials series, the first book of which is The Golden Compass (1995); the screenplay of the movie Wonder; a new adaptation of A Christmas Carol by Charles Dickens for Broadway; and the creation of the television drama National Treasure, the latter of which won him a BAFTA award. Harry Potter and the Cursed Child is among his award-winning works; at the 2017 Laurence Olivier Awards, the London production received a record-breaking level of nominations, of which it took home a record-breaking nine awards, including Best New Play. The Broadway production, too, received similar honors at the 2018 Tony Awards, taking home six awards, including Best Play.', 'Borrowed'),
(5, 'A Game of Thrones', 'George R.R. Martin', 'Fantasy', '../book_img/y648.jpg', 'â€œA Game of Thronesâ€ by George R.R. Martin tells the tale of various clashing households and their quest to conquer control over the seven kingdoms.', 'Borrowed'),
(6, 'The Fault in Our Stars', 'John Green', 'Romance', '../book_img/11870085.jpg', 'The Fault in Our Stars by John Green is a young adult fiction novel that narrates the story of a 16-year-old girl who is diagnosed with cancer. She joins a support group where she meets Augustus, and there is a rollercoaster of emotions throughout this novel as the relationship between Hazel and Augustus develops.', 'Available'),
(7, 'A Clash of Kings', 'George R.R. Martin', 'Fantasy', '../book_img/7193zyz9thL._SL1200_.jpg', 'The novel follows the complex power struggles across the Seven Kingdoms of Westeros as rival claimants vie for the Iron Throne, while ancient threats and supernatural forces loom on the horizon.', 'Borrowed'),
(8, 'Silence of the Lambs', 'Thomas Harris', 'Thriller', '../book_img/Silence3.png', 'The Silence of the Lambs by Thomas Harris is a 1988 psychological thriller novel about an FBI trainee who teams up with a former serial killer to catch a murderer', 'Borrowed'),
(9, 'Les MisÃ©rables', 'Victor Hugo', 'Drama', '../book_img/les-miserables-9781626864641_hr.jpg', 'Les MisÃ©rables is a novel about how people in the lower classes of France find their way in society. It explores what people stuck in the lower rungs of society must do to survive, effectively criticizing society by showing stories of people who struggle in unfair societies.', 'Borrowed'),
(10, 'The Great Gatsby', 'F. Scott Fitzgerald', 'Historical', '../book_img/81QuEGw8VPL.jpg', 'The Great Gatsby is a 1925 novel by American writer F. Scott Fitzgerald. Set in the Jazz Age on Long Island, near New York City, the novel depicts first-person narrator Nick Carraway\'s interactions with Jay Gatsby, the mysterious millionaire with an obsession to reunite with his former lover, Daisy Buchanan.\r\n', 'Available'),
(11, 'A Storm of Swords', 'George R.R. Martin', 'Fantasy', '../book_img/IMG-20240229_154626_1024x1024@2x.webp', 'A Storm of Swords is the third book in the A Song of Ice and Fire series by George R.R. Martin. It continues the story from A Clash of Kings and is set in the Seven Kingdoms of Westeros during the War of the Five Kings. ', 'Borrowed'),
(12, 'A Feast for Crows', 'George R.R. Martin', 'Fantasy', '../book_img/13497.jpg', 'Crows will fight over a dead man\'s flesh, and kill each other for his eyes. Bloodthirsty, treacherous and cunning, the Lannisters are in power on the Iron Throne in the name of the boy-king Tommen. The war in the Seven Kingdoms has burned itself out, but in its bitter aftermath new conflicts spark to life.', 'Available'),
(13, 'A Dance with Dragons', 'George R.R. Martin', 'Fantasy', '../book_img/A_Dance_With_Dragons_US.jpg', 'In the aftermath of a colossal battle, the future of the Seven Kingdoms hangs in the balance once againâ€“beset by newly emerging threats from every direction. In the east, Daenerys Targaryen, the last scion of House Targaryen, rules with her three dragons as queen of a city built on dust and death.', 'Available'),
(14, 'Farenheit 451', 'Ray Bradbury', 'Science Fiction', '../book_img/Fahrenheit_451_1st_ed_cover.jpg', 'Fahrenheit 451 is a 1953 dystopian novel by American writer Ray Bradbury. It presents a future American society where books have been outlawed, and \"firemen\" burn any that are found.', 'Borrowed'),
(15, 'The Hunger Games', 'Suzanne Collins', 'Science Fiction', '../book_img/2767052._SY475_.jpg', 'The Hunger Games is an annual event in which one boy and one girl aged 12â€“18 from each of the twelve districts surrounding the Capitol are selected by lottery to compete in a televised battle royale to the death. The book received critical acclaim from major reviewers and authors.', 'Borrowed'),
(16, 'Catching Fire', 'Suzanne Collins', 'Science Fiction', '../book_img/81SdPfjin8L._SL1500_.jpg', 'Catching Fire is a young adult dystopian science fiction novel that takes place in the future, amidst the ruins of what was once America. Catching Fire details the aftermath of Katniss Everdeen and Peeta Mellark\'s victory in the 74th Hunger Games from the first novel.', 'Available'),
(17, 'Mockingjay', 'Suzanne Collins', 'Science Fiction', '../book_img/61QkuvGgc2S._SL1500_.jpg', 'Mockingjay is a young adult dystopian novel by Suzanne Collins and the final book in The Hunger Games trilogy. It tells the story of Katniss Everdeen, who becomes the symbol of rebellion against the Capitol in a fight to save her loved ones: ', 'Available'),
(18, 'The Ballad of Songbirds and Snakes', 'Suzanne Collins', 'Science Fiction', '../book_img/61xCJNYdljL._AC_UF894,1000_QL80_.jpg', 'â€œThe Ballad of Songbirds and Snakesâ€ tells the story of Coriolanus Snow, the future President of Panem, when he is just 18. As a Capital citizen, he has been asked in his final year of Academy to be a mentor in the Hunger Games. His tribute is Lucy Gray Baird, a fiery girl from District 12', 'Available'),
(19, 'The Princess Diaries', 'Meg Cabot', 'Romance', '../book_img/50997637.jpg', 'Mia Thermopolis is a high school freshman, content living in Manhattan, N.Y., with her artist mother. Then her European playboy father and aristocratic GrandmÃ©re reveal a shocking secret: Mia is the princess of a country called Genovia.', 'Borrowed'),
(20, 'Divergent', 'Veronica Roth', 'Science Fiction', '../book_img/13335037.jpg', 'The first main installment in the series tells the story of Beatrice Prior, a teenager who lives in a post-apocalyptic Chicago in which society has been divided into five factions, each with a specialized social function: Abnegation, Amity, Candor, Dauntless, and Erudite.', 'Borrowed'),
(21, 'Insurgent', 'Veronica Roth', 'Science Fiction', '../book_img/s-l1200.jpg', 'The plot of Insurgent takes place five days after the previous installment and continues to follow Dauntless soldier Tris Prior; Tris and Four, her Dauntless instructor, are on the run after evading a coup from Erudite faction leader Jeanine and the rest of her faction.', 'Available'),
(22, 'Allegiant', 'Veronica Roth', 'Action', '../book_img/71HntHaTLeL.jpg', 'The faction-based society that Tris Prior once believed in is shatteredâ€”fractured by violence and power struggles and scarred by loss and betrayal. So when offered a chance to explore the world past the limits she\'s known, Tris is ready.', 'Available');

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
  `Status` enum('Requested','Active','Returned','Overdue','Denied') DEFAULT 'Requested'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `borrow`
--

INSERT INTO `borrow` (`BorrowID`, `BookID`, `UserID`, `BorrowDate`, `DueDate`, `ReturnDate`, `Status`) VALUES
(1, 9, 7, '2024-11-30', '2024-12-05', NULL, 'Requested'),
(2, 8, 7, '2024-11-30', '2024-12-04', NULL, 'Requested'),
(3, 2, 7, '2024-11-30', '2024-12-05', NULL, 'Requested'),
(4, 5, 7, '2024-11-30', '2024-12-04', NULL, 'Requested'),
(5, 7, 7, '2024-11-30', '2024-12-05', NULL, 'Requested'),
(6, 11, 7, '2024-12-01', '2024-12-06', NULL, 'Requested'),
(7, 19, 7, '2024-12-01', '2024-12-06', NULL, 'Requested'),
(8, 15, 7, '2024-12-01', '2024-12-06', NULL, 'Requested'),
(9, 14, 7, '2024-12-01', '2024-12-06', NULL, 'Requested'),
(10, 20, 7, '2024-12-01', '2024-12-06', NULL, 'Requested');

-- --------------------------------------------------------

--
-- Table structure for table `borrowinghistory`
--

CREATE TABLE `borrowinghistory` (
  `HistoryID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `BookID` int(11) NOT NULL,
  `BorrowDate` date NOT NULL,
  `ReturnDate` date DEFAULT NULL,
  `Status` enum('Active','Cleared') NOT NULL DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `borrowinghistory`
--

INSERT INTO `borrowinghistory` (`HistoryID`, `UserID`, `BookID`, `BorrowDate`, `ReturnDate`, `Status`) VALUES
(1, 7, 9, '2024-11-30', NULL, 'Active'),
(2, 7, 8, '2024-11-30', NULL, 'Active'),
(3, 7, 2, '2024-11-30', NULL, 'Active'),
(4, 7, 5, '2024-11-30', NULL, 'Active'),
(5, 7, 7, '2024-11-30', NULL, 'Active'),
(6, 7, 11, '2024-12-01', NULL, 'Active'),
(7, 7, 19, '2024-12-01', NULL, 'Active'),
(8, 7, 15, '2024-12-01', NULL, 'Active'),
(9, 7, 14, '2024-12-01', NULL, 'Active'),
(10, 7, 20, '2024-12-01', NULL, 'Active');

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
(6, 'Test', 'Test', 'test@gmail.com', '$2y$10$elemLcn2AIlL5keqKYik7.M/ATYseTGaTqskDY06t9qNmDNPZojqK', 0, '2024-11-17 06:55:34', NULL, NULL, 'Reader'),
(7, 'Tester', 'Test', 'test@example.com', '$2y$10$5IX32MopVKb6CNMeT5QFCONBUme3BZS97Nt6dEuQZOIzWLqzl/qoi', 0, '2024-11-30 13:09:26', NULL, NULL, 'Reader');

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
  MODIFY `BookID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `borrow`
--
ALTER TABLE `borrow`
  MODIFY `BorrowID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `borrowinghistory`
--
ALTER TABLE `borrowinghistory`
  MODIFY `HistoryID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

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
