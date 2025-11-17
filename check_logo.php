<?php
require 'koneksi/koneksi.php';
$sql = 'SELECT * FROM infoweb WHERE id = 1';
$result = mysqli_query($koneksi, $sql);
$row = mysqli_fetch_assoc($result);
echo 'Logo: ' . ($row['logo'] ?? 'NULL') . PHP_EOL;
echo 'Nama Rental: ' . $row['nama_rental'] . PHP_EOL;
?>
