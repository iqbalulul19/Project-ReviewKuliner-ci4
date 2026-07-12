-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 12, 2026 at 04:30 PM
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
-- Database: `db_kuliner`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `created_at`) VALUES
(1, 'Warteg & Nasi', '', '2026-06-07 16:01:58'),
(2, 'Cafe & Kopi', '', '2026-06-07 16:01:58'),
(3, 'Street Food', '', '2026-06-07 16:01:58'),
(4, 'Mie & Bakso', '', '2026-06-07 16:01:58');

-- --------------------------------------------------------

--
-- Table structure for table `favorites`
--

CREATE TABLE `favorites` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `place_id` int(11) UNSIGNED NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `version` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `batch` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `version`, `class`, `group`, `namespace`, `time`, `batch`) VALUES
(1, '2026-04-21-143927', 'App\\Database\\Migrations\\CreateCategories', 'default', 'App', 1780848112, 1),
(2, '2026-04-21-143927', 'App\\Database\\Migrations\\CreatePlaces', 'default', 'App', 1780848112, 1),
(3, '2026-04-27-131737', 'App\\Database\\Migrations\\CreatePlacePhotos', 'default', 'App', 1780848112, 1),
(4, '2026-04-28-052521', 'App\\Database\\Migrations\\CreateReviews', 'default', 'App', 1780848112, 1),
(5, '2026-04-28-063547', 'App\\Database\\Migrations\\CreateAdmins', 'default', 'App', 1780848112, 1),
(6, '2026-04-28-120049', 'App\\Database\\Migrations\\UpdateUserAndReview', 'default', 'App', 1780848112, 1),
(7, '2026-05-18-025632', 'App\\Database\\Migrations\\AddCategoryToPlaces', 'default', 'App', 1780848112, 1),
(8, '2026-06-07-140528', 'App\\Database\\Migrations\\CreateTagsAndPlaceTags', 'default', 'App', 1780848112, 1),
(9, '2026-06-24-125106', 'App\\Database\\Migrations\\CreateFavorites', 'default', 'App', 1782621696, 2),
(10, '2026-06-28-044055', 'App\\Database\\Migrations\\CreateVouchers', 'default', 'App', 1782621696, 2);

-- --------------------------------------------------------

--
-- Table structure for table `places`
--

CREATE TABLE `places` (
  `id` int(11) UNSIGNED NOT NULL,
  `category_id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `address` text NOT NULL,
  `latitude` decimal(10,8) NOT NULL,
  `longitude` decimal(11,8) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `places`
--

INSERT INTO `places` (`id`, `category_id`, `name`, `description`, `address`, `latitude`, `longitude`, `created_at`, `status`) VALUES
(1, 1, 'Warteg Kharisma Bahari Nakula', NULL, 'Jl. Nakula I No.10', -6.98210000, 110.40910000, '2026-06-07 16:01:58', 'approved'),
(2, 1, 'Nasi Ayam Bu Pini', NULL, 'Jl. Pemuda', -6.98150000, 110.41020000, '2026-06-07 16:01:58', 'approved'),
(3, 1, 'Ayam Geprek Bensu Tugu Muda', NULL, 'Sekitar Tugu Muda', -6.98320000, 110.40850000, '2026-06-07 16:01:58', 'approved'),
(4, 1, 'Penyetan Mas Kobis', NULL, 'Jl. Sadewa', -6.98180000, 110.40750000, '2026-06-07 16:01:58', 'approved'),
(5, 1, 'Nasi Padang Murah Meriah', NULL, 'Jl. Pendrikan Lor', -6.98050000, 110.40980000, '2026-06-07 16:01:58', 'approved'),
(6, 2, 'Kopi Janji Jiwa Udinus', NULL, 'Gedung G Udinus', -6.98250000, 110.40900000, '2026-06-07 16:01:58', 'approved'),
(7, 2, 'Kenangan Mantan Cafe', NULL, 'Jl. Imam Bonjol', -6.98010000, 110.41100000, '2026-06-07 16:01:58', 'approved'),
(8, 2, 'Angkringan Kopi Joss', NULL, 'Pinggir Kali Garang', -6.98400000, 110.40700000, '2026-06-07 16:01:58', 'approved'),
(10, 2, 'Es Teh Indonesia', NULL, 'Depan Kampus', -6.98280000, 110.40950000, '2026-06-07 16:01:58', 'approved'),
(11, 3, 'Telur Gulung SD Pendrikan', NULL, 'Dekat SD Pendrikan', -6.98080000, 110.40880000, '2026-06-07 16:01:58', 'approved'),
(13, 3, 'Cilok Kuah Pedas Gila', NULL, 'Jl. Nakula Raya', -6.98190000, 110.40800000, '2026-06-07 16:01:58', 'approved'),
(16, 4, 'Mie Ayam Bangka', NULL, 'Jl. Hasanudin', -6.97900000, 110.41200000, '2026-06-07 16:01:58', 'approved'),
(17, 4, 'Bakso Sapi Urat Joss', NULL, 'Perempatan Nakula', -6.98100000, 110.40990000, '2026-06-07 16:01:58', 'approved'),
(20, 4, 'Bakmi Jowo Pak Gareng', NULL, 'Jl. Wotgandul', -6.98000000, 110.41300000, '2026-06-07 16:01:58', 'approved'),
(21, 4, 'Mie Jebew Super Pedas', NULL, 'Kantin Kampus', -6.98240000, 110.40930000, '2026-06-07 16:01:58', 'approved'),
(24, 1, 'Geprek Sada', NULL, 'Jl.Nakula Raya', -6.22535240, 106.90753100, NULL, 'approved'),
(29, 4, 'Mie gacoan', NULL, 'Jl. Pemuda Semarang', -6.98227140, 110.41150860, NULL, 'approved');

-- --------------------------------------------------------

--
-- Table structure for table `place_photos`
--

CREATE TABLE `place_photos` (
  `id` int(11) UNSIGNED NOT NULL,
  `place_id` int(11) UNSIGNED NOT NULL,
  `photo` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `place_tags`
--

CREATE TABLE `place_tags` (
  `place_id` int(11) UNSIGNED NOT NULL,
  `tag_id` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `place_tags`
--

INSERT INTO `place_tags` (`place_id`, `tag_id`) VALUES
(1, 2),
(4, 2),
(4, 3),
(6, 1),
(6, 5),
(7, 1),
(7, 6),
(8, 3),
(8, 4),
(24, 2),
(24, 5),
(29, 2),
(29, 7);

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) UNSIGNED NOT NULL,
  `place_id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `rating` int(1) NOT NULL,
  `comment` text DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `place_id`, `user_id`, `rating`, `comment`, `photo`, `created_at`) VALUES
