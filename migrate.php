<?php
require 'koneksi/koneksi.php';

$sql_create_table = "
CREATE TABLE `mobil_plat` (
  `id_plat` int(11) NOT NULL AUTO_INCREMENT,
  `id_mobil` int(11) NOT NULL,
  `no_plat` varchar(255) NOT NULL,
  `status_plat` enum('Tersedia','Dipesan') NOT NULL DEFAULT 'Tersedia',
  PRIMARY KEY (`id_plat`),
  KEY `id_mobil` (`id_mobil`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
";

$sql_add_constraint = "
ALTER TABLE `mobil_plat`
  ADD CONSTRAINT `mobil_plat_ibfk_1` FOREIGN KEY (`id_mobil`) REFERENCES `mobil` (`id_mobil`) ON DELETE CASCADE ON UPDATE CASCADE;
";

$sql_alter_table = "
ALTER TABLE `booking` ADD `id_plat` INT(11) NULL DEFAULT NULL AFTER `id_mobil`;
";

if (mysqli_query($koneksi, $sql_create_table)) {
  echo "Table mobil_plat created successfully\n";
} else {
  echo "Error creating table: " . mysqli_error($koneksi) . "\n";
}

if (mysqli_query($koneksi, $sql_add_constraint)) {
  echo "Constraint added to mobil_plat successfully\n";
} else {
  echo "Error adding constraint: " . mysqli_error($koneksi) . "\n";
}

if (mysqli_query($koneksi, $sql_alter_table)) {
  echo "Table booking altered successfully\n";
} else {
  echo "Error altering table: " . mysqli_error($koneksi) . "\n";
}

mysqli_close($koneksi);
?>
