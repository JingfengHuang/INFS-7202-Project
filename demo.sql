-- phpMyAdmin SQL Dump
-- version 5.0.4deb2~bpo10+1+bionic1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 14, 2021 at 02:34 PM
-- Server version: 5.7.34-0ubuntu0.18.04.1
-- PHP Version: 7.2.24-0ubuntu0.18.04.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `demo`
--

-- --------------------------------------------------------

--
-- Table structure for table `avatar`
--

CREATE TABLE `avatar` (
  `username` varchar(225) NOT NULL,
  `filename` varchar(225) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `avatar`
--

INSERT INTO `avatar` (`username`, `filename`) VALUES
('danda', 'F8EA5427-1EE8-4C94-8DBE-1F4949C9435C_1_201_a.jpeg'),
('Jingfeng', '1FCD085E-CF03-4A54-AE12-F7239CE0E590_1_201_a4.jpeg'),
('JingfengHuang', '1FCD085E-CF03-4A54-AE12-F7239CE0E590_1_201_a6.jpeg'),
('s4652737', 'dl_10_04.png'),
('Vodka', 'pexels-mohamed-abdelghaffar-771742.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `favourites`
--

CREATE TABLE `favourites` (
  `id` int(11) NOT NULL,
  `username` varchar(25) CHARACTER SET latin1 NOT NULL,
  `videoID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `favourites`
--

INSERT INTO `favourites` (`id`, `username`, `videoID`) VALUES
(2, 'Jingfeng', 27),
(4, 'JingfengHuang', 19),
(7, 'Vodka', 28),
(9, 'JingfengHuang', 29),
(10, 'JingfengHuang', 28);

-- --------------------------------------------------------

--
-- Table structure for table `follows`
--

CREATE TABLE `follows` (
  `id` int(11) NOT NULL,
  `user` varchar(25) NOT NULL,
  `following` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `follows`
--

INSERT INTO `follows` (`id`, `user`, `following`) VALUES
(10, 'Jingfeng', 'rex'),
(15, 'Jingfeng', 's4652737'),
(16, 'Jingfeng', 'danda'),
(21, 'JingfengHuang', 'rex'),
(22, 'JingfengHuang', 's4652737'),
(23, 'Vodka', 'JingfengHuang'),
(24, 'Vodka', 'danda'),
(26, 'JingfengHuang', 'Vodka'),
(28, 'JingfengHuang', 'danda');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `username` varchar(25) NOT NULL,
  `nickname` varchar(100) NOT NULL,
  `password` varchar(225) NOT NULL,
  `email` varchar(100) NOT NULL,
  `self_intro` text,
  `verified` tinyint(1) NOT NULL,
  `twitterAccountName` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`username`, `nickname`, `password`, `email`, `self_intro`, `verified`, `twitterAccountName`) VALUES
('danda', 'Sora', 'Qaz123456', 's4614261@student.uq.edu.au', 'This user is too lazy to write a short self introduction.', 1, NULL),
('Jingfeng', 'Nyarly', 'HJf14370341', 'huangjingfeng970106@outlook.com', 'Hello everyone! I am Nyarly, and I like to play Overwatch.', 1, NULL),
('JingfengHuang', 'Jingfeng', '$2y$10$LLJxCSUWnC35tYVp9zaZWOliqsaFc.V/Y8Ln23QbAIfQjjFvt/k/.', 'huangjingfeng970106@gmail.com', 'This user is too lazy to write a short self introduction.', 1, 'Huang_Jingfeng'),
('rex', 'FatOtter', '1qaz2wsxE', 'liyue.shen@uqconnect.edu.au', 'This user is too lazy to write a short self introduction.', 1, NULL),
('s4652737', 'ninggg', 'Ninggg123', 'weining.tang@uqconnect.edu.au', 'This user is too lazy to write a short self introduction.', 1, NULL),
('Test1', 'TestAccount', '$2y$10$mclieJXBuBTXQS.GBDh.pu5siUSBeZfojTSeNRHEJpq8ge.2Fy8GC', 'test1@gmail.com', 'This user is too lazy to write a short self introduction.', 1, NULL),
('Vodka', 'FatOtter123', '$2y$10$rZdht8a0WLTQ1X/lLM4Btu5jZiFD7u0zlCIw/h/.vO12EYQ1/VCD.', 'narcy188@outlook.com', 'Some introduction', 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `videocomments`
--

CREATE TABLE `videocomments` (
  `id` int(11) NOT NULL,
  `user` varchar(25) CHARACTER SET latin1 NOT NULL,
  `videoID` int(11) NOT NULL,
  `text` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `time` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `isanonymous` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `videocomments`
--

INSERT INTO `videocomments` (`id`, `user`, `videoID`, `text`, `time`, `isanonymous`) VALUES
(1, 'JingfengHuang', 19, 'Wow, it\'s so cool!', '2021-04-23 10:19 AM', 0),
(6, 'Jingfeng', 19, 'This is a very long comment, for testing the style of comments area. The following contents are random generated words: asdjaoifno anfjaisojdoiasjdioasnfioasnfas', '2021-04-23 10:30 AM', 0),
(8, 'JingfengHuang', 22, 'https://www.pexels.com/video/video-of-funny-cat-855029/', '2021-04-23 11:27 AM', 0),
(9, 'JingfengHuang', 27, 'Thank you for uploading!', '2021-04-23 12:36 PM', 0),
(13, 'JingfengHuang', 27, 'Emoji is not supported here : (', '2021-04-23 01:25 PM', 0),
(15, 'JingfengHuang', 21, 'Nice', '2021-04-23 02:01 PM', 0),
(16, 'JingfengHuang', 20, 'FatOtter\'s first video!', '2021-04-23 02:04 PM', 0),
(24, 'Jingfeng', 22, 'Lovely cat : )', '2021-04-24 06:58 AM', 0),
(25, 'rex', 23, 'Haha it\'s so fat!', '2021-04-24 08:05 AM', 0),
(26, 'JingfengHuang', 28, 'Good job : )', '2021-04-24 09:18 AM', 0),
(28, 'JingfengHuang', 23, 'Re-FatOtter: Yeah', '2021-04-24 11:37 PM', 0),
(29, 'JingfengHuang', 18, 'Test comment function.', '2021-04-27 09:43 PM', 0),
(30, 'JingfengHuang', 28, 'Test comment function.', '2021-04-27 09:55 PM', 0),
(31, 'JingfengHuang', 28, 'Test common function again : )', '2021-04-27 10:04 PM', 0),
(42, 'JingfengHuang', 28, 'ABC', '2021-05-26 02:00 PM', 1);

-- --------------------------------------------------------

--
-- Table structure for table `videolikes`
--

CREATE TABLE `videolikes` (
  `id` int(11) NOT NULL,
  `username` varchar(25) CHARACTER SET latin1 NOT NULL,
  `videoID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `videolikes`
--

INSERT INTO `videolikes` (`id`, `username`, `videoID`) VALUES
(9, 'JingfengHuang', 28),
(10, 'JingfengHuang', 11),
(11, 'JingfengHuang', 20),
(12, 'JingfengHuang', 27),
(13, 'JingfengHuang', 19),
(14, 'JingfengHuang', 26),
(17, 'JingfengHuang', 18),
(19, 'Vodka', 29),
(20, 'JingfengHuang', 29),
(22, 'JingfengHuang', 25);

-- --------------------------------------------------------

--
-- Table structure for table `videos`
--

CREATE TABLE `videos` (
  `id` int(11) NOT NULL,
  `filename` varchar(225) NOT NULL,
  `filetype` varchar(10) NOT NULL,
  `videotitle` text NOT NULL,
  `videocategory` text NOT NULL,
  `description` longtext,
  `username` varchar(25) NOT NULL,
  `time` text NOT NULL,
  `viewedtimes` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `videos`
--

INSERT INTO `videos` (`id`, `filename`, `filetype`, `videotitle`, `videocategory`, `description`, `username`, `time`, `viewedtimes`) VALUES
(11, 'Genji.mp4', 'video/mp4', 'Overwatch-Genji', 'Game', 'Play of the game', 'JingfengHuang', '2021-04-12 02:43 AM', 38),
(17, 'echo_21-04-21_20-10-54.mp4', 'video/mp4', 'Overwatch-Echo', 'Game', 'Echo: play of the game', 'JingfengHuang', '2021-04-21 12:21 PM', 4),
(18, 'mccree_21-04-21_20-09-21.mp4', 'video/mp4', 'Overwatch-Mccree', 'Game', 'Mccree: Play of the game', 'JingfengHuang', '2021-04-21 12:32 PM', 65),
(19, 'doomfist_21-04-22_17-28-45.mp4', 'video/mp4', 'Overwatch-Doomfist', 'Game', 'Doomfist game play', 'Jingfeng', '2021-04-22 12:38 PM', 125),
(20, 'production_ID_4291570.mp4', 'video/mp4', 'Smart phone games', 'Game', 'Playing the video game', 'rex', '2021-04-23 08:54 AM', 43),
(21, 'reaper_21-04-23_16-42-57.mp4', 'video/mp4', 'Overwatch-Reaper', 'Game', 'Reaper: Play of the game', 'Jingfeng', '2021-04-23 08:56 AM', 18),
(22, 'Video_Of_Funny_Cat.mp4', 'video/mp4', 'Funny cat', 'Fun', 'A cute funny cat', 'JingfengHuang', '2021-04-23 11:21 AM', 2168),
(23, 'production_ID_4874384.mp4', 'video/mp4', 'Funny dog', 'Fun', 'https://www.pexels.com/video/walking-the-dog-in-the-park-4874384/', 'JingfengHuang', '2021-04-23 11:34 AM', 478),
(25, 'pexels-joshua-6832006.mp4', 'video/mp4', 'Animal webtoon', 'Webtoon', 'https://www.pexels.com/video/horses-under-the-coconut-tree-6832006/', 'JingfengHuang', '2021-04-23 11:48 AM', 13),
(26, 'Woman_Picking_Out_Clothes.mp4', 'video/mp4', 'Woman Picking Out Clothes', 'Fashion', 'https://www.pexels.com/video/woman-picking-out-clothes-853800/', 'JingfengHuang', '2021-04-23 11:52 AM', 4),
(27, 'MVI_2903.MP4', 'video/mp4', 'Christmas decoration', 'Life', 'Christmas decoration in my high school', 's4652737', '2021-04-23 12:35 AM', 120),
(28, '1619255561281220.mp4', 'video/mp4', 'Disney\'s night', 'Life', 'Christmas Eve', 'danda', '2021-04-24 09:14 AM', 3326),
(29, 'smallsample1.mp4', 'video/mp4', 'Smart phone games', 'Fun', 'some description', 'Vodka', '2021-04-30 04:24 PM', 11);

-- --------------------------------------------------------

--
-- Table structure for table `watchlater`
--

CREATE TABLE `watchlater` (
  `id` int(11) NOT NULL,
  `username` varchar(25) CHARACTER SET latin1 NOT NULL,
  `videoID` int(11) NOT NULL,
  `time` text COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `avatar`
--
ALTER TABLE `avatar`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `favourites`
--
ALTER TABLE `favourites`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_user_favourite_videos` (`username`),
  ADD KEY `FK_video_id` (`videoID`);

--
-- Indexes for table `follows`
--
ALTER TABLE `follows`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_follow_user` (`user`),
  ADD KEY `FK_follow_following` (`following`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `videocomments`
--
ALTER TABLE `videocomments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_videocomments_user` (`user`),
  ADD KEY `FK_videocomments_video` (`videoID`);

--
-- Indexes for table `videolikes`
--
ALTER TABLE `videolikes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_videolikes_username` (`username`),
  ADD KEY `FK_videolikes_videoID` (`videoID`);

--
-- Indexes for table `videos`
--
ALTER TABLE `videos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_user_upload` (`username`);

--
-- Indexes for table `watchlater`
--
ALTER TABLE `watchlater`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_watch_later_videoID` (`videoID`),
  ADD KEY `FK_watch_later_username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `favourites`
--
ALTER TABLE `favourites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `follows`
--
ALTER TABLE `follows`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `videocomments`
--
ALTER TABLE `videocomments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `videolikes`
--
ALTER TABLE `videolikes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `videos`
--
ALTER TABLE `videos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `watchlater`
--
ALTER TABLE `watchlater`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `avatar`
--
ALTER TABLE `avatar`
  ADD CONSTRAINT `avatar_ibfk_1` FOREIGN KEY (`username`) REFERENCES `users` (`username`);

--
-- Constraints for table `favourites`
--
ALTER TABLE `favourites`
  ADD CONSTRAINT `FK_user_favourite_videos` FOREIGN KEY (`username`) REFERENCES `users` (`username`),
  ADD CONSTRAINT `FK_video_id` FOREIGN KEY (`videoID`) REFERENCES `videos` (`id`);

--
-- Constraints for table `follows`
--
ALTER TABLE `follows`
  ADD CONSTRAINT `FK_follow_following` FOREIGN KEY (`following`) REFERENCES `users` (`username`),
  ADD CONSTRAINT `FK_follow_user` FOREIGN KEY (`user`) REFERENCES `users` (`username`);

--
-- Constraints for table `videocomments`
--
ALTER TABLE `videocomments`
  ADD CONSTRAINT `FK_videocomments_user` FOREIGN KEY (`user`) REFERENCES `users` (`username`),
  ADD CONSTRAINT `FK_videocomments_video` FOREIGN KEY (`videoID`) REFERENCES `videos` (`id`);

--
-- Constraints for table `videolikes`
--
ALTER TABLE `videolikes`
  ADD CONSTRAINT `FK_videolikes_username` FOREIGN KEY (`username`) REFERENCES `users` (`username`),
  ADD CONSTRAINT `FK_videolikes_videoID` FOREIGN KEY (`videoID`) REFERENCES `videos` (`id`);

--
-- Constraints for table `videos`
--
ALTER TABLE `videos`
  ADD CONSTRAINT `FK_user_upload` FOREIGN KEY (`username`) REFERENCES `users` (`username`);

--
-- Constraints for table `watchlater`
--
ALTER TABLE `watchlater`
  ADD CONSTRAINT `FK_watch_later_username` FOREIGN KEY (`username`) REFERENCES `users` (`username`),
  ADD CONSTRAINT `FK_watch_later_videoID` FOREIGN KEY (`videoID`) REFERENCES `videos` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
