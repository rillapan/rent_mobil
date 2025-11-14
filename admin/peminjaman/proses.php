<?php

 require '../../koneksi/koneksi.php';

if($_GET['id'] == 'konfirmasi')
{
    $kode_booking = $_POST['kode_booking'];
    $status = $_POST['status'];
    $id_mobil = $_POST['id_mobil'];
    $status_pengembalian = $_POST['status_pengembalian'] ?? 'Belum Dikembalikan';

    // Update status mobil
    $sql = "UPDATE `mobil` SET `status`= ? WHERE id_mobil= ?";
    $stmt = $koneksi->prepare($sql);
    $stmt->bindParam(1, $status);
    $stmt->bindParam(2, $id_mobil);
    $mobil_updated = $stmt->execute();

    // Update status pengembalian di tabel booking
    $sql_booking = "UPDATE `booking` SET `status_pengembalian`= ? WHERE kode_booking= ?";
    $stmt_booking = $koneksi->prepare($sql_booking);
    $stmt_booking->bindParam(1, $status_pengembalian);
    $stmt_booking->bindParam(2, $kode_booking);
    $booking_updated = $stmt_booking->execute();

    // Jika status pengembalian adalah 'Dikembalikan', insert ke tabel pengembalian dan update status supir
    if ($status_pengembalian == 'Dikembalikan') {
        $tanggal_pengembalian = $_POST['tanggal_pengembalian'] ?? date('Y-m-d');
        $denda = $_POST['denda'] ?? 0;

        $sql_pengembalian = "INSERT INTO `pengembalian` (`kode_booking`, `tanggal`, `denda`) VALUES (?, ?, ?)";
        $stmt_pengembalian = $koneksi->prepare($sql_pengembalian);
        $stmt_pengembalian->bindParam(1, $kode_booking);
        $stmt_pengembalian->bindParam(2, $tanggal_pengembalian);
        $stmt_pengembalian->bindParam(3, $denda);
        $pengembalian_inserted = $stmt_pengembalian->execute();

        // Update status supir kembali ke 'Tersedia' jika ada supir yang digunakan
        $supir_updated = true;
        $sql_supir = "SELECT id_supir FROM booking WHERE kode_booking = ?";
        $stmt_supir_check = $koneksi->prepare($sql_supir);
        $stmt_supir_check->bindParam(1, $kode_booking);
        $stmt_supir_check->execute();
        $booking_data = $stmt_supir_check->fetch(PDO::FETCH_ASSOC);

        if (!empty($booking_data['id_supir'])) {
            $sql_update_supir = "UPDATE supir SET status = 'Tersedia' WHERE id_supir = ?";
            $stmt_update_supir = $koneksi->prepare($sql_update_supir);
            $stmt_update_supir->bindParam(1, $booking_data['id_supir']);
            $supir_updated = $stmt_update_supir->execute();
        }

        if ($mobil_updated && $booking_updated && $pengembalian_inserted && $supir_updated) {
            echo "<script>window.location='peminjaman.php?id=$kode_booking&status=pengembalian_success';</script>";
        } else {
            echo "<script>window.location='peminjaman.php?id=$kode_booking&status=update_error';</script>";
        }
    } else {
        if ($mobil_updated && $booking_updated) {
            echo "<script>window.location='peminjaman.php?id=$kode_booking&status=update_success';</script>";
        } else {
            echo "<script>window.location='peminjaman.php?id=$kode_booking&status=update_error';</script>";
        }
    }
}
