-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 03, 2021 at 06:59 AM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 7.4.10

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
  `id_customer` int(100) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `alamat` varchar(100) NOT NULL,
  `no_hp` varchar(13) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`id_customer`, `nama`, `alamat`, `no_hp`) VALUES
(1, 'krisna', 'bandung', '89697113656'),
(2, 'asep', 'sukabumi', '0555646464'),
(3, 'ujang', 'surabaya', '6468986'),
(5, 'julio', 'lembang', '65849661'),
(6, 'ananda', 'sukabumi', '2899'),
(7, 'genus', 'surabaya', '0555646464656'),
(9, 'hani', 'tangerang', '08936556');

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
  `id_pegawai` int(100) NOT NULL,
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
  `id_pemesanan` int(11) NOT NULL,
  `no_kuitansi` varchar(20) NOT NULL,
  `tgl_bayar` date NOT NULL,
  `besar_bayar` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pembayaran`
--

INSERT INTO `pembayaran` (`id_pembayaran`, `id_pemesanan`, `no_kuitansi`, `tgl_bayar`, `besar_bayar`) VALUES
(1, 33, 'KWI-20210419-1-001', '2021-04-19', 2500000),
(2, 36, 'KWI-20210419-2-112', '2021-04-19', 2500000),
(11, 38, 'KWI-20210419-11-102', '2021-04-19', 2500000);

-- --------------------------------------------------------

--
-- Table structure for table `pembayaran_customer`
--

CREATE TABLE `pembayaran_customer` (
  `id_pembayaran_customer` int(11) NOT NULL,
  `id_trans_customer` int(11) NOT NULL,
  `no_kuitansi` varchar(20) NOT NULL,
  `tgl_bayar` date NOT NULL,
  `besar_bayar` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
-- Table structure for table `pemesanan`
--

CREATE TABLE `pemesanan` (
  `id_pesan` int(11) NOT NULL,
  `id_customer` int(11) NOT NULL,
  `tgl_pesan` date NOT NULL,
  `tgl_renovasi` date NOT NULL,
  `jenis_renovasi` varchar(50) NOT NULL,
  `status_bayar` varchar(100) NOT NULL,
  `harga_deal` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pemesanan`
--

INSERT INTO `pemesanan` (`id_pesan`, `id_customer`, `tgl_pesan`, `tgl_renovasi`, `jenis_renovasi`, `status_bayar`, `harga_deal`) VALUES
(95, 1, '2021-04-20', '2021-04-22', 'Renovasi Kamar', 'Lunas', 5000000),
(96, 2, '2021-04-20', '2021-04-24', 'Renovasi Dapur', 'Belum Lunas', 3000000),
(102, 6, '2021-04-13', '2021-04-20', 'Renovasi Teras', 'Belum Lunas', 10000000),
(106, 5, '2021-04-01', '2021-04-27', 'Renovasi Teras', 'Lunas', 3000000),
(107, 9, '2021-04-20', '2021-04-22', 'Renovasi Dapur', 'Belum Lunas', 3000000);

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
  `alamat_kirim` varchar(100) NOT NULL,
  `status_bayar` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `id_supplier` int(15) NOT NULL,
  `nama_supplier` varchar(100) NOT NULL,
  `alamat_supplier` varchar(100) NOT NULL,
  `telepon_supplier` varchar(13) NOT NULL,
  `jenis_material` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`id_supplier`, `nama_supplier`, `alamat_supplier`, `telepon_supplier`, `jenis_material`) VALUES
(7, 'Syafia', 'Ciracas', '081221065578', 'Batu granit'),
(8, 'Alyssa', 'Pabuaran', '08122106954', 'Keramik marbel'),
(9, 'Regina', 'Serang', '081221076646', 'cat'),
(10, 'Biyan', 'Ciceri', '081221076644', 'besi'),
(11, 'Doni', 'jakarta', '081221076645', 'baja');

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

-- --------------------------------------------------------

--
-- Table structure for table `view_transaksi`
--

CREATE TABLE `view_transaksi` (
  `id_transaksi` int(11) NOT NULL,
  `waktu` datetime NOT NULL,
  `sumber` varchar(10) NOT NULL
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
-- Indexes for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`id_pembayaran`);

--
-- Indexes for table `pembayaran_customer`
--
ALTER TABLE `pembayaran_customer`
  ADD PRIMARY KEY (`id_pembayaran_customer`);

--
-- Indexes for table `pembayaran_supplier`
--
ALTER TABLE `pembayaran_supplier`
  ADD PRIMARY KEY (`id_pembayaran`);

--
-- Indexes for table `pemesanan`
--
ALTER TABLE `pemesanan`
  ADD PRIMARY KEY (`id_pesan`);

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
-- Indexes for table `transaksi_supplier`
--
ALTER TABLE `transaksi_supplier`
  ADD PRIMARY KEY (`id_trans_supplier`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `id_customer` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `pegawai`
--
ALTER TABLE `pegawai`
  MODIFY `id_pegawai` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `id_pembayaran` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `pembayaran_customer`
--
ALTER TABLE `pembayaran_customer`
  MODIFY `id_pembayaran_customer` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pembayaran_supplier`
--
ALTER TABLE `pembayaran_supplier`
  MODIFY `id_pembayaran` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pemesanan`
--
ALTER TABLE `pemesanan`
  MODIFY `id_pesan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=108;

--
-- AUTO_INCREMENT for table `pemesanan_supplier`
--
ALTER TABLE `pemesanan_supplier`
  MODIFY `id_pemesanan` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `id_supplier` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

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
