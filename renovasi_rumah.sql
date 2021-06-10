-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 03, 2021 at 07:30 AM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 7.3.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
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
  `harga` int(11) NOT NULL,
  `total_diskon` int(11) NOT NULL,
  `total_bayar` int(11) NOT NULL,
  `id_material` varchar(15) DEFAULT NULL,
  `item` varchar(100) DEFAULT NULL,
  `volume` int(11) DEFAULT NULL
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
  `id_material` varchar(15) NOT NULL,
  `nama_material` varchar(100) NOT NULL,
  `jenis_material` varchar(100) NOT NULL,
  `satuan` varchar(100) NOT NULL,
  `spesifikasi` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `pegawai`
--

CREATE TABLE `pegawai` (
  `id_pegawai` int(11) NOT NULL,
  `nama_pegawai` varchar(100) NOT NULL,
  `alamat_pegawai` varchar(100) NOT NULL,
  `telp_pegawai` varchar(13) NOT NULL,
  `jenis_pegawai` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pegawai`
--

INSERT INTO `pegawai` (`id_pegawai`, `nama_pegawai`, `alamat_pegawai`, `telp_pegawai`, `jenis_pegawai`) VALUES
(1, 'getty', 'subang', '2147483647', ''),
(2, 'regina', 'serang', '087653428197', 'balkon');

-- --------------------------------------------------------

--
-- Table structure for table `pembayaran_supplier`
--

CREATE TABLE `pembayaran_supplier` (
  `id_pembayaran` int(11) NOT NULL,
  `id_pemesanan` int(11) NOT NULL,
  `no_kuitansi` varchar(20) NOT NULL,
  `tgl_bayar` date NOT NULL,
  `besar_bayar` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `pemesanan_supplier`
--

CREATE TABLE `pemesanan_supplier` (
  `id_pemesanan` int(11) NOT NULL,
  `id_supplier` int(11) NOT NULL,
  `tanggal_pesan` date NOT NULL,
  `tanggal_ambil` date NOT NULL,
  `diskon` decimal(10,0) NOT NULL,
  `ongkos_kirim` int(11) NOT NULL,
  `total_jual` int(11) NOT NULL,
  `harga` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `alamat_kirim` varchar(100) NOT NULL,
  `status_bayar` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pemesanan_supplier`
--

INSERT INTO `pemesanan_supplier` (`id_pemesanan`, `id_supplier`, `tanggal_pesan`, `tanggal_ambil`, `diskon`, `ongkos_kirim`, `total_jual`, `harga`, `jumlah`, `alamat_kirim`, `status_bayar`) VALUES
(15, 7, '2021-04-20', '2021-04-21', '100000', 10000, 990000, 1000000, 0, 'serang', 'Belum Lunas'),
(16, 8, '2021-04-08', '2021-04-29', '50000', 10000, 460000, 500000, 0, 'bandung', 'Belum Lunas'),
(17, 8, '2021-04-08', '2021-04-29', '50000', 10000, 460000, 500000, 0, 'bandung', 'Belum Lunas'),
(18, 8, '2021-04-22', '2021-04-29', '50000', 20000, 770000, 700000, 0, 'Bojongsoang', 'Belum Lunas'),
(19, 8, '2021-04-01', '2021-04-20', '50000', 10000, 960000, 1000000, 0, 'Bojongsoang', 'Belum Lunas'),
(20, 8, '2021-04-01', '2021-04-20', '50000', 10000, 960000, 1000000, 0, 'Bojongsoang', 'Belum Lunas'),
(21, 11, '2021-04-13', '2021-04-27', '100000', 20000, 920000, 1000000, 0, 'Telkom', 'Lunas'),
(22, 11, '2021-04-18', '2021-04-21', '200000', 150000, 9950000, 10000000, 0, 'Aceh', 'Belum Lunas'),
(23, 11, '2021-04-18', '2021-04-21', '200000', 150000, 9950000, 10000000, 0, 'Aceh', 'Belum Lunas'),
(24, 11, '2021-04-13', '2021-04-20', '10000', 20000, 510000, 500000, 0, 'Cimuncang', 'Lunas'),
(25, 11, '2021-04-19', '2021-04-21', '10000', 5000, 695000, 700000, 0, 'Subang', 'Lunas'),
(26, 7, '2021-04-20', '2021-04-22', '10000', 20000, 1010000, 1000000, 0, 'Lampung', 'Belum Lunas'),
(27, 14, '2021-04-20', '2021-04-22', '100000', 10000, 910000, 1000000, 0, 'serang', 'Belum Lunas'),
(28, 16, '2021-04-01', '2021-04-07', '100000', 10000, 910000, 500000, 0, 'Cimuncang', 'Belum Lunas');

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `id_supplier` int(15) NOT NULL,
  `nama_supplier` varchar(100) NOT NULL,
  `alamat_supplier` varchar(100) NOT NULL,
  `telepon_supplier` varchar(13) NOT NULL,
  `jenis_material` varchar(100) NOT NULL,
  `harga_material` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`id_supplier`, `nama_supplier`, `alamat_supplier`, `telepon_supplier`, `jenis_material`, `harga_material`) VALUES
(7, 'Syafia', 'Ciracas', '081221065578', 'Batu granit', 0),
(8, 'Alyssa', 'Pabuaran', '08122106954', 'Keramik marbel', 0),
(9, 'Regina', 'Serang', '081221076646', 'cat', 0),
(10, 'Biyan', 'Ciceri', '081221076644', 'besi', 0),
(11, 'Doni', 'jakarta', '081221076645', 'baja', 0),
(12, 'Davin', 'Lontar', '081221067734', 'Kayu Jati', 0),
(13, 'Rege', 'Sukabirus', '082116574423', 'Kawat', 0),
(14, 'Jihan', 'Batam', '089441675529', 'Rumput', 0),
(16, 'getty', 'subang', '081221076646', 'cat tembok', 500000);

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
-- Table structure for table `transaksi_material`
--

CREATE TABLE `transaksi_material` (
  `id_trans_material` varchar(15) NOT NULL,
  `tgl_transaksi` date NOT NULL,
  `total_bayar` int(100) NOT NULL,
  `total_beli` int(100) NOT NULL,
  `kembalian` int(100) NOT NULL
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

--
-- Indexes for dumped tables
--

--
-- Indexes for table `akun`
--
ALTER TABLE `akun`
  ADD PRIMARY KEY (`username`);

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
-- Indexes for table `pembayaran_supplier`
--
ALTER TABLE `pembayaran_supplier`
  ADD PRIMARY KEY (`id_pembayaran`);

--
-- Indexes for table `pemesanan_supplier`
--
ALTER TABLE `pemesanan_supplier`
  ADD PRIMARY KEY (`id_pemesanan`);

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
-- Indexes for table `transaksi_material`
--
ALTER TABLE `transaksi_material`
  ADD PRIMARY KEY (`id_trans_material`);

--
-- Indexes for table `transaksi_pegawai`
--
ALTER TABLE `transaksi_pegawai`
  ADD PRIMARY KEY (`id_trans_pegawai`),
  ADD KEY `fk_trans_pegawai` (`id_pegawai`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pegawai`
--
ALTER TABLE `pegawai`
  MODIFY `id_pegawai` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `pembayaran_supplier`
--
ALTER TABLE `pembayaran_supplier`
  MODIFY `id_pembayaran` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pemesanan_supplier`
--
ALTER TABLE `pemesanan_supplier`
  MODIFY `id_pemesanan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `id_supplier` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

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
