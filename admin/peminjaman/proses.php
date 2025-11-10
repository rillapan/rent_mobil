<?php

 require '../../koneksi/koneksi.php';

if($_GET['id'] == 'konfirmasi')
{
    $kode_booking = $_POST['kode_booking'];
    $status = $_POST['status'];
    $id_mobil = $_POST['id_mobil'];
    $sql = "UPDATE `mobil` SET `status`= ? WHERE id_mobil= ?";
    $stmt = $koneksi->prepare($sql);
    $stmt->bindParam(1, $status);
    $stmt->bindParam(2, $id_mobil);
    if ($stmt->execute()) {
        echo "<script>window.location='peminjaman.php?id=$kode_booking&status=update_success';</script>";
    } else {
        echo "<script>window.location='peminjaman.php?id=$kode_booking&status=update_error';</script>";
    }
}
