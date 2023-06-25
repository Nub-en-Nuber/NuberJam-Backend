-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 25, 2023 at 02:24 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `nuber_jam`
--

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

CREATE TABLE `account` (
  `accountId` int(11) NOT NULL,
  `accountName` varchar(250) NOT NULL,
  `accountUsername` varchar(250) NOT NULL,
  `accountEmail` varchar(250) NOT NULL,
  `accountPassword` varchar(250) NOT NULL,
  `accountPhoto` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `album`
--

CREATE TABLE `album` (
  `albumId` int(11) NOT NULL,
  `albumName` varchar(250) NOT NULL,
  `albumPhoto` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `favorite`
--

CREATE TABLE `favorite` (
  `favoriteId` int(11) NOT NULL,
  `musicId` int(11) NOT NULL,
  `accountId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `music`
--

CREATE TABLE `music` (
  `musicId` int(11) NOT NULL,
  `musicName` varchar(250) NOT NULL,
  `musicDuration` int(11) NOT NULL,
  `musicFile` varchar(500) NOT NULL,
  `albumId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `music_artist`
--

CREATE TABLE `music_artist` (
  `artistId` int(11) NOT NULL,
  `musicId` int(11) NOT NULL,
  `accountId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `playlist`
--

CREATE TABLE `playlist` (
  `playlistId` int(11) NOT NULL,
  `playlistName` varchar(250) NOT NULL,
  `playlistPhoto` varchar(500) NOT NULL,
  `accountId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `playlist_detail`
--

CREATE TABLE `playlist_detail` (
  `playlistDetailId` int(11) NOT NULL,
  `playlistId` int(11) NOT NULL,
  `musicId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `token`
--

CREATE TABLE `token` (
  `id` int(11) NOT NULL,
  `token` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `token`
--

INSERT INTO `token` (`id`, `token`) VALUES
(1, 'RAj6ALw6JEToPg5Rij4nX2bPMwvDB5dP');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`accountId`),
  ADD UNIQUE KEY `uq_user_username` (`accountUsername`),
  ADD UNIQUE KEY `uq_user_email` (`accountEmail`);

--
-- Indexes for table `album`
--
ALTER TABLE `album`
  ADD PRIMARY KEY (`albumId`);

--
-- Indexes for table `favorite`
--
ALTER TABLE `favorite`
  ADD PRIMARY KEY (`favoriteId`),
  ADD KEY `fk_favorite_musicId` (`musicId`),
  ADD KEY `fk_favorite_userId` (`accountId`);

--
-- Indexes for table `music`
--
ALTER TABLE `music`
  ADD PRIMARY KEY (`musicId`),
  ADD KEY `fk_music_albumId` (`albumId`);

--
-- Indexes for table `music_artist`
--
ALTER TABLE `music_artist`
  ADD PRIMARY KEY (`artistId`),
  ADD KEY `fk_account_artist_id` (`accountId`),
  ADD KEY `fk_music_artist_id` (`musicId`);

--
-- Indexes for table `playlist`
--
ALTER TABLE `playlist`
  ADD PRIMARY KEY (`playlistId`),
  ADD KEY `fk_playlist_userId` (`accountId`);

--
-- Indexes for table `playlist_detail`
--
ALTER TABLE `playlist_detail`
  ADD PRIMARY KEY (`playlistDetailId`),
  ADD KEY `fk_playlist_detail_playlistId` (`playlistId`),
  ADD KEY `fk_playlist_detail_musicId` (`musicId`);

--
-- Indexes for table `token`
--
ALTER TABLE `token`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account`
--
ALTER TABLE `account`
  MODIFY `accountId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `album`
--
ALTER TABLE `album`
  MODIFY `albumId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `favorite`
--
ALTER TABLE `favorite`
  MODIFY `favoriteId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `music`
--
ALTER TABLE `music`
  MODIFY `musicId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `music_artist`
--
ALTER TABLE `music_artist`
  MODIFY `artistId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `playlist`
--
ALTER TABLE `playlist`
  MODIFY `playlistId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `playlist_detail`
--
ALTER TABLE `playlist_detail`
  MODIFY `playlistDetailId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `token`
--
ALTER TABLE `token`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `favorite`
--
ALTER TABLE `favorite`
  ADD CONSTRAINT `fk_favorite_accountId` FOREIGN KEY (`accountId`) REFERENCES `account` (`accountId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_favorite_musicId` FOREIGN KEY (`musicId`) REFERENCES `music` (`musicId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `music`
--
ALTER TABLE `music`
  ADD CONSTRAINT `fk_music_albumId` FOREIGN KEY (`albumId`) REFERENCES `album` (`albumId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `music_artist`
--
ALTER TABLE `music_artist`
  ADD CONSTRAINT `fk_account_artist_id` FOREIGN KEY (`accountId`) REFERENCES `account` (`accountId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_music_artist_id` FOREIGN KEY (`musicId`) REFERENCES `music` (`musicId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `playlist`
--
ALTER TABLE `playlist`
  ADD CONSTRAINT `fk_playlist_accountId` FOREIGN KEY (`accountId`) REFERENCES `account` (`accountId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `playlist_detail`
--
ALTER TABLE `playlist_detail`
  ADD CONSTRAINT `fk_playlist_detail_musicId` FOREIGN KEY (`musicId`) REFERENCES `music` (`musicId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_playlist_detail_playlistId` FOREIGN KEY (`playlistId`) REFERENCES `playlist` (`playlistId`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
