-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 26, 2026 at 11:27 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `movie_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `Booking_ID` int(11) NOT NULL,
  `User_ID` bigint(20) UNSIGNED NOT NULL,
  `Show_ID` int(11) NOT NULL,
  `Booking_Date` datetime DEFAULT current_timestamp(),
  `Total_Price` decimal(10,2) NOT NULL,
  `Status` varchar(50) DEFAULT 'ปกติ'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `booking`
--

INSERT INTO `booking` (`Booking_ID`, `User_ID`, `Show_ID`, `Booking_Date`, `Total_Price`, `Status`) VALUES
(1, 1, 1, '2026-09-26 14:47:06', 360.00, 'ปกติ'),
(2, 1, 2, '2026-09-26 14:47:06', 240.00, 'ปกติ');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `movie`
--

CREATE TABLE `movie` (
  `Movie_ID` int(11) NOT NULL,
  `Title` varchar(200) NOT NULL,
  `Release_Date` date DEFAULT NULL,
  `Duration` int(11) DEFAULT NULL,
  `Genre` varchar(100) DEFAULT NULL,
  `Director` varchar(150) DEFAULT NULL,
  `Actors` text DEFAULT NULL,
  `Synopsis` text DEFAULT NULL,
  `Poster` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `movie`
--

INSERT INTO `movie` (`Movie_ID`, `Title`, `Release_Date`, `Duration`, `Genre`, `Director`, `Actors`, `Synopsis`, `Poster`) VALUES
(1, 'Avatar: The Way of Water', '2022-12-14', 192, 'Sci-Fi / Action', 'James Cameron', 'Sam Worthington, Zoe Saldana', 'เรื่องราวของครอบครัวซัลลีและการต่อสู้เพื่อเอาชีวิตรอ', 'Avatar_The_Way_of_Water.png');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `seat`
--

CREATE TABLE `seat` (
  `Seat_ID` int(11) NOT NULL,
  `Theater_ID` int(11) NOT NULL,
  `seat_row` varchar(5) NOT NULL,
  `seat_number` int(11) NOT NULL,
  `Seat_No` varchar(10) NOT NULL,
  `Seat_Type` varchar(100) DEFAULT NULL,
  `pos_x` int(11) DEFAULT 0,
  `pos_y` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `seat`
--

INSERT INTO `seat` (`Seat_ID`, `Theater_ID`, `seat_row`, `seat_number`, `Seat_No`, `Seat_Type`, `pos_x`, `pos_y`) VALUES
(1, 1, 'A', 1, 'A1', 'Deluxe - 180 บาท', 1, 1),
(2, 1, 'A', 2, 'A2', 'Deluxe - 180 บาท', 2, 1),
(3, 1, 'A', 3, 'A3', 'Deluxe - 180 บาท', 3, 1),
(4, 1, 'A', 4, 'A4', 'Deluxe - 180 บาท', 4, 1),
(5, 1, 'A', 5, 'A5', 'Deluxe - 180 บาท', 5, 1),
(6, 1, 'A', 6, 'A6', 'Deluxe - 180 บาท', 6, 1),
(7, 1, 'A', 7, 'A7', 'Deluxe - 180 บาท', 7, 1),
(8, 1, 'A', 8, 'A8', 'Deluxe - 180 บาท', 8, 1),
(9, 1, 'B', 1, 'B1', 'Deluxe - 180 บาท', 1, 2),
(10, 1, 'B', 2, 'B2', 'Deluxe - 180 บาท', 2, 2),
(11, 1, 'B', 3, 'B3', 'Deluxe - 180 บาท', 3, 2),
(12, 1, 'B', 4, 'B4', 'Deluxe - 180 บาท', 4, 2),
(13, 1, 'B', 5, 'B5', 'Deluxe - 180 บาท', 5, 2),
(14, 1, 'B', 6, 'B6', 'Deluxe - 180 บาท', 6, 2),
(15, 1, 'B', 7, 'B7', 'Deluxe - 180 บาท', 7, 2),
(16, 1, 'B', 8, 'B8', 'Deluxe - 180 บาท', 8, 2),
(17, 1, 'C', 1, 'C1', 'Premium - 240 บาท', 1, 3),
(18, 1, 'C', 2, 'C2', 'Premium - 240 บาท', 2, 3),
(19, 1, 'C', 3, 'C3', 'Premium - 240 บาท', 3, 3),
(20, 1, 'C', 4, 'C4', 'Premium - 240 บาท', 4, 3),
(21, 1, 'C', 5, 'C5', 'Premium - 240 บาท', 5, 3),
(22, 1, 'C', 6, 'C6', 'Premium - 240 บาท', 6, 3),
(23, 1, 'C', 7, 'C7', 'Premium - 240 บาท', 7, 3),
(24, 1, 'C', 8, 'C8', 'Premium - 240 บาท', 8, 3),
(25, 1, 'D', 1, 'D1', 'VIP - 350 บาท', 1, 4),
(26, 1, 'D', 2, 'D2', 'VIP - 350 บาท', 2, 4),
(27, 1, 'D', 3, 'D3', 'VIP - 350 บาท', 3, 4),
(28, 1, 'D', 4, 'D4', 'VIP - 350 บาท', 4, 4),
(29, 1, 'D', 5, 'D5', 'VIP - 350 บาท', 5, 4),
(30, 1, 'D', 6, 'D6', 'VIP - 350 บาท', 6, 4),
(31, 1, 'D', 7, 'D7', 'VIP - 350 บาท', 7, 4),
(32, 1, 'D', 8, 'D8', 'VIP - 350 บาท', 8, 4);

-- --------------------------------------------------------

--
-- Table structure for table `showtime`
--

CREATE TABLE `showtime` (
  `Show_ID` int(11) NOT NULL,
  `Movie_ID` int(11) NOT NULL,
  `Theater_ID` int(11) NOT NULL,
  `Show_Date` date NOT NULL,
  `Show_Time` time NOT NULL,
  `Language` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `showtime`
--

INSERT INTO `showtime` (`Show_ID`, `Movie_ID`, `Theater_ID`, `Show_Date`, `Show_Time`, `Language`) VALUES
(1, 1, 1, '2026-06-10', '14:30:00', 'พากย์ไทย / SUB'),
(2, 1, 2, '2026-06-10', '18:00:00', 'เสียงอังกฤษ / SUB ไทย'),
(3, 1, 2, '2026-06-11', '12:00:00', 'พากย์ไทย'),
(4, 1, 3, '2026-06-11', '19:15:00', 'เสียงอังกฤษ / SUB ไทย');

-- --------------------------------------------------------

--
-- Table structure for table `theater`
--

CREATE TABLE `theater` (
  `Theater_ID` int(11) NOT NULL,
  `Theater_Location` varchar(200) NOT NULL,
  `Theater_Name` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `theater`
--

INSERT INTO `theater` (`Theater_ID`, `Theater_Location`, `Theater_Name`) VALUES
(1, 'กรุงเทพฯ (สยามพารากอน)', 'โรงภาพยนตร์ที่ 1 (IMAX)'),
(2, 'กรุงเทพฯ (เซ็นทรัลเวิลด์)', 'โรงภาพยนตร์ที่ 2 (Normal)'),
(3, 'ขอนแก่น (เซ็นทรัล ขอนแก่น)', 'โรงภาพยนตร์ที่ 1 (VIP)');

-- --------------------------------------------------------

--
-- Table structure for table `ticket`
--

CREATE TABLE `ticket` (
  `Ticket_ID` int(11) NOT NULL,
  `Booking_ID` int(11) NOT NULL,
  `Seat_ID` int(11) NOT NULL,
  `Ticket_Code` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ticket`
