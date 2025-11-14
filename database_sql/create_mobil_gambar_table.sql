CREATE TABLE `mobil_gambar` (
  `id_gambar` int(11) NOT NULL AUTO_INCREMENT,
  `id_mobil` int(11) NOT NULL,
  `nama_gambar` varchar(255) NOT NULL,
  PRIMARY KEY (`id_gambar`),
  KEY `id_mobil` (`id_mobil`),
  CONSTRAINT `mobil_gambar_ibfk_1` FOREIGN KEY (`id_mobil`) REFERENCES `mobil` (`id_mobil`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;