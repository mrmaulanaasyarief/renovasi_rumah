-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 02, 2021 at 03:41 PM
-- Server version: 10.4.10-MariaDB
-- PHP Version: 7.3.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `renovasi_rumah`
--

-- --------------------------------------------------------

--
-- Table structure for table `akun`
--

CREATE TABLE `akun` (
  `username` varchar(50) NOT NULL,
  `pwd` varchar(32) NOT NULL,
  `last_login` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `akun`
--

INSERT INTO `akun` (`username`, `pwd`, `last_login`) VALUES
('admin', '21232f297a57a5a743894a0e4a801fc3', '2021-01-27 18:24:27');

-- --------------------------------------------------------

--
-- Table structure for table `coba`
--

CREATE TABLE `coba` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `id_customer` varchar(15) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `alamat` varchar(100) NOT NULL,
  `no_hp` varchar(13) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`id_customer`, `nama`, `alamat`, `no_hp`) VALUES
('', 'ujang', 'jakarta', '0555646464'),
('001', 'krisna', 'bandung', '89697113656');

-- --------------------------------------------------------

--
-- Table structure for table `detail_trans_supplier`
--

CREATE TABLE `detail_trans_supplier` (
  `id_detail_trans` varchar(15) NOT NULL,
  `id_transaksi` varchar(15) NOT NULL,
  `id_supplier` varchar(15) NOT NULL,
  `harga` int(11) NOT NULL,
  `total_diskon` int(11) NOT NULL,
  `total_bayar` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `jasa_desain`
--

CREATE TABLE `jasa_desain` (
  `id_jasa_desain` varchar(15) NOT NULL,
  `jenis_jasa_desain` varchar(100) NOT NULL,
  `tipe_desain` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `material`
--

CREATE TABLE `material` (
  `id_material` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `jenis_material` varchar(100) NOT NULL,
  `satuan` varchar(100) NOT NULL,
  `harga` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `material`
--

INSERT INTO `material` (`id_material`, `nama`, `jenis_material`, `satuan`, `harga`) VALUES
(1, 'Semen', 'Bahan', 'Kg', 500000),
(5, 'Obeng', 'Alat', 'cm', 35000),
(6, 'pasir', 'Bahan', 'Kg', 300000),
(8, 'Batu bata', 'Bahan', 'Kg', 1000000);

-- --------------------------------------------------------

--
-- Table structure for table `pegawai`
--

CREATE TABLE `pegawai` (
  `id_pegawai` varchar(15) NOT NULL,
  `nama_pegawai` varchar(100) NOT NULL,
  `alamat_pegawai` varchar(100) NOT NULL,
  `telp_pegawai` int(11) NOT NULL,
  `jenis_pegawai` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `pembayaran`
--

CREATE TABLE `pembayaran` (
  `id_pembayaran` int(11) NOT NULL,
  `id_pesan` int(11) NOT NULL,
  `no_kuitansi` varchar(100) NOT NULL,
  `tgl_bayar` date NOT NULL,
  `besar_bayar` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `pemesanan`
--

CREATE TABLE `pemesanan` (
  `id_pesan` int(11) NOT NULL,
  `id_material` int(11) NOT NULL,
  `tanggal_pesan` date NOT NULL,
  `tanggal_ambil` date NOT NULL,
  `status_bayar` varchar(100) NOT NULL,
  `total_trans` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pemesanan`
--

INSERT INTO `pemesanan` (`id_pesan`, `id_material`, `tanggal_pesan`, `tanggal_ambil`, `status_bayar`, `total_trans`) VALUES
(11, 1, '2021-04-19', '2021-04-26', 'Lunas', 450000),
(12, 5, '2021-04-19', '2021-04-26', 'Lunas', 30000),
(13, 5, '2021-04-19', '2021-04-26', 'Lunas', 30000),
(14, 6, '2021-04-12', '2021-04-19', 'Belum Lunas', 250000),
(15, 6, '2021-04-12', '2021-04-19', 'Belum Lunas', 250000),
(16, 8, '2021-04-19', '2021-04-12', 'Lunas', 900000),
(17, 8, '2021-04-19', '2021-04-12', 'Lunas', 900000),
(18, 5, '2021-04-19', '2021-04-26', 'Lunas', 30000),
(19, 5, '2021-04-19', '2021-04-26', 'Lunas', 30000),
(20, 5, '2021-04-19', '2021-04-26', 'Lunas', 30000),
(21, 5, '2021-04-19', '2021-04-26', 'Lunas', 30000),
(22, 1, '2021-04-19', '2021-04-19', 'Lunas', 500000),
(23, 1, '2021-04-19', '2021-04-26', 'Lunas', 450000),
(24, 6, '2021-04-19', '2021-04-26', 'Lunas', 250000),
(25, 1, '2021-04-13', '2021-04-26', 'Lunas', 450000),
(26, 1, '2021-04-19', '2021-04-26', 'Lunas', 400000),
(27, 6, '2021-04-13', '2021-04-13', 'Lunas', 250000),
(28, 1, '2021-04-13', '2021-04-20', 'Lunas', 400000),
(29, 5, '2021-04-13', '2021-04-20', 'Lunas', 30000),
(30, 8, '2021-04-03', '2021-04-10', 'Lunas', 900000),
(31, 1, '2021-04-17', '2021-04-21', 'Lunas', 450000);

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `id_supplier` varchar(15) NOT NULL,
  `nama_supplier` varchar(100) NOT NULL,
  `alamat_supplier` varchar(100) NOT NULL,
  `telepon_supplier` varchar(13) NOT NULL,
  `jenis_material` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `transaksi_customer`
--

CREATE TABLE `transaksi_customer` (
  `id_trans_customer` varchar(15) NOT NULL,
  `id_customer` varchar(15) NOT NULL,
  `jenis_renovasi` varchar(100) NOT NULL,
  `total_renovasi` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `transaksi_jasa_desain`
--

CREATE TABLE `transaksi_jasa_desain` (
  `id_trans_jasa_desain` varchar(15) NOT NULL,
  `id_jasa_desain` varchar(100) NOT NULL,
  `total_bayar` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `transaksi_pegawai`
--

CREATE TABLE `transaksi_pegawai` (
  `id_trans_pegawai` varchar(15) NOT NULL,
  `jumlah_hari` int(11) NOT NULL,
  `gaji` int(11) NOT NULL,
  `total_gaji` int(11) NOT NULL,
  `id_pegawai` varchar(15) DEFAULT NULL,
  `tanggal_transaksi` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `transaksi_supplier`
--

CREATE TABLE `transaksi_supplier` (
  `id_trans_supplier` varchar(15) NOT NULL,
  `tanggal_trans_supplier` date NOT NULL,
  `diskon` decimal(10,0) NOT NULL,
  `ongkos_kirim` int(11) NOT NULL,
  `total_jual` int(11) NOT NULL,
  `posisi_dr_cr` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `akun`
--
ALTER TABLE `akun`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `coba`
--
ALTER TABLE `coba`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`id_customer`);

--
-- Indexes for table `detail_trans_supplier`
--
ALTER TABLE `detail_trans_supplier`
  ADD PRIMARY KEY (`id_detail_trans`);

--
-- Indexes for table `jasa_desain`
--
ALTER TABLE `jasa_desain`
  ADD PRIMARY KEY (`id_jasa_desain`);

--
-- Indexes for table `material`
--
ALTER TABLE `material`
  ADD PRIMARY KEY (`id_material`);

--
-- Indexes for table `pegawai`
--
ALTER TABLE `pegawai`
  ADD PRIMARY KEY (`id_pegawai`);

--
-- Indexes for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`id_pembayaran`);

--
-- Indexes for table `pemesanan`
--
ALTER TABLE `pemesanan`
  ADD PRIMARY KEY (`id_pesan`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`id_supplier`);

--
-- Indexes for table `transaksi_customer`
--
ALTER TABLE `transaksi_customer`
  ADD PRIMARY KEY (`id_trans_customer`),
  ADD KEY `id_customer` (`id_customer`);

--
-- Indexes for table `transaksi_jasa_desain`
--
ALTER TABLE `transaksi_jasa_desain`
  ADD PRIMARY KEY (`id_trans_jasa_desain`),
  ADD KEY `fk_transjasa` (`id_jasa_desain`);

--
-- Indexes for table `transaksi_pegawai`
--
ALTER TABLE `transaksi_pegawai`
  ADD PRIMARY KEY (`id_trans_pegawai`),
  ADD KEY `fk_trans_pegawai` (`id_pegawai`);

--
-- Indexes for table `transaksi_supplier`
--
ALTER TABLE `transaksi_supplier`
  ADD PRIMARY KEY (`id_trans_supplier`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `coba`
--
ALTER TABLE `coba`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `material`
--
ALTER TABLE `material`
  MODIFY `id_material` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `id_pembayaran` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pemesanan`
--
ALTER TABLE `pemesanan`
  MODIFY `id_pesan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `transaksi_jasa_desain`
--
ALTER TABLE `transaksi_jasa_desain`
  ADD CONSTRAINT `fk_transjasa` FOREIGN KEY (`id_jasa_desain`) REFERENCES `jasa_desain` (`id_jasa_desain`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
