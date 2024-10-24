-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Oct 23, 2024 at 09:10 PM
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
-- Database: `event_registration`
--

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `event_name` varchar(255) DEFAULT NULL,
  `event_description` text DEFAULT NULL,
  `event_date` date DEFAULT NULL,
  `max_participants` int(11) DEFAULT NULL,
  `status` enum('open','closed','cancelled') DEFAULT 'open',
  `image` varchar(255) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `total_tickets` int(11) NOT NULL,
  `ticket_price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `event_name`, `event_description`, `event_date`, `max_participants`, `status`, `image`, `location`, `total_tickets`, `ticket_price`) VALUES
(3, 'festival kuliner', 'kuliner enak perut kenyang', '2024-10-07', 70, 'open', 'Poster Festival Kuliner Nusantara Ceria _ Template Canva.jpg', 'Gedung DPR', 70, 15000.00),
(4, 'Festival bunga', 'Bunga mawar mekar di taman ', '2024-10-15', 83, 'open', 'Spring Festival Flyer by styleWish on DeviantArt.jpg', 'Jalan Panunggangan no.10', 83, 20000.00),
(5, 'Festival Otomotif', 'Mobil keren dan mewah pokoknya, tapi dijamin bikin kantong kering kalo liat harganya', '2024-10-26', 100, 'open', 'Haval Sale Promo.jpg', 'Grand Indonesia', 100, 75000.00),
(6, 'MAM (Musik Anak Muda)', 'Mari berpesta ria dengan musik kekikinian yang akan membuat anda bergoyang', '2024-10-31', 100, 'open', 'download (7).jpg', 'Lapangan tembak Poris Plawad', 100, 175000.00),
(7, 'Coffee Fest', 'Festival dengan berbagai macam jenis kopi', '2024-10-31', 77, 'open', 'Banner Brasil Coffee Week 2023.jpg', 'Mall Kelapa Gading', 77, 35000.00),
(8, 'Festival Motor', 'Motor keren dan macho', '2024-10-31', 87, 'open', 'Motorbike Creative _ Social Media Post _ Adobe Photoshop.jpg', 'Alun-alun Banten', 87, 45000.00);

-- --------------------------------------------------------

--
-- Table structure for table `registrations`
--

CREATE TABLE `registrations` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `event_id` int(11) DEFAULT NULL,
  `ticket_count` int(11) NOT NULL DEFAULT 1,
  `payment_method` enum('ovo','gopay','dana') NOT NULL,
  `phone` varchar(13) NOT NULL,
  `amount_paid` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `registrations`
--

INSERT INTO `registrations` (`id`, `user_id`, `event_id`, `ticket_count`, `payment_method`, `phone`, `amount_paid`) VALUES
(11, 4, 3, 1, 'ovo', '', 0.00),
(15, 4, 4, 1, 'dana', '081295523321', 10000.00),
(16, 10, 3, 3, 'ovo', '081177777777', 20000.00),
(18, 13, 3, 1, 'ovo', '08187322338', 15000.00),
(19, 13, 8, 1, 'ovo', '0887829967', 45000.00);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `profile_photo` varchar(255) DEFAULT 'uploads/default.png',
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_token_expiry` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `profile_photo`, `reset_token`, `reset_token_expiry`) VALUES
(1, 'ahmad', 'ahmad@gmail.com', '$2y$10$ssUYyHVIYo3NWm/IY616w.rTpERC0D72RVNFK11.BkbRFfM2BCElK', 'user', 'uploads/default.png', NULL, NULL),
(2, 'Bayu', 'bayu@gmail.com', '$2y$10$JRPd/2jMM6BoLTm4LfrLp.LRsH9dN0WMAhhB1pUYwrrZNoTqR53p6', 'admin', 'uploads/default.png', 'a6959050380c7af1d14f993ae0de108cf947e7c15e49d4c74d2b4725e8b9336fc143b75007df97a8023f36afe2141e2c7b19', '2024-10-10 19:21:03'),
(3, 'Bagas', 'bagas@gmail.com', '$2y$10$sN2Aqf5qmv7thl8PEBWJ9uDrz0qsZyVj9U3znx5oovnWL5YRDwvuu', 'user', 'uploads/default.png', '51225bc5446ff8f8eab6de4c7d7868d194b9e517fc76421249359362b9de147bd5d914974010f8bef584a268d9e4e80c7fdb', '2024-10-22 23:30:49'),
(4, 'alex', 'alex@gmail.com', '$2y$10$NEcg4VDFiNarcfhGAS58s.i5Nv6rL9Tg54WUDYJ7nrljybnDqhVtG', 'user', '6700f0dc23178.jpg', NULL, NULL),
(5, 'agung', 'agung@gmail.com', '$2y$10$PfvmSU6Jd0.D2aSNib9R1ugUNT51sof1aS8sJh3C0e5tvMmQbPBji', 'user', '6700374fea889.jpg', NULL, NULL),
(6, 'dasha', 'dasha@gmail.com', '$2y$10$5KR4oQWJjBE1w1hNjcwl9Ov1cban7rKw6eDhQ6eYEuvEVipUe5QLa', 'user', '6702a191cbc8c.jpg', NULL, NULL),
(10, 'mieko', 'mieko@gmail.com', '$2y$10$h50IFs.uvFGuxTVHcd5a3eKwfQoBooEltqwRkyELAXqOLK1si6Xee', 'user', '67166a8b57504.jpg', NULL, NULL),
(12, 'nande', 'nande@gmail.com', '$2y$10$zaIpZXEfUp59sA9ULzH8neuPMrXEmrywL9UXMFpEzDOOGEiIcdrXK', 'user', 'uploads/default.png', NULL, NULL),
(13, 'sule', 'sule@gmail.com', '$2y$10$lrr4uCfew2vertqR.CA5je2lLn.RBF.6SeYhbPBUuMIOKTBd9ZFUK', 'user', 'uploads/default.png', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `registrations`
--
ALTER TABLE `registrations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user_id` (`user_id`),
  ADD KEY `fk_event_id` (`event_id`);

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
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `registrations`
--
ALTER TABLE `registrations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `registrations`
--
ALTER TABLE `registrations`
  ADD CONSTRAINT `fk_event_id` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `registrations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `registrations_ibfk_2` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