--

INSERT INTO `ticket` (`Ticket_ID`, `Booking_ID`, `Seat_ID`, `Ticket_Code`) VALUES
(1, 1, 1, 'TK-2225'),
(2, 1, 2, 'TK-2226');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `is_admin` tinyint(1) DEFAULT 0,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `is_admin`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'max', 'maxmac13333@gmail.com', 1, NULL, '$2y$12$rIUJQp5y.uU9CofFhhmjx.28PRcBB0BD9buj/qydj4dEbU9ZBrSku', NULL, '2026-09-25 19:01:24', '2026-09-25 19:01:24'),
(2, 'kk', 'g@gmail.com', 0, NULL, '$2y$12$EmRMzRiqNqJSGyi0MIlT8.P.yIGoBBlz6zmVEMTqXlDUNFZf2dPaK', NULL, '2026-09-26 00:09:23', '2026-09-26 00:09:23');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`Booking_ID`),
  ADD KEY `User_ID` (`User_ID`),
  ADD KEY `Show_ID` (`Show_ID`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `movie`
--
ALTER TABLE `movie`
  ADD PRIMARY KEY (`Movie_ID`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `seat`
--
ALTER TABLE `seat`
  ADD PRIMARY KEY (`Seat_ID`),
  ADD KEY `Theater_ID` (`Theater_ID`);

--
-- Indexes for table `showtime`
--
ALTER TABLE `showtime`
  ADD PRIMARY KEY (`Show_ID`),
  ADD KEY `Movie_ID` (`Movie_ID`),
  ADD KEY `Theater_ID` (`Theater_ID`);

--
-- Indexes for table `theater`
--
ALTER TABLE `theater`
  ADD PRIMARY KEY (`Theater_ID`);

--
-- Indexes for table `ticket`
--
ALTER TABLE `ticket`
  ADD PRIMARY KEY (`Ticket_ID`),
  ADD UNIQUE KEY `Ticket_Code` (`Ticket_Code`),
  ADD KEY `Booking_ID` (`Booking_ID`),
  ADD KEY `Seat_ID` (`Seat_ID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `Booking_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `movie`
--
ALTER TABLE `movie`
  MODIFY `Movie_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `seat`
--
ALTER TABLE `seat`
  MODIFY `Seat_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `showtime`
--
ALTER TABLE `showtime`
  MODIFY `Show_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `theater`
--
ALTER TABLE `theater`
  MODIFY `Theater_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `ticket`
--
ALTER TABLE `ticket`
  MODIFY `Ticket_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `booking`
--
ALTER TABLE `booking`
  ADD CONSTRAINT `booking_ibfk_1` FOREIGN KEY (`User_ID`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `booking_ibfk_2` FOREIGN KEY (`Show_ID`) REFERENCES `showtime` (`Show_ID`) ON DELETE CASCADE;

--
-- Constraints for table `seat`
--
ALTER TABLE `seat`
  ADD CONSTRAINT `seat_ibfk_1` FOREIGN KEY (`Theater_ID`) REFERENCES `theater` (`Theater_ID`) ON DELETE CASCADE;

--
-- Constraints for table `showtime`
--
ALTER TABLE `showtime`
  ADD CONSTRAINT `showtime_ibfk_1` FOREIGN KEY (`Movie_ID`) REFERENCES `movie` (`Movie_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `showtime_ibfk_2` FOREIGN KEY (`Theater_ID`) REFERENCES `theater` (`Theater_ID`) ON DELETE CASCADE;

--
-- Constraints for table `ticket`
--
ALTER TABLE `ticket`
  ADD CONSTRAINT `ticket_ibfk_1` FOREIGN KEY (`Booking_ID`) REFERENCES `booking` (`Booking_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `ticket_ibfk_2` FOREIGN KEY (`Seat_ID`) REFERENCES `seat` (`Seat_ID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
