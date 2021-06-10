-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 03 Jun 2021 pada 07.28
-- Versi server: 10.4.17-MariaDB
-- Versi PHP: 7.3.27

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
-- Struktur dari tabel `akun`
--

CREATE TABLE `akun` (
  `username` varchar(50) NOT NULL,
  `pwd` varchar(32) NOT NULL,
  `last_login` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `akun`
--

INSERT INTO `akun` (`username`, `pwd`, `last_login`) VALUES
('admin', '21232f297a57a5a743894a0e4a801fc3', '2021-01-27 18:24:27');

-- --------------------------------------------------------

--
-- Struktur dari tabel `customer`
--

CREATE TABLE `customer` (
  `id_customer` varchar(15) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `alamat` varchar(100) NOT NULL,
  `no_hp` varchar(13) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `customer`
--

INSERT INTO `customer` (`id_customer`, `nama`, `alamat`, `no_hp`) VALUES
('', 'ujang', 'jakarta', '0555646464'),
('001', 'krisna', 'bandung', '89697113656');

-- --------------------------------------------------------

--
-- Struktur dari tabel `detail_trans_supplier`
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
-- Struktur dari tabel `jasa_desain`
--

CREATE TABLE `jasa_desain` (
  `id_jasa_desain` varchar(15) NOT NULL,
  `jenis_jasa_desain` varchar(100) NOT NULL,
  `tipe_desain` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `material`
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
-- Struktur dari tabel `pegawai`
--

CREATE TABLE `pegawai` (
  `id_pegawai` int(15) NOT NULL,
  `nama_pegawai` varchar(100) NOT NULL,
  `alamat_pegawai` varchar(100) NOT NULL,
  `telp_pegawai` varchar(13) NOT NULL,
  `jenis_pegawai` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `pegawai`
--

INSERT INTO `pegawai` (`id_pegawai`, `nama_pegawai`, `alamat_pegawai`, `telp_pegawai`, `jenis_pegawai`) VALUES
(10, 'amelia getty', 'bandung', '08211706368', 'tukang cat'),
(11, 'ingka novita', 'jakarta', '08211706321', 'tukang keramik'),
(13, 'regina', 'serang', '082117063456', 'tukang las'),
(14, 'dara', 'Bali', '08211706366', 'tukang cat'),
(15, 'vanya', 'Bandung', '082117063457', 'mandor'),
(16, 'Mince', 'Semarang', '082117064567', 'tukang semen'),
(17, 'Desta', 'Depok', '081224411447', 'mandor');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pembayaran_pegawai`
--

CREATE TABLE `pembayaran_pegawai` (
  `id_pembayaran_pegawai` int(11) NOT NULL,
  `id_penggajian` int(11) NOT NULL,
  `no_kuitansi` varchar(20) NOT NULL,
  `tgl_bayar` date NOT NULL,
  `besar_bayar` int(11) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `pembayaran_pegawai`
--

INSERT INTO `pembayaran_pegawai` (`id_pembayaran_pegawai`, `id_penggajian`, `no_kuitansi`, `tgl_bayar`, `besar_bayar`, `status`) VALUES
(2, 31, 'KWI/2021-04-20/31/10', '2021-04-20', 0, 0),
(3, 32, 'KWI/2021-04-20/32/10', '2021-04-20', 0, 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `penggajian_pegawai`
--

CREATE TABLE `penggajian_pegawai` (
  `id_penggajian` int(15) NOT NULL,
  `jumlah_hari` int(11) NOT NULL,
  `gaji` int(11) NOT NULL,
  `total_gaji` int(11) NOT NULL,
  `id_pegawai` int(15) DEFAULT NULL,
  `tanggal_penggajian` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `penggajian_pegawai`
--

INSERT INTO `penggajian_pegawai` (`id_penggajian`, `jumlah_hari`, `gaji`, `total_gaji`, `id_pegawai`, `tanggal_penggajian`) VALUES
(31, 30, 30000, 900000, 10, '2021-04-20'),
(32, 28, 30000, 840000, 10, '2021-04-20');

-- --------------------------------------------------------

--
-- Struktur dari tabel `supplier`
--

CREATE TABLE `supplier` (
  `id_supplier` int(11) NOT NULL,
  `nama_supplier` varchar(100) NOT NULL,
  `alamat_supplier` varchar(100) NOT NULL,
  `telepon_supplier` varchar(13) NOT NULL,
  `jenis_material` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `supplier`
--

INSERT INTO `supplier` (`id_supplier`, `nama_supplier`, `alamat_supplier`, `telepon_supplier`, `jenis_material`) VALUES
(2, 'syifa', 'banten', '0821224411447', 'cat');

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaksi_customer`
--

CREATE TABLE `transaksi_customer` (
  `id_trans_customer` varchar(15) NOT NULL,
  `id_customer` varchar(15) NOT NULL,
  `jenis_renovasi` varchar(100) NOT NULL,
  `total_renovasi` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaksi_jasa_desain`
--

CREATE TABLE `transaksi_jasa_desain` (
  `id_trans_jasa_desain` varchar(15) NOT NULL,
  `id_jasa_desain` varchar(100) NOT NULL,
  `total_bayar` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaksi_material`
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
-- Struktur dari tabel `transaksi_supplier`
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
-- Indeks untuk tabel `akun`
--
ALTER TABLE `akun`
  ADD PRIMARY KEY (`username`);

--
-- Indeks untuk tabel `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`id_customer`);

--
-- Indeks untuk tabel `detail_trans_supplier`
--
ALTER TABLE `detail_trans_supplier`
  ADD PRIMARY KEY (`id_detail_trans`);

--
-- Indeks untuk tabel `jasa_desain`
--
ALTER TABLE `jasa_desain`
  ADD PRIMARY KEY (`id_jasa_desain`);

--
-- Indeks untuk tabel `material`
--
ALTER TABLE `material`
  ADD PRIMARY KEY (`id_material`);

--
-- Indeks untuk tabel `pegawai`
--
ALTER TABLE `pegawai`
  ADD PRIMARY KEY (`id_pegawai`);

--
-- Indeks untuk tabel `pembayaran_pegawai`
--
ALTER TABLE `pembayaran_pegawai`
  ADD PRIMARY KEY (`id_pembayaran_pegawai`);

--
-- Indeks untuk tabel `penggajian_pegawai`
--
ALTER TABLE `penggajian_pegawai`
  ADD PRIMARY KEY (`id_penggajian`),
  ADD KEY `fk_trans_pegawai` (`id_pegawai`);

--
-- Indeks untuk tabel `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`id_supplier`);

--
-- Indeks untuk tabel `transaksi_customer`
--
ALTER TABLE `transaksi_customer`
  ADD PRIMARY KEY (`id_trans_customer`),
  ADD KEY `id_customer` (`id_customer`);

--
-- Indeks untuk tabel `transaksi_jasa_desain`
--
ALTER TABLE `transaksi_jasa_desain`
  ADD PRIMARY KEY (`id_trans_jasa_desain`),
  ADD KEY `fk_transjasa` (`id_jasa_desain`);

--
-- Indeks untuk tabel `transaksi_material`
--
ALTER TABLE `transaksi_material`
  ADD PRIMARY KEY (`id_trans_material`);

--
-- Indeks untuk tabel `transaksi_supplier`
--
ALTER TABLE `transaksi_supplier`
  ADD PRIMARY KEY (`id_trans_supplier`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `pegawai`
--
ALTER TABLE `pegawai`
  MODIFY `id_pegawai` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT untuk tabel `pembayaran_pegawai`
--
ALTER TABLE `pembayaran_pegawai`
  MODIFY `id_pembayaran_pegawai` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `penggajian_pegawai`
--
ALTER TABLE `penggajian_pegawai`
  MODIFY `id_penggajian` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT untuk tabel `supplier`
--
ALTER TABLE `supplier`
  MODIFY `id_supplier` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `transaksi_jasa_desain`
--
ALTER TABLE `transaksi_jasa_desain`
  ADD CONSTRAINT `fk_transjasa` FOREIGN KEY (`id_jasa_desain`) REFERENCES `jasa_desain` (`id_jasa_desain`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
