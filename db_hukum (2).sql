-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 15, 2026 at 02:07 PM
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
-- Database: `db_hukum`
--

-- --------------------------------------------------------

--
-- Table structure for table `kasus`
--

CREATE TABLE `kasus` (
  `id_kasus` int(11) NOT NULL,
  `id_konsultasi` int(11) NOT NULL,
  `tanggal_laporan` date NOT NULL,
  `progres` text NOT NULL,
  `tindakan` text NOT NULL,
  `rencana` text NOT NULL,
  `status_kasus` enum('open','closed') DEFAULT 'open',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kasus`
--

INSERT INTO `kasus` (`id_kasus`, `id_konsultasi`, `tanggal_laporan`, `progres`, `tindakan`, `rencana`, `status_kasus`, `created_at`, `updated_at`) VALUES
(1, 1, '2026-01-15', 'Masih hakim agung', 'negosiasi kasus suap', 'suap presiden', 'open', '2026-01-15 08:45:45', '2026-01-15 08:45:45'),
(2, 9, '2026-01-15', 'Sesi Konsultasi Selesai', 'Analisis awal kasus', 'Menyusun strategi hukum', 'open', '2026-01-15 10:35:04', '2026-01-15 10:35:04'),
(3, 9, '2026-01-15', 'wda', 'awda', 'awd', 'closed', '2026-01-15 10:35:14', '2026-01-15 10:35:14'),
(4, 9, '2026-01-15', 'dwada', 'adwwad', 'awdawda', 'closed', '2026-01-15 10:35:28', '2026-01-15 10:35:28'),
(5, 6, '2026-01-15', 'adada', 'dawda', 'dadwa', 'open', '2026-01-15 10:58:01', '2026-01-15 10:58:01'),
(6, 6, '2026-01-15', 'dadad', 'adwdad', 'dadaw', 'open', '2026-01-15 10:58:04', '2026-01-15 10:58:04'),
(7, 6, '2026-01-15', 'adada', 'dadada', 'da', 'closed', '2026-01-15 10:58:07', '2026-01-15 10:58:07'),
(8, 7, '2026-01-15', 'adwad', 'awda', 'awda', 'open', '2026-01-15 11:55:07', '2026-01-15 11:55:07');

-- --------------------------------------------------------

--
-- Table structure for table `konsultasi`
--

CREATE TABLE `konsultasi` (
  `id_konsultasi` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `no_bas` varchar(50) DEFAULT NULL,
  `tipe_konsultasi` enum('online','offline') NOT NULL DEFAULT 'online',
  `jenis_perkara` varchar(100) NOT NULL,
  `deskripsi_masalah` text NOT NULL,
  `dokumen_kelengkapan` varchar(255) DEFAULT NULL,
  `tanggal_usulan` datetime NOT NULL,
  `tanggal_fiksasi` datetime DEFAULT NULL,
  `status` enum('pending','waiting_lawyer','waiting_payment','approved','rejected','reschedule','completed') DEFAULT 'pending',
  `meeting` varchar(255) DEFAULT NULL,
  `alasan_tolak` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `konsultasi`
--

INSERT INTO `konsultasi` (`id_konsultasi`, `id_user`, `no_bas`, `tipe_konsultasi`, `jenis_perkara`, `deskripsi_masalah`, `dokumen_kelengkapan`, `tanggal_usulan`, `tanggal_fiksasi`, `status`, `meeting`, `alasan_tolak`, `created_at`, `updated_at`) VALUES
(1, 5, 'B-12345', 'online', 'dwdawad', 'nijadbijawda', '1767892894_37c2d34098e422fe27eb.pdf', '2026-01-07 00:20:00', '2026-01-07 00:20:00', 'completed', 'dawdawdawd', NULL, '2026-01-08 17:21:34', '2026-01-15 08:20:22'),
(2, 5, 'B-12345', 'online', 'adwwadw', 'wadawdadw', '1767894078_55cfa267640c790d3fee.pdf', '2026-01-23 04:45:00', '2026-01-23 04:45:00', NULL, '14141414213', NULL, '2026-01-08 17:41:18', '2026-01-08 17:42:12'),
(3, 5, 'B-12345', 'online', 'dadwwadada123', 'awdadwdaw123', '1767894094_acbea7860da94f2940fe.pdf', '2026-01-24 05:47:00', '2026-01-24 05:47:00', '', '41414141', NULL, '2026-01-08 17:41:34', '2026-01-08 17:42:21'),
(4, 5, 'B-12345', 'online', '11231223', '132123', '1767894547_88b366159d58fabdd139.pdf', '2026-01-10 00:48:00', '2026-01-10 00:48:00', 'reschedule', 'awdawdawd', NULL, '2026-01-08 17:49:07', '2026-01-08 17:50:16'),
(5, 5, 'B-12345', 'offline', 'Sengketa tanah kuburan', 'takut', '1768460189_e8989fb767e393537aaf.pdf', '2026-01-16 04:58:00', '2026-01-16 04:58:00', 'completed', 'blablablaa', NULL, '2026-01-15 06:56:29', '2026-01-15 07:30:00'),
(6, 5, 'B-12345', 'online', 'adwada', 'dawdawdwadad', '1768469068_4f4cc92ac16d97157fdf.pdf', '2026-01-15 06:26:00', '2026-01-15 06:26:00', 'completed', 'wadwdawd', NULL, '2026-01-15 09:24:28', '2026-01-15 09:38:57'),
(7, 5, 'B-12345', 'online', 'zzzzzzzz', 'zzzzzzzzz', '1768470000_7f501e706841bfaa64bb.pdf', '2026-01-15 16:39:00', '2026-01-15 16:39:00', 'completed', 'aaaaaaaaaaa', NULL, '2026-01-15 09:40:00', '2026-01-15 10:04:28'),
(8, 5, 'B-12345', 'online', 'hhhhhhhhhh', 'dadwdahhhhhhhhhhhhhhhhhhhhhhhhhhhh', '1768471434_4774746fb83ae7feb5b6.pdf', '2026-01-27 17:03:00', '2026-01-27 17:03:00', 'completed', 'aaaaaaaaaaaaaaa', NULL, '2026-01-15 10:03:54', '2026-01-15 10:04:22'),
(9, 5, 'B-12345', 'online', 'bbbbbbbbbbbbbbbbbbbbbbbbbbbbb', 'bbbbbbbbb', '1768471505_243b4e58a869518c39df.pdf', '2026-01-21 17:05:00', '2026-01-21 17:05:00', 'completed', 'wwwwwwww', NULL, '2026-01-15 10:05:05', '2026-01-15 10:35:04'),
(10, 5, 'B-12345', 'online', 'PENTING', 'PENTING', '1768471591_002d2c57db83543797bf.pdf', '2026-01-15 20:06:00', '2026-01-15 20:06:00', 'waiting_payment', 'penitng', NULL, '2026-01-15 10:06:31', '2026-01-15 10:09:38'),
(11, 10, 'BAS-999', 'offline', 'Sengketa Lahan', '', NULL, '0000-00-00 00:00:00', NULL, 'completed', NULL, NULL, '2026-01-10 10:00:00', '2026-01-10 10:00:00'),
(12, 11, 'BAS-777', 'online', 'Pencemaran Nama Baik', '', NULL, '0000-00-00 00:00:00', NULL, 'completed', NULL, NULL, '2026-01-15 14:00:00', '2026-01-15 14:00:00'),
(13, 10, 'BAS-888', 'online', 'Perceraian', '', NULL, '0000-00-00 00:00:00', NULL, 'completed', NULL, NULL, '2026-02-20 09:00:00', '2026-02-20 09:00:00'),
(14, 10, 'BAS-999', 'offline', 'Kasus Tambang', '', NULL, '0000-00-00 00:00:00', NULL, 'completed', NULL, NULL, '2026-03-05 10:00:00', '2026-03-05 10:00:00'),
(15, 11, 'BAS-888', 'online', 'Warisan', '', NULL, '0000-00-00 00:00:00', NULL, 'completed', NULL, NULL, '2026-03-12 13:00:00', '2026-03-12 13:00:00'),
(16, 10, 'BAS-777', 'offline', 'Korupsi Dana Desa', '', NULL, '0000-00-00 00:00:00', NULL, 'completed', NULL, NULL, '2026-03-25 15:00:00', '2026-03-25 15:00:00'),
(17, 11, 'BAS-999', 'online', 'Penganiayaan', '', NULL, '0000-00-00 00:00:00', NULL, 'completed', NULL, NULL, '2026-04-02 08:00:00', '2026-04-02 08:00:00'),
(18, 10, 'BAS-888', 'offline', 'Hutang Piutang', '', NULL, '0000-00-00 00:00:00', NULL, 'completed', NULL, NULL, '2026-04-18 11:00:00', '2026-04-18 11:00:00'),
(19, 11, 'BAS-777', 'online', 'Penipuan Online', '', NULL, '0000-00-00 00:00:00', NULL, 'completed', NULL, NULL, '2026-05-01 09:00:00', '2026-05-01 09:00:00'),
(20, 10, 'BAS-999', 'offline', 'Sengketa Pilkada', '', NULL, '0000-00-00 00:00:00', NULL, 'approved', NULL, NULL, '2026-05-05 10:00:00', '2026-05-05 10:00:00'),
(21, 10, 'BAS-999', 'offline', 'Sengketa Lahan', 'Masalah sengketa tanah warisan di Jakarta Selatan.', NULL, '2026-01-09 00:00:00', '2026-01-10 10:00:00', 'completed', NULL, NULL, '2026-01-10 10:00:00', '2026-01-10 10:00:00'),
(22, 11, 'BAS-777', 'online', 'Pencemaran Nama Baik', 'Kasus penghinaan di media sosial.', NULL, '2026-01-14 00:00:00', '2026-01-15 14:00:00', 'completed', NULL, NULL, '2026-01-15 14:00:00', '2026-01-15 14:00:00'),
(23, 10, 'BAS-888', 'online', 'Perceraian', 'Gugatan cerai dan hak asuh anak.', NULL, '2026-02-19 00:00:00', '2026-02-20 09:00:00', 'completed', NULL, NULL, '2026-02-20 09:00:00', '2026-02-20 09:00:00'),
(24, 10, 'BAS-999', 'offline', 'Kasus Tambang', 'Izin usaha pertambangan ilegal.', NULL, '2026-03-04 00:00:00', '2026-03-05 10:00:00', 'completed', NULL, NULL, '2026-03-05 10:00:00', '2026-03-05 10:00:00'),
(25, 11, 'BAS-888', 'online', 'Warisan', 'Pembagian harta gono gini.', NULL, '2026-03-11 00:00:00', '2026-03-12 13:00:00', 'completed', NULL, NULL, '2026-03-12 13:00:00', '2026-03-12 13:00:00'),
(26, 10, 'BAS-777', 'offline', 'Korupsi Dana Desa', 'Dugaan penyelewengan dana bantuan.', NULL, '2026-03-24 00:00:00', '2026-03-25 15:00:00', 'completed', NULL, NULL, '2026-03-25 15:00:00', '2026-03-25 15:00:00'),
(27, 11, 'BAS-999', 'online', 'Penganiayaan', 'Tindak kekerasan fisik ringan.', NULL, '2026-04-01 00:00:00', '2026-04-02 08:00:00', 'completed', NULL, NULL, '2026-04-02 08:00:00', '2026-04-02 08:00:00'),
(28, 10, 'BAS-888', 'offline', 'Hutang Piutang', 'Wanprestasi perjanjian hutang.', NULL, '2026-04-17 00:00:00', '2026-04-18 11:00:00', 'completed', NULL, NULL, '2026-04-18 11:00:00', '2026-04-18 11:00:00'),
(29, 11, 'BAS-777', 'online', 'Penipuan Online', 'Korban investasi bodong.', NULL, '2026-05-01 00:00:00', '2026-05-01 09:00:00', 'completed', NULL, NULL, '2026-05-01 09:00:00', '2026-05-01 09:00:00'),
(30, 10, 'BAS-999', 'offline', 'Sengketa Pilkada', 'Gugatan hasil pemilihan umum daerah.', NULL, '2026-05-04 00:00:00', '2026-05-05 10:00:00', 'approved', NULL, NULL, '2026-05-05 10:00:00', '2026-05-05 10:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `role` enum('client','lawyer','ketua firma','sekretaris') NOT NULL,
  `no_telp` varchar(20) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `nik` varchar(16) DEFAULT NULL,
  `no_bas` varchar(50) DEFAULT NULL,
  `spesialisasi` varchar(100) DEFAULT NULL,
  `harga_konsultasi` int(11) DEFAULT 150000,
  `available` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `email`, `password`, `nama`, `role`, `no_telp`, `alamat`, `nik`, `no_bas`, `spesialisasi`, `harga_konsultasi`, `available`, `created_at`, `updated_at`) VALUES
(1, 'q@q.com', '202cb962ac59075b964b07152d234b70', 'Firman Nurilhaq', 'client', '1313212313132', 'Jl. Gambir Saketi No.40, Cibeunyi Kaler, Kota Bandung, Jawabarat', '1231312312321', NULL, NULL, 150000, 1, '2026-01-05 18:09:51', '2026-01-05 18:09:51'),
(2, 's@g.com', '123', 'siti', 'sekretaris', '124521561', 'dawdawd', NULL, NULL, NULL, 150000, 1, '2026-01-08 23:54:49', NULL),
(3, 'a@a.com', '$2y$10$48FF.5.9E9L75cQCKgGNmeMaBeiE3kCYXJVTR6pKIjvbh6EyL6Vb.', 'a', 'sekretaris', '15151334134', 'JL. BANDUNG NO 23', '15153141', NULL, NULL, 150000, 1, '2026-01-08 16:55:40', '2026-01-08 16:55:40'),
(4, 'w@w.com', '$2y$10$jZw4GUUTPcl1zQE3tFpRDuBHQm4v.Qr4obLg7m7WB1fFSGU9kDOp2', 'wd', 'lawyer', '15151334134', 'dadawdawd', '1515314112', 'B-12345', 'Pidana', 700000, 1, '2026-01-08 16:56:09', '2026-01-15 12:54:36'),
(5, 'e@e', '$2y$10$Ni7LGEM8UgbIE/q3M5xruOex724/nHWhyojaAWRHVTT1WCr6dmqPG', 'e', 'client', '31231', '32123123', '312312', NULL, NULL, 150000, 1, '2026-01-08 17:20:04', '2026-01-08 17:20:04'),
(6, 's@s', '$2y$10$x33Z0SZ2KbIlP6O7XDleROkU2HvDLWCeImCRznoAmvZRqEMUslPAi', 'Bapak Pimpinan', 'ketua firma', '08123456789', 'Jakarta', NULL, NULL, NULL, 150000, 1, '2026-01-15 18:19:30', '2026-01-15 11:23:01'),
(7, 'hotmankw@law.com', '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', 'Hotman Paris KW', 'lawyer', '08110001', 'Jakarta', NULL, 'BAS-999', 'Pidana Berat', 5000000, 1, '2026-01-01 10:00:00', NULL),
(8, 'elsakw@law.com', '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', 'Elsa Syarief KW', 'lawyer', '08110002', 'Bandung', NULL, 'BAS-888', 'Perdata & Harta', 1500000, 1, '2026-01-01 10:00:00', NULL),
(9, 'kamarkw@law.com', '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', 'Kamaruddin KW', 'lawyer', '08110003', 'Medan', NULL, 'BAS-777', 'Korupsi', 750000, 1, '2026-01-01 10:00:00', NULL),
(10, 'sultan@client.com', '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', 'Sultan Andara', 'client', '08129999', 'Depok', NULL, NULL, NULL, 150000, 1, '2026-01-01 10:00:00', NULL),
(11, 'rakyat@client.com', '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', 'Rakyat Jelata', 'client', '08128888', 'Bogor', NULL, NULL, NULL, 150000, 1, '2026-01-01 10:00:00', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `kasus`
--
ALTER TABLE `kasus`
  ADD PRIMARY KEY (`id_kasus`),
  ADD KEY `id_konsultasi` (`id_konsultasi`);

--
-- Indexes for table `konsultasi`
--
ALTER TABLE `konsultasi`
  ADD PRIMARY KEY (`id_konsultasi`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `no_bas` (`no_bas`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `no_bas` (`no_bas`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `kasus`
--
ALTER TABLE `kasus`
  MODIFY `id_kasus` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `konsultasi`
--
ALTER TABLE `konsultasi`
  MODIFY `id_konsultasi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `kasus`
--
ALTER TABLE `kasus`
  ADD CONSTRAINT `kasus_ibfk_1` FOREIGN KEY (`id_konsultasi`) REFERENCES `konsultasi` (`id_konsultasi`) ON DELETE CASCADE;

--
-- Constraints for table `konsultasi`
--
ALTER TABLE `konsultasi`
  ADD CONSTRAINT `konsultasi_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE,
  ADD CONSTRAINT `konsultasi_ibfk_2` FOREIGN KEY (`no_bas`) REFERENCES `user` (`no_bas`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
