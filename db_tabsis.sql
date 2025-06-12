-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 28, 2025 at 06:12 PM
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
  `nis` char(12) NOT NULL,
  `nama_siswa` varchar(40) NOT NULL,
  `jekel` enum('LK','PR') NOT NULL,
  `id_kelas` int(11) NOT NULL,
  `status` enum('Aktif','Lulus','Pindah') NOT NULL,
  `th_masuk` year(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tb_siswa`
--

INSERT INTO `tb_siswa` (`nis`, `nama_siswa`, `jekel`, `id_kelas`, `status`, `th_masuk`) VALUES
('12211', 'Dhifa aulia nuraini', 'PR', 17, 'Aktif', '2022'),
('12414', 'ADINDA NABILAH HELMI', 'PR', 17, 'Aktif', '2022'),
('12415', 'AHMAD FARISYA MAULUDY', 'LK', 17, 'Aktif', '2022'),
('12416', 'ALFI HASANAH TASYA JOHARI', 'PR', 17, 'Aktif', '2022'),
('12417', 'Alviansyah Hendrawan', 'LK', 16, 'Aktif', '2022'),
('12418', 'Alya Citra', 'PR', 17, 'Aktif', '2022'),
('12419', 'ANDIKA PRASETYA', 'LK', 17, 'Aktif', '2022'),
('12421', 'ASHYA NUR SABILA', 'PR', 17, 'Aktif', '2022'),
('12422', 'AULIA PRATIWI', 'PR', 16, 'Aktif', '2022'),
('12423', 'Ayuni Galih Dwi Sanjaya', 'PR', 17, 'Aktif', '2022'),
('12424', 'AZZAHRA AYU FADILLAH', 'PR', 17, 'Aktif', '2022'),
('12425', 'BIMA PRASETIA', 'LK', 16, 'Aktif', '2022'),
('12426', 'BINTAN SAFITRI', 'PR', 16, 'Aktif', '2022'),
('12427', 'DAVINA KEYSHA JASMINE', 'PR', 17, 'Aktif', '2022'),
('12428', 'DAVVA PUTRA PRATAMA', 'LK', 16, 'Aktif', '2022'),
('12429', 'Dhiya Eka Saputri', 'PR', 17, 'Aktif', '2022'),
('12430', 'DIFFA MARCELLENO', 'LK', 17, 'Aktif', '2022'),
('12431', 'Dimas Faturrohman Ramadan', 'LK', 16, 'Aktif', '2022'),
('12432', 'DWI PUSPITA SARI', 'PR', 17, 'Aktif', '2022'),
('12433', 'EKO HADIKUSUMO', 'LK', 16, 'Aktif', '2022'),
('12434', 'Fathiya Nur Aziza', 'PR', 16, 'Aktif', '2022'),
('12435', 'FAUZAN RAFLI BAIHAQI', 'LK', 17, 'Aktif', '2022'),
('12437', 'GENIE MARSHA FAJRIAH', 'PR', 16, 'Aktif', '2022'),
('12438', 'GHASSANI AMALIANISA', 'PR', 17, 'Aktif', '2022'),
('12439', 'JULIANTI AYU LESTARI', 'PR', 16, 'Aktif', '2022'),
('12441', 'Leyka Ayudya Bahri', 'PR', 16, 'Aktif', '2022'),
('12442', 'Mahesa Putri Apriliyanti', 'PR', 16, 'Aktif', '2022'),
('12443', 'MAZAYA PUTRI YALINA', 'PR', 17, 'Aktif', '2022'),
('12444', 'MEILANI DWI PRATIWI', 'PR', 17, 'Aktif', '2022'),
('12445', 'MEY APRIL LIANI', 'PR', 17, 'Aktif', '2022'),
('12446', 'MUHAMAD FARHAN', 'LK', 17, 'Aktif', '2022'),
('12447', 'MUHAMMAD HAIKAL PRATAMA', 'LK', 16, 'Aktif', '2022'),
('12449', 'MUHAMMAD SYAFIQ QURTHUBI', 'LK', 16, 'Aktif', '2022'),
('12450', 'MUHAMMAD YUSUF', 'LK', 17, 'Aktif', '2022'),
('12451', 'NAHLA IZZATI AGHNIA', 'PR', 16, 'Aktif', '2022'),
('12452', 'NAILA MARSELLA', 'PR', 17, 'Aktif', '2022'),
('12453', 'NA\'ILAH SALSABIL', 'PR', 17, 'Aktif', '2022'),
('12454', 'NAJWA SALSA MY BILA', 'PR', 16, 'Aktif', '2022'),
('12455', 'Natasha Keysa Madhvani', 'PR', 16, 'Aktif', '2022'),
('12456', 'NIKITA RAIYA ELSA', 'PR', 16, 'Aktif', '2022'),
('12457', 'NUR KHALIZA', 'PR', 16, 'Aktif', '2022'),
('12458', 'NURDETIKA', 'PR', 16, 'Aktif', '2022'),
('12459', 'NURMALITA', 'PR', 17, 'Aktif', '2022'),
('12460', 'NURUL ATIIKAH', 'PR', 16, 'Aktif', '2022'),
('12461', 'OLGA SELVIYANA', 'PR', 17, 'Aktif', '2022'),
('12462', 'RARA DESTIYANA', 'PR', 16, 'Aktif', '2022'),
('12463', 'REGA SAPUTRA', 'LK', 16, 'Aktif', '2022'),
('12464', 'RENGGANIS NAILA SYAFA', 'PR', 16, 'Aktif', '2022'),
('12465', 'REVA KAYLA', 'PR', 17, 'Aktif', '2022'),
('12466', 'RISKA PUTRI ISTIQOMAH', 'PR', 17, 'Aktif', '2022'),
('12467', 'RIZKI AMALIYYAH RAMADHANI', 'PR', 16, 'Aktif', '2022'),
('12468', 'Sahla Bunga Alzijah', 'PR', 17, 'Aktif', '2022'),
('12469', 'SAQINA ZULFA AVIKA', 'PR', 16, 'Aktif', '2022'),
('12470', 'SATRIO BUDHI MAMORA LUBIS', 'LK', 16, 'Aktif', '2022'),
('12471', 'SEPTIAN NURROHMAD', 'LK', 16, 'Aktif', '2022'),
('12472', 'SHABRINA TARISA', 'PR', 16, 'Aktif', '2022'),
('12474', 'SITI ALISYA PUTRI', 'PR', 17, 'Aktif', '2022'),
('12475', 'SITI NUR KAROH MAYA', 'PR', 17, 'Aktif', '2022'),
('12476', 'SITI ZAHRA', 'PR', 17, 'Aktif', '2022'),
('12477', 'SUCI RAMADHANI', 'PR', 16, 'Aktif', '2022'),
('12478', 'SYAFA TRYANITA HANDAYANI', 'PR', 17, 'Aktif', '2022'),
('12479', 'TIANA PITALOKA FEBRIANA', 'PR', 16, 'Aktif', '2022'),
('12480', 'WANDA NOVALITA', 'PR', 17, 'Aktif', '2022'),
('12481', 'Wildan Hasanudin', 'LK', 16, 'Aktif', '2022'),
('12482', 'WILDAN SYACHBAN NURRACHMAN', 'LK', 17, 'Aktif', '2022'),
('12483', 'ZAKY ZAHRAN', 'LK', 17, 'Aktif', '2022'),
('12484', 'AALIYA ANANDA PUTRI', 'PR', 20, 'Aktif', '2022'),
('12485', 'ADAM ALFAREZI PRATAMA', 'LK', 21, 'Aktif', '2022'),
('12486', 'ADELIA SAFIRA RAHMADANTI', 'PR', 21, 'Aktif', '2022'),
('12487', 'ADI EKA PRASETYO', 'LK', 20, 'Aktif', '2022'),
('12488', 'AFIFAH KHAIRUNNISA', 'PR', 20, 'Aktif', '2022'),
('12489', 'AHMAD BRYAN AL FARREL', 'LK', 20, 'Aktif', '2022'),
('12490', 'Alfiah', 'PR', 21, 'Aktif', '2022'),
('12491', 'ALIVIA KHAIRUNISA RAJABANIAH', 'PR', 20, 'Aktif', '2022'),
('12492', 'Amanda Irsani', 'PR', 20, 'Aktif', '2022'),
('12493', 'Andien Pratama Putri', 'PR', 20, 'Aktif', '2022'),
('12494', 'ANGGI RASYA NURAENA', 'PR', 20, 'Aktif', '2022'),
('12495', 'ANGGITA CAHYA RAMADHANI', 'PR', 21, 'Aktif', '2022'),
('12496', 'Anwar Farid Aziz', 'LK', 20, 'Aktif', '2022'),
('12497', 'ARSYI RAMADHAN', 'LK', 21, 'Aktif', '2022'),
('12498', 'ASRI TYAS LESTARI', 'PR', 21, 'Aktif', '2022'),
('12499', 'ATHENA ZAHRA ANNABIL BALQIS', 'PR', 21, 'Aktif', '2022'),
('12500', 'AYU OKTAVIANY', 'PR', 20, 'Aktif', '2022'),
('12501', 'AZWADITA AURAINI', 'PR', 21, 'Aktif', '2022'),
('12502', 'CYNTHIA DWINOV', 'PR', 20, 'Aktif', '2022'),
('12503', 'Daffa Ardianzyah', 'LK', 21, 'Aktif', '2022'),
('12504', 'DAFFA RIHHADATUL AISY', 'LK', 21, 'Aktif', '2022'),
('12505', 'DELI ARISTIA', 'PR', 21, 'Aktif', '2022'),
('12506', 'DHAVAREL REVARIZA AKBAR', 'LK', 21, 'Aktif', '2022'),
('12507', 'DIANA ANGGITA', 'PR', 20, 'Aktif', '2022'),
('12508', 'Diana Permatasari', 'PR', 20, 'Aktif', '2022'),
('12509', 'Dimas Ananda Saputra', 'LK', 20, 'Aktif', '2022'),
('12510', 'FAHREZI AL BANI', 'LK', 20, 'Aktif', '2022'),
('12511', 'FERRY DWI NOVALGI', 'LK', 20, 'Aktif', '2022'),
('12512', 'FIONITA FEBRIANI', 'PR', 21, 'Aktif', '2022'),
('12513', 'IBRAHIMOFIQ', 'LK', 20, 'Aktif', '2022'),
('12514', 'IKHWAN HERIYANTO', 'LK', 21, 'Aktif', '2022'),
('12515', 'INTAN ANANDA', 'PR', 20, 'Aktif', '2022'),
('12516', 'INTAN PERMATA SHERANA LAIQA', 'PR', 20, 'Aktif', '2022'),
('12517', 'IRFAN RAMADHANI RIZKY', 'LK', 20, 'Aktif', '2022'),
('12518', 'ISABELLA MUTIARA MAHARANI', 'PR', 20, 'Aktif', '2022'),
('12519', 'JAIZAHKHAIRUN NISA', 'PR', 20, 'Aktif', '2022'),
('12520', 'Kanaya Azzahra Putri', 'PR', 21, 'Aktif', '2022'),
('12521', 'LAZUARDI RAHENDRA PUTRA', 'LK', 20, 'Aktif', '2022'),
('12522', 'MARIA ULFAH', 'PR', 21, 'Aktif', '2022'),
('12523', 'MARSHA FAUZIAH', 'PR', 20, 'Aktif', '2022'),
('12524', 'MAULIDYA MAHDANIATI', 'PR', 21, 'Aktif', '2022'),
('12525', 'MEDYNA DHIANRA', 'PR', 21, 'Aktif', '2022'),
('12526', 'MUHAMAD ARKAN GATA MANDAWA', 'LK', 21, 'Aktif', '2022'),
('12527', 'MUHAMAD SULTAN ATHALLAH', 'LK', 21, 'Aktif', '2022'),
('12528', 'MUHAMMAD EVAN PRATAMA', 'LK', 21, 'Aktif', '2022'),
('12529', 'MUHAMMAD RADHITYA PUTRA IRAWAN', 'LK', 21, 'Aktif', '2022'),
('12531', 'NABILA FEBRIANI', 'PR', 21, 'Aktif', '2022'),
('12532', 'NAYSILLA MOZA ADIMINATA', 'PR', 20, 'Aktif', '2022'),
('12533', 'NURUL AULIA', 'PR', 20, 'Aktif', '2022'),
('12534', 'PUTRI DEA AMANDA', 'PR', 20, 'Aktif', '2022'),
('12535', 'RAFFI FAHRI KURNIAWAN', 'LK', 20, 'Aktif', '2022'),
('12536', 'RAHMA PRASETIAWATI', 'PR', 21, 'Aktif', '2022'),
('12537', 'RANDY PRATAMA', 'LK', 21, 'Aktif', '2022'),
('12538', 'Riri Dwi Meylani', 'PR', 21, 'Aktif', '2022'),
('12539', 'RISKI MAULANA', 'LK', 21, 'Aktif', '2022'),
('12540', 'RIVANIA FAULICA PUTRI', 'PR', 21, 'Aktif', '2022'),
('12541', 'SABRINA BALQIS ALIFYA PASHA', 'PR', 21, 'Aktif', '2022'),
('12542', 'Satya Sani', 'LK', 20, 'Aktif', '2022'),
('12543', 'SELSYA ABELYA PUTRI', 'PR', 20, 'Aktif', '2022'),
('12544', 'SHEILA VEDA WIDYADANA', 'PR', 20, 'Aktif', '2022'),
('12545', 'Siska Maharani', 'PR', 21, 'Aktif', '2022'),
('12546', 'Siti Hasmaliah', 'PR', 20, 'Aktif', '2022'),
('12547', 'SUBAIDAH', 'PR', 21, 'Aktif', '2022'),
('12548', 'SYAHWA KHAIRUN NISA', 'PR', 21, 'Aktif', '2022'),
('12549', 'SYIFA ANANTA SAFIRA', 'PR', 20, 'Aktif', '2022'),
('12550', 'YUANIS SALSA MUSMIZAR', 'PR', 21, 'Aktif', '2022'),
('12551', 'ZACKY NURUL HUDA', 'LK', 20, 'Aktif', '2022'),
('12552', 'Zahrotul Amalia', 'PR', 21, 'Aktif', '2022'),
('12553', 'ZALFA SAFINATUNNAJAH', 'PR', 20, 'Aktif', '2022'),
('12554', 'ZHAHARA INDA WILADA', 'PR', 21, 'Aktif', '2022'),
('12555', 'ZIHAN FITRI YENI OCTANIA', 'PR', 20, 'Aktif', '2022'),
('12556', 'AHMAD MUHAJIRIN', 'LK', 19, 'Aktif', '2022'),
('12557', 'ANGGUN DWI CAHYA', 'PR', 19, 'Aktif', '2022'),
('12558', 'AWAN PRAYOGA', 'LK', 19, 'Aktif', '2022'),
('12559', 'AZIIZAH ARROHMAH', 'PR', 19, 'Aktif', '2022'),
('12560', 'BIMA AKBAR PRATAMA', 'LK', 19, 'Aktif', '2022'),
('12561', 'Claresta Agnesia', 'PR', 19, 'Aktif', '2022'),
('12562', 'FACHRI AHMAD', 'LK', 19, 'Aktif', '2022'),
('12563', 'FAIZ SYAFA SAEFUDIN', 'LK', 19, 'Aktif', '2022'),
('12564', 'FALISHA AMIRA MUMTAZ', 'PR', 19, 'Aktif', '2022'),
('12565', 'Ferris Athallah Radityo', 'LK', 19, 'Aktif', '2022'),
('12566', 'GHANIYA ANIQA HAYA', 'PR', 19, 'Aktif', '2022'),
('12567', 'ILHAM RAMADAN', 'LK', 19, 'Aktif', '2022'),
('12568', 'JORDAN PUTRA SURYA PRATAMA', 'LK', 19, 'Aktif', '2022'),
('12569', 'KATYA MARYAM NURAISYAH', 'PR', 19, 'Aktif', '2022'),
('12570', 'Kayla Alvira Rosa', 'PR', 19, 'Aktif', '2022'),
('12571', 'KEIKO SHAFIRA ATHARYANDRA', 'PR', 19, 'Aktif', '2022'),
('12572', 'KEISHA AQEELA DAANYA', 'PR', 19, 'Aktif', '2022'),
('12573', 'Keiza Azka Hasyura', 'LK', 19, 'Aktif', '2022'),
('12574', 'KEN LAYUNG', 'PR', 19, 'Aktif', '2022'),
('12575', 'MUHAMAD DAMAR', 'LK', 19, 'Aktif', '2022'),
('12576', 'MUHAMAD KHAIRUL HADZAMI', 'LK', 19, 'Aktif', '2022'),
('12577', 'Mukfa Jajuli Hasan', 'LK', 19, 'Aktif', '2022'),
('12578', 'MUTIARA RATU SHAKIRA', 'PR', 19, 'Aktif', '2022'),
('12579', 'NAZELINA BILGINA', 'PR', 19, 'Aktif', '2022'),
('12580', 'PANJI MAULIDAN', 'LK', 19, 'Aktif', '2022'),
('12581', 'RADHITA DINI', 'PR', 19, 'Aktif', '2022'),
('12582', 'RAFKY RIZKI RAMADHAN', 'LK', 19, 'Aktif', '2022'),
('12583', 'RAFLY IRGIANTO', 'LK', 19, 'Aktif', '2022'),
('12584', 'RAYYA PRATAMA', 'LK', 19, 'Aktif', '2022'),
('12585', 'ROSITA TRI WULANDARI', 'PR', 19, 'Aktif', '2022'),
('12586', 'Siti Artika Bestari', 'PR', 19, 'Aktif', '2022'),
('12587', 'SITI HABIBAH HAMZAH LUBIS', 'PR', 19, 'Aktif', '2022'),
('12588', 'SOPHIE NANDAPRASYA', 'PR', 19, 'Aktif', '2022'),
('12589', 'Syabiekha Putri Herrina', 'PR', 19, 'Aktif', '2022'),
('12590', 'WAFYL MIZAN', 'LK', 19, 'Aktif', '2022'),
('12591', 'Alifia Zailanti', 'PR', 18, 'Aktif', '2022'),
('12592', 'AMANDA', 'PR', 18, 'Aktif', '2022'),
('12593', 'AMELIA ANDRIANTI', 'PR', 18, 'Aktif', '2022'),
('12594', 'Ananda Dimas Saputra', 'LK', 18, 'Aktif', '2022'),
('12595', 'ANNISA SITTI MUTIARA TASIK', 'PR', 18, 'Aktif', '2022'),
('12596', 'AULIA NURKAMILAH', 'PR', 18, 'Aktif', '2022'),
('12597', 'Daffa Rafi Al\'Faraz', 'LK', 18, 'Aktif', '2022'),
('12598', 'DIAN RISMITA', 'PR', 18, 'Aktif', '2022'),
('12599', 'DINI OLIVIA', 'PR', 18, 'Aktif', '2022'),
('12600', 'Fedya Suvi Jelila', 'PR', 18, 'Aktif', '2022'),
('12601', 'FINA ANGGRIANI AZZAHRAH', 'PR', 18, 'Aktif', '2022'),
('12602', 'FRISKA PUTRI NAZLA', 'PR', 18, 'Aktif', '2022'),
('12603', 'HIDAYANI FAJRIYAH', 'PR', 18, 'Aktif', '2022'),
('12604', 'Johan Rohmadoni Putra Setiawan', 'LK', 18, 'Aktif', '2022'),
('12606', 'MADYA RAFIKA', 'PR', 18, 'Aktif', '2022'),
('12607', 'MOCHAMAD AKHDAN ALIF FATHAN', 'LK', 18, 'Aktif', '2022'),
('12608', 'Muhamad Sahrul', 'LK', 18, 'Aktif', '2022'),
('12609', 'MUHAMMAD ARYA PRASETYA', 'LK', 18, 'Aktif', '2022'),
('12610', 'NAJLA NURUL ARIFA', 'PR', 18, 'Aktif', '2022'),
('12611', 'NASYA SAPUTRI', 'PR', 18, 'Aktif', '2022'),
('12612', 'Nayla Syafira Yudha', 'PR', 18, 'Aktif', '2022'),
('12613', 'Nayla Zannita', 'PR', 18, 'Aktif', '2022'),
('12614', 'NAZHWATUL RAEWANI', 'PR', 18, 'Aktif', '2022'),
('12615', 'NAZWA AULIA PUTRI', 'PR', 18, 'Aktif', '2022'),
('12616', 'NISPI AMELIA SAIROZI', 'PR', 18, 'Aktif', '2022'),
('12617', 'NUR AZIZAH MUKHTAR', 'PR', 18, 'Aktif', '2022'),
('12618', 'RAHMAH DWI AULIA', 'PR', 18, 'Aktif', '2022'),
('12619', 'REVIE CAHYATI WULANDARI', 'PR', 18, 'Aktif', '2022'),
('12620', 'RIDHO ALI FIRDAUS', 'LK', 18, 'Aktif', '2022'),
('12621', 'SASKIA SHELOMITA ZAHRA', 'PR', 18, 'Aktif', '2022'),
('12622', 'Saskiah', 'PR', 18, 'Aktif', '2022'),
('12623', 'SHAFA NADIYAH UTAMI', 'PR', 18, 'Aktif', '2022'),
('12624', 'SYAFINATUL AWALIA', 'PR', 18, 'Aktif', '2022'),
('12625', 'YESI FALENS BAKO', 'PR', 18, 'Aktif', '2022'),
('12626', 'ZHYLDHA YUKASAWA', 'PR', 18, 'Aktif', '2022'),
('12633', 'AMANDA CALVINA', 'PR', 16, 'Aktif', '2022'),
('12634', 'Carresha Aulya', 'PR', 16, 'Aktif', '2022'),
('12635', 'FARIDZ IKHSAN NUR HAKIM', 'LK', 17, 'Aktif', '2022'),
('12638', 'FARAH NABILAH AZ-ZAHRA', 'PR', 21, 'Aktif', '2022'),
('12639', 'ASYLLA FADYA PUTRI', 'PR', 15, 'Aktif', '2023'),
('12640', 'AYU SYAFIRA KHOIRUNNISA', 'PR', 15, 'Aktif', '2023'),
('12641', 'CAHAYA MELIZA FADHILA', 'PR', 15, 'Aktif', '2023'),
('12643', 'ERLAN SYAHPUTRA', 'LK', 15, 'Aktif', '2023'),
('12644', 'Frans Gabriel Sahala Simanjuntak', 'LK', 15, 'Aktif', '2023'),
('12645', 'HAFIDZA ARINI SETYAWATI', 'PR', 15, 'Aktif', '2023'),
('12646', 'KARINA RACHMADINI', 'PR', 15, 'Aktif', '2023'),
('12647', 'LAZUAR LYADI', 'LK', 15, 'Aktif', '2023'),
('12648', 'MUHAMMAD AIDAL FITRI', 'LK', 15, 'Aktif', '2023'),
('12649', 'MUHAMMAD MAGGIO AL ZAHRAN', 'LK', 15, 'Aktif', '2023'),
('12650', 'Muhammad Satria Lazuardy', 'LK', 15, 'Aktif', '2023'),
('12651', 'Nabila Syafina Mutiara Faradina', 'PR', 15, 'Aktif', '2023'),
('12652', 'NELSON AHMAD AMIRULLAH', 'LK', 15, 'Aktif', '2023'),
('12653', 'RIFKYAN REINWARIN', 'LK', 15, 'Aktif', '2023'),
('12654', 'SHARLIZ ZAHIRA IFRAYA', 'PR', 15, 'Aktif', '2023'),
('12656', 'VANNESA PHILIA BASTIAN', 'PR', 15, 'Aktif', '2023'),
('12657', 'AULIYA AZ-ZAHRA', 'PR', 15, 'Aktif', '2023'),
('12658', 'BUNGA APRILIA MARATUS SHOLIHAH', 'PR', 15, 'Aktif', '2023'),
('12659', 'CHELSEA FELICIA NUR ISA', 'PR', 15, 'Aktif', '2023'),
('12660', 'DASHA BUDI AZARINE', 'PR', 15, 'Aktif', '2023'),
('12661', 'FATHAN ALFARIZI', 'LK', 15, 'Aktif', '2023'),
('12662', 'GALIH MUHAMMAD AFFAN', 'LK', 15, 'Aktif', '2023'),
('12663', 'Juniva Savira', 'PR', 15, 'Aktif', '2023'),
('12664', 'KEYLA EZZAR SUHENDRA', 'PR', 15, 'Aktif', '2023'),
('12665', 'MOCHAMAD AFGAN MUJAHID', 'LK', 15, 'Aktif', '2023'),
('12666', 'MUHAMMAD FAKHRI PRASETYO', 'LK', 15, 'Aktif', '2023'),
('12667', 'MUHAMMAD SAHRIL SAHRUL', 'LK', 15, 'Aktif', '2023'),
('12668', 'NABILA NUR AENI', 'PR', 15, 'Aktif', '2023'),
('12669', 'NAEYLLA FAUZIYYAH', 'PR', 15, 'Aktif', '2023'),
('12671', 'SATRIO RASYA RAMADHAN', 'LK', 15, 'Aktif', '2023'),
('12672', 'SYAKA FADILLAH', 'LK', 15, 'Aktif', '2023'),
('12673', 'TYSON FAUZ AL YAWAN', 'LK', 15, 'Aktif', '2023'),
('12674', 'Vanza Adyatma Fariz', 'LK', 15, 'Aktif', '2023'),
('12675', 'ABDUL FAHRI', 'LK', 13, 'Aktif', '2023'),
('12676', 'ADE DRI ASIH PENERUS', 'PR', 13, 'Aktif', '2023'),
('12677', 'Altata Aura Detya Agustina', 'PR', 13, 'Aktif', '2023'),
('12678', 'ANANDA NUR AULIA', 'PR', 13, 'Aktif', '2023'),
('12679', 'ANDRYANI DEWI MARTHALIA', 'PR', 13, 'Aktif', '2023'),
('12680', 'AYLA ZAHRA HABIBAH', 'PR', 13, 'Aktif', '2023'),
('12681', 'CHINTIYA MARLINA PUTRI', 'PR', 13, 'Aktif', '2023'),
('12683', 'DEVINA ALIKA TRIANDARI', 'PR', 13, 'Aktif', '2023'),
('12684', 'DWI ANNISA AZZARAH', 'PR', 13, 'Aktif', '2023'),
('12685', 'FANI AMELIA FRIDAYANTI', 'PR', 13, 'Aktif', '2023'),
('12686', 'FAREL JUAN SYAHPUTRA', 'LK', 13, 'Aktif', '2023'),
('12687', 'Fitri Az Zahra', 'PR', 13, 'Aktif', '2023'),
('12688', 'GANIS WIDYA PUSPANINGRUM', 'PR', 13, 'Aktif', '2023'),
('12689', 'JENY TRY ANGGRAENI', 'PR', 13, 'Aktif', '2023'),
('12690', 'JOHAN RIRIS ANGGORO', 'LK', 13, 'Aktif', '2023'),
('12691', 'Keiysha Anwar', 'PR', 13, 'Aktif', '2023'),
('12692', 'KEYSIA PUTRI HALIUKI', 'PR', 13, 'Aktif', '2023'),
('12693', 'KRESNA ALFIANSYAH', 'LK', 13, 'Aktif', '2023'),
('12694', 'MOHAMAD FAQIH', 'LK', 13, 'Aktif', '2023'),
('12695', 'MOHAMMAD DANANG PUTRA FATMAN', 'LK', 13, 'Aktif', '2023'),
('12696', 'MUHAMAD SULTAN ZIKRI MULYADI', 'LK', 13, 'Aktif', '2023'),
('12697', 'MUHAMMAD EVANDRA', 'LK', 13, 'Aktif', '2023'),
('12698', 'MUHAMMAD LUTHFI ADZAMI', 'LK', 13, 'Aktif', '2023'),
('12699', 'MUHAMMAD RAFLI', 'LK', 13, 'Aktif', '2023'),
('12700', 'NABILA SYAHDARIN', 'PR', 13, 'Aktif', '2023'),
('12701', 'Naifa Khairunnisa Rifda', 'PR', 13, 'Aktif', '2023'),
('12702', 'NAJWA SALSABILA', 'PR', 13, 'Aktif', '2023'),
('12704', 'Raisya Putri Adelliandi', 'PR', 13, 'Aktif', '2023'),
('12705', 'RISMA NURAINI', 'PR', 13, 'Aktif', '2023'),
('12706', 'SARAH FAUZIYAH', 'PR', 13, 'Aktif', '2023'),
('12707', 'SHERINA ALMALIYA', 'PR', 13, 'Aktif', '2023'),
('12708', 'SITI NUR HALISAH', 'PR', 13, 'Aktif', '2023'),
('12709', 'THANIA MEILIANA', 'PR', 13, 'Aktif', '2023'),
('12710', 'WINDI FEBRIYANI', 'PR', 13, 'Aktif', '2023'),
('12711', 'ABIDAH PRISKA SAHIRAH', 'PR', 14, 'Aktif', '2023'),
('12712', 'ADHWA RAIHANAH HAURA', 'PR', 14, 'Aktif', '2023'),
('12713', 'ALUYA', 'PR', 14, 'Aktif', '2023'),
('12714', 'ANDINI AULIA SALEH', 'PR', 14, 'Aktif', '2023'),
('12715', 'ARIKA ARYANI', 'PR', 14, 'Aktif', '2023'),
('12716', 'Cahaya Lauda', 'PR', 14, 'Aktif', '2023'),
('12717', 'CINDRELLA DEALOVA PUTRI', 'PR', 14, 'Aktif', '2023'),
('12718', 'Darunna Dwinata', 'LK', 14, 'Aktif', '2023'),
('12719', 'Dhea Rahmatika', 'PR', 14, 'Aktif', '2023'),
('12720', 'Fadhila Hasya Putri Dewanti', 'PR', 14, 'Aktif', '2023'),
('12721', 'FARAH HUMAIRAA ELYZA RAMADHANI', 'PR', 14, 'Aktif', '2023'),
('12722', 'FELLYKHA ARDYA HASTIAYUNINGTIAS UTAMA', 'PR', 14, 'Aktif', '2023'),
('12723', 'FITRI FATMA RAMADHANI', 'PR', 14, 'Aktif', '2023'),
('12724', 'HADRA FARIHAH', 'PR', 14, 'Aktif', '2023'),
('12725', 'JESSICA ANASTASYA', 'PR', 14, 'Aktif', '2023'),
('12726', 'KAYLA WAFFA RAMADHANI', 'PR', 14, 'Aktif', '2023'),
('12727', 'Keyla Ramadina', 'PR', 14, 'Aktif', '2023'),
('12728', 'KHAYRA ANGGANI PUTRI SANTOSO', 'PR', 14, 'Aktif', '2023'),
('12729', 'MAULIDYA CHAIRUNNISA', 'PR', 14, 'Aktif', '2023'),
('12730', 'MOHAMAD RISKI MAULANA', 'LK', 14, 'Aktif', '2023'),
('12731', 'MUHAMAD FARRELL FAIR RIZQI', 'LK', 14, 'Aktif', '2023'),
('12732', 'MUHAMMAD CESAR AVISCENA', 'LK', 14, 'Aktif', '2023'),
('12733', 'MUHAMMAD FAREL', 'LK', 14, 'Aktif', '2023'),
('12734', 'Muhammad Rafi Ramadhan', 'LK', 14, 'Aktif', '2023'),
('12735', 'NABILA SAKILA', 'PR', 14, 'Aktif', '2023'),
('12736', 'Nadya Oktaviani', 'PR', 14, 'Aktif', '2023'),
('12737', 'NAJLA SAMARA', 'PR', 14, 'Aktif', '2023'),
('12738', 'NAYSILLA OKTAVIA ANDINIE', 'PR', 14, 'Aktif', '2023'),
('12739', 'Olga Zaskia Ghofani', 'PR', 14, 'Aktif', '2023'),
('12740', 'RISKA PUTRI DEWI', 'PR', 14, 'Aktif', '2023'),
('12741', 'RIZKI SAPUTRA', 'LK', 14, 'Aktif', '2023'),
('12742', 'SHERIL BINTANG CAHYA PUTRI', 'PR', 14, 'Aktif', '2023'),
('12743', 'Siska Amanda', 'PR', 14, 'Aktif', '2023'),
('12744', 'SYIFA NAZHIFAH SULTANY', 'PR', 14, 'Aktif', '2023'),
('12745', 'TIARA NOVA HERLIZA', 'PR', 14, 'Aktif', '2023'),
('12746', 'ZORA ANASTASYA', 'PR', 14, 'Aktif', '2023'),
('12747', 'ACHMAD ATHAR HAFIYAN ZAFIR MUTAZ', 'LK', 10, 'Aktif', '2023'),
('12748', 'ALYA LUTHFIANA', 'PR', 10, 'Aktif', '2023'),
('12750', 'Aura nuzul azhari', 'PR', 10, 'Aktif', '2023'),
('12751', 'AZAHRA HANNY ZULFA', 'PR', 10, 'Aktif', '2023'),
('12752', 'BILLY ARIEF AUDRIYAN', 'LK', 10, 'Aktif', '2023'),
('12753', 'DANISH AZFAR RABBANI', 'LK', 10, 'Aktif', '2023'),
('12754', 'DESIKA ARNITA', 'PR', 10, 'Aktif', '2023'),
('12755', 'DITHA ZALFIAH', 'PR', 10, 'Aktif', '2023'),
('12756', 'Falisha Zamhairani', 'PR', 10, 'Aktif', '2023'),
('12757', 'FATIM SALAS ARROSIH', 'PR', 10, 'Aktif', '2023'),
('12758', 'HANIF RIFQI KHAIRAN', 'LK', 10, 'Aktif', '2023'),
('12759', 'ISYHELL ADITHA IYOY', 'PR', 10, 'Aktif', '2023'),
('12761', 'KHALIPA AZKIA', 'PR', 10, 'Aktif', '2023'),
('12762', 'LANA SOFYANTI', 'PR', 10, 'Aktif', '2023'),
('12763', 'LEYLA PURWANTI', 'PR', 10, 'Aktif', '2023'),
('12764', 'M. ARIF HILMAN WIDYATMOJO', 'LK', 10, 'Aktif', '2023'),
('12765', 'MEILANA ANGGITA PUTRI', 'PR', 10, 'Aktif', '2023'),
('12766', 'MUTHIA NIHAYATUL BADIAH', 'PR', 10, 'Aktif', '2023'),
('12768', 'NADIRA AISHA FAHRAZETA', 'PR', 10, 'Aktif', '2023'),
('12769', 'NAIDA SAFITRI', 'PR', 10, 'Aktif', '2023'),
('12770', 'NAURAH FATIMAH NURRUSSANA', 'PR', 10, 'Aktif', '2023'),
('12771', 'NAZLA AQILA ZAHRA', 'PR', 10, 'Aktif', '2023'),
('12773', 'RAELILA FAJAR DAYU', 'PR', 10, 'Aktif', '2023'),
('12774', 'RASYA RADHITIA PUTRA', 'LK', 10, 'Aktif', '2023'),
('12775', 'RIFQI ULLUM HANAFIS', 'LK', 10, 'Aktif', '2023'),
('12776', 'RORO DWI ASTUTI FEBRIANA', 'PR', 10, 'Aktif', '2023'),
('12777', 'SARAH NADHIFAH', 'PR', 10, 'Aktif', '2023'),
('12778', 'SITI HUMAIROH', 'PR', 10, 'Aktif', '2023'),
('12779', 'SYARIZHMA AZALIA DAFNA', 'PR', 10, 'Aktif', '2023'),
('12780', 'Taliza Putri Firzahra', 'PR', 10, 'Aktif', '2023'),
('12781', 'TITIAN KIRANI', 'PR', 10, 'Aktif', '2023'),
('12782', 'VITA CALISTA JULIANTI', 'PR', 10, 'Aktif', '2023'),
('12783', 'ALFARINDRA SAPUTRA', 'LK', 11, 'Aktif', '2023'),
('12785', 'ANDRISA HARUM JANNAH', 'PR', 11, 'Aktif', '2023'),
('12786', 'ARI BAGUS LAKSONO', 'LK', 11, 'Aktif', '2023'),
('12787', 'AZ ZAHRA NAFEEZA KHUMAIRAH', 'PR', 11, 'Aktif', '2023'),
('12788', 'AZZAHRA AUSHAF FAIZA TRIYANTO', 'PR', 11, 'Aktif', '2023'),
('12789', 'CANTIKA ADELISE PUSPITA IRANI', 'PR', 11, 'Aktif', '2023'),
('12790', 'Dela Saptani', 'PR', 11, 'Aktif', '2023'),
('12791', 'DESY RAHMAWATI', 'PR', 11, 'Aktif', '2023'),
('12792', 'FABYAN RAESYA JUNIOR', 'LK', 11, 'Aktif', '2023'),
('12793', 'FAREL OKTARIANO', 'LK', 11, 'Aktif', '2023'),
('12794', 'HANAYA SURAIDA', 'PR', 11, 'Aktif', '2023'),
('12795', 'Indri Syifa Aulia', 'PR', 11, 'Aktif', '2023'),
('12797', 'KEYSHA AZZAHRA', 'PR', 11, 'Aktif', '2023'),
('12798', 'KHOIRUNNISA', 'PR', 11, 'Aktif', '2023'),
('12799', 'LATHIIF AL RASYIID PRASETIYO', 'LK', 11, 'Aktif', '2023'),
('12800', 'LISYA JUNI SAPUTRI', 'PR', 11, 'Aktif', '2023'),
('12801', 'Marsheila Syifa Anwar', 'PR', 11, 'Aktif', '2023'),
('12802', 'MUHAMMAD IQBAL FAHREZA', 'LK', 11, 'Aktif', '2023'),
('12803', 'MUTIA CANDRA WARDANI', 'PR', 11, 'Aktif', '2023'),
('12804', 'NADIA NUR ALIKA', 'PR', 11, 'Aktif', '2023'),
('12805', 'NAJWA AULIA RAMADHANI', 'PR', 11, 'Aktif', '2023'),
('12806', 'NAYLA LATHIFA AYUDHYA AZZAHRA', 'PR', 11, 'Aktif', '2023'),
('12807', 'NIKEN FEBRIANA PUTRI', 'PR', 11, 'Aktif', '2023'),
('12808', 'QEYLA LEDZITA', 'PR', 11, 'Aktif', '2023'),
('12809', 'RASYA IMAM FAUZI', 'LK', 11, 'Aktif', '2023'),
('12810', 'Reza Abimanyu', 'LK', 11, 'Aktif', '2023'),
('12811', 'RIZKY PRATAMA', 'LK', 11, 'Aktif', '2023'),
('12812', 'SALSYA FITRIA RAHMADANI', 'PR', 11, 'Aktif', '2023'),
('12813', 'Sazkia Maharani', 'PR', 11, 'Aktif', '2023'),
('12814', 'SYAHKIA YUNITA HUMAIRA', 'PR', 11, 'Aktif', '2023'),
('12815', 'Syeila Anastasya Yohan', 'PR', 11, 'Aktif', '2023'),
('12817', 'VIRGIANTI AURA ANGGUN', 'PR', 11, 'Aktif', '2023'),
('12818', 'WINDRY ANGGRAENI', 'PR', 11, 'Aktif', '2023'),
('12819', 'ANANDA FAJAR FEBRIAN', 'LK', 12, 'Aktif', '2023'),
('12820', 'ANINDIA DZULDAH', 'PR', 12, 'Aktif', '2023'),
('12821', 'ANISSA ANANDITHA SOBARI', 'PR', 12, 'Aktif', '2023'),
('12822', 'ARIEL SYARON GONZALES BAKO', 'LK', 12, 'Aktif', '2023'),
('12823', 'CEYSA DWI ARIANI', 'PR', 12, 'Aktif', '2023'),
('12824', 'Cinde Roro Despriyani Priyustisiyo', 'PR', 12, 'Aktif', '2023'),
('12825', 'DEVANO DANENDRA', 'LK', 12, 'Aktif', '2023'),
('12826', 'FAUZAN PRADITYA DERMANA', 'LK', 12, 'Aktif', '2023'),
('12827', 'HANA SALMA IRTIYAH', 'PR', 12, 'Aktif', '2023'),
('12828', 'Hendi Saputra', 'LK', 12, 'Aktif', '2023'),
('12829', 'IQBAL FAZMAR KHOLIDIN', 'LK', 12, 'Aktif', '2023'),
('12830', 'IRHAM FADHIL HAQ SITOHANG', 'LK', 12, 'Aktif', '2023'),
('12832', 'KALILA RAFA FAUZIYYAH', 'PR', 12, 'Aktif', '2023'),
('12833', 'KEISYA AZAITA', 'PR', 12, 'Aktif', '2023'),
('12834', 'KHALILA MUTIARA SHAUMY', 'PR', 12, 'Aktif', '2023'),
('12835', 'KIKI ARYA PUTRA', 'LK', 12, 'Aktif', '2023'),
('12836', 'KRESNA PUTRA LISTANTO', 'LK', 12, 'Aktif', '2023'),
('12837', 'MAULIDYA PUTRI DEYAS', 'PR', 12, 'Aktif', '2023'),
('12838', 'MUHAMMAD ADITYA DWIANTORO', 'LK', 12, 'Aktif', '2023'),
('12839', 'MUHAMMAD AINUR ROFIQ', 'LK', 12, 'Aktif', '2023'),
('12840', 'MUHAMMAD FIRDAUS', 'LK', 12, 'Aktif', '2023'),
('12841', 'MUHAMMAD KEVIN MANDAFI', 'LK', 12, 'Aktif', '2023'),
('12842', 'MUHAMMAD RAFIQ', 'LK', 12, 'Aktif', '2023'),
('12843', 'Muhammad Raihan', 'LK', 12, 'Aktif', '2023'),
('12844', 'NAYLA AULIA ANINDITA', 'PR', 12, 'Aktif', '2023'),
('12845', 'NAYLA MARCHSANTY', 'PR', 12, 'Aktif', '2023'),
('12846', 'NIDA WISQA AUFANI', 'PR', 12, 'Aktif', '2023'),
('12847', 'PELANGI RAHMADINY', 'PR', 12, 'Aktif', '2023'),
('12848', 'Putri Aprilia', 'PR', 12, 'Aktif', '2023'),
('12849', 'RADIAN SURYA PRATAMA', 'LK', 12, 'Aktif', '2023'),
('12850', 'RAVI RAFAEL', 'LK', 12, 'Aktif', '2023'),
('12851', 'RIFKY', 'LK', 12, 'Aktif', '2023'),
('12852', 'ROUBIAH ADAWIYAH', 'PR', 12, 'Aktif', '2023'),
('12853', 'SEKAR YULI LESTARI', 'PR', 12, 'Aktif', '2023'),
('12854', 'SURYA ABADI', 'LK', 12, 'Aktif', '2023'),
('12855', 'AHMAD JULIANTO', 'LK', 18, 'Aktif', '2022'),
('12856', 'MARSHALL BRYANT MANALU', 'LK', 19, 'Aktif', '2022'),
('12857', 'AMANDA AVRILIANY', 'PR', 10, 'Aktif', '2023'),
('12859', 'FATHIYA ADIBA', 'PR', 15, 'Aktif', '2023'),
('12860', 'JASMINE NAJLA AZAHRA', 'PR', 15, 'Aktif', '2023'),
('12861', 'KEISHA AZZAHRA', 'PR', 13, 'Aktif', '2023'),
('12862', 'NABILA APRILIA PUTRI', 'PR', 11, 'Aktif', '2023'),
('12863', 'NAURA FELISSA', 'PR', 11, 'Aktif', '2023'),
('12864', 'SARAH MEIDINA ARMIATY', 'PR', 13, 'Aktif', '2023'),
('12865', 'ABU WAFA AL-MUSYAFFA\'', 'LK', 6, 'Aktif', '2023'),
('12866', 'AHMAD FACHLEVI', 'LK', 6, 'Aktif', '2023'),
('12867', 'AISYAH AMALIAH', 'PR', 6, 'Aktif', '2023'),
('12868', 'Ajeng Risma Yanti', 'PR', 6, 'Aktif', '2023'),
('12869', 'ALIMA FAIQO NISSA', 'PR', 6, 'Aktif', '2023'),
('12870', 'Alvero Kurniadi', 'LK', 6, 'Aktif', '2023'),
('12871', 'APRILIA PUTRI KIRANA', 'PR', 6, 'Aktif', '2023'),
('12872', 'ARSYA PRATAMA JICHOATA', 'LK', 6, 'Aktif', '2023'),
('12873', 'ATHAYA NAILA SYAFIYYAH', 'PR', 6, 'Aktif', '2023'),
('12874', 'AUNI FIRDINA ALEXANDRIA', 'PR', 6, 'Aktif', '2023'),
('12875', 'AYU DWI KHAIRANI', 'PR', 6, 'Aktif', '2023'),
('12876', 'BAIHAQI AHMAD RIFAIL', 'LK', 6, 'Aktif', '2023'),
('12877', 'BALQIS ASHILAH RAMADHANI', 'PR', 6, 'Aktif', '2023'),
('12878', 'BILKYS SYAWALILA NARUTAMI', 'PR', 6, 'Aktif', '2023'),
('12879', 'DHANY FAYYADHI ZHAFAR', 'LK', 6, 'Aktif', '2023'),
('12880', 'Hana Evangelica', 'PR', 6, 'Aktif', '2023'),
('12882', 'Kenisha Ayudia Wijanarko', 'PR', 6, 'Aktif', '2023'),
('12883', 'KHEYRIL IBAD AZIZ', 'LK', 6, 'Aktif', '2023'),
('12884', 'KIARA BELLA ARISTA', 'PR', 6, 'Aktif', '2023'),
('12885', 'LUTFIAH KHUMAIROH', 'PR', 6, 'Aktif', '2023'),
('12886', 'Maulana Ibrahim', 'LK', 6, 'Aktif', '2023'),
('12887', 'MEILITA INDRIYATI', 'PR', 6, 'Aktif', '2023'),
('12888', 'MUHAMMAD HIJR SANI JAARULAAH', 'LK', 6, 'Aktif', '2023'),
('12889', 'MUHAMMAD IQBAL FARRAS', 'LK', 6, 'Aktif', '2023'),
('12890', 'Nafis', 'LK', 6, 'Aktif', '2023'),
('12892', 'OSEA HEZA ISBANDI', 'LK', 6, 'Aktif', '2023'),
('12893', 'Rafizah Ihsana', 'PR', 6, 'Aktif', '2023'),
('12894', 'RAISYA AURA ALFARRASI', 'PR', 6, 'Aktif', '2023'),
('12895', 'SAHRA ABIDAH ARDELIA', 'PR', 6, 'Aktif', '2023'),
('12896', 'SASKIA ARDANI ARIF', 'PR', 6, 'Aktif', '2023'),
('12897', 'SUCI DWIARTHA MUKTI', 'PR', 6, 'Aktif', '2023'),
('12898', 'TRIA LUVI ZASKIA', 'PR', 6, 'Aktif', '2023'),
('12899', 'VIQRY NUR IHSAN', 'LK', 6, 'Aktif', '2023'),
('12900', 'WULAN FEBRIYANTI', 'PR', 6, 'Aktif', '2023'),
('12901', 'ABDURROHMAN SOLEH', 'LK', 8, 'Aktif', '2023'),
('12902', 'AHMAD FAUZI', 'LK', 9, 'Aktif', '2023'),
('12903', 'ALIFKA SILVIANDINI SANTOSO', 'PR', 8, 'Aktif', '2023'),
('12904', 'ALZENA GALADRIEL AIUDIA', 'PR', 9, 'Aktif', '2023'),
('12905', 'ANISA PUTRI RAMADHANI', 'PR', 8, 'Aktif', '2023'),
('12906', 'ANNABINA VAGATHA HIDAYAT', 'PR', 9, 'Aktif', '2023'),
('12907', 'ARUM KHUMAIROH', 'PR', 8, 'Aktif', '2023'),
('12908', 'ASHILA PUTRI FEBRYAN', 'PR', 9, 'Aktif', '2023'),
('12909', 'ASYIFA RIZZATUNNISA', 'PR', 8, 'Aktif', '2023'),
('12910', 'AULIA RIZKY ALFIRA', 'PR', 9, 'Aktif', '2023'),
('12911', 'AZAHRA SAIDATINA PATIMAH', 'PR', 8, 'Aktif', '2023'),
('12912', 'BAMBANG PUTRA DJAYA', 'LK', 9, 'Aktif', '2023'),
('12913', 'Bayu Fathur Rahman', 'LK', 8, 'Aktif', '2023'),
('12914', 'BUNGA ARGADIA PRATIWI', 'PR', 9, 'Aktif', '2023'),
('12915', 'CARRISA ANAYA', 'PR', 8, 'Aktif', '2023'),
('12916', 'CEISYA PUTRI ANINDRA', 'PR', 9, 'Aktif', '2023'),
('12917', 'CHALISTA AFIFA SYAHIRA', 'PR', 8, 'Aktif', '2023'),
('12918', 'DEVI BINTANG SEGAMI', 'PR', 9, 'Aktif', '2023'),
('12919', 'DEVI CITRA GHANI', 'PR', 8, 'Aktif', '2023'),
('12920', 'DEVI FIBRIYANI', 'PR', 9, 'Aktif', '2023'),
('12921', 'DHAIFULLAH AQIL HADI WINATA', 'LK', 8, 'Aktif', '2023'),
('12922', 'DIKY FEBRIYANTO', 'LK', 9, 'Aktif', '2023'),
('12923', 'Dimas Hanafi', 'LK', 8, 'Aktif', '2023'),
('12924', 'DZIKRAH TALITA ZAHRA', 'PR', 9, 'Aktif', '2023'),
('12925', 'ELFIRA NURJANNAH', 'PR', 8, 'Aktif', '2023'),
('12926', 'FADLI ADRIANSYAH', 'LK', 9, 'Aktif', '2023'),
('12927', 'FITRIA OKTAVIANI', 'PR', 8, 'Aktif', '2023'),
('12928', 'GAVRILA AURELIA PUTRI', 'PR', 9, 'Aktif', '2023'),
('12929', 'HERNINA DWI LESTARI', 'PR', 8, 'Aktif', '2023'),
('12930', 'IBNU KHAHFI', 'LK', 9, 'Aktif', '2023'),
('12931', 'INTAN MAULINA', 'PR', 8, 'Aktif', '2023'),
('12932', 'KALLISTA QUEENA', 'PR', 9, 'Aktif', '2023'),
('12933', 'KAMIL AGUNG NURDIANSYAH', 'LK', 8, 'Aktif', '2023'),
('12934', 'KANAYA PUTRI NARAYA', 'PR', 9, 'Aktif', '2023'),
('12935', 'KAYLA ANJANI', 'PR', 8, 'Aktif', '2023'),
('12936', 'KAYLA AZZAHRA', 'PR', 9, 'Aktif', '2023'),
('12937', 'KAYLA SABRINA NASYA', 'PR', 8, 'Aktif', '2023'),
('12938', 'KEISHA FARRAS AZIZ', 'PR', 9, 'Aktif', '2023'),
('12939', 'Keyla Moza Rahmadhani', 'PR', 8, 'Aktif', '2023'),
('12940', 'Keyza Malik', 'LK', 9, 'Aktif', '2023'),
('12941', 'KHOFIAN NISA', 'PR', 8, 'Aktif', '2023'),
('12942', 'KHUMAIROH KHANAYAH SELLY', 'PR', 9, 'Aktif', '2023'),
('12943', 'LUNA SAFITRI', 'PR', 8, 'Aktif', '2023'),
('12944', 'LYLA ZAHRA ASHARI', 'PR', 9, 'Aktif', '2023'),
('12945', 'MADINAHTUN JANNAH', 'PR', 8, 'Aktif', '2023'),
('12946', 'MUHAMAD FIKAR AL MAHDI', 'LK', 9, 'Aktif', '2023'),
('12947', 'MUHAMMAD FAHRI', 'LK', 8, 'Aktif', '2023'),
('12948', 'MUHAMMAD RASYID RIDHO', 'LK', 9, 'Aktif', '2023'),
('12949', 'MUHAMMAD SOFWAN MAULIDI', 'LK', 8, 'Aktif', '2023'),
('12950', 'NABILA PUTRI', 'PR', 9, 'Aktif', '2023'),
('12951', 'NADIA BUDIYONO', 'PR', 8, 'Aktif', '2023'),
('12952', 'NAILA PUTRI RAMA DHANTI', 'PR', 9, 'Aktif', '2023'),
('12953', 'NAYLA YUSMA MEI SARI', 'PR', 8, 'Aktif', '2023'),
('12954', 'NUR JIHAN ATMAJA', 'PR', 9, 'Aktif', '2023'),
('12955', 'Olivia Fitriani Imansyah', 'PR', 8, 'Aktif', '2023'),
('12956', 'QAYRA VINAYA ERGA', 'PR', 9, 'Aktif', '2023'),
('12957', 'RAFFAEL JONATHAN FAIZ', 'LK', 8, 'Aktif', '2023'),
('12958', 'RAISYA NAZIFAH', 'PR', 9, 'Aktif', '2023'),
('12959', 'RASYA ARDIA', 'PR', 8, 'Aktif', '2023'),
('12960', 'RATU SYIFA AISYAH', 'PR', 9, 'Aktif', '2023'),
('12961', 'SABILI ZAIDAN AHMAD', 'LK', 8, 'Aktif', '2023'),
('12962', 'SABRINA AL ADHA', 'PR', 9, 'Aktif', '2023'),
('12963', 'SAKINAH NURSYAFA', 'PR', 8, 'Aktif', '2023'),
('12964', 'SALWA KHANSA ANANDADIVA', 'PR', 9, 'Aktif', '2023'),
('12965', 'SAZKYA AZLYKA SUSANTO', 'PR', 8, 'Aktif', '2023'),
('12966', 'SENDI HARDIANSYAH', 'LK', 9, 'Aktif', '2023'),
('12967', 'SYAFA AULIA', 'PR', 8, 'Aktif', '2023'),
('12968', 'SYAFA AULIA RAHMAT', 'PR', 9, 'Aktif', '2023'),
('12969', 'TYAS NUR AZIZAH', 'PR', 8, 'Aktif', '2023'),
('12970', 'WULAN DESTIA NINGRUM', 'PR', 9, 'Aktif', '2023'),
('12971', 'ZAHRA TALITA DZAKIRA', 'PR', 8, 'Aktif', '2023'),
('12972', 'Zaki Vito Herdiansyah', 'LK', 9, 'Aktif', '2023'),
('12973', 'ADINDA RAHADATUL\'AISY SALSABILA', 'PR', 4, 'Aktif', '2024'),
('12974', 'AISYAH PUTRI NUR AFYANI', 'PR', 5, 'Aktif', '2023'),
('12975', 'AISYLA ZAKIRAH', 'PR', 4, 'Aktif', '2024'),
('12976', 'ALFIERA RAHMA NURAINI', 'PR', 5, 'Aktif', '2023'),
('12977', 'ALFINA KHAFFILIANI SANTOSA', 'PR', 4, 'Aktif', '2024'),
('12978', 'ALIF PUTRA NURHIDAYAT', 'LK', 5, 'Aktif', '2023'),
('12979', 'AMELIA BELLA INDRIYANA', 'PR', 4, 'Aktif', '2024'),
('12980', 'ANANDA SARI', 'PR', 5, 'Aktif', '2023'),
('12981', 'ANANDA SEPIA', 'PR', 4, 'Aktif', '2024'),
('12982', 'ANDIKA ALWIANSYAH', 'LK', 5, 'Aktif', '2023'),
('12983', 'ANDRA ADITIYA SYAMIL', 'LK', 4, 'Aktif', '2024'),
('12984', 'ANNISA ZAHRA', 'PR', 5, 'Aktif', '2023'),
('12985', 'ARYA RAISYA AQILLA', 'LK', 4, 'Aktif', '2024'),
('12986', 'ASHILAH WARDAH SOFYAN', 'PR', 5, 'Aktif', '2023'),
('12987', 'BILQIS HUMAIRAH', 'PR', 4, 'Aktif', '2024'),
('12988', 'CARISSA ANASTASYA', 'PR', 5, 'Aktif', '2023'),
('12989', 'CHAERUN NISA', 'PR', 4, 'Aktif', '2024'),
('12990', 'Dinara Aisha Rubiya', 'PR', 5, 'Aktif', '2023'),
('12991', 'DINDA DWI RAMADHANI', 'PR', 4, 'Aktif', '2024'),
('12992', 'DIVA FIBRIYANA', 'PR', 5, 'Aktif', '2023'),
('12993', 'ESA PUTRA PRATAMA', 'LK', 4, 'Aktif', '2024'),
('12994', 'FEBRIYAN NAAFIL SYAHPUTRA', 'LK', 5, 'Aktif', '2023'),
('12995', 'FIFI DISTA KUMALA', 'PR', 4, 'Aktif', '2024'),
('12996', 'GHASSANI ARIANISA', 'PR', 5, 'Aktif', '2023'),
('12997', 'HAFIKA FIRIZKI', 'PR', 4, 'Aktif', '2024'),
('12998', 'HERLIEN PENINDA NUGROHO', 'PR', 5, 'Aktif', '2023'),
('12999', 'IQBAL DWI SAPUTRA', 'LK', 4, 'Aktif', '2024'),
('13000', 'IRSYAD SAKHA KARWANTO', 'LK', 5, 'Aktif', '2023'),
('13001', 'IYZUN HANIF MABYNA', 'LK', 4, 'Aktif', '2024'),
('13002', 'KAISHARA RUMARI CHANTENA', 'PR', 5, 'Aktif', '2023'),
('13003', 'KEISYA ALYA HASANAH', 'PR', 4, 'Aktif', '2024'),
('13004', 'LINA NURAINI', 'PR', 5, 'Aktif', '2023'),
('13005', 'LOUISA NAULI DOSNIROHA', 'PR', 4, 'Aktif', '2024'),
('13006', 'MUHAMMAD AKHDAN ZIYAD', 'LK', 5, 'Aktif', '2023'),
('13007', 'MUHAMMAD ALBAR AL\'ABBAS', 'LK', 4, 'Aktif', '2024'),
('13008', 'MUHAMMAD ERLINGGA FADILAH', 'LK', 5, 'Aktif', '2023'),
('13009', 'MUHAMMAD EVAN JUNIOR', 'LK', 4, 'Aktif', '2024'),
('13010', 'MUHAMMAD FAUZI ELFAHMI', 'LK', 5, 'Aktif', '2023'),
('13011', 'MUHAMMAD FERDRIYAN', 'LK', 4, 'Aktif', '2024'),
('13012', 'MUHAMMAD MARCELLO SULTAN HALIM', 'LK', 5, 'Aktif', '2023'),
('13013', 'MUJADHIDAH BINTI RAJIB', 'PR', 4, 'Aktif', '2024'),
('13014', 'NADYA ANANDA YULIANTI', 'PR', 5, 'Aktif', '2023'),
('13015', 'NADYA SAFIRA AULIA PRISCA', 'PR', 4, 'Aktif', '2024'),
('13016', 'NAFISA ALMA PUTRI', 'PR', 5, 'Aktif', '2023'),
('13017', 'NAOMI GRISDAYANTI PANDIANGAN', 'PR', 4, 'Aktif', '2024'),
('13018', 'NAZHIRA AZMI NUGRAHA', 'PR', 5, 'Aktif', '2023'),
('13019', 'NESHA PUTRI RISAMBAS', 'PR', 4, 'Aktif', '2024'),
('13020', 'NIVALA AUDIA TAMA', 'PR', 5, 'Aktif', '2023'),
('13021', 'PUTRI RAMADHANI', 'PR', 4, 'Aktif', '2024'),
('13022', 'QUINSHA AQUILLA ZARA', 'PR', 5, 'Aktif', '2023'),
('13023', 'RABIAH ALADAWIYAH', 'PR', 4, 'Aktif', '2024'),
('13024', 'RADIN MULYA ADZANI', 'LK', 5, 'Aktif', '2023'),
('13025', 'RAISSA YUMNA YULIANDRY', 'PR', 4, 'Aktif', '2024'),
('13026', 'RANIA CAHAYA HARSANI', 'PR', 5, 'Aktif', '2023'),
('13027', 'SAFIRA KHAIRUNNISA', 'PR', 4, 'Aktif', '2024'),
('13028', 'SALWAH FITRI HUMAIRAH', 'PR', 5, 'Aktif', '2023'),
('13029', 'SHAFHA ZIA AL BACHTIAR', 'PR', 4, 'Aktif', '2024'),
('13030', 'SHAFRI YUSUF ZAMZAMI', 'LK', 5, 'Aktif', '2023'),
('13031', 'SHILVY ADELIA EFFENDI', 'PR', 4, 'Aktif', '2024'),
('13032', 'SIFA NURPADILAH', 'PR', 5, 'Aktif', '2023'),
('13033', 'SIPA AULIA PUTRI', 'PR', 4, 'Aktif', '2024'),
('13034', 'SITI MALIKAH', 'PR', 5, 'Aktif', '2023'),
('13035', 'SITI ZAHRA AZHARI', 'PR', 4, 'Aktif', '2024'),
('13036', 'SUCI NUR RAHMAWATI', 'PR', 5, 'Aktif', '2023'),
('13037', 'SYAKILAH CAHAYA FITRI', 'PR', 4, 'Aktif', '2024'),
('13038', 'SYEINA CAHYA CAMILA', 'PR', 5, 'Aktif', '2023'),
('13039', 'TITENIA KENES LARASATI', 'PR', 4, 'Aktif', '2024'),
('13040', 'WIJAYA KUSUMA', 'LK', 5, 'Aktif', '2023'),
('13041', 'WINDY PRASTYWY', 'PR', 4, 'Aktif', '2024'),
('13042', 'ZAHRA FATONAH MULIA', 'PR', 5, 'Aktif', '2023'),
('13043', 'ZAKI ARIFIN', 'LK', 4, 'Aktif', '2024'),
('13044', 'ZALIKA SYAFIRAHMA', 'PR', 5, 'Aktif', '2023'),
('13045', 'ABDUL RAHMAN SETIAWAN', 'LK', 7, 'Aktif', '2023'),
('13046', 'ADELA BENITA', 'PR', 7, 'Aktif', '2023'),
('13047', 'AZIZAH FAHIRAH', 'PR', 7, 'Aktif', '2023'),
('13048', 'CESSA FAIZ RAMADHAN', 'LK', 7, 'Aktif', '2023'),
('13049', 'DESLA ARIANA INDAH', 'PR', 7, 'Aktif', '2023'),
('13050', 'DIKA FEBRIYANTO', 'LK', 7, 'Aktif', '2023'),
('13051', 'ELSE ARSITA DEWI', 'PR', 7, 'Aktif', '2023'),
('13052', 'FADHLU AZDHAR SETYA WARDANA', 'LK', 7, 'Aktif', '2023'),
('13053', 'FITRAH IKHWANTI', 'PR', 7, 'Aktif', '2023'),
('13054', 'GINA AULIA MAHARANI', 'PR', 7, 'Aktif', '2023'),
('13055', 'IKRAM KHAIRIYAH', 'LK', 7, 'Aktif', '2023'),
('13056', 'ITSNAINI RAUDHATUZ ZAHRA', 'PR', 7, 'Aktif', '2023'),
('13057', 'JESSICA PUTRI AMELIA', 'PR', 7, 'Aktif', '2023'),
('13058', 'JHUANITA SYAHLU VINATA', 'PR', 7, 'Aktif', '2023'),
('13059', 'KAYLA MAHDIAH HALABI', 'PR', 7, 'Aktif', '2023'),
('13060', 'KAYLILA PUTRI MAULIDIA', 'PR', 7, 'Aktif', '2023'),
('13061', 'KEIRA SALSABILA', 'PR', 7, 'Aktif', '2023'),
('13062', 'KEYLA PUTRI NURAISYAH', 'PR', 7, 'Aktif', '2023'),
('13063', 'KHALISA PUTRI AZAHRA', 'PR', 7, 'Aktif', '2023'),
('13064', 'KIRANIA DWI TRISTIHADI', 'PR', 7, 'Aktif', '2023'),
('13065', 'LORA PURDANINGSIH', 'PR', 7, 'Aktif', '2023'),
('13066', 'MUHAMMAD RIZAL AQBAR', 'LK', 7, 'Aktif', '2023'),
('13067', 'NAJWA SALSABILA', 'PR', 7, 'Aktif', '2023'),
('13068', 'NAZWA KHUMAYRA', 'PR', 7, 'Aktif', '2023'),
('13069', 'NOVAL', 'LK', 7, 'Aktif', '2023'),
('13070', 'RAFI AZZAM AR RASYID', 'LK', 7, 'Aktif', '2023'),
('13071', 'RAISSA SANTANA', 'PR', 7, 'Aktif', '2023'),
('13072', 'RAKA KELANA PUTRA', 'LK', 7, 'Aktif', '2023'),
('13073', 'RASYA AL-GHIFARI', 'LK', 7, 'Aktif', '2023'),
('13074', 'RIDHO KUSUMA DANI', 'LK', 7, 'Aktif', '2023'),
('13075', 'RISKA ERNI BALI CAKRAVORTY', 'PR', 7, 'Aktif', '2023'),
('13076', 'RUZAIN ANWAR', 'LK', 7, 'Aktif', '2023'),
('13077', 'SABRINA PUTRI', 'PR', 7, 'Aktif', '2023'),
('13078', 'SAHWA KHOERUNNISA LARASATI', 'PR', 7, 'Aktif', '2023'),
('13079', 'SASKIA RAHMAWATI', 'PR', 7, 'Aktif', '2023'),
('13080', 'VIANKA VINDI KUMAR', 'PR', 7, 'Aktif', '2023'),
('13081', 'TASYA INDIRA', 'PR', 15, 'Aktif', '2023'),
('13082', 'DEVA SAHWA PRATAMA PUTRA', 'LK', 10, 'Aktif', '2023'),
('13083', 'MUCHAMMAD ARYA RAMADHAN', 'LK', 10, 'Aktif', '2024'),
('13084', 'GALIH PRATAMA', 'LK', 12, 'Aktif', '2023');

-- --------------------------------------------------------

--
-- Table structure for table `tb_tabungan`
--

CREATE TABLE `tb_tabungan` (
  `id_tabungan` int(11) NOT NULL,
  `nis` char(12) NOT NULL,
  `setor` int(11) NOT NULL,
  `tarik` int(11) NOT NULL,
  `tgl` date NOT NULL,
  `jenis` enum('ST','TR') NOT NULL,
  `petugas` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_kelas`
--
ALTER TABLE `tb_kelas`
  ADD PRIMARY KEY (`id_kelas`);

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
  MODIFY `id_tabungan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- Constraints for dumped tables
--

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
