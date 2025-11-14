-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 12 Nov 2025 pada 02.25
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
-- Database: `codekop_free_rental_mobil`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `booking`
--

CREATE TABLE `booking` (
  `id_booking` int(11) NOT NULL,
  `kode_booking` varchar(255) NOT NULL,
  `id_login` int(11) NOT NULL,
  `id_mobil` int(11) NOT NULL,
  `ktp` varchar(255) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `alamat` varchar(255) NOT NULL,
  `no_tlp` varchar(15) NOT NULL,
  `tanggal` varchar(255) NOT NULL,
  `lama_sewa` int(11) NOT NULL,
  `total_harga` int(11) NOT NULL,
  `konfirmasi_pembayaran` varchar(255) NOT NULL,
  `tgl_input` varchar(255) NOT NULL,
  `id_supir` int(11) DEFAULT NULL,
  `status_pengembalian` varchar(255) DEFAULT 'Belum Dikembalikan'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `booking`
--

INSERT INTO `booking` (`id_booking`, `kode_booking`, `id_login`, `id_mobil`, `ktp`, `nama`, `alamat`, `no_tlp`, `tanggal`, `lama_sewa`, `total_harga`, `konfirmasi_pembayaran`, `tgl_input`, `id_supir`, `status_pengembalian`) VALUES
(1, '1576329294', 3, 5, '231423123', 'Krisna', 'Bekasi', '08132312321', '2019-12-28', 2, 400000, 'Pembayaran Diterima', '2019-12-14', NULL, 'Belum Dikembalikan'),
(2, '1576671989', 3, 5, '231423', 'Krisna Waskita', 'Bekasi Ujung Harapan', '082391273127', '2019-12-20', 2, 400525, 'Sudah Dibayar', '2019-12-18', NULL, 'Belum Dikembalikan'),
(3, '1642998828', 3, 5, '1283821832813', 'Krisna', 'Bekasi', '089618173609', '2022-01-26', 4, 800743, 'Pembayaran Diterima', '2022-01-24', NULL, 'Belum Dikembalikan'),
(4, '1758113281', 4, 6, 'rillapan', 'rillapan', 'rillapan', 'rillapan', '2025-09-17', 10, 5000608, 'Pembayaran Diterima', '2025-09-17', NULL, 'Belum Dikembalikan'),
(5, '1758113355', 4, 6, 'rillapan', 'rillapan', 'rillapan', 'rillapan', '2025-09-17', 10, 5000967, 'Sudah Dibayar', '2025-09-17', NULL, 'Belum Dikembalikan'),
(6, '1758123009', 5, 7, '12345', 'MOHAMMAD FARID ATABAQI', 'pekalongan', '090909', '2025-09-17', 10, 1000573, 'Pembayaran Diterima', '2025-09-17', NULL, 'Belum Dikembalikan'),
(7, '1758123604', 4, 7, '12345', 'MOHAMMAD FARID ATABAQI', 'pekalongan', '090909', '2025-09-17', 9, 900934, 'Sudah Dibayar', '2025-09-17', NULL, 'Belum Dikembalikan'),
(8, '1758123794', 4, 6, '12345', 'MOHAMMAD FARID ATABAQI', 'pekalongan', '090909', '2025-09-17', 9, 4500511, 'Pembayaran Diterima', '2025-09-17', NULL, 'Belum Dikembalikan'),
(9, '1758123962', 4, 6, '12345', 'AC CHANGHONG 1/5PK', 'pekalongan', '987987', '2025-09-17', 7, 3500699, 'Pembayaran di terima', '2025-09-17', NULL, 'Belum Dikembalikan'),
(10, '1758125072', 6, 6, '12345', 'vwfwe', 'wegeg', 'efwg', '2025-09-17', 2, 1000533, 'Pembayaran di terima', '2025-09-17', NULL, 'Belum Dikembalikan'),
(11, '1758199992', 4, 6, 'rillapan', 'rillapan', 'rillapan', 'rillapan', '2025-09-17', 10, 5000541, 'Sedang Diproses', '2025-09-18', NULL, 'Belum Dikembalikan'),
(12, '1758200104', 1, 6, 'rillapan', 'rillapan', 'rillapan', 'rillapan', '2025-09-17', 10, 5000663, 'Pembayaran Diterima', '2025-09-18', NULL, 'Belum Dikembalikan'),
(13, '1758200250', 7, 6, 'user1', 'user1', 'user1', 'user1', '2025-09-17', 10, 5000771, 'Pembayaran Diterima', '2025-09-18', NULL, 'Belum Dikembalikan'),
(14, '1758204187', 7, 6, '12345', 'MOHAMMAD FARID ATABAQI', 'pekalongan', '090909', '2025-09-18', 546, 273000628, 'Sudah Dibayar', '2025-09-18', NULL, 'Belum Dikembalikan'),
(15, '1758205511', 7, 7, '12345', 'MOHAMMAD FARID ATABAQI', 'gre', 'rgeeg', '2025-09-18', 12, 1200447, 'Pembayaran Diterima', '2025-09-18', NULL, 'Belum Dikembalikan'),
(16, '1758210594', 5, 7, '12345', 'affan', 'pekalongan', '089630961095', '2025-09-18', 12, 1200283, 'Pembayaran Diterima', '2025-09-18', NULL, 'Belum Dikembalikan'),
(17, '1758211520', 1, 7, '12345', 'affan', 'pekalongan', '090909', '2025-09-18', 1, 100192, 'Pembayaran Diterima', '2025-09-18', NULL, 'Belum Dikembalikan'),
(18, '1758249162', 1, 7, '12345', 'aril', 'aril', 'aril', '2025-09-19', 2, 200245, 'Sudah Dibayar', '2025-09-19', NULL, 'Belum Dikembalikan'),
(19, '1758249221', 8, 6, '12345', 'arilaril', 'aril', '434345', '2025-09-19', 2, 1000502, 'Sudah Dibayar', '2025-09-19', NULL, 'Belum Dikembalikan'),
(20, '1758249930', 8, 6, 'user1', 'user1', 'user1', 'user1', '2025-09-17', 10, 5000572, 'Sedang di proses', '2025-09-19', NULL, 'Belum Dikembalikan'),
(21, '1758250058', 8, 6, 'user1', 'user1', 'user1', 'user1', '2025-09-17', 10, 5000310, 'Sedang Diproses', '2025-09-19', NULL, 'Belum Dikembalikan'),
(22, '1758250133', 8, 6, 'user1', 'user1', 'user1', 'user1', '2025-09-17', 10, 5000444, 'Pembayaran Diterima', '2025-09-19', NULL, 'Belum Dikembalikan'),
(23, '1758251362', 8, 6, 'user1', 'user1', 'user1', 'user1', '2025-09-17', 10, 5000580, 'diproses', '2025-09-19', NULL, 'Belum Dikembalikan'),
(24, '1758251717', 8, 6, 'user1', 'user1', 'user1', 'user1', '2025-09-17', 10, 5000198, 'Sedang Diproses', '2025-09-19', NULL, 'Belum Dikembalikan'),
(25, '1758252099', 8, 6, 'user1', 'user1', 'user1', 'user1', '2025-09-17', 10, 5000155, 'diproses', '2025-09-19', NULL, 'Belum Dikembalikan'),
(26, '1758254431', 9, 6, 'user1', 'user1', 'user1', 'user1', '2025-09-17', 10, 5000800, 'Pembayaran Diterima', '2025-09-19', NULL, 'Belum Dikembalikan'),
(27, '1758255918', 9, 6, 'user1', 'user1', 'user1', 'user1', '2025-09-17', 10, 5000100, 'Sudah Dibayar', '2025-09-19', NULL, 'Belum Dikembalikan'),
(28, '1758266026', 9, 6, 'user1', 'user1', 'user1', 'user1', '2025-09-17', 10, 5000352, 'Sedang Diproses', '2025-09-19', NULL, 'Belum Dikembalikan'),
(29, '1758267754', 9, 6, 'user1', 'user1', 'user1', 'user1', '2025-09-17', 10, 5000588, 'Sedang Diproses', '2025-09-19', NULL, 'Belum Dikembalikan'),
(30, '1758267941', 9, 6, 'user1', 'user1', 'user1', 'user1', '2025-09-17', 10, 5000617, 'Sedang Diproses', '2025-09-19', NULL, 'Belum Dikembalikan'),
(31, '1758283460', 9, 6, 'user1', 'user1', 'user1', 'user1', '2025-09-17', 10, 5000776, 'Sedang Diproses', '2025-09-19', NULL, 'Belum Dikembalikan'),
(32, '1758283835', 9, 6, 'user1', 'user1', 'user1', 'user1', '2025-09-17', 10, 5000218, 'Pembayaran Diterima', '2025-09-19', NULL, 'Belum Dikembalikan'),
(33, '1758285769', 9, 6, 'user1', 'user1', 'user1', 'user1', '2025-09-17', 10, 5000390, 'Sedang Diproses', '2025-09-19', NULL, 'Belum Dikembalikan'),
(34, '1758286309', 9, 6, 'user1', 'user1', 'user1', 'user1', '2025-09-17', 10, 5000998, 'Sedang Diproses', '2025-09-19', NULL, 'Belum Dikembalikan'),
(35, '1758287064', 9, 6, 'user1', 'user1', 'user1', 'user1', '2025-09-17', 10, 5000193, 'Pembayaran Diterima', '2025-09-19', NULL, 'Belum Dikembalikan'),
(36, '1758289204', 9, 7, 'user1', 'user1', 'user1', 'user1', '2025-09-17', 10, 1000853, 'Sudah Dibayar', '2025-09-19', NULL, 'Belum Dikembalikan'),
(37, '1758292110', 9, 7, 'user1', 'user1', 'user1', 'user1', '2025-09-17', 10, 1000717, 'Sudah Dibayar', '2025-09-19', NULL, 'Belum Dikembalikan'),
(38, '1758383087', 1, 15, '12345', 'affan', 'pekalongan', '090909', '2025-09-20', 12, 2147483647, 'Sedang Diproses', '2025-09-20', NULL, 'Belum Dikembalikan'),
(39, '1758422737', 5, 15, '12345', 'affan', 'pekalongan', '090909', '2025-09-21', 2, 800000726, 'Sedang Diproses', '2025-09-21', NULL, 'Belum Dikembalikan'),
(40, '1758422916', 10, 15, '12345', 'affan', 'pekalongan', '090909', '2025-09-21', 1, 400000894, 'Sudah Dibayar', '2025-09-21', NULL, 'Belum Dikembalikan'),
(41, '1758425891', 10, 15, '12345', 'affan', 'pekalongan', '090909', '2025-09-21', 1, 400000697, 'Sedang Diproses', '2025-09-21', NULL, 'Belum Dikembalikan'),
(42, '1758527272', 4, 14, '12345', 'affan', 'pekalongan', 'rgeeg', '2025-09-22', 3, 900744, 'Pembayaran Diterima', '2025-09-22', NULL, 'Belum Dikembalikan'),
(43, '1758697236', 5, 15, 'user1', 'user1', 'user1', 'user1', '2025-09-17', 10, 2147483647, 'Sudah Dibayar', '2025-09-24', NULL, 'Belum Dikembalikan'),
(44, '1759460740', 5, 15, 'user1', 'user1', 'user1', 'user1', '2025-09-17', 10, 2147483647, 'Sudah Dibayar', '2025-10-03', NULL, 'Belum Dikembalikan'),
(45, '1760065871', 5, 15, '12345', 'SEPATU', 'pekalongan', '090909', '2025-10-10', 2, 800000786, 'Sudah Dibayar', '2025-10-10', NULL, 'Belum Dikembalikan'),
(46, '1760077644', 5, 15, '7398472520759', 'ALDO', 'FDOIHOWO', '904583495U4390', '2025-10-10', 3, 1200000690, 'Belum Bayar', '2025-10-10', NULL, 'Belum Dikembalikan'),
(47, '1760670319', 5, 16, '12345', 'SEPATU', 'pekalongan', '434345', '2025-10-17', 2, 800217, 'Pembayaran Diterima', '2025-10-17', NULL, 'Belum Dikembalikan'),
(48, '1761274512', 5, 17, '4466656', 'bjkk', 'jbkjbih', '689865', '2025-10-13', 3, 1200954, 'Belum Bayar', '2025-10-24', 1, 'Belum Dikembalikan'),
(49, '1761275421', 5, 17, '12345', 'affan', 'aril', '090909', '2025-10-24', 3, 1500261, 'Belum Bayar', '2025-10-24', 2, 'Belum Dikembalikan'),
(50, '1761277371', 5, 16, '7657r', 'affan', 'pekalongan', '090909', '2025-10-24', 4, 1600919, 'Sedang Diproses', '2025-10-24', NULL, 'Belum Dikembalikan'),
(51, '1761277866', 5, 17, '12345', 'SARUNG', 'pekalongan', '324342', '2025-10-24', 4, 1200342, 'Sedang Diproses', '2025-10-24', NULL, 'Belum Dikembalikan'),
(52, '1761279130', 5, 6, '12345', 'affan', 'pekalongan', '090909', '2025-10-24', 3, 1500946, 'Belum Bayar', '2025-10-24', NULL, 'Belum Dikembalikan'),
(53, '1761279545', 5, 17, '12345', 'affan', 'pekalongan', '090909', '2025-10-24', 4, 1200881, 'Belum Bayar', '2025-10-24', NULL, 'Belum Dikembalikan'),
(54, '1761279704', 5, 16, '12345', 'affan', 'pekalongan', '0987737', '2025-10-24', 2, 800794, 'Sedang Diproses', '2025-10-24', NULL, 'Belum Dikembalikan'),
(55, '1761279961', 5, 6, '12345', 'affan', 'pekalongan', '090909', '2025-10-24', 2, 1000987, 'Belum Bayar', '2025-10-24', NULL, 'Belum Dikembalikan'),
(56, '1761280043', 5, 6, '12345', 'affan', 'aril', '090909', '2025-10-24', 4, 2000409, 'Pembayaran Diterima', '2025-10-24', NULL, 'Belum Dikembalikan'),
(57, '1761285631', 5, 17, '12345', 'affan', 'kbi', '0989', '2025-10-24', 4, 1200859, 'Pembayaran Diterima', '2025-10-24', NULL, 'Belum Dikembalikan'),
(58, '1762312564', 5, 18, '12345', 'khairil affan', 'FDOIHOWO', 'rgeeg', '2025-11-05', 4, 1200153, 'Belum Bayar', '2025-11-05', 1, 'Belum Dikembalikan'),
(59, '1762312589', 5, 18, '12345', 'khairil affan', 'FDOIHOWO', 'rgeeg', '2025-11-05', 4, 1200436, 'Belum Bayar', '2025-11-05', 1, 'Belum Dikembalikan'),
(60, '1762329007', 5, 17, '12345', 'khairil affan', 'jbkjbih', '090909', '2025-11-05', 2, 1000447, 'Pembayaran Diterima', '2025-11-05', 2, 'Belum Dikembalikan'),
(61, '1762698389', 5, 18, '4466656', 'affan', 'pekalongan', '090909', '2025-11-09', 2, 600267, 'Belum Bayar', '2025-11-09', 3, 'Dikembalikan'),
(62, '1762699178', 5, 17, '09765', 'khairil affan', 'pekalongan', '987987', '2025-11-09', 1, 300571, 'Belum Bayar', '2025-11-09', NULL, 'Belum Dikembalikan'),
(63, '1762699648', 5, 17, '12345', 'SEPATU', 'aril', '090909', '2025-11-09', 1, 300498, 'Sedang Diproses', '2025-11-09', NULL, 'Belum Dikembalikan'),
(64, '1762699970', 5, 15, '12345', 'SEPATU', 'pekalongan', '090909', '2025-11-09', 1, 400000913, 'Sudah Dibayar', '2025-11-09', NULL, 'Belum Dikembalikan'),
(65, '1762756573', 5, 14, 'hbgui', 'nm bjk', ' kbj', '8796', '2025-11-21', 1, 300846, 'Belum Bayar', '2025-11-10', NULL, 'Belum Dikembalikan'),
(66, '1762757405', 5, 17, '12345', 'affan', 'pekalongan', '090909', '2025-11-10', 1, 300605, 'Sedang Diproses', '2025-11-10', NULL, 'Belum Dikembalikan'),
(67, '1762758621', 8, 17, '12345', 'mohammad khairil affan', 'pekalongan', '097766', '2025-11-10', 1, 300412, 'Sedang Diproses', '2025-11-10', NULL, 'Belum Dikembalikan'),
(68, '1762761540', 5, 16, '123', 'affan', 'affanaffan', '098753235', '2025-11-10', 10, 4000912, 'Sudah Dibayar', '2025-11-10', NULL, 'Dikembalikan'),
(69, '1762779855', 5, 18, '12345', 'affan', 'pekalongan', '090909', '2025-11-10', 1, 220167, 'Pembayaran Diterima', '2025-11-10', 4, 'Dikembalikan'),
(70, '1762861295', 5, 18, '12345', 'khairil affan', 'jdnewiodeh', '090909', '2025-11-11', 1, 220403, 'Pembayaran Diterima', '2025-11-11', 4, 'Belum Dikembalikan');

-- --------------------------------------------------------

--
-- Struktur dari tabel `infoweb`
--

CREATE TABLE `infoweb` (
  `id` int(11) NOT NULL,
  `nama_rental` varchar(255) DEFAULT NULL,
  `telp` varchar(15) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `no_rek` text DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `infoweb`
--

INSERT INTO `infoweb` (`id`, `nama_rental`, `telp`, `alamat`, `email`, `no_rek`, `updated_at`) VALUES
(1, 'rental mobil nusantara', '0987654321', 'tirto Pekalongan', 'khairilaffan@gmail.com', 'BRI A/N affan 1234567890', '2022-01-24 04:57:29');

-- --------------------------------------------------------

--
-- Struktur dari tabel `login`
--

CREATE TABLE `login` (
  `id_login` int(11) NOT NULL,
  `nama_pengguna` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `level` varchar(255) NOT NULL,
  `no_hp` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `login`
--

INSERT INTO `login` (`id_login`, `nama_pengguna`, `username`, `password`, `level`, `no_hp`) VALUES
(1, 'affan', 'admin', '21232f297a57a5a743894a0e4a801fc3', 'admin', NULL),
(3, 'Krisna Waskita', 'demo', 'fe01ce2a7fbac8fafaed7c982a04e229', 'pengguna', NULL),
(4, 'rillapan', 'rillapan', '0803a6c4ee99c800bf8a7522faa5e802', 'pengguna', NULL),
(5, 'khairil affan', 'affan', '2e6f4060039b26cb50d2a0d7d1aaed42', 'pengguna', NULL),
(6, 'belfarillll', 'Affan', '202cb962ac59075b964b07152d234b70', 'pengguna', NULL),
(7, 'user1', 'user1', '24c9e15e52afc47c225b757e7bee1f9d', 'pengguna', NULL),
(8, 'aril', 'aril', '513e63de470114891012072f5ffd3d8b', 'pengguna', NULL),
(9, 'user2', 'user2', '7e58d63b60197ceb55a1c487989a3720', 'pengguna', NULL),
(10, 'pengguna1', 'pengguna1', '750d66e99a19b9ba1463a16d2a55b400', 'pengguna', NULL),
(11, 'jihh', 'halo', '202cb962ac59075b964b07152d234b70', 'pengguna', NULL),
(12, 'joko', 'joko', '9ba0009aa81e794e628a04b51eaf7d7f', 'pengguna', 'joko'),
(13, 'jono', 'jono123', 'ef9322a1a342a36856e9e8929b19437a', 'pengguna', '0986798876'),
(14, 'budi', 'budi', '7f583aba3b117143c556a331b915b321', 'pengguna', '098669087656'),
(15, 'mohammad khairil affan', 'khairil', 'f26a0bf074f116ce14de7c4bff010090', 'pengguna', '089630961095');

-- --------------------------------------------------------

--
-- Struktur dari tabel `mobil`
--

CREATE TABLE `mobil` (
  `id_mobil` int(11) NOT NULL,
  `no_plat` varchar(255) NOT NULL,
  `merk` varchar(255) NOT NULL,
  `harga` int(255) NOT NULL,
  `deskripsi` varchar(255) NOT NULL,
  `keunggulan` text DEFAULT NULL,
  `status` varchar(255) NOT NULL,
  `gambar` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `mobil`
--

INSERT INTO `mobil` (`id_mobil`, `no_plat`, `merk`, `harga`, `deskripsi`, `keunggulan`, `status`, `gambar`) VALUES
(5, 'G 123 456', 'Avanza', 200000, 'Toyota Avanza ', 'free bensin 100k', 'Tidak Tersedia', '1758382735.jpg'),
(6, 'N 1232 BKT', 'New Xenia', 500000, 'Baru', 'free bensin 100k', 'Tersedia', 'all-new-xenia-exterior-tampak-perspektif-depan---varian-1.5r-ads.jpg'),
(7, 'G 123 456', 'BMW Seri 3 ', 1000000, 'BMW Seri 3 ', 'gerg', 'Tersedia', '1758382920.jpg'),
(10, 'G 123 456', 'Mitsubishi Xpander ', 2000000, 'Mitsubishi Xpander ', 'free bensin 100k', 'Tersedia', '1758383018.jpg'),
(14, 'G 123 456', 'Toyota Fortuner ', 300000, 'Toyota Fortuner ', 'free bensin 100k', 'Tersedia', '1758382416.jpg'),
(15, 'G 123 456', 'Honda CRV ', 400000000, 'Honda CRV ', 'Free tiket tol||free bensin 100k', 'Tersedia', '1758382579.jpg'),
(16, 'G 123 456', 'Toyota Fortuner ', 400000, 'mobil ', 'bensin gratis||uang toll 1000||berish', 'Tersedia', '1759460881.jpg'),
(17, 'N34234', 'mobi;', 300000, 'mobil mobil', 'bensin||toll||bensin', 'Tersedia', '1762778294.png'),
(18, 'N34234', 'mobil', 200000, 'jfweoifwerhot', 'bensin||dapet alfin||cuci bersih', 'Tersedia', '1762777995.png'),
(19, 'G 123 456', 'Toyota Affan', 50000, 'mobil mobil', 'Bnnsin', 'Tersedia', '1762778542.png');

-- --------------------------------------------------------

--
-- Struktur dari tabel `mobil_gambar`
--

CREATE TABLE `mobil_gambar` (
  `id_gambar` int(11) NOT NULL,
  `id_mobil` int(11) NOT NULL,
  `nama_gambar` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `mobil_gambar`
--

INSERT INTO `mobil_gambar` (`id_gambar`, `id_mobil`, `nama_gambar`) VALUES
(4, 14, '1758382416_0.jpg'),
(5, 14, '1758382416_1.jpg'),
(6, 14, '1758382416_2.jpg'),
(7, 15, '1758382579_0.jpg'),
(8, 15, '1758382579_1.jpg'),
(9, 5, '1758382735_0.jpg'),
(10, 6, '1758382810_0.jpg'),
(11, 16, '1759460881_0.png'),
(12, 16, '1759460881_1.png');

-- --------------------------------------------------------

--
-- Struktur dari tabel `notifikasi`
--

CREATE TABLE `notifikasi` (
  `id` int(11) NOT NULL,
  `id_login` int(11) NOT NULL,
  `id_booking` int(11) NOT NULL,
  `pesan` text NOT NULL,
  `status_baca` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `notifikasi`
--

INSERT INTO `notifikasi` (`id`, `id_login`, `id_booking`, `pesan`, `status_baca`, `created_at`) VALUES
(1, 7, 0, 'Pembayaranmu sudah kami terima 🎉 Mobil impianmu siap dibooking! Silakan datang ke toko kami untuk segera mengambil mobil idamanmu 🚗💨.', 1, '2025-09-18 15:39:04'),
(2, 7, 0, 'Pembayaranmu sudah kami terima 🎉 Mobil impianmu siap dibooking! Silakan datang ke toko kami untuk segera mengambil mobil idamanmu 🚗💨.', 1, '2025-09-18 15:39:20'),
(3, 7, 0, 'Pesananmu sedang kami proses ✅ Tim kami sedang menyiapkan mobil impianmu. Tunggu sebentar ya, kami akan segera menghubungi kamu untuk langkah berikutnya.', 1, '2025-09-18 15:39:44'),
(4, 5, 0, 'Pembayaranmu sudah kami terima 🎉 Mobil impianmu siap dibooking! Silakan datang ke toko kami untuk segera mengambil mobil idamanmu 🚗💨.', 1, '2025-09-18 15:40:00'),
(5, 1, 0, 'Pesananmu sedang kami proses ✅ Tim kami sedang menyiapkan mobil impianmu. Tunggu sebentar ya, kami akan segera menghubungi kamu untuk langkah berikutnya.', 1, '2025-09-18 15:40:30'),
(6, 7, 0, 'Pembayaranmu sudah kami terima 🎉 Mobil impianmu siap dibooking! Silakan datang ke toko kami untuk segera mengambil mobil idamanmu 🚗💨.', 1, '2025-09-18 15:42:47'),
(7, 7, 0, 'Pembayaranmu sudah kami terima 🎉 Mobil impianmu siap dibooking! Silakan datang ke toko kami untuk segera mengambil mobil idamanmu 🚗💨.', 1, '2025-09-18 15:47:01'),
(8, 7, 0, 'Selamat 🎉 Pembayaranmu berhasil! Mobil impianmu sudah resmi menjadi milikmu. Terima kasih telah mempercayai layanan kami 🚘✨.', 1, '2025-09-18 15:47:17'),
(9, 7, 0, 'Pembayaranmu sudah kami terima 🎉 Mobil impianmu siap dibooking! Silakan datang ke toko kami untuk segera mengambil mobil idamanmu 🚗💨.', 1, '2025-09-18 15:47:32'),
(10, 5, 0, 'Pembayaranmu sudah kami terima 🎉 Mobil impianmu siap dibooking! Silakan datang ke toko kami untuk segera mengambil mobil idamanmu 🚗💨.', 1, '2025-09-18 15:50:43'),
(11, 5, 0, 'Oops! 🚨 Pesananmu masih belum dibayar. Segera lakukan pembayaran agar mobil impianmu tidak dibooking orang lain 😉.', 1, '2025-09-18 15:51:04'),
(12, 5, 0, 'Pembayaranmu sudah kami terima 🎉 Mobil impianmu siap dibooking! Silakan datang ke toko kami untuk segera mengambil mobil idamanmu 🚗💨.', 1, '2025-09-18 15:56:54'),
(13, 5, 0, 'Pembayaranmu sudah kami terima 🎉 Mobil impianmu siap dibooking! Silakan datang ke toko kami untuk segera mengambil mobil idamanmu 🚗💨.', 1, '2025-09-18 15:57:09'),
(14, 4, 0, 'Pembayaranmu sudah kami terima 🎉 Mobil impianmu siap dibooking! Silakan datang ke toko kami untuk segera mengambil mobil idamanmu 🚗💨.', 1, '2025-09-18 15:57:28'),
(15, 3, 0, 'Pembayaranmu sudah kami terima 🎉 Mobil impianmu siap dibooking! Silakan datang ke toko kami untuk segera mengambil mobil idamanmu 🚗💨.', 0, '2025-09-18 15:58:19'),
(16, 3, 0, 'Pesananmu sedang kami proses ✅ Tim kami sedang menyiapkan mobil impianmu. Tunggu sebentar ya, kami akan segera menghubungi kamu untuk langkah berikutnya.', 0, '2025-09-18 15:58:55'),
(17, 3, 0, 'Selamat 🎉 Pembayaranmu berhasil! Mobil impianmu sudah resmi menjadi milikmu. Terima kasih telah mempercayai layanan kami 🚘✨.', 0, '2025-09-18 15:59:07'),
(18, 3, 0, 'Selamat 🎉 Pembayaranmu berhasil! Mobil impianmu sudah resmi menjadi milikmu. Terima kasih telah mempercayai layanan kami 🚘✨.', 0, '2025-09-18 15:59:49'),
(19, 4, 0, 'Pesananmu sedang kami proses ✅ Tim kami sedang menyiapkan mobil impianmu. Tunggu sebentar ya, kami akan segera menghubungi kamu untuk langkah berikutnya.', 1, '2025-09-18 16:00:00'),
(20, 4, 0, 'Oops! 🚨 Pesananmu masih belum dibayar. Segera lakukan pembayaran agar mobil impianmu tidak dibooking orang lain 😉.', 1, '2025-09-18 16:00:12'),
(21, 4, 0, 'Pembayaranmu sudah kami terima 🎉 Mobil impianmu siap dibooking! Silakan datang ke toko kami untuk segera mengambil mobil idamanmu 🚗💨.', 1, '2025-09-18 16:02:14'),
(22, 3, 0, 'Pembayaranmu sudah kami terima 🎉 Mobil impianmu siap dibooking! Silakan datang ke toko kami untuk segera mengambil mobil idamanmu 🚗💨.', 0, '2025-09-18 16:02:44'),
(23, 1, 0, 'Pembayaranmu sudah kami terima 🎉 Mobil impianmu siap dibooking! Silakan datang ke toko kami untuk segera mengambil mobil idamanmu 🚗💨.', 1, '2025-09-18 16:06:41'),
(24, 1, 0, 'Selamat 🎉 Pembayaranmu berhasil! Mobil impianmu sudah resmi menjadi milikmu. Terima kasih telah mempercayai layanan kami 🚘✨.', 1, '2025-09-18 16:07:29'),
(25, 1, 0, 'Oops! 🚨 Pesananmu masih belum dibayar. Segera lakukan pembayaran agar mobil impianmu tidak dibooking orang lain 😉.', 1, '2025-09-18 16:07:50'),
(26, 1, 0, 'Selamat 🎉 Pembayaranmu berhasil! Mobil impianmu sudah resmi menjadi milikmu. Terima kasih telah mempercayai layanan kami 🚘✨.', 1, '2025-09-18 16:11:39'),
(27, 1, 0, 'Oops! 🚨 Pesananmu masih belum dibayar. Segera lakukan pembayaran agar mobil impianmu tidak dibooking orang lain 😉.', 1, '2025-09-18 16:12:10'),
(28, 1, 0, 'Selamat 🎉 Pembayaranmu berhasil! Mobil impianmu sudah resmi menjadi milikmu. Terima kasih telah mempercayai layanan kami 🚘✨.', 1, '2025-09-18 16:14:25'),
(29, 5, 0, 'Pembayaranmu sudah kami terima 🎉 Mobil impianmu siap dibooking! Silakan datang ke toko kami untuk segera mengambil mobil idamanmu 🚗💨.', 1, '2025-09-18 16:14:47'),
(30, 5, 0, 'Pembayaranmu sudah kami terima 🎉 Mobil impianmu siap dibooking! Silakan datang ke toko kami untuk segera mengambil mobil idamanmu 🚗💨.', 1, '2025-09-18 16:14:54'),
(31, 1, 0, 'Oops! 🚨 Pesananmu masih belum dibayar. Segera lakukan pembayaran agar mobil impianmu tidak dibooking orang lain 😉.', 1, '2025-09-18 16:15:25'),
(32, 1, 0, 'Pesananmu sedang kami proses ✅ Tim kami sedang menyiapkan mobil impianmu. Tunggu sebentar ya, kami akan segera menghubungi kamu untuk langkah berikutnya.', 1, '2025-09-18 16:15:40'),
(33, 1, 0, 'Pesananmu sedang kami proses ✅ Tim kami sedang menyiapkan mobil impianmu. Tunggu sebentar ya, kami akan segera menghubungi kamu untuk langkah berikutnya.', 1, '2025-09-18 16:18:37'),
(34, 1, 0, 'Pembayaranmu sudah kami terima 🎉 Mobil impianmu siap dibooking! Silakan datang ke toko kami untuk segera mengambil mobil idamanmu 🚗💨.', 1, '2025-09-18 16:26:03'),
(35, 1, 0, 'Selamat 🎉 Pembayaranmu berhasil! Mobil impianmu sudah resmi menjadi milikmu. Terima kasih telah mempercayai layanan kami 🚘✨.', 1, '2025-09-18 16:26:23'),
(36, 3, 0, 'Pesananmu sedang kami proses ✅ Tim kami sedang menyiapkan mobil impianmu. Tunggu sebentar ya, kami akan segera menghubungi kamu untuk langkah berikutnya.', 0, '2025-09-18 16:26:39'),
(37, 3, 0, 'Oops! 🚨 Pesananmu masih belum dibayar. Segera lakukan pembayaran agar mobil impianmu tidak dibooking orang lain 😉.', 0, '2025-09-18 16:26:54'),
(38, 3, 0, 'Selamat 🎉 Pembayaranmu berhasil! Mobil impianmu sudah resmi menjadi milikmu. Terima kasih telah mempercayai layanan kami 🚘✨.', 0, '2025-09-18 16:27:05'),
(39, 3, 0, 'Pembayaranmu sudah kami terima 🎉 Mobil impianmu siap dibooking! Silakan datang ke toko kami untuk segera mengambil mobil idamanmu 🚗💨.', 0, '2025-09-18 16:27:16'),
(40, 5, 0, 'Pembayaranmu sudah kami terima 🎉 Mobil impianmu siap dibooking! Silakan datang ke toko kami untuk segera mengambil mobil idamanmu 🚗💨.', 1, '2025-09-18 16:28:56'),
(41, 1, 0, 'Oops! 🚨 Pesananmu masih belum dibayar. Segera lakukan pembayaran agar mobil impianmu tidak dibooking orang lain 😉.', 1, '2025-09-18 16:29:42'),
(42, 1, 0, 'Pesananmu sedang kami proses ✅ Tim kami sedang menyiapkan mobil impianmu. Tunggu sebentar ya, kami akan segera menghubungi kamu untuk langkah berikutnya.', 1, '2025-09-18 16:29:57'),
(43, 1, 0, 'Pembayaranmu sudah kami terima 🎉 Mobil impianmu siap dibooking! Silakan datang ke toko kami untuk segera mengambil mobil idamanmu 🚗💨.', 1, '2025-09-18 16:30:26'),
(44, 1, 0, 'Selamat 🎉 Pembayaranmu berhasil! Mobil impianmu sudah resmi menjadi milikmu. Terima kasih telah mempercayai layanan kami 🚘✨.', 1, '2025-09-18 16:30:44'),
(45, 7, 0, 'Selamat 🎉 Pembayaranmu berhasil! Mobil impianmu sudah resmi menjadi milikmu. Terima kasih telah mempercayai layanan kami 🚘✨.', 1, '2025-09-18 16:31:34'),
(46, 7, 0, 'Pembayaranmu sudah kami terima 🎉 Mobil impianmu siap dibooking! Silakan datang ke toko kami untuk segera mengambil mobil idamanmu 🚗💨.', 1, '2025-09-18 16:31:52'),
(47, 4, 0, 'Pesananmu sedang kami proses ✅ Tim kami sedang menyiapkan mobil impianmu. Tunggu sebentar ya, kami akan segera menghubungi kamu untuk langkah berikutnya.', 1, '2025-09-19 02:10:07'),
(48, 4, 0, 'Pembayaranmu sudah kami terima 🎉 Mobil impianmu siap dibooking! Silakan datang ke toko kami untuk segera mengambil mobil idamanmu 🚗💨.', 1, '2025-09-19 02:11:10'),
(49, 4, 0, 'Pesananmu sedang kami proses ✅ Tim kami sedang menyiapkan mobil impianmu. Tunggu sebentar ya, kami akan segera menghubungi kamu untuk langkah berikutnya.', 1, '2025-09-19 02:11:27'),
(50, 4, 0, 'Selamat 🎉 Pembayaranmu berhasil! Mobil impianmu sudah resmi menjadi milikmu. Terima kasih telah mempercayai layanan kami 🚘✨.', 1, '2025-09-19 02:13:00'),
(51, 4, 0, 'Selamat 🎉 Pembayaranmu berhasil! Mobil impianmu sudah resmi menjadi milikmu. Terima kasih telah mempercayai layanan kami 🚘✨.', 1, '2025-09-19 02:13:18'),
(52, 4, 0, 'Pesananmu sedang kami proses ✅ Tim kami sedang menyiapkan mobil impianmu. Tunggu sebentar ya, kami akan segera menghubungi kamu untuk langkah berikutnya.', 1, '2025-09-19 02:13:30'),
(53, 4, 0, 'Pembayaranmu sudah kami terima 🎉 Mobil impianmu siap dibooking! Silakan datang ke toko kami untuk segera mengambil mobil idamanmu 🚗💨.', 1, '2025-09-19 02:13:43'),
(54, 1, 0, 'Pembayaranmu sudah kami terima 🎉 Mobil impianmu siap dibooking! Silakan datang ke toko kami untuk segera mengambil mobil idamanmu 🚗💨.', 1, '2025-09-19 02:17:33'),
(55, 1, 0, 'Pembayaranmu sudah kami terima 🎉 Mobil impianmu siap dibooking! Silakan datang ke toko kami untuk segera mengambil mobil idamanmu 🚗💨.', 1, '2025-09-19 02:19:17'),
(56, 1, 0, 'Pesananmu sedang kami proses ✅ Tim kami sedang menyiapkan mobil impianmu. Tunggu sebentar ya, kami akan segera menghubungi kamu untuk langkah berikutnya.', 1, '2025-09-19 02:19:37'),
(57, 1, 0, 'Pembayaranmu sudah kami terima 🎉 Mobil impianmu siap dibooking! Silakan datang ke toko kami untuk segera mengambil mobil idamanmu 🚗💨.', 1, '2025-09-19 02:19:48'),
(58, 1, 0, 'Selamat 🎉 Pembayaranmu berhasil! Mobil impianmu sudah resmi menjadi milikmu. Terima kasih telah mempercayai layanan kami 🚘✨.', 1, '2025-09-19 02:19:58'),
(59, 1, 0, 'Oops! 🚨 Pesananmu masih belum dibayar. Segera lakukan pembayaran agar mobil impianmu tidak dibooking orang lain 😉.', 1, '2025-09-19 02:20:10'),
(60, 1, 0, 'Pembayaranmu sudah kami terima 🎉 Mobil impianmu siap dibooking! Silakan datang ke toko kami untuk segera mengambil mobil idamanmu 🚗💨.', 1, '2025-09-19 02:20:24'),
(61, 7, 0, 'Selamat 🎉 Pembayaranmu berhasil! Mobil impianmu sudah resmi menjadi milikmu. Terima kasih telah mempercayai layanan kami 🚘✨.', 1, '2025-09-19 02:20:34'),
(62, 4, 0, 'Selamat 🎉 Pembayaranmu berhasil! Mobil impianmu sudah resmi menjadi milikmu. Terima kasih telah mempercayai layanan kami 🚘✨.', 1, '2025-09-19 02:21:11'),
(63, 4, 0, 'Pembayaranmu sudah kami terima 🎉 Mobil impianmu siap dibooking! Silakan datang ke toko kami untuk segera mengambil mobil idamanmu 🚗💨.', 1, '2025-09-19 02:21:28'),
(64, 7, 0, 'Pesananmu sedang kami proses ✅ Tim kami sedang menyiapkan mobil impianmu. Tunggu sebentar ya, kami akan segera menghubungi kamu untuk langkah berikutnya.', 1, '2025-09-19 02:26:05'),
(65, 7, 0, 'Selamat 🎉 Pembayaranmu berhasil! Mobil impianmu sudah resmi menjadi milikmu. Terima kasih telah mempercayai layanan kami 🚘✨.', 1, '2025-09-19 02:26:34'),
(66, 8, 0, 'Pesananmu sedang kami proses ✅ Tim kami sedang menyiapkan mobil impianmu. Tunggu sebentar ya, kami akan segera menghubungi kamu untuk langkah berikutnya.', 1, '2025-09-19 02:34:30'),
(67, 1, 0, 'Selamat 🎉 Pembayaranmu berhasil! Mobil impianmu sudah resmi menjadi milikmu. Terima kasih telah mempercayai layanan kami 🚘✨.', 1, '2025-09-19 02:35:13'),
(68, 8, 0, 'Pembayaranmu sudah kami terima 🎉 Mobil impianmu siap dibooking! Silakan datang ke toko kami untuk segera mengambil mobil idamanmu 🚗💨.', 1, '2025-09-19 02:43:25'),
(69, 8, 0, 'Selamat 🎉 Pembayaranmu berhasil! Mobil impianmu sudah resmi menjadi milikmu. Terima kasih telah mempercayai layanan kami 🚘✨.', 1, '2025-09-19 02:44:06'),
(70, 8, 0, 'Oops! 🚨 Pesananmu masih belum dibayar. Segera lakukan pembayaran agar mobil impianmu tidak dibooking orang lain 😉.', 1, '2025-09-19 02:44:52'),
(71, 8, 0, 'Selamat 🎉 Pembayaranmu berhasil! Mobil impianmu sudah resmi menjadi milikmu. Terima kasih telah mempercayai layanan kami 🚘✨.', 1, '2025-09-19 02:46:11'),
(72, 8, 0, 'Pembayaranmu sudah kami terima 🎉 Mobil impianmu siap dibooking! Silakan datang ke toko kami untuk segera mengambil mobil idamanmu 🚗💨.', 1, '2025-09-19 02:57:32'),
(73, 8, 0, 'Pesananmu sedang kami proses ✅ Tim kami sedang menyiapkan mobil impianmu. Tunggu sebentar ya, kami akan segera menghubungi kamu untuk langkah berikutnya.', 1, '2025-09-19 02:58:17'),
(74, 8, 0, 'Pesananmu sedang kami proses ✅ Tim kami sedang menyiapkan mobil impianmu. Tunggu sebentar ya, kami akan segera menghubungi kamu untuk langkah berikutnya.', 1, '2025-09-19 03:15:48'),
(75, 9, 0, 'Pembayaranmu sudah kami terima 🎉 Mobil impianmu siap dibooking! Silakan datang ke toko kami untuk segera mengambil mobil idamanmu 🚗💨.', 1, '2025-09-19 04:01:39'),
(76, 9, 0, 'Sabar sebentar ya! Pesananmu sedang kami proses ✅. Tim kami sedang bekerja untuk memastikan semuanya siap. Kami akan segera kabari kamu untuk langkah selanjutnya.', 1, '2025-09-19 04:26:57'),
(77, 9, 0, 'Selamat 🎉 Pembayaranmu sukses! Sekarang, mobil impianmu sudah resmi di tanganmu. Terima kasih sudah memilih layanan kami. Selamat jalan-jalan! 🚘✨.', 1, '2025-09-19 04:30:38'),
(78, 7, 0, 'Yeay! Pembayaranmu sudah kami terima 🎉. Pesananmu akan segera kami siapkan. Yuk, segera datang ke toko kami untuk mengambil mobil impianmu 🚗💨.', 1, '2025-09-19 06:28:38'),
(79, 9, 0, '✨ *NOTIFIKASI RENTAL MOBIL* ✨\n\n📋 *Detail Pesanan:*\n────────────────────\n📄 No. Booking: *1758255918*\n👤 Nama Pelanggan: *user1*\n🚗 Mobil: **\n\n💬 *Status Terbaru:*\n────────────────────\nSabar sebentar ya! Pesananmu sedang kami proses ✅. Tim kami sedang bekerja untuk memastikan semuanya siap. Kami akan segera kabari kamu untuk langkah selanjutnya.\n\n📞 *Customer Service:*\n────────────────────\nHubungi kami jika ada pertanyaan!\nTerima kasih telah mempercayai layanan kami! ❤️', 1, '2025-09-19 06:32:02'),
(80, 9, 0, '✨ *NOTIFIKASI RENTAL MOBIL* ✨\n\n📋 *Detail Pesanan:*\n────────────────────\n📄 No. Booking: *1758255918*\n👤 Nama Pelanggan: *user1*\n🚗 Mobil: **\n\n💬 *Status Terbaru:*\n────────────────────\nSelamat 🎉 Pembayaranmu sukses! Sekarang, mobil impianmu sudah resmi di tanganmu. Terima kasih sudah memilih layanan kami. Selamat jalan-jalan! 🚘✨.\n\n📞 *Customer Service:*\n────────────────────\nHubungi kami jika ada pertanyaan!\nTerima kasih telah mempercayai layanan kami! ❤️', 1, '2025-09-19 06:32:52'),
(81, 9, 0, '✨ *NOTIFIKASI RENTAL MOBIL* ✨\n\n📋 *Detail Pesanan:*\n────────────────────\n📄 No. Booking: *1758255918*\n👤 Nama Pelanggan: *user1*\n🚗 Mobil: **\n\n💬 *Status Terbaru:*\n────────────────────\nPembayaranmu telah *Diterima* 🎉. Pesananmu sedang kami siapkan. Silakan datang ke toko kami untuk pengambilan mobil. Terima kasih! 🚗💨\n\n📞 *Customer Service:*\n────────────────────\nHubungi kami jika ada pertanyaan!\nTerima kasih telah mempercayai layanan kami! ❤️', 1, '2025-09-19 06:36:24'),
(82, 9, 0, '✨ *NOTIFIKASI RENTAL MOBIL* ✨\n\n📋 *Detail Pesanan:*\n────────────────────\n📄 No. Booking: *1758255918*\n👤 Nama Pelanggan: *user1*\n🚗 Mobil: **\n\n💬 *Status Terbaru:*\n────────────────────\nPembayaranmu telah *Selesai* 🎉. Mobil impianmu kini resmi menjadi milikmu. Terima kasih telah memilih layanan kami. Selamat menikmati perjalanan! 🚘✨\n\n📞 *Customer Service:*\n────────────────────\nHubungi kami jika ada pertanyaan!\nTerima kasih telah mempercayai layanan kami! ❤️', 1, '2025-09-19 06:42:05'),
(83, 9, 0, '✨ *NOTIFIKASI RENTAL MOBIL* ✨\n\n📋 *Detail Pesanan:*\n────────────────────\n<i class=\"fas fa-file-alt\"></i> No. Booking: *1758255918*\n<i class=\"fas fa-user\"></i> Nama Pelanggan: *user1*\n<i class=\"fas fa-car\"></i> Mobil: **\n\n💬 *Status Terbaru:*\n────────────────────\nPesananmu sedang *Diproses* ✅. Tim kami sedang bekerja untuk memastikan semuanya siap. Kami akan segera mengabarimu untuk langkah selanjutnya. Mohon menunggu sebentar.\n\n📞 *Customer Service:*\n────────────────────\nHubungi kami jika ada pertanyaan!\nTerima kasih telah mempercayai layanan kami! ❤️', 1, '2025-09-19 06:55:54'),
(84, 9, 0, '✨ *NOTIFIKASI RENTAL MOBIL* ✨\n\n📋 *Detail Pesanan:*\n────────────────────\n<i class=\"fas fa-file-alt\"></i> No. Booking: *1758255918*\n<i class=\"fas fa-user\"></i> Nama Pelanggan: *user1*\n<i class=\"fas fa-car\"></i> Mobil: **\n\n💬 *Status Terbaru:*\n────────────────────\nPembayaranmu telah *Selesai* 🎉. Mobil impianmu kini resmi menjadi milikmu. Terima kasih telah memilih layanan kami. Selamat menikmati perjalanan! 🚘✨\n\n📞 *Customer Service:*\n────────────────────\nHubungi kami jika ada pertanyaan!\nTerima kasih telah mempercayai layanan kami! ❤️', 1, '2025-09-19 06:56:41'),
(85, 9, 0, '✨ *NOTIFIKASI RENTAL MOBIL* ✨\n\n📋 *Detail Pesanan:*\n────────────────────\n<i class=\"fas fa-file-alt\"></i> No. Booking: *1758255918*\n<i class=\"fas fa-user\"></i> Nama Pelanggan: *user1*\n<i class=\"fas fa-car\"></i> Mobil: **\n\n💬 *Status Terbaru:*\n────────────────────\nPesananmu masih *Menunggu Pembayaran* 🚨. Mohon segera selesaikan pembayaranmu agar mobil impianmu tidak diambil orang lain. Jangan sampai kehabisan! 😉\n\n📞 *Customer Service:*\n────────────────────\nHubungi kami jika ada pertanyaan!\nTerima kasih telah mempercayai layanan kami! ❤️', 1, '2025-09-19 06:57:36'),
(86, 9, 0, 'Selamat 🎉 Pembayaranmu sukses! Sekarang, mobil impianmu sudah resmi di tanganmu. Terima kasih sudah memilih layanan kami. Selamat jalan-jalan! 🚘✨.', 1, '2025-09-19 07:06:10'),
(87, 9, 0, 'Yeay! Pembayaranmu sudah kami terima 🎉. Pesananmu akan segera kami siapkan. Yuk, segera datang ke toko kami untuk mengambil mobil impianmu 🚗💨.', 1, '2025-09-19 07:14:24'),
(88, 9, 0, 'Sabar sebentar ya! Pesananmu sedang kami proses ✅. Tim kami sedang bekerja untuk memastikan semuanya siap. Kami akan segera kabari kamu untuk langkah selanjutnya.', 1, '2025-09-19 07:20:01'),
(89, 9, 0, '✨ *NOTIFIKASI RENTAL MOBIL* ✨\n\n💬 *Status Terbaru:*\n────────────────────\nPembayaranmu telah *Diterima* 🎉. Pesananmu sedang kami siapkan. Silakan datang ke toko kami untuk pengambilan mobil. Terima kasih! 🚗💨\n\n📞 *Customer Service:*\n────────────────────\nHubungi kami jika ada pertanyaan!\nTerima kasih telah mempercayai layanan kami! ❤️\n---BOOKING_DETAILS_START---\nNO_BOOKING:\nNAMA_PELANGGAN:user1\nMOBIL:\n---BOOKING_DETAILS_END---', 1, '2025-09-19 07:28:21'),
(90, 9, 0, '🔔 *Sedang Diproses*\nPesananmu sedang kami proses ✅ Tim kami sedang menyiapkan mobil impianmu. Tunggu sebentar ya, kami akan segera menghubungi kamu untuk langkah berikutnya.\n\n📝 *Identitas Pesanan:*\nNo Booking : 1758266026\nNama       : user1\nMobil      : New Xenia\n', 1, '2025-09-19 07:31:46'),
(91, 9, 0, '🔔 *Sudah Dibayar*\nSelamat 🎉 Pembayaranmu berhasil! Mobil impianmu sudah resmi menjadi milikmu. Terima kasih telah mempercayai layanan kami 🚘✨.\n\n📝 *Identitas Pesanan:*\nNo Booking : 1758266026\nNama       : user1\nMobil      : New Xenia\n', 1, '2025-09-19 07:32:55'),
(92, 9, 0, '🔔 *Sedang Diproses*\nPesananmu sedang kami proses ✅ Tim kami sedang menyiapkan mobil impianmu. Tunggu sebentar ya, kami akan segera menghubungi kamu untuk langkah berikutnya.\n\n📝 *Identitas Pesanan:*\nNo Booking : 1758266026\nNama       : user1\nMobil      : New Xenia\n', 1, '2025-09-19 07:33:29'),
(93, 9, 0, '🔔 *Belum Dibayar*\nOops! 🚨 Pesananmu masih belum dibayar. Segera lakukan pembayaran agar mobil impianmu tidak dibooking orang lain 😉.\n\n📝 *Identitas Pesanan:*\nNo Booking : 1758266026\nNama       : user1\nMobil      : New Xenia\n', 1, '2025-09-19 07:33:48'),
(94, 9, 0, '🔔 *Pembayaran Diterima*\nPembayaranmu sudah kami terima 🎉 Mobil impianmu siap dibooking! Silakan datang ke toko kami untuk segera mengambil mobil idamanmu 🚗💨.\n\n📝 *Identitas Pesanan:*\nNo Booking : 1758266026\nNama       : user1\nMobil      : New Xenia\n', 1, '2025-09-19 07:33:59'),
(95, 9, 0, '🔔 *Sedang Diproses*\nPesananmu sedang kami proses ✅ Tim kami sedang menyiapkan mobil impianmu. Tunggu sebentar ya, kami akan segera menghubungi kamu untuk langkah berikutnya.\n\n📝 *Identitas Pesanan:*\nNo Booking : 1758266026\nNama       : user1\nMobil      : New Xenia\n', 1, '2025-09-19 07:34:12'),
(96, 9, 0, '🔔 *Sedang Diproses*\nPesananmu sedang kami proses ✅ Tim kami sedang menyiapkan mobil impianmu. Tunggu sebentar ya, kami akan segera menghubungi kamu untuk langkah berikutnya.\n\n📝 *Identitas Pesanan:*\nNo Booking : 1758266026\nNama       : user1\nMobil      : New Xenia\n', 1, '2025-09-19 07:39:59'),
(97, 9, 0, '🔔 *Sedang Diproses*\nPesananmu sedang kami proses ✅ Tim kami sedang menyiapkan mobil impianmu. Tunggu sebentar ya, kami akan segera menghubungi kamu untuk langkah berikutnya.\n\n📝 *Identitas Pesanan:*\nNo Booking : 1758266026\nNama       : user1\nMobil      : New Xenia\n', 1, '2025-09-19 07:40:46'),
(98, 9, 0, 'Sabar sebentar ya! Pesananmu sedang kami proses ✅. Tim kami sedang bekerja untuk memastikan semuanya siap. Kami akan segera kabari kamu untuk langkah selanjutnya.', 1, '2025-09-19 07:41:32'),
(99, 9, 0, 'Sabar sebentar ya! Pesananmu sedang kami proses ✅. Tim kami sedang bekerja untuk memastikan semuanya siap. Kami akan segera kabari kamu untuk langkah selanjutnya.', 1, '2025-09-19 07:41:39'),
(100, 9, 0, 'Sabar sebentar ya! Pesananmu sedang kami proses ✅. Tim kami sedang bekerja untuk memastikan semuanya siap. Kami akan segera kabari kamu untuk langkah selanjutnya.', 1, '2025-09-19 07:43:04'),
(101, 9, 0, 'Yeay! Pembayaranmu sudah kami terima 🎉. Pesananmu akan segera kami siapkan. Yuk, segera datang ke toko kami untuk mengambil mobil impianmu 🚗💨.', 1, '2025-09-19 07:44:51'),
(102, 9, 0, 'Sabar sebentar ya! Pesananmu sedang kami proses ✅. Tim kami sedang bekerja untuk memastikan semuanya siap. Kami akan segera kabari kamu untuk langkah selanjutnya.', 1, '2025-09-19 07:44:59'),
(103, 9, 0, 'Selamat 🎉 Pembayaranmu sukses! Sekarang, mobil impianmu sudah resmi di tanganmu. Terima kasih sudah memilih layanan kami. Selamat jalan-jalan! 🚘✨.', 1, '2025-09-19 07:46:07'),
(104, 9, 0, 'Ups, jangan sampai kehabisan! 🚨 Pesananmu belum selesai, nih. Segera lakukan pembayaranmu agar mobil impianmu enggak diambil orang lain ya 😉.', 1, '2025-09-19 07:46:15'),
(105, 9, 0, 'Yeay! Pembayaranmu sudah kami terima 🎉. Pesananmu akan segera kami siapkan. Yuk, segera datang ke toko kami untuk mengambil mobil impianmu 🚗💨.', 1, '2025-09-19 07:46:23'),
(106, 9, 0, 'Yeay! Pembayaranmu sudah kami terima 🎉. Pesananmu akan segera kami siapkan. Yuk, segera datang ke toko kami untuk mengambil mobil impianmu 🚗💨.', 1, '2025-09-19 07:46:26'),
(107, 9, 0, 'Sabar sebentar ya! Pesananmu sedang kami proses ✅. Tim kami sedang bekerja untuk memastikan semuanya siap. Kami akan segera kabari kamu untuk langkah selanjutnya.', 1, '2025-09-19 07:46:36'),
(108, 9, 0, 'Sabar sebentar ya! Pesananmu sedang kami proses ✅. Tim kami sedang bekerja untuk memastikan semuanya siap. Kami akan segera kabari kamu untuk langkah selanjutnya.', 1, '2025-09-19 07:49:20'),
(109, 9, 0, '🔔 *Sedang Diproses*\nPesananmu sedang kami proses ✅ Tim kami sedang menyiapkan mobil impianmu. Tunggu sebentar ya, kami akan segera menghubungi kamu untuk langkah berikutnya.\n\n📝 *Identitas Pesanan:*\nNo Booking : 1758267754\nNama       : user1\nMobil      : New Xenia\n', 1, '2025-09-19 07:51:43'),
(110, 9, 0, '🔔 *Sedang Diproses*\nPesananmu sedang kami proses ✅ Tim kami sedang menyiapkan mobil impianmu. Tunggu sebentar ya, kami akan segera menghubungi kamu untuk langkah berikutnya.\n\n📝 *Identitas Pesanan:*\nNo Booking : 1758267754\nNama       : user1\nMobil      : New Xenia\n', 1, '2025-09-19 07:56:12'),
(111, 9, 0, '🔔 *Sedang Diproses*\nPesananmu sedang kami proses ✅ Tim kami sedang menyiapkan mobil impianmu. Tunggu sebentar ya, kami akan segera menghubungi kamu untuk langkah berikutnya.\n\n📝 *Identitas Pesanan:*\nNo Booking : 1758267941\nNama       : user1\nMobil      : New Xenia\n', 1, '2025-09-19 11:49:15'),
(112, 9, 0, 'Sabar sebentar ya! Pesananmu sedang kami proses ✅. Tim kami sedang bekerja untuk memastikan semuanya siap. Kami akan segera kabari kamu untuk langkah selanjutnya.', 1, '2025-09-19 12:11:01'),
(113, 9, 0, 'Sabar sebentar ya! Pesananmu sedang kami proses ✅. Tim kami sedang bekerja untuk memastikan semuanya siap. Kami akan segera kabari kamu untuk langkah selanjutnya.', 1, '2025-09-19 12:11:39'),
(114, 9, 0, 'Yeay! Pembayaranmu sudah kami terima 🎉. Pesananmu akan segera kami siapkan. Yuk, segera datang ke toko kami untuk mengambil mobil impianmu 🚗💨.', 1, '2025-09-19 12:11:49'),
(115, 9, 0, 'Sabar sebentar ya! Pesananmu sedang kami proses ✅. Tim kami sedang bekerja untuk memastikan semuanya siap. Kami akan segera kabari kamu untuk langkah selanjutnya.', 1, '2025-09-19 12:15:32'),
(116, 9, 0, 'Sabar sebentar ya! Pesananmu sedang kami proses ✅. Tim kami sedang bekerja untuk memastikan semuanya siap. Kami akan segera kabari kamu untuk langkah selanjutnya.', 1, '2025-09-19 12:21:59'),
(117, 9, 0, 'Sabar sebentar ya! Pesananmu sedang kami proses ✅. Tim kami sedang bekerja untuk memastikan semuanya siap. Kami akan segera kabari kamu untuk langkah selanjutnya.', 1, '2025-09-19 12:52:15'),
(118, 9, 0, 'Sabar sebentar ya! Pesananmu sedang kami proses ✅. Tim kami sedang bekerja untuk memastikan semuanya siap. Kami akan segera kabari kamu untuk langkah selanjutnya.', 1, '2025-09-19 12:52:52'),
(119, 9, 0, 'Yeay! Pembayaranmu sudah kami terima 🎉. Pesananmu akan segera kami siapkan. Yuk, segera datang ke toko kami untuk mengambil mobil impianmu 🚗💨.', 1, '2025-09-19 12:53:17'),
(120, 9, 0, 'Selamat 🎉 Pembayaranmu sukses! Sekarang, mobil impianmu sudah resmi di tanganmu. Terima kasih sudah memilih layanan kami. Selamat jalan-jalan! 🚘✨.', 1, '2025-09-19 12:53:37'),
(121, 9, 0, '🔔 Sedang Diproses\nPesananmu sedang kami proses ✅ Tim kami sedang menyiapkan mobil impianmu. Tunggu sebentar ya, kami akan segera menghubungi kamu untuk langkah berikutnya.\n\n		  Identitas Pesanan:\nNo Booking : 1758286309\nNama : user1\nMobil : New Xenia', 1, '2025-09-19 13:03:13'),
(122, 9, 0, '🔔 Sedang Diproses\nPesananmu sedang kami proses ✅ Tim kami sedang menyiapkan mobil impianmu. Tunggu sebentar ya, kami akan segera menghubungi kamu untuk langkah berikutnya.\n\n		  Identitas Pesanan:\nNo Booking : 1758287064\nNama : user1\nMobil : New Xenia', 1, '2025-09-19 13:04:48'),
(123, 9, 0, '🔔 Pembayaran Diterima\nYeay! Pembayaranmu sudah kami terima 🎉. Pesananmu akan segera kami siapkan. Yuk, segera datang ke toko kami untuk mengambil mobil impianmu 🚗💨.\n\n		  Identitas Pesanan:\nNo Booking : 1758287064\nNama : user1\nMobil : New Xenia', 1, '2025-09-19 13:04:57'),
(124, 9, 0, '🔔 Sedang Diproses\nPesananmu sedang kami proses ✅ Tim kami sedang menyiapkan mobil impianmu. Tunggu sebentar ya, kami akan segera menghubungi kamu untuk langkah berikutnya.\n\n		  Identitas Pesanan:\nNo Booking : 1758287064\nNama : user1\nMobil : New Xenia', 1, '2025-09-19 13:10:15'),
(125, 9, 0, '🔔 Pembayaran Diterima\nYeay! Pembayaranmu sudah kami terima 🎉. Pesananmu akan segera kami siapkan. Yuk, segera datang ke toko kami untuk mengambil mobil impianmu 🚗💨.\n\n		  Identitas Pesanan:\nNo Booking : 1758287064\nNama : user1\nMobil : New Xenia', 1, '2025-09-19 13:15:23'),
(126, 9, 0, '🔔 Lunas\nSelamat 🎉 Pembayaranmu lunas! Sekarang, mobil impianmu sudah resmi di tanganmu. Terima kasih sudah memilih layanan kami. Selamat jalan-jalan! 🚘✨.\n\n		  Identitas Pesanan:\nNo Booking : 1758287064\nNama : user1\nMobil : New Xenia', 1, '2025-09-19 13:15:51'),
(127, 9, 0, '🔔 Belum Dibayar\nUps, jangan sampai kehabisan! 🚨 Pesananmu belum selesai, nih. Segera lakukan pembayaranmu agar mobil impianmu enggak diambil orang lain ya 😉.\n\n		  Identitas Pesanan:\nNo Booking : 1758287064\nNama : user1\nMobil : New Xenia', 1, '2025-09-19 13:15:59'),
(128, 9, 0, '🔔 Lunas\nSelamat 🎉 Pembayaranmu lunas! Sekarang, mobil impianmu sudah resmi di tanganmu. Terima kasih sudah memilih layanan kami. Selamat jalan-jalan! 🚘✨.\n\n		  Identitas Pesanan:\nNo Booking : 1758287064\nNama : user1\nMobil : New Xenia', 1, '2025-09-19 13:16:20'),
(129, 9, 0, '🔔 Sedang Diproses\nPesananmu sedang kami proses ✅ Tim kami sedang menyiapkan mobil impianmu. Tunggu sebentar ya, kami akan segera menghubungi kamu untuk langkah berikutnya.\n\n		  Identitas Pesanan:\nNo Booking : 1758287064\nNama : user1\nMobil : New Xenia', 1, '2025-09-19 13:21:02'),
(130, 0, 0, ' **\n\n\n\n--------------------\n📋 *Detail Pesanan*\nNo. Booking : **\nNama : **\nMobil : **', 0, '2025-09-19 13:23:18'),
(131, 0, 0, ' **\n\n\n\n--------------------\n📋 *Detail Pesanan*\nNo. Booking : **\nNama : **\nMobil : **', 0, '2025-09-19 13:24:31'),
(132, 9, 0, '🔔 Pembayaran Diterima\nYeay! Pembayaranmu sudah kami terima 🎉. Pesananmu akan segera kami siapkan. Yuk, segera datang ke toko kami untuk mengambil mobil impianmu 🚗💨.\n\n		  Identitas Pesanan:\nNo Booking : 1758287064\nNama : user1\nMobil : New Xenia', 1, '2025-09-19 13:25:59'),
(133, 9, 0, '🔔 Lunas\nSelamat 🎉 Pembayaranmu lunas! Sekarang, mobil impianmu sudah resmi di tanganmu. Terima kasih sudah memilih layanan kami. Selamat jalan-jalan! 🚘✨.\n\n		  Identitas Pesanan:\nNo Booking : 1758287064\nNama : user1\nMobil : New Xenia', 1, '2025-09-19 13:28:30'),
(134, 9, 0, '🔔 Belum Dibayar\nUps, jangan sampai kehabisan! 🚨 Pesananmu belum selesai, nih. Segera lakukan pembayaranmu agar mobil impianmu enggak diambil orang lain ya 😉.\n\n		  Identitas Pesanan:\nNo Booking : 1758287064\nNama : user1\nMobil : New Xenia', 1, '2025-09-19 13:28:39'),
(135, 9, 0, '🔔 Lunas\nSelamat 🎉 Pembayaranmu lunas! Sekarang, mobil impianmu sudah resmi di tanganmu. Terima kasih sudah memilih layanan kami. Selamat jalan-jalan! 🚘✨.\n\n		  Identitas Pesanan:\nNo Booking : 1758287064\nNama : user1\nMobil : New Xenia', 1, '2025-09-19 13:29:12'),
(136, 9, 0, '<div style=\"font-family: Arial, sans-serif; line-height: 1.6;\">\n    <h4 style=\"color: #2c3e50; margin-bottom: 10px;\">\n        <i class=\"fas fa-bell\" style=\"color: #f39c12;\"></i> &nbsp; <strong>$title</strong>\n    </h4>\n    <p style=\"margin-bottom: 20px;\">$message</p>\n    \n    <div style=\"background-color: #f8f9fa; border-left: 4px solid #3498db; padding: 15px; margin-bottom: 20px;\">\n        <h5 style=\"color: #2c3e50; margin-top: 0; margin-bottom: 10px;\">\n            <i class=\"fas fa-receipt\" style=\"color: #3498db;\"></i> &nbsp; <strong>Identitas Pesanan</strong>\n        </h5>\n        <p style=\"margin: 5px 0;\"><strong>No Booking :</strong> $kode_booking</p>\n        <p style=\"margin: 5px 0;\"><strong>Nama :</strong> $customer_name</p>\n        <p style=\"margin: 5px 0;\"><strong>Mobil :</strong> $merk_mobil</p>\n    </div>\n    \n    <a href=\"$link_detail\" style=\"display: inline-block; padding: 10px 20px; background-color: #3498db; color: #ffffff; text-decoration: none; border-radius: 5px; font-weight: bold;\">\n        <i class=\"fas fa-eye\"></i> &nbsp; Lihat Detail Pesanan\n    </a>\n</div>', 1, '2025-09-19 13:34:19'),
(137, 9, 0, '<div style=\"font-family: Arial, sans-serif; line-height: 1.6;\">\n    <h4 style=\"color: #2c3e50; margin-bottom: 10px;\">\n        <i class=\"fas fa-check-circle\" style=\"color: #2ecc71;\"></i> &nbsp; <strong>Pembayaran Diterima</strong>\n    </h4>\n    <p style=\"margin-bottom: 20px;\">Yeay! Pembayaranmu sudah kami terima 🎉. Pesananmu akan segera kami siapkan. Yuk, segera datang ke toko kami untuk mengambil mobil impianmu 🚗💨.</p>\n    \n    <div style=\"background-color: #f8f9fa; border-left: 4px solid #3498db; padding: 15px; margin-bottom: 20px;\">\n        <h5 style=\"color: #2c3e50; margin-top: 0; margin-bottom: 10px;\">\n            <i class=\"fas fa-receipt\" style=\"color: #3498db;\"></i> &nbsp; <strong>Identitas Pesanan</strong>\n        </h5>\n        <p style=\"margin: 5px 0;\"><strong>No Booking :</strong> 1758287064</p>\n        <p style=\"margin: 5px 0;\"><strong>Nama :</strong> user1</p>\n        <p style=\"margin: 5px 0;\"><strong>Mobil :</strong> New Xenia</p>\n    </div>\n    \n    <a href=\"http://localhost/rental_mobil-master/bayar.php?id=1758287064\" style=\"display: inline-block; padding: 10px 20px; background-color: #3498db; color: #ffffff; text-decoration: none; border-radius: 5px; font-weight: bold;\">\n        <i class=\"fas fa-eye\"></i> &nbsp; Lihat Detail Pesanan\n    </a>\n</div>', 1, '2025-09-19 13:38:56'),
(138, 9, 0, '<div style=\"font-family: Arial, sans-serif; line-height: 1.6;\">\n    <h4 style=\"color: #2c3e50; margin-bottom: 10px;\">\n        <i class=\"fas fa-cogs\" style=\"color: #f39c12;\"></i> &nbsp; <strong>Sedang Diproses</strong>\n    </h4>\n    <p style=\"margin-bottom: 20px;\">Pesananmu sedang kami proses ✅ Tim kami sedang menyiapkan mobil impianmu. Tunggu sebentar ya, kami akan segera menghubungi kamu untuk langkah berikutnya.</p>\n    \n    <div style=\"background-color: #f8f9fa; border-left: 4px solid #3498db; padding: 15px; margin-bottom: 20px;\">\n        <h5 style=\"color: #2c3e50; margin-top: 0; margin-bottom: 10px;\">\n            <i class=\"fas fa-receipt\" style=\"color: #3498db;\"></i> &nbsp; <strong>Identitas Pesanan</strong>\n        </h5>\n        <p style=\"margin: 5px 0;\"><strong>No Booking :</strong> 1758289204</p>\n        <p style=\"margin: 5px 0;\"><strong>Nama :</strong> user1</p>\n        <p style=\"margin: 5px 0;\"><strong>Mobil :</strong> lenovo</p>\n    </div>\n    \n    <a href=\"http://localhost/rental_mobil-master/bayar.php?id=1758289204\" style=\"display: inline-block; padding: 10px 20px; background-color: #3498db; color: #ffffff; text-decoration: none; border-radius: 5px; font-weight: bold;\">\n        <i class=\"fas fa-eye\"></i> &nbsp; Lihat Detail Pesanan\n    </a>\n</div>', 1, '2025-09-19 14:07:08'),
(139, 9, 0, '<div style=\"font-family: Arial, sans-serif; line-height: 1.6;\">\n    <h4 style=\"color: #2c3e50; margin-bottom: 10px;\">\n        <i class=\"fas fa-car\" style=\"color: #3498db;\"></i> &nbsp; <strong>Lunas</strong>\n    </h4>\n    <p style=\"margin-bottom: 20px;\">Selamat 🎉 Pembayaranmu lunas! Sekarang, mobil impianmu sudah resmi di tanganmu. Terima kasih sudah memilih layanan kami. Selamat jalan-jalan! 🚘✨.</p>\n    \n    <div style=\"background-color: #f8f9fa; border-left: 4px solid #3498db; padding: 15px; margin-bottom: 20px;\">\n        <h5 style=\"color: #2c3e50; margin-top: 0; margin-bottom: 10px;\">\n            <i class=\"fas fa-receipt\" style=\"color: #3498db;\"></i> &nbsp; <strong>Identitas Pesanan</strong>\n        </h5>\n        <p style=\"margin: 5px 0;\"><strong>No Booking :</strong> 1758289204</p>\n        <p style=\"margin: 5px 0;\"><strong>Nama :</strong> user1</p>\n        <p style=\"margin: 5px 0;\"><strong>Mobil :</strong> lenovo</p>\n    </div>\n    \n    <a href=\"http://localhost/rental_mobil-master/bayar.php?id=1758289204\" style=\"display: inline-block; padding: 10px 20px; background-color: #3498db; color: #ffffff; text-decoration: none; border-radius: 5px; font-weight: bold;\">\n        <i class=\"fas fa-eye\"></i> &nbsp; Lihat Detail Pesanan\n    </a>\n</div>', 1, '2025-09-19 14:08:00'),
(140, 9, 0, '<div style=\"font-family: Arial, sans-serif; line-height: 1.6;\">\n    <h4 style=\"color: #2c3e50; margin-bottom: 10px;\">\n        <i class=\"fas fa-exclamation-triangle\" style=\"color: #e74c3c;\"></i> &nbsp; <strong>Belum Dibayar</strong>\n    </h4>\n    <p style=\"margin-bottom: 20px;\">Ups, jangan sampai kehabisan! 🚨 Pesananmu belum selesai, nih. Segera lakukan pembayaranmu agar mobil impianmu enggak diambil orang lain ya 😉.</p>\n    \n    <div style=\"background-color: #f8f9fa; border-left: 4px solid #3498db; padding: 15px; margin-bottom: 20px;\">\n        <h5 style=\"color: #2c3e50; margin-top: 0; margin-bottom: 10px;\">\n            <i class=\"fas fa-receipt\" style=\"color: #3498db;\"></i> &nbsp; <strong>Identitas Pesanan</strong>\n        </h5>\n        <p style=\"margin: 5px 0;\"><strong>No Booking :</strong> 1758289204</p>\n        <p style=\"margin: 5px 0;\"><strong>Nama :</strong> user1</p>\n        <p style=\"margin: 5px 0;\"><strong>Mobil :</strong> lenovo</p>\n    </div>\n    \n    <a href=\"http://localhost/rental_mobil-master/bayar.php?id=1758289204\" style=\"display: inline-block; padding: 10px 20px; background-color: #3498db; color: #ffffff; text-decoration: none; border-radius: 5px; font-weight: bold;\">\n        <i class=\"fas fa-eye\"></i> &nbsp; Lihat Detail Pesanan\n    </a>\n</div>', 1, '2025-09-19 14:08:43'),
(141, 9, 0, '<div style=\"font-family: Arial, sans-serif; line-height: 1.6;\">\n    <h4 style=\"color: #2c3e50; margin-bottom: 10px;\">\n        <i class=\"fas fa-car\" style=\"color: #3498db;\"></i> &nbsp; <strong>Lunas</strong>\n    </h4>\n    <p style=\"margin-bottom: 20px;\">Selamat 🎉 Pembayaranmu lunas! Sekarang, mobil impianmu sudah resmi di tanganmu. Terima kasih sudah memilih layanan kami. Selamat jalan-jalan! 🚘✨.</p>\n    \n    <div style=\"background-color: #f8f9fa; border-left: 4px solid #3498db; padding: 15px; margin-bottom: 20px;\">\n        <h5 style=\"color: #2c3e50; margin-top: 0; margin-bottom: 10px;\">\n            <i class=\"fas fa-receipt\" style=\"color: #3498db;\"></i> &nbsp; <strong>Identitas Pesanan</strong>\n        </h5>\n        <p style=\"margin: 5px 0;\"><strong>No Booking :</strong> 1758289204</p>\n        <p style=\"margin: 5px 0;\"><strong>Nama :</strong> user1</p>\n        <p style=\"margin: 5px 0;\"><strong>Mobil :</strong> lenovo</p>\n    </div>\n    \n    <a href=\"http://localhost/rental_mobil-master/bayar.php?id=1758289204\" style=\"display: inline-block; padding: 10px 20px; background-color: #3498db; color: #ffffff; text-decoration: none; border-radius: 5px; font-weight: bold;\">\n        <i class=\"fas fa-eye\"></i> &nbsp; Lihat Detail Pesanan\n    </a>\n</div>', 1, '2025-09-19 14:09:18'),
(142, 9, 0, '<div style=\"font-family: Arial, sans-serif; line-height: 1.6;\">\n    <h4 style=\"color: #2c3e50; margin-bottom: 10px;\">\n        <i class=\"fas fa-car\" style=\"color: #3498db;\"></i> &nbsp; <strong>Lunas</strong>\n    </h4>\n    <p style=\"margin-bottom: 20px;\">Selamat 🎉 Pembayaranmu lunas! Sekarang, mobil impianmu sudah resmi di tanganmu. Terima kasih sudah memilih layanan kami. Selamat jalan-jalan! 🚘✨.</p>\n    \n    <div style=\"background-color: #f8f9fa; border-left: 4px solid #3498db; padding: 15px; margin-bottom: 20px;\">\n        <h5 style=\"color: #2c3e50; margin-top: 0; margin-bottom: 10px;\">\n            <i class=\"fas fa-receipt\" style=\"color: #3498db;\"></i> &nbsp; <strong>Identitas Pesanan</strong>\n        </h5>\n        <p style=\"margin: 5px 0;\"><strong>No Booking :</strong> 1758289204</p>\n        <p style=\"margin: 5px 0;\"><strong>Nama :</strong> user1</p>\n        <p style=\"margin: 5px 0;\"><strong>Mobil :</strong> lenovo</p>\n    </div>\n    \n    <a href=\"http://localhost/rental_mobil-master/bayar.php?id=1758289204\" style=\"display: inline-block; padding: 10px 20px; background-color:  #FF6B35; color: #ffffff; text-decoration: none; border-radius: 5px; font-weight: bold;\">\n        <i class=\"fas fa-eye\"></i> &nbsp; Lihat Detail Pesanan\n    </a>\n</div>', 1, '2025-09-19 14:11:05'),
(143, 9, 0, '<div style=\"font-family: Arial, sans-serif; line-height: 1.6;\">\n    <h4 style=\"color: #2c3e50; margin-bottom: 10px;\">\n        <i class=\"fas fa-exclamation-triangle\" style=\"color: #e74c3c;\"></i> &nbsp; <strong>Belum Dibayar</strong>\n    </h4>\n    <p style=\"margin-bottom: 20px;\">Ups, jangan sampai kehabisan! 🚨 Pesananmu belum selesai, nih. Segera lakukan pembayaranmu agar mobil impianmu enggak diambil orang lain ya 😉.</p>\n    \n    <div style=\"background-color: #f8f9fa; border-left: 4px solid #3498db; padding: 15px; margin-bottom: 20px;\">\n        <h5 style=\"color: #2c3e50; margin-top: 0; margin-bottom: 10px;\">\n            <i class=\"fas fa-receipt\" style=\"color: #3498db;\"></i> &nbsp; <strong>Identitas Pesanan</strong>\n        </h5>\n        <p style=\"margin: 5px 0;\"><strong>No Booking :</strong> 1758289204</p>\n        <p style=\"margin: 5px 0;\"><strong>Nama :</strong> user1</p>\n        <p style=\"margin: 5px 0;\"><strong>Mobil :</strong> lenovo</p>\n    </div>\n    \n    <a href=\"http://localhost/rental_mobil-master/bayar.php?id=1758289204\" style=\"display: inline-block; padding: 10px 20px; background-color:  #FF6B35; color: #ffffff; text-decoration: none; border-radius: 5px; font-weight: bold;\">\n        <i class=\"fas fa-eye\"></i> &nbsp; Lihat Detail Pesanan\n    </a>\n</div>', 1, '2025-09-19 14:14:35'),
(144, 9, 0, '<div style=\"font-family: Arial, sans-serif; line-height: 1.6;\">\n    <h4 style=\"color: #2c3e50; margin-bottom: 10px;\">\n        <i class=\"fas fa-exclamation-triangle\" style=\"color: #e74c3c;\"></i> &nbsp; <strong>Belum Dibayar</strong>\n    </h4>\n    <p style=\"margin-bottom: 20px;\">Ups, jangan sampai kehabisan! 🚨 Pesananmu belum selesai, nih. Segera lakukan pembayaranmu agar mobil impianmu enggak diambil orang lain ya 😉.</p>\n    \n    <div style=\"background-color: #f8f9fa; border-left: 4px solid #3498db; padding: 15px; margin-bottom: 20px;\">\n        <h5 style=\"color: #2c3e50; margin-top: 0; margin-bottom: 10px;\">\n            <i class=\"fas fa-receipt\" style=\"color: #3498db;\"></i> &nbsp; <strong>Identitas Pesanan</strong>\n        </h5>\n        <p style=\"margin: 5px 0;\"><strong>No Booking :</strong> 1758289204</p>\n        <p style=\"margin: 5px 0;\"><strong>Nama :</strong> user1</p>\n        <p style=\"margin: 5px 0;\"><strong>Mobil :</strong> lenovo</p>\n    </div>\n    \n    <a href=\"http://localhost/rental_mobil-master/bayar.php?id=1758289204\" style=\"display: inline-block; padding: 10px 20px; background-color:  #FF6B35; color: #ffffff; text-decoration: none; border-radius: 5px; font-weight: bold;\">\n        <i class=\"fas fa-eye\"></i> &nbsp; Lihat Detail Pesanan\n    </a>\n</div>', 1, '2025-09-19 14:15:07'),
(145, 9, 0, '<div style=\"font-family: Arial, sans-serif; line-height: 1.6;\">\n    <h4 style=\"color: #2c3e50; margin-bottom: 10px;\">\n        <i class=\"fas fa-exclamation-triangle\" style=\"color: #e74c3c;\"></i> &nbsp; <strong>Belum Dibayar</strong>\n    </h4>\n    <p style=\"margin-bottom: 20px;\">Ups, jangan sampai kehabisan! 🚨 Pesananmu belum selesai, nih. Segera lakukan pembayaranmu agar mobil impianmu enggak diambil orang lain ya 😉.</p>\n    \n    <div style=\"background-color: #f8f9fa; border-left: 4px solid #3498db; padding: 15px; margin-bottom: 20px;\">\n        <h5 style=\"color: #2c3e50; margin-top: 0; margin-bottom: 10px;\">\n            <i class=\"fas fa-receipt\" style=\"color: #3498db;\"></i> &nbsp; <strong>Identitas Pesanan</strong>\n        </h5>\n        <p style=\"margin: 5px 0;\"><strong>No Booking :</strong> 1758289204</p>\n        <p style=\"margin: 5px 0;\"><strong>Nama :</strong> user1</p>\n        <p style=\"margin: 5px 0;\"><strong>Mobil :</strong> lenovo</p>\n    </div>\n    \n    <a href=\"http://localhost/rental_mobil-master/bayar.php?id=1758289204\" style=\"display: inline-block; padding: 10px 20px; background-color:  #FF6B35; color: #ffffff; text-decoration: none; border-radius: 5px; font-weight: bold;\">\n        <i class=\"fas fa-eye\"></i> &nbsp; Lihat Detail Pesanan\n    </a>\n</div>', 1, '2025-09-19 14:15:13'),
(146, 9, 0, '<div style=\"font-family: Arial, sans-serif; line-height: 1.6;\">\n    <h4 style=\"color: #2c3e50; margin-bottom: 10px;\">\n        <i class=\"fas fa-exclamation-triangle\" style=\"color: #e74c3c;\"></i> &nbsp; <strong>Belum Dibayar</strong>\n    </h4>\n    <p style=\"margin-bottom: 20px;\">Ups, jangan sampai kehabisan! 🚨 Pesananmu belum selesai, nih. Segera lakukan pembayaranmu agar mobil impianmu enggak diambil orang lain ya 😉.</p>\n    \n    <div style=\"background-color: #f8f9fa; border-left: 4px solid #3498db; padding: 15px; margin-bottom: 20px;\">\n        <h5 style=\"color: #2c3e50; margin-top: 0; margin-bottom: 10px;\">\n            <i class=\"fas fa-receipt\" style=\"color: #3498db;\"></i> &nbsp; <strong>Identitas Pesanan</strong>\n        </h5>\n        <p style=\"margin: 5px 0;\"><strong>No Booking :</strong> 1758289204</p>\n        <p style=\"margin: 5px 0;\"><strong>Nama :</strong> user1</p>\n        <p style=\"margin: 5px 0;\"><strong>Mobil :</strong> lenovo</p>\n    </div>\n    \n    <a href=\"http://localhost/rental_mobil-master/bayar.php?id=1758289204\" style=\"display: inline-block; padding: 10px 20px; background-color:  #FF6B35; color: #ffffff; text-decoration: none; border-radius: 5px; font-weight: bold;\">\n        <i class=\"fas fa-eye\"></i> &nbsp; Lihat Detail Pesanan\n    </a>\n</div>', 1, '2025-09-19 14:15:22'),
(147, 9, 0, '<div style=\"font-family: Arial, sans-serif; line-height: 1.6;\">\n    <h4 style=\"color: #2c3e50; margin-bottom: 10px;\">\n        <i class=\"fas fa-exclamation-triangle\" style=\"color: #e74c3c;\"></i> &nbsp; <strong>Belum Dibayar</strong>\n    </h4>\n    <p style=\"margin-bottom: 20px;\">Ups, jangan sampai kehabisan! 🚨 Pesananmu belum selesai, nih. Segera lakukan pembayaranmu agar mobil impianmu enggak diambil orang lain ya 😉.</p>\n    \n    <div style=\"background-color: #f8f9fa; border-left: 4px solid #3498db; padding: 15px; margin-bottom: 20px;\">\n        <h5 style=\"color: #2c3e50; margin-top: 0; margin-bottom: 10px;\">\n            <i class=\"fas fa-receipt\" style=\"color: #3498db;\"></i> &nbsp; <strong>Identitas Pesanan</strong>\n        </h5>\n        <p style=\"margin: 5px 0;\"><strong>No Booking :</strong> 1758289204</p>\n        <p style=\"margin: 5px 0;\"><strong>Nama :</strong> user1</p>\n        <p style=\"margin: 5px 0;\"><strong>Mobil :</strong> lenovo</p>\n    </div>\n    \n    <a href=\"http://localhost/rental_mobil-master/bayar.php?id=1758289204\" style=\"display: inline-block; padding: 10px 20px; background-color:  #FF6B35; color: #ffffff; text-decoration: none; border-radius: 5px; font-weight: bold;\">\n        <i class=\"fas fa-eye\"></i> &nbsp; Lihat Detail Pesanan\n    </a>\n</div>', 1, '2025-09-19 14:16:12'),
(148, 9, 0, '<div style=\"font-family: Arial, sans-serif; line-height: 1.6;\">\n    <h4 style=\"color: #2c3e50; margin-bottom: 10px;\">\n        <i class=\"fas fa-car\" style=\"color: #3498db;\"></i> &nbsp; <strong>Selesai</strong>\n    </h4>\n    <p style=\"margin-bottom: 20px;\">Selamat 🎉 Pembayaranmu lunas! Sekarang, mobil impianmu sudah resmi di tanganmu. Terima kasih sudah memilih layanan kami. Selamat jalan-jalan! 🚘✨.</p>\n    \n    <div style=\"background-color: #f8f9fa; border-left: 4px solid #3498db; padding: 15px; margin-bottom: 20px;\">\n        <h5 style=\"color: #2c3e50; margin-top: 0; margin-bottom: 10px;\">\n            <i class=\"fas fa-receipt\" style=\"color: #3498db;\"></i> &nbsp; <strong>Identitas Pesanan</strong>\n        </h5>\n        <p style=\"margin: 5px 0;\"><strong>No Booking :</strong> 1758289204</p>\n        <p style=\"margin: 5px 0;\"><strong>Nama :</strong> user1</p>\n        <p style=\"margin: 5px 0;\"><strong>Mobil :</strong> lenovo</p>\n    </div>\n    \n    <a href=\"http://localhost/rental_mobil-master/bayar.php?id=1758289204\" style=\"display: inline-block; padding: 10px 20px; background-color:  #FF6B35; color: #ffffff; text-decoration: none; border-radius: 5px; font-weight: bold;\">\n        <i class=\"fas fa-eye\"></i> &nbsp; Lihat Detail Pesanan\n    </a>\n</div>', 1, '2025-09-19 14:16:19'),
(149, 9, 0, '<div style=\"font-family: Arial, sans-serif; line-height: 1.6;\">\n    <h4 style=\"color: #2c3e50; margin-bottom: 10px;\">\n        <i class=\"fas fa-car\" style=\"color: #3498db;\"></i> &nbsp; <strong>Selesai</strong>\n    </h4>\n    <p style=\"margin-bottom: 20px;\">Selamat 🎉 Pembayaranmu lunas! Sekarang, mobil impianmu sudah resmi di tanganmu. Terima kasih sudah memilih layanan kami. Selamat jalan-jalan! 🚘✨.</p>\n    \n    <div style=\"background-color: #f8f9fa; border-left: 4px solid #3498db; padding: 15px; margin-bottom: 20px;\">\n        <h5 style=\"color: #2c3e50; margin-top: 0; margin-bottom: 10px;\">\n            <i class=\"fas fa-receipt\" style=\"color: #3498db;\"></i> &nbsp; <strong>Identitas Pesanan</strong>\n        </h5>\n        <p style=\"margin: 5px 0;\"><strong>No Booking :</strong> 1758289204</p>\n        <p style=\"margin: 5px 0;\"><strong>Nama :</strong> user1</p>\n        <p style=\"margin: 5px 0;\"><strong>Mobil :</strong> lenovo</p>\n    </div>\n    \n    <a href=\"http://localhost/rental_mobil-master/bayar.php?id=1758289204\" style=\"display: inline-block; padding: 10px 20px; background-color:  #FF6B35; color: #ffffff; text-decoration: none; border-radius: 5px; font-weight: bold;\">\n        <i class=\"fas fa-eye\"></i> &nbsp; Lihat Detail Pesanan\n    </a>\n</div>', 1, '2025-09-19 14:16:36');
INSERT INTO `notifikasi` (`id`, `id_login`, `id_booking`, `pesan`, `status_baca`, `created_at`) VALUES
(150, 9, 0, '<div style=\"font-family: Arial, sans-serif; line-height: 1.6;\">\n    <h4 style=\"color: #2c3e50; margin-bottom: 10px;\">\n        <i class=\"fas fa-cogs\" style=\"color: #f39c12;\"></i> &nbsp; <strong>Sedang Diproses</strong>\n    </h4>\n    <p style=\"margin-bottom: 20px;\">Pesananmu sedang kami proses ✅ Tim kami sedang menyiapkan mobil impianmu. Tunggu sebentar ya, kami akan segera menghubungi kamu untuk langkah berikutnya.</p>\n    \n    <div style=\"background-color: #f8f9fa; border-left: 4px solid #3498db; padding: 15px; margin-bottom: 20px;\">\n        <h5 style=\"color: #2c3e50; margin-top: 0; margin-bottom: 10px;\">\n            <i class=\"fas fa-receipt\" style=\"color: #3498db;\"></i> &nbsp; <strong>Identitas Pesanan</strong>\n        </h5>\n        <p style=\"margin: 5px 0;\"><strong>No Booking :</strong> 1758289204</p>\n        <p style=\"margin: 5px 0;\"><strong>Nama :</strong> user1</p>\n        <p style=\"margin: 5px 0;\"><strong>Mobil :</strong> lenovo</p>\n    </div>\n    \n    <a href=\"http://localhost/rental_mobil-master/bayar.php?id=1758289204\" style=\"display: inline-block; padding: 10px 20px; background-color:  #FF6B35; color: #ffffff; text-decoration: none; border-radius: 5px; font-weight: bold;\">\n        <i class=\"fas fa-eye\"></i> &nbsp; Lihat Detail Pesanan\n    </a>\n</div>', 1, '2025-09-19 14:25:45'),
(151, 9, 0, '<div style=\"font-family: Arial, sans-serif; line-height: 1.6;\">\n    <h4 style=\"color: #2c3e50; margin-bottom: 10px;\">\n        <i class=\"fas fa-check-circle\" style=\"color: #2ecc71;\"></i> &nbsp; <strong>Pembayaran Diterima</strong>\n    </h4>\n    <p style=\"margin-bottom: 20px;\">Yeay! Pembayaranmu sudah kami terima 🎉. Pesananmu akan segera kami siapkan. Yuk, segera datang ke toko kami untuk mengambil mobil impianmu 🚗💨.</p>\n    \n    <div style=\"background-color: #f8f9fa; border-left: 4px solid #3498db; padding: 15px; margin-bottom: 20px;\">\n        <h5 style=\"color: #2c3e50; margin-top: 0; margin-bottom: 10px;\">\n            <i class=\"fas fa-receipt\" style=\"color: #3498db;\"></i> &nbsp; <strong>Identitas Pesanan</strong>\n        </h5>\n        <p style=\"margin: 5px 0;\"><strong>No Booking :</strong> 1758289204</p>\n        <p style=\"margin: 5px 0;\"><strong>Nama :</strong> user1</p>\n        <p style=\"margin: 5px 0;\"><strong>Mobil :</strong> lenovo</p>\n    </div>\n    \n    <a href=\"http://localhost/rental_mobil-master/bayar.php?id=1758289204\" style=\"display: inline-block; padding: 10px 20px; background-color:  #FF6B35; color: #ffffff; text-decoration: none; border-radius: 5px; font-weight: bold;\">\n        <i class=\"fas fa-eye\"></i> &nbsp; Lihat Detail Pesanan\n    </a>\n</div>', 1, '2025-09-19 14:25:53'),
(152, 9, 0, '<div style=\"font-family: Arial, sans-serif; line-height: 1.6;\">\n    <h4 style=\"color: #2c3e50; margin-bottom: 10px;\">\n        <i class=\"fas fa-car\" style=\"color: #3498db;\"></i> &nbsp; <strong>Selesai</strong>\n    </h4>\n    <p style=\"margin-bottom: 20px;\">Selamat 🎉 Pembayaranmu lunas! Sekarang, mobil impianmu sudah resmi di tanganmu. Terima kasih sudah memilih layanan kami. Selamat jalan-jalan! 🚘✨.</p>\n    \n    <div style=\"background-color: #f8f9fa; border-left: 4px solid #3498db; padding: 15px; margin-bottom: 20px;\">\n        <h5 style=\"color: #2c3e50; margin-top: 0; margin-bottom: 10px;\">\n            <i class=\"fas fa-receipt\" style=\"color: #3498db;\"></i> &nbsp; <strong>Identitas Pesanan</strong>\n        </h5>\n        <p style=\"margin: 5px 0;\"><strong>No Booking :</strong> 1758289204</p>\n        <p style=\"margin: 5px 0;\"><strong>Nama :</strong> user1</p>\n        <p style=\"margin: 5px 0;\"><strong>Mobil :</strong> lenovo</p>\n    </div>\n    \n    <a href=\"http://localhost/rental_mobil-master/bayar.php?id=1758289204\" style=\"display: inline-block; padding: 10px 20px; background-color:  #FF6B35; color: #ffffff; text-decoration: none; border-radius: 5px; font-weight: bold;\">\n        <i class=\"fas fa-eye\"></i> &nbsp; Lihat Detail Pesanan\n    </a>\n</div>', 1, '2025-09-19 14:26:07'),
(153, 9, 0, '<div style=\"font-family: Arial, sans-serif; line-height: 1.6;\">\n    <h4 style=\"color: #2c3e50; margin-bottom: 10px;\">\n        <i class=\"fas fa-exclamation-triangle\" style=\"color: #e74c3c;\"></i> &nbsp; <strong>Belum Dibayar</strong>\n    </h4>\n    <p style=\"margin-bottom: 20px;\">Ups, jangan sampai kehabisan! 🚨 Pesananmu belum selesai, nih. Segera lakukan pembayaranmu agar mobil impianmu enggak diambil orang lain ya 😉.</p>\n    \n    <div style=\"background-color: #f8f9fa; border-left: 4px solid #3498db; padding: 15px; margin-bottom: 20px;\">\n        <h5 style=\"color: #2c3e50; margin-top: 0; margin-bottom: 10px;\">\n            <i class=\"fas fa-receipt\" style=\"color: #3498db;\"></i> &nbsp; <strong>Identitas Pesanan</strong>\n        </h5>\n        <p style=\"margin: 5px 0;\"><strong>No Booking :</strong> 1758289204</p>\n        <p style=\"margin: 5px 0;\"><strong>Nama :</strong> user1</p>\n        <p style=\"margin: 5px 0;\"><strong>Mobil :</strong> lenovo</p>\n    </div>\n    \n    <a href=\"http://localhost/rental_mobil-master/bayar.php?id=1758289204\" style=\"display: inline-block; padding: 10px 20px; background-color:  #FF6B35; color: #ffffff; text-decoration: none; border-radius: 5px; font-weight: bold;\">\n        <i class=\"fas fa-eye\"></i> &nbsp; Lihat Detail Pesanan\n    </a>\n</div>', 1, '2025-09-19 14:26:13'),
(154, 9, 0, '<div style=\"font-family: Arial, sans-serif; line-height: 1.6;\">\n    <h4 style=\"color: #2c3e50; margin-bottom: 10px;\">\n        <i class=\"fas fa-car\" style=\"color: #3498db;\"></i> &nbsp; <strong>Selesai</strong>\n    </h4>\n    <p style=\"margin-bottom: 20px;\">Selamat 🎉 Pembayaranmu lunas! Sekarang, mobil impianmu sudah resmi di tanganmu. Terima kasih sudah memilih layanan kami. Selamat jalan-jalan! 🚘✨.</p>\n    \n    <div style=\"background-color: #f8f9fa; border-left: 4px solid #3498db; padding: 15px; margin-bottom: 20px;\">\n        <h5 style=\"color: #2c3e50; margin-top: 0; margin-bottom: 10px;\">\n            <i class=\"fas fa-receipt\" style=\"color: #3498db;\"></i> &nbsp; <strong>Identitas Pesanan</strong>\n        </h5>\n        <p style=\"margin: 5px 0;\"><strong>No Booking :</strong> 1758289204</p>\n        <p style=\"margin: 5px 0;\"><strong>Nama :</strong> user1</p>\n        <p style=\"margin: 5px 0;\"><strong>Mobil :</strong> lenovo</p>\n    </div>\n    \n    <a href=\"http://localhost/rental_mobil-master/bayar.php?id=1758289204\" style=\"display: inline-block; padding: 10px 20px; background-color:  #FF6B35; color: #ffffff; text-decoration: none; border-radius: 5px; font-weight: bold;\">\n        <i class=\"fas fa-eye\"></i> &nbsp; Lihat Detail Pesanan\n    </a>\n</div>', 1, '2025-09-19 14:26:19'),
(155, 9, 0, '<div style=\"font-family: Arial, sans-serif; line-height: 1.6;\">\n    <h4 style=\"color: #2c3e50; margin-bottom: 10px;\">\n        <i class=\"fas fa-check-circle\" style=\"color: #2ecc71;\"></i> &nbsp; <strong>Pembayaran Diterima</strong>\n    </h4>\n    <p style=\"margin-bottom: 20px;\">Yeay! Pembayaranmu sudah kami terima 🎉. Pesananmu akan segera kami siapkan. Yuk, segera datang ke toko kami untuk mengambil mobil impianmu 🚗💨.</p>\n    \n    <div style=\"background-color: #f8f9fa; border-left: 4px solid #3498db; padding: 15px; margin-bottom: 20px;\">\n        <h5 style=\"color: #2c3e50; margin-top: 0; margin-bottom: 10px;\">\n            <i class=\"fas fa-receipt\" style=\"color: #3498db;\"></i> &nbsp; <strong>Identitas Pesanan</strong>\n        </h5>\n        <p style=\"margin: 5px 0;\"><strong>No Booking :</strong> 1758292110</p>\n        <p style=\"margin: 5px 0;\"><strong>Nama :</strong> user1</p>\n        <p style=\"margin: 5px 0;\"><strong>Mobil :</strong> lenovo</p>\n    </div>\n    \n    <a href=\"http://localhost/rental_mobil-master/bayar.php?id=1758292110\" style=\"display: inline-block; padding: 10px 20px; background-color:  #FF6B35; color: #ffffff; text-decoration: none; border-radius: 5px; font-weight: bold;\">\n        <i class=\"fas fa-eye\"></i> &nbsp; Lihat Detail Pesanan\n    </a>\n</div>', 1, '2025-09-19 14:29:03'),
(156, 9, 0, '<div style=\"font-family: Arial, sans-serif; line-height: 1.6;\">\n    <h4 style=\"color: #2c3e50; margin-bottom: 10px;\">\n        <i class=\"fas fa-check-circle\" style=\"color: #2ecc71;\"></i> &nbsp; <strong>Pembayaran Diterima</strong>\n    </h4>\n    <p style=\"margin-bottom: 20px;\">Yeay! Pembayaranmu sudah kami terima 🎉. Pesananmu akan segera kami siapkan. Yuk, segera datang ke toko kami untuk mengambil mobil impianmu 🚗💨.</p>\n    \n    <div style=\"background-color: #f8f9fa; border-left: 4px solid #3498db; padding: 15px; margin-bottom: 20px;\">\n        <h5 style=\"color: #2c3e50; margin-top: 0; margin-bottom: 10px;\">\n            <i class=\"fas fa-receipt\" style=\"background-color:  #FF6B35;\"></i> &nbsp; <strong>Identitas Pesanan</strong>\n        </h5>\n        <p style=\"margin: 5px 0;\"><strong>No Booking :</strong> 1758292110</p>\n        <p style=\"margin: 5px 0;\"><strong>Nama :</strong> user1</p>\n        <p style=\"margin: 5px 0;\"><strong>Mobil :</strong> lenovo</p>\n    </div>\n    \n    <a href=\"http://localhost/rental_mobil-master/bayar.php?id=1758292110\" style=\"display: inline-block; padding: 10px 20px; background-color:  #FF6B35; color: #ffffff; text-decoration: none; border-radius: 5px; font-weight: bold;\">\n        <i class=\"fas fa-eye\"></i> &nbsp; Lihat Detail Pesanan\n    </a>\n</div>', 1, '2025-09-19 14:32:07'),
(157, 9, 0, '<div style=\"font-family: Arial, sans-serif; line-height: 1.6;\">\n    <h4 style=\"color: #2c3e50; margin-bottom: 10px;\">\n        <i class=\"fas fa-check-circle\" style=\"color: #2ecc71;\"></i> &nbsp; <strong>Pembayaran Diterima</strong>\n    </h4>\n    <p style=\"margin-bottom: 20px;\">Yeay! Pembayaranmu sudah kami terima 🎉. Pesananmu akan segera kami siapkan. Yuk, segera datang ke toko kami untuk mengambil mobil impianmu 🚗💨.</p>\n    \n    <div style=\"background-color: #f8f9fa; border-left: 4px solid #3498db; padding: 15px; margin-bottom: 20px;\">\n        <h5 style=\"color: #ffffffff; margin-top: 0; margin-bottom: 10px;\">\n            <i class=\"fas fa-receipt\" style=\"background-color:  #FF6B35;\"></i> &nbsp; <strong>Identitas Pesanan</strong>\n        </h5>\n        <p style=\"margin: 5px 0;\"><strong>No Booking :</strong> 1758292110</p>\n        <p style=\"margin: 5px 0;\"><strong>Nama :</strong> user1</p>\n        <p style=\"margin: 5px 0;\"><strong>Mobil :</strong> lenovo</p>\n    </div>\n    \n    <a href=\"http://localhost/rental_mobil-master/bayar.php?id=1758292110\" style=\"display: inline-block; padding: 10px 20px; background-color:  #FF6B35; color: #ffffff; text-decoration: none; border-radius: 5px; font-weight: bold;\">\n        <i class=\"fas fa-eye\"></i> &nbsp; Lihat Detail Pesanan\n    </a>\n</div>', 1, '2025-09-19 14:32:48'),
(158, 9, 0, '<div style=\"font-family: Arial, sans-serif; line-height: 1.6;\">\n    <h4 style=\"color: #2c3e50; margin-bottom: 10px;\">\n        <i class=\"fas fa-check-circle\" style=\"color: #2ecc71;\"></i> &nbsp; <strong>Pembayaran Diterima</strong>\n    </h4>\n    <p style=\"margin-bottom: 20px;\">Yeay! Pembayaranmu sudah kami terima 🎉. Pesananmu akan segera kami siapkan. Yuk, segera datang ke toko kami untuk mengambil mobil impianmu 🚗💨.</p>\n    \n    <div style=\"background-color: #f8f9fa; border-left: 4px solid #3498db; padding: 15px; margin-bottom: 20px;\">\n        <h5 style=\"color: #2c3e50; margin-top: 0; margin-bottom: 10px;\">\n            <i class=\"fas fa-receipt\" style=\"color: #3498db;\"></i> &nbsp; <strong>Identitas Pesanan</strong>\n        </h5>\n        <p style=\"margin: 5px 0;\"><strong>No Booking :</strong> 1758292110</p>\n        <p style=\"margin: 5px 0;\"><strong>Nama :</strong> user1</p>\n        <p style=\"margin: 5px 0;\"><strong>Mobil :</strong> lenovo</p>\n    </div>\n    \n    <a href=\"http://localhost/rental_mobil-master/bayar.php?id=1758292110\" style=\"display: inline-block; padding: 10px 20px; background-color:  #FF6B35; color: #ffffff; text-decoration: none; border-radius: 5px; font-weight: bold;\">\n        <i class=\"fas fa-eye\"></i> &nbsp; Lihat Detail Pesanan\n    </a>\n</div>', 1, '2025-09-19 14:33:31'),
(159, 9, 0, '<div style=\"font-family: Arial, sans-serif; line-height: 1.6;\">\n    <h4 style=\"color: #2c3e50; margin-bottom: 10px;\">\n        <i class=\"fas fa-check-circle\" style=\"color: #2ecc71;\"></i> &nbsp; <strong>Pembayaran Diterima</strong>\n    </h4>\n    <p style=\"margin-bottom: 20px;\">Yeay! Pembayaranmu sudah kami terima 🎉. Pesananmu akan segera kami siapkan. Yuk, segera datang ke toko kami untuk mengambil mobil impianmu 🚗💨.</p>\n    \n    <div style=\"background-color: #f8f9fa; border-left: 4px solid #3498db; padding: 15px; margin-bottom: 20px;\">\n        <h5 style=\"color: #2c3e50; margin-top: 0; margin-bottom: 10px;\">\n            <i class=\"fas fa-receipt\" style=\"color: #FF6B35;\"></i> <strong style=\"color: #333;\">Identitas Pesanan</strong>\n        </h5>\n        <p style=\"margin: 5px 0;\"><strong>No Booking :</strong> 1758292110</p>\n        <p style=\"margin: 5px 0;\"><strong>Nama :</strong> user1</p>\n        <p style=\"margin: 5px 0;\"><strong>Mobil :</strong> lenovo</p>\n    </div>\n    \n    <a href=\"http://localhost/rental_mobil-master/bayar.php?id=1758292110\" style=\"display: inline-block; padding: 10px 20px; background-color:  #FF6B35; color: #ffffff; text-decoration: none; border-radius: 5px; font-weight: bold;\">\n        <i class=\"fas fa-eye\"></i> &nbsp; Lihat Detail Pesanan\n    </a>\n</div>', 1, '2025-09-19 14:34:59'),
(160, 9, 0, '<div style=\"font-family: Arial, sans-serif; line-height: 1.6;\">\n    <h4 style=\"color: #2c3e50; margin-bottom: 10px;\">\n        <i class=\"fas fa-check-circle\" style=\"color: #2ecc71;\"></i> &nbsp; <strong>Pembayaran Diterima</strong>\n    </h4>\n    <p style=\"margin-bottom: 20px;\">Yeay! Pembayaranmu sudah kami terima 🎉. Pesananmu akan segera kami siapkan. Yuk, segera datang ke toko kami untuk mengambil mobil impianmu 🚗💨.</p>\n    \n    <div style=\"background-color: #f8f9fa; border-left: 4px solid #1A237E; padding: 15px; margin-bottom: 20px;\">\n        <h5 style=\"color: #2c3e50; margin-top: 0; margin-bottom: 10px;\">\n            <i class=\"fas fa-receipt\" style=\"color: #FF6B35;\"></i> <strong style=\"color: #333;\">Identitas Pesanan</strong>\n        </h5>\n        <p style=\"margin: 5px 0;\"><strong>No Booking :</strong> 1758292110</p>\n        <p style=\"margin: 5px 0;\"><strong>Nama :</strong> user1</p>\n        <p style=\"margin: 5px 0;\"><strong>Mobil :</strong> lenovo</p>\n    </div>\n    \n    <a href=\"http://localhost/rental_mobil-master/bayar.php?id=1758292110\" style=\"display: inline-block; padding: 10px 20px; background-color:  #FF6B35; color: #ffffff; text-decoration: none; border-radius: 5px; font-weight: bold;\">\n        <i class=\"fas fa-eye\"></i> &nbsp; Lihat Detail Pesanan\n    </a>\n</div>', 1, '2025-09-19 14:37:08'),
(161, 9, 0, '<div style=\"font-family: Arial, sans-serif; line-height: 1.6;\">\n    <h4 style=\"color: #2c3e50; margin-bottom: 10px;\">\n        <i class=\"fas fa-check-circle\" style=\"color: #2ecc71;\"></i> &nbsp; <strong>Pembayaran Diterima</strong>\n    </h4>\n    <p style=\"margin-bottom: 20px;\">Yeay! Pembayaranmu sudah kami terima 🎉. Pesananmu akan segera kami siapkan. Yuk, segera datang ke toko kami untuk mengambil mobil impianmu 🚗💨.</p>\n    \n    <div style=\"background-color: #1A237E; border-left: 4px solid #1A237E; padding: 15px; margin-bottom: 20px;\">\n        <h5 style=\"color: #2c3e50; margin-top: 0; margin-bottom: 10px;\">\n            <i class=\"fas fa-receipt\" style=\"color: #FF6B35;\"></i> <strong style=\"color: #333;\">Identitas Pesanan</strong>\n        </h5>\n        <p style=\"margin: 5px 0;\"><strong>No Booking :</strong> 1758292110</p>\n        <p style=\"margin: 5px 0;\"><strong>Nama :</strong> user1</p>\n        <p style=\"margin: 5px 0;\"><strong>Mobil :</strong> lenovo</p>\n    </div>\n    \n    <a href=\"http://localhost/rental_mobil-master/bayar.php?id=1758292110\" style=\"display: inline-block; padding: 10px 20px; background-color:  #FF6B35; color: #ffffff; text-decoration: none; border-radius: 5px; font-weight: bold;\">\n        <i class=\"fas fa-eye\"></i> &nbsp; Lihat Detail Pesanan\n    </a>\n</div>', 1, '2025-09-19 14:38:13'),
(162, 9, 0, '<div style=\"font-family: Arial, sans-serif; line-height: 1.6;\">\n    <h4 style=\"color: #2c3e50; margin-bottom: 10px;\">\n        <i class=\"fas fa-check-circle\" style=\"color: #2ecc71;\"></i> &nbsp; <strong>Pembayaran Diterima</strong>\n    </h4>\n    <p style=\"margin-bottom: 20px;\">Yeay! Pembayaranmu sudah kami terima 🎉. Pesananmu akan segera kami siapkan. Yuk, segera datang ke toko kami untuk mengambil mobil impianmu 🚗💨.</p>\n    \n    <div style=\"background-color: #f8f9fa; border-left: 4px solid #3498db; padding: 15px; margin-bottom: 20px;\">\n        <h5 style=\"color: #2c3e50; margin-top: 0; margin-bottom: 10px;\">\n            <i class=\"fas fa-receipt\" style=\"color: #FF6B35;\"></i> <strong style=\"color: #333;\">Identitas Pesanan</strong>\n        </h5>\n        <p style=\"margin: 5px 0;\"><strong>No Booking :</strong> 1758292110</p>\n        <p style=\"margin: 5px 0;\"><strong>Nama :</strong> user1</p>\n        <p style=\"margin: 5px 0;\"><strong>Mobil :</strong> lenovo</p>\n    </div>\n    \n    <a href=\"http://localhost/rental_mobil-master/bayar.php?id=1758292110\" style=\"display: inline-block; padding: 10px 20px; background-color:  #FF6B35; color: #ffffff; text-decoration: none; border-radius: 5px; font-weight: bold;\">\n        <i class=\"fas fa-eye\"></i> &nbsp; Lihat Detail Pesanan\n    </a>\n</div>', 1, '2025-09-19 14:38:34'),
(163, 4, 0, '<div style=\"font-family: Arial, sans-serif; line-height: 1.6;\">\n    <h4 style=\"color: #2c3e50; margin-bottom: 10px;\">\n        <i class=\"fas fa-cogs\" style=\"color: #f39c12;\"></i> &nbsp; <strong>Sedang Diproses</strong>\n    </h4>\n    <p style=\"margin-bottom: 20px;\">Pesananmu sedang kami proses ✅ Tim kami sedang menyiapkan mobil impianmu. Tunggu sebentar ya, kami akan segera menghubungi kamu untuk langkah berikutnya.</p>\n    \n    <div style=\"background-color: #f8f9fa; border-left: 4px solid #3498db; padding: 15px; margin-bottom: 20px;\">\n        <h5 style=\"color: #2c3e50; margin-top: 0; margin-bottom: 10px;\">\n            <i class=\"fas fa-receipt\" style=\"color: #FF6B35;\"></i> <strong style=\"color: #333;\">Identitas Pesanan</strong>\n        </h5>\n        <p style=\"margin: 5px 0;\"><strong>No Booking :</strong> 1758199992</p>\n        <p style=\"margin: 5px 0;\"><strong>Nama :</strong> rillapan</p>\n        <p style=\"margin: 5px 0;\"><strong>Mobil :</strong> New Xenia</p>\n    </div>\n    \n    <a href=\"http://localhost/rental_mobil-master/bayar.php?id=1758199992\" style=\"display: inline-block; padding: 10px 20px; background-color:  #FF6B35; color: #ffffff; text-decoration: none; border-radius: 5px; font-weight: bold;\">\n        <i class=\"fas fa-eye\"></i> &nbsp; Lihat Detail Pesanan\n    </a>\n</div>', 1, '2025-09-20 04:14:53'),
(164, 10, 0, '<div style=\"font-family: Arial, sans-serif; line-height: 1.6;\">\n    <h4 style=\"color: #2c3e50; margin-bottom: 10px;\">\n        <i class=\"fas fa-check-circle\" style=\"color: #2ecc71;\"></i> &nbsp; <strong>Pembayaran Diterima</strong>\n    </h4>\n    <p style=\"margin-bottom: 20px;\">Yeay! Pembayaranmu sudah kami terima 🎉. Pesananmu akan segera kami siapkan. Yuk, segera datang ke toko kami untuk mengambil mobil impianmu 🚗💨.</p>\n    \n    <div style=\"background-color: #f8f9fa; border-left: 4px solid #3498db; padding: 15px; margin-bottom: 20px;\">\n        <h5 style=\"color: #2c3e50; margin-top: 0; margin-bottom: 10px;\">\n            <i class=\"fas fa-receipt\" style=\"color: #FF6B35;\"></i> <strong style=\"color: #333;\">Identitas Pesanan</strong>\n        </h5>\n        <p style=\"margin: 5px 0;\"><strong>No Booking :</strong> 1758422916</p>\n        <p style=\"margin: 5px 0;\"><strong>Nama :</strong> affan</p>\n        <p style=\"margin: 5px 0;\"><strong>Mobil :</strong> Honda CRV </p>\n    </div>\n    \n    <a href=\"http://localhost/rental_mobil-master/bayar.php?id=1758422916\" style=\"display: inline-block; padding: 10px 20px; background-color:  #FF6B35; color: #ffffff; text-decoration: none; border-radius: 5px; font-weight: bold;\">\n        <i class=\"fas fa-eye\"></i> &nbsp; Lihat Detail Pesanan\n    </a>\n</div>', 1, '2025-09-21 02:50:03'),
(165, 10, 0, '<div style=\"font-family: Arial, sans-serif; line-height: 1.6;\">\n    <h4 style=\"color: #2c3e50; margin-bottom: 10px;\">\n        <i class=\"fas fa-car\" style=\"color: #3498db;\"></i> &nbsp; <strong>Selesai</strong>\n    </h4>\n    <p style=\"margin-bottom: 20px;\">Selamat 🎉 Pembayaranmu lunas! Sekarang, mobil impianmu sudah resmi di tanganmu. Terima kasih sudah memilih layanan kami. Selamat jalan-jalan! 🚘✨.</p>\n    \n    <div style=\"background-color: #f8f9fa; border-left: 4px solid #3498db; padding: 15px; margin-bottom: 20px;\">\n        <h5 style=\"color: #2c3e50; margin-top: 0; margin-bottom: 10px;\">\n            <i class=\"fas fa-receipt\" style=\"color: #FF6B35;\"></i> <strong style=\"color: #333;\">Identitas Pesanan</strong>\n        </h5>\n        <p style=\"margin: 5px 0;\"><strong>No Booking :</strong> 1758422916</p>\n        <p style=\"margin: 5px 0;\"><strong>Nama :</strong> affan</p>\n        <p style=\"margin: 5px 0;\"><strong>Mobil :</strong> Honda CRV </p>\n    </div>\n    \n    <a href=\"http://localhost/rental_mobil-master/bayar.php?id=1758422916\" style=\"display: inline-block; padding: 10px 20px; background-color:  #FF6B35; color: #ffffff; text-decoration: none; border-radius: 5px; font-weight: bold;\">\n        <i class=\"fas fa-eye\"></i> &nbsp; Lihat Detail Pesanan\n    </a>\n</div>', 1, '2025-09-21 03:03:40'),
(166, 10, 0, '<div style=\"font-family: Arial, sans-serif; line-height: 1.6;\">\n    <h4 style=\"color: #2c3e50; margin-bottom: 10px;\">\n        <i class=\"fas fa-cogs\" style=\"color: #f39c12;\"></i> &nbsp; <strong>Sedang Diproses</strong>\n    </h4>\n    <p style=\"margin-bottom: 20px;\">Pesananmu sedang kami proses ✅ Tim kami sedang menyiapkan mobil impianmu. Tunggu sebentar ya, kami akan segera menghubungi kamu untuk langkah berikutnya.</p>\n    \n    <div style=\"background-color: #f8f9fa; border-left: 4px solid #3498db; padding: 15px; margin-bottom: 20px;\">\n        <h5 style=\"color: #2c3e50; margin-top: 0; margin-bottom: 10px;\">\n            <i class=\"fas fa-receipt\" style=\"color: #FF6B35;\"></i> <strong style=\"color: #333;\">Identitas Pesanan</strong>\n        </h5>\n        <p style=\"margin: 5px 0;\"><strong>No Booking :</strong> 1758422916</p>\n        <p style=\"margin: 5px 0;\"><strong>Nama :</strong> affan</p>\n        <p style=\"margin: 5px 0;\"><strong>Mobil :</strong> Honda CRV </p>\n    </div>\n    \n    <a href=\"http://localhost/rental_mobil-master/bayar.php?id=1758422916\" style=\"display: inline-block; padding: 10px 20px; background-color:  #FF6B35; color: #ffffff; text-decoration: none; border-radius: 5px; font-weight: bold;\">\n        <i class=\"fas fa-eye\"></i> &nbsp; Lihat Detail Pesanan\n    </a>\n</div>', 1, '2025-09-21 03:30:58'),
(167, 10, 0, '<div style=\"font-family: Arial, sans-serif; line-height: 1.6;\">\n    <h4 style=\"color: #2c3e50; margin-bottom: 10px;\">\n        <i class=\"fas fa-check-circle\" style=\"color: #2ecc71;\"></i> &nbsp; <strong>Pembayaran Diterima</strong>\n    </h4>\n    <p style=\"margin-bottom: 20px;\">Yeay! Pembayaranmu sudah kami terima 🎉. Pesananmu akan segera kami siapkan. Yuk, segera datang ke toko kami untuk mengambil mobil impianmu 🚗💨.</p>\n    \n    <div style=\"background-color: #f8f9fa; border-left: 4px solid #3498db; padding: 15px; margin-bottom: 20px;\">\n        <h5 style=\"color: #2c3e50; margin-top: 0; margin-bottom: 10px;\">\n            <i class=\"fas fa-receipt\" style=\"color: #FF6B35;\"></i> <strong style=\"color: #333;\">Identitas Pesanan</strong>\n        </h5>\n        <p style=\"margin: 5px 0;\"><strong>No Booking :</strong> 1758422916</p>\n        <p style=\"margin: 5px 0;\"><strong>Nama :</strong> affan</p>\n        <p style=\"margin: 5px 0;\"><strong>Mobil :</strong> Honda CRV </p>\n    </div>\n    \n    <a href=\"http://localhost/rental_mobil-master/bayar.php?id=1758422916\" style=\"display: inline-block; padding: 10px 20px; background-color:  #FF6B35; color: #ffffff; text-decoration: none; border-radius: 5px; font-weight: bold;\">\n        <i class=\"fas fa-eye\"></i> &nbsp; Lihat Detail Pesanan\n    </a>\n</div>', 1, '2025-09-21 03:31:47'),
(168, 10, 0, '<div style=\"font-family: Arial, sans-serif; line-height: 1.6;\">\n    <h4 style=\"color: #2c3e50; margin-bottom: 10px;\">\n        <i class=\"fas fa-cogs\" style=\"color: #f39c12;\"></i> &nbsp; <strong>Sedang Diproses</strong>\n    </h4>\n    <p style=\"margin-bottom: 20px;\">Pesananmu sedang kami proses ✅ Tim kami sedang menyiapkan mobil impianmu. Tunggu sebentar ya, kami akan segera menghubungi kamu untuk langkah berikutnya.</p>\n    \n    <div style=\"background-color: #f8f9fa; border-left: 4px solid #3498db; padding: 15px; margin-bottom: 20px;\">\n        <h5 style=\"color: #2c3e50; margin-top: 0; margin-bottom: 10px;\">\n            <i class=\"fas fa-receipt\" style=\"color: #FF6B35;\"></i> <strong style=\"color: #333;\">Identitas Pesanan</strong>\n        </h5>\n        <p style=\"margin: 5px 0;\"><strong>No Booking :</strong> 1758422916</p>\n        <p style=\"margin: 5px 0;\"><strong>Nama :</strong> affan</p>\n        <p style=\"margin: 5px 0;\"><strong>Mobil :</strong> Honda CRV </p>\n    </div>\n    \n    <a href=\"http://localhost/rental_mobil-master/bayar.php?id=1758422916\" style=\"display: inline-block; padding: 10px 20px; background-color:  #FF6B35; color: #ffffff; text-decoration: none; border-radius: 5px; font-weight: bold;\">\n        <i class=\"fas fa-eye\"></i> &nbsp; Lihat Detail Pesanan\n    </a>\n</div>', 1, '2025-09-21 03:32:36'),
(169, 10, 0, '<div style=\"font-family: Arial, sans-serif; line-height: 1.6;\">\n    <h4 style=\"color: #2c3e50; margin-bottom: 10px;\">\n        <i class=\"fas fa-check-circle\" style=\"color: #2ecc71;\"></i> &nbsp; <strong>Pembayaran Diterima</strong>\n    </h4>\n    <p style=\"margin-bottom: 20px;\">Yeay! Pembayaranmu sudah kami terima 🎉. Pesananmu akan segera kami siapkan. Yuk, segera datang ke toko kami untuk mengambil mobil impianmu 🚗💨.</p>\n    \n    <div style=\"background-color: #f8f9fa; border-left: 4px solid #3498db; padding: 15px; margin-bottom: 20px;\">\n        <h5 style=\"color: #2c3e50; margin-top: 0; margin-bottom: 10px;\">\n            <i class=\"fas fa-receipt\" style=\"color: #FF6B35;\"></i> <strong style=\"color: #333;\">Identitas Pesanan</strong>\n        </h5>\n        <p style=\"margin: 5px 0;\"><strong>No Booking :</strong> 1758422916</p>\n        <p style=\"margin: 5px 0;\"><strong>Nama :</strong> affan</p>\n        <p style=\"margin: 5px 0;\"><strong>Mobil :</strong> Honda CRV </p>\n    </div>\n    \n    <a href=\"http://localhost/rental_mobil-master/bayar.php?id=1758422916\" style=\"display: inline-block; padding: 10px 20px; background-color:  #FF6B35; color: #ffffff; text-decoration: none; border-radius: 5px; font-weight: bold;\">\n        <i class=\"fas fa-eye\"></i> &nbsp; Lihat Detail Pesanan\n    </a>\n</div>', 1, '2025-09-21 03:33:59'),
(170, 10, 0, '<div style=\"font-family: Arial, sans-serif; line-height: 1.6;\">\n    <h4 style=\"color: #2c3e50; margin-bottom: 10px;\">\n        <i class=\"fas fa-car\" style=\"color: #3498db;\"></i> &nbsp; <strong>Selesai</strong>\n    </h4>\n    <p style=\"margin-bottom: 20px;\">Selamat 🎉 Pembayaranmu lunas! Sekarang, mobil impianmu sudah resmi di tanganmu. Terima kasih sudah memilih layanan kami. Selamat jalan-jalan! 🚘✨.</p>\n    \n    <div style=\"background-color: #f8f9fa; border-left: 4px solid #3498db; padding: 15px; margin-bottom: 20px;\">\n        <h5 style=\"color: #2c3e50; margin-top: 0; margin-bottom: 10px;\">\n            <i class=\"fas fa-receipt\" style=\"color: #FF6B35;\"></i> <strong style=\"color: #333;\">Identitas Pesanan</strong>\n        </h5>\n        <p style=\"margin: 5px 0;\"><strong>No Booking :</strong> 1758422916</p>\n        <p style=\"margin: 5px 0;\"><strong>Nama :</strong> affan</p>\n        <p style=\"margin: 5px 0;\"><strong>Mobil :</strong> Honda CRV </p>\n    </div>\n    \n    <a href=\"http://localhost/rental_mobil-master/bayar.php?id=1758422916\" style=\"display: inline-block; padding: 10px 20px; background-color:  #FF6B35; color: #ffffff; text-decoration: none; border-radius: 5px; font-weight: bold;\">\n        <i class=\"fas fa-eye\"></i> &nbsp; Lihat Detail Pesanan\n    </a>\n</div>', 1, '2025-09-21 03:34:08'),
(171, 4, 0, '<div style=\"font-family: Arial, sans-serif; line-height: 1.6;\">\n    <h4 style=\"color: #2c3e50; margin-bottom: 10px;\">\n        <i class=\"fas fa-check-circle\" style=\"color: #2ecc71;\"></i> &nbsp; <strong>Pembayaran Diterima</strong>\n    </h4>\n    <p style=\"margin-bottom: 20px;\">Yeay! Pembayaranmu sudah kami terima 🎉. Pesananmu akan segera kami siapkan. Yuk, segera datang ke toko kami untuk mengambil mobil impianmu 🚗💨.</p>\n    \n    <div style=\"background-color: #f8f9fa; border-left: 4px solid #3498db; padding: 15px; margin-bottom: 20px;\">\n        <h5 style=\"color: #2c3e50; margin-top: 0; margin-bottom: 10px;\">\n            <i class=\"fas fa-receipt\" style=\"color: #FF6B35;\"></i> <strong style=\"color: #333;\">Identitas Pesanan</strong>\n        </h5>\n        <p style=\"margin: 5px 0;\"><strong>No Booking :</strong> 1758527272</p>\n        <p style=\"margin: 5px 0;\"><strong>Nama :</strong> affan</p>\n        <p style=\"margin: 5px 0;\"><strong>Mobil :</strong> Toyota Fortuner </p>\n    </div>\n    \n    <a href=\"http://localhost/rental_mobil-master/bayar.php?id=1758527272\" style=\"display: inline-block; padding: 10px 20px; background-color:  #FF6B35; color: #ffffff; text-decoration: none; border-radius: 5px; font-weight: bold;\">\n        <i class=\"fas fa-eye\"></i> &nbsp; Lihat Detail Pesanan\n    </a>\n</div>', 1, '2025-09-22 07:48:35'),
(172, 5, 0, '<div style=\"font-family: Arial, sans-serif; line-height: 1.6;\">\n    <h4 style=\"color: #2c3e50; margin-bottom: 10px;\">\n        <i class=\"fas fa-check-circle\" style=\"color: #2ecc71;\"></i> &nbsp; <strong>Pembayaran Diterima</strong>\n    </h4>\n    <p style=\"margin-bottom: 20px;\">Yeay! Pembayaranmu sudah kami terima 🎉. Pesananmu akan segera kami siapkan. Yuk, segera datang ke toko kami untuk mengambil mobil impianmu 🚗💨.</p>\n    \n    <div style=\"background-color: #f8f9fa; border-left: 4px solid #3498db; padding: 15px; margin-bottom: 20px;\">\n        <h5 style=\"color: #2c3e50; margin-top: 0; margin-bottom: 10px;\">\n            <i class=\"fas fa-receipt\" style=\"color: #FF6B35;\"></i> <strong style=\"color: #333;\">Identitas Pesanan</strong>\n        </h5>\n        <p style=\"margin: 5px 0;\"><strong>No Booking :</strong> 1758697236</p>\n        <p style=\"margin: 5px 0;\"><strong>Nama :</strong> user1</p>\n        <p style=\"margin: 5px 0;\"><strong>Mobil :</strong> Honda CRV </p>\n    </div>\n    \n    <a href=\"http://localhost/rental_mobil-master/bayar.php?id=1758697236\" style=\"display: inline-block; padding: 10px 20px; background-color:  #FF6B35; color: #ffffff; text-decoration: none; border-radius: 5px; font-weight: bold;\">\n        <i class=\"fas fa-eye\"></i> &nbsp; Lihat Detail Pesanan\n    </a>\n</div>', 1, '2025-09-24 07:01:52'),
(173, 5, 0, '<div style=\"font-family: Arial, sans-serif; line-height: 1.6;\">\n    <h4 style=\"color: #2c3e50; margin-bottom: 10px;\">\n        <i class=\"fas fa-car\" style=\"color: #3498db;\"></i> &nbsp; <strong>Selesai</strong>\n    </h4>\n    <p style=\"margin-bottom: 20px;\">Selamat 🎉 Pembayaranmu lunas! Sekarang, mobil impianmu sudah resmi di tanganmu. Terima kasih sudah memilih layanan kami. Selamat jalan-jalan! 🚘✨.</p>\n    \n    <div style=\"background-color: #f8f9fa; border-left: 4px solid #3498db; padding: 15px; margin-bottom: 20px;\">\n        <h5 style=\"color: #2c3e50; margin-top: 0; margin-bottom: 10px;\">\n            <i class=\"fas fa-receipt\" style=\"color: #FF6B35;\"></i> <strong style=\"color: #333;\">Identitas Pesanan</strong>\n        </h5>\n        <p style=\"margin: 5px 0;\"><strong>No Booking :</strong> 1758697236</p>\n        <p style=\"margin: 5px 0;\"><strong>Nama :</strong> user1</p>\n        <p style=\"margin: 5px 0;\"><strong>Mobil :</strong> Honda CRV </p>\n    </div>\n    \n    <a href=\"http://localhost/rental_mobil-master/bayar.php?id=1758697236\" style=\"display: inline-block; padding: 10px 20px; background-color:  #FF6B35; color: #ffffff; text-decoration: none; border-radius: 5px; font-weight: bold;\">\n        <i class=\"fas fa-eye\"></i> &nbsp; Lihat Detail Pesanan\n    </a>\n</div>', 1, '2025-09-24 07:18:48'),
(174, 5, 0, '<div style=\"font-family: Arial, sans-serif; line-height: 1.6;\">\n    <h4 style=\"color: #2c3e50; margin-bottom: 10px;\">\n        <i class=\"fas fa-car\" style=\"color: #3498db;\"></i> &nbsp; <strong>Selesai</strong>\n    </h4>\n    <p style=\"margin-bottom: 20px;\">Selamat 🎉 Pembayaranmu lunas! Sekarang, mobil impianmu sudah resmi di tanganmu. Terima kasih sudah memilih layanan kami. Selamat jalan-jalan! 🚘✨.</p>\n    \n    <div style=\"background-color: #f8f9fa; border-left: 4px solid #3498db; padding: 15px; margin-bottom: 20px;\">\n        <h5 style=\"color: #2c3e50; margin-top: 0; margin-bottom: 10px;\">\n            <i class=\"fas fa-receipt\" style=\"color: #FF6B35;\"></i> <strong style=\"color: #333;\">Identitas Pesanan</strong>\n        </h5>\n        <p style=\"margin: 5px 0;\"><strong>No Booking :</strong> 1758697236</p>\n        <p style=\"margin: 5px 0;\"><strong>Nama :</strong> user1</p>\n        <p style=\"margin: 5px 0;\"><strong>Mobil :</strong> Honda CRV </p>\n    </div>\n    \n    <a href=\"http://localhost/rental_mobil-master/bayar.php?id=1758697236\" style=\"display: inline-block; padding: 10px 20px; background-color:  #FF6B35; color: #ffffff; text-decoration: none; border-radius: 5px; font-weight: bold;\">\n        <i class=\"fas fa-eye\"></i> &nbsp; Lihat Detail Pesanan\n    </a>\n</div>', 1, '2025-09-24 07:19:18'),
(175, 9, 0, '<div style=\"font-family: Arial, sans-serif; line-height: 1.6;\">\n    <h4 style=\"color: #2c3e50; margin-bottom: 10px;\">\n        <i class=\"fas fa-car\" style=\"color: #3498db;\"></i> &nbsp; <strong>Selesai</strong>\n    </h4>\n    <p style=\"margin-bottom: 20px;\">Selamat 🎉 Pembayaranmu lunas! Sekarang, mobil impianmu sudah resmi di tanganmu. Terima kasih sudah memilih layanan kami. Selamat jalan-jalan! 🚘✨.</p>\n    \n    <div style=\"background-color: #f8f9fa; border-left: 4px solid #3498db; padding: 15px; margin-bottom: 20px;\">\n        <h5 style=\"color: #2c3e50; margin-top: 0; margin-bottom: 10px;\">\n            <i class=\"fas fa-receipt\" style=\"color: #FF6B35;\"></i> <strong style=\"color: #333;\">Identitas Pesanan</strong>\n        </h5>\n        <p style=\"margin: 5px 0;\"><strong>No Booking :</strong> 1758292110</p>\n        <p style=\"margin: 5px 0;\"><strong>Nama :</strong> user1</p>\n        <p style=\"margin: 5px 0;\"><strong>Mobil :</strong> BMW Seri 3 </p>\n    </div>\n    \n    <a href=\"http://localhost/rental_mobil-master/bayar.php?id=1758292110\" style=\"display: inline-block; padding: 10px 20px; background-color:  #FF6B35; color: #ffffff; text-decoration: none; border-radius: 5px; font-weight: bold;\">\n        <i class=\"fas fa-eye\"></i> &nbsp; Lihat Detail Pesanan\n    </a>\n</div>', 1, '2025-09-24 07:20:01'),
(176, 5, 0, '<div style=\"font-family: Arial, sans-serif; line-height: 1.6;\">\n    <h4 style=\"color: #2c3e50; margin-bottom: 10px;\">\n        <i class=\"fas fa-check-circle\" style=\"color: #2ecc71;\"></i> &nbsp; <strong>Pembayaran Diterima</strong>\n    </h4>\n    <p style=\"margin-bottom: 20px;\">Yeay! Pembayaranmu sudah kami terima 🎉. Pesananmu akan segera kami siapkan. Yuk, segera datang ke toko kami untuk mengambil mobil impianmu 🚗💨.</p>\n    \n    <div style=\"background-color: #f8f9fa; border-left: 4px solid #3498db; padding: 15px; margin-bottom: 20px;\">\n        <h5 style=\"color: #2c3e50; margin-top: 0; margin-bottom: 10px;\">\n            <i class=\"fas fa-receipt\" style=\"color: #FF6B35;\"></i> <strong style=\"color: #333;\">Identitas Pesanan</strong>\n        </h5>\n        <p style=\"margin: 5px 0;\"><strong>No Booking :</strong> 1759460740</p>\n        <p style=\"margin: 5px 0;\"><strong>Nama :</strong> user1</p>\n        <p style=\"margin: 5px 0;\"><strong>Mobil :</strong> Honda CRV </p>\n    </div>\n    \n    <a href=\"http://localhost/rental_mobil-master/bayar.php?id=1759460740\" style=\"display: inline-block; padding: 10px 20px; background-color:  #FF6B35; color: #ffffff; text-decoration: none; border-radius: 5px; font-weight: bold;\">\n        <i class=\"fas fa-eye\"></i> &nbsp; Lihat Detail Pesanan\n    </a>\n</div>', 1, '2025-10-03 03:06:07'),
(177, 5, 0, '<div style=\"font-family: Arial, sans-serif; line-height: 1.6;\">\n    <h4 style=\"color: #2c3e50; margin-bottom: 10px;\">\n        <i class=\"fas fa-car\" style=\"color: #3498db;\"></i> &nbsp; <strong>Selesai</strong>\n    </h4>\n    <p style=\"margin-bottom: 20px;\">Selamat 🎉 Pembayaranmu lunas! Sekarang, mobil impianmu sudah resmi di tanganmu. Terima kasih sudah memilih layanan kami. Selamat jalan-jalan! 🚘✨.</p>\n    \n    <div style=\"background-color: #f8f9fa; border-left: 4px solid #3498db; padding: 15px; margin-bottom: 20px;\">\n        <h5 style=\"color: #2c3e50; margin-top: 0; margin-bottom: 10px;\">\n            <i class=\"fas fa-receipt\" style=\"color: #FF6B35;\"></i> <strong style=\"color: #333;\">Identitas Pesanan</strong>\n        </h5>\n        <p style=\"margin: 5px 0;\"><strong>No Booking :</strong> 1759460740</p>\n        <p style=\"margin: 5px 0;\"><strong>Nama :</strong> user1</p>\n        <p style=\"margin: 5px 0;\"><strong>Mobil :</strong> Honda CRV </p>\n    </div>\n    \n    <a href=\"http://localhost/rental_mobil-master/bayar.php?id=1759460740\" style=\"display: inline-block; padding: 10px 20px; background-color:  #FF6B35; color: #ffffff; text-decoration: none; border-radius: 5px; font-weight: bold;\">\n        <i class=\"fas fa-eye\"></i> &nbsp; Lihat Detail Pesanan\n    </a>\n</div>', 1, '2025-10-03 03:06:42'),
(178, 5, 0, '<div style=\"font-family: Arial, sans-serif; line-height: 1.6;\">\n    <h4 style=\"color: #2c3e50; margin-bottom: 10px;\">\n        <i class=\"fas fa-check-circle\" style=\"color: #2ecc71;\"></i> &nbsp; <strong>Pembayaran Diterima</strong>\n    </h4>\n    <p style=\"margin-bottom: 20px;\">Yeay! Pembayaranmu sudah kami terima 🎉. Pesananmu akan segera kami siapkan. Yuk, segera datang ke toko kami untuk mengambil mobil impianmu 🚗💨.</p>\n    \n    <div style=\"background-color: #f8f9fa; border-left: 4px solid #3498db; padding: 15px; margin-bottom: 20px;\">\n        <h5 style=\"color: #2c3e50; margin-top: 0; margin-bottom: 10px;\">\n            <i class=\"fas fa-receipt\" style=\"color: #FF6B35;\"></i> <strong style=\"color: #333;\">Identitas Pesanan</strong>\n        </h5>\n        <p style=\"margin: 5px 0;\"><strong>No Booking :</strong> 1760065871</p>\n        <p style=\"margin: 5px 0;\"><strong>Nama :</strong> SEPATU</p>\n        <p style=\"margin: 5px 0;\"><strong>Mobil :</strong> Honda CRV </p>\n    </div>\n    \n    <a href=\"http://localhost/rental_mobil-master/bayar.php?id=1760065871\" style=\"display: inline-block; padding: 10px 20px; background-color:  #FF6B35; color: #ffffff; text-decoration: none; border-radius: 5px; font-weight: bold;\">\n        <i class=\"fas fa-eye\"></i> &nbsp; Lihat Detail Pesanan\n    </a>\n</div>', 1, '2025-10-10 03:11:50'),
(179, 5, 0, '<div style=\"font-family: Arial, sans-serif; line-height: 1.6;\">\n    <h4 style=\"color: #2c3e50; margin-bottom: 10px;\">\n        <i class=\"fas fa-car\" style=\"color: #3498db;\"></i> &nbsp; <strong>Selesai</strong>\n    </h4>\n    <p style=\"margin-bottom: 20px;\">Selamat 🎉 Pembayaranmu lunas! Sekarang, mobil impianmu sudah resmi di tanganmu. Terima kasih sudah memilih layanan kami. Selamat jalan-jalan! 🚘✨.</p>\n    \n    <div style=\"background-color: #f8f9fa; border-left: 4px solid #3498db; padding: 15px; margin-bottom: 20px;\">\n        <h5 style=\"color: #2c3e50; margin-top: 0; margin-bottom: 10px;\">\n            <i class=\"fas fa-receipt\" style=\"color: #FF6B35;\"></i> <strong style=\"color: #333;\">Identitas Pesanan</strong>\n        </h5>\n        <p style=\"margin: 5px 0;\"><strong>No Booking :</strong> 1760065871</p>\n        <p style=\"margin: 5px 0;\"><strong>Nama :</strong> SEPATU</p>\n        <p style=\"margin: 5px 0;\"><strong>Mobil :</strong> Honda CRV </p>\n    </div>\n    \n    <a href=\"http://localhost/rental_mobil-master/bayar.php?id=1760065871\" style=\"display: inline-block; padding: 10px 20px; background-color:  #FF6B35; color: #ffffff; text-decoration: none; border-radius: 5px; font-weight: bold;\">\n        <i class=\"fas fa-eye\"></i> &nbsp; Lihat Detail Pesanan\n    </a>\n</div>', 1, '2025-10-10 03:12:20'),
(180, 5, 0, '<div style=\"font-family: Arial, sans-serif; line-height: 1.6;\">\n    <h4 style=\"color: #2c3e50; margin-bottom: 10px;\">\n        <i class=\"fas fa-check-circle\" style=\"color: #2ecc71;\"></i> &nbsp; <strong>Pembayaran Diterima</strong>\n    </h4>\n    <p style=\"margin-bottom: 20px;\">Yeay! Pembayaranmu sudah kami terima 🎉. Pesananmu akan segera kami siapkan. Yuk, segera datang ke toko kami untuk mengambil mobil impianmu 🚗💨.</p>\n    \n    <div style=\"background-color: #f8f9fa; border-left: 4px solid #3498db; padding: 15px; margin-bottom: 20px;\">\n        <h5 style=\"color: #2c3e50; margin-top: 0; margin-bottom: 10px;\">\n            <i class=\"fas fa-receipt\" style=\"color: #FF6B35;\"></i> <strong style=\"color: #333;\">Identitas Pesanan</strong>\n        </h5>\n        <p style=\"margin: 5px 0;\"><strong>No Booking :</strong> 1760670319</p>\n        <p style=\"margin: 5px 0;\"><strong>Nama :</strong> SEPATU</p>\n        <p style=\"margin: 5px 0;\"><strong>Mobil :</strong> Toyota Fortuner </p>\n    </div>\n    \n    <a href=\"http://localhost/rental_mobil-master/bayar.php?id=1760670319\" style=\"display: inline-block; padding: 10px 20px; background-color:  #FF6B35; color: #ffffff; text-decoration: none; border-radius: 5px; font-weight: bold;\">\n        <i class=\"fas fa-eye\"></i> &nbsp; Lihat Detail Pesanan\n    </a>\n</div>', 1, '2025-10-17 03:07:25'),
(181, 5, 0, '<div style=\"font-family: Arial, sans-serif; line-height: 1.6;\">\n    <h4 style=\"color: #2c3e50; margin-bottom: 10px;\">\n        <i class=\"fas fa-check-circle\" style=\"color: #2ecc71;\"></i> &nbsp; <strong>Pembayaran Diterima</strong>\n    </h4>\n    <p style=\"margin-bottom: 20px;\">Yeay! Pembayaranmu sudah kami terima 🎉. Pesananmu akan segera kami siapkan. Yuk, segera datang ke toko kami untuk mengambil mobil impianmu 🚗💨.</p>\n    \n    <div style=\"background-color: #f8f9fa; border-left: 4px solid #3498db; padding: 15px; margin-bottom: 20px;\">\n        <h5 style=\"color: #2c3e50; margin-top: 0; margin-bottom: 10px;\">\n            <i class=\"fas fa-receipt\" style=\"color: #FF6B35;\"></i> <strong style=\"color: #333;\">Identitas Pesanan</strong>\n        </h5>\n        <p style=\"margin: 5px 0;\"><strong>No Booking :</strong> 1761285631</p>\n        <p style=\"margin: 5px 0;\"><strong>Nama :</strong> affan</p>\n        <p style=\"margin: 5px 0;\"><strong>Mobil :</strong> lenovo</p>\n    </div>\n    \n    <a href=\"http://localhost/rental_mobil-master/bayar.php?id=1761285631\" style=\"display: inline-block; padding: 10px 20px; background-color:  #FF6B35; color: #ffffff; text-decoration: none; border-radius: 5px; font-weight: bold;\">\n        <i class=\"fas fa-eye\"></i> &nbsp; Lihat Detail Pesanan\n    </a>\n</div>', 1, '2025-10-24 06:20:41'),
(182, 5, 0, '<div style=\"font-family: Arial, sans-serif; line-height: 1.6;\">\r\n    <h4 style=\"color: #2c3e50; margin-bottom: 10px;\">\r\n        <i class=\"fas fa-cogs\" style=\"color: #f39c12;\"></i> &nbsp; <strong>Sedang Diproses</strong>\r\n    </h4>\r\n    <p style=\"margin-bottom: 20px;\">Pesananmu sedang kami proses ✅ Tim kami sedang menyiapkan mobil impianmu. Tunggu sebentar ya, kami akan segera menghubungi kamu untuk langkah berikutnya.</p>\r\n    \r\n    <div style=\"background-color: #f8f9fa; border-left: 4px solid #3498db; padding: 15px; margin-bottom: 20px;\">\r\n        <h5 style=\"color: #2c3e50; margin-top: 0; margin-bottom: 10px;\">\r\n            <i class=\"fas fa-receipt\" style=\"color: #FF6B35;\"></i> <strong style=\"color: #333;\">Identitas Pesanan</strong>\r\n        </h5>\r\n        <p style=\"margin: 5px 0;\"><strong>No Booking :</strong> 1761280043</p>\r\n        <p style=\"margin: 5px 0;\"><strong>Nama :</strong> affan</p>\r\n        <p style=\"margin: 5px 0;\"><strong>Mobil :</strong> New Xenia</p>\r\n    </div>\r\n    \r\n    <a href=\"http://localhost/rental_mobil-master/bayar.php?id=1761280043\" style=\"display: inline-block; padding: 10px 20px; background-color:  #FF6B35; color: #ffffff; text-decoration: none; border-radius: 5px; font-weight: bold;\">\r\n        <i class=\"fas fa-eye\"></i> &nbsp; Lihat Detail Pesanan\r\n    </a>\r\n</div>', 1, '2025-11-05 00:39:19'),
(183, 5, 0, '<div style=\"font-family: Arial, sans-serif; line-height: 1.6;\">\r\n    <h4 style=\"color: #2c3e50; margin-bottom: 10px;\">\r\n        <i class=\"fas fa-check-circle\" style=\"color: #2ecc71;\"></i> &nbsp; <strong>Pembayaran Diterima</strong>\r\n    </h4>\r\n    <p style=\"margin-bottom: 20px;\">Yeay! Pembayaranmu sudah kami terima 🎉. Pesananmu akan segera kami siapkan. Yuk, segera datang ke toko kami untuk mengambil mobil impianmu 🚗💨.</p>\r\n    \r\n    <div style=\"background-color: #f8f9fa; border-left: 4px solid #3498db; padding: 15px; margin-bottom: 20px;\">\r\n        <h5 style=\"color: #2c3e50; margin-top: 0; margin-bottom: 10px;\">\r\n            <i class=\"fas fa-receipt\" style=\"color: #FF6B35;\"></i> <strong style=\"color: #333;\">Identitas Pesanan</strong>\r\n        </h5>\r\n        <p style=\"margin: 5px 0;\"><strong>No Booking :</strong> 1761280043</p>\r\n        <p style=\"margin: 5px 0;\"><strong>Nama :</strong> affan</p>\r\n        <p style=\"margin: 5px 0;\"><strong>Mobil :</strong> New Xenia</p>\r\n    </div>\r\n    \r\n    <a href=\"http://localhost/rental_mobil-master/bayar.php?id=1761280043\" style=\"display: inline-block; padding: 10px 20px; background-color:  #FF6B35; color: #ffffff; text-decoration: none; border-radius: 5px; font-weight: bold;\">\r\n        <i class=\"fas fa-eye\"></i> &nbsp; Lihat Detail Pesanan\r\n    </a>\r\n</div>', 1, '2025-11-05 00:44:34'),
(184, 5, 0, '<div style=\"font-family: Arial, sans-serif; line-height: 1.6;\">\r\n    <h4 style=\"color: #2c3e50; margin-bottom: 10px;\">\r\n        <i class=\"fas fa-check-circle\" style=\"color: #2ecc71;\"></i> &nbsp; <strong>Pembayaran Diterima</strong>\r\n    </h4>\r\n    <p style=\"margin-bottom: 20px;\">Yeay! Pembayaranmu sudah kami terima 🎉. Pesananmu akan segera kami siapkan. Yuk, segera datang ke toko kami untuk mengambil mobil impianmu 🚗💨.</p>\r\n    \r\n    <div style=\"background-color: #f8f9fa; border-left: 4px solid #3498db; padding: 15px; margin-bottom: 20px;\">\r\n        <h5 style=\"color: #2c3e50; margin-top: 0; margin-bottom: 10px;\">\r\n            <i class=\"fas fa-receipt\" style=\"color: #FF6B35;\"></i> <strong style=\"color: #333;\">Identitas Pesanan</strong>\r\n        </h5>\r\n        <p style=\"margin: 5px 0;\"><strong>No Booking :</strong> 1762329007</p>\r\n        <p style=\"margin: 5px 0;\"><strong>Nama :</strong> khairil affan</p>\r\n        <p style=\"margin: 5px 0;\"><strong>Mobil :</strong> lenovo</p>\r\n    </div>\r\n    \r\n    <a href=\"http://localhost/rental_mobil-master/bayar.php?id=1762329007\" style=\"display: inline-block; padding: 10px 20px; background-color:  #FF6B35; color: #ffffff; text-decoration: none; border-radius: 5px; font-weight: bold;\">\r\n        <i class=\"fas fa-eye\"></i> &nbsp; Lihat Detail Pesanan\r\n    </a>\r\n</div>', 1, '2025-11-05 08:02:41'),
(185, 5, 0, '<div style=\"font-family: Arial, sans-serif; line-height: 1.6;\">\r\n    <h4 style=\"color: #2c3e50; margin-bottom: 10px;\">\r\n        <i class=\"fas fa-car\" style=\"color: #3498db;\"></i> &nbsp; <strong>Selesai</strong>\r\n    </h4>\r\n    <p style=\"margin-bottom: 20px;\">Selamat 🎉 Pembayaranmu lunas! Sekarang, mobil impianmu sudah resmi di tanganmu. Terima kasih sudah memilih layanan kami. Selamat jalan-jalan! 🚘✨.</p>\r\n    \r\n    <div style=\"background-color: #f8f9fa; border-left: 4px solid #3498db; padding: 15px; margin-bottom: 20px;\">\r\n        <h5 style=\"color: #2c3e50; margin-top: 0; margin-bottom: 10px;\">\r\n            <i class=\"fas fa-receipt\" style=\"color: #FF6B35;\"></i> <strong style=\"color: #333;\">Identitas Pesanan</strong>\r\n        </h5>\r\n        <p style=\"margin: 5px 0;\"><strong>No Booking :</strong> 1762699970</p>\r\n        <p style=\"margin: 5px 0;\"><strong>Nama :</strong> SEPATU</p>\r\n        <p style=\"margin: 5px 0;\"><strong>Mobil :</strong> Honda CRV </p>\r\n    </div>\r\n    \r\n    <a href=\"http://localhost/rental_mobil-master/bayar.php?id=1762699970\" style=\"display: inline-block; padding: 10px 20px; background-color:  #FF6B35; color: #ffffff; text-decoration: none; border-radius: 5px; font-weight: bold;\">\r\n        <i class=\"fas fa-eye\"></i> &nbsp; Lihat Detail Pesanan\r\n    </a>\r\n</div>', 1, '2025-11-10 06:33:29');
INSERT INTO `notifikasi` (`id`, `id_login`, `id_booking`, `pesan`, `status_baca`, `created_at`) VALUES
(186, 5, 0, '<div style=\"font-family: Arial, sans-serif; line-height: 1.6;\">\r\n    <h4 style=\"color: #2c3e50; margin-bottom: 10px;\">\r\n        <i class=\"fas fa-car\" style=\"color: #3498db;\"></i> &nbsp; <strong>Selesai</strong>\r\n    </h4>\r\n    <p style=\"margin-bottom: 20px;\">Selamat 🎉 Pembayaranmu lunas! Sekarang, mobil impianmu sudah resmi di tanganmu. Terima kasih sudah memilih layanan kami. Selamat jalan-jalan! 🚘✨.</p>\r\n    \r\n    <div style=\"background-color: #f8f9fa; border-left: 4px solid #3498db; padding: 15px; margin-bottom: 20px;\">\r\n        <h5 style=\"color: #2c3e50; margin-top: 0; margin-bottom: 10px;\">\r\n            <i class=\"fas fa-receipt\" style=\"color: #FF6B35;\"></i> <strong style=\"color: #333;\">Identitas Pesanan</strong>\r\n        </h5>\r\n        <p style=\"margin: 5px 0;\"><strong>No Booking :</strong> 1762761540</p>\r\n        <p style=\"margin: 5px 0;\"><strong>Nama :</strong> affan</p>\r\n        <p style=\"margin: 5px 0;\"><strong>Mobil :</strong> Toyota Fortuner </p>\r\n    </div>\r\n    \r\n    <a href=\"http://localhost/rental_mobil-master/bayar.php?id=1762761540\" style=\"display: inline-block; padding: 10px 20px; background-color:  #FF6B35; color: #ffffff; text-decoration: none; border-radius: 5px; font-weight: bold;\">\r\n        <i class=\"fas fa-eye\"></i> &nbsp; Lihat Detail Pesanan\r\n    </a>\r\n</div>', 1, '2025-11-10 11:40:24'),
(187, 5, 0, '<div style=\"font-family: Arial, sans-serif; line-height: 1.6;\">\r\n    <h4 style=\"color: #2c3e50; margin-bottom: 10px;\">\r\n        <i class=\"fas fa-check-circle\" style=\"color: #2ecc71;\"></i> &nbsp; <strong>Pembayaran Diterima</strong>\r\n    </h4>\r\n    <p style=\"margin-bottom: 20px;\">Yeay! Pembayaranmu sudah kami terima 🎉. Pesananmu akan segera kami siapkan. Yuk, segera datang ke toko kami untuk mengambil mobil impianmu 🚗💨.</p>\r\n    \r\n    <div style=\"background-color: #f8f9fa; border-left: 4px solid #3498db; padding: 15px; margin-bottom: 20px;\">\r\n        <h5 style=\"color: #2c3e50; margin-top: 0; margin-bottom: 10px;\">\r\n            <i class=\"fas fa-receipt\" style=\"color: #FF6B35;\"></i> <strong style=\"color: #333;\">Identitas Pesanan</strong>\r\n        </h5>\r\n        <p style=\"margin: 5px 0;\"><strong>No Booking :</strong> 1762779855</p>\r\n        <p style=\"margin: 5px 0;\"><strong>Nama :</strong> affan</p>\r\n        <p style=\"margin: 5px 0;\"><strong>Mobil :</strong> mobil</p>\r\n    </div>\r\n    \r\n    <a href=\"http://localhost/rental_mobil-master/bayar.php?id=1762779855\" style=\"display: inline-block; padding: 10px 20px; background-color:  #FF6B35; color: #ffffff; text-decoration: none; border-radius: 5px; font-weight: bold;\">\r\n        <i class=\"fas fa-eye\"></i> &nbsp; Lihat Detail Pesanan\r\n    </a>\r\n</div>', 1, '2025-11-10 13:05:29'),
(188, 5, 0, '<div style=\"font-family: Arial, sans-serif; line-height: 1.6;\">\r\n    <h4 style=\"color: #2c3e50; margin-bottom: 10px;\">\r\n        <i class=\"fas fa-check-circle\" style=\"color: #2ecc71;\"></i> &nbsp; <strong>Pembayaran Diterima</strong>\r\n    </h4>\r\n    <p style=\"margin-bottom: 20px;\">Yeay! Pembayaranmu sudah kami terima 🎉. Pesananmu akan segera kami siapkan. Yuk, segera datang ke toko kami untuk mengambil mobil impianmu 🚗💨.</p>\r\n    \r\n    <div style=\"background-color: #f8f9fa; border-left: 4px solid #3498db; padding: 15px; margin-bottom: 20px;\">\r\n        <h5 style=\"color: #2c3e50; margin-top: 0; margin-bottom: 10px;\">\r\n            <i class=\"fas fa-receipt\" style=\"color: #FF6B35;\"></i> <strong style=\"color: #333;\">Identitas Pesanan</strong>\r\n        </h5>\r\n        <p style=\"margin: 5px 0;\"><strong>No Booking :</strong> 1762861295</p>\r\n        <p style=\"margin: 5px 0;\"><strong>Nama :</strong> khairil affan</p>\r\n        <p style=\"margin: 5px 0;\"><strong>Mobil :</strong> mobil</p>\r\n    </div>\r\n    \r\n    <a href=\"http://localhost/rental_mobil-master/bayar.php?id=1762861295\" style=\"display: inline-block; padding: 10px 20px; background-color:  #FF6B35; color: #ffffff; text-decoration: none; border-radius: 5px; font-weight: bold;\">\r\n        <i class=\"fas fa-eye\"></i> &nbsp; Lihat Detail Pesanan\r\n    </a>\r\n</div>', 0, '2025-11-11 11:42:58');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pembayaran`
--

CREATE TABLE `pembayaran` (
  `id_pembayaran` int(11) NOT NULL,
  `id_booking` int(255) NOT NULL,
  `no_rekening` int(255) NOT NULL,
  `nama_rekening` varchar(255) NOT NULL,
  `nominal` int(255) NOT NULL,
  `tanggal` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `pembayaran`
--

INSERT INTO `pembayaran` (`id_pembayaran`, `id_booking`, `no_rekening`, `nama_rekening`, `nominal`, `tanggal`) VALUES
(3, 1, 2131241, 'Krisna Aldi Waskito', 400000, '2019-12-14'),
(4, 2, 2131241, 'Krisna Aldi Waskito', 400525, '2019-12-18'),
(5, 3, 13213, 'Fauzan Falah', 800743, '2022-01-24'),
(6, 4, 11111, 'rillapan', 10000000, '2025-09-17'),
(7, 5, 11111, 'rillapan', 5, '2025-09-17'),
(8, 6, 2523566, 'MOHAMMAD FARID ATABAQI', 2000000, '2025-09-17'),
(9, 7, 2523566, 'MOHAMMAD FARID ATABAQI', 2000000, '2025-09-17'),
(10, 8, 2523566, 'MOHAMMAD FARID ATABAQI', 2000000, '2025-09-17'),
(11, 9, 2523566, 'rillapan', 2000000, '2025-09-18'),
(12, 11, 11111, 'rillapan', 5, '2025-09-17'),
(13, 12, 11111, 'rillapan', 5, '2025-09-17'),
(14, 13, 11111, 'user1', 5, '2025-09-17'),
(15, 14, 2523566, 'MOHAMMAD FARID ATABAQI', 2000000, '2025-09-18'),
(16, 16, 2523566, 'affan', 2000000, '2025-09-18'),
(17, 17, 2523566, 'affan', 2000000, '2025-09-18'),
(18, 20, 11111, 'user1', 5, '2025-09-17'),
(19, 21, 11111, 'user1', 5, '2025-09-17'),
(20, 22, 11111, 'user1', 5, '2025-09-17'),
(21, 23, 11111, 'user1', 5, '2025-09-17'),
(22, 23, 11111, 'user1', 5, '2025-09-17'),
(23, 24, 11111, 'user1', 5, '2025-09-17'),
(24, 25, 11111, 'user1', 5, '2025-09-17'),
(25, 26, 11111, 'user2', 5, '2025-09-17'),
(26, 27, 11111, 'user2', 5, '2025-09-17'),
(27, 28, 11111, 'user2', 5, '2025-09-17'),
(28, 29, 11111, 'user2', 5, '2025-09-17'),
(29, 30, 11111, 'user2', 5, '2025-09-17'),
(30, 31, 11111, 'user2', 5, '2025-09-17'),
(31, 31, 11111, 'user2', 5, '2025-09-17'),
(32, 32, 11111, 'user2', 5, '2025-09-17'),
(33, 33, 11111, 'user2', 5, '2025-09-17'),
(34, 33, 11111, 'user2', 5, '2025-09-17'),
(35, 34, 11111, 'user2', 5, '2025-09-17'),
(36, 35, 11111, 'user2', 5, '2025-09-17'),
(37, 36, 11111, 'user2', 5, '2025-09-17'),
(38, 37, 11111, 'user2', 5, '2025-09-17'),
(39, 38, 2523566, 'affan', 3000000, '2025-09-20'),
(40, 39, 2523566, 'affan', 2000000, '2025-09-21'),
(41, 40, 2523566, 'affan', 2000000, '2025-09-21'),
(42, 41, 2523566, 'affan', 2000000, '2025-09-21'),
(43, 42, 2523566, 'affan', 2000000, '2025-09-22'),
(44, 43, 11111, 'user2', 5, '2025-09-17'),
(45, 44, 11111, 'user2', 5, '2025-09-17'),
(46, 45, 2523566, 'affan', 2000000, '2025-10-29'),
(47, 47, 2523566, ' hjviuv', 3000000, '2025-10-17'),
(48, 50, 77685, 'affan', 3000000, '2025-10-24'),
(49, 51, 666, 'bvbj', 70000, '2025-10-24'),
(50, 54, 123, 'affan', 100000, '2025-10-24'),
(51, 57, 2523566, 'SEPATU', 3000000, '2025-10-24'),
(52, 63, 2523566, 'SEPATU', 2000000, '2025-11-09'),
(53, 64, 2523566, 'SEPATU', 0, '2025-11-09'),
(54, 66, 2523566, 'affan', 2000000, '2025-11-10'),
(55, 66, 2523566, 'affan', 2000000, '2025-11-10'),
(56, 66, 2523566, 'affan', 2000000, '2025-11-10'),
(57, 67, 2523566, 'affan', 3000000, '2025-11-12'),
(58, 68, 2523566, 'khairil affan', 2000000, '2025-11-10'),
(59, 69, 2523566, 'SEPATU', 2000000, '2025-11-10'),
(60, 70, 2523566, 'SARUNG', 100000, '2025-11-11');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengembalian`
--

CREATE TABLE `pengembalian` (
  `id_pengembalian` int(11) NOT NULL,
  `kode_booking` varchar(255) NOT NULL,
  `tanggal` varchar(255) NOT NULL,
  `denda` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `pengembalian`
--

INSERT INTO `pengembalian` (`id_pengembalian`, `kode_booking`, `tanggal`, `denda`) VALUES
(1, '1762761540', '2025-11-10', 0),
(2, '1762761540', '2025-11-10', 0),
(3, '1762779855', '2025-11-10', 0),
(4, '1762698389', '2025-11-10', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `supir`
--

CREATE TABLE `supir` (
  `id_supir` int(11) NOT NULL,
  `nama_supir` varchar(255) NOT NULL,
  `pengalaman` int(11) NOT NULL COMMENT 'Total pengalaman dalam tahun',
  `deskripsi` text NOT NULL,
  `foto` text NOT NULL,
  `harga_sewa` int(11) NOT NULL COMMENT 'Harga sewa per hari',
  `status` enum('Tersedia','Sedang Digunakan','Close') NOT NULL DEFAULT 'Tersedia'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `supir`
--

INSERT INTO `supir` (`id_supir`, `nama_supir`, `pengalaman`, `deskripsi`, `foto`, `harga_sewa`, `status`) VALUES
(1, 'pak affan', 3, 'affan masih menyupir mobil', '1762778792_6911dea8286e4.jpg', 100000, 'Sedang Digunakan'),
(2, 'pak budi', 3, 'pak budi ', '1761274205_68fae95dc28b4.png', 200000, 'Sedang Digunakan'),
(3, 'pak budi', 4, 'hkkgiug', '1762778737_6911de71110f2.png', 100000, 'Tersedia'),
(4, 'pak seto', 3, 'pak seto baik', '1762778647_6911de17873ea.jpg', 20000, 'Sedang Digunakan');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`id_booking`);

--
-- Indeks untuk tabel `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`id_login`);

--
-- Indeks untuk tabel `mobil`
--
ALTER TABLE `mobil`
  ADD PRIMARY KEY (`id_mobil`);

--
-- Indeks untuk tabel `mobil_gambar`
--
ALTER TABLE `mobil_gambar`
  ADD PRIMARY KEY (`id_gambar`),
  ADD KEY `id_mobil` (`id_mobil`);

--
-- Indeks untuk tabel `notifikasi`
--
ALTER TABLE `notifikasi`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`id_pembayaran`);

--
-- Indeks untuk tabel `pengembalian`
--
ALTER TABLE `pengembalian`
  ADD PRIMARY KEY (`id_pengembalian`);

--
-- Indeks untuk tabel `supir`
--
ALTER TABLE `supir`
  ADD PRIMARY KEY (`id_supir`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `booking`
--
ALTER TABLE `booking`
  MODIFY `id_booking` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT untuk tabel `login`
--
ALTER TABLE `login`
  MODIFY `id_login` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT untuk tabel `mobil`
--
ALTER TABLE `mobil`
  MODIFY `id_mobil` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT untuk tabel `mobil_gambar`
--
ALTER TABLE `mobil_gambar`
  MODIFY `id_gambar` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT untuk tabel `notifikasi`
--
ALTER TABLE `notifikasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=189;

--
-- AUTO_INCREMENT untuk tabel `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `id_pembayaran` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT untuk tabel `pengembalian`
--
ALTER TABLE `pengembalian`
  MODIFY `id_pengembalian` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `supir`
--
ALTER TABLE `supir`
  MODIFY `id_supir` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `mobil_gambar`
--
ALTER TABLE `mobil_gambar`
  ADD CONSTRAINT `mobil_gambar_ibfk_1` FOREIGN KEY (`id_mobil`) REFERENCES `mobil` (`id_mobil`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
