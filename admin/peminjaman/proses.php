<?php

 require '../../koneksi/koneksi.php';

if($_GET['id'] == 'konfirmasi')
{
    $kode_booking = $_POST['kode_booking'];
    $status = $_POST['status'];
    $id_mobil = $_POST['id_mobil'];
    $sql = "UPDATE `mobil` SET `status`= ? WHERE id_mobil= ?";
    $stmt = mysqli_prepare($koneksi, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $status, $id_mobil);
    if (mysqli_stmt_execute($stmt)) {
        echo "<script>window.location='peminjaman.php?id=$kode_booking&status=update_success';</script>";
    } else {
        echo "<script>window.location='peminjaman.php?id=$kode_booking&status=update_error';</script>";
    }
}
