-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 17, 2024 at 03:32 AM
-- Server version: 11.4.2-MariaDB-log
-- PHP Version: 8.3.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `loker_kerja`
--

-- --------------------------------------------------------

--
-- Table structure for table `applications`
--

CREATE TABLE `applications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `job_id` int(11) DEFAULT NULL,
  `applied_at` timestamp NULL DEFAULT current_timestamp(),
  `status` enum('pending','reviewed','accepted','rejected') DEFAULT 'pending',
  `resume_link` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `applications`
--

INSERT INTO `applications` (`id`, `user_id`, `job_id`, `applied_at`, `status`, `resume_link`) VALUES
(2, 4, 9, '2024-09-11 06:44:29', 'rejected', 'uploads/CV Gilang Artha Wijaya XII RPL.pdf'),
(3, 4, 6, '2024-09-12 04:55:46', 'pending', 'uploads/CV Gilang Artha Wijaya XII RPL.pdf'),
(4, 18, 9, '2024-09-12 05:06:50', 'reviewed', 'uploads/CV Gilang Artha Wijaya XII RPL.pdf'),
(5, 12, 7, '2024-09-17 03:14:47', 'pending', 'uploads/img002.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `job_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `comment` text NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `company` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`id`, `user_id`, `title`, `description`, `location`, `company`, `created_at`, `image`) VALUES
(6, 4, 'Searching for an senior programmer ', 'â€¢ Warga Negara Indonesia\r\nâ€¢ Pria / Wanita\r\nâ€¢ Usia 21 maksimal 42 tahun\r\nâ€¢ Pendidikan minimal SMA / SMK, D3, S1 & S2 semua jurusan\r\nâ€¢ Berbadan sehat jasmani maupun rohani\r\nâ€¢ Diutamakan berpengalaman\r\nâ€¢ Non pengalaman boleh melamar\r\nâ€¢ Mempunyai kemampuan analisa yang baik\r\nâ€¢ Memiliki kemampuan dan dapat berkomunikasi dengan baik\r\nâ€¢ Teliti,disiplin dan bertanggung jawab serta motivasi kerja tinggi\r\nâ€¢ Dapat bekerja secara individu maupun dalam tim', 'https://maps.app.goo.gl/PuhWot1cTCbL1Y8M6', 'ICON+', '2024-09-01 07:29:49', 'thumbnail.jpeg'),
(7, 4, 'Searching for an senior programmer ', 'â€¢ Warga Negara Indonesia\r\nâ€¢ Pria / Wanita\r\nâ€¢ Usia 21 maksimal 42 tahun\r\nâ€¢ Pendidikan minimal SMA / SMK, D3, S1 & S2 semua jurusan\r\nâ€¢ Berbadan sehat jasmani maupun rohani\r\nâ€¢ Diutamakan berpengalaman\r\nâ€¢ Non pengalaman boleh melamar\r\nâ€¢ Mempunyai kemampuan analisa yang baik\r\nâ€¢ Memiliki kemampuan dan dapat berkomunikasi dengan baik\r\nâ€¢ Teliti,disiplin dan bertanggung jawab serta motivasi kerja tinggi\r\nâ€¢ Dapat bekerja secara individu maupun dalam tim', 'https://maps.app.goo.gl/PuhWot1cTCbL1Y8M6', 'ICON+', '2024-09-01 07:34:21', 'thumbnail.jpeg'),
(9, 4, 'pertambangan', 'tambang terus wir', 'Jl. Jenderal Sudirman No.7, Damai, Kec. Balikpapan Kota, Kota Balikpapan, Kalimantan Timur 76114', 'PT. TAMBANG SEJAHTERA', '2024-09-01 07:36:45', 'tambang.jpeg'),
(16, 7, 'asasda', 'sdasdad', 'dasdad', 'sdasda', '2024-09-02 01:01:32', ''),
(19, 11, 'pertambangan', 'aku nak kau lompat tali', 'samarinda', 'PT.BAIM AMAN', '2024-09-02 23:32:51', ''),
(20, 11, 'pertambangan', '1', 'BALIKPAPAN', 'PT. TAMBANG SEJAHTERA', '2024-09-07 13:34:49', '');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `profile_picture` varchar(255) DEFAULT 'default.png',
  `bio` text DEFAULT NULL,
  `role` enum('user','admin','employer') NOT NULL DEFAULT 'user',
  `status` enum('free','blocked') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `created_at`, `profile_picture`, `bio`, `role`, `status`) VALUES
