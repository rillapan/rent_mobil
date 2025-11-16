<?php
require 'koneksi/koneksi.php';

// Insert dummy booking data for testing
$sql = "INSERT INTO booking (kode_booking, id_login, id_mobil, id_plat, ktp, nama, alamat, no_tlp, tanggal, lama_sewa, total_harga, konfirmasi_pembayaran, tgl_input, id_supir) VALUES
(1234567890, 1, 1, 1, '1234567890123456', 'Affan', 'Jl. Dummy No. 1', '081234567890', CURDATE(), 3, 150000, 'Pembayaran Diterima', NOW(), 1)";

if (mysqli_query($koneksi, $sql)) {
    echo "Dummy booking inserted successfully.";
} else {
    echo "Error: " . mysqli_error($koneksi);
}
?>
