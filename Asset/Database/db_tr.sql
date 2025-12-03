-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 03 Des 2025 pada 11.48
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_tr`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `favorites`
--

CREATE TABLE `favorites` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `poster_url` text NOT NULL,
  `rating` varchar(10) DEFAULT 'N/A',
  `imdbID` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `favorites`
--

INSERT INTO `favorites` (`id`, `title`, `poster_url`, `rating`, `imdbID`, `created_at`) VALUES
(1, 'Sha Ma Te, Wo Ai Ni (We Were Smart)', 'https://m.media-amazon.com/images/M/MV5BOTg5YWZhODEtYWJiZC00MDFjLWFmN2QtMTc5NjI3Njc5YzYyXkEyXkFqcGc@._V1_SX300.jpg', '8.0', 'tt13809752', '2025-11-24 04:18:49'),
(3, 'Home Alone', 'https://m.media-amazon.com/images/M/MV5BNzNmNmQ2ZDEtMTc1MS00NjNiLThlMGUtZmQxNTg1Nzg5NWMzXkEyXkFqcGc@._V1_SX300.jpg', '8.0', 'tt0099785', '2025-11-24 05:58:53'),
(5, 'Almost Human', 'https://m.media-amazon.com/images/M/MV5BMzQ1NDQ3MjUxOF5BMl5BanBnXkFtZTgwMTY2MDczMDE@._V1_SX300.jpg', '8.0', 'tt2654580', '2025-11-24 06:01:17'),
(6, 'The Human Centipede 2 (Full Sequence)', 'https://m.media-amazon.com/images/M/MV5BMjkwMDI0NjA5OV5BMl5BanBnXkFtZTcwODAxODI4Ng@@._V1_SX300.jpg', '8.0', 'tt1530509', '2025-11-24 06:01:21'),
(7, 'The Human Stain', 'https://m.media-amazon.com/images/M/MV5BMTk5MjQyNTcxNV5BMl5BanBnXkFtZTcwMjcwNDAwMQ@@._V1_SX300.jpg', '8.0', 'tt0308383', '2025-11-24 06:01:23'),
(8, 'Human Planet', 'https://m.media-amazon.com/images/M/MV5BMDYxN2U4YjEtZmZjYS00NmJkLTg3Y2EtZjFmNmFiNmExMDMwXkEyXkFqcGc@._V1_SX300.jpg', '9.9', 'tt1806234', '2025-11-24 06:01:25'),
(10, 'Bila Esok Ibu Tiada', 'https://m.media-amazon.com/images/M/MV5BNGNjNDUwZjMtODI0Ny00ODY3LTkyODEtZDY0YTZjNjVjMmE3XkEyXkFqcGc@._V1_SX300.jpg', '8.0', 'tt31079741', '2025-11-24 06:07:49'),
(13, '1 Kakak 7 Ponakan', 'https://m.media-amazon.com/images/M/MV5BYWI0ZmNiZmEtYjdhZC00YjA0LWFjNDktZDQwMDczYjk2YTVlXkEyXkFqcGc@._V1_SX300.jpg', '8.0', 'tt32881480', '2025-11-24 06:11:11'),
(14, 'Sore: Wife from the Future', 'https://m.media-amazon.com/images/M/MV5BMmExZTcyZGUtN2Q4NC00NmFiLWI1NmQtOTg3OWRlMmE3OGVjXkEyXkFqcGc@._V1_SX300.jpg', '9.0', 'tt34548722', '2025-11-24 08:51:19'),
(15, 'Kukira Kau Rumah', 'https://m.media-amazon.com/images/M/MV5BZTliYWZhZjYtZTVjNi00NmJiLTgxNzItZTc1Njg4MzE0YzQ2XkEyXkFqcGc@._V1_SX300.jpg', '8.0', 'tt12351994', '2025-12-02 02:54:10');

-- --------------------------------------------------------

--
-- Struktur dari tabel `film`
--

CREATE TABLE `film` (
  `judul_film` varchar(255) NOT NULL,
  `genre` varchar(255) NOT NULL,
  `durasi` varchar(255) NOT NULL,
  `rating` varchar(255) NOT NULL,
  `gambar` text NOT NULL,
  `saran_umur` varchar(255) NOT NULL,
  `tanggal_rilis` date NOT NULL,
  `harga` decimal(10,2) NOT NULL,
  `deskripsi` text NOT NULL,
  `id_film` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user','kasir') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id_user`, `username`, `password`, `role`, `created_at`) VALUES
(1, 'admin', '', 'admin', '2025-12-02 03:13:22'),
(2, 'kasir', '', 'kasir', '2025-12-02 03:13:22'),
(3, 'user', '', 'user', '2025-12-02 03:13:22'),
(4, 'talita', '$2y$10$ErrR06l9bkgTQ.lN0cwGuOZ2/cYs6B0MVBpgqW.uxReetaqz4IRYm', 'user', '2025-12-02 03:13:52');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `imdbID` (`imdbID`);

--
-- Indeks untuk tabel `film`
--
ALTER TABLE `film`
  ADD PRIMARY KEY (`id_film`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `favorites`
--
ALTER TABLE `favorites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT untuk tabel `film`
--
ALTER TABLE `film`
  MODIFY `id_film` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
