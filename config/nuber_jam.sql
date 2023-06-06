-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 06, 2023 at 04:39 PM
-- Server version: 10.4.25-MariaDB
-- PHP Version: 8.0.23

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
-- Table structure for table `album`
--

CREATE TABLE `album` (
  `albumId` int(11) NOT NULL,
  `albumName` varchar(250) NOT NULL,
  `albumArtist` varchar(250) NOT NULL,
  `albumPhoto` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `album`
--

INSERT INTO `album` (`albumId`, `albumName`, `albumArtist`, `albumPhoto`) VALUES
(1, 'Map of The Soul', 'BTS', 'localhost'),
(2, 'Album Coba 2', 'Muhammad Coba 2', 'http://localhost/nuberjam/asset/images/album/default-album.png');

-- --------------------------------------------------------

--
-- Table structure for table `favorite`
--

CREATE TABLE `favorite` (
  `favoriteId` int(11) NOT NULL,
  `musicId` int(11) NOT NULL,
  `userId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `music`
--

INSERT INTO `music` (`musicId`, `musicName`, `musicDuration`, `musicFile`, `albumId`) VALUES
(1, 'ON', 246, 'localhost', 1),
(2, 'Jamais Vu', 227, 'localhost', 1);

-- --------------------------------------------------------

--
-- Table structure for table `playlist`
--

CREATE TABLE `playlist` (
  `playlistId` int(11) NOT NULL,
  `playlistName` varchar(250) NOT NULL,
  `playlistPhoto` varchar(500) NOT NULL,
  `userId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `playlist`
--

INSERT INTO `playlist` (`playlistId`, `playlistName`, `playlistPhoto`, `userId`) VALUES
(1, 'Lagu-lagu Tidur', 'localhost', 2),
(2, 'Coldplay - Maroon5', 'localhost', 2),
(4, 'Lagu Belajar', 'http://localhost/nuberjam/asset/images/playlist/default-playlist.png', 1),
(6, 'Lagu Main', 'http://localhost/nuberjam/asset/images/playlist/LaguMain-1-00c574a29e4e0223ebb92352d9fae3b3.png', 1);

-- --------------------------------------------------------

--
-- Table structure for table `playlist_detail`
--

CREATE TABLE `playlist_detail` (
  `playlistDetailId` int(11) NOT NULL,
  `playlistId` int(11) NOT NULL,
  `musicId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `playlist_detail`
--

INSERT INTO `playlist_detail` (`playlistDetailId`, `playlistId`, `musicId`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 4, 1);

-- --------------------------------------------------------

--
-- Table structure for table `token`
--

CREATE TABLE `token` (
  `id` int(11) NOT NULL,
  `token` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `token`
--

INSERT INTO `token` (`id`, `token`) VALUES
(1, 'RAj6ALw6JEToPg5Rij4nX2bPMwvDB5dP');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `userId` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `username` varchar(250) NOT NULL,
  `email` varchar(250) NOT NULL,
  `password` varchar(250) NOT NULL,
  `photo` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`userId`, `name`, `username`, `email`, `password`, `photo`) VALUES
(1, 'Rama Tri Agung', 'RTAgung', 'agung@email.com', 'hehe', 'hihi'),
(2, 'Anjar Harimurti', 'args06', 'anjar@gmail.com', 'anjar123', 'http://localhost/nuberJam/asset/images/user/c81e728d9d4c2f636f067f89cc14862c.png'),
(3, 'Liek Allyandaru', 'all4yandaru', 'daru@email.com', '54321', 'localhost');

--
-- Indexes for dumped tables
--

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
  ADD KEY `fk_favorite_userId` (`userId`);

--
-- Indexes for table `music`
--
ALTER TABLE `music`
  ADD PRIMARY KEY (`musicId`),
  ADD KEY `fk_music_albumId` (`albumId`);

--
-- Indexes for table `playlist`
--
ALTER TABLE `playlist`
  ADD PRIMARY KEY (`playlistId`),
  ADD KEY `fk_playlist_userId` (`userId`);

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
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`userId`),
  ADD UNIQUE KEY `uq_user_username` (`username`),
  ADD UNIQUE KEY `uq_user_email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `album`
--
ALTER TABLE `album`
  MODIFY `albumId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `favorite`
--
ALTER TABLE `favorite`
  MODIFY `favoriteId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `music`
--
ALTER TABLE `music`
  MODIFY `musicId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `playlist`
--
ALTER TABLE `playlist`
  MODIFY `playlistId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `playlist_detail`
--
ALTER TABLE `playlist_detail`
  MODIFY `playlistDetailId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `token`
--
ALTER TABLE `token`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `userId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `favorite`
--
ALTER TABLE `favorite`
  ADD CONSTRAINT `fk_favorite_musicId` FOREIGN KEY (`musicId`) REFERENCES `music` (`musicId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_favorite_userId` FOREIGN KEY (`userId`) REFERENCES `user` (`userId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `music`
--
ALTER TABLE `music`
  ADD CONSTRAINT `fk_music_albumId` FOREIGN KEY (`albumId`) REFERENCES `album` (`albumId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `playlist`
--
ALTER TABLE `playlist`
  ADD CONSTRAINT `fk_playlist_userId` FOREIGN KEY (`userId`) REFERENCES `user` (`userId`) ON DELETE CASCADE ON UPDATE CASCADE;

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
