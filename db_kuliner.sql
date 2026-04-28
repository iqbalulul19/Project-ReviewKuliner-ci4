-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 28 Apr 2026 pada 14.51
-- Versi server: 10.4.21-MariaDB
-- Versi PHP: 8.0.10

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
-- Struktur dari tabel `categories`
--

CREATE TABLE `categories` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `version` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `batch` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `version`, `class`, `group`, `namespace`, `time`, `batch`) VALUES
(1, '2026-04-21-143927', 'App\\Database\\Migrations\\CreateCategories', 'default', 'App', 1777300016, 1),
(2, '2026-04-21-143927', 'App\\Database\\Migrations\\CreatePlaces', 'default', 'App', 1777300016, 1),
(3, '2026-04-27-131737', 'App\\Database\\Migrations\\CreatePlacePhotos', 'default', 'App', 1777300016, 1),
(4, '2026-04-28-052521', 'App\\Database\\Migrations\\CreateReviews', 'default', 'App', 1777358454, 2),
(5, '2026-04-28-063547', 'App\\Database\\Migrations\\CreateAdmins', 'default', 'App', 1777358454, 2),
(6, '2026-04-28-120049', 'App\\Database\\Migrations\\UpdateUserAndReview', 'default', 'App', 1777377778, 3);

-- --------------------------------------------------------

--
-- Struktur dari tabel `places`
--

CREATE TABLE `places` (
  `id` int(11) UNSIGNED NOT NULL,
  `category_id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `address` text NOT NULL,
  `latitude` decimal(10,8) NOT NULL,
  `longitude` decimal(11,8) NOT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `places`
--

INSERT INTO `places` (`id`, `category_id`, `name`, `description`, `address`, `latitude`, `longitude`, `created_at`) VALUES
(4, 1, 'Mie gacoan', NULL, 'Jl. Imam Bonjol, Semarang', '-6.97140060', '110.41924480', NULL),
(5, 1, 'Nasi Padang Murah', NULL, 'Jl. Karangayu Semarang', '-6.97948600', '110.39486240', NULL),
(7, 1, 'Loenpia Mbak Lien', NULL, 'Jl. Pemuda Semarang', '-6.97301910', '110.42094680', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `place_photos`
--

CREATE TABLE `place_photos` (
  `id` int(11) UNSIGNED NOT NULL,
  `place_id` int(11) UNSIGNED NOT NULL,
  `photo` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `place_photos`
--

INSERT INTO `place_photos` (`id`, `place_id`, `photo`) VALUES
(1, 1, '1777355725_b2d1d309e9857c3151c6.jpeg'),
(2, 2, '1777356223_ea7945a69439f86b07e5.jpeg'),
(3, 4, '1777366061_67221730280ce9245904.jpeg'),
(4, 5, '1777366144_4ef55dee9010c3e29935.avif'),
(6, 7, '1777372089_5083181e88d150ab21e8.jpeg');

-- --------------------------------------------------------

--
-- Struktur dari tabel `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) UNSIGNED NOT NULL,
  `place_id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED DEFAULT NULL,
  `rating` int(1) NOT NULL,
  `comment` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `reviews`
--

INSERT INTO `reviews` (`id`, `place_id`, `user_id`, `rating`, `comment`, `created_at`) VALUES
(1, 5, NULL, 5, 'rekomen banget, enak dan murah', '2026-04-28 09:16:13'),
(2, 4, NULL, 5, 'enak banget cuy', '2026-04-28 10:08:27');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `password`, `role`) VALUES
(1, NULL, 'admin', '$2y$10$9oYk5BhxgdlbTHXqmCCTW.GbNaBU8y9upoBqiY9mr09SgDElYsd7W', 'admin'),
(2, 'Iqbal', 'iqbal', '$2y$10$7DgDEv3cfe/KOo/GMv.ZzudL1UFiF3.HLbioSlNyOkbOMQqnm3cna', 'user');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `places`
--
ALTER TABLE `places`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `place_photos`
--
ALTER TABLE `place_photos`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `places`
--
ALTER TABLE `places`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `place_photos`
--
ALTER TABLE `place_photos`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
