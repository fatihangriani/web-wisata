-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 14, 2025 at 09:48 AM
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
-- Database: `db_wis`
--

-- --------------------------------------------------------

--
-- Table structure for table `favorit`
--

CREATE TABLE `favorit` (
  `id_favorit` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_wisata` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `favorit`
--

INSERT INTO `favorit` (`id_favorit`, `id_user`, `id_wisata`) VALUES
(7, 2, 3);

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id_kategori` int(11) NOT NULL,
  `nama_kategori` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id_kategori`, `nama_kategori`) VALUES
(1, 'gunung'),
(2, 'pantai'),
(4, 'sejarah');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `username`, `email`, `password`, `role`) VALUES
(1, 'admin', 'admin@gmail.com', '827ccb0eea8a706c4c34a16891f84e7b', 'admin'),
(2, 'fatih', 'fatih@gmail.com', 'e821a8bfc2c786f275e5d5ea94d519a7', 'user'),
(3, 'ayu', 'ayu@gmail.con', '202cb962ac59075b964b07152d234b70', 'user'),
(5, 'fafa', 'fafa@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 'user');

-- --------------------------------------------------------

--
-- Table structure for table `wisata`
--

CREATE TABLE `wisata` (
  `id_wisata` int(11) NOT NULL,
  `nama_wisata` varchar(255) NOT NULL,
  `deskripsi` varchar(255) NOT NULL,
  `lokasi` varchar(255) NOT NULL,
  `gambar` text NOT NULL,
  `rating` decimal(3,2) NOT NULL,
  `id_kategori` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wisata`
--

INSERT INTO `wisata` (`id_wisata`, `nama_wisata`, `deskripsi`, `lokasi`, `gambar`, `rating`, `id_kategori`) VALUES
(1, 'Gunung Bromo', 'Gunung Bromo adalah gunung berapi aktif di Jawa Timur, terkenal dengan keindahan alamnya berupa lautan pasir luas, kawah berasap, dan pemandangan matahari terbit yang menakjubkan. Gunung ini merupakan bagian dari Taman Nasional Bromo Tengger Semeru dan me', 'Jawa Timur dan berada di perbatasan empat\r\nKabupaten: Probolinggo, Pasuruan, Lumajang, dan Malang', 'bromo.webp', 4.00, 1),
(2, 'Gunung Kawah Ijen ', 'Gunung Kawah Ijen adalah gunung berapi aktif di perbatasan Jawa Timur dengan ketinggian 2.386 mdpl yang terkenal dengan fenomena api biru (blue fire) dan danau kawah hijau toska yang sangat asam. Selain terkenal dengan pemandangan uniknya, Ijen juga menja', 'Terletak di perbatasan Kabupaten Banyuwangi dan Bondowoso, Jawa Timur, dengan ketinggian 2.386 mdpl.', 'kawah.webp', 4.97, 1),
(3, 'Kota Batu', 'Kota Batu adalah kota otonom yang terbentuk dari pemekaran Kabupaten Malang pada tahun 2001, menjadikannya wilayah pengembangan tersendiri di Malang Raya. Lokasinya yang berada di ketinggian memberikan udara segar dan pemandangan alam yang indah, menjadik', 'Provinsi Jawa Timur, Indonesia. Kota ini terletak 90 km sebelah barat daya Surabaya atau 15 km sebelah barat laut Malang.', 'kota.webp', 4.65, 4),
(4, 'Taman Nasional Baluran', 'Julukan: \"Africa van Java\" karena lanskap savana yang mirip dengan Afrika.\r\nLuas: Sekitar 25.000 hektar.\r\nEkosistem: Memiliki berbagai tipe vegetasi, termasuk savana (40% area), hutan mangrove, hutan musim, hutan pantai, hutan pegunungan bawah, dan hutan ', 'Desa Wonorejo, Kecamatan Banyuputih, Kabupaten Situbondo, Jawa Timur.Sekitar 35 km dari Kota Banyuwangi atau 57 km dari pusat Kota Situbondo. ', 'baluran.jpeg', 5.00, 4),
(5, 'Pantai 3 Warna', 'Pantai Tiga Warna terkenal dengan air lautnya yang punya gradasi warna biru, hijau, dan coklat kemerahan akibat kedalaman dan plankton, bukan taman bunga, namun menawarkan keindahan alam bawah laut yang asri dengan terumbu karang, cocok untuk snorkeling, ', 'Desa Tambakrejo, Sumbermanjing Wetan, Malang, dekat kawasan Clungup Mangrove Conservation (CMC). ', 'pantai 3.jpg', 5.00, 2),
(6, 'Pantai Banyu Anjlok', 'Banyu anjlok merupakan pantai di daerah malang selatan. Uniknya pantai ini memiliki air terjun dari sungai yang langsung jatuh langsung ke laut. Jadi apabila jalan ke pantai ini bisa dapt 2 pemandangan sekaligus, air terjun dan laut.', 'Lenggoksono, Purwodadi, Kec. Tirtoyudo, Kabupaten Malang, Jawa Timur 65182', 'pantai anjlok.jpg', 4.77, 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `favorit`
--
ALTER TABLE `favorit`
  ADD PRIMARY KEY (`id_favorit`),
  ADD KEY `fk_user` (`id_user`),
  ADD KEY `fk_wisata` (`id_wisata`);

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`);

--
-- Indexes for table `wisata`
--
ALTER TABLE `wisata`
  ADD PRIMARY KEY (`id_wisata`),
  ADD KEY `fk_kategori` (`id_kategori`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `favorit`
--
ALTER TABLE `favorit`
  MODIFY `id_favorit` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id_kategori` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `wisata`
--
ALTER TABLE `wisata`
  MODIFY `id_wisata` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `favorit`
--
ALTER TABLE `favorit`
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`),
  ADD CONSTRAINT `fk_wisata` FOREIGN KEY (`id_wisata`) REFERENCES `wisata` (`id_wisata`);

--
-- Constraints for table `wisata`
--
ALTER TABLE `wisata`
  ADD CONSTRAINT `fk_kategori` FOREIGN KEY (`id_kategori`) REFERENCES `kategori` (`id_kategori`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
