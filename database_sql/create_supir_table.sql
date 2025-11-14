-- Tabel untuk data supir
CREATE TABLE `supir` (
  `id_supir` int(11) NOT NULL AUTO_INCREMENT,
  `nama_supir` varchar(255) NOT NULL,
  `pengalaman` int(11) NOT NULL COMMENT 'Total pengalaman dalam tahun',
  `deskripsi` text NOT NULL,
  `foto` text NOT NULL,
  `harga_sewa` int(11) NOT NULL COMMENT 'Harga sewa per hari',
  `status` enum('Tersedia','Sedang Digunakan','Close') NOT NULL DEFAULT 'Tersedia',
  PRIMARY KEY (`id_supir`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Contoh data supir
INSERT INTO `supir` (`id_supir`, `nama_supir`, `pengalaman`, `deskripsi`, `foto`, `harga_sewa`, `status`) VALUES
(1, 'Budi Santoso', 5, 'Supir berpengalaman dengan mobil manual dan otomatis.', 'supir1.jpg', 150000, 'Tersedia'),
(2, 'Siti Aminah', 3, 'Supir wanita yang ramah dan terampil.', 'supir2.jpg', 120000, 'Tersedia');
