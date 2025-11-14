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
    $stmt = mysqli_prepare($koneksi, $sql);
    mysqli_stmt_bind_param($stmt, "si", $status, $id_mobil);
    $mobil_updated = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // Update status pengembalian di tabel booking
    $sql_booking = "UPDATE `booking` SET `status_pengembalian`= ? WHERE kode_booking= ?";
    $stmt_booking = mysqli_prepare($koneksi, $sql_booking);
    mysqli_stmt_bind_param($stmt_booking, "ss", $status_pengembalian, $kode_booking);
    $booking_updated = mysqli_stmt_execute($stmt_booking);
    mysqli_stmt_close($stmt_booking);

    // Jika status pengembalian adalah 'Dikembalikan', insert ke tabel pengembalian dan update status supir
    if ($status_pengembalian == 'Dikembalikan') {
        $tanggal_pengembalian = $_POST['tanggal_pengembalian'] ?? date('Y-m-d');
        $denda = $_POST['denda'] ?? 0;

        $sql_pengembalian = "INSERT INTO `pengembalian` (`kode_booking`, `tanggal`, `denda`) VALUES (?, ?, ?)";
        $stmt_pengembalian = mysqli_prepare($koneksi, $sql_pengembalian);
        mysqli_stmt_bind_param($stmt_pengembalian, "ssd", $kode_booking, $tanggal_pengembalian, $denda);
        $pengembalian_inserted = mysqli_stmt_execute($stmt_pengembalian);
        mysqli_stmt_close($stmt_pengembalian);

        // Update status supir kembali ke 'Tersedia' jika ada supir yang digunakan
        $supir_updated = true;
        $sql_supir = "SELECT id_supir FROM booking WHERE kode_booking = ?";
        $stmt_supir_check = mysqli_prepare($koneksi, $sql_supir);
        mysqli_stmt_bind_param($stmt_supir_check, "s", $kode_booking);
        mysqli_stmt_execute($stmt_supir_check);
        $result_stmt_supir = mysqli_stmt_get_result($stmt_supir_check);
        $booking_data = mysqli_fetch_assoc($result_stmt_supir);
        mysqli_stmt_close($stmt_supir_check);

        if (!empty($booking_data['id_supir'])) {
            $sql_update_supir = "UPDATE supir SET status = 'Tersedia' WHERE id_supir = ?";
            $stmt_update_supir = mysqli_prepare($koneksi, $sql_update_supir);
            mysqli_stmt_bind_param($stmt_update_supir, "i", $booking_data['id_supir']);
            $supir_updated = mysqli_stmt_execute($stmt_update_supir);
            mysqli_stmt_close($stmt_update_supir);
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