(1, 3, 1, 5, 'Geprek terenak sih ini', '1783825245_7264c9c580d90708aa9d.jpg', '2026-07-12 03:00:45'),
(2, 3, 2, 5, 'enak bangett', '1783825300_8a375e33ca4cfd46cf6c.jpg', '2026-07-12 03:01:40');

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE `tags` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Aesthetic', '2026-06-07 16:01:58', '2026-06-07 16:01:58'),
(2, 'Murah Meriah', '2026-06-07 16:01:58', '2026-06-07 16:01:58'),
(3, 'Lesehan', '2026-06-07 16:01:58', '2026-06-07 16:01:58'),
(4, 'Outdoor', '2026-06-07 16:01:58', '2026-06-07 16:01:58'),
(5, 'Wifi Cepat', '2026-06-07 16:01:58', '2026-06-07 16:01:58'),
(6, 'Live Music', '2026-06-07 16:01:58', '2026-06-07 16:01:58'),
(7, 'Instagramable', '2026-06-07 16:01:58', '2026-06-07 16:01:58');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `password`, `role`, `created_at`) VALUES
(1, 'Admin', 'admin', '$2y$10$4Zl7MrYaWnrS/R07KHj7GOXLvtyHfTDjXbKHXZKfbsyEY/xG28SQW', 'admin', NULL),
(2, 'Iqbal Ulul', 'iqbal', '$2y$10$Dw0LI3ss2lfBj5jvJIV9xuD06zk1vnjwlXYKnu5Ajuglpjmhh47hC', 'user', NULL),
(3, 'ian', 'ian', '$2y$10$oLGQBJ9853gdWu2iEV7Sae/GbvPjeYsb2U4Zi/Ad4xwIIKsa/4lH.', 'user', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_vouchers`
--

CREATE TABLE `user_vouchers` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `voucher_id` int(11) UNSIGNED NOT NULL,
  `order_id` varchar(50) NOT NULL,
  `status` enum('pending','paid','used','expired') NOT NULL DEFAULT 'pending',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_vouchers`
--

INSERT INTO `user_vouchers` (`id`, `user_id`, `voucher_id`, `order_id`, `status`, `created_at`, `updated_at`) VALUES
(1, 3, 1, 'INV-1782707579', 'pending', '2026-06-29 04:32:59', '2026-06-29 04:32:59'),
(2, 3, 1, 'INV-1782707777', 'pending', '2026-06-29 04:36:17', '2026-06-29 04:36:17'),
(3, 3, 1, 'INV-1782707870', 'pending', '2026-06-29 04:37:50', '2026-06-29 04:37:50'),
(4, 3, 1, 'INV-1782713111', 'paid', '2026-06-29 06:05:11', '2026-06-29 06:30:43'),
(5, 3, 1, 'INV-1782713478', 'paid', '2026-06-29 06:11:18', '2026-06-29 06:11:53'),
(6, 3, 1, 'INV-1782714352', 'paid', '2026-06-29 06:25:52', '2026-06-29 06:26:35'),
(7, 3, 1, 'INV-1782715011', 'paid', '2026-06-29 06:36:51', '2026-06-29 06:37:48'),
(8, 3, 1, 'INV-1782715151', 'paid', '2026-06-29 06:39:11', '2026-06-29 06:39:54'),
(9, 1, 1, 'INV-1782821970', 'pending', '2026-06-30 12:19:31', '2026-06-30 12:19:31'),
(10, 1, 1, 'INV-1782822184', 'pending', '2026-06-30 12:23:04', '2026-06-30 12:23:04'),
(11, 1, 1, 'INV-1782822262', 'pending', '2026-06-30 12:24:22', '2026-06-30 12:24:22'),
(12, 3, 1, 'INV-1782822416', '', '2026-06-30 12:26:56', '2026-06-30 13:21:30'),
(13, 3, 1, 'INV-1782823251', 'paid', '2026-06-30 12:40:51', '2026-06-30 12:41:41'),
(14, 3, 1, 'INV-1782823363', 'paid', '2026-06-30 12:42:43', '2026-06-30 12:43:11'),
(15, 3, 1, 'INV-1782825373', '', '2026-06-30 13:16:13', '2026-06-30 13:16:31'),
(16, 3, 1, 'INV-1782825701', 'paid', '2026-06-30 13:21:41', '2026-06-30 13:22:50'),
(17, 2, 1, 'INV-1783757565', 'paid', '2026-07-11 08:12:45', '2026-07-11 08:13:40'),
(18, 2, 1, 'INV-1783829782', 'paid', '2026-07-12 04:16:22', '2026-07-12 04:18:13');

-- --------------------------------------------------------

--
-- Table structure for table `vouchers`
--

CREATE TABLE `vouchers` (
  `id` int(11) UNSIGNED NOT NULL,
  `place_id` int(11) UNSIGNED NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` int(11) NOT NULL,
  `discount_value` int(11) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `expired_at` date DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vouchers`
--

INSERT INTO `vouchers` (`id`, `place_id`, `title`, `description`, `price`, `discount_value`, `stock`, `expired_at`, `created_at`, `updated_at`) VALUES
(1, 3, 'Diskon Makan Siang', 'Potongan harga untuk menu ayam', 15000, 5000, 30, '2026-12-31', NULL, '2026-07-11 08:01:06'),
(3, 1, 'Diskon akhir bulan', 'Minimal pembelian 50rb', 10000, 5000, 20, '2026-07-18', '2026-07-12 04:22:51', '2026-07-12 04:22:51');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`id`),
  ADD KEY `favorites_user_id_foreign` (`user_id`),
  ADD KEY `favorites_place_id_foreign` (`place_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `places`
--
ALTER TABLE `places`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `place_photos`
--
ALTER TABLE `place_photos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `place_tags`
--
ALTER TABLE `place_tags`
  ADD KEY `place_tags_tag_id_foreign` (`tag_id`),
  ADD KEY `place_id_tag_id` (`place_id`,`tag_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_vouchers`
--
ALTER TABLE `user_vouchers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_vouchers_user_id_foreign` (`user_id`),
  ADD KEY `user_vouchers_voucher_id_foreign` (`voucher_id`);

--
-- Indexes for table `vouchers`
--
ALTER TABLE `vouchers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vouchers_place_id_foreign` (`place_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `favorites`
--
ALTER TABLE `favorites`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `places`
--
ALTER TABLE `places`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `place_photos`
--
ALTER TABLE `place_photos`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user_vouchers`
--
ALTER TABLE `user_vouchers`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `vouchers`
--
ALTER TABLE `vouchers`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `favorites_place_id_foreign` FOREIGN KEY (`place_id`) REFERENCES `places` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `favorites_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `place_tags`
--
ALTER TABLE `place_tags`
  ADD CONSTRAINT `place_tags_place_id_foreign` FOREIGN KEY (`place_id`) REFERENCES `places` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `place_tags_tag_id_foreign` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_vouchers`
--
ALTER TABLE `user_vouchers`
  ADD CONSTRAINT `user_vouchers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_vouchers_voucher_id_foreign` FOREIGN KEY (`voucher_id`) REFERENCES `vouchers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `vouchers`
--
ALTER TABLE `vouchers`
  ADD CONSTRAINT `vouchers_place_id_foreign` FOREIGN KEY (`place_id`) REFERENCES `places` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
