-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 23, 2025 at 07:20 AM
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
-- Database: `db_tabsis`
--

-- --------------------------------------------------------

--
-- Table structure for table `tb_kelas`
--

CREATE TABLE `tb_kelas` (
  `id_kelas` int(11) NOT NULL,
  `kelas` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tb_kelas`
--

INSERT INTO `tb_kelas` (`id_kelas`, `kelas`) VALUES
(4, 'X AKL 1'),
(5, 'X AKL 2'),
(6, 'X DKV'),
(7, 'X BR'),
(8, 'X MPLB 1'),
(9, 'X MPLB 2'),
(10, 'XI AKL 1'),
(11, 'XI AKL 2'),
(12, 'XI BR'),
(13, 'XI MPLB 1'),
(14, 'XI MPLB 2'),
(15, 'XI DKV'),
(16, 'XII AKL 1'),
(17, 'XII AKL 2'),
(18, 'XII MPLB'),
(19, 'XII DKV'),
(20, 'XII BR 1'),
(21, 'XII BR 2');

-- --------------------------------------------------------

--
-- Table structure for table `tb_notifikasi`
--

CREATE TABLE `tb_notifikasi` (
  `id_notifikasi` int(11) NOT NULL,
  `id_pengguna` int(11) DEFAULT NULL,
  `pesan` text DEFAULT NULL,
  `status` enum('baru','dibaca') DEFAULT 'baru',
  `waktu` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_notifikasi`
--

INSERT INTO `tb_notifikasi` (`id_notifikasi`, `id_pengguna`, `pesan`, `status`, `waktu`) VALUES
(1, 1, 'Notifikasi pertama', 'baru', '2025-03-13 05:04:05');

-- --------------------------------------------------------

--
-- Table structure for table `tb_pengguna`
--

CREATE TABLE `tb_pengguna` (
  `id_pengguna` int(11) NOT NULL,
  `nama_pengguna` varchar(20) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(15) NOT NULL,
  `level` enum('Administrator','Petugas') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tb_pengguna`
--

INSERT INTO `tb_pengguna` (`id_pengguna`, `nama_pengguna`, `username`, `password`, `level`) VALUES
(1, 'administrator', 'admin', 'bankmini18', 'Administrator'),
(5, 'Teller 1', 'teller1', 'teller1', 'Petugas'),
(6, 'Teller 2', 'teller2', 'teller2', 'Petugas');

-- --------------------------------------------------------

--
-- Table structure for table `tb_profil`
--

CREATE TABLE `tb_profil` (
  `id_profil` int(11) NOT NULL,
  `nama_sekolah` varchar(200) NOT NULL,
  `alamat` varchar(400) NOT NULL,
  `akreditasi` char(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tb_profil`
--

INSERT INTO `tb_profil` (`id_profil`, `nama_sekolah`, `alamat`, `akreditasi`) VALUES
(1, 'SMKN 18 Jakarta', 'Kompleks Bank Mandiri Jl. Ciputat Raya', 'A');

-- --------------------------------------------------------

--
-- Table structure for table `tb_siswa`
--

CREATE TABLE `tb_siswa` (
  `nis` int(12) NOT NULL,
  `nama_siswa` varchar(40) NOT NULL,
  `jekel` enum('LK','PR') NOT NULL,
  `id_kelas` int(11) NOT NULL,
  `status` enum('Aktif','Lulus','Pindah') NOT NULL,
  `th_masuk` year(4) NOT NULL,
  `saldo` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tb_siswa`
--

INSERT INTO `tb_siswa` (`nis`, `nama_siswa`, `jekel`, `id_kelas`, `status`, `th_masuk`, `saldo`, `created_at`, `updated_at`) VALUES
(12211, 'Dhifa aulia nuraini', 'PR', 17, 'Aktif', '2022', 30000, '2025-06-23 00:50:06', '2025-06-23 04:42:45'),
(12414, 'ADINDA NABILAH HELMI', 'PR', 17, 'Aktif', '2022', 42000, '2025-06-23 00:50:06', '2025-06-23 04:29:24'),
(12415, 'AHMAD FARISYA MAULUDY', 'LK', 17, 'Aktif', '2022', 0, '2025-06-23 00:50:06', '2025-06-23 00:50:06'),
(12416, 'ALFI HASANAH TASYA JOHARI', 'PR', 17, 'Aktif', '2022', 3000, '2025-06-23 00:50:06', '2025-06-23 04:29:40'),
(12417, 'Alviansyah Hendrawan', 'LK', 16, 'Aktif', '2022', 10000, '2025-06-23 00:50:06', '2025-06-23 04:42:25'),
(12418, 'Alya Citra', 'PR', 17, 'Aktif', '2022', 10000, '2025-06-23 00:50:06', '2025-06-23 04:43:39'),
(12419, 'ANDIKA PRASETYA', 'LK', 17, 'Aktif', '2022', 20000, '2025-06-23 00:50:06', '2025-06-23 04:43:30'),
(12421, 'ASHYA NUR SABILA', 'PR', 17, 'Aktif', '2022', 10000, '2025-06-23 00:50:06', '2025-06-23 04:43:18'),
(12422, 'AULIA PRATIWI', 'PR', 16, 'Aktif', '2022', 5000, '2025-06-23 00:50:06', '2025-06-23 04:43:05'),
(12423, 'Ayuni Galih Dwi Sanjaya', 'PR', 17, 'Aktif', '2022', 50000, '2025-06-23 00:50:06', '2025-06-23 00:50:06');

-- --------------------------------------------------------

--
-- Table structure for table `tb_tabungan`
--

CREATE TABLE `tb_tabungan` (
  `id_tabungan` int(11) NOT NULL,
  `nis` int(12) NOT NULL,
  `setor` int(11) NOT NULL,
  `tarik` int(11) NOT NULL,
  `saldo_awal` int(11) NOT NULL DEFAULT 0,
  `saldo_akhir` int(11) NOT NULL DEFAULT 0,
  `tgl` date NOT NULL,
  `jenis` enum('ST','TR') NOT NULL,
  `petugas` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tb_tabungan`
--

INSERT INTO `tb_tabungan` (`id_tabungan`, `nis`, `setor`, `tarik`, `saldo_awal`, `saldo_akhir`, `tgl`, `jenis`, `petugas`) VALUES
(1, 12211, 30000, 0, 0, 30000, '2025-06-08', 'ST', 'administrator'),
(2, 12415, 10000, 0, 0, 10000, '2025-06-08', 'ST', 'administrator'),
(3, 12211, 0, 10000, 30000, 20000, '2025-06-08', 'TR', 'administrator'),
(4, 12417, 10000, 0, 0, 10000, '2025-06-11', 'ST', 'administrator'),
(5, 12423, 50000, 0, 0, 50000, '2025-06-11', 'ST', 'administrator'),
(6, 12211, 0, 10000, 20000, 10000, '2025-06-11', 'TR', 'administrator'),
(7, 12417, 0, 10000, 10000, 0, '2025-06-11', 'TR', 'administrator'),
(8, 12211, 0, 20000, 10000, -10000, '2025-06-11', 'TR', 'administrator'),
(9, 12415, 0, 20000, 10000, -10000, '2025-06-11', 'TR', 'administrator'),
(10, 12211, 20000, 0, -10000, 10000, '2025-06-11', 'ST', 'administrator'),
(11, 12415, 10000, 0, -10000, 0, '2025-06-11', 'ST', 'administrator'),
(12, 12414, 10000, 0, 0, 10000, '2025-06-18', 'ST', 'administrator'),
(13, 12211, 1000, 0, 10000, 11000, '2025-06-18', 'ST', 'administrator'),
(14, 12211, 0, 1000, 11000, 10000, '2025-06-18', 'TR', 'administrator'),
(15, 12414, 0, 10000, 10000, 0, '2025-06-18', 'TR', 'administrator'),
(16, 12211, 0, 10000, 10000, 0, '2025-06-18', 'TR', 'administrator'),
(17, 12414, 10000, 0, 0, 10000, '2025-06-18', 'ST', 'administrator'),
(18, 12414, 10000, 0, 10000, 20000, '2025-06-18', 'ST', 'administrator'),
(19, 12416, 10000, 0, 0, 10000, '2025-06-22', 'ST', 'administrator'),
(20, 12414, 10000, 0, 20000, 30000, '2025-06-22', 'ST', 'administrator'),
(21, 12416, 0, 5000, 10000, 5000, '2025-06-22', 'TR', 'administrator'),
(22, 12416, 30000, 0, 5000, 35000, '2025-06-23', 'ST', 'administrator'),
(23, 12416, 0, 20000, 35000, 15000, '2025-06-23', 'TR', 'administrator'),
(24, 12414, 12000, 0, 30000, 42000, '2025-06-23', 'ST', 'administrator'),
(25, 12416, 0, 12000, 15000, 3000, '2025-06-23', 'TR', 'administrator'),
(26, 12417, 10000, 0, 0, 10000, '2025-06-23', 'ST', 'administrator'),
(27, 12211, 30000, 0, 0, 30000, '2025-06-23', 'ST', 'administrator'),
(28, 12422, 5000, 0, 0, 5000, '2025-06-23', 'ST', 'administrator'),
(29, 12421, 10000, 0, 0, 10000, '2025-06-23', 'ST', 'administrator'),
(30, 12419, 20000, 0, 0, 20000, '2025-06-23', 'ST', 'administrator'),
(31, 12418, 10000, 0, 0, 10000, '2025-06-23', 'ST', 'administrator');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_kelas`
--
ALTER TABLE `tb_kelas`
  ADD PRIMARY KEY (`id_kelas`);

--
-- Indexes for table `tb_notifikasi`
--
ALTER TABLE `tb_notifikasi`
  ADD PRIMARY KEY (`id_notifikasi`),
  ADD KEY `fk_pengguna_notifikasi` (`id_pengguna`);

--
-- Indexes for table `tb_pengguna`
--
ALTER TABLE `tb_pengguna`
  ADD PRIMARY KEY (`id_pengguna`);

--
-- Indexes for table `tb_profil`
--
ALTER TABLE `tb_profil`
  ADD PRIMARY KEY (`id_profil`);

--
-- Indexes for table `tb_siswa`
--
ALTER TABLE `tb_siswa`
  ADD PRIMARY KEY (`nis`),
  ADD KEY `id_kelas` (`id_kelas`);

--
-- Indexes for table `tb_tabungan`
--
ALTER TABLE `tb_tabungan`
  ADD PRIMARY KEY (`id_tabungan`),
  ADD KEY `nis` (`nis`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_kelas`
--
ALTER TABLE `tb_kelas`
  MODIFY `id_kelas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `tb_notifikasi`
--
ALTER TABLE `tb_notifikasi`
  MODIFY `id_notifikasi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tb_pengguna`
--
ALTER TABLE `tb_pengguna`
  MODIFY `id_pengguna` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tb_profil`
--
ALTER TABLE `tb_profil`
  MODIFY `id_profil` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tb_tabungan`
--
ALTER TABLE `tb_tabungan`
  MODIFY `id_tabungan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tb_notifikasi`
--
ALTER TABLE `tb_notifikasi`
  ADD CONSTRAINT `fk_pengguna_notifikasi` FOREIGN KEY (`id_pengguna`) REFERENCES `tb_pengguna` (`id_pengguna`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `tb_siswa`
--
ALTER TABLE `tb_siswa`
  ADD CONSTRAINT `tb_siswa_ibfk_1` FOREIGN KEY (`id_kelas`) REFERENCES `tb_kelas` (`id_kelas`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tb_tabungan`
--
ALTER TABLE `tb_tabungan`
  ADD CONSTRAINT `tb_tabungan_ibfk_1` FOREIGN KEY (`nis`) REFERENCES `tb_siswa` (`nis`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
