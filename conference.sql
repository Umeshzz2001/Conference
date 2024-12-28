-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Dec 27, 2024 at 07:39 AM
-- Server version: 8.2.0
-- PHP Version: 8.2.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `conference`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

DROP TABLE IF EXISTS `attendance`;
CREATE TABLE IF NOT EXISTS `attendance` (
  `attendance_id` int NOT NULL AUTO_INCREMENT,
  `session_id` int NOT NULL,
  `participant_id` int NOT NULL,
  `attendance_time` datetime NOT NULL,
  PRIMARY KEY (`attendance_id`),
  KEY `participant_id` (`participant_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tracksandsessions`
--

DROP TABLE IF EXISTS `tracksandsessions`;
CREATE TABLE IF NOT EXISTS `tracksandsessions` (
  `track_id` int NOT NULL,
  `session_id` varchar(1) NOT NULL,
  `title_id` varchar(10) NOT NULL,
  `title` varchar(255) NOT NULL,
  `speaker` varchar(255) NOT NULL,
  `time` time NOT NULL,
  `venue` varchar(100) NOT NULL,
  `capacity` int NOT NULL,
  PRIMARY KEY (`title_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tracksandsessions`
--

INSERT INTO `tracksandsessions` (`track_id`, `session_id`, `title_id`, `title`, `speaker`, `time`, `venue`, `capacity`) VALUES
(1, 'A', '1A1', 'The Impact of Global Crude Oil Price Fluctuations on the Economy of Sri Lanka with a Special Reference to the Tourism and Agricultural Sectors', 'D.A.D. Lavindika, S.C. Mathugama, D.R.T. Jayasundara', '14:00:00', 'D1711', 100);

--
-- Triggers `tracksandsessions`
--
DROP TRIGGER IF EXISTS `generate_title_id`;
DELIMITER $$
CREATE TRIGGER `generate_title_id` BEFORE INSERT ON `tracksandsessions` FOR EACH ROW BEGIN
    DECLARE new_counter INT;

    -- Get the current max counter for the combination of track_id and session_id
    SELECT IFNULL(MAX(CAST(SUBSTRING_INDEX(title_id, '-', -1) AS UNSIGNED)), 0) + 1
    INTO new_counter
    FROM your_table_name
    WHERE track_id = NEW.track_id AND session_id = NEW.session_id;

    -- Generate the new title_id
    SET NEW.title_id = CONCAT(NEW.track_id, NEW.session_id, '-', new_counter);
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `participant_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `password` varchar(255) NOT NULL,
  `track` varchar(100) NOT NULL,
  `additionalRequest` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `profile_photo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `qr_code_picture` varchar(255) DEFAULT NULL,
  `admin_code` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`participant_id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`participant_id`, `name`, `email`, `phone`, `password`, `track`, `additionalRequest`, `profile_photo`, `qr_code_picture`, `admin_code`, `created_at`) VALUES
(5, 'umesh sandeepa', 'umeshsandeepa1@gmail.com', '+94 76 9829976', '$2y$10$5K2OTDiAOmG1wvTJQwFAqOtvlHuB7J1ucirnCRdx1FDDZd5v/WgIi', 'Track 01', '', NULL, 'uploads/qr_code_676ac20f974ed.png', 'admin001', '2024-12-24 14:15:43'),
(12, 'Heshan Tharushika', 'umeshsandeepa12@gmail.com', '+94 77 6427769', '$2y$10$mn9JxYraiNJHSA0bGwLdiesKa/XU6yrMH94OTV1MAOkUWE4TqVXea', 'Track 01', '', NULL, 'uploads/qr_code_676d8ee45c047.png', NULL, '2024-12-26 17:14:12');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