(3, 'admin', '12345', 'yes', '2024-08-30 10:20:17', 'default.png', NULL, 'user', 'free'),
(4, 'admin1', '$2y$10$05qWUVjPYehyBzsk0BkGkOOwj5udKBqEugXky/F/vIvz1HgNge1YK', 'gilang4rthaw@gmail.com', '2024-08-30 10:21:19', '92002696_104552827880066_8564368421213437952_n.jpg', '', 'admin', 'free'),
(7, 'yahya', '$2y$10$CgZV/3z5He.hBfLvcuEZgOdTZg1EKL9ESR3UWFhS1E.aOFXqxhkcm', 'officialmax978@gmail.com', '2024-09-01 13:28:44', 'thumbnail.jpg', 'hai', 'admin', 'free'),
(8, 'max1', '$2y$10$SLqhjEKtat9AOk8pnxT9HuXEi8947PKWlFRWjHP8f//IA/YNVJWlS', 'max@gmail.com', '2024-09-01 13:29:04', 'default.png', NULL, 'admin', 'free'),
(11, 'baim', '$2y$10$WPU3B0Mc79qKT49jvm8M2.koN01k/p8kg/B2X2.XSSirasOC9r9OG', 'admin@gmail.com', '2024-09-02 00:57:33', 'default.png', NULL, 'user', 'free'),
(12, 'gilang2', '$2y$10$BWhBqL.TVRgaUneES8xEcefbH2xm1852meaPqt7Y7l/9ugw.VrrOq', 'officialmax978@gmail.com', '2024-09-02 01:14:55', 'default.png', NULL, 'user', 'free'),
(13, 'company1', 'password_hash', 'company1@example.com', '2024-09-08 06:29:09', 'default.png', NULL, 'employer', 'free'),
(14, 'jobseeker1', 'password_hash', 'jobseeker1@example.com', '2024-09-08 06:29:09', 'default.png', NULL, 'user', 'free'),
(16, 'yahya2', '$2y$10$A8EBaAEyFn87/2XhRuTVJ.lZA..TWT5aVj9PkXSy.BM8T/Fynqy9.', 'user@gmail.com', '2024-09-08 06:34:15', 'default.png', NULL, 'employer', 'free'),
(17, 'yahya3', '$2y$10$ml1fsChKZdf82cCOnT34S.HykJdAZQip3jO/vWEIn8ql1JnPrK2DW', 'user@gmail.com', '2024-09-08 13:15:22', 'default.png', NULL, 'user', 'free'),
(18, 'Hitam', '$2y$10$6cPvaFjmsGi7/xDPgKR2bO8Mq54490wziCMw92Bn9F0TFTGl883ZS', 'gilang4rthaw@gmail.com', '2024-09-12 05:06:24', 'Ash_Baby_Meme.jpg', 'aku hitam nooo', 'user', 'free');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `applications`
--
ALTER TABLE `applications`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`job_id`),
  ADD KEY `job_id` (`job_id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `job_id` (`job_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `applications`
--
ALTER TABLE `applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `applications`
--
ALTER TABLE `applications`
  ADD CONSTRAINT `applications_ibfk_1` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`),
  ADD CONSTRAINT `applications_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`),
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `jobs`
--
ALTER TABLE `jobs`
  ADD CONSTRAINT `jobs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
